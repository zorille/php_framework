<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_LogicalInterface
 *
 * @package Lib
 * @subpackage itop
 */
class itop_LogicalInterface extends itop_ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_VirtualMachine
	 */
	private $itop_VirtualMachine = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_LogicalInterface. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_webservice_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_LogicalInterface
	 */
	static function &creer_itop_LogicalInterface (
			&$liste_option, 
			&$itop_webservice_rest, 
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_LogicalInterface ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"itop_wsclient_rest" => $itop_webservice_rest 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_LogicalInterface
	 */
	public function &_initialise (
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this ->setFormat ( 'LogicalInterface' ) 
			->setObjetItopVirtualMachine ( itop_VirtualMachine::creer_itop_VirtualMachine ( $liste_class ['options'], $liste_class ['itop_wsclient_rest'] ) );
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
	public function __construct (
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_LogicalInterface (
			$name, 
			$server_name) {
		return $this ->creer_oql ( $name, $server_name ) 
			->retrouve_ci ();
	}

	public function creer_oql (
			$name, 
			$server_name = '', 
			$fields = array()) {
		if (! empty ( $server_name )) {
			$name .= " " . $server_name;
		}
		$oql = "SELECT " . $this ->getFormat () . " WHERE friendlyname='" . $name . "'" . $this->prepare_oql_fields($fields);
		return $this ->setOqlCi ( $oql );
	}

	public function gestion_LogicalInterface (
			$name, 
			$server_name, 
			$ipaddress, 
			$macaddress, 
			$ipgateway, 
			$ipmask) {
		$this ->onDebug ( __METHOD__, 1 );
		$params = array (
				'virtualmachine_id' => $this ->getObjetItopVirtualMachine () 
					->creer_oql ( $server_name ) 
					->getOqlCi (),
				'name' => $name,
				'ipaddress' => $ipaddress,
				'macaddress' => $macaddress,
				'ipgateway' => $ipgateway,
				'ipmask' => $ipmask 
		);
		$this ->creer_oql ( $name, $server_name ) 
			->creer_ci ( $name . " " . $server_name, $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return itop_VirtualMachine
	 */
	public function &getObjetItopVirtualMachine () {
		return $this ->itop_VirtualMachine;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopVirtualMachine (
			&$itop_VirtualMachine) {
		$this ->itop_VirtualMachine = $itop_VirtualMachine;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help () {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "itop_LogicalInterface :";
		return $help;
	}
}
?>
