<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class UserLocal
 *
 * @package Lib
 * @subpackage itop
 */
class UserLocal extends Contact {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Person
	 */
	private $Person = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type UserLocal. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return UserLocal
	 */
	static function &creer_UserLocal(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new UserLocal ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return UserLocal
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'UserLocal' )
			->setObjetItopPerson ( Person::creer_Person ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
					'login'=>false,
					'language'=>false,
					'reset_pwd_token'=>false,
					'password'=>false,
					'org_id'=>false
					)
				);
		}
		return $this;
	}

	public function retrouve_UserLocal(
			$login) {
		return $this->creer_oql ( $login )
			->retrouve_ci ();
	}

	public function creer_oql(
			$login = '', $email='') {
		if (empty ( $login )) {
			$oql = "SELECT " . $this->getFormat ();
		} else {
			$oql = "SELECT " . $this->getFormat () . " WHERE login='" . $login . "'";
		}
		return $this->setOqlCi ( $oql );
	}

	/**
	 * Creer un CI de type UserLocal (necessite un Contact existant)
	 * 'name', 'first_name', 'email', 'login', 'password', 'language', 'status', 'org_name', 'profile_list', 'allowed_org_list'
 	 * @param array $parametres Liste des critères. Le nom de la case= le nom du champ itop, la valeur de la case est la valeur dans itop.
	 * @return UserLocal
	*/
	public function gestion_UserLocal(
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
				case 'contactid_friendlyname':
					$params[$champ]=$this->getObjetItopPerson ()
						->creer_oql ( $valeur,'','',$parametres['org_name'] )
						->getOqlCi ();
					break;
				default :
					$params[$champ]=$valeur;
			}
		}
		$this->valide_mandatory_fields($mandatory);
		$this->creer_oql ( $params['login'] )
			->creer_ci ( $params['login'], $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Person
	 */
	public function &getObjetItopPerson() {
		return $this->Person;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopPerson(
			&$Person) {
		$this->Person = $Person;
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
		$help [__CLASS__] ["text"] [] .= "UserLocal :";
		return $help;
	}
}
?>
