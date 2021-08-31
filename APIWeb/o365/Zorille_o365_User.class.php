<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class User
 *
 * @package Lib
 * @subpackage o365
 */
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
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return User
	 */
	static function &creer_User(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
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
			$liste_class) {
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
	 * @return true
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
	 * @param string $nom
	 * @return \Zorille\o365\User|false
	 */
	public function retrouve_userid_par_nom(
			$nom) {
		$this->onDebug ( __METHOD__, 1 );
		$this->list_users ();
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
	 * @param string $nom
	 * @return \Zorille\o365\User|false
	 */
	public function retrouve_userid_par_mail(
			$mail) {
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
	public function valide_userid() {
		if (empty ( $this->getUserId () )) {
			$this->onDebug ( $this->getUserId (), 2 );
			$this->onError ( "Il faut un user id renvoye par O365 pour travailler" );
			return false;
		}
		return true;
	}

	/**
	 * ******************************* DRIVE URI ******************************
	 */
	public function users_list_uri() {
		return '/users';
	}

	public function user_id_uri() {
		if ($this->valide_userid () == false) {
			return $this->onError ( "Il n'y pas d'user-id selectionne" );
		}
		return '/users/' . $this->getUserId ();
	}
	
	public function users_me_uri() {
		return '/me';
	}

	/**
	 * ******************************* O365 USERS *********************************
	 */
	/**
	 * Recuperer la liste d'utilisateurs O365
	 * @param array $params
	 * @return \Zorille\o365\User|false
	 */
	public function list_users(
			$params = array ()) {
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
	 * @return \Zorille\o365\User|false
	 */
	public function user_license(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjetO365Wsclient ()
			->getMethod ( $this->user_id_uri () . '/licenseDetails', $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getUserId() {
		return $this->user_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUserId(
			&$user_id) {
		$this->user_id = $user_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeUser() {
		return $this->liste_user;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeUser(
			&$liste_user) {
		$this->liste_user = $liste_user;
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
		$help [__CLASS__] ["text"] [] .= "User :";
		return $help;
	}
}
?>
