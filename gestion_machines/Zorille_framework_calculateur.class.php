<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use Exception;

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
	 * @param int $MaxNbJob
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return calculateur
	 * @throws Exception
	 */
	static function &creer_calculateur(
		options &$liste_option,
		string $Nom,
		string $NetName,
		string      $IP,
		string      $Username,
		string      $Password,
		string      $FTPPassword,
		int         $DiskSpace,
		int         $RamSpace,
		int         $MaxRamJob,
		int         $CPUUnit,
		int         $MinCPUJob,
		int         $MaxCPUJob,
		int         $MaxNbJob = 20,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): calculateur
	{
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
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Prend  les valeurs intrinsecs du calculateur et les charges en memoire
	 * @codeCoverageIgnore
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prend les valeurs RAM, Disk et CPU d'un job
	 * et verifie si ce job peut aller sur cette machine
	 *
	 * @param int $ram RAM utilise par le job
	 * @param int $disk Disque utilise par le job
	 * @param int $cpu CPUUnit utilise par le job
	 * @return bool|int -1 Renvoi TRUE si le job est acceptable ou FALSE sinon. -1 le calculateur ne supporte pas ce job.
	 */
	public function compare_valeur(int $ram = 0, int $disk = 0, int $cpu = 0): bool|int
	{
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
	public function compare_maxcpu(int $cpu): bool
	{
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
	public function compare_diskspace(int $disk): bool
	{
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
	public function compare_maxram(int $RamSpace): bool
	{
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
	public function compare_nbjob(): bool
	{
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
	 * @return bool|int -1 Renvoi TRUE si le job est attribue ou FALSE sinon. -1 le calculateur ne supporte pas ce job.
	 */
	public function utilise_puissance_calculateur(int $ram = 0, int $disk = 0, int $cpu = 0): bool|int
	{
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
	public function libere_puissance_calculateur(int $ram = 0, int $disk = 0, int $cpu = 0): bool
	{
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
	 * @return bool|int|string Renvoi la valeur demande ou FALSE si cette valeur n'existe pas.
	 */
	public function renvoi_donnees_machine(string $choix): bool|int|string
	{
		return match ($choix) {
			"Name" => $this->getNom(),
			"NetName" => $this->getNetName(),
			"MaxNbJob" => $this->getMaxNbJob(),
			default => false,
		};

	}

	/**
	 * Accesseur en lecture du nombre de job en cours
	 *
	 * @return int Renvoi le nombre de job en cours
	 */
	public function renvoi_nb_job_en_cours(): int
	{
		return $this->getUsedParType ( "NBJob" );
	}

	/************************* Accesseurs ************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getNom(): string
	{
		return $this->Nom;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNom($Nom): static
	{
		$this->Nom = $Nom;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNetName(): string
	{
		return $this->NetName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNetName($NetName): static
	{
		$this->NetName = $NetName;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIP(): string
	{
		return $this->IP;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIP($IP): static
	{
		$this->IP = $IP;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsername(): string
	{
		return $this->Username;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsername($Username): static
	{
		$this->Username = $Username;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassword(): string
	{
		return $this->Password;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword($Password): static
	{
		$this->Password = $Password;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFTPPassword(): string
	{
		return $this->FTPPassword;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFTPPassword($FTPPassword): static
	{
		$this->FTPPassword = $FTPPassword;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDiskSpace(): int|string
	{
		return $this->DiskSpace;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDiskSpace($DiskSpace): static
	{
		$this->DiskSpace = $DiskSpace;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRamSpace(): int|string
	{
		return $this->RamSpace;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRamSpace($RamSpace): static
	{
		$this->RamSpace = $RamSpace;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxRamJob(): int|string
	{
		return $this->MaxRamJob;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxRamJob($MaxRamJob): static
	{
		$this->MaxRamJob = $MaxRamJob;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCPUUnit(): int|string
	{
		return $this->CPUUnit;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCPUUnit($CPUUnit): static
	{
		$this->CPUUnit = $CPUUnit;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMinCPUJob(): int|string
	{
		return $this->MinCPUJob;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMinCPUJob($MinCPUJob): static
	{
		$this->MinCPUJob = $MinCPUJob;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxCPUJob(): int|string
	{
		return $this->MaxCPUJob;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxCPUJob($MaxCPUJob): static
	{
		$this->MaxCPUJob = $MaxCPUJob;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMaxNbJob(): int
	{
		return $this->MaxNbJob;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMaxNbJob($MaxNbJob): static
	{
		$this->MaxNbJob = $MaxNbJob;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getUsed(): array
	{
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
	public function &setUsed($used): static
	{
		$this->used = $used;
		return $this;
	}

	/**
	 * Creer ou modifie le champ $type avec la valeur $used
	 * @codeCoverageIgnore
	 */
	public function &setAddUsed($type, $used): static
	{
		$this->used [$type] = $used;
		return $this;
	}
/************************* Accesseurs ************************/
}
