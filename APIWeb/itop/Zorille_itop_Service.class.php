<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class Service
 *
 * @package Lib
 * @subpackage itop
 */
class Service extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization
	 */
	private $Organization = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var ServiceFamily
	 */
	private $ServiceFamily = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Instancie un objet de type Service.
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Service
	 */
	static function &creer_Service(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Service ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * @codeCoverageIgnore
	 * Initialisation de l'objet
	 * @param array $liste_class
	 * @return Service
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Service' )
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopServiceFamily ( ServiceFamily::creer_ServiceFamily ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_Service(
			$name) {
		return $this->creer_oql ( $name )
			->retrouve_ci ();
	}

	/**
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Service
	 */
	public function creer_oql(
			$name,
			$fields = array()) {
		$where = "";
		if (! empty ( $name )) {
			$where .= " WHERE name='" . $name . "'";
		}
		return $this->setOqlCi ( "SELECT " . $this->getFormat () . $where );
	}

	/**
	 * Creer une entree Service
	 * @param string $service_name
	 * @param string $org_name
	 * @param string $status
	 * @param string $description
	 * @param string $serviceFamily_name
	 * @return Service
	 */
	public function gestion_Service(
			$service_name,
			$org_name,
			$status,
			$description,
			$serviceFamily_name = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = array (
				'name' => $service_name,
				'description' => $description,
				'status' => $status
		);
		$params ['org_id'] = $this->getObjetItopOrganization ()
			->creer_oql ( $org_name )
			->getOqlCi ();
		if (! empty ( $serviceFamily_name )) {
			$params ['servicefamily_id'] = $this->getObjetItopServiceFamily ()
				->creer_oql ( $serviceFamily_name )
				->getOqlCi ();
		}
		$this->creer_oql ( $service_name )
			->creer_ci ( $service_name, $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopOrganization() {
		return $this->Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization(
			&$Organization) {
		$this->Organization = $Organization;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return ServiceFamily
	 */
	public function &getObjetItopServiceFamily() {
		return $this->ServiceFamily;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopServiceFamily(
			&$ServiceFamily) {
		$this->ServiceFamily = $ServiceFamily;
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
		$help [__CLASS__] ["text"] [] .= "Service :";
		return $help;
	}
}
?>
