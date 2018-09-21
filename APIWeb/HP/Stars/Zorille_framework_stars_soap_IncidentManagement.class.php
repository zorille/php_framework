<?php
/**
 * Gestion de stars.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class stars_soap_IncidentManagement
 *
 * @package Lib
 * @subpackage stars
 */
class stars_soap_IncidentManagement extends stars_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $wsdl = "IncidentManagement";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type stars_soap_IncidentManagement.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet 
	 * @return stars_soap_IncidentManagement
	 */
	static function &creer_stars_soap_IncidentManagement(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new stars_soap_IncidentManagement ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return stars_soap_IncidentManagement
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
	 * @param string $entete Entete des logs de l'objet
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de star_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Connexion au soap IncidentManagement de stars (IncidentManagement)
	 *
	 * @param string $nom
	 *        	nom du stars a connecter
	 * @return bool TRUE si connexion ok, FALSE sinon
	 */
	public function connect($nom = "") {
		return $this->connexion ( $nom, $this->getWsdlNom () );
	}

	/**
	 * Execute la demande soap
	 * @param string $fonction Fonction SOAP demandee
	 * @param array $params Parametres de la fonction
	 * @return array|false
	 */
	public function applique_requete_soap($fonction, $params = array()) {
		$this->onDebug ( "applique_requete_soap", 1 );
		
		try {
			if ($this->getListeOptions ()
				->getOption ( "dry-run" ) !== false) {
				$this->onWarning ( "DRY RUN : " . $fonction . " NON EXECUTE" );
				$resultat = false;
			} else {
				$resultat = $this->getSoapConnection ()
					->getSoapClient ()
					->__call ( $fonction, $params );
				
				$this->onDebug ( $this->getSoapConnection ()
					->getSoapClient ()
					->__getLastRequest (), 2 );
			}
		} catch ( Exception $e ) {
			return $this->onError ( $e->getMessage (), $this->getSoapConnection ()
				->getSoapClient ()
				->__getLastRequest (), $e->getCode () );
		}
		
		return $resultat;
	}

	/******************************* Incidents ********************************/
	
	/**
	 * Cree un incident dans Stars<br/>
	 * Donnees en retour :<br/>
	 * IncidentModelType model;<br/>
	 * MessagesType messages;<br/>
	 * StatusType status;<br/>
	 * string message;<br/>
	 * date schemaRevisionDate;<br/>
	 * int schemaRevisionLevel;<br/>
	 * decimal returnCode;<br/>
	 * string query;
	 * 
	 * @return array|false
	 */
	public function CreateIncident($Incident) {
		$this->onDebug ( "CreateIncident de stars", 1 );
		$this->onDebug ( $Incident, 2 );
		
		/**manual version
		$soap_data = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ns=\"http://schemas.hp.com/SM/7\" xmlns:com=\"http://schemas.hp.com/SM/7/Common\" xmlns:xm=\"http://www.w3.org/2005/05/xmlmime\">";
		$soap_data .= "<soapenv:Header/>";
		$soap_data .= "<soapenv:Body>";
		$soap_data .= "<ns:CreateIncidentRequest attachmentInfo=\"\" attachmentData=\"\" ignoreEmptyElements=\"true\" updateconstraint=\"\">";
		$soap_data .= "   <ns:model query=\"\">";
		$soap_data .= "     <ns:keys query=\"\" updatecounter=\"\">";
		$soap_data .= "     </ns:keys>";
		$soap_data .= "      <ns:instance query=\"\">";
		$soap_data .= "          <ns:Category type=\"String\"  >" . $Incident ["Category"] . "</ns:Category>";
		$soap_data .= "          <ns:OpenedBy type=\"String\"  >" . $Incident ["OpenedBy"] . "</ns:OpenedBy>";
		$soap_data .= "          <ns:Urgency type=\"String\"  >" . $Incident ["Urgency"] . "</ns:Urgency>";
		$soap_data .= "          <ns:AssignmentGroup type=\"String\"  >" . $Incident ["AssignmentGroup"] . "</ns:AssignmentGroup>";
		$soap_data .= "          <ns:AffectedCI type=\"String\"  >" . $Incident ["AffectedCI"] . "</ns:AffectedCI>";
		$soap_data .= "          <ns:Description type=\"Array\">";
		$soap_data .= "             <ns:Description type=\"String\"  >" . $Incident ["Description"] ["Description"] . "</ns:Description>";
		$soap_data .= "          </ns:Description>";
		$soap_data .= "          <ns:Contact type=\"String\"  >" . $Incident ["Contact"] . "</ns:Contact>";
		$soap_data .= "          <ns:Company type=\"String\"  >" . $Incident ["Company"] . "</ns:Company>";
		$soap_data .= "          <ns:Title type=\"String\"  >" . $Incident ["Title"] . " </ns:Title>";
		$soap_data .= "          <ns:Area type=\"String\"  >" . $Incident ["Area"] . "</ns:Area>";
		$soap_data .= "          <ns:Subarea type=\"String\"  >" . $Incident ["Subarea"] . "</ns:Subarea>";
		$soap_data .= "          <ns:Impact type=\"String\"  >" . $Incident ["Impact"] . "</ns:Impact>";
		$soap_data .= "          <ns:Service type=\"String\"  >" . $Incident ["Service"] . "</ns:Service>";
		$soap_data .= "          <ns:HPOMID type=\"String\"  >" . $Incident ["HPOMID"] . "</ns:HPOMID>";
		$soap_data .= "		     <ns:KnowledgeDoc type=\"String\"  >" . $Incident ["KnowledgeDoc"] . "</ns:KnowledgeDoc>";
		$soap_data .= "		     <ns:CustomerReference1 type=\"String\"  >" . $Incident ["CustomerReference1"] . "</ns:CustomerReference1>";
		$soap_data .= "      </ns:instance>";
		$soap_data .= "      <ns:messages>";
		$soap_data .= "          <!--Zero or more repetitions:-->";
		$soap_data .= "          <com:message severity=\"?\" module=\"?\"></com:message>";
		$soap_data .= "      </ns:messages>";
		$soap_data .= "   </ns:model>";
		$soap_data .= "</ns:CreateIncidentRequest>";
		$soap_data .= "</soapenv:Body>";
		$soap_data .= "</soapenv:Envelope>";
		
		if ($this->getListeOptions ()
			->getOption ( "dry-run" ) !== false) {
			$this->onWarning ( "DRY RUN : CreateIncident NON EXECUTE" );
			$this->onInfo ( $soap_data );
			$CreateIncidentResponse = array (
					"status" => "SUCCESS",
					"message" => "",
					"IncidentID" => "1" 
			);
		} else {
			$CreateIncidentResponse = $this->getSoapConnection ()
				->send_curl_soap_requete ( $soap_data );
		}
		*/
		
		$CreateIncidentResponse = $this->applique_requete_soap ( "CreateIncident", array (
				$Incident 
		) );
		
		$this->onDebug ( $CreateIncidentResponse, 2 );
		return $CreateIncidentResponse;
	}

	/**
	 * Liste les incidents dans Stars<br/>
	 * Donnees en retour :<br/>
	 * IncidentModelType model;<br/>
	 * MessagesType messages;<br/>
	 * StatusType status;<br/>
	 * string message;<br/>
	 * date schemaRevisionDate;<br/>
	 * int schemaRevisionLevel;<br/>
	 * decimal returnCode;<br/>
	 * string query;
	 *
	 * @return array|false
	 */
	public function RetrieveIncidentList($IncidentListRequest) {
		$this->onDebug ( "RetrieveIncidentList de stars", 1 );
		$this->onDebug ( $IncidentListRequest, 2 );
		
		$RetrieveIncidentListResponse = $this->applique_requete_soap ( "RetrieveIncidentList", array (
				$IncidentListRequest
		) );

		$this->onDebug ( $RetrieveIncidentListResponse, 2 );
		return $RetrieveIncidentListResponse;
	}

	/******************************* Incidents ********************************/
	
	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlNom() {
		return $this->wsdl;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "\t--dry-run Affiche les appels sans les executer";
		
		return $help;
	}
}

?>
