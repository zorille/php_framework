<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class machine<br> Gere l'attribution et la liberation des jobs pour un machine.
 * @package Lib
 * @subpackage Gestion_Machine
 */
class machine extends abstract_log {
	/**
	 * @access private
	 * @var string
	 */
	private $Nom = "";
	/**
	 * @access private
	 * @var string
	 */
	private $NetName = "";
	/**
	 * @access private
	 * @var string
	 */
	private $IP = "";
	/**
	 * @access private
	 * @var string
	 */
	private $Username = "";
	/**
	 * @access private
	 * @var int
	 */
	private $DiskSpace = "";
	/**
	 * @access private
	 * @var int
	 */
	private $RamSpace = "";
	/**
	 * @access private
	 * @var int
	 */
	private $CPUUnit = "";
	/**
	 * @access private
	 * @var string
	 */
	private $OS = "";
	/**
	 * @access private
	 * @var string
	 */
	private $type_connexion = "";

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type machine.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return machine
	 */
	static function &creer_machine(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): machine
	{
		$objet = new machine ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return machine
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Prend les valeurs intrinsecs du machine et les charges en memoire
	 * @codeCoverageIgnore
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function manage_entete(
			$valeur,
			$entete) {
		if ($entete) {
			$valeur = array_merge ( array (
					$entete
			), $valeur );
		}
		return $valeur;
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return false|machine.
	 * @throws Exception
	 */
	public function &retrouve_machine_param(
			$nom_machine,
			$entete = false): static|bool {
		$this->onDebug ( __METHOD__, 1 );
		// Le nom est obligatoire
		$this->setNom ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"nom"
		), $entete ) ) );
		if ($this->getNom () == null) {
			$r = $this->onError ( "Il faut un nom a la machine", "", 10000 );
			return $r;
		}
		$this->setHost ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"host"
		), $entete ), "" ) );
		$this->setIP ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"ip"
		), $entete ), "" ) );
		$this->setOs ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"os"
		), $entete ), "" ) );
		$this->setUsername ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"username"
		), $entete ), "" ) );
		$this->setDiskSpace ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"diskspace"
		), $entete ), "" ) );
		$this->setRamSpace ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"ramspace"
		), $entete ), "" ) );
		$this->setCPUUnit ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"cpuunit"
		), $entete ), "" ) );
		$this->setTypeConnexion ( $this->getListeOptions ()
			->_valideOption ( $this->manage_entete ( array (
				$nom_machine,
				"connexion"
		), $entete ), "" ) );
		return $this;
	}

	/**
	 * Prend la valeur du Disque d'un job et verifie si ce job ne depasse pas le DiskSpace.
	 *
	 * @param int $disk Disque utilise par le job
	 * @return Bool Renvoi TRUE si le job est acceptable ou FALSE sinon.
	 */
	public function compare_diskspace(
		int $disk): bool
	{
		if ($disk <= $this->getDiskSpace ())
			return true;
		return false;
	}

	/**
	 * Prend la valeur de la RAM d'un job et verifie si ce job ne depasse pas le MaxRamJob
	 *
	 * @param int $RamSpace RAM utilise par le job
	 * @return Bool Renvoi TRUE si le job est acceptable ou FALSE sinon.
	 */
	public function compare_maxram(
		int $RamSpace): bool
	{
		if ($RamSpace <= $this->getMaxRamJob ())
			return true;
		return false;
	}

	/**
	 * Accesseur en lecture des infos sur la machine
	 *
	 * @param string $choix Type d'info demande (Name, Netname)
	 * @return string|false Renvoi la valeur demande ou FALSE si cette valeur n'existe pas.
	 */
	public function renvoi_donnees_machine(
		string $choix): bool|string
	{
		return match ($choix) {
			"Name" => $this->getNom(),
			"NetName" => $this->getNetName(),
			default => false,
		};
	}

	/**
	 * *********************** Accesseurs ***********************
	 */
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
	public function &setNom(
			$Nom): static
	{
		$this->Nom = $Nom;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHost(): string
	{
		return $this->NetName;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHost(
			$NetName): static
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
	public function &setIP(
			$IP): static
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
	public function &setUsername(
			$Username): static
	{
		$this->Username = $Username;
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
	public function &setDiskSpace(
			$DiskSpace): static
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
	public function &setRamSpace(
			$RamSpace): static
	{
		$this->RamSpace = $RamSpace;
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
	public function &setCPUUnit(
			$CPUUnit): static
	{
		$this->CPUUnit = $CPUUnit;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOs(): string
	{
		return $this->OS;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOs(
			$Os): static
	{
		$this->OS = $Os;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTypeConnexion(): string
	{
		return $this->type_connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTypeConnexion(
			$type_connexion): static
	{
		$this->type_connexion = $type_connexion;
		return $this;
	}

	/**
	 * *********************** Accesseurs ***********************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "machine :";
		return $help;
	}
}
