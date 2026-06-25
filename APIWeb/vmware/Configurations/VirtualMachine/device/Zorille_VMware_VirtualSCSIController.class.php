<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;
use Zorille\framework\options as options;
use \Exception as Exception;
use \ArrayObject as ArrayObject;
use \soapvar as soapvar;
/**
 * class VirtualSCSIController<br>
 * @package Lib
 * @subpackage VMWare
 */
class VirtualSCSIController extends VirtualController {
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $type_scsi = "VirtualLsiLogicController";
	/**
	 * var privee
	 * @access private
	 * @var Boolean
	 */
	private $hotAddRemove = false;
	/**
	 * var privee
	 * @access private
	 * @var Integer
	 */
	private $scsiCtlrUnitNumber = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $sharedBus = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type VirtualSCSIController.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return VirtualSCSIController
	 * @throws Exception
	 */
	static function &creer_VirtualSCSIController(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): VirtualSCSIController
	{
		
		$objet = new VirtualSCSIController ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return VirtualSCSIController
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/************************* Methodes VMWare ***********************/
	/**
	 * Prepare les donnees sous forme de tableau pour une requete SOAP
	 * @param string $arrayObject Permet de choisir entre un array ou un arrayObject en retour
	 * @return ArrayObject|array
	 * @throws Exception
	 */
	public function renvoi_donnees_soap($arrayObject = false): bool|array
	{
		$liste_proprietes = parent::renvoi_donnees_soap ( true );
		if ( $this->etHotAddRemove () ) {
			$liste_proprietes ["hotAddRemove"] = $this->etHotAddRemove ();
		}
		if ( $this->getScsiCtlrUnitNumber () ) {
			$liste_proprietes ["scsiCtlrUnitNumber"] = $this->getScsiCtlrUnitNumber ();
		}
		if (empty ( $this->getSharedBus () )) {
			return $this->onError ( "Il faut un sharedBus valide" );
		}
		$liste_proprietes ["sharedBus"] = $this->getSharedBus ();
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @param bool|string $arrayObject Permet de choisir entre un array ou un arrayObject en retour de renvoi_donnees_soap
	 * @return soapvar
	 * @throws Exception
	 */
	public function &renvoi_objet_soap(bool|string $arrayObject = false): soapvar
	{
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( $arrayObject ), SOAP_ENC_OBJECT, $this->getTypeScsi () );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getTypeScsi(): bool|string
	{
		return $this->type_scsi;
	}

	/**
	 * @param string $type_scsi Type de class SCSI (ParaVirtualSCSIController/VirtualBusLogicController/VirtualLsiLogicController/VirtualLsiLogicSASController)
	 * @codeCoverageIgnore
	 */
	public function &setTypeScsi($type_scsi): static
	{
		$this->type_scsi = match ($type_scsi) {
			"ParaVirtualSCSIController", "VirtualBusLogicController", "VirtualLsiLogicSASController" => $type_scsi,
			default => "VirtualLsiLogicController",
		};
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function etHotAddRemove(): bool
	{
		return $this->hotAddRemove;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHotAddRemove($hotAddRemove): static
	{
		$this->hotAddRemove = $hotAddRemove;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getScsiCtlrUnitNumber(): bool|int
	{
		return $this->scsiCtlrUnitNumber;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setScsiCtlrUnitNumber($scsiCtlrUnitNumber): static
	{
		$this->scsiCtlrUnitNumber = $scsiCtlrUnitNumber;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSharedBus(): string
	{
		return $this->sharedBus;
	}

	/**
	 * noSharing/physicalSharing/virtualSharing
	 * @codeCoverageIgnore
	 */
	public function &setSharedBus($sharedBus): static
	{
		$this->sharedBus = match ($sharedBus) {
			'noSharing', 'physicalSharing', 'virtualSharing' => $sharedBus,
			default => "",
		};
		
		return $this;
	}
/************************* Accesseurs ***********************/
}
