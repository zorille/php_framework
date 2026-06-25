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
use \soapvar as soapvar;
/**
 * class VirtualDeviceConfigSpec<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualDeviceConfigSpec extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var VirtualDevice
	 */
	private $device = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $fileOperation = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $operation = "";
	/**
	 * var privee
	 * Liste de VirtualMachineProfileSpec
	 * @access private
	 * @var array
	 */
	private $profile = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualDeviceConfigSpec.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualDeviceConfigSpec
	 * @throws Exception
	 */
	static function &creer_VirtualDeviceConfigSpec(Core\options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): VirtualDeviceConfigSpec
	{
		
		$objet = new VirtualDeviceConfigSpec ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualDeviceConfigSpec
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
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 */
	public function renvoi_donnees_soap(bool $arrayObject = false): ArrayObject|array
	{
		$liste_proprietes = new ArrayObject ();
		if ( $this->getOperation () ) {
			$liste_proprietes ["operation"] = $this->getOperation ();
		}
		if ( $this->getFileOperation () ) {
			$liste_proprietes ["fileOperation"] = $this->getFileOperation ();
		}
		if ( $this->getDevice () ) {
			$liste_proprietes ["device"] = $this->getDevice ()
				->renvoi_objet_soap ();
		}
		if ( $this->getProfile () ) {
			$liste_proprietes ["profile"] = array ();
			foreach ( $this->getProfile () as $VirtualMachineProfileSpec ) {
				$liste_proprietes ["profile"] [] = $VirtualMachineProfileSpec->renvoi_objet_soap ( false );
			}
		}
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param boolean $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 */
	public function &renvoi_objet_soap(bool $arrayObject = false): soapvar
	{
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, 'VirtualDeviceConfigSpec' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return VirtualDevice
	 */
	public function &getDevice(): ?VirtualDevice
	{
		return $this->device;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDevice(&$device): static
	{
		$this->device = $device;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFileOperation(): string
	{
		return $this->fileOperation;
	}

	/**
	 * create/destroy/replace
	 * @codeCoverageIgnore
	 */
	public function &setFileOperation($fileOperation): static
	{
		$this->fileOperation = match ($fileOperation) {
			'create', 'destroy', 'replace' => $fileOperation,
			default => "",
		};
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOperation(): string
	{
		return $this->operation;
	}

	/**
	 * add/edit/remove
	 * @codeCoverageIgnore
	 */
	public function &setOperation($operation): static
	{
		$this->operation = match ($operation) {
			'add', 'edit', 'remove' => $operation,
			default => "",
		};
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getProfile(): array
	{
		return $this->profile;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setProfile($profile): static
	{
		$this->profile = $profile;
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
