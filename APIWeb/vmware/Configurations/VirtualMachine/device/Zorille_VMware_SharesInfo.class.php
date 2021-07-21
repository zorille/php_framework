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
 * class SharesInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class SharesInfo extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var String
	 */
	private $level = "";
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $shares = 0;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type SharesInfo.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return SharesInfo
	 */
	static function &creer_SharesInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		
		$objet = new SharesInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return SharesInfo
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
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
	public function renvoi_donnees_soap($arrayObject = false) {
		$liste_proprietes = new ArrayObject ();
		if (empty ( $this->getLevel () )) {
			return $this->onError ( "Il faut un shared level" );
		}
		$liste_proprietes ["level"] = $this->getLevel ();
		if (empty ( $this->getShares () )) {
			return $this->onError ( "Il faut un shared numero" );
		}
		$liste_proprietes ["shares"] = $this->getShares ();
		
		if ($arrayObject) {
			return $liste_proprietes;
		}
		return $liste_proprietes->getArrayCopy ();
	}

	/**
	 * Renvoi un soapvar contenant les variables de l'objet en cours
	 * @return soapvar
	 */
	public function &renvoi_objet_soap() {
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'SharesInfo' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * custom/high/low/normal
	 * @codeCoverageIgnore
	 */
	public function &setLevel($level) {
		switch ($level) {
			case 'custom' :
			case 'high' :
			case 'low' :
			case 'normal' :
				$this->level = $level;
				break;
			default :
				$this->level = "";
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getShares() {
		return $this->shares;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setShares($shares) {
		$this->shares = $shares;
		return $this;
	}
/************************* Accesseurs ***********************/
}

?>
