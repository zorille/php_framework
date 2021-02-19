<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class lnkContactToContract
 *
 * @package Lib
 * @subpackage itop
 */
class lnkContactToContract extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Contact
	 */
	private $Contact = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var CustomerContract
	 */
	private $CustomerContract = null;
	
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type lnkContactToContract. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return lnkContactToContract
	 */
	static function &creer_lnkContactToContract(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new lnkContactToContract ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return lnkContactToContract
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'lnkContactToContract' ) 
			->setObjetItopContact ( Contact::creer_Contact ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopCustomerContract ( CustomerContract::creer_CustomerContract ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_lnkContactToContract($Contract_name,$Contact_name) {
		return $this ->creer_oql ( $Contract_name,$Contact_name ) 
			->retrouve_ci ();
	}

	public function creer_oql($Contract_name='', $Contact_name='',$Contact_email='', $org_name='') {
		$where="";
		$join="";
		if(!empty($Contract_name)){
			$where .= " contract_name='" . $Contract_name . "'";
		}
		if(!empty($Contact_name)||!empty($Contact_email)||!empty($org_name)){
			$person=$this->getObjetItopContact () ->getFormat ();
			$join=" JOIN ".$person. " ON ".$this->getFormat ().".contact_id=".$person.".id ";
			if(!empty($Contact_name)){
				if(!empty($where)){
					$where .= " AND ";
				}
				$where .= " ".$person.".name='" . $Contact_name . "'";
			}
			if(!empty($Contact_email)){
				if(!empty($where)){
					$where .= " AND ";
				}
				$where .= " ".$person.".email='" . $Contact_email . "'";
			}
			if(!empty($org_name)){
				if(!empty($where)){
					$where .= " AND ";
				}
				$where .= " ".$person.".org_name='" . $org_name . "'";
			}
		}
		if(!empty($where)){
			$where = " WHERE".$where;
		}
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . $join . $where );
	}
	
	/**
	 * Creer une entree lnkContactToContract
	 * @param string $Contact_name
	 * @param string $Contact_email
	 * @param string $Contract_name Role
	 * @return lnkContactToContract
	 */
	public function gestion_lnkContactToContract(
			$Contract_name,
			$Contact_name,
			$Contact_email,
			$org_name='') {
		$this->onDebug ( __METHOD__, 1 );
		$params = array ();
		$params ['contact_id'] = $this->getObjetItopContact ()
			->creer_oql ( $Contact_name, $Contact_email, $org_name )
			->getOqlCi ();
		$params ['contract_id'] = $this->getObjetItopCustomerContract ()
			->creer_oql ( $Contract_name, $org_name )
			->getOqlCi ();
			
		$this->creer_oql ( $Contract_name, $Contact_name, $Contact_email, $org_name )
			->creer_ci ( $Contract_name." ".$Contact_name, $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Contact
	 */
	public function &getObjetItopContact() {
		return $this->Contact;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopContact(&$Contact) {
		$this->Contact = $Contact;
		
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return Contact
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
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "lnkContactToContract :";
		
		return $help;
	}
}
?>
