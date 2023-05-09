<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;
use Zorille\framework\abstract_log;

/**
 * class ModeleServices
 *
 * @package Lib
 * @subpackage coservit
 */
class ModeleServices extends globalapi {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $modeleServices = array (
			'EXEMPLE' => 1
	);

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type ModeleServices. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ModeleServices
	 */
	static function &creer_ModeleServices(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ModeleServices ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ModeleServices
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		$this->retrouve_ModeleServices ();
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
	 * ******************************* ModeleServices URI ******************************
	 */
	public function ModeleServices_uri() {
		return $this->globalapi_uri () . '/service_templates';
	}

	/**
	 * ******************************* Coservit ModeleServices *********************************
	 */
	public function retrouve_id_ModeleServices(
			$ModeleServices) {
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( "Modele recherche : " . $ModeleServices, 1 );
		if (empty ( $this->getModeleServices () )) {
			$this->retrouve_ModeleServices ();
		}
		if (isset ( $this->getModeleServices () [strtoupper ( $ModeleServices )] )) {
			return $this->getModeleServices () [strtoupper ( $ModeleServices )];
		}
		return $this->onError ( "Le modele de services " . $ModeleServices . " n'existe pas dans la liste", "", 1 );
	}

	public function prepare_ModeleServices() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_ModeleServices = array ();
		if (isset ( $this->getDonnees ()->_embedded->items )) {
			foreach ( $this->getDonnees ()->_embedded->items as $ModeleService ) {
				$liste_ModeleServices [mb_strtoupper ( trim ( $ModeleService->name ) )] = $ModeleService->id;
			}
		}
		$this->onDebug ( $liste_ModeleServices, 1 );
		return $this->setModeleServices ( $liste_ModeleServices );
	}

	public function retrouve_ModeleServices(
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
			->getMethod ( $this->ModeleServices_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_ModeleServices ();
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return $this
	 */
	public function creerModeleServices(
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
	public function getModeleServices() {
		return $this->modeleServices;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setModeleServices(
			$liste_modeleServices) {
		$this->modeleServices = $liste_modeleServices;
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
		$help [__CLASS__] ["text"] [] .= "ModeleServices :";
		return $help;
	}
}
?>
