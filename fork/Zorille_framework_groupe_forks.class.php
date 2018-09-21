<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class groupe_fork.<br>
 * @codeCoverageIgnore
 * Gere plusieurs processus fils.
 * @package Lib
 * @subpackage Fork
 */
class groupe_forks extends abstract_log
{
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private static $pids=array();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private static $code_retour=array();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private static $nb_erreur_wait=0;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $gestion_par_signaux=true;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $nohangup=false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type groupe_forks.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return groupe_forks
	 */
	static function &creer_groupe_forks(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new groupe_forks ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return groupe_forks
	 */
	public function &_initialise($liste_class) {
		parent::_initialise($liste_class);
		
		$this->prepare_signaux();
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($sort_en_erreur=true, $entete = __CLASS__)
	{
		//Gestion de abstract_log
		parent::__construct("GRP FORK",$sort_en_erreur);
		
	}
	
	/**
	 * Active les signaus de pcntl pour la communication entre processus
	 * @return groupe_forks
	 */
	public function prepare_signaux(){
		if($this->getListeOptions()->verifie_option_existe("groupe_forks_gestion_par_signaux",true)!==false
		&& $this->getListeOptions()->getOption("groupe_forks_gestion_par_signaux")==="non"){
			$this->setGestionParSignaux(false);
		}
		
		if($this->getListeOptions()->verifie_option_existe("groupe_forks_no_hangup",true)!==false
		&& $this->getListeOptions()->getOption("groupe_forks_no_hangup")==="oui"){
			$this->setNoHangUp(true);
		}
		
		declare(ticks = 1);
		// Installation des gestionnaires de signaux
		
		pcntl_signal(SIGHUP,  SIG_IGN);
		pcntl_signal(SIGINT,  array("groupe_forks","traitement_retour"));
		pcntl_signal(SIGQUIT,  array("groupe_forks","traitement_retour"));
		pcntl_signal(SIGILL,  SIG_IGN);
		pcntl_signal(SIGABRT,  SIG_IGN);
		pcntl_signal(SIGFPE, SIG_IGN);
		pcntl_signal(SIGSEGV, SIG_IGN);
		pcntl_signal(SIGPIPE, SIG_IGN);
		pcntl_signal(SIGALRM, SIG_IGN);
		pcntl_signal(SIGTERM,  array("groupe_forks","traitement_retour"));
		if($this->getGestionParSignaux()){
			pcntl_signal(SIGCHLD,  array("groupe_forks","traitement_retour"));
		}
		pcntl_signal(SIGCONT, SIG_IGN);
		pcntl_signal(SIGTSTP, SIG_IGN);
		pcntl_signal(SIGTTIN, SIG_IGN);
		pcntl_signal(SIGTTOU, SIG_IGN);
		
		return $this;
	}

	/**
	 * Gere les retours systeme des processus fils.
	 * var privee
	 * @access private
	 *
	 * @param signaux Signaux systeme.
	 * @return true
	 */
	static function traitement_retour($signo)
	{
		switch ($signo) {
			case SIGCHLD:
				//Seul le pere a cette fonctionnailite
				abstract_log::onDebug_standard("Wait_pid hangup SIGCHILD ",1);
				groupe_forks::wait_pid(false);
				break;
			case SIGINT:
				// gestion de l'interruption
				abstract_log::onWarning_standard("Interruption, attente des derniers procs fils.");
				while(groupe_forks::nombre_fork_en_cours()>0){
					abstract_log::onDebug_standard("Wait_pid hangup SIGINT ",1);
					groupe_forks::wait_pid(false);
				}
				exit(0);
				break;
			case SIGTERM:
				// gestion de l'extinction
				abstract_log::onWarning_standard("SIGTERM, attente des derniers procs fils.");
				while(groupe_forks::nombre_fork_en_cours()>0){
					abstract_log::onDebug_standard("Wait_pid hangup SIGTERM ",1);
					groupe_forks::wait_pid(false);
				}
				exit(0);
				break;
			case SIGHUP:
				// gestion du redemarrage
				break;
			default:
				// gestion des autres signaux
		}
		return true;
	}

	static function wait_pid($nohup=false){
		if($nohup){
			abstract_log::onDebug_standard("Wait_pid NOHUP ",1);
			$pid=pcntl_wait($status,WNOHANG OR WUNTRACED);
		} else {
			abstract_log::onDebug_standard("Wait_pid HANGUP ",1);
			$pid=pcntl_wait($status);
		}
		if($nohup && $pid===0){
			return 0;
		}
		if($pid==-1){
			//abstract_log::onError_standard("pcntl_wait renvoi une erreur.");
			groupe_forks::setNbErreurWait();
			return false;
		} else {
			groupe_forks::clean_pid($pid,$status);
			return 1;
		}
	}

	static function clean_pid($pid,$status){

		$nom_fork=groupe_forks::getPid($pid);
		if($nom_fork!==false){
			groupe_forks::setCodeRetour($nom_fork, $status);
			groupe_forks::removePids($pid);
		} else {
			abstract_log::onError_standard("Le pid ".$pid." n'existe pas dans la liste des pids.",$liste);
		}
		abstract_log::onDebug_standard("Pid : ".$pid,1);
	}

	/**
	 * Gere les retours systeme des processus fils.
	 * var privee
	 * @access private
	 *
	 * @param signaux Signaux systeme.
	 * @return true
	 */
	static function traitement_retour_fils($signo)
	{
		switch ($signo) {
			case SIGINT:
				// gestion de l'interruption
				break;
			case SIGTERM:
				// gestion de l'extinction
				break;
			case SIGHUP:
				// gestion du redemarrage
				break;
			case SIGCHLD:
				break;
			default:
				// gestion des autres signaux
		}
		return true;
	}

	/**
	 * Reset les signaux systeme.
	 */
	public function reset_signals(){
		pcntl_signal(SIGINT,  array("groupe_forks","traitement_retour_fils"));
		pcntl_signal(SIGQUIT,  array("groupe_forks","traitement_retour_fils"));
		pcntl_signal(SIGTERM,  array("groupe_forks","traitement_retour_fils"));
		pcntl_signal(SIGCHLD,  array("groupe_forks","traitement_retour_fils"));
	}

	/**
	 * Fork les processus en cours et lui donne un nom.
	 *
	 * @param string $nom_process_fork Nom utilisateur du processus fils.
	 * @return int Renvoi 0 en cas de fork deja fait, -1 en cas d'erreur, 1 on est dans le pere, 2 on est dans le fils.
	 */
	public function fork($nom_process_fork)
	{
		if(!in_array($nom_process_fork,groupe_forks::getPids()))
		{
			$pid = pcntl_fork();
			if ($pid == -1) {
				return $this->onError('Duplication impossible');
				$CODE_RETOUR=-1;
			} elseif ($pid) {
				// le pere
				groupe_forks::setPids($nom_process_fork, $pid);
				$CODE_RETOUR=1;
			} else {
				// le fils
				// Installation des gestionnaires de signaux fils
				$this->reset_signals();
				$CODE_RETOUR=2;
			}
		} else {
			$CODE_RETOUR=0;
		}

		return $CODE_RETOUR;
	}

	/**
	 * Transforme un processus fils en une commande shell.(NON teste)
	 *
	 * @param string $nom_process_fork Nom utilisateur du processus fils.
	 * @param string $commande Commande systeme a appliquer.
	 * @param array $arguments Arguments de la commande systeme.
	 * @return int Renvoi -1 le processus fils existe.
	 */
	public function execute_process($nom_process_fork,$commande,$arguments=array())
	{
		if(!in_array($nom_process_fork,groupe_forks::getPids()))
		{
			pcntl_exec($commande,$arguments,array("PATH"=>"/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/sbin:/usr/local/bin:/usr/X11R6/bin:/home/echo/bin"));
		} else $CODE_RETOUR=-1;

		return $CODE_RETOUR;

	}

	/**
	 * Accesseur en lecture<br>
	 * Renvoi la liste des processus fils en erreur.
	 *
	 * @return array Liste des processus fils en erreur.
	 */
	public function renvoi_liste_erreur()
	{
		$liste=array();
		foreach(groupe_forks::getCodeRetour() as $code_retour=>$nom_process_fork)
		{
			if($code_retour!=0)
				$liste[$nom_process_fork]=$code_retour;
		}
		return $liste;
	}


	/**
	 * Attend la fin de tous les processus fils (fonction bloquante).
	 *
	 * @return int Renvoi le code retour ou -1 si il n'y a pas de processus fils.
	 */
	public function wait_all_children($nb_erreur_max=100)
	{
		while(groupe_forks::nombre_fork_en_cours()>0
				&& groupe_forks::getNbErreurWait()<$nb_erreur_max){
			if($this->getGestionParSignaux()){
				sleep(60);
			} else {
				$this->onDebug("Wait_pid hangup wait_all_children ",2);
				groupe_forks::wait_pid(false);
			}
		}

		if(groupe_forks::nombre_fork_en_cours()==0){
			return true;
		}

		return false;
	}

	/**
	 * Verifie l'etat des processus fils (fonction NON bloquante).
	 * Renvoi TRUE pour un ou plusieurs processus fils fini,
	 * FALSE si aucun des processus fils n'est termine
	 * ou -1 si il n'y a pas de processus fils.
	 *
	 * @return int|Bool TRUE, FALSE ou -1 si il n'y a pas de processus fils.
	 */
	public function wait_one_of_all_children($nb_erreur_max=100)
	{
		if(groupe_forks::nombre_fork_en_cours()>0){
			$timing=0;
			if($this->getNoHangUp()){
				$compteur=0;
				//20 jobs maximum
				while($retour!==0 && $compteur<20){
					$this->onDebug("Wait_pid nohup ",2);
					$retour=groupe_forks::wait_pid(true);
					$compteur++;
					$this->onDebug("Wait_pid nohup : ".$retour." compteur : ".$compteur,2);
				}
			} else {
				while($timing==0
						&& groupe_forks::getNbErreurWait()<$nb_erreur_max){
					if($this->getGestionParSignaux()){
						$this->onInfo("Attente de 60 s.");
						$timing=sleep(60);
					} else {
						$this->onDebug("Wait_pid hangup !!!!! ",2);
						groupe_forks::wait_pid(false);
						$timing=10;
					}
				}
			}
			return true;
		}

		return false;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct()
	{
	}

	/**
	 * Accesseur en lecture<br>
	 * Renvoi le nombre de processus fils en cours.
	 *
	 * @return int Renvoi le nombre de processus fils en cours.
	 */
	static function nombre_fork_en_cours()
	{
		return count(groupe_forks::getPids());
	}

	/***************** ACCESSEURS **********************/
	/**
	 * @codeCoverageIgnore
	 */
	static function getCodeRetour(){
		return groupe_forks::$code_retour;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function renvoi_code_retour($nom_process_fork)
	{
		if(isset($this->pids[$nom_process_fork])){
			return $this->code_retour[$nom_process_fork];
		} else {
			return -1;
		}
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function setCodeRetour($nom_process_fork,$code){
		$code_local=pcntl_wexitstatus($code);
		if($code_local==236){
			$code_local=0;
		}
		groupe_forks::$code_retour[$nom_process_fork]=$code_local;
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function removeCodeRetour($nom_process_fork){
		if(isset(groupe_forks::$code_retour[$nom_process_fork])){
			unset(groupe_forks::$code_retour[$nom_process_fork]);
		}
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function getPids(){
		return groupe_forks::$pids;
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function getPid($pid){
		if(isset(groupe_forks::$pids[$pid])){
			return groupe_forks::$pids[$pid];
		}

		return false;
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function validPids($pid){
		if(isset(groupe_forks::$pids[$pid])){
			return true;
		}
		return false;
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function setPids($nom_process_fork,$pid){
		groupe_forks::$pids[$pid]=$nom_process_fork;
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function removePids($pid){
		if(isset(groupe_forks::$pids[$pid])){
			unset(groupe_forks::$pids[$pid]);
		}
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function getNbErreurWait(){
		return groupe_forks::$nb_erreur_wait;
	}
	/**
	 * @codeCoverageIgnore
	 */
	static function setNbErreurWait(){
		groupe_forks::$nb_erreur_wait++;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function getGestionParSignaux(){
		return $this->gestion_par_signaux;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function setGestionParSignaux($gestion_par_signaux){
		$this->gestion_par_signaux=$gestion_par_signaux;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function getNoHangUp(){
		return $this->nohangup;
	}
	/**
	 * @codeCoverageIgnore
	 */
	public function setNoHangUp($NoHangUp){
		$this->nohangup=$NoHangUp;
	}
	/***************** ACCESSEURS **********************/

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help()
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__]["text"][].="Gestion des forks";
		$help [__CLASS__]["text"][].="\t--groupe_forks_gestion_par_signaux oui/non";
		$help [__CLASS__]["text"][].="\t--groupe_forks_no_hangup oui/non";

		return $help;
	}
} //Fin de la class
?>