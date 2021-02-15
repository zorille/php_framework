<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class Person
 *
 * @package Lib
 * @subpackage itop
 */
class Person extends Contact {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Team
	 */
	private $Team = null;
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Person. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Person
	 */
	static function &creer_Person(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Person ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Person
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Person' )
			->setObjetItopTeam ( Team::creer_Team ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	
	/**
	* Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes
	* Format array('nom du champ obligatoire'=>false, ... )
	* @return Person
	*/
	public function champ_obligatoire_standard(){
		if(empty($this->getMandatory())) {
			$this->setMandatory(
				array(
					'name'=>false,
					'first_name'=>false,
					'email'=>false,
					'org_id'=>false
					)
				);
		}
		return $this;
	}

	/**
	 * 
	 * @param string $name
	 * @param string $firstname
	 * @return Person
	 */
	public function retrouve_Person(
			$name,
			$firstname) {
		return $this->creer_oql ( $name, $firstname )
			->retrouve_ci ();
	}

	/**
	 * 
	 * @param string $name
	 * @param string $firstname
	 * @param string $email
	 * @return Person
	 */
	public function creer_oql(
			$name='', 
			$firstname = '',
			$email='', 
			$org_name='') {
		$where="";
		if(!empty($name)){
			if (empty ( $firstname )) {
				$where = " friendlyname='" . $name . "'";
			} else {
				$where = " friendlyname='" . $firstname . " " . $name . "'";
			}
		}
		if(!empty($email)){
			if(!empty($where)){
				$where .= " AND ";
			}
			$where .= " email='" . $email . "'";
		}
		if(!empty($org_name)){
			if(!empty($where)){
				$where .= " AND ";
			}
			$where .= " org_name='" . $org_name . "'";
		}
		if(!empty($where)){
			$where = " WHERE".$where;
		}
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . $where );
	}

	/**
	* Récupère une personne existante suivant les critères données ou créer cette personne si elle n'existe pas
	* 'org_name', 'team_name
	* @param array $parametres Liste des critères. Le nom de la case= le nom du champ itop, la valeur de la case est la valeur dans itop.
	* @return Person
	*/
	public function gestion_Person(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params=array();
		$this->champ_obligatoire_standard();
		foreach($parametres as $champ=>$valeur) {
			if(isset($mandatory[$champ]) && !empty($valeur)) {
				$mandatory[$champ]=true;
			}
			switch ($champ) {
				case 'org_name':
					$params['org_id']=$this->getObjetItopOrganization ()
						->creer_oql ( $valeur )
						->getOqlCi ();
					break;
				case 'team_name':
					$params['team_id']=$this->getObjetItopTeam ()
						->creer_oql ( $valeur )
						->getOqlCi ();
					break;
				default :
					$params[$champ]=$valeur;
			}
		}
		$this->valide_mandatory_fields();
		$this->creer_oql ( $params['name'], $params['first_name'], $params['email'], $params['org_id'] )
			->creer_ci ( $params['first_name'] . " " . $params['name']. " " . $params['email'], $params );
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
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Person :";
		return $help;
	}
}
?>
