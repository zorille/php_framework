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
 * class DistributedVirtualSwitchPortConnection<br>
 * @package Lib
 * @subpackage VMWare
 */
class DistributedVirtualSwitchPortConnection extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $connectionCookie = 0;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $portgroupKey = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $portKey = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $switchUuid = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type DistributedVirtualSwitchPortConnection.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return DistributedVirtualSwitchPortConnection
	 */
	static function &creer_DistributedVirtualSwitchPortConnection(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new DistributedVirtualSwitchPortConnection ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return DistributedVirtualSwitchPortConnection
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
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if ( $this->getConnectionCookie () ) {
			$liste_proprietes ["connectionCookie"] = $this->getConnectionCookie ();
		}
		if ( $this->getPortgroupKey () ) {
			$liste_proprietes ["portgroupKey"] = $this->getPortgroupKey ();
		}
		if ( $this->getPortKey () ) {
			$liste_proprietes ["portKey"] = $this->getPortKey ();
		}
		if (empty ( $this->getSwitchUuid () )) {
			return $this->onError ( "Il faut un switchUuid" );
		}
		$liste_proprietes ["switchUuid"] = $this->getSwitchUuid ();
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getConnectionCookie() {
		return $this->connectionCookie;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setConnectionCookie($connectionCookie) {
		$this->connectionCookie = $connectionCookie;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPortgroupKey() {
		return $this->portgroupKey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPortgroupKey($portgroupKey) {
		$this->portgroupKey = $portgroupKey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPortKey() {
		return $this->portKey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPortKey($portKey) {
		$this->portKey = $portKey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSwitchUuid() {
		return $this->switchUuid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSwitchUuid($switchUuid) {
		$this->switchUuid = $switchUuid;
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
