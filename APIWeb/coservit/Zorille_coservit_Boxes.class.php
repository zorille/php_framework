<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;
use Zorille\framework\abstract_log;

/**
 * class Boxe
 *
 * @package Lib
 * @subpackage coservit
 */
class Boxes extends item {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $boxe = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Boxe. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return $this
	 */
	static function &creer_Boxes(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Boxes ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return $this
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
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format coservit
	 */
	public function prepare_params_Boxe(
			$parametres) {
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				default :
			}
		}
		return $params;
	}

	/**
	 * ******************************* Boxe URI ******************************
	 */
	public function boxes_uri() {
		return $this->globalapi_uri () . '/boxes';
	}

	public function boxe_conf_uri() {
		return $this->boxes_uri () . '/configurations';
	}

	/**
	 * ******************************* Coservit Boxe *********************************
	 */
	public function retrouve_id_boxe(
			$Boxe,
			$company_id = 2) {
		$this->onDebug ( __METHOD__, 1 );
		if ($company_id != 2) {
			$this->onDebug ( "Company ID : ".$company_id, 2 );
			$this->retrouve_Boxes ( $company_id );
		}
		if (isset ( $this->getBoxes () [strtoupper ( $Boxe )] )) {
			return $this->getBoxes () [strtoupper ( $Boxe )];
		}
		return $this->onError ( "Le Collecteur " . $Boxe . " n'existe pas dans la liste", "", 1 );
	}

	public function prepare_Boxes() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_Boxe = array ();
		if (isset ( $this->getDonnees ()->_embedded->items )) {
			foreach ( $this->getDonnees ()->_embedded->items as $Boxe ) {
				$liste_Boxe [mb_strtoupper ( $Boxe->name )] = $Boxe->id;
			}
		}
		$this->onDebug ( $liste_Boxe, 1 );
		return $this->setBoxes ( $liste_Boxe );
	}

	public function retrouve_Boxes(
			$company_id = 2,
			$params = array (
					"company" => array (
							1,
							2
					),
					"inheritance" => true,
					"limit" => 1000,
					"sort" => array (
							"+name"
					)
			)) {
		$this->onDebug ( __METHOD__, 1 );
		if (! in_array ( $company_id, $params ["company"] )) {
			$params ["company"] [] .= $company_id;
		}
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->boxes_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_Boxes ();
	}
	
	public function verifie_presence_boxes(){
		if(empty($this->getBoxes ())){
			$this->retrouve_Boxes();
		}
		return $this;
	}

	/**
	 * Met a jour les configurations de toutes les boxes de collecte
	 * @param array $parametres
	 * @return $this
	 */
	public function updateConfigurationToutesBoxes() {
		$parametres = array (
				"collectorIds" => array ()
		);
		foreach ( $this->verifie_presence_boxes()->getBoxes () as $boxe_id ) {
			$parametres ["collectorIds"] [] = $boxe_id;
		}
		return $this->updateConfiguration ( $parametres );
	}

	/**
	 * Met a jour les configurations des boxes de collecte
	 * @param array $parametres
	 * @return $this
	 */
	public function updateConfiguration(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$this->setMandatory ( array (
				"collectorIds" => false
		) );
		$params = $this->prepare_params_Boxe ( $parametres );
		$this->onDebug ( $params, 1 );
		$this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->postMethod ( $this->boxe_conf_uri (), $params );
		return $this;
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return \Zorille\coservit\Company
	 */
	public function creerBoxe(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getBoxes() {
		return $this->boxe;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBoxes(
			$liste_boxe) {
		$this->boxe = $liste_boxe;
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
		$help [__CLASS__] ["text"] [] .= "Boxes :";
		return $help;
	}
}
?>
