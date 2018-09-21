<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class fonctions_standards_flux<br>
 * 
 * @package Lib
 * @subpackage Flux
 */
class fonctions_standards_flux extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var fonctions_standards
	 */
	private $connexion = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fonctions_standards_flux.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standards_flux
	 */
	static function &creer_fonctions_standards_flux(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fonctions_standards_flux ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standards_flux
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet.
	 * @codeCoverageIgnore
	 */
	public function __construct($entete = __CLASS__, $sort_en_erreur = false) {
		
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		return $this;
	}

	/**
	 * Creer un objet ssh_z
	 * 
	 * @return ssh_z|false Renvoi un objet SSH_Z, FALSE sinon.
	 */
	public function creer_connexion_ssh($nom_machine_distante, $nbretry = 3) {
		//voir help SSH
		if ($this->getConnexion () !== false && $this->getConnexion () instanceof ssh_z && $this->getConnexion ()
			->getSshConnexion () !== false) {
			$this->onInfo ( "On utilise la connexion ssh active." );
			return $this->getConnexion ();
		}
		
		$this->setConnexion ( ssh_z::creer_ssh_z ( $this->getListeOptions (), $this->getSortEnErreur () ) );
		return $this->getConnexion ()
			->setMachineDistante ( $nom_machine_distante )
			->setNbRetry ( $nbretry )
			->ssh_connect ();
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml et creer un objet SFTP (sftp_z).<br>
	 * @codeCoverageIgnore
	 * @return ssh_z|false Renvoi un objet SFTP_Z, FALSE sinon.
	 */
	public function creer_connexion_sftp($machine_distante) {
		//voir help SSH
		if ($this->getConnexion () !== false && $this->getConnexion () instanceof sftp_z && $this->getConnexion ()
			->getSshConnexion () !== false) {
			$this->onInfo ( "On utilise la connexion sftp active." );
			return $this->getConnexion ();
		}
		$this->setConnexion ( sftp_z::creer_sftp_z ( $this->getListeOptions (), $this->getSortEnErreur () ) );
		
		return $this->getConnexion ()
			->setMachineDistante ( $machine_distante )
			->ssh_connect ();
	}

	/**
	 * Verifie/Set la liste option avec les variables obligatoires pour le ftp.
	 *
	 * options &$liste_option
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function verifie_variables_ftp(&$liste_option, $compte = 'compte') {
		$tableau_retour = false;
		
		if ($this->getListeOptions ()
			->verifie_parametre_standard ( "ftp[@using='oui']" ) !== false) {
			$this->getListeOptions ()
				->prepare_variable_standard ( array (
					"ftp",
					$compte,
					"user" 
			), "compte" );
			$this->getListeOptions ()
				->prepare_variable_standard ( array (
					"ftp",
					$compte,
					"passwd" 
			), "nopass" );
			$this->getListeOptions ()
				->prepare_variable_standard ( array (
					"ftp",
					$compte,
					"serveur" 
			), "noserv" );
			$this->getListeOptions ()
				->prepare_variable_standard ( array (
					"ftp",
					$compte,
					"port" 
			), "21" );
			$this->getListeOptions ()
				->prepare_variable_standard ( array (
					"ftp",
					$compte,
					"timeout" 
			), "10" );
			
			$tableau_retour = true;
		}
		
		return $tableau_retour;
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml et creer un objet FTP.<br>
	 * Include : $INCLUDE_FONCTIONS<br>
	 * Arguments reconnus :<br>
	 * --ftp_using=oui <br>
	 * --ftp_user=xx <br>
	 * --ftp_passwd=xx <br>
	 * --ftp_serveur=xx <br>
	 * --ftp_port=xx <br>
	 * --ftp_timeout=xx <br>
	 * --ftp_sort_en_erreur=oui <br>
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur les arguments
	 * @return ftp|false Renvoi un objet FTP, FALSE sinon.
	 */
	public function creer_connexion_ftp(&$liste_option, $compte = 'compte', $connect = true, $force = false, $nbretry = 3) {
		//voir help FTP
		if ($force === false && $this->getConnexion () !== false && $this->getConnexion () instanceof ftp) {
			$this->onInfo ( "On utilise la connexion ftp active." );
			return true;
		}
		$liste_donnees = $this->verifie_variables_ftp ( $liste_option, $compte );
		
		if ($liste_donnees) {
			$connexion = ftp::creer_ftp ( $liste_option, $liste_option->getOption ( array (
					"ftp",
					$compte,
					"user" 
			) ), $liste_option->getOption ( array (
					"ftp",
					$compte,
					"passwd" 
			) ), $liste_option->getOption ( array (
					"ftp",
					$compte,
					"port" 
			) ), $liste_option->getOption ( array (
					"ftp",
					$compte,
					"timeout" 
			) ), $this->getListeOptions ()
				->verifie_parametre_standard ( "ftp[@sort_en_erreur='oui']" ) );
			$connexion->setPassive ( $this->getListeOptions ()
				->verifie_parametre_standard ( array (
					"ftp",
					$compte . "[@passive='oui']" 
			) ) );
			if ($connexion instanceof ftp && $connect) {
				$connexion->setNbRetry ( $nbretry );
				$connexion->connect ( $liste_option->getOption ( array (
						"ftp",
						$compte,
						"serveur" 
				) ) );
			}
			
			$this->setConnexion ( $connexion );
			return true;
		}
		
		return false;
	}

	/**
	 * Verifie/Set la liste option avec les variables obligatoires pour le ftp.
	 *
	 * options &$liste_option
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function verifie_variables_socket(&$liste_option) {
		$tableau_retour = false;
		
		if ($this->getListeOptions ()
			->verifie_parametre_standard ( "socket[@using='oui']" ) !== false) {
			$this->getListeOptions ()
				->prepare_variable_standard ( array (
					"socket",
					"serveur" 
			), "" );
			$this->getListeOptions ()
				->prepare_variable_standard ( array (
					"socket",
					"port" 
			), "21" );
			
			$tableau_retour = true;
		}
		
		return $tableau_retour;
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml et creer un objet socket.<br>
	 * Include : $INCLUDE_FONCTIONS<br>
	 * Arguments reconnus :<br>
	 * --socket_using=oui <br>
	 * --socket_user=xx <br>
	 * --socket_passwd=xx <br>
	 * --socket_serveur=xx <br>
	 * --socket_port=xx <br>
	 * --socket_timeout=xx <br>
	 * --socket_sort_en_erreur=oui <br>
	 *
	 * @param options &$liste_option Pointeur sur les arguments
	 * @return socket|false Renvoi un objet socket, FALSE sinon.
	 */
	public function creer_connexion_socket_tcp(&$liste_option) {
		//voir help socket
		$liste_donnees = $this->verifie_variables_socket ( $liste_option );
		
		if ($liste_donnees) {
			$connexion = socket::creer_socket ( $liste_option, "tcp://" . $liste_option->getOption ( array (
					"socket",
					"serveur" 
			) ) . ":" . $liste_option->getOption ( array (
					"socket",
					"port" 
			) ), $this->getListeOptions ()
				->verifie_parametre_standard ( "socket[@sort_en_erreur='oui']" ) );
		} else {
			$connexion = false;
		}
		
		$this->onDebug ( $connexion, 2 );
		return $connexion;
	}

	/**
	 * Verifie/Set la liste option avec les variables obligatoires pour le rsync.
	 * @codeCoverageIgnore
	 * options &$liste_option
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function verifie_variables_rsync(&$liste_option) {
		$retour = false;
		
		//if ($this->getListeOptions()->verifie_parametre_standard("rsync[@using='oui']")!==false) {
		

		$this->getListeOptions ()
			->prepare_variable_standard ( array (
				"commande",
				"rsync" 
		), $this->class_standard->recupere_chemin_commande ( "rsync" ) );
		$this->getListeOptions ()
			->prepare_variable_standard ( array (
				"commande",
				"mkdir" 
		), $this->class_standard->recupere_chemin_commande ( "mkdir" ) );
		$this->getListeOptions ()
			->prepare_variable_standard ( array (
				"commande",
				"ssh" 
		), $this->class_standard->recupere_chemin_commande ( "ssh" ) );
		
		$retour = true;
		//}
		

		return $retour;
	}

	/**
	 * Creer la connexion en fonction du type demande.
	 * @codeCoverageIgnore
	 * @param options $liste_option Pointeur sur un objet options.
	 * @param string $type_connexion Type de connexion ssh/rsync/ftp.
	 * @param String $compte user du comte a utiliser (compte par defaut).
	 */
	public function creer_objet_flux(&$liste_option, $type_connexion, $compte = 'compte') {
		switch ($type_connexion) {
			case "ssh" :
				$this->onDebug ( "creer_connexion::protocole : SSH", 1 );
				$this->creer_connexion_ssh ( $compte );
				
				break;
			case "sftp" :
				$this->onDebug ( "creer_connexion::protocole : SFTP", 1 );
				$this->creer_connexion_sftp ( $compte );
				
				break;
			case "rsync" :
				$this->setConnexion ( true );
				break;
			case "ftp" :
				$this->onDebug ( "creer_connexion::protocole : FTP", 1 );
				$this->creer_connexion_ftp ( $liste_option, $compte, false );
				
				break;
			default :
				$this->onWarning ( "creer_connexion::Le type de connexion : " . $type_connexion . " n'existe pas." );
		}
		
		if ($this->getConnexion () === false) {
			return $this->onError ( "Ils manquant des variables pour creer la connexion." );
		}
		
		return true;
	}

	/**
	 * Affiche le debug complet pour la copie.
	 *
	 * @param string $protocole GET ou PUT pour l'affichage.
	 * @param string $source Chemin complet de la source a copier.
	 * @param string $dest	Chemin complet de la destination local.
	 * @param string $type_connexion Type de connexion ssh/rsync.
	 * @param string|false $machine_dest Machine de destination.
	 * @param string|false $machine_source|false Machine source.
	 * @return true
	 */
	public function affiche_resume_debug($protocole, $source, $dest, $type_connexion, $machine_dest = false, $machine_source = false, $compte = 'compte') {
		$this->onDebug ( "Type de copie : " . $protocole, 2 );
		if ($machine_dest) {
			$this->onDebug ( "Machine_dest : " . $machine_dest, 2 );
		}
		if ($machine_source) {
			$this->onDebug ( "Machine_source : " . $machine_source, 2 );
		}
		$this->onDebug ( "source : " . $source, 2 );
		$this->onDebug ( "dest : " . $dest, 2 );
		$this->onDebug ( "type connexion : " . $type_connexion, 2 );
		$this->onDebug ( "compte  : " . $compte, 2 );
		
		return true;
	}

	/**
	 * recupere la cle privee
	 *
	 * @param options $liste_option Pointeur sur un objet options.
	 * @param bool $rsync commande rsync ou ssh.
	 * @return string private key.
	 */
	public function retrouve_privkey($ssh = false, $sftp = false) {
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"ssh",
				"privkey" 
		) ) !== false) {
			if ($ssh) {
				$privkey = " -i " . $this->getListeOptions ()
					->renvoi_variables_standard ( array (
						"ssh",
						"privkey" 
				) );
			} elseif ($sftp) {
				$privkey = " -oIdentityFile=" . $this->getListeOptions ()
					->renvoi_variables_standard ( array (
						"ssh",
						"privkey" 
				) );
			} else {
				//par defaut rsync
				$privkey = " -e \"ssh -i " . $this->getListeOptions ()
					->renvoi_variables_standard ( array (
						"ssh",
						"privkey" 
				) ) . "\"";
			}
		} else {
			$privkey = "";
		}
		
		return $privkey;
	}

	/**
	 * Permet d'envoyer des fichiers a partir d'une machine distante.<br>
	 * Necessite une connexion SSH sans mot de passe (cle partagee).
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur un objet options.
	 * @param string $machine_dest Machine de destination.
	 * @param string $source Chemin complet de la source a copier.
	 * @param string $dest	Chemin complet de la destination local.
	 * @param string $type_connexion Type de connexion ssh/rsync.
	 * @param string $compte compte de connexion (compte/compte2).
	 * @param Bool $erreur Affiche les erreurs true/false.
	 * @param Bool $dest_is_dir Permet de savoir si la destination est un dossier.
	 * @param ssh_z|ftp $connexion_active connexion ftp/ssh existante
	 * @return bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function envoi_donnee_standard(&$liste_option, $machine_dest, $source, $dest, $compte = "compte", $type_connexion = "ssh", $option_scp = "-r", $erreur = true, $dest_is_dir = true) {
		$this->onDebug ( "fonctions_standards_flux::envoi_donnee_standard", 2 );
		if ($liste_option->verifie_option_existe ( "netname" ) === false) {
			$liste_option->setOption ( "netname", "" );
		}
		
		$this->affiche_resume_debug ( "PUT", $source, $dest, $type_connexion, $machine_dest, $liste_option->getOption ( "netname" ), $compte );
		
		//Si on est deja sur le filer, on ne fait pas de connexion
		if ($machine_dest != $liste_option->getOption ( "netname" )) {
			$this->onInfo ( $machine_dest . "!=" . $liste_option->getOption ( "netname" ) );
			if (! $dest_is_dir)
				$dossier_dest = dirname ( $dest );
			else
				$dossier_dest = $dest;
			
			switch ($type_connexion) {
				case "ssh" :
					$this->onInfo ( "protocole : SSH" );
					
					$this->creer_objet_flux ( $liste_option, $type_connexion, $compte );
					if ($this->getConnexion ()) {
						if ($this->getConnexion ()
							->ssh_connect ( $machine_dest )) {
							$this->getConnexion ()
								->creer_dossier ( $dossier_dest, $liste_option->getOption ( array (
									"commande",
									"mkdir" 
							) ) );
							//on copie le fichier
							$CODE_RETOUR = $this->copie_ssh ( $liste_option, $source, $dest, $machine_dest, "envoi", $erreur, $option_scp );
						} else {
							return $this->onError ( "La connexion ssh ne peut pas etre activee." );
						}
					} else {
						return $this->onError ( "Il manque des variables pour creer l'objet SSH." );
					}
					
					break;
				case "sftp" :
					$this->onInfo ( "protocole : SFTP" );
					
					$this->creer_objet_flux ( $liste_option, $type_connexion, $compte );
					if ($this->getConnexion ()) {
						$this->onDebug ( "Objet SFTP OK", 1 );
						if ($this->getConnexion ()
							->ssh_connect ( $machine_dest ) && $this->getConnexion ()
							->sftp_connect ( $machine_dest )) {
							
							$this->getConnexion ()
								->creer_repertoire ( $dossier_dest );
							//on copie le fichier
							$CODE_RETOUR = $this->copie_sftp ( $liste_option, $source, $dest, $machine_dest, "envoi", $erreur );
						} else {
							return $this->onError ( "La connexion sftp ne peut pas etre activee." );
						}
					} else {
						return $this->onError ( "Il manque des variables pour creer l'objet SSH(SFTP)." );
					}
					
					break;
				case "rsync" :
					$this->onInfo ( "protocole : RSYNC" );
					$this->verifie_variables_rsync ( $liste_option );
					$mkdir = $liste_option->getOption ( array (
							"commande",
							"mkdir" 
					), true );
					if ($mkdir === false) {
						$mkdir = $this->class_standard->recupere_chemin_commande ( "mkdir" );
					}
					$CMD = $liste_option->getOption ( array (
							"commande",
							"ssh" 
					) ) . " " . $this->retrouve_privkey ( true ) . " " . $machine_dest . " \"if [ ! -d \\\"" . $dossier_dest . "\\\" ]; then " . $liste_option->getOption ( array (
							"commande",
							"mkdir" 
					) ) . " -p " . $dossier_dest . " ; fi\"";
					$output = $this->class_standard->applique_commande_systeme ( $CMD );
					$CODE_RETOUR = $this->copie_rsync ( $liste_option, $source, $dest, $machine_dest, "envoi", $erreur );
					break;
				case "ftp" :
					$this->onInfo ( "protocole : FTP" );
					
					$this->creer_objet_flux ( $liste_option, $type_connexion, $compte );
					if ($this->getConnexion ()) {
						if ($this->getConnexion ()
							->connect ( $machine_dest )) {
							if ($dossier_dest !== "." && $dossier_dest !== "./") {
								$nbretry = 0;
								$sleep = 0;
								$retour = false;
								while ( $retour === false && $nbretry < $this->getConnexion ()
									->getNbRetry () ) {
									sleep ( $sleep );
									$retour = $this->getConnexion ()
										->creer_dossier ( $dossier_dest );
									$nbretry ++;
									$sleep ++;
								}
							} else {
								$retour = true;
							}
							//Si le dossier de destination existe bien, on copie
							if ($retour) {
								$CODE_RETOUR = $this->copie_ftp ( $liste_option, $machine_dest, $source, $dest, "envoi", $erreur );
							}
						} else {
							return $this->onError ( "La connexion ftp ne peut pas etre activee." );
						}
					} else {
						return $this->onError ( "Il manque des variables pour creer l'objet FTP." );
					}
					
					break;
				default :
					$this->onWarning ( "Le type de connexion : " . $type_connexion . " n'existe pas." );
			}
		} elseif ($source != $dest) {
			$this->onInfo ( "protocole : CP" );
			//Si on est sur la meme machine, mais le fichier de destination se nomme/est range differement, on le copie
			$this->onInfo ( "(CP) Copie de type PUT" . "  avec les parametres suivants : Machine " . $machine_dest . ", fichier source : " . $source . ", fichier destination : " . $dest );
			$retour = fichier::copie ( $source, $dest );
			if ($retour === 0) {
				$CODE_RETOUR = true;
			} else {
				$CODE_RETOUR = false;
			}
		} else {
			$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Permet de recuperer des fichiers a partir d'une machine distante.<br>
	 * Necessite une connexion SSH sans mot de passe (cle partagee).
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur un objet options.
	 * @param string $machine_source Machine source.
	 * @param string $source Chemin complet de la source a copier.
	 * @param string $dest	Chemin complet de la destination local.
	 * @param string $compte compte de connexion (compte/compte2).
	 * @param string $type_connexion Type de connexion ssh/rsync.
	 * @param Bool $erreur Affiche les erreurs true/false.
	 * @param Bool $dest_is_dir Permet de savoir si la destination est un dossier.
	 * @param ssh_z|ftp $connexion_active connexion ftp/ssh existante
	 * @return bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function copie_donnee_standard(&$liste_option, $machine_source, $source, $dest, $compte = "compte", $type_connexion = "ssh", $option_scp = "-r", $erreur = true, $dest_is_dir = true) {
		$this->onDebug ( "fonctions_standards_flux::copie_donnee_standard", 2 );
		
		if ($liste_option->verifie_option_existe ( "netname" ) === false) {
			$liste_option->setOption ( "netname", "" );
		}
		
		$this->affiche_resume_debug ( "GET", $source, $dest, $type_connexion, $liste_option->getOption ( "netname" ), $machine_source, $compte );
		
		//Si on est deja sur le filer, on ne fait pas de connexion
		if ($machine_source != $liste_option->getOption ( "netname" )) {
			$this->onInfo ( $machine_source . "!=" . $liste_option->getOption ( "netname" ) );
			if (! $dest_is_dir)
				$dossier_dest = dirname ( $dest );
			else
				$dossier_dest = $dest;
			if (repertoire::tester_repertoire_existe ( $dossier_dest ) != true)
				repertoire::creer_nouveau_repertoire ( $dossier_dest );
			
			switch ($type_connexion) {
				case "ssh" :
					$this->onInfo ( "protocole : SSH" );
					
					$this->creer_objet_flux ( $liste_option, $type_connexion, $compte );
					if ($this->getConnexion ()) {
						if ($this->getConnexion ()
							->ssh_connect ( $machine_source )) {
							$CODE_RETOUR = $this->copie_ssh ( $liste_option, $source, $dest, $machine_source, "recupere", $erreur, $option_scp );
						} else {
							return $this->onError ( "La connexion ssh ne peut pas etre activee." );
						}
					} else {
						return $this->onError ( "Il manque des variables pour creer l'objet SSH." );
					}
					
					break;
				case "sftp" :
					$this->onInfo ( "protocole : SFTP" );
					
					$this->creer_objet_flux ( $liste_option, $type_connexion, $compte );
					if ($this->getConnexion ()) {
						if ($this->getConnexion ()
							->ssh_connect ( $machine_dest ) && $this->getConnexion ()
							->sftp_connect ( $machine_source )) {
							$CODE_RETOUR = $this->copie_sftp ( $liste_option, $source, $dest, $machine_source, "recupere", $erreur );
						} else {
							return $this->onError ( "La connexion sftp ne peut pas etre activee." );
						}
					} else {
						return $this->onError ( "Il manque des variables pour creer l'objet SSH(SFTP)." );
					}
					
					break;
				case "rsync" :
					$this->onInfo ( "protocole : RSYNC" );
					$CODE_RETOUR = $this->copie_rsync ( $liste_option, $source, $dest, $machine_source, "recupere", $erreur );
					break;
				case "ftp" :
					$this->onInfo ( "protocole : FTP" );
					
					$this->creer_objet_flux ( $liste_option, $type_connexion, $compte );
					if ($this->getConnexion ()) {
						if ($this->getConnexion ()
							->connect ( $machine_source )) {
							$CODE_RETOUR = $this->copie_ftp ( $liste_option, $machine_source, $source, $dest, "recupere", $erreur );
						} else {
							return $this->onError ( "La connexion ftp ne peut pas etre activee." );
						}
					} else {
						return $this->onError ( "Il manque des variables pour creer l'objet FTP." );
					}
					
					break;
				default :
					$this->onWarning ( "Le type de connexion : " . $type_connexion . " n'existe pas." );
			}
		} elseif ($source != $dest) {
			$this->onInfo ( "protocole : CP" );
			$this->onInfo ( "(CP) Copie de type GET" . "  avec les parametres suivants : Machine " . $machine_source . ", fichier source : " . $source . ", fichier destination : " . $dest );
			//Si on est sur la meme machine, mais le fichier source se nomme/est range differement, on le copie
			$retour = fichier::copie ( $source, $dest );
			if ($retour === 0) {
				$CODE_RETOUR = true;
			} else {
				$CODE_RETOUR = false;
			}
		} else {
			$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Fait une copie SCP.<br>
	 * Necessite une connexion SSH sans mot de passe (cle partagee).
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur un objet options.
	 * @param ssh_z &$ssh Connexion ssh active.
	 * @param string $source Chemin complet de la source a copier.
	 * @param string $dest	Chemin complet de la destination local.
	 * @param string $machine_source Machine source.
	 * @param string $type_copie Type de copie envoi/recupere.
	 * @param Bool $erreur Affiche les erreurs true/false.
	 * @return bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function copie_ssh(&$liste_option, $source, $dest, $machine_source, $type_copie = "recupere", $erreur = true, $option_scp = "-r") {
		$CODE_RETOUR = false;
		$this->onInfo ( "(SSH) Copie de type " . $type_copie . " en SCP avec les parametres suivants : Machine " . $machine_source . ", fichier source : " . $source . ", fichier destination : " . $dest );
		
		$var_return = $this->getConnexion ()
			->setMachineDistante ( $machine_source )
			->scp ( $type_copie, $source, $dest, $erreur, $option_scp );
		$this->onDebug ( "(SSH) Retour de la copie : " . $var_return, 2 );
		
		if (is_numeric ( $var_return ) && $var_return == 0)
			$CODE_RETOUR = true;
		else {
			if ($erreur) {
				return $this->onError ( "(SSH) Erreur lors de la copie de " . $source . " vers " . $dest . " avec le code retour : ", $var_return );
			} else {
				$this->onWarning ( "(SSH) Erreur lors de la copie de " . $source . " vers " . $dest . " ." );
				$this->onWarning ( $var_return );
			}
			$CODE_RETOUR = false;
		}
		
		$this->onDebug ( "(SSH) Fin de la recuperation des fichiers.", 1 );
		
		return $CODE_RETOUR;
	}

	/**
	 * Fait un copie SCP.<br>
	 * Necessite une connexion SSH sans mot de passe (cle partagee).
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur un objet options.
	 * @param ssh_z &$ssh Connexion ssh active.
	 * @param string $source Chemin complet de la source a copier.
	 * @param string $dest	Chemin complet de la destination local.
	 * @param string $machine_source Machine source.
	 * @param string $type_copie Type de copie envoi/recupere.
	 * @param Bool $erreur Affiche les erreurs true/false.
	 * @return bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function copie_sftp(&$liste_option, $source, $dest, $machine_source, $type_copie = "recupere", $erreur = true) {
		$CODE_RETOUR = false;
		$this->onInfo ( "(SFTP) Copie de type " . $type_copie . " en SFTP avec les parametres suivants : Machine " . $machine_source . ", fichier source : " . $source . ", fichier destination : " . $dest );
		
		switch ($type_copie) {
			case "recupere" :
				$var_return = $this->getConnexion ()
					->recupere_fichier ( $source, $dest );
				break;
			case "envoi" :
				$var_return = $this->getConnexion ()
					->envoi_fichier ( $source, $dest );
				break;
			default :
				$var_return = false;
		}
		$this->onDebug ( "(SFTP) Retour de la copie : " . $var_return, 2 );
		
		if ($var_return)
			$CODE_RETOUR = true;
		else {
			if ($erreur) {
				return $this->onError ( "(SFTP) Erreur lors de la copie de " . $source . " vers " . $dest . " avec le code retour : ", $var_return );
			} else {
				$this->onWarning ( "(SFTP) Erreur lors de la copie de " . $source . " vers " . $dest . " ." );
				$this->onWarning ( $var_return );
			}
			$CODE_RETOUR = false;
		}
		
		$this->onDebug ( "(SFTP) Fin de la recuperation des fichiers.", 1 );
		
		return $CODE_RETOUR;
	}

	/**
	 * Fait un copie RSYNC.<br>
	 * Necessite un case ["commande"]["rsync"]="/.../rsync" dans la liste des options.<br>
	 * Necessite une connexion SSH sans mot de passe (cle partagee).
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur un objet options.
	 * @param string $source Chemin complet de la source a copier.
	 * @param string $dest	Chemin complet de la destination local.
	 * @param string $machine_source Machine source.
	 * @param string $type_copie Type de copie envoi/recupere.
	 * @param Bool $erreur Affiche les erreurs true/false.
	 * @return bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function copie_rsync(&$liste_option, $source, $dest, $machine_source, $type_copie = "recupere", $erreur = true) {
		$CODE_RETOUR = false;
		
		$this->onInfo ( "(RSYNC) Copie de type " . $type_copie . " en RSYNC avec les parametres suivants : Machine " . $machine_source . ", fichier source : " . $source . ", fichier destination : " . $dest );
		
		if ($type_copie == "recupere") {
			$CMD = $liste_option->getOption ( array (
					"commande",
					"rsync" 
			) ) . " -avlu " . $this->retrouve_privkey ( false ) . " " . $machine_source . ":" . $source . " " . $dest;
		} else {
			$CMD = $liste_option->getOption ( array (
					"commande",
					"rsync" 
			) ) . " -avlu " . $this->retrouve_privkey ( false ) . " " . $source . " " . $machine_source . ":" . $dest;
		}
		
		$this->onDebug ( $CMD, 1 );
		$var_return = fonctions_standards::applique_commande_systeme ( $CMD, $erreur );
		if (is_numeric ( $var_return [0] ) && $var_return [0] == 0) {
			$CODE_RETOUR = true;
		} else {
			if ($erreur) {
				return $this->onError ( "(RSYNC) Erreur lors de la copie de " . $source . " vers " . $dest . " .", $var_return );
			} else {
				$this->onWarning ( "(RSYNC) Erreur lors de la copie de " . $source . " vers " . $dest . " ." );
				$this->onWarning ( $var_return );
			}
			$CODE_RETOUR = false;
		}
		
		$this->onDebug ( "(RSYNC) Fin de la recuperation des fichiers avec le CODE RETOUR : " . $CODE_RETOUR, 1 );
		
		return $CODE_RETOUR;
	}

	/**
	 * Fait un copie FTP.<br>
	 * @codeCoverageIgnore
	 * @param options &$liste_option Pointeur sur un objet options.
	 * @param ftp &$ftp Connexion ftp active.
	 * @param string $source Chemin complet de la source a copier.
	 * @param string $dest	Chemin complet de la destination local.
	 * @param string $type_copie Type de copie envoi/recupere.
	 * @param Bool $erreur Affiche les erreurs true/false.
	 * @return bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function copie_ftp(&$liste_option, $machine_source, $source, $dest, $type_copie = "recupere", $erreur = true) {
		$CODE_RETOUR = false;
		$this->onInfo ( "(FTP) Copie de type " . $type_copie . " en FTP avec les parametres suivants : Machine " . $machine_source . ", fichier source : " . $source . ", fichier destination : " . $dest );
		if ($type_copie == "recupere") {
			$var_return = $this->getConnexion ()
				->recupere ( $source, $dest );
		} else {
			$var_return = $this->getConnexion ()
				->envoi ( $source, $dest );
		}
		$this->onDebug ( "(FTP) Retour de la copie : " . $var_return, 2 );
		
		if ($var_return)
			$CODE_RETOUR = true;
		else {
			if ($erreur) {
				return $this->onError ( "(FTP) Erreur lors de la copie de " . $source . " vers " . $dest . " avec le code retour : ", $var_return );
			} else {
				$this->onWarning ( "(FTP) Erreur lors de la copie de " . $source . " vers " . $dest . " : " . $var_return );
			}
			$CODE_RETOUR = false;
		}
		
		$this->onDebug ( "(FTP) Fin de la recuperation des fichiers.", 1 );
		
		return $CODE_RETOUR;
	}

	/***************** ACCESSEURS *********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getConnexion() {
		return $this->connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnexion($connexion) {
		$this->connexion = $connexion;
		return $this;
	}
/***************** ACCESSEURS *********************/
}
?>