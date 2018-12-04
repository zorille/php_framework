<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class ServiceFamily
 *
 * @package Lib
 * @subpackage itop
 */
class ServiceFamily extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Service
	 */
	private $Service = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Instancie un objet de type ServiceFamily.
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ServiceFamily
	 */
	static function &creer_ServiceFamily(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ServiceFamily ( $sort_en_erreur, $entete );
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
	 * @return ServiceFamily
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'ServiceFamily' );
		// L'instanciation de Service genere une boucle infini avec ServiceFamily. Donc on lie le ServiceFamily uniquement dans le Service
		// ->setObjetItopService ( Service::creer_Service ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_ServiceFamily(
			$name) {
		return $this->creer_oql ( $name )
			->retrouve_ci ();
	}

	/**
	 * @param string $name Nom du ServiceFamily
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return ServiceFamily
	 */
	public function creer_oql(
			$name = '') {
		$where = "";
		if (! empty ( $name )) {
			$where .= " WHERE name='" . $name . "'";
		}
		return $this->setOqlCi ( "SELECT " . $this->getFormat () . $where );
	}

	/**
	 * Creer une entree ServiceFamily
	 * @param string $name Nom de la famille de service
	 * @param string $service_name Nom de service existant
	 * @return ServiceFamily
	 */
	public function gestion_ServiceFamily(
			$name,
			$service_name = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = array (
				'name' => $name
		);
		// Non utilisable en l'etat
		// if (! empty ( $service_name )) {
		// $params ['services_list'] = $this->getObjetItopService ()
		// ->creer_oql ( $service_name )
		// ->getOqlCi ();
		// }
		$this->creer_oql ( $name )
			->creer_ci ( $name, $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Service
	 */
	public function &getObjetItopService() {
		return $this->Service;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopService(
			&$Service) {
		$this->Service = $Service;
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
		$help [__CLASS__] ["text"] [] .= "ServiceFamily :";
		return $help;
	}
}
?>
