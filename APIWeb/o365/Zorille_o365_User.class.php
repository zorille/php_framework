<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use SimpleXMLElement;
use stdClass;
use Zorille\framework as Core;
use Exception as Exception;

/**
 * class User
 *
 * @package Lib
 * @subpackage o365
 */
// Usable data for user
// aboutMe (NOT USABLE)
// accountEnabled
// ageGroup
// birthday (NOT USABLE)
// businessPhones
// city
// companyName
// consentProvidedForMinor
// country
// customSecurityAttributes
// department
// displayName
// employeeId
// employeeType
// givenName
// employeeHireDate
// employeeLeaveDateTime
// employeeOrgData
// interests (NOT USABLE)
// jobTitle
// mail
// mailNickname
// mobilePhone
// mySite (NOT USABLE)
// officeLocation
// onPremisesExtensionAttributes
// onPremisesImmutableId
// otherMails
// passwordPolicies
// passwordProfile
// pastProjects (NOT USABLE)
// postalCode
// preferredLanguage
// responsibilities (NOT USABLE)
// schools (NOT USABLE)
// skills (NOT USABLE)
// state
// streetAddress
// surname
// usageLocation
// userPrincipalName
// userType
class User extends Item {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $user_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_user = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type User. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return User
	 */
	static function &creer_User(
		Core\options &$liste_option,
		wsclient     &$webservice,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): User
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new User ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return User
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setObjetO365Wsclient ( $liste_class ['wsclient'] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * ******************************* USERS *********************************
	 */
    /**
     * Retrouve l'ID d'un utilisateur dans le champ displayname
     * @param string|array $nom
     * @return $this|false
     * @throws Exception
     */
	public function retrouve_userid_par_nom(
		string|array $nom): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
        if (is_array($nom)) $nom = implode(" ", $nom);
		$this->list_users (['$top' => '999']);
		foreach ( $this->getListeUser () as $personne ) {
			if ($personne->displayName == $nom) {
				$this->onDebug ( $nom . " trouve avec l'id " . $personne->id, 1 );
				return $this->setUserId ( $personne->id );
			}
		}
		return $this->onError ( "User " . $nom . " introuvable dans la liste", $this->getListeUser (), 1 );
	}

	/**
	 * Retrouve l'ID d'un utilisateur dans le champ displayname
	 * @param $mail
	 * @return User|false
	 * @throws Exception
	 */
	public function retrouve_userid_par_mail(
			$mail): User|bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->list_users ();
		foreach ( $this->getListeUser () as $personne ) {
			if ($personne->mail == $mail) {
				return $this->setUserId ( $personne->id );
			}
		}
		return $this->onError ( "User " . $mail . " introuvable dans la liste", $this->getListeUser (), 1 );
	}

	/**
	 * Verifie qu'un user id est remplit/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_userid(): bool
	{
		if (empty ( $this->getUserId () )) {
			$this->onDebug ( $this->getUserId (), 2 );
			$this->onError ( "Il faut un user id renvoye par O365 pour travailler" );
			return false;
		}
		return true;
	}

	/**
	 * ******************************* USER URI ******************************
	 */
	public function users_list_uri(): string
	{
		return '/users';
	}

	/**
	 * @throws Exception
	 */
	public function user_id_uri(): bool|string
	{
		if (!$this->valide_userid()) {
			return $this->onError ( "Il n'y pas d'user-id selectionne" );
		}
		return '/users/' . $this->getUserId ();
	}
	
	public function users_me_uri(): string
	{
		return '/me';
	}

	/**
	 * ******************************* O365 USERS *********************************
	 */
	/**
	 * Recuperer la liste d'utilisateurs O365
	 * @param array $params
	 * @return User|false
	 * @throws Exception
	 */
	public function list_users(
		array $params = array ()): User|bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_users_o365 = $this->getObjetO365Wsclient ()
			->getMethod ( $this->users_list_uri (), $params );
		$this->onDebug ( $liste_users_o365, 2 );
		if (isset ( $liste_users_o365->value )) {
			return $this->setListeUser ( $liste_users_o365->value );
		}
		return $this->onError ( "Pas de liste utilisateurs", $liste_users_o365, 1 );
	}

	/**
	 * Recuperer la liste d'utilisateurs O365
	 * @param array $params
	 * @return array|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function user_license(
		array $params = array ()): SimpleXMLElement|array|string|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjetO365Wsclient ()
			->getMethod ( $this->user_id_uri () . '/licenseDetails', $params );
	}

	/**
	 * Recuperer la liste de rôles O365
	 * @param array $params
	 * @return array|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function user_memberOf(
		array $params = array ()): SimpleXMLElement|array|string|stdClass
	{
				$this->onDebug ( __METHOD__, 1 );
				return $this->getObjetO365Wsclient ()
				->getMethod ( $this->user_id_uri () . '/memberOf', $params );
	}

	/**
	 * Recuperer la liste des manager du user O365
	 * @param array $params
	 * @return SimpleXMLElement|array|string|stdClass
	 * @throws Exception
	 */
	public function user_manager(
		array $params = array ()): SimpleXMLElement|array|string|stdClass
	{
				$this->onDebug ( __METHOD__, 1 );
				if(empty($params)){
					return $this->getObjetO365Wsclient ()
						->getMethod ( $this->user_id_uri () . '/manager', $params );
				}
				return $this->getObjetO365Wsclient ()
					->getMethod ( $this->user_id_uri () . '/', $params );
				
	}

	/**
	 * Recuperer la liste des sites suivis (etoile) sur O365
	 * @param array $params
	 * @return array|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function user_followedSites(
		array $params = array ()): SimpleXMLElement|array|string
	{
				$this->onDebug ( __METHOD__, 1 );
				return $this->getObjetO365Wsclient ()
				->getMethod ( $this->user_id_uri () . '/followedSites', $params );
	}

	/**
	 * Recuperer la liste des donnees personnelles sur O365
	 * @param array $params
	 * @return User|false
	 * @throws Exception
	 */
	public function user_exportPersonalData(
		array $params = array ()): User|bool|stdClass
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjetO365Wsclient ()
		->postMethod ( $this->user_id_uri () . '/exportPersonalData', $params );
	}

	/**
	 * Recuperer la liste des donnees personnelles sur O365
	 * @param array $params
	 * @return array|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function update_user(
		array $params = array ()): SimpleXMLElement|array|string
	{
				$this->onDebug ( __METHOD__, 1 );
				return $this->getObjetO365Wsclient ()
				->jsonPatchMethod ( $this->user_id_uri () , $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getUserId(): ?string
	{
		return $this->user_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUserId(
			&$user_id): static
	{
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeUser(): array
	{
		return $this->liste_user;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeUser(
			&$liste_user): static
	{
		$this->liste_user = $liste_user;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "User :";
		return $help;
	}
}
