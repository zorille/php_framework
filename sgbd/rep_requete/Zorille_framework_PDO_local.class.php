<?php
/**
 * @author dvargas
 */
namespace Zorille\framework;
use Exception;
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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return PDO_local
	 */
	static function &creer_PDO_local(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): PDO_local
	{
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
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
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
	public function connexion(string $dsn, $username = "", $password = "", array $options): static
	{
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
	 * @return PDO|null
	 */
	public function &getPDOConnexion(): ?PDO
	{
		return $this->PDO_local;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPDOConnexion($PDO_local): static
	{
		$this->PDO_local = $PDO_local;
		return $this;
	}
/************** Accesseur ****************/
}
