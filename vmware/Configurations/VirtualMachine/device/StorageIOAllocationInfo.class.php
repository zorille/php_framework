<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class StorageIOAllocationInfo<br>
 * @package Lib
 * @subpackage VMWare
 */
class StorageIOAllocationInfo extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var integer
	 */
	private $limit = 0;
	/**
	 * var privee
	 * deprecated mais obligatoire :|
	 * @access private
	 * @var Integer
	 */
	private $reservation = 0;
	/**
	 * var privee
	 * @access private
	 * @var SharesInfo
	 */
	private $shares = NULL;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type StorageIOAllocationInfo.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return StorageIOAllocationInfo
	 */
	static function &creer_StorageIOAllocationInfo(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new StorageIOAllocationInfo ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return StorageIOAllocationInfo
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
		$liste_proprietes = new ArrayObject();
		if ( $this->getLimit () ) {
			$liste_proprietes ["limit"] = $this->getLimit ();
		}
		if ( $this->getReservation () ) {
			$liste_proprietes ["reservation"] = $this->getReservation ();
		}
		if ( $this->getShares () ) {
			$liste_proprietes ["shares"] = $this->getShares ()->renvoi_donnees_soap(false);
		}
		
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
		$soap_var = new soapvar ( $this->renvoi_donnees_soap ( true ), SOAP_ENC_OBJECT, 'StorageIOAllocationInfo' );
		return $soap_var;
	}

	/************************* Methodes VMWare ***********************/
	
	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 */
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getLimit() {
		return $this->limit;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setLimit($limit) {
		$this->limit = $limit;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getReservation() {
		return $this->reservation;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setReservation($reservation) {
		$this->reservation = $reservation;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 * @return SharesInfo
	 */
	public function &getShares() {
		return $this->shares;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setShares(&$shares) {
		$this->shares = $shares;
		return $this;
	}

/************************* Accesseurs ***********************/
}

?>
