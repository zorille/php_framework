<?php
/**
 * @author dvargas
 * @package Lib
 */

/**
 * class calculateur<br>
 *
 * Gere l'attribution et la liberation des jobs pour un calculateur.
 * @package Lib
 * @subpackage Gestion_Machine
 */
class calculateur extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	var $Nom = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	var $NetName = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	var $IP = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	var $Username = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	var $Password = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	var $FTPPassword = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	var $DiskSpace = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	var $RamSpace = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	var $MaxRamJob = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	var $CPUUnit = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	var $MinCPUJob = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	var $MaxCPUJob = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	var $MaxNbJob = 20;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	var $used = array (
			"RamSpace" => 0,
			"DiskSpace" => 0,
			"CPUUnit" => 0,
			"NBJob" => 0 
	);

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type calculateur.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $Nom Nom de la machine
	 * @param string $NetName Nom reseau de la machine
	 * @param string $IP Adresse IP de la machine
	 * @param string $Username Nom de l'utilisateur de la machine
	 * @param string $Password Mot de passe SSH de l'utilisateur de la machine
	 * @param string $FTPPassword Mot de passe FTP de l'utilisateur de la machine
	 * @param int $DiskSpace Place disque de la machine
	 * @param int $RamSpace RAM de la machine
	 * @param int $MaxRamJob Taille maximal d'un job pour la machine
	 * @param int $CPUUnit Somme max des CPUJobs sur la machine
	 * @param int $MinCPUJob Taille min d'un Job sur la machine
	 * @param int $MaxCPUJob Taille max d'un job sur la machine
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return calculateur
	 */
	static function &creer_calculateur(&$liste_option, $Nom, $NetName, $IP, $Username, $Password, $FTPPassword, $DiskSpace, $RamSpace, $MaxRamJob, $CPUUnit, $MinCPUJob, $MaxCPUJob, $MaxNbJob = 20, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new calculateur ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) )
			->setNom ( $Nom )
			->setNetName ( $NetName )
			->setIP ( $IP )
			->setUsername ( $Username )
			->setPassword ( $Password )
			->setFTPPassword ( $FTPPassword )
			->setDiskSpace ( $DiskSpace )
			->setRamSpace ( $RamSpace )
			->setMaxRamJob ( $MaxRamJob )
			->setCPUUnit ( $CPUUnit )
			->setMinCPUJob ( $MinCPUJob )
			->setMaxCPUJob ( $MaxCPUJob )
			->setMaxNbJob ( $MaxNbJob );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return calculateur
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Prend  les valeurs intrinsecs du calculateur et les charges en memoire
	 * @codeCoverageIgnore
	 * @return TRUE
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		return true;
	}

	/**
	 * Prend les valeurs RAM, Disk et CPU d'un job
	 * et verifie si ce job peut aller sur cette machine
	 *
	 * @param int $ram RAM utilise par le job
	 * @param int $disk Disque utilise par le job
	 * @param int $cpu CPUUnit utilise par le job
	 * @return Bool|-1 Renvoi TRUE si le job est acceptable ou FALSE sinon. -1 le calculateur ne supporte pas ce job.
	 */
	public function compare_valeur($ram = 0, $disk = 0, $cpu = 0) {
		if (! $this->compare_maxcpu ( $cpu ) || ! $this->compare_maxram ( $ram ) || ! $this->compare_diskspace ( $disk )) {
			return - 1;
		} elseif ($this->getUsedParType ( "RamSpace" ) + $ram <= $this->getRamSpace () && $this->getUsedParType ( "DiskSpace" ) + $disk < $this->getDiskSpace () && $this->getUsedParType ( "CPUUnit" ) + $cpu < $this->getCPUUnit () && $this->compare_nbjob ())
			return true;
		
		return false;
	}

	/**
	 * Prend la valeur du CPU d'un job
	 * et verifie si ce job ne depasse pas le MaxCPUJob
	 *
	 * @param int $cpu CPUUnit utilise par le job
	 * @return Bool Renvoi TRUE si le job est acceptable ou FALSE sinon.
	 */
	public function compare_maxcpu($cpu) {
		if ($cpu <= $this->getMaxCPUJob ())
			return true;
		
		return false;
	}

	/**
	 * Prend la valeur du Disque d'un job
	 * et verifie si ce job ne depasse pas le DiskSpace.
	 *
	 * @param int $disk Disque utilise par le job
	 * @return Bool Renvoi TRUE si le job est acceptable ou FALSE sinon.
	 */
	public function compare_diskspace($disk) {
		if ($disk <= $this->getDiskSpace ())
			return true;
		
		return false;
	}

	/**
	 * Prend la valeur de la RAM d'un job
	 * et verifie si ce job ne depasse pas le MaxRamJob
	 *
	 * @param int $RamSpace RAM utilise par le job
	 * @return Bool Renvoi TRUE si le job est acceptable ou FALSE sinon.
	 */
	public function compare_maxram($RamSpace) {
		if ($RamSpace <= $this->getMaxRamJob ())
			return true;
		
		return false;
	}

	/**
	 * Prend le nombre de job en cours
	 * et verifie si ce nombre ne depasse pas le MaxNbJob
	 *
	 * @return Bool Renvoi TRUE si le job est acceptable ou FALSE sinon.
	 */
	public function compare_nbjob() {
		if ($this->getUsedParType ( "NBJob" ) < $this->getMaxNbJob ())
			return true;
		
		return false;
	}

	/**
	 * Prend les valeurs RAM, Disk et CPU d'un job
	 * et attribue ce job a cette machine
	 *
	 * @param int $ram RAM utilise par le job
	 * @param int $disk Disque utilise par le job
	 * @param int $cpu CPUUnit utilise par le job
	 * @return Bool|-1 Renvoi TRUE si le job est attribue ou FALSE sinon. -1 le calculateur ne supporte pas ce job.
	 */
	public function utilise_puissance_calculateur($ram = 0, $disk = 0, $cpu = 0) {
		$attribue = $this->compare_valeur ( $ram, $disk, $cpu );
		$CODE_RETOUR = $attribue;
		if ($attribue === true) {
			$this->setAddUsed ( "RamSpace", $this->getUsedParType ( "RamSpace" ) + $ram );
			$this->setAddUsed ( "DiskSpace", $this->getUsedParType ( "DiskSpace" ) + $disk );
			$this->setAddUsed ( "CPUUnit", $this->getUsedParType ( "CPUUnit" ) + $cpu );
			$this->setAddUsed ( "NBJob", $this->getUsedParType ( "NBJob" ) + 1 );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Prend les valeurs RAM, Disk et CPU d'un job
	 * et des-attribue ce job a cette machine
	 *
	 * @param int $ram RAM utilise par le job
	 * @param int $disk Disque utilise par le job
	 * @param int $cpu CPUUnit utilise par le job
	 * @return Bool Renvoi TRUE si le job est bien des-attribue ou FALSE si il n'y a plus de job sur la machine.
	 */
	public function libere_puissance_calculateur($ram = 0, $disk = 0, $cpu = 0) {
		if ($this->getUsedParType ( "NBJob" ) > 0) {
			$ram = $this->getUsedParType ( "RamSpace" ) - $ram;
			if ($ram < 0)
				$ram = 0;
			$disk = $this->getUsedParType ( "DiskSpace" ) - $disk;
			if ($disk < 0)
				$disk = 0;
			$cpu = $this->getUsedParType ( "CPUUnit" ) - $cpu;
			if ($cpu < 0)
				$cpu = 0;
			$this->setAddUsed ( "RamSpace", $ram );
			$this->setAddUsed ( "DiskSpace", $disk );
			$this->setAddUsed ( "CPUUnit", $cpu );
			$this->setAddUsed ( "NBJob", $this->getUsedParType ( "NBJob" ) - 1 );
			return true;
		}
		
		return false;
	}

	/**
	 * Accesseur en lecture des infos sur la machine
	 *
	 * @param string $choix Type d'info demande (Name, Netname)
	 * @return string|false Renvoi la valeur demande ou FALSE si cette valeur n'existe pas.
	 */
	public function renvoi_donnees_machine($choix) {
		switch ($choix) {
			case "Name" :
				return $this->getNom ();
			case "NetName" :
				return $this->getNetName ();
			case "MaxNbJob" :
				return $this->getMaxNbJob ();
		}
		
		return false;
	}

	/**
	 * Accesseur en lecture du nombre de job en cours
	 *
	 * @return int Renvoi le nombre de job en cours
	 */
	public function renvoi_nb_job_en_cours() {
		return $this->getUsedParType ( "NBJob" );
	}

	/************************* Accesseurs ************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getNom() {
		return $this->Nom;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNom($Nom) {
		$this->Nom = $Nom;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNetName() {
		return $this->NetName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNetName($NetName) {
		$this->NetName = $NetName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIP() {
		return $this->IP;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIP($IP) {
		$this->IP = $IP;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsername() {
		return $this->Username;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsername($Username) {
		$this->Username = $Username;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassword() {
		return $this->Password;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword($Password) {
		$this->Password = $Password;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFTPPassword() {
		return $this->FTPPassword;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFTPPassword($FTPPassword) {
		$this->FTPPassword = $FTPPassword;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDiskSpace() {
		return $this->DiskSpace;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDiskSpace($DiskSpace) {
		$this->DiskSpace = $DiskSpace;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRamSpace() {
		return $this->RamSpace;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRamSpace($RamSpace) {
		$this->RamSpace = $RamSpace;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxRamJob() {
		return $this->MaxRamJob;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxRamJob($MaxRamJob) {
		$this->MaxRamJob = $MaxRamJob;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCPUUnit() {
		return $this->CPUUnit;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCPUUnit($CPUUnit) {
		$this->CPUUnit = $CPUUnit;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMinCPUJob() {
		return $this->MinCPUJob;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMinCPUJob($MinCPUJob) {
		$this->MinCPUJob = $MinCPUJob;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxCPUJob() {
		return $this->MaxCPUJob;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxCPUJob($MaxCPUJob) {
		$this->MaxCPUJob = $MaxCPUJob;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxNbJob() {
		return $this->MaxNbJob;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxNbJob($MaxNbJob) {
		$this->MaxNbJob = $MaxNbJob;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getUsed() {
		return $this->used;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsedParType($type) {
		return $this->used [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsed($used) {
		$this->used = $used;
		return $this;
	}

	/**
	 * Creer ou modifie le champ $type avec la valeur $used
	 * @codeCoverageIgnore
	 */
	public function &setAddUsed($type, $used) {
		$this->used [$type] = $used;
		return $this;
	}
/************************* Accesseurs ************************/
}
?>