<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class CustomerContract
 *
 * @package Lib
 * @subpackage itop
 */
class CustomerContract extends ci {
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
	 * @var Organization
	 */
	private $Provider = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type CustomerContract. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return CustomerContract
	 */
	static function &creer_CustomerContract(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomerContract ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CustomerContract
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'CustomerContract' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopProvider ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_CustomerContract($name,$org_name) {
		return $this ->creer_oql ( $name,$org_name ) 
			->retrouve_ci ();
	}

	public function creer_oql($name='',$org_name='') {
		$where="";
		if(!empty($name)){
			$where .= " name='" . $name . "'";
		}
		if(!empty($org_name)){
			if(!empty($where)){
				$where .= " AND ";
			}
			$where .= " organization_name='" . $org_name . "'";
		}
		if(!empty($where)){
			$where = " WHERE".$where;
		}
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . $where );
	}
	
	/**
	 * Creer une entree CustomerContract
	 * @param string $CustomerContract_name
	 * @param string $org_name
	 * @param string $status
	 * @param string $provider_name
	 * @param string $description
	 * @return CustomerContract
	 */
	public function gestion_CustomerContract(
			$CustomerContract_name,
			$org_name,
			$status,
			$provider_name,
			$description="",
			$start_date="",
			$end_date="",
			$cost="",
			$cost_currency="",
			$cost_unit="",
			$billing_frequency="") {
		$this->onDebug ( __METHOD__, 1 );
		$params = array (
				'name' => $CustomerContract_name,
				'description' => $description,
				'status' => $status,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'cost' => $cost,
				'cost_currency' => $cost_currency,
				'cost_unit' => $cost_unit,
				'billing_frequency' => $billing_frequency
		);
		$params ['org_id'] = $this->getObjetItopOrganization ()
			->creer_oql ( $org_name )
			->getOqlCi ();
		$params ['provider_id'] = $this->getObjetItopProvider ()
			->creer_oql ( $provider_name )
			->getOqlCi ();
			
		$this->creer_oql ( $CustomerContract_name, $org_name )
			->creer_ci ( $CustomerContract_name, $params );
		return $this;
	}
	
	/**
	 * Creer une entree CustomerContract
	 * @param string $CustomerContract_name
	 * @param string $org_name
	 * @param string $status
	 * @param string $provider_name
	 * @param string $description
	 * @return CustomerContract
	 */
	public function gestion_CustomerContract_Euclyde(
			$CustomerContract_name,
			$org_name,
			$status,
			$provider_name,
			$description="",
			$start_date="",
			$end_date="",
			$cost="",
			$cost_currency="",
			$cost_unit="",
			$billing_frequency="",
			$remise=0) {
		$this->onDebug ( __METHOD__, 1 );
		$params = array (
				'name' => $CustomerContract_name,
				'description' => $description,
				'status' => $status,
				'start_date' => $start_date,
				'end_date' => $end_date,
				'cost' => $cost,
				'cost_currency' => $cost_currency,
				'cost_unit' => $cost_unit,
				'billing_frequency' => $billing_frequency,
				'remise'=>$remise
		);
		$params ['org_id'] = $this->getObjetItopOrganization ()
			->creer_oql ( $org_name )
			->getOqlCi ();
		$params ['provider_id'] = $this->getObjetItopProvider ()
			->creer_oql ( $provider_name )
			->getOqlCi ();
			
		$this->creer_oql ( $CustomerContract_name, $org_name )
			->creer_ci ( $CustomerContract_name, $params );
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
	public function &setObjetItopOrganization(&$Organization) {
		$this->Organization = $Organization;
		
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopProvider() {
		return $this->Provider;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopProvider(&$Provider) {
		$this->Provider = $Provider;
		
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
		$help [__CLASS__] ["text"] [] .= "CustomerContract :";
		
		return $help;
	}
}
?>
