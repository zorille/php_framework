<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use \Exception as Exception;
/**
 * class vmwareVim25ManagedObject<br>
 * @package Lib
 * @subpackage VMWare
 */
class vmwareVim25ManagedObject extends vmwareVim25ManagedEntity {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareVim25ManagedObject.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $vmware_webservice Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareVim25ManagedObject
	 */
	static function &creer_vmwareVim25ManagedObject(&$liste_option, &$vmware_webservice, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new vmwareVim25ManagedObject ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareWsclient" => $vmware_webservice 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareVim25ManagedObject
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}
	
	/************************* Methodes Task ***********************/
	/**
	 * Retrouve toutes les donnees d'une task
	 *
	 * @param array $task_moid ManagedObject ID de la task
	 * @param array $options Liste des options de recherches
	 * @return array|false
	 * @throws Exception
	 */
	public function Get_Task_Datas($task_moid) {
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjectVmwarePropertyCollector ()
			->retrouve_donnees_par_ManagedObject ( $task_moid );
	}

	/**
	 * Retrouve toutes les donnees d'une task
	 *
	 * @param array $task_moid ManagedObject ID de la task
	 * @return string Etat final de la task (success,error,timeout)
	 * @throws Exception
	 */
	public function Wait_Task($task_moid) {
		$this->onDebug ( __METHOD__, 1 );
		$etat = $this->getObjectVmwarePropertyCollector ()
			->retrouve_propset ( $task_moid, false, array (
				"info.state" 
		) );
		$state = $etat ["info.state"];
		$this->progress_task ( $state, $task_moid );
		
		$this->onDebug ( $state, 2 );
		return $state;
	}

	/**
	 * Affiche la progression de la task &task_moid. Met a jour le state avec l'etat de la task toutes les secondes.
	 * Attention au timeout d'1 heure.
	 * 
	 * @param string &$state Etat de progression (doit etre a running pour demarrer le suivi de la progression)
	 * @param array $task_moid ManagedObject ID de la task
	 * @return vmwareVim25ManagedObject
	 * @throws Exception
	 */
	public function progress_task(&$state, $task_moid) {
		$this->onDebug ( __METHOD__, 1 );
		//timeout au bout d'une heure
		$timeout = 0;
		while ( $state == "running" && $timeout < 3600 ) {
			sleep ( 1 );
			$etat = $this->getObjectVmwarePropertyCollector ()
				->retrouve_propset ( $task_moid, false, array (
					"info.state",
					"info.progress" 
			) );
			//Tant qu'il y a une progression, on a le champ info.progress
			if (isset ( $etat ["info.progress"] )) {
				$state = $etat ["info.state"];
				$this->onInfo ( "Progress : " . $etat ["info.progress"] . "%" );
			} else {
				$this->onInfo ( "Progress : 100%" );
				$state = $etat ["info.state"];
			}
			
			$timeout ++;
		}
		// @codeCoverageIgnoreStart
		if ($timeout == 3600) {
			$state = "timeout";
		}
		// @codeCoverageIgnoreEnd
		
		return $this;
	}

	/************************* Methodes Task ***********************/
	
	/************************* Accesseurs ***********************/
	
	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

?>
