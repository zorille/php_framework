<?php
/**
 * Gestion de dolibarr.
 * @author dvargas
 */
namespace Zorille\dolibarr;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class users
 *
 * @package Lib
 * @subpackage dolibarr
 */
class users extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type users. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $dolibarr_webservice_rest Reference sur un objet wsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return users
	 * @throws Exception
	 */
	static function &creer_users(
		Core\options &$liste_option,
		wsclient     &$dolibarr_webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): users {
				Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new users ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $dolibarr_webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return users
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->reset_resource ();
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
			string|bool $sort_en_erreur = false,
			string      $entete = __CLASS__) {
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return users
	 */
	public function &reset_resource(): static {
		return parent::reset_resource ()->addResource ( 'users' );
	}

	/**
	 * Resource: users Method: Get List Users params : sortfield,sortorder,limit,page,user_ids,sqlfilters
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getAllUsers(
		array $params = array()): static {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->get ( $params );
		return $this;
	}

	/**
	 * @return users
	 * @throws Exception
	 */
	public function retrouve_liste_utilisateurs_dolibarr(): static {
		$this->getAllUsers ();
		foreach ( $this->getListEntry () as $user ) {
			$this->onInfo ( $user ['login'] );
		}
		return $this;
	}

	/**
	 * @return users
	 * @throws Exception
	 */
	public function retrouve_liste_utilisateurs_infraops_dolibarr(): static {
		$this->getAllUsers ();
		foreach ( $this->getListEntry () as $user ) {
			if (empty ( $user ['societe_id'] ) && isset ( $user ['login'] ) && ! empty ( $user ['lastname'] ) && ! empty ( $user ['firstname'] )) {
				$this->onInfo ( $user ['login'] );
			}
		}
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "users :";
		return $help;
	}
}
