<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class item
 *
 * @package Lib
 * @subpackage coservit
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
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Enregistre les donnees class, key et fields du premier objet de la reponse REST
	 * @param array $item Retour d'un requete REST sur coservit
	 * @return $this
	 */
	public function enregistre_item_a_partir_rest(
			$item) {
		foreach ( $item ['objects'] as $donnees ) {
			$this->setFormat ( $donnees ['class'] )
				->setId ( $donnees ['key'] )
				->setDonnees ( $donnees ['fields'] );
			break;
		}
		return $this;
	}

	/**
	 * Permet de trouver un CI dans coservit a partir d'une requete OQL et enregistre les donnees du CI dans l'objet
	 * @return $this
	 * @throws Exception
	 */
	public function retrouve_item() {
		// Si il y a deja un objet item, alors le item existe
		if ($this->getDonnees ()) {
			return $this;
		}
		// Sinon, on requete iTop
		$item = $this->recupere_item_dans_coservit ();
		if ($item ['message'] != 'Found: 1') {
			// Le item n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la requete : " . $this->getOqlCi () . " : " . $item ['message'] );
		}
		return $this->enregistre_item_a_partir_rest ( $item );
	}

	/**
	 * Valide que le CI existe et est unique dans coservit et enregistre les donnees du CI dans l'objet s'il est trouve
	 * @return $this|null
	 */
	public function valide_item_existe() {
		// Si il y a deja un objet item, alors le item existe
		if ($this->getDonnees ()) {
			return $this;
		}
		// Sinon, on requete iTop
		$item = $this->recupere_item_dans_coservit ();
		if ($item ['message'] != 'Found: 1') {
			// Le item n'existe pas
			$this->onDebug ( "Probleme avec la requete : " . $this->getOqlCi () . " : " . $item ['message'], 1 );
			return null;
		}
		return $this->enregistre_item_a_partir_rest ( $item );
	}

	/**
	 * Creer un CI dans coservit du format de l'objet
	 * @param string $name
	 * @param array $params
	 * @return $this
	 * @throws Exception
	 */
	public function creer_item(
			$name,
			$params) {
		$this->onDebug ( __METHOD__, 1 );
		if (! $this->valide_item_existe ()) {
			$this->onInfo ( "Ajout de : " . $name );
			$item = $this->getObjetCoservitWsclientRest ()
				->core_create ( $this->getFormat (), '', $params );
			$this->enregistre_item_a_partir_rest ( $item );
		} else if ($this->getUpdate ()) {
			$this->onInfo ( "Update de : " . $name );
			$item = $this->getObjetCoservitWsclientRest ()
				->core_update ( $this->getFormat (), $this->getId (), $params );
			$this->enregistre_item_a_partir_rest ( $item );
		}
		return $this;
	}

	/**
	 * Creer un CI dans coservit du format de l'objet
	 * @param string $name
	 * @param array $params
	 * @return $this
	 * @throws Exception
	 */
	public function update_item(
			$name,
			$params) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_item_existe ()) {
			$this->onInfo ( "Update de : " . $name );
			$item = $this->getObjetCoservitWsclientRest ()
				->core_update ( $this->getFormat (), $this->getId (), $params );
			$this->enregistre_item_a_partir_rest ( $item );
		}
		return $this;
	}
	
	/**
	 * reset les informations dynamiques de l'objet
	 * @return $this
	 */
	public function reset_donnees() {
		return $this->setId ( "" )
		->setDonnees ( array () );
	}

	/**
	 * Valide si tous les champs nécessaires sont remplis avec une données
	 * @param array $mandatory
	 * @return $this
	 * @throws Exception
	 */
	public function valide_mandatory_fields() {
		$this->onDebug ( __METHOD__, 1 );
		$retour = array ();
		foreach ( $this->getMandatory () as $champ => $valeur ) {
			if ($valeur === false) {
				$retour [] .= $champ;
			}
		}
		if (count ( $retour ) != 0) {
			return $this->onError ( "Il manque des champs obligatoires : ", $retour, 1 );
		}
		return $this;
	}

	/**
	 * Valide que valeur a des donnees et que le champ esr Mandatory
	 * @param string $champ
	 * @param string $valeur
	 * @return $this
	 */
	public function valide_mandatory_field_filled(
			$champ,
			$valeur) {
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
			$parametres) {
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
			$parametres) {
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
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_item_id($error=true) {
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
	public function getFormat() {
		return $this->format;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFormat(
			$format) {
		$this->format = $format;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setId(
			$id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMandatory() {
		return $this->mandatory;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMandatory(
			$mandatory) {
		if (is_array ( $mandatory )) {
			$this->mandatory = $mandatory;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $field
	 * @return \Zorille\coservit\item
	 */
	public function &setMandatoryField(
			$field) {
		if (isset ( $this->mandatory [$field] )) {
			$this->mandatory [$field] = true;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUpdate() {
		return $this->update;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUpdate(
			$update) {
		$this->update = $update;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "item :";
		return $help;
	}
}
?>
