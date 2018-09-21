<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class gestion_bd_gestion_sam
 *
 * Gere la connexion a une base.
 * @package Lib
 * @subpackage SQL-dbconnue
 */
class desc_bd_gestion_sam extends gestion_definition_table
{
	/**
	 * Cree objet, prepare la valeur du sort_en_erreur et entete des logs.
	 *
	 * @param string $entete Entete a afficher dans les logs.
	 * @param string|bool $sort_en_erreur Prend les valeurs oui/non ou true/false.
	 */
	public function __construct($sort_en_erreur="oui",$entete="BD gestion_sam")
	{
		//Gestion de abstract_log
		parent::__construct($sort_en_erreur,$entete);

		$this->_chargeTable();
		$this->_chargeChamps ();
	}

	private function _chargeTable(){
		$this->setTable("ci","ci");
		$this->setTable("crontabs","crontabs");
		$this->setTable("hist_modifs","hist_modifs");
		$this->setTable("logs","logs");
		$this->setTable("nagios","nagios");
		$this->setTable("network","network");
		$this->setTable("os","os");
		$this->setTable("process","process");
		$this->setTable("props","props");
		$this->setTable("rpm","rpm");
		$this->setTable("runtime","runtime");
		$this->setTable("serveur","serveur");
		$this->setTable("serveur_ci","serveur_ci");
		$this->setTable("tree","tree");


	}

	private function _chargeChamps (){
		$this->_chargeChampsCi();
		$this->_chargeChampsCrontabs();
		$this->_chargeChampsHistModifs();
		$this->_chargeChampsLogs();
		$this->_chargeChampsNagios();
		$this->_chargeChampsNetwork();
		$this->_chargeChampsOs();
		$this->_chargeChampsProcess();
		$this->_chargeChampsProps();
		$this->_chargeChampsRpm();
		$this->_chargeChampsRuntime();
		$this->_chargeChampsServeur();
		$this->_chargeChampsServeurCi();
		$this->_chargeChampsTree();


		return true;
	}

	
	private function _chargeChampsCi(){
		$this->setChamp ( "id", "id", "ci", "text" );
		$this->setChamp ( "name", "name", "ci", "text" );
		$this->setChamp ( "status", "status", "ci", "numeric" );
		
		return true;
	}


	private function _chargeChampsCrontabs(){
		$this->setChamp ( "id", "id", "crontabs", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "crontabs", "text" );
		$this->setChamp ( "_key", "key", "crontabs", "text" );
		$this->setChamp ( "_value", "value", "crontabs", "text" );
		$this->setChamp ( "table_parent", "table_parent", "crontabs", "text" );
		
		return true;
	}


	private function _chargeChampsHistModifs(){
		$this->setChamp ( "id", "id", "hist_modifs", "numeric" );
		$this->setChamp ( "date_traitement", "date_traitement", "hist_modifs", "date" );
		$this->setChamp ( "user", "user", "hist_modifs", "text" );
		$this->setChamp ( "reason", "reason", "hist_modifs", "text" );
		
		return true;
	}


	private function _chargeChampsLogs(){
		$this->setChamp ( "id", "id", "logs", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "logs", "text" );
		$this->setChamp ( "_key", "key", "logs", "text" );
		$this->setChamp ( "_value", "value", "logs", "text" );
		$this->setChamp ( "table_parent", "table_parent", "logs", "text" );
		
		return true;
	}


	private function _chargeChampsNagios(){
		$this->setChamp ( "id", "id", "nagios", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "nagios", "text" );
		$this->setChamp ( "_key", "key", "nagios", "text" );
		$this->setChamp ( "_value", "value", "nagios", "text" );
		$this->setChamp ( "table_parent", "table_parent", "nagios", "text" );
		
		return true;
	}


	private function _chargeChampsNetwork(){
		$this->setChamp ( "id", "id", "network", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "network", "text" );
		$this->setChamp ( "_key", "key", "network", "text" );
		$this->setChamp ( "_value", "value", "network", "text" );
		$this->setChamp ( "table_parent", "table_parent", "network", "text" );
		
		return true;
	}


	private function _chargeChampsOs(){
		$this->setChamp ( "id", "id", "os", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "os", "text" );
		$this->setChamp ( "_key", "key", "os", "text" );
		$this->setChamp ( "_value", "value", "os", "text" );
		$this->setChamp ( "table_parent", "table_parent", "os", "text" );
		
		return true;
	}


	private function _chargeChampsProcess(){
		$this->setChamp ( "id", "id", "process", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "process", "text" );
		$this->setChamp ( "_key", "key", "process", "text" );
		$this->setChamp ( "_value", "value", "process", "text" );
		$this->setChamp ( "table_parent", "table_parent", "process", "text" );
		
		return true;
	}


	private function _chargeChampsProps(){
		$this->setChamp ( "id", "id", "props", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "props", "text" );
		$this->setChamp ( "_key", "key", "props", "text" );
		$this->setChamp ( "_value", "value", "props", "text" );
		$this->setChamp ( "table_parent", "table_parent", "props", "text" );
		
		return true;
	}

	private function _chargeChampsRpm(){
		$this->setChamp ( "id", "id", "rpm", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "rpm", "text" );
		$this->setChamp ( "_key", "key", "rpm", "text" );
		$this->setChamp ( "_value", "value", "rpm", "text" );
		$this->setChamp ( "table_parent", "table_parent", "rpm", "text" );
	
		return true;
	}
	
	private function _chargeChampsRuntime(){
		$this->setChamp ( "id", "id", "runtime", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "runtime", "text" );
		$this->setChamp ( "_key", "key", "runtime", "text" );
		$this->setChamp ( "_value", "value", "runtime", "text" );
		$this->setChamp ( "table_parent", "table_parent", "runtime", "text" );
		
		return true;
	}


	private function _chargeChampsServeur(){
		$this->setChamp ( "id", "id", "serveur", "numeric" );
		$this->setChamp ( "name", "name", "serveur", "text" );
		$this->setChamp ( "actif", "actif", "serveur", "numeric" );
		
		return true;
	}


	private function _chargeChampsServeurCi(){
		$this->setChamp ( "serveur_id", "serveur_id", "serveur_ci", "numeric" );
		$this->setChamp ( "ci_id", "ci_id", "serveur_ci", "text" );
		
		return true;
	}


	private function _chargeChampsTree(){
		$this->setChamp ( "id", "id", "tree", "text" );
		$this->setChamp ( "ci_id", "ci_id", "tree", "numeric" );
		$this->setChamp ( "parent_id", "parent_id", "tree", "text" );
		$this->setChamp ( "name", "name", "tree", "text" );
		$this->setChamp ( "fullpathname", "fullpathname", "tree", "text" );
		
		return true;
	}


			
	/**
	 * @static
	 *
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help()
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"][].="Descripion de la base gestion_sam";

		return $help;
	}
}
?>