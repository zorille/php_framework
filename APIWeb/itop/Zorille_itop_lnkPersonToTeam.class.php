<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class lnkPersonToTeam
 *
 * @package Lib
 * @subpackage itop
 */
class lnkPersonToTeam extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Team
	 */
	private $Team = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var Person
	 */
	private $Person = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var ContactType
	 */
	private $ContactType = null;
	
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type lnkPersonToTeam. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return lnkPersonToTeam
	 */
	static function &creer_lnkPersonToTeam(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new lnkPersonToTeam ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return lnkPersonToTeam
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'lnkPersonToTeam' ) 
			->setObjetItopTeam ( Team::creer_Team ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopPerson ( Person::creer_Person ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopContactType ( ContactType::creer_ContactType ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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

	public function retrouve_lnkPersonToTeam($Team_name,$Person_name) {
		return $this ->creer_oql ( $Team_name,$Person_name ) 
			->retrouve_ci ();
	}

	public function creer_oql($Team_name='',$Person_name='',$Person_firstname='',$Person_email='',$org_name='') {
		$where="";
		$join="";
		if(!empty($Team_name)){
			$where .= " team_name='" . $Team_name . "'";
		}
		if(!empty($Person_name)||!empty($Person_firstname)||!empty($Person_email)||!empty($org_name)){
			$person=$this->getObjetItopPerson () ->getFormat ();
			$join=" JOIN ".$person. " ON ".$this->getFormat ().".person_id=".$person.".id ";
			if(!empty($Person_name)){
				if(!empty($where)){
					$where .= " AND ";
				}
				$where .= " ".$person.".name='" . $Person_name . "'";
			}
			if(!empty($Person_firstname)){
				if(!empty($where)){
					$where .= " AND ";
				}
				$where .= " ".$person.".first_name='" . $Person_firstname . "'";
			}
			if(!empty($Person_email)){
				if(!empty($where)){
					$where .= " AND ";
				}
				$where .= " ".$person.".email='" . $Person_email . "'";
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
	 * Creer une entree lnkPersonToTeam
	 * @param string $Team_name
	 * @param string $Person_name
	 * @param string $Person_firstname
	 * @param string $Person_email
	 * @param string $ContactType_name Role
	 * @return lnkPersonToTeam
	 */
	public function gestion_lnkPersonToTeam(
			$Team_name,
			$Person_name,
			$Person_firstname,
			$Person_email,
			$ContactType_name='',
			$org_name='') {
		$this->onDebug ( __METHOD__, 1 );
		$params = array ();
		$params ['team_id'] = $this->getObjetItopTeam ()
			->creer_oql ( $Team_name )
			->getOqlCi ();
		$params ['person_id'] = $this->getObjetItopPerson ()
			->creer_oql ( $Person_name, $Person_firstname, $Person_email, $org_name )
			->getOqlCi ();
		if(!empty($ContactType_name)){
			$params ['role_id'] = $this->getObjetItopContactType ()
				->creer_oql ( $ContactType_name )
				->getOqlCi ();
		}
			
		$this->creer_oql ( $Team_name, $Person_name, $Person_firstname, $Person_email, $org_name )
			->creer_ci ( $Team_name." ".$Person_name, $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Team
	 */
	public function &getObjetItopTeam() {
		return $this->Team;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopTeam(&$Team) {
		$this->Team = $Team;
		
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return Team
	 */
	public function &getObjetItopPerson() {
		return $this->Person;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopPerson(&$Person) {
		$this->Person = $Person;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return Team
	 */
	public function &getObjetItopContactType() {
		return $this->ContactType;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopContactType(&$ContactType) {
		$this->ContactType = $ContactType;
		
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
		$help [__CLASS__] ["text"] [] .= "lnkPersonToTeam :";
		
		return $help;
	}
}
?>
