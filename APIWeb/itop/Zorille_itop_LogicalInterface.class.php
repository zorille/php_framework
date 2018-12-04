<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class LogicalInterface
 *
 * @package Lib
 * @subpackage itop
 */
class LogicalInterface extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var VirtualMachine
	 */
	private $VirtualMachine = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type LogicalInterface. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return LogicalInterface
	 */
	static function &creer_LogicalInterface (
			&$liste_option, 
			&$webservice_rest, 
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new LogicalInterface ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return LogicalInterface
	 */
	public function &_initialise (
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this ->setFormat ( 'LogicalInterface' ) 
			->setObjetItopVirtualMachine ( VirtualMachine::creer_VirtualMachine ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * @return VirtualMachine
	 */
	public function &getObjetItopVirtualMachine () {
		return $this ->VirtualMachine;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopVirtualMachine (
			&$VirtualMachine) {
		$this ->VirtualMachine = $VirtualMachine;
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
		$help [__CLASS__] ["text"] [] .= "LogicalInterface :";
		return $help;
	}
}
?>
