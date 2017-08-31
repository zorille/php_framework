<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class VirtualMachineFileInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualMachineFileInfo extends abstract_log {
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
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualMachineFileInfo
	 */
	static function &creer_VirtualMachineFileInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
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
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap($arrayObject=false){
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
	public function getLogDirectory() {
		return $this->logDirectory;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setLogDirectory($logDirectory) {
		$this->logDirectory=$logDirectory;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSnapshotDirectory() {
		return $this->snapshotDirectory;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSnapshotDirectory($snapshotDirectory) {
		$this->snapshotDirectory=$snapshotDirectory;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getSuspendDirectory() {
		return $this->suspendDirectory;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSuspendDirectory($suspendDirectory) {
		$this->suspendDirectory = $suspendDirectory;
		return $this;
	}
	
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getVmPathName() {
		return $this->vmPathName;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setVmPathName($vmPathName) {
		$this->vmPathName=$vmPathName;
		return $this;
	}
	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

?>
