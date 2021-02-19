<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class lnkCustomerContractToService
 *
 * @package Lib
 * @subpackage itop
 */
class lnkCustomerContractToService extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var CustomerContract
	 */
	private $CustomerContract = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var Service
	 */
	private $Service = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var Service
	 */
	private $SLA = null;
	
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type lnkCustomerContractToService. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return lnkCustomerContractToService
	 */
	static function &creer_lnkCustomerContractToService(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new lnkCustomerContractToService ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return lnkCustomerContractToService
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'lnkCustomerContractToService' ) 
			->setObjetItopCustomerContract ( CustomerContract::creer_CustomerContract ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopService ( Service::creer_Service ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopSLA ( SLA::creer_SLA ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_lnkCustomerContractToService($CustomerContract_name,$Service_name) {
		return $this ->creer_oql ( $CustomerContract_name,$Service_name ) 
			->retrouve_ci ();
	}

	public function creer_oql($CustomerContract_name='',$Service_name='') {
		$where="";
		if(!empty($CustomerContract_name)){
			$where .= " customercontract_name='" . $CustomerContract_name . "'";
		}
		if(!empty($Service_name)){
			if(!empty($where)){
				$where .= " AND ";
			}
			$where .= " service_name='" . $Service_name . "'";
		}
		if(!empty($where)){
			$where = " WHERE".$where;
		}
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . $where );
	}
	
	/**
	 * Creer une entree lnkCustomerContractToService
	 * @param string $CustomerContract_name
	 * @param string $Service_name
	 * @param string $sla_name
	 * @return lnkCustomerContractToService
	 */
	public function gestion_lnkCustomerContractToService(
			$CustomerContract_name,
			$Service_name,
			$sla_name='') {
		$this->onDebug ( __METHOD__, 1 );
		$params = array ();
		$params ['customercontract_id'] = $this->getObjetItopCustomerContract ()
			->creer_oql ( $CustomerContract_name )
			->getOqlCi ();
		$params ['service_id'] = $this->getObjetItopService ()
			->creer_oql ( $Service_name )
			->getOqlCi ();
		if(!empty($sla_name)){
			$params ['sla_id'] = $this->getObjetItopSLA ()
				->creer_oql ( $sla_name )
				->getOqlCi ();
		}
			
		$this->creer_oql ( $CustomerContract_name, $Service_name )
			->creer_ci ( $CustomerContract_name." ".$Service_name, $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return CustomerContract
	 */
	public function &getObjetItopCustomerContract() {
		return $this->CustomerContract;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopCustomerContract(&$CustomerContract) {
		$this->CustomerContract = $CustomerContract;
		
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return CustomerContract
	 */
	public function &getObjetItopService() {
		return $this->Service;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopService(&$Service) {
		$this->Service = $Service;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return CustomerContract
	 */
	public function &getObjetItopSLA() {
		return $this->SLA;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopSLA(&$SLA) {
		$this->SLA = $SLA;
		
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
		$help [__CLASS__] ["text"] [] .= "lnkCustomerContractToService :";
		
		return $help;
	}
}
?>
