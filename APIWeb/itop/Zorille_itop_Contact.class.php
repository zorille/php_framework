<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class Contact
 *
 * @package Lib
 * @subpackage itop
 */
class Contact extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization
	 */
	private $Organization = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Contact. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Contact
	 */
	static function &creer_Contact(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Contact ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Contact
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'Contact' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_Contact($name,$email) {
		return $this ->creer_oql ( $name,$email ) 
			->retrouve_ci ();
	}

	public function creer_oql($name='',$email='') {
		$where="";
		if(!empty($name)){
			$where .= " name='" . $name . "'";
		}
		if(!empty($email)){
			if(!empty($where)){
				$where .= " AND ";
			}
			$where .= " email='" . $email . "'";
		}
		if(!empty($where)){
			$where = " WHERE".$where;
		}
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . $where );
	}

	/**
	 * Creer un lnkContactToFunctionalCI en fonction de la Contact
	 * @param string $FunctionalCI_id
	 * @param string $FunctionalCI_name
	 * @return array lnkContactToFunctionalCI
	 * @throws Exception
	 */
	public function creer_lnkContactToFunctionalCI($FunctionalCI_id = '', $FunctionalCI_name = '') {
		$lnkContactToFunctionalCI = array ();
		if (empty ( $this ->getId () )) {
			return $this ->onError ( "Il faut un ID a cette ".$this->getFormat() );
		}
		$lnkContactToFunctionalCI ['contact_id'] = $this ->getId ();
		$tableau=$this ->getDonnees ();
		if (isset ( $tableau['name'] )) {
			$lnkContactToFunctionalCI ['contact_name'] = $tableau['name'];
		}
		
		if (! empty ( $FunctionalCI_id )) {
			$lnkContactToFunctionalCI ['functionalci_id'] = $FunctionalCI_id;
		}
		if (! empty ( $FunctionalCI_name )) {
			$lnkContactToFunctionalCI ['functionalci_name'] = $FunctionalCI_name;
		}
		
		return $lnkContactToFunctionalCI;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopOrganization() {
		return $this->Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization(&$Organization) {
		$this->Organization = $Organization;
		
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Contact :";
		
		return $help;
	}
}
?>
