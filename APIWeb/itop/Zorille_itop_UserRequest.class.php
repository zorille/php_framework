<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class UserRequest
 *
 * @package Lib
 * @subpackage itop
 */
class UserRequest extends ci {
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
	 * @var Contact
	 */
	private $Contact = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type UserRequest. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return UserRequest
	 */
	static function &creer_UserRequest(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new UserRequest ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return UserRequest
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'UserRequest' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) ) 
			->setObjetItopContact ( Contact::creer_Contact ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_UserRequest($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	public function creer_oql($name, $not_in_status = 'closed') {
		$where = " WHERE status NOT IN ('" . $not_in_status . "')";
		if (! empty ( $name )) {
			$where .= " AND title='" . $name . "'";
		}
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . $where );
	}

	public function gestion_UserRequest($title, $org_name, $description, $impact, $urgency, $email_caller, $contacts_list = array(), $functionalcis_list = array(), $workorders_list = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'title' => $title, 
				'description' => $description, 
				'impact' => $impact, 
				'urgency' => $urgency );
		$params ['caller_id'] = $this ->getObjetItopContact () 
			->creer_oql ( '', $email_caller ) 
			->getOqlCi ();
		$params ['org_id'] = $this ->getObjetItopOrganization () 
			->creer_oql ( $org_name ) 
			->getOqlCi ();
		if (! empty ( $contacts_list )) {
			$params ['contacts_list'] = $contacts_list;
		}
		if (! empty ( $functionalcis_list )) {
			$params ['functionalcis_list'] = $functionalcis_list;
		}
		if (! empty ( $workorders_list )) {
			$params ['workorders_list'] = $workorders_list;
		}
		
		$this ->creer_oql ( $title ) 
			->creer_ci ( $title, $params );
		
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
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "UserRequest :";
		
		return $help;
	}
}
?>
