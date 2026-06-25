<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class item
 *
 * @package Lib
 * @subpackage evobserve
 */
abstract class item extends globalapi {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $format = '';
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $id = '';
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $mandatory = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var boolean
	 */
	private $update = false;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return item
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Enregistre les donnees class, key et fields du premier objet de la reponse REST
	 * @param array $item Retour d'un requete REST sur evobserve
	 * @return $this
	 */
	public function enregistre_item_a_partir_rest(
		array $item): static {
		foreach ( $item ['objects'] as $donnees ) {
			$this->setFormat ( $donnees ['class'] )
				->setId ( $donnees ['key'] )
				->setDonnees ( $donnees ['fields'] );
			break;
		}
		return $this;
	}

	/**
	 * Permet de trouver un CI dans evobserve a partir d'une requete OQL et enregistre les donnees du CI dans l'objet
	 * @return item|bool
	 * @throws Exception
	 */
	public function retrouve_item(): static|bool {
		// Si il y a deja un objet item, alors le item existe
		if ($this->getDonnees ()) {
			return $this;
		}
		// Sinon, on requete iTop
		$item = $this->recupere_item_dans_evobserve ();
		if ($item ['message'] != 'Found: 1') {
			// Le item n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la requete : " . $this->getOqlCi () . " : " . $item ['message'] );
		}
		return $this->enregistre_item_a_partir_rest ( $item );
	}

	/**
	 * Valide que le CI existe et est unique dans evobserve et enregistre les donnees du CI dans l'objet s'il est trouve
	 * @return item|null
	 */
	public function valide_item_existe(): ?static {
		// Si il y a deja un objet item, alors le item existe
		if ($this->getDonnees ()) {
			return $this;
		}
		// Sinon, on requete iTop
		$item = $this->recupere_item_dans_evobserve ();
		if ($item ['message'] != 'Found: 1') {
			// Le item n'existe pas
			$this->onDebug ( "Probleme avec la requete : " . $this->getOqlCi () . " : " . $item ['message'], 1 );
			return null;
		}
		return $this->enregistre_item_a_partir_rest ( $item );
	}

	/**
	 * Creer un CI dans evobserve du format de l'objet
	 * @param string $name
	 * @param array $params
	 * @return $this
	 * @throws Exception
	 */
	public function creer_item(
		string $name,
		array  $params): static {
		$this->onDebug ( __METHOD__, 1 );
		if (! $this->valide_item_existe ()) {
			$this->onInfo ( "Ajout de : " . $name );
			$item = $this->getObjetEvobserveWsclientRest ()
				->core_create ( $this->getFormat (), '', $params );
			$this->enregistre_item_a_partir_rest ( $item );
		} else if ($this->getUpdate ()) {
			$this->onInfo ( "Update de : " . $name );
			$item = $this->getObjetEvobserveWsclientRest ()
				->core_update ( $this->getFormat (), $this->getId (), $params );
			$this->enregistre_item_a_partir_rest ( $item );
		}
		return $this;
	}

	/**
	 * Creer un CI dans evobserve du format de l'objet
	 * @param string $name
	 * @param array $params
	 * @return $this
	 * @throws Exception
	 */
	public function update_item(
		string $name,
		array  $params): static {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_item_existe ()) {
			$this->onInfo ( "Update de : " . $name );
			$item = $this->getObjetEvobserveWsclientRest ()
				->core_update ( $this->getFormat (), $this->getId (), $params );
			$this->enregistre_item_a_partir_rest ( $item );
		}
		return $this;
	}
	
	/**
	 * reset les informations dynamiques de l'objet
	 * @return $this
	 */
	public function reset_donnees(): static {
		return $this->setId ( "" )
		->setDonnees ( array () );
	}

	/**
	 * Valide si tous les champs nécessaires sont remplis avec une données
	 * @return item|bool
	 * @throws Exception
	 */
	public function valide_mandatory_fields(): static|bool {
		$this->onDebug ( __METHOD__, 1 );
		$retour = array ();
		foreach ( $this->getMandatory () as $champ => $valeur ) {
			if ($valeur === false) {
				$retour [] .= $champ;
			}
		}
		if (count ( $retour ) != 0) {
			return $this->onError ( "Il manque des champs obligatoires : ", $retour );
		}
		return $this;
	}

	/**
	 * Valide que valeur a des donnees et que le champ esr Mandatory
	 * @param string $champ
	 * @param mixed $valeur
	 * @return true
	 */
	public function valide_mandatory_field_filled(
		string $champ,
		mixed $valeur): bool {
		if (isset ( $this->getMandatory () [$champ] ) && (! empty ( $valeur ) || $valeur===0)) {
			$this->setMandatoryField ( $champ );
		}
		return true;
	}

	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_standard_params(
		array $parametres): array {
		$params = array ();
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'id' :
					$this->setId($valeur);
					$this->valide_mandatory_field_filled ( $champ, $valeur );
					break;
				default :
					$this->valide_mandatory_field_filled ( $champ, $valeur );
					$params [$champ] = $valeur;
			}
		}
		return $params;
	}

	/**
	 * Prepare les parametres obligatoire d'un objet. Necessite les champ type org_id deja rempli correctement.
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_obligatoire(
		array $parametres): array {
		$params = array ();
		foreach ( $this->getMandatory () as $champ => $inutile ) {
			if (isset ( $parametres [$champ] )) {
				$params [$champ] = $parametres [$champ];
			}
		}
		return $params;
	}

	/**
	 * Verifie qu'un item id est remplit/existe
	 * @param bool $error
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_item_id(bool $error=true): bool {
		if (empty ( $this->getId () )) {
			$this->onDebug ( $this->getId (), 2 );
			if($error){
				$this->onError ( "Il faut un item id pour travailler" );
			}
			return false;
		}
		return true;
	}
	
	/**
	 * ******************************* Global URI ******************************
	 */
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getFormat(): string {
		return $this->format;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFormat(
			$format): static {
		$this->format = $format;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getId(): string {
		return $this->id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setId(
			$id): static {
		$this->id = $id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMandatory(): array {
		return $this->mandatory;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMandatory(
			$mandatory): static {
		if (is_array ( $mandatory )) {
			$this->mandatory = $mandatory;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $field
	 * @return item
	 */
	public function &setMandatoryField(
		string $field): static {
		if (isset ( $this->mandatory [$field] )) {
			$this->mandatory [$field] = true;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUpdate(): bool {
		return $this->update;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUpdate(
			$update): static {
		$this->update = $update;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "item :";
		return $help;
	}
}
