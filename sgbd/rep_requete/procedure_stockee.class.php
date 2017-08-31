<?php
/**
 * @author dvargas
 */
/**
 * class procedure_stockee<br>
 * Gere la connexion a une base SQL.
 * 
 * @package Lib
 * @subpackage SQL
 */
class procedure_stockee extends connexion {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $procedure_stocke;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type procedure_stockee.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return procedure_stockee
	 */
	static function &creer_procedure_stockee(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new procedure_stockee ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return procedure_stockee
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet, prepare la valeur du sort_en_erreur
	 * et creer la connexion.
	 * @codeCoverageIgnore
	 * @param string $machine Nom de la machine ayant la base.
	 * @param string $user Nom de l'utilisateur de connexion.
	 * @param string $password Mot de passe de l'utilisateur.
	 * @param string $type Type de base : mysql/sqlite ...
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare la requete pour la procedure stockee.
	 *
	 * @param string $requete Requete pour la procedure stockee.
	 * @return bool true si OK, false sinon.
	 * @throws Exception
	 */
	public function prepare_requete_stockee($requete) {
		$this->setProcedureStocke($this->preparer_requete ( $requete ));
		
		return true;
	}

	/**
	 * Prepare la valeur pour la procedure stockee.
	 *
	 * @param string $parametre Parametre du BindValue PDO.
	 * @param string $valeur Valeur du BindValue PDO.
	 * @return PDO Renvoi le resultat au format PDO.
	 */
	public function bind_valeur($parametre, $valeur) {
		return $this->getProcedureStocke()->bindValue ( $parametre, $valeur );
	}

	/**
	 * Prepare le parametre pour la procedure stockee.
	 *
	 * @param string $parametre Parametre du BindParam PDO.
	 * @param string $valeur Valeur du BindParam PDO.
	 * @return PDO Renvoi le resultat au format PDO.
	 */
	public function bind_parametre($parametre, $valeur) {
		return $this->getProcedureStocke()->bindParam ( $parametre, $valeur );
	}

	/**
	 * Execute la procedure stockee.
	 *
	 * @param array $tableau_parametre Liste de parametre PDO.
	 * @return PDO|false Renvoi le resultat au format PDO, FALSE en cas d'erreur.
	 */
	public function applique_requete_stocker($tableau_parametre = "notableau") {
		if ($this->getProcedureStocke() instanceof PDOStatement) {
			if (is_array ( $tableau_parametre ))
				$CODE_RETOUR = $this->getProcedureStocke()->execute ( $tableau_parametre );
			else
				$CODE_RETOUR = $this->getProcedureStocke()->execute ();
		} else
			$CODE_RETOUR = false;
		return $CODE_RETOUR;
	}

	/**
	 * Renvoi les resultats de l'execution de la procedure stockee.
	 *
	 * @return PDO|false Renvoi le resultat au format PDO, FALSE en cas d'erreur.
	 */
	public function renvoi_valeur() {
		if ($this->getProcedureStocke() instanceof PDOStatement) {
			return $this->getProcedureStocke()->fetchAll ();
		}
		
		return false;
	}
	
	/**************************** ACCESSEURS **********************/
	/**
	 * @codeCoverageIgnore
	 * @return PDOStatement
	 */
	public function &getProcedureStocke() {
		return $this->procedure_stocke;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setProcedureStocke($procedure_stocke) {
		$this->procedure_stocke = $procedure_stocke;
		return $this;
	}
	
	/**************************** ACCESSEURS **********************/

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>