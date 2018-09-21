<?php
/**
 * Gestion de HPOM.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class hpom
 *$msgID="7b5b9444-ceec-71e3-01a7-ac1040200000";
try {
$wmiobj = new COM("WinMgmts:{impersonationLevel=impersonate}!root\\HewlettPackard\\OpenView\\Data:OV_Message.Id=\"$msgID\"");
} catch (com_exception $e ){
	echo $e->getMessage ();
	exit(1);
}
echo "node=$wmiobj->PrimaryNodeName\n\r";
echo "customer=$wmiobj->MessageGroup\n\r";
echo "appl=$wmiobj->Application\n\r";	 
echo "object=$wmiobj->Object\n\r";
echo "severity=$wmiobj->Severity\n\r";
foreach($wmiobj->CMAs as $CMA){
echo $CMA->name."\n\r";
echo $CMA->value."\n\r";
 * @package Lib
 * @subpackage HP
 */
class hpom extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var COM
	 */
	private $wmiobj = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $msgID = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $node = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $AffectedCI = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $customer = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $departement = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $application = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $objet = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $severite = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $msg_text = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $description = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $subArea = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $appl = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $CMA = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type hpom.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return hpom
	 */
	static function &creer_hpom(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new hpom ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return hpom
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
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Supprime tous ce qui est apres le "."
	 * @param string $nom
	 * @return string
	 */
	public function supprime_fqdn($nom) {
		$tok = strtok ( strtoupper ( $nom ), "." );
		
		if ($tok !== false) {
			return $tok;
		}
		// @codeCoverageIgnoreStart
		//uniquement en cas d'erreur de strtok
		return $nom;
		// @codeCoverageIgnoreEnd
	}

	/**
	 * @codeCoverageIgnore
	 * @return hpom
	 * @throws Exception
	 */
	public function connecte_COM() {
		//$msgID="7b5b9444-ceec-71e3-01a7-ac1040200000";
		$wmiobj = new COM ( "WinMgmts:{impersonationLevel=impersonate}!root\\HewlettPackard\\OpenView\\Data:OV_Message.Id=\"" . $this->getMsgId () . "\"" );
		$this->setWmiObject ( $wmiobj );
		
		return $this;
	}

	/**
	 * Recupere les donnees dans la stack WMI windows (necessite COM)
	 * @return hpom|false
	 */
	public function retrouve_hpom_param() {
		$this->setNode ( $this->supprime_fqdn ( $this->getWmiObject ()->PrimaryNodeName ) )
			->setCustomer ( $this->getWmiObject ()->MessageGroup )
			->setDepartement ( $this->getCustomer () )
			->setAffectedCI ( $this->getDepartement () . "_" . $this->getNode () )
			->setApplication ( $this->getWmiObject ()->Application )
			->setSeverite ( $this->getWmiObject ()->Severity )
			->setMsgText ( $this->getWmiObject ()->Text )
			->setCMAs ( array () )
			->setDescription ( "NODE : " . $this->getNode () . "\ncf titre" )
			->setObjet ( $this->getWmiObject ()->Object );
		
		if ($this->getApplication () == "OPS") {
			$this->setObjet ( "OPS" );
		}
		
		//gestion des CMAs
		foreach ( $this->getWmiObject ()->CMAs as $CMA ) {
			if ($CMA->name != "") {
				$this->setAjouteCMAs ( $CMA->name, $CMA->value );
				if ($CMA->name == "incident_descr") {
					$this->gere_description ( $CMA->value );
				}
				if ($CMA->name == "instance") {
					$this->setInstance ( $CMA->value );
				}
			}
		}
		
		$this->gere_subarea ();
		
		return $this;
	}

	/**
	 * Prepare la description
	 * @param string $valeur
	 * @return hpom
	 */
	public function gere_description($valeur) {
		$valeur = str_replace ( "<br/>", "\n", $valeur );
		$this->setDescription ( "NODE : " . $this->getNode () . "\n" . $valeur );
		return $this;
	}

	/**
	 * Prepare la description
	 * @param string $valeur
	 * @return hpom
	 */
	public function gere_subarea() {
		return $this->setSubArea ( $this->getApplication () . "_" . $this->getObjet () );
	}

	/**
	 * Traite les messages internes a HPOM : <br/>
	 * assigne le bon client en fonction du node<br/>
	 * pour tous les "buffering messages" envoi un mail
	 * @return boolean|hpom renvoi true ou false en cas d'envoi de mail, sinon renvoi l'objet
	 */
	public function traite_message_interne_hpom() {
		$internal_msg_grp = array (
				"VP_SM",
				"VP_SM_DB",
				"OPC",
				"OPENVIEW" 
		);
		//Si le message est interne a HPOM (ne vient pas d'un client spécifique)
		if (in_array ( $this->getCustomer (), $internal_msg_grp )) {
			//On gere le changement de client pour certain CI
			$vrai_customer = array ();
			$internal_err_map = $this->getListeOptions ()
				->getOption ( "internal_err_map" );
			if (fichier::tester_fichier_existe ( $internal_err_map )) {
				$liste_donnee_customer = fichier::Lit_integralite_fichier_en_tableau ( $internal_err_map );
				
				foreach ( $liste_donnee_customer as $ligne ) {
					//On supprime les lignes commentees
					if ($ligne == "" || strpos ( $ligne, "#" ) === 0) {
						continue;
					}
					
					//On decoupe la ligne en node et customer
					if (preg_match ( '/^(?P<node>.*);(?P<customer>.*)$/', $ligne, $matches ) > 0) {
						if ($this->getNode () == $matches ["node"]) {
							$this->setCustomer ( $matches ["customer"] );
							continue;
						}
						// @codeCoverageIgnoreStart
					}
					// @codeCoverageIgnoreEnd
					$this->setApplication ( "MONITORING" );
					$this->setObjet ( "HPOM" );
					#in case of buffering message, we don't want to create ticket but send an email
					if (preg_match ( '/buffering messages/i', $ligne ) > 0) {
						//send a mail
						return true;
					}
				}
				// @codeCoverageIgnoreStart
			}
		}
		// @codeCoverageIgnoreEnd
		//Fin du message interne a HPOM (ne vient pas d'un client spécifique)
		

		return false;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function &getWmiObject() {
		return $this->wmiobj;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWmiObject(&$wmiobj) {
		$this->wmiobj = $wmiobj;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMsgId() {
		return $this->msgID;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMsgId($msgID) {
		$this->msgID = $msgID;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCustomer() {
		return $this->customer;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCustomer($customer) {
		$this->customer = $customer;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDepartement() {
		return $this->departement;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDepartement($departement) {
		$this->departement = $departement;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getApplication() {
		return $this->application;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setApplication($application) {
		$this->application = $application;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNode() {
		return $this->node;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNode($node) {
		$this->node = $node;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getAffectedCI() {
		return $this->AffectedCI;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAffectedCI($AffectedCI) {
		$this->AffectedCI = $AffectedCI;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getObjet() {
		return $this->objet;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjet($objet) {
		$this->objet = str_replace ( "\"", "", $objet );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSeverite() {
		return $this->severite;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSeverite($severite) {
		$this->severite = $severite;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCMAs() {
		return $this->CMA;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCMAs($CMA) {
		$this->CMA = $CMA;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAjouteCMAs($nom, $valeur) {
		$this->CMA [$nom] = $valeur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMsgText() {
		return $this->msg_text;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMsgText($msg_text) {
		$this->msg_text = $msg_text;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDescription($description) {
		$this->description = $description;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getInstance() {
		return $this->instance;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setInstance($instance) {
		$this->instance = $instance;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSubArea() {
		return $this->subArea;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSubArea($subArea) {
		$this->subArea = $subArea;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Necessite un objet COM (php_com_dotnet.dll)";
		$help [__CLASS__] ["text"] [] .= "\t--internal_err_map fichier contenant la correspondance erreur interne hpom<=>client";
		
		return $help;
	}
}
?>
