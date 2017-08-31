<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
/**
 * class splunk_datamodel_acceleration_name
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_datamodel_acceleration_name extends splunk_datamodel_acceleration {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $datamodel_acceleration_name = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_datamodel_acceleration_name. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string $datamodel_acceleration_name Nom de l'instance a traiter
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_datamodel_acceleration_name
	 */
	static function &creer_splunk_datamodel_acceleration_name(&$liste_option, &$splunk_webservice_rest, $datamodel_acceleration_name, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_datamodel_acceleration_name ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest, 
				"datamodel_acceleration_name" => $datamodel_acceleration_name ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_datamodel_acceleration_name
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->setMessagesName ( $liste_class ['datamodel_acceleration_name'] ) 
			->reset_resource ();
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
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return splunk_saved_searches_name
	 * @throws  Exception
	 */
	public function &reset_resource() {
		$this ->valid_datamodel_acceleration_name ();
		return parent::reset_resource () ->addResource ( $this ->getMessagesNamed () );
	}

	/**
	 * Valid if search_id is not empty. Send Exception if false.
	 * @return splunk_saved_searches_name
	 * @throws  Exception
	 */
	public function valid_datamodel_acceleration_name() {
		if (empty ( $this ->getMessagesNamed () )) {
			$this ->onError ( "Il faut un datamodel_acceleration_name pour travailler" );
		}
		
		return $this;
	}

	/**
	 * Resource: datamodel/acceleration/{name}
	 *   Method: Get 
	 * Show data datamodel/acceleration.
	 *
	 * Return system datamodel_acceleration
	 * @codeCoverageIgnore
	 * @param   $params				Request Parameters
	 * @throws  Exception
	 */
	public function getDatamodelAccelerationName($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource () 
			->get ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	public function getDatamodelAccelerationNamed() {
		return $this->datamodel_acceleration_name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatamodelAccelerationName($datamodel_acceleration_name) {
		$this->datamodel_acceleration_name = $datamodel_acceleration_name;
		
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
		$help [__CLASS__] ["text"] [] .= "splunk_datamodel_acceleration_name :";
		
		return $help;
	}
}
?>
