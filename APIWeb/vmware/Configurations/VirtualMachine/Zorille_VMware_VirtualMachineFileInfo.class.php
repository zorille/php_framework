<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework as Core;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
/**
 * class VirtualMachineFileInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineFileInfo extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $logDirectory = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $snapshotDirectory = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $suspendDirectory = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $vmPathName = "";
	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualMachineFileInfo.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineFileInfo
	 * @throws Exception
	 */
	static function &creer_VirtualMachineFileInfo(Core\options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): VirtualMachineFileInfo
	{
		$objet = new VirtualMachineFileInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualMachineFileInfo
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @throws Exception
	 */
	public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param bool|string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap(bool|string $arrayObject=false): ArrayObject|array
	{
		$liste_proprietes=new ArrayObject();
		if(!empty($this->getLogDirectory())){
			$liste_proprietes["logDirectory"]=$this->getLogDirectory();
		}
		if(!empty($this->getSnapshotDirectory())){
			$liste_proprietes["snapshotDirectory"]=$this->getSnapshotDirectory();
		}
		if(!empty($this->getSuspendDirectory())){
			$liste_proprietes["suspendDirectory"]=$this->getSuspendDirectory();
		}
		if(!empty($this->getVmPathName())){
			$liste_proprietes["vmPathName"]=$this->getVmPathName();
		}
		
		if($arrayObject){
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy();
	}
	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getLogDirectory(): string
	{
		return $this->logDirectory;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLogDirectory($logDirectory): static
	{
		$this->logDirectory=$logDirectory;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnapshotDirectory(): string
	{
		return $this->snapshotDirectory;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnapshotDirectory($snapshotDirectory): static
	{
		$this->snapshotDirectory=$snapshotDirectory;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getSuspendDirectory(): string
	{
		return $this->suspendDirectory;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSuspendDirectory($suspendDirectory): static
	{
		$this->suspendDirectory = $suspendDirectory;
		return $this;
	}
	
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getVmPathName(): string
	{
		return $this->vmPathName;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVmPathName($vmPathName): static
	{
		$this->vmPathName=$vmPathName;
		return $this;
	}
	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
