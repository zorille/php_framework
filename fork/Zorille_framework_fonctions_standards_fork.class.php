<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class fonctions_standards_fork<br>
 * Fonctions generales communes.
 *
 * @package Lib
 * @subpackage Fonctions_Standards
 */
class fonctions_standards_fork extends abstract_log {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fonctions_standards_fork.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standards_fork
	 */
	static function &creer_fonctions_standards_fork(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fonctions_standards_fork ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standards_fork
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	* Verifie et permet d'attendre la fin de plusieurs processus fils.<br>
	* Include : $INCLUDE_FONCTIONS<br>
	*
	* @param groupe_fork &$fork_liste Pointeur sur la class groupe_fork.
	* @param Bool $attend_fin_fork Met la fonction en attente de la fin des processus fils.
	* @return array|false Tableau des codes retours des forks, FALSE en cas d'erreur.
	*/
	public function check_process_fils(&$fork_liste, $attend_fin_fork = true) {
		$this->onDebug ( "Attente de la fin des forks. (NOHANGUP=" . (($attend_fin_fork) ? "oui" : "non") . ").", 1 );
		
		if ($fork_liste instanceof groupe_forks) {
			if ($attend_fin_fork) {
				$CODE_RETOUR = $fork_liste->wait_all_children ();
			} else {
				$CODE_RETOUR = $fork_liste->wait_one_of_all_children ();
			}
			
			$this->onDebug ( "Code retour ordonne :" . (($CODE_RETOUR === false) ? "FALSE" : "TRUE"), 2 );
		} else {
			$this->onDebug ( "Pas d'instance groupe_forks.", 2 );
			$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Verifie l'etat des processus fils en cours.<br>
	 * Charge la liste_erreurs en cas de probleme.
	 *
	 * @param groupe_forks &$fork_liste Pointeur sur les processus fils.
	 * @param string $message Message a afficher en cas d'erreur.
	 * @param bool $attend_fin_fork rend la fonction bloquante jusqu'a la fin des processus fils de $fork_liste.
	 * @return array|false un tableau si OK, FALSE sinon.
	 * @throws Exception
	 */
	public function gestion_process_fils(&$fork_liste, $message = "", $attend_fin_fork = false) {
		$forks_termines = $this->check_process_fils ( $fork_liste, $attend_fin_fork );
		
		if ($forks_termines) {
			$CODE_RETOUR = array ();
			foreach ( groupe_forks::getCodeRetour () as $serial => $code_retour ) {
				//Information sur le code retour
				if ($code_retour == 0) {
					$this->onInfo ( "Fin " . $message . " : " . $serial . " avec le code_retour : " . $code_retour . " ." );
				} else {
					//L'affichage des erreurs dans les logs se fait a la fin du programme
					return $this->onError ( "Probleme lors " . $message . " : " . $serial . " avec le code_retour : " . $code_retour . " .", "" );
				}
				$CODE_RETOUR [$serial] = $code_retour;
			}
		} else {
			$this->onDebug ( "Pas de liste_serial_fait.", 2 );
			$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}
}
?>