<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class fonctions_standards_moniteur<br>
 * Gere un point de monitoring.
 *
 * @package Lib
 * @subpackage Monitoring
 */
class fonctions_standards_moniteur extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var moniteur
	 */
	private $moniteur;
	/**
	 * var privee
	 *
	 * @access private
	 * @var contraintesHoraire
	 */
	private $horaire;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fonctions_standards_moniteur.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param moniteur $moniteur Pointeur sur un objet moniteur
	 * @param contraintesHoraire $horaire Pointeur sur un objet contraintesHoraire
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standards_moniteur
	 */
	static function &creer_fonctions_standards_moniteur(&$liste_option, &$moniteur, &$horaire, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fonctions_standards_moniteur ( $entete, $sort_en_erreur );
		$liste_class = array (
				"options" => $liste_option,
				"moniteur" => $moniteur,
				"contraintesHoraire" => $horaire 
		);
		$objet->_initialise ( $liste_class );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standards_moniteur
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		if (! isset ( $liste_class ["moniteur"] )) {
			return $this->onError ( "il faut un objet de type moniteur" );
		}
		if (! isset ( $liste_class ["contraintesHoraire"] )) {
			return $this->onError ( "il faut un objet de type contraintesHoraire" );
		}
		$this->setMoniteur ( $liste_class ["moniteur"] )
			->setHoraire ( $liste_class ["contraintesHoraire"] );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * @codeCoverageIgnore
	 * @param string $entete        	
	 * @param string $sort_en_erreur        	
	 */
	public function __construct($entete = __CLASS__, $sort_en_erreur = false) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * ******************* Fichier log abstract_log **************************
	 */
	/**
	 * Parse une ligne de log PHP.<br>
	 * Il met a jour un objet moniteur.
	 *
	 * @param string $ligne ligne a parser.
	 * @return Bool pour indiquer si le mot [Exit] a ete trouve.
	 */
	public function parse_ligne_log($ligne, $message_ok, $message_false, $active_warning = false) {
		$this->onDebug ( "Ligne en cours : " . $ligne, 1 );
		if (strpos ( $ligne, "[Info]" ) !== 0) {
			if (strpos ( $ligne, "[Warning]" ) === 0) {
				if ($active_warning) {
					$this->onDebug ( "On a un warning.", 2 );
					$this->getMoniteur ()
						->ecrit ( $ligne );
				}
			} elseif (strpos ( $ligne, "[Error]" ) === 0) {
				$this->onDebug ( "On a une erreur.", 2 );
				$this->getMoniteur ()
					->ecrit ( $ligne );
			} elseif (strpos ( $ligne, "[Exit]" ) === 0) {
				// si on traite le code Exit
				$code_retour = trim ( substr ( $ligne, strlen ( "[Exit]" ) ) );
				switch ($code_retour) {
					case "0" :
						if ($message_ok != "")
							$this->getMoniteur ()
								->ecrit ( $message_ok );
						break;
					default :
						$this->getMoniteur ()
							->ecrit ( $message_false );
				}
				return true;
			}
		}
		
		return false;
	}

	/**
	 * Parse un fichier de log PHP obligatoire.<br>
	 * Il met a jour un objet moniteur.
	 *
	 * @param string $fichier Chemin complet du fichier a parser.
	 * @param string $message_ok Message a afficher si tout est OK.
	 * @param string $message_false Message a afficher en cas d'erreur.
	 * @return Bool pour indiquer si le mot [Exit] a ete trouve.
	 */
	public function parse_fichier_log($fichier, $message_ok, $message_false, $active_warning = false) {
		$flag_exit = false;
		
		try {
			$fichier_en_cours = fichier::creer_fichier ( $this->getListeOptions (), $fichier, "non", false );
			$presence = $fichier_en_cours->ouvrir ();
		} catch ( Exception $e ) {
			$this->getMoniteur ()
				->ecrit ( "Le fichier : " . $fichier . " n'a pas pu &ecirc;tre ouvert\n" );
			return false;
		}
		
		while ( $ligne = $fichier_en_cours->lit_une_ligne () ) {
			if ($this->parse_ligne_log ( $ligne, $message_ok, $message_false, $active_warning )) {
				$flag_exit = true;
			}
		}
		$fichier_en_cours->close ();
		
		return $flag_exit;
	}

	/**
	 * Parse un fichier de log PHP obligatoire.<br>
	 * Permet de parser aussi un envoi de mail.
	 * Il met a jour un objet moniteur.
	 *
	 * @param string $fichier Chemin complet du fichier a parser.
	 * @param string|false $check_mail Domaine a verifier pour l'envoi de mail, FALSE si pas de check.
	 * @param string $message_false Message a afficher en cas d'erreur.
	 * @return Bool pour indiquer si le mot [Exit] a ete trouve.
	 */
	public function parse_fichier_log_with_mail($fichier, $check_mail = false, $message_ok = "Code Exit : 0", $message_false = "Code de sortie en erreur", $active_warning = false) {
		$this->onDebug ( "parse_fichier_log_with_mail : " . $fichier, 1 );
		$flag_mail = true;
		$flag_exit = false;
		
		try {
			$fichier_en_cours = fichier::creer_fichier ( $this->getListeOptions (), $fichier, "non", false );
			$presence = $fichier_en_cours->ouvrir ();
		} catch ( Exception $e ) {
			$this->getMoniteur ()
				->ecrit ( "Le fichier : " . $fichier . " n'a pas pu &ecirc;tre ouvert\n" );
			return false;
		}
		
		while ( $ligne = $fichier_en_cours->lit_une_ligne () ) {
			if ($this->parse_ligne_log ( $ligne, $message_ok, $message_false, $active_warning )) {
				$flag_exit = true;
			}
			if ($check_mail !== false && strpos ( $ligne, "Liste destinataire" ) !== false) {
				$this->onDebug ( "On check le mail.", 1 );
				$liste_mail = explode ( ",", $ligne );
				if (is_array ( $liste_mail )) {
					for($i = 0; $i < count ( $liste_mail ); $i ++) {
						$liste_domaine = explode ( "@", $liste_mail [$i] );
						if ($flag_mail && isset ( $liste_domaine [1] ) && strpos ( $liste_domaine [1], $check_mail ) !== false)
							$flag_mail = false;
					}
					
					// Si aucun mail n'a mis le flag a faux, alors aucun domaine ne correspond
					if ($flag_mail) {
						$this->getMoniteur ()
							->ecrit ( "Aucun mail qui comporte le domaine : " . $check_mail );
						$flag_mail = false;
					}
				}
			}
		}
		$fichier_en_cours->close ();
		
		// Si on a jamais la ligne Liste destinataire :
		if ($check_mail !== false && $flag_mail) {
			$this->getMoniteur ()
				->ecrit ( "Aucun mail envoy&eacute;." );
		}
		
		return $flag_exit;
	}

	/**
	 * ******************* Fichier log abstract_log **************************
	 */
	
	/**
	 * ******************* Processus ram **************************
	 */
	
	/**
	 * Verifie la presence d'un ou plusieurs processus.<br>
	 * Il affiche la liste de ces processus dans le moniteur.
	 *
	 * @param string $nom_processus
	 *        	Processus a retrouver.
	 * @return array false des processus, false sinon.
	 */
	public function check_processus($nom_processus, $type = "linux") {
		if ($nom_processus == "no_process") {
			return array (
					"1" 
			);
		}
		
		switch ($type) {
			case "win" :
				// @codeCoverageIgnoreStart
				$cmd = "tasklist |find \"" . $nom_processus . "\"";
				break;
				// @codeCoverageIgnoreEnd
			case "linux" :
			default :
				$cmd = "ps ax -eo pid,args | grep " . $nom_processus . "|grep -v grep |grep -v parser_log.php";
		}
		
		$liste_ps = fonctions_standards::applique_commande_systeme ( $cmd, "non" );
		// Si il y en a un, on l'affiche
		if (count ( $liste_ps ) > 1 && is_array ( $liste_ps )) {
			for($i = 1; $i < count ( $liste_ps ); $i ++) {
				$this->getMoniteur ()
					->ecrit ( $liste_ps [$i] . "\n" );
			}
		}
		
		$this->onDebug ( $liste_ps, 1 );
		return $liste_ps;
	}

	/**
	 * ******************* Processus ram **************************
	 */

	/**
	 * ******************* MongoDB **************************
	 */
	
	/**
	 * Gestion de la table "runtime" de la MongoDB.
	 * @codeCoverageIgnore
	 * @param array $resultat_runtime_slurm        	
	 * @param string $type_traitement        	
	 * @return array
	 */
	public function valide_jobs(&$resultat_runtime_slurm, $type_traitement) {
		$liste_erreurs = array ();
		// On valide que tous les traitements sont ok
		foreach ( $resultat_runtime_slurm as $slurmJob ) {
			if ($slurmJob ["type"] != $type_traitement) {
				// Au cas ou
				continue;
			}
			switch ($slurmJob ["etat"]) {
				case "ok" :
					continue;
				case "corriger" :
				case "annuler" :
					continue;
				case "en cours" :
					// Valide l'heure d'attribution
					if ($this->horaire->activeAlarme ()) {
						$liste_erreurs [( string ) $slurmJob ["_id"]] ["job"] = $slurmJob;
						$liste_erreurs [( string ) $slurmJob ["_id"]] ["erreur"] = "Le Job : " . ( string ) $slurmJob ["_id"] . " n'est pas termin&eacute; apr&eacute;s " . $this->horaire->getHoraireDebutMax ();
					}
					break;
				case "distribution" :
					// Valide l'heure de distribution
					if ($this->horaire->activeAlarme ()) {
						$liste_erreurs [( string ) $slurmJob ["_id"]] ["job"] = $slurmJob;
						$liste_erreurs [( string ) $slurmJob ["_id"]] ["erreur"] = "Le Job : " . ( string ) $slurmJob ["_id"] . " n'est pas attribu&eacute; par slurm apr&eacute;s " . $this->horaire->getHoraireDebutMax ();
					}
					break;
				default :
					// Tout autre etat que les precedents
					$liste_erreurs [( string ) $slurmJob ["_id"]] ["job"] = $slurmJob;
					$liste_erreurs [( string ) $slurmJob ["_id"]] ["erreur"] = "Le job " . ( string ) $slurmJob ["_id"] . " (" . $slurmJob ["collection"] . ") est en " . $slurmJob ["etat"] . " dans slurm &agrave; la date : " . date ( "d-m-Y H:i:s", $slurmJob ["date_fin"]->sec );
			}
		}
		
		return $liste_erreurs;
	}

	/**
	 * Valide l'etat d'1 job dans la MongoDB.
	 * @codeCoverageIgnore
	 * @param gestion_bd_MongoDB $mongo        	
	 * @param array $job        	
	 * @param string $type_traitement        	
	 */
	public function valide_job(&$mongo, &$job, $type_traitement = "") {
		// On valide que le traitement est ok
		if ($type_traitement == "" || $job ["type"] == $type_traitement) {
			$entete = __CLASS__;
			$this->onDebug ( $entete . " est en etat " . $job ["etat"], 1 );
			$validation = $mongo->valide_etat_traitement ( $job );
			$this->onDebug ( "validation : " . $validation, 2 );
			switch ($validation) {
				case 1 :
					// Job en erreur
					$this->getMoniteur ()
						->ecrit ( $entete . " est en erreur &agrave; la date : " . date ( "d-m-Y H:i:s", $job ["date_fin"]->sec ) . "\n", "red" );
					return false;
					break;
				case 154 :
					// job en cours d'e traitement
					if ($this->getHoraire ()
						->setTimestampJour ( time () )
						->valideHeureFinGlobal ()) {
						$this->getMoniteur ()
							->ecrit ( $entete . " n'est pas termin&eacute; apr&eacute;s " . $this->getHoraire ()
							->getHoraireFinMax () . "\n" );
						return false;
					}
					$this->getMoniteur ()
						->ecrit ( $entete . " est en cours.\n" );
					break;
				case 155 :
					// job en cours d'attribution
					if ($this->getHoraire ()
						->setTimestampJour ( time () )
						->valideHeureFinGlobal ()) {
						$this->getMoniteur ()
							->ecrit ( $entete . " n'est pas attribu&eacute; par slurm apr&eacute;s " . $this->getHoraire ()
							->getHoraireFinMax () . "\n" );
						return false;
					}
					$this->getMoniteur ()
						->ecrit ( $entete . " est distribution.\n" );
					break;
				case 156 :
					// job est en warning
					$this->getMoniteur ()
						->ecrit ( $entete . " est en warning par slurm.\n" );
				case 0 :
				default :
				// Tout est ok
			}
		}
		
		return true;
	}

	/**
	 * Retrouve le jobs dans la MongoDB.
	 * @codeCoverageIgnore
	 * @param gestion_bd_MongoDB $mongo        	
	 * @param dates $liste_dates        	
	 * @param string $type_traitement        	
	 */
	public function retrouve_jobs_par_type(&$mongo, &$liste_dates, $type_traitement) {
		// /Si il y a des fichier et il n'y a pas de traitement a un horaire donnee, alors on alerte
		$resultat_runtime_slurm = $mongo->retrouve_liste_jobs ( $liste_dates->recupere_premier_jour () . " 00:00:00", $liste_dates->recupere_dernier_jour () . " 23:59:59", $type_traitement );
		if ($resultat_runtime_slurm) {
			if ($resultat_runtime_slurm->count () === 0) {
				// On valide l'heure
				if ($this->getHoraire ()
					->activeAlarme ()) {
					$this->getMoniteur ()
						->ecrit ( "Il n'y a aucun traitements de type " . $type_traitement . " &agrave; " . $this->getHoraire ()
						->getHoraireDebutMax () . ".\n", "red" );
				} else {
					$this->getMoniteur ()
						->ecrit ( "Il n'y a pas encore de " . $type_traitement . ".\n" );
				}
				return false;
			}
		}
		
		return $resultat_runtime_slurm;
	}

	/**
	 * Valide l'etat d'un job pour chaque fichier dans la MongoDB.
	 * @codeCoverageIgnore
	 * @param gestion_bd_MongoDB $mongo        	
	 * @param array $resultat_fichiers        	
	 * @param string $type_traitement        	
	 */
	public function valide_tous_les_fichiers(&$mongo, &$resultat_fichiers, $type_traitement) {
		$liste_job_connu = array ();
		
		$this->onDebug ( "Nombre de fichier a valider :" . $resultat_fichiers->count (), 1 );
		foreach ( $resultat_fichiers as $fichier ) {
			if (isset ( $fichier ["nom"] )) {
				$nom = $fichier ["nom"];
			} elseif (isset ( $fichier ["serial"] )) {
				$nom = $fichier ["serial"];
			} else {
				$nom = 'unknow';
			}
			
			if (isset ( $fichier ["jobs"] ) && is_array ( $fichier ["jobs"] )) {
				foreach ( $fichier ["jobs"] as $refjob ) {
					// On evite plusieurs fois le meme traitement
					if (isset ( $liste_job_connu [( string ) $refjob ['$id']] )) {
						continue;
					}
					
					$job = $mongo->getDb ()
						->getDBRef ( $refjob );
					// Si le job correspond au type de traitement recherche
					$liste_job_connu [( string ) $refjob ['$id']] = $job ['etat'];
					if ($this->valide_job ( $mongo, $moniteur, $horaire, $job, $type_traitement ) === false) {
						$this->onDebug ( $nom . " (" . ( string ) $fichier ["_id"] . ") a un job en etat " . $liste_job_connu [( string ) $refjob ['$id']], 1 );
					}
				}
			} else {
				// Si il n'y a pas de traitement pour ce fichier
				$this->onDebug ( $nom . " (" . ( string ) $fichier ["_id"] . ") n'a pas de job", 1 );
				if ($this->getHoraire ()
					->valideHeureFinGlobal ()) {
					$this->getMoniteur ()
						->ecrit ( "Le fichier " . $nom . " ( " . ( string ) $fichier ["_id"] . ") n'a pas de job\n" );
				}
			}
		}
		
		return true;
	}

	/**
	 * ******************* MongoDB **************************
	 */
	
	/************************* Accesseurs ************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getMoniteur() {
		return $this->moniteur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMoniteur(&$moniteur) {
		$this->moniteur = $moniteur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getHoraire() {
		return $this->horaire;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHoraire(&$horaire) {
		$this->horaire = $horaire;
		return $this;
	}
/************************* Accesseurs ************************/
}

?>