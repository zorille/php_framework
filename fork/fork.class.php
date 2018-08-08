<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class fork<br>
 *
 * Gere un (et un seul) processus fils.
 * @package Lib
 * @subpackage Fork
 */
class fork extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $pid = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $code_retour = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fork.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fork
	 */
	static function &creer_fork(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fork ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fork
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Fork les processus en cours.
	 *
	 * @return int Renvoi 0 en cas de fork deja fait, 1 on est dans le pere, 2 on est dans le fils.
	 * @throws Exception en cas d'erreur
	 */
	public function fork_local() {
		if ($this->getPid () == "") {
			$this->setPid ( pcntl_fork () );
			// @codeCoverageIgnoreStart
			if ($this->getPid () == - 1) {
				return $this->onError ( 'Duplication impossible' );
			} elseif ($this->getPid ()) {
				// le pere
				return 1;
			} else {
				// le fils
				return 2;
			}
			// @codeCoverageIgnoreEnd
		}
		
		return 0;
	}

	/**
	 * Transforme le processus courant en une commande shell.(NON teste)
	 * @codeCoverageIgnore
	 * @param string $commande Commande systeme a appliquer.
	 * @param array $arguments Arguments de la commande systeme.
	 */
	static public function execute_process($commande, $arguments = array()) {
		// le process courant devient le nouveau programme
		pcntl_exec ( $commande, $arguments, array (
				"PATH" => "/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/sbin:/usr/local/bin:/usr/X11R6/bin:/home/echo/bin" 
		) );
		
		return true;
	}

	/**
	 * Accesseur en lecture<br>
	 * Renvoi le code retour du processus fils.
	 *
	 * @return int Renvoi -1 si le processus n'est pas termine, le code retour du processus sinon.
	 */
	public function renvoi_code_retour() {
		if ($this->getCodeRetour () != "")
			return $this->getCodeRetour ();
		
		return - 1;
	}
	
	//renvoi -1 en cas d'erreur de process
	//renvoi le code retour de fin de processus
	/**
	* Verifie l'etat du processus fils et attend la fin de celui-ci.
	* 
	* @return int Renvoi -1 en cas d'erreur, le code retour du processus sinon.
	*/
	public function wait_children() {
		if ($this->getPid () != "") {
			pcntl_waitpid ( $this->getPid (), $status, WUNTRACED );
			$this->setCodeRetour ( pcntl_wexitstatus ( $status ) );
			// @codeCoverageIgnoreStart
			if ($this->getCodeRetour () == "236") {
				//???????
				$this->setCodeRetour ( 0 );
			}
		} else {
			$this->setCodeRetour ( - 1 );
		}
		// @codeCoverageIgnoreEnd
		
		return $this->getCodeRetour ();
	}
	
	//meme renvoi que precedement +
	//renvoi false si le process est en cours
	/**
	* Verifie l'etat du processus fils sans attendre la fin de celui-ci.
	* @codeCoverageIgnore
	* @return int|false FALSE si le processus est en cours, -1 en cas d'erreur, le code retour du processus sinon.
	*/
	public function wait_child_nohup() {
		if ($this->getPid () != "") {
			$var_return = pcntl_waitpid ( $this->getPid (), $status, WNOHANG or WUNTRACED );
			// @codeCoverageIgnoreStart
			if ($var_return === 0) {
				//Process en cour suite au WNOHANG OR WUNTRACED
				$this->setCodeRetour ( false );
			} elseif ($var_return === - 1) {
				//erreur de la fonction pcntl_waitpid
				$this->setCodeRetour ( - 1 );
			} else {
				if (pcntl_wifexited ( $status )) {
					$this->setCodeRetour ( pcntl_wexitstatus ( $status ) );
					if ($this->getCodeRetour () == "236") {
						//???????
						$this->setCodeRetour ( 0 );
					}
				} else {
					//Code exit inconnu
					$this->onWarning ( "Code exit inconnu a partir du status : " . $status );
					$this->setCodeRetour ( 0 );
				}
			}
		} else {
			$this->setCodeRetour ( - 1 );
		}
		// @codeCoverageIgnoreEnd
		
		return $this->getCodeRetour ();
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct() {
	}

	/*************** ACCESSEURS *******************/
	/**
	 * Accesseur en lecture a la liste des mois
	 * @codeCoverageIgnore
	 *
	 * @return array Renvoi la liste des mois.
	 */
	public function getPid() {
		return $this->pid;
	}

	/**
	 * @codeCoverageIgnore
	 * @return dates
	 */
	public function &setPid($pid) {
		$this->pid = $pid;
		return $this;
	}

	/**
	 * Accesseur en lecture a la liste des mois
	 * @codeCoverageIgnore
	 *
	 * @return array Renvoi la liste des mois.
	 */
	public function getCodeRetour() {
		return $this->code_retour;
	}

	/**
	 * @codeCoverageIgnore
	 * @return dates
	 */
	public function &setCodeRetour($code_retour) {
		$this->code_retour = $code_retour;
		return $this;
	}

/*************** ACCESSEURS *******************/
} //Fin de la class
?>