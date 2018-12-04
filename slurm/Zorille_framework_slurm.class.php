<?php
/**
 * @author dvargas
 * @package Lib
 *
 */

namespace Zorille\framework;
/**
 * class slurm<br>
 * @codeCoverageIgnore
 * Gere les appels a slurm.
 * @package Lib
 * @subpackage Slurm
 */
class slurm extends CommandLine {
	
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_slurm_id = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_slurm_erreur = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $liste_slurm_returncode = array ();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $Repertoire;
	
	/************* Simulation ultralight du fonctionnement de slurm *****************************/
	/**
	 * @access public
	 * @var calculateurs
	 */
	public $liste_calculateurs;
	/**
	 * @access public
	 * @var groupe_forks
	 */
	public $fork_liste;
	/**
	 * @access public
	 * @var string
	 */
	public $slurmid;

	/**
	 * @codeCoverageIgnore
	 */
	private function _prepareCalculateurs() {
		$fonctions_calculateurs = fonctions_standards_gestion_machines::creer_fonctions_standards_gestion_machines ( $this->getListeOptions () );
		$this->liste_calculateurs = $fonctions_calculateurs->creer_liste_calculateurs ();
		if (! $this->liste_calculateurs instanceof calculateurs) {
			return $this->onError ( "Impossible de creer la liste des calculateurs." );
		}
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $serial
	 * @param integer $ram
	 * @param string $disk
	 * @param number $cpu
	 * @return boolean|string
	 */
	private function _attribut($serial, $ram, $disk, $cpu = 1) {
		$this->onDebug ( "_attribution du calculateur pour le serial : " . $serial, 1 );
		if ($serial == "") {
			return $this->onError ( "Il faut un serial en parametres pour l'_attribution d'un calculateur." );
		} elseif ($ram == "" || $disk == "") {
			$ram = 50;
			$disk = 50;
			$this->onWarning ( "Valeurs par defaut des parametres disk/ram pour l'_attribution d'un calculateur." );
		}
		return $this->liste_calculateurs->_attribut_calculateur ( $serial, $ram, $disk, $cpu );
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $serial
	 * @return boolean
	 */
	private function _libere($serial) {
		$this->onDebug ( "Liberation du calculateur pour le serial : " . $serial, 1 );
		$this->liste_calculateurs->_libere_calculateur ( $serial );
		groupe_forks::removeCodeRetour ( $serial );
		
		return true;
	}

	/**
	 * @codeCoverageIgnore
	 * @return number
	 */
	private function _nbMaxJob() {
		$nb_job = $this->liste_calculateurs->renvoi__nbMaxJob ();
		$this->onDebug ( "On renvoi le nombre max de job : " . $nb_job, 2 );
		
		return $nb_job;
	}

	/**
	 * Attend la liberation d'un calculateur pour un job.
	 * @codeCoverageIgnore
	 * @param array &$donnees_serial Pointeur sur donnees d'un serial.
	 * @return string Calculateur selectionne.
	 */
	private function _attendLiberationCalculateur(&$donnees_serial) {
		$alert = 0;
		
		//on _attribut des calculateurs tant qu'il y a de la place dans les calculateurs
		$calculateur = $this->_attribut ( $donnees_serial ["serial"], $donnees_serial ["ram"], $donnees_serial ["disk"], $donnees_serial ["cpu"] );
		
		while ( $calculateur === false || $calculateur == "" ) {
			//Les process pere check en constant les process fils qui rendent la main
			$this->onDebug ( "nb fork en cours :" . groupe_forks::nombre_fork_en_cours (), 1 );
			if ($this->_checkProcessFilsMaster () === false || groupe_forks::nombre_fork_en_cours () === 0) {
				$this->onDebug ( "nb calculateurs :" . $this->liste_calculateurs->renvoi_nb__attribution (), 2 );
				if (groupe_forks::nombre_fork_en_cours () === 0) {
					//Si il n'y a plus de fork, les calcualteurs sont "vide" de traitements
					$this->onWarning ( "Liberation de tous les calculateurs" );
					$this->liste_calculateurs->_libere_tous_calculateurs ();
				} else {
					$alert ++;
					sleep ( 1 );
				}
			}
			if ($alert > 100) {
				$this->onDebug ( "nb calculateurs :" . $this->liste_calculateurs->renvoi_nb__attribution (), 0 );
				return $this->onError ( "On n'arrive pas a attribuer un calculateur alors qu'il n'y a plus de process fils pour " . print_r ( $donnees_serial, true ) );
				break;
			}
			//on _attribut des calculateurs tant qu'il y a de la place dans les calculateurs
			$calculateur = $this->_attribut ( $donnees_serial ["serial"], $donnees_serial ["ram"], $donnees_serial ["disk"], $donnees_serial ["cpu"] );
			if ($calculateur == - 1) {
				break;
			}
		}
		
		if ($calculateur == - 1) {
			return $this->onError ( "Aucun calculateur ne peut etre attribue a ce job : " . $donnees_serial ["serial"] . " avec ram=" . $donnees_serial ["ram"] . " disk=" . $donnees_serial ["disk"] . " cpu=" . $donnees_serial ["cpu"] );
		}
		
		return $calculateur;
	}

	/**
	 * Verifie l'etat des processus fils en cours.<br>
	 * Charge la liste_erreurs en cas de probleme.
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur les arguments.
	 * @param groupe_forks &$fork_liste Pointeur sur les processus fils.
	 * @param array &$liste_erreurs Pointeur sur les erreurs de rotate.
	 * @param string $message Message a afficher en cas d'erreur.
	 * @param bool $attend_fin_fork rend la fonction bloquante jusqu'a la fin des processus fils de $fork_liste.
	 * @return int nombre de processus termines.
	 */
	private function _checkProcessFilsMaster($attend_fin_fork = false) {
		$fonctions_standards_fork = fonctions_standards_fork::creer_fonctions_standards_fork ( $this->getListeOptions () );
		$serials_fait = $fonctions_standards_fork->gestion_process_fils ( $this->fork_liste, "du job", $attend_fin_fork );
		
		if ($serials_fait === false) {
			$this->onDebug ( "Pas de serial fait", 2 );
			return false;
		} else {
			$CODE_RETOUR = true;
			foreach ( $serials_fait as $serial => $code_retour ) {
				$code_retour;
				$this->_libere ( $serial );
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
 	* @codeCoverageIgnore
 	* @param string $serial
	*/
	public function creer_slurmid($serial) {
		$this->slurmid = $serial . "_" . mt_rand ();
	}

	/************* Simulation ultralight du fonctionnement de slurm *****************************/
	
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type slurm.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $Repertoire
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return slurm
	 */
	static function &creer_slurm(&$liste_option, $Repertoire, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new slurm ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		$objet->prepare_slurm_param ( $Repertoire );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return slurm
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string|bool $sort_en_erreur Prend les valeurs oui/non
	 * @param string $entete Entete pour l'affichage.
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * 
	 * @param string $Repertoire
	 */
	public function prepare_slurm_param($Repertoire) {
		$this->setRepertoire ( $Repertoire );
		
		$this->_prepareCalculateurs ();
		
		$this->fork_liste = groupe_forks::creer_groupe_forks ( $this->getListeOptions (), $sort_en_erreur );
	}

	/**
	 * Lance la commande a slurm
	 *
	 * @param string $cmd
	 * @param array &$donnees_serial
	 * @param string $Repertoire
	 * @param MongoId &$jobid
	 * @param string $type_traitement
	 * @param gestion_bd_mongoDB &$mongo
	 */
	private function _execute($cmd, &$donnees_serial, $jobid, $type_traitement, &$mongo) {
		//Temporaire sans le vrai slurm
		$this->creer_slurmid ( ( string ) $jobid );
		$retour = $this->slurmid;
		$mongo->close ();
		
		$this->onInfo ( "Jobid : " . $this->slurmid . " traitement : " . $type_traitement . " pour le serial " . $donnees_serial ['serial'] );
		
		$donnees_serial ['serial'] = ( string ) $jobid;
		$calculateur = $this->_attendLiberationCalculateur ( $donnees_serial );
		
		$this->onInfo ( "Calculateur : " . $calculateur . " Pour le JobId " . ( string ) $jobid );
		
		$pid = $this->fork_liste->fork ( $donnees_serial ['serial'] );
		//si on est dans le fils on _execute la commande
		if ($pid == 2) {
			$connexion = array ();
			$connexion = fonctions_standards_sgbd::creer_connexion_liste_option ( $this->getListeOptions () );
			$localmongo = fonctions_standards_sgbd::recupere_db_mongodbAbstract ( $connexion, true );
			$cmd .= "\t--workspace " . $this->getRepertoire () . "/CALCULATOR/" . $type_traitement . "_" . $this->slurmid;
			$cmd .= "\t--slurmid " . $this->slurmid;
			$this->onInfo ( "Commande : " . $cmd );
			
			if ($calculateur != $this->getListeOptions ()
				->getOption ( "netname" )) {
				$this->onDebug ( $calculateur . "!=" . $this->getListeOptions ()
					->getOption ( "netname" ), 1 );
				//On fait la connexion
				$class_flux = fonctions_standards_flux::creer_fonctions_standards_flux ( $liste_option );
				if (! is_object ( $class_flux )) {
					$localmongo->requete_update_dans_jobs ( $jobid, "__no_update", $this->slurmid, "erreur", 1005, "__no_update", "__no_update", date ( "Ymd H:i:s" ) );
					return $this->onError ( "La class fonctions_standards_flux est introuvable.", "", 1005 );
				}
				
				$connexion = $class_flux->creer_connexion_ssh ();
				if ($connexion === false) {
					$localmongo->requete_update_dans_jobs ( $jobid, "__no_update", $this->slurmid, "erreur", 1002, "__no_update", "__no_update", date ( "Ymd H:i:s" ) );
					$this->onError ( "Creation de l'objet ssh impossible." );
					//exit du thread fils
					exit ( 1002 );
				}
				if ($connexion->ssh_connect ( $calculateur )) {
					//On _execute la commande
					$CODE_RETOUR = $connexion->ssh_commande ( $cmd, true, true );
					$this->onDebug ( $CODE_RETOUR, 2 );
					$connexion->ssh_commande ( "rm -Rf " . $this->getRepertoire () . "/CALCULATOR/" . $type_traitement . "_" . $this->slurmid, true, true );
					
					if (is_array ( $CODE_RETOUR )) {
						//Si il y a eu des erreurs non bloquantes
						if ($CODE_RETOUR ["err"] !== false && preg_match ( '/[\r|\n]\[Exit\]0[\r|\n]/', $CODE_RETOUR ["output"] ) !== 0) {
							//Si il y a eu des erreurs NON bloquantes
							$localmongo->requete_update_dans_jobs ( $jobid, "__no_update", $this->slurmid, "warning", 0, "__no_update", "__no_update", date ( "Ymd H:i:s" ) );
						} elseif ($CODE_RETOUR ["err"] !== false) {
							//Si il y a eu des erreurs bloquantes
							$localmongo->requete_update_dans_jobs ( $jobid, "__no_update", $this->slurmid, "erreur", 1001, "__no_update", "__no_update", date ( "Ymd H:i:s" ) );
						} elseif (preg_match ( '/[\r|\n]\[Exit\]0[\r|\n]/', $CODE_RETOUR ["output"] ) === 0) {
							//Si il y a eu des erreurs hors du stderr
							$localmongo->requete_update_dans_jobs ( $jobid, "__no_update", $this->slurmid, "erreur", 1010, "__no_update", "__no_update", date ( "Ymd H:i:s" ) );
						}
						
						$fichier_out = fichier::creer_fichier ( $this->getListeOptions (), $this->getRepertoire () . "/logs/" . $this->slurmid . "_" . $type_traitement . ".log", "oui" );
						$fichier_out->ouvrir ( "w" );
						foreach ( $CODE_RETOUR as $ligne ) {
							if ($ligne != "") {
								$fichier_out->ecrit ( $ligne );
							}
						}
						$fichier_out->close ();
					} else {
						//theoriquement impossible :p
						$localmongo->requete_update_dans_jobs ( $jobid, "__no_update", $this->slurmid, "erreur", 1004, "__no_update", "__no_update", date ( "Ymd H:i:s" ) );
					}
					$connexion->ssh_close ();
					exit ( 1004 );
				} else {
					$localmongo->requete_update_dans_jobs ( $jobid, "__no_update", $this->slurmid, "erreur", 1003, "__no_update", "__no_update", date ( "Ymd H:i:s" ) );
					return $this->onError ( "Connexion impossible.", "", 1003 );
				}
			} else {
				$this->onInfo ( "On applique la commande en local." );
				$retour = fonctions_standards::applique_commande_systeme ( $cmd, false );
				fonctions_standards::applique_commande_systeme ( "rm -Rf " . $this->getRepertoire () . "/CALCULATOR/" . $type_traitement . "_" . $this->slurmid );
				if ($retour [0] !== 0) {
					$localmongo->requete_update_dans_jobs ( $jobid, "__no_update", $this->slurmid, "erreur", 1001, "__no_update", "__no_update", date ( "Ymd H:i:s" ) );
				}
				
				$fichier_out = fichier::creer_fichier ( $this->getListeOptions (), $this->getRepertoire () . "/logs/" . $this->slurmid . "_" . $type_traitement . ".log", "oui" );
				$fichier_out->ouvrir ( "w" );
				foreach ( $retour as $ligne ) {
					if ($ligne != "") {
						$fichier_out->ecrit ( $ligne . "\n" );
					}
				}
				$fichier_out->close ();
			}
			
			$localmongo->close ();
			
			//exit du thread fils
			exit ( 0 );
		} elseif ($pid != 1) {
			return $this->onError ( "Erreur lors de l'appel a slurm avec le retour : " . $pid );
		}
		
		usleep ( 10 );
		/******* Gestion des BASES de DONNEES ********/
		$connexion = array ();
		$connexion = fonctions_standards_sgbd::creer_connexion_liste_option ( $this->getListeOptions () );
		$mongo = fonctions_standards_sgbd::recupere_db_mongodbAbstract ( $connexion, true );
		/******* FIN de la Gestion des BASES de DONNEES ********/
		
		return $retour;
	}

	/**
	 * Lance la commande srun a slurm
	 *
	 * @param string $cmd_php
	 * @param array &$donnees_serial
	 * @param string $jobid
	 */
	public function srun($cmd_php, &$donnees_serial, $jobid) {
		$this->setCmd ( "srun " );
		$this->addToCmd ( $this->AddParam ( "--account", "slurm", true, "=" ) );
		$this->addToCmd ( $this->AddParam ( "--chdir", $this->getRepertoire () . "/CALCULATOR", true, "=" ) );
		$this->addToCmd ( $this->AddParam ( "--cpus-per-task", $donnees_serial ["cpu"], true, "=" ) );
		$this->addToCmd ( $this->AddParam ( "--mem", $donnees_serial ["ram"], true, "=" ) );
		//Le temps max de traitement egale le temps en mongo x2 en cas de dépassement
		$this->addToCmd ( $this->AddParam ( "--time", $donnees_serial ["time"] * 2, true, "=" ) );
		
		$cmd = $this->getCmd () . " " . $cmd_php;
		$this->onInfo ( "Commande : " . $cmd );
		
		$this->onInfo ( "On applique la commande srun." );
		$retour = parent::applique_commande_systeme ( $cmd );
		$this->onDebug ( "Code retour = " . $retour [0], 1 );
		$this->onDebug ( $retour, 1 );
		
		return $retour [0];
	}

	/**
	 * Lance la commande sbatch a slurm
	 *
	 * @param string $cmd_php
	 * @param array &$donnees_serial
	 * @param string $jobid
	 * @param string $type_traitement
	 * @param gestion_bd_mongoDB &$mongo
	 */
	public function sbatch($cmd_php, &$donnees_serial, $jobid, $type_traitement, &$mongo) {
		return $this->_execute ( $cmd_php, $donnees_serial, $jobid, $type_traitement, $mongo );
		/*$this->setCmd("sbatch ");
		 $this->addToCmd($this->AddParam("--account", "slurm",true,"="));
		$this->addToCmd($this->AddParam("--workdir", $this->getRepertoire()."/CALCULATOR",true,"="));
		$this->addToCmd($this->AddParam("--cpus-per-task", $donnees_serial["cpu"],true,"="));
		$this->addToCmd($this->AddParam("--mem", $donnees_serial["ram"],true,"="));
		//Le temps max de traitement egale le temps en mongo x2 en cas de dépassement
		$this->addToCmd($this->AddParam("--time", $donnees_serial["time"]*2,true,"="));

		$cmd=$this->getCmd()." ".$this->getRepertoire()."/TOC/scripts/systeme/run_php.sh ".$cmd_php;
		$this->onInfo("Commande : ".$cmd);

		$this->onInfo("On applique la commande sbatch.");
		$retour=parent::applique_commande_systeme($cmd);
		$this->onDebug("Code retour = ".$retour[0],1);
		$this->onDebug($retour, 1);
		if($retour[0]===0){
		$nb_retour=count($retour);
		if($nb_retour>1){
		for($i=1;$i<$nb_retour;$i++){
		if(strpos($retour[$i],"Submitted batch job ")===0){
		$id=str_replace("Submitted batch job ","",$retour[$i]);
		$this->onDebug($retour[$i],1);
		$this->onDebug($id,2);
		$this->setListeSlurmId($id,$jobid,true);
		//On soumet un et un seul job a la fois
		break;
		}
		}
		}
		}

		return $retour[0];*/
	}

	/**
	 * Valide l'etat de tous les jobs.
	 */
	public function valide_etats_jobs() {
		$commande = $this->getRepertoire () . "/TOC/scripts/systeme/get_job_status.sh";
		
		$liste = $this->getListeSlurmId ();
		foreach ( $liste as $slurmid => $jobid ) {
			$cmd = $commande . " " . $slurmid;
			$retour = parent::applique_commande_systeme ( $cmd );
			if ($retour [0] === 0 && count ( $retour ) > 1) {
				switch (trim ( $retour [1] )) {
					case "COMPLETED" :
						unset ( $liste [$slurmid] );
						break;
					case "FAILED" :
					case "CANCELLED" :
						$this->setListeSlurmErreur ( $slurmid, $jobid, true );
						unset ( $liste [$slurmid] );
						break;
					case "RUNNING" :
					//rien a faire
					default :
				}
			}
		}
		
		$this->setListeSlurmId ( $liste, "", false );
	}

	/**
	 * Permet d'attendre la fin des jobs.
	 * @param int $timeout temps avant le timeout en seconde
	 * @param int $attente=60 temps entre 2 verification en seconde
	 * @return bool TRUE si le traitement est termine, false si on a atteint le timeout.
	 */
	public function attend_fin_jobs($timeout, $attente = 60) {
		if ($this->_checkProcessFilsMaster ( true )) {
			return true;
		}
		return false;
		
		/*$start_ts=time();
		 //On valide une premiere fois pour eviter les attentes inutiles
		$this->valide_etats_jobs();

		while(count($this->getListeSlurmId())>0){
		$this->onInfo("On attend la fin des jobs");
		$this->onDebug($this->getListeSlurmId(), 1);
		sleep($attente);
		$this->valide_etats_jobs();
		if((time()-$start_ts)>$timeout){
		return false;
		}
		}

		return true;*/
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getRepertoire() {
		return $this->Repertoire;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRepertoire($Repertoire) {
		if ($Repertoire != "") {
			$this->Repertoire = $Repertoire;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeSlurmId() {
		return $this->liste_slurm_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeSlurmId($slurm_id, $jobid, $add = false) {
		if ($add) {
			$this->liste_slurm_id [$slurm_id] = $jobid;
		} else {
			if (is_array ( $slurm_id )) {
				$this->liste_slurm_id = $slurm_id;
			} else {
				$this->liste_slurm_id = array (
						$slurm_id => $jobid 
				);
			}
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeSlurmErreur() {
		return $this->liste_slurm_erreur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeSlurmErreur($slurm_id, $jobid, $add = false) {
		if ($add) {
			$this->liste_slurm_erreur [$slurm_id] = $jobid;
		} else {
			if (is_array ( $slurm_id )) {
				$this->liste_slurm_erreur = $slurm_id;
			} else {
				$this->liste_slurm_erreur = array (
						$slurm_id => $jobid 
				);
			}
		}
		
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
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