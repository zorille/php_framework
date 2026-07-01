<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception as Exception;

/**
 * class item
 *
 * @package Lib
 * @subpackage otrs
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
	 * Prepare les parametres standards d'un objet OTRS.
	 * @param array $parametres
	 * @return array
	 */
	public function prepare_standard_params(
		array $parametres): array {
		$params = array ();
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'id' :
					$this->setId ( $valeur );
					break;
				default :
					$params [$champ] = $valeur;
			}
		}
		$this->valide_mandatory_fields_filled ( $params );
		return $params;
	}

	/**
	 * Appelle une operation OTRS en POST.
	 * @param string $operation
	 * @param array $parametres
	 * @return $this
	 * @throws Exception
	 */
	public function post_operation(
		string $operation,
		array  $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_standard_params ( $parametres );
		$this->onDebug ( $params, 2 );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->operation_uri ( $operation ), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Enregistre l'identifiant principal retourne par OTRS.
	 * @param mixed $resultat
	 * @return $this
	 */
	public function enregistre_id_depuis_resultat(
		mixed $resultat): static {
		if (isset ( $resultat->TicketID )) {
			$this->setId ( $resultat->TicketID );
		} else if (isset ( $resultat->ConfigItemID )) {
			$this->setId ( $resultat->ConfigItemID );
		} else if (isset ( $resultat->CustomerUserLogin )) {
			$this->setId ( $resultat->CustomerUserLogin );
		} else if (isset ( $resultat->CustomerID )) {
			$this->setId ( $resultat->CustomerID );
		}
		return $this;
	}

	/**
	 * Valide que valeur a des donnees et que le champ est Mandatory.
	 * @param string $champ
	 * @param mixed $valeur
	 * @return true
	 */
	public function valide_mandatory_field_filled(
		string $champ,
		mixed  $valeur): bool {
		if (isset ( $this->getMandatory () [$champ] ) && (! empty ( $valeur ) || $valeur === 0 || $valeur === '0' )) {
			$this->setMandatoryField ( $champ );
		}
		return true;
	}

	/**
	 * Valide recursivement les champs obligatoires remplis.
	 * @param array $parametres
	 * @param string $prefix
	 * @return $this
	 */
	public function valide_mandatory_fields_filled(
		array  $parametres,
		string $prefix = ''): static {
		foreach ( $parametres as $champ => $valeur ) {
			$nom_champ = $prefix === '' ? $champ : $prefix . '.' . $champ;
			$this->valide_mandatory_field_filled ( $nom_champ, $valeur );
			if (is_array ( $valeur )) {
				$this->valide_mandatory_fields_filled ( $valeur, $nom_champ );
			}
		}
		return $this;
	}

	/**
	 * Valide si tous les champs necessaires sont remplis.
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
	 * Verifie qu'un item id est remplit/existe.
	 * @param bool $error
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_item_id(
		bool $error = true): bool {
		if (empty ( $this->getId () )) {
			$this->onDebug ( $this->getId (), 2 );
			if ($error) {
				$this->onError ( "Il faut un item id pour travailler" );
			}
			return false;
		}
		return true;
	}

	/**
	 * ******************************* URI ******************************
	 */
	public function operation_uri(
		string $operation): string {
		return $this->globalapi_uri () . '/' . $operation;
	}

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
