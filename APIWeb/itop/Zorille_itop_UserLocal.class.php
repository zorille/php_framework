<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
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
	 * 'name', 'first_name', 'email', 'login', 'password', 'language', 'status', 'profile_list', 'allowed_org_list'
	 * @param string $name,
	 * @param string $firstname
	 * @param string $email
	 * @param string $login
	 * @param string $password
	 * @param string $language FR FR/EN US
	 * @param string $status disabled/enabled
	 * @return UserLocal
	 */
	public function gestion_UserLocal(
			$name,
			$firstname,
			$email,
			$login,
			$password,
			$language,
			$status) {
		$this->onDebug ( __METHOD__, 1 );
		$params = array (
				'login' => $login,
				'password' => $password,
				'language' => $language,
				'status' => $status
		);
		$params ['contactid'] = $this->getObjetItopPerson ()
			->creer_oql ( $name, $firstname, $email )
			->getOqlCi ();
		$params ['profile_list']=array(3);
		$this->creer_oql ( $login )
			->creer_ci ( $login, $params );
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
