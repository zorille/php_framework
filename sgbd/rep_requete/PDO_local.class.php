<?php
/**
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
use \PDO as PDO;
use \PDOException as PDOException;
/**
 * Cette class evite un new dans la class connexion pour les tests unitaires
 * @package Lib
 * @subpackage SQL
 */
class PDO_local extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var PDO
	 */
	private $PDO_local = NULL;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type PDO_local.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return PDO_local
	 */
	static function &creer_PDO_local(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new PDO_local ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return PDO_local
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet, prepare la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		return $this;
	}

	/**
	 * Creer l'objet PDO
	 * 
	 * @param string $dsn
	 * @param string $username
	 * @param string $password
	 * @param array $options
	 * @return PDO_local
	 * @throws PDOException
	 */
	public function connexion($dsn, $username = "", $password = "", $options) {
		$this->onDebug ( "connexion", 1 );
		
		if ($username == "") {
			$this->onDebug ( "On creer le PDO avec pas de user et Dbase=" . $dsn, 2 );
			$this->setPDOConnexion ( new PDO ( $dsn, "", "", $options ) );
			// @codeCoverageIgnoreStart
		} else {
			// @codeCoverageIgnoreEnd
			$this->onDebug ( "On creer le PDO avec user : " . $username . " , et Dbase=" . $dsn, 2 );
			$this->setPDOConnexion ( new PDO ( $dsn, $username, $password, $options ) );
		}
		
		// @codeCoverageIgnoreStart
		$this->onDebug ( $this->getPDOConnexion (), 2 );
		return $this;
		// @codeCoverageIgnoreEnd
	}

	/************** Accesseur ****************/
	/**
	 * @codeCoverageIgnore
	 * @return PDO
	 */
	public function &getPDOConnexion() {
		return $this->PDO_local;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPDOConnexion($PDO_local) {
		$this->PDO_local = $PDO_local;
		return $this;
	}
/************** Accesseur ****************/
}
?>