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
		return $this->setFormat ( 'Person' );
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
			$name = '',
			$firstname = '',
			$email = '') {
		if (empty ( $firstname )) {
			$oql = "SELECT " . $this->getFormat () . " WHERE friendlyname='" . $name . "'";
		} else {
			$oql = "SELECT " . $this->getFormat () . " WHERE friendlyname='" . $firstname . " " . $name . "'";
		}
		if (! empty ( $email )) {
			$oql .= " AND email='" . $email . "'";
		}
		return $this->setOqlCi ( $oql );
	}

	public function gestion_Person(
			$name,
			$firstname,
			$org_name,
			$email,
			$status) {
		$this->onDebug ( __METHOD__, 1 );
		$params = array (
				'name' => $name,
				'first_name' => $firstname,
				'email' => $email,
				'status' => $status
		);
		$params ['org_id'] = $this->getObjetItopOrganization ()
			->creer_oql ( $org_name )
			->getOqlCi ();
		$this->creer_oql ( $name, $firstname )
			->creer_ci ( $firstname . " " . $name, $params );
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
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Person :";
		return $help;
	}
}
?>
