<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Zorille\framework as Core;

/**
 * class WebApplication
 *
 * @package Lib
 * @subpackage itop
 */
class WebApplication extends FunctionalCI {
	/**
	 * var privee
	 *
	 * @access private
	 * @var WebServer
	 */
	private $WebServer = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type WebApplication. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return WebApplication
	 */
	static function &creer_WebApplication(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new WebApplication ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return WebApplication
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'WebApplication' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopWebServer ( WebServer::creer_WebServer ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return Organization
	 */
	public function &champ_obligatoire_standard() {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'org_id' => false,
					'webserver_id' => false
			) );
		}
		return $this;
	}

	public function retrouve_WebApplication(
			$name) {
		return $this->creer_oql ( array (
				'friendlyname' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_WebApplication(
			$parametres) {
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'webserver_name' :
					$params ['webserver_id'] = $this->getObjetItopWebServer ()
						->creer_oql ( array (
							'fiendlyname' => $valeur,
							'org_id' => $params ['org_id']
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'webserver_id', $params ['webserver_id'] );
					if (isset ( $params ['webserver_name'] )) {
						unset ( $params ['webserver_name'] );
					}
					break;
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return WebApplication
	 */
	public function creer_oql_WebApplication(
			$fields = array ()) {
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'org_id' :
					$filtre ['org_name'] = $fields ['org_name'];
					break;
				case 'webserver_id' :
					$filtre ['webserver_name'] = $fields ['webserver_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs standards : name, org_name,webserver_name, business_criticity, move2production
	 * @return WebApplication
	 */
	public function gestion_WebApplication(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_WebApplication ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_WebApplication ( $parametres )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return WebServer
	 */
	public function &getObjetItopWebServer() {
		return $this->WebServer;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopWebServer(
			&$WebServer) {
		$this->WebServer = $WebServer;
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
		$help [__CLASS__] ["text"] [] .= "WebApplication :";
		return $help;
	}
}
?>
