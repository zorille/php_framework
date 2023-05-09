<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;
use Zorille\framework\abstract_log;

/**
 * class ModeleHosts
 *
 * @package Lib
 * @subpackage coservit
 */
class ModeleHosts extends globalapi {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $modeleHosts = array (
			'EXEMPLE' => 1
	);
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $checkTemplates = array (
			'EXEMPLE' => 1
	);

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type ModeleHosts. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ModeleHosts
	 */
	static function &creer_ModeleHosts(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ModeleHosts ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ModeleHosts
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		$this->retrouve_check_template ()
			->retrouve_modeleHost ();
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

	public function retrouve_check_template() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_modeleHosts = array ();
		$donnee_coservit = $this->_valideOption ( array (
				"coservit",
				"checkTemplates",
				"check"
		) );
		$this->onDebug ( $donnee_coservit, 2 );
		// Si il n'y a qu'une donnee, on recree le tableau
		if (isset ( $donnee_coservit ["nom"] ) && isset ( $donnee_coservit ["id"] )) {
			$donnee_coservit = array (
					"0" => $donnee_coservit
			);
		}
		foreach ( $donnee_coservit as $modeleHost ) {
			$liste_modeleHosts [strtoupper ( $modeleHost ['nom'] )] = $modeleHost ['id'];
		}
		$this->onDebug ( $liste_modeleHosts, 1 );
		return $this->setCheckTemplates ( $liste_modeleHosts );
	}

	/**
	 * ******************************* ModeleHosts URI ******************************
	 */
	public function modeleHost_uri() {
		return $this->globalapi_uri () . '/host_templates';
	}

	/**
	 * ******************************* Coservit ModeleHosts *********************************
	 */
	public function retrouve_id_modeleHost(
			$modeleHosts) {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getModeleHost () )) {
			$this->retrouve_modeleHost ();
		}
		if (isset ( $this->getModeleHost () [strtoupper ( $modeleHosts )] )) {
			return $this->getModeleHost () [strtoupper ( $modeleHosts )];
		}
		return $this->onError ( "Le modele de host " . $modeleHosts . " n'existe pas dans la liste", "", 1 );
	}

	public function prepare_modeleHost() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_modeleHosts = array ();
		if (isset ( $this->getDonnees ()->_embedded->items )) {
			foreach ( $this->getDonnees ()->_embedded->items as $modeleHost ) {
				$liste_modeleHosts [mb_strtoupper ( $modeleHost->name )] = $modeleHost->id;
			}
		}
		$this->onDebug ( $liste_modeleHosts, 1 );
		return $this->setmodeleHost ( $liste_modeleHosts );
	}

	public function retrouve_modeleHost(
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
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->modeleHost_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_modeleHost ();
	}

	public function retrouve_id_checkTemplate(
			$CheckTemplate) {
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( $this->getCheckTemplates (), 2 );
		if (isset ( $this->getCheckTemplates () [strtoupper ( $CheckTemplate )] )) {
			return $this->getCheckTemplates () [strtoupper ( $CheckTemplate )];
		}
		return $this->onError ( "L'id du check_template " . $CheckTemplate . " n'existe pas dans la liste", "", 1 );
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return \Zorille\coservit\Company
	 */
	public function creerModeleHosts(
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
	public function getModeleHost() {
		return $this->modeleHosts;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setModeleHost(
			$liste_modeleHosts) {
		$this->modeleHosts = $liste_modeleHosts;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getCheckTemplates() {
		return $this->checkTemplates;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCheckTemplates(
			$checkTemplates) {
		$this->checkTemplates = $checkTemplates;
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
		$help [__CLASS__] ["text"] [] .= "ModeleHosts :";
		return $help;
	}
}
?>
