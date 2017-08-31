<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
/**
 * class splunk_datamodel_pivot_name
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_datamodel_pivot_name extends splunk_datamodel_pivot {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $model_name = '';
	
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_datamodel_pivot_name. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string $model_name Id de l'instance a traiter
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_datamodel_pivot_name
	 */
	static function &creer_splunk_datamodel_pivot_name(&$liste_option, &$splunk_webservice_rest, $model_name, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_datamodel_pivot_name ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest, 
				'model_name' => $model_name ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_datamodel_pivot_name
	 */
	public function &_initialise($liste_class) {
		$this ->setModelName ( $liste_class ['model_name'] );
		parent::_initialise ( $liste_class );
		
		return $this ->reset_resource ();
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
	 * @return splunk_datamodel_pivot_name
	 * @throws  Exception
	 */
	public function &reset_resource() {
		$this ->valid_model_name ();
		return parent::reset_resource () ->addResource ( $this ->getModelName () );
	}

	/**
	 * Valid if model_name is not empty. Send Exception if false.
	 * @return splunk_datamodel_pivot_name
	 * @throws  Exception
	 */
	public function valid_model_name() {
		if (empty ( $this ->getModelName () )) {
			$this ->onError ( "Il faut un model_name pour travailler" );
		}
		
		return $this;
	}

	/**
	 * Resource: datamodel/pivot/{model_name}
	 *   Method: Get 
	 * Access a specific data model. 
	 *
	 * @codeCoverageIgnore
	 * @param   $params				Request Parameters
	 * @throws  Exception
	 */
	public function getPivotInformations($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$results = $this ->reset_resource () 
			->get ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getModelName() {
		return $this->model_name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setModelName($model_name) {
		$this->model_name = $model_name;
		
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
		$help [__CLASS__] ["text"] [] .= "splunk_datamodel_pivot_name :";
		
		return $help;
	}
}
?>
