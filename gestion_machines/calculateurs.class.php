<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class calculateurs<br>
 *
 * Gere l'attribution et la liberation des jobs sur plusieurs calculateurs.<br>
 * Necessite la class calculateur pour fonctionner.
 * @package Lib
 * @subpackage Gestion_Machine
 */
class calculateurs extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	var $liste_machine = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	var $machine_id = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	var $attribution = array ();
	/**
	 * @ignore
	 * var privee
	 * @access private
	 * @var calculateur
	 */
	var $calculateur_ref = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type calculateurs.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return calculateurs
	 */
	static function &creer_calculateurs(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new calculateurs ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return calculateurs
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjetCalculateurRef ( calculateur::creer_calculateur ( $liste_class ["options"], "REF", "NetName", "IP", "Username", "Password", "FTPPassword", 0, 0, 0, 0, 0, 0, 0 ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et set la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Prend une liste de calculateurs avec leurs caracteristiques (cf class calculateur)
	 * et cree la liste de calculateur avec la class calculateur
	 *
	 * @param array $liste liste de machine avec tous leurs attribues (cf class calculateur sans le s)
	 * @return Bool Renvoi TRUE si des calculateurs sont creer, FALSE sinon.
	 * @throws Exception
	 */
	public function &charge_liste_calculateurs($liste) {
		if (is_array ( $liste ) && count ( $liste ) > 0) {
			$liste_calc = $this->getListeMachine ();
			foreach ( $liste as $row ) {
				if ($row ["NetName"] != "") {
					$calculateur = clone $this->getObjetCalculateurRef ();
					$calculateur->setNom ( $row ["Name"] )
						->setNetName ( $row ["NetName"] )
						->setIP ( $row ["IP"] )
						->setUsername ( $row ["Username"] )
						->setPassword ( $row ["Password"] )
						->setFTPPassword ( $row ["FTPPassword"] )
						->setDiskSpace ( $row ["DiskSpace"] )
						->setRamSpace ( $row ["RamSpace"] )
						->setMaxRamJob ( $row ["MaxRamJob"] )
						->setCPUUnit ( $row ["CPUUnit"] )
						->setMinCPUJob ( $row ["MinCPUJob"] )
						->setMaxCPUJob ( $row ["MaxCPUJob"] )
						->setMaxNbJob ( $row ["MaxNbJob"] );
					
					$pos = count ( $liste_calc );
					$liste_calc [$pos] = $calculateur;
					
					$this->setAddMachineId ( $row ["Name"], $pos );
				}
			}
			$this->setListeMachine ( $liste_calc );
		} else {
			return $this->onError ( "Pas de calculateurs dans la liste" );
		}
		
		if (count ( $this->getListeMachine () ) == 0) {
			return $this->onError ( "Pas de calculateurs dans la liste des machines" );
		}
		$this->onDebug ( $this->getListeMachine (), 2 );
		return $this;
	}

	/**
	 * Accesseur en lecture de la liste des NetName des calculateurs utilises
	 *
	 * @return array Liste des NetName des calculateurs utilises
	 */
	public function renvoi_liste_machine() {
		$liste_machine_en_cours = array ();
		foreach ( $this->getListeMachine () as $machine )
			$liste_machine_en_cours [] .= $machine->renvoi_donnees_machine ( "NetName" );
		
		return $liste_machine_en_cours;
	}

	/**
	 * Accesseur en lecture de la liste des NetName des calculateurs utilises
	 *
	 * @return array Liste ordre aleatoire des NetName des calculateurs utilises
	 */
	public function renvoi_liste_machine_aleatoire() {
		$liste_calc = $this->getListeMachine ();
		$new_liste = array ();
		$nb_machine = count ( $liste_calc );
		$num_depart = rand ( 0, $nb_machine );
		
		for($i = 0; $i < $nb_machine; $i ++) {
			$num_final = ($i + $num_depart) % $nb_machine;
			$new_liste [$i] = &$liste_calc [$num_final];
		}
		
		return $new_liste;
	}

	/**
	* Prend les valeurs RAM, Disk et CPU d'un job
	* et trouve une machine pouvant le recevoir
	*
	* @param int $ram RAM utilise par le job
	* @param int $disk Disque utilise par le job
	* @param int $cpu CPUUnit utilise par le job
	* @return string|false Renvoi l'id de la machine pouvant recevoir le job ou FALSE sinon.
	*/
	public function trouve_calculateur_libre($ram, $disk, $cpu) {
		$CODE_RETOUR = - 1;
		foreach ( $this->getListeMachine () as $id => $calculateur ) {
			$RETOUR = $calculateur->compare_valeur ( $ram, $disk, $cpu );
			if ($RETOUR === true) {
				if ($calculateur->renvoi_nb_job_en_cours () < $calculateur->renvoi_donnees_machine ( "MaxNbJob" )) {
					$CODE_RETOUR = $id;
				}
				break;
			} elseif ($CODE_RETOUR == - 1) {
				$CODE_RETOUR = $RETOUR;
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Renvoi le nombre de job maximum allouable en fonction de la liste des calculateurs.
	 *
	 * @return int nombre max de job.
	 */
	public function renvoi_nb_max_job() {
		$CODE_RETOUR = 0;
		foreach ( $this->getListeMachine () as $id => $calculateur ) {
			$CODE_RETOUR += $calculateur->renvoi_donnees_machine ( "MaxNbJob" );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Renvoi le nombre de job maximum allouable en fonction de la liste des calculateurs.
	 *
	 * @return int nombre max de job.
	 */
	public function renvoi_nb_attribution() {
		return count ( $this->getAttribution () );
	}

	/**
	 * Prend les valeurs RAM, Disk et CPU d'un job
	 * et attribue ce job a la machine referencee par ID
	 *
	 * @param int $id id de la machine attribuee au job
	 * @param int $ram RAM utilise par le job
	 * @param int $disk Disque utilise par le job
	 * @param int $cpu CPUUnit utilise par le job
	 * @return Bool Renvoi TRUE si le job est attribue ou FALSE sinon. -1 le calculateur ne supporte pas ce job.
	 */
	public function utilise_puissance_machine($id, $ram, $disk, $cpu) {
		$calculateur = $this->getCalculateur ( $id );
		if ($calculateur != NULL) {
			return $calculateur->utilise_puissance_calculateur ( $ram, $disk, $cpu );
		}
		
		return false;
	}

	/**
	 * Prend les valeurs RAM, Disk et CPU d'un job
	 * et des-attribue ce job a la machine referencee par ID
	 *
	 * @param int $id id de la machine attribuee au job
	 * @param int $ram RAM utilise par le job
	 * @param int $disk Disque utilise par le job
	 * @param int $cpu CPUUnit utilise par le job
	 * @return Bool Renvoi TRUE si le job est bien des-attribue ou FALSE si il n'y a plus de job sur la machine.
	 */
	public function libere_puissance_machine($id, $ram, $disk, $cpu) {
		$calculateur = $this->getCalculateur ( $id );
		if ($calculateur != NULL) {
			return $calculateur->libere_puissance_calculateur ( $ram, $disk, $cpu );
		}
		
		return false;
	}

	/**
	 * Prend les valeurs RAM, Disk et CPU d'un job (serial)
	 * et trouve une machine pouvant le recevoir.<br>
	 * Une fois la machine trouvee, il attribue le job.
	 *
	 * @param string $uniq_id Numero unique du job
	 * @param int $ram RAM utilise par le job
	 * @param int $disk Disque utilise par le job
	 * @param int $cpu CPUUnit utilise par le job
	 * @return string|false Renvoi le nom de la machine ayant recu le job ou FALSE si il n'y a pas eu d'attribution.
	 */
	public function attribut_calculateur($uniq_id, $ram, $disk, $cpu) {
		$liste_attribution = $this->getAttribution ();
		if (! isset ( $liste_attribution [$uniq_id] )) {
			$id = $this->trouve_calculateur_libre ( $ram, $disk, $cpu );
			if ($id !== false && $id != - 1) {
				$liste_attribution [$uniq_id] ["calculateur"] = $id;
				$liste_attribution [$uniq_id] ["ram"] = $ram;
				$liste_attribution [$uniq_id] ["disk"] = $disk;
				$liste_attribution [$uniq_id] ["cpu"] = $cpu;
				$this->utilise_puissance_machine ( $id, $ram, $disk, $cpu );
				$this->setAttribution ( $liste_attribution );
				$CODE_RETOUR = $this->getCalculateur ( $id )
					->renvoi_donnees_machine ( "NetName" );
			} else {
				$CODE_RETOUR = $id;
			}
		} else {
			$CODE_RETOUR = $this->getCalculateur ( $liste_attribution [$uniq_id] ["calculateur"] )
				->renvoi_donnees_machine ( "NetName" );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Prend le nom d'un job et des-alloue ses ressources du calculateur.
	 *
	 * @param string $uniq_id Numero unique du job
	 * @return Bool Renvoi TRUE si le job est bien des-attribue ou FALSE si il n'y a plus de job sur la machine.
	 */
	public function libere_calculateur($uniq_id) {
		$liste_attribution = $this->getAttribution ();
		if (isset ( $liste_attribution [$uniq_id] )) {
			$var_return = $this->libere_puissance_machine ( $liste_attribution [$uniq_id] ["calculateur"], $liste_attribution [$uniq_id] ["ram"], $liste_attribution [$uniq_id] ["disk"], $liste_attribution [$uniq_id] ["cpu"] );
			if ($var_return) {
				$this->onDebug ( "On libere le compte " . $uniq_id, 2 );
				unset ( $liste_attribution [$uniq_id] );
				$this->setAttribution ( $liste_attribution );
				$CODE_RETOUR = true;
			} else {
				$this->onDebug ( "Le calculateur " . $liste_attribution [$uniq_id] ["calculateur"] . " n'est pas libere.", 2 );
				$CODE_RETOUR = false;
			}
		} else {
			$this->onDebug ( "Le compte " . $uniq_id . " n'a pas de calculateur", 2 );
			$CODE_RETOUR = - 1;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * libere tous les calculateurs.
	 */
	public function libere_tous_calculateurs() {
		foreach ( $this->getAttribution () as $uniq_id => $calc ) {
			$this->libere_calculateur ( $uniq_id );
		}
		
		return true;
	}

	/************************* Accesseurs ************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeMachine() {
		return $this->liste_machine;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeMachine($liste_machine) {
		$this->liste_machine = $liste_machine;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return calculateur
	 */
	public function &getCalculateur($pos) {
		if (isset ( $this->liste_machine [$pos] )) {
			return $this->liste_machine [$pos];
		}
		$retour=NULL;
		return $retour;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMachineId() {
		return $this->machine_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMachineId($machine_id) {
		$this->machine_id = $machine_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAddMachineId($nom, $machine_id) {
		$this->machine_id [$nom] = $machine_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAttribution() {
		return $this->attribution;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAttribution($attribution) {
		$this->attribution = $attribution;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAddAttribution($nom, $attribution) {
		$this->attribution [$nom] = $attribution;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return calculateur
	 */
	public function &getObjetCalculateurRef() {
		return $this->calculateur_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetCalculateurRef($calculateur_ref) {
		$this->calculateur_ref = $calculateur_ref;
		return $this;
	}

	/************************* Accesseurs ************************/
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "en ligne de commande :";
		$help [__CLASS__] ["text"] [] .= "\t--liste_calculateurs=\"calculateur_1 calculateur_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_name_calculateurs=\"nom_calc_1 nom_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_ip_calculateurs=\"ip_calc_1 ip_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_username_calculateurs=\"username_calc_1 username_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_password_calculateurs=\"password_calc_1 password_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_ftppassword_calculateurs=\"ftppassword_calc_1 ftppassword_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_diskspace_calculateurs=\"diskspace_calc_1 diskspace_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_ramspace_calculateurs=\"ramspace_calc_1 ramspace_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_maxramjob_calculateurs=\"maxramjob_calc_1 maxramjob_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_cpuunit_calculateurs=\"cpuunit_calc_1 cpuunit_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_mincpujob_calculateurs=\"mincpujob_calc_1 mincpujob_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "\t--liste_maxcpujob_calculateurs=\"maxcpujob_calc_1 maxcpujob_calc_2 ... \"";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "Param XML : liste_calculateurs=>calc01=> Name/IP/Username/Password/FTPPassword/DiskSpace/RamSpace/MaxRamJob/CPUUnit/MinCPUJob/MaxCPUJob => cal0x ...";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "Les parametres sont optionnels.";
		$help [__CLASS__] ["text"] [] .= "S'ils sont donnees, les valeurs de chaque parametre des calculateurs doivent etre au meme nombre que le nombre de calculateur.";
		
		return $help;
	}
}
?>