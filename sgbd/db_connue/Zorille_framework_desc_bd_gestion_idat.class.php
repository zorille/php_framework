<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class gestion_bd_gestion_idat<br>
 * Gere la connexion a une base gestion_idat.
 * 
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class desc_bd_gestion_idat extends gestion_definition_table {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type desc_bd_gestion_idat.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return desc_bd_gestion_idat
	 */
	static function &creer_desc_bd_gestion_idat(&$liste_option,$sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new desc_bd_gestion_idat ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return desc_bd_gestion_idat
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Cree l'objet, prepare la valeur du sort_en_erreur et l'entete des logs.
	 *
	 * @param string $entete Entete a afficher dans les logs.
	 * @param string|bool $sort_en_erreur Prend les valeurs oui/non ou true/false.
	 */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->_chargeTable ();
		$this->charge_champs ();
	}

	private function _chargeTable() {
		$this->setTable ( 'ci', "ci" );
		$this->setTable ( 'props', "props" );
		$this->setTable ( 'runtime', "runtime" );
		$this->setTable ( 'serveur', "serveur" );
		$this->setTable ( 'tree', "tree" );
		$this->setTable ( 'hist_modifs', "hist_modifs" );
	}

	private function _chargeChamps () {
		$this->_chargeChampsCi ();
		$this->_chargeChampsProps ();
		$this->_chargeChampsRuntime ();
		$this->_chargeChampsServeur ();
		$this->_chargeChampsTree ();
		$this->_chargeChampsHistModifs ();
		
		return true;
	}

	private function _chargeChampsCi() {
		$this->setChamp ( "id", "id", "ci", "text" );
		$this->setChamp ( "serveur_id", "serveur_id", "ci", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "ci", "text" );
		$this->setChamp ( "_name", "name", "ci", "text" );
		$this->setChamp ( "status", "status", "ci", "numeric" );
		$this->setChamp ( "code_client", "code_client", "ci", "text" );
		
		return true;
	}

	private function _chargeChampsProps() {
		$this->setChamp ( "id", "id", "props", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "props", "text" );
		$this->setChamp ( "table_parent", "table_parent", "props", "text" );
		$this->setChamp ( "_key", "key", "props", "text" );
		$this->setChamp ( "_value", "value", "props", "text" );
		
		return true;
	}

	private function _chargeChampsRuntime() {
		$this->setChamp ( "id", "id", "runtime", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "runtime", "text" );
		$this->setChamp ( "table_parent", "table_parent", "runtime", "text" );
		$this->setChamp ( "_key", "key", "runtime", "text" );
		$this->setChamp ( "_value", "value", "runtime", "text" );
		
		return true;
	}

	private function _chargeChampsServeur() {
		$this->setChamp ( "id", "id", "serveur", "numeric" );
		$this->setChamp ( "name", "name", "serveur", "text" );
		$this->setChamp ( "actif", "actif", "serveur", "text" );
		$this->setChamp ( "customer", "customer", "serveur", "text" );
		$this->setChamp ( "idat_env", "idat_env", "serveur", "text" );
		
		return true;
	}

	private function _chargeChampsTree() {
		$this->setChamp ( "id", "id", "tree", "text" );
		$this->setChamp ( "serveur_id", "serveur_id", "tree", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "tree", "text" );
		$this->setChamp ( "_name", "name", "tree", "text" );
		$this->setChamp ( "_fullpathname", "fullpathname", "tree", "text" );
		
		return true;
	}

	private function _chargeChampsHistModifs() {
		$this->setChamp ( "date_traitement", "date_traitement", "hist_modifs", "date" );
		$this->setChamp ( "user", "user", "hist_modifs", "text" );
		$this->setChamp ( "reason", "reason", "hist_modifs", "text" );
		
		return true;
	}

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echoAffiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>