<?php
/**
 * Gestion de splunk.
 * @author dvargas
 */
/**
 * class splunk_messages_name
 *
 * @package Lib
 * @subpackage splunk
 */
class splunk_messages_name extends splunk_messages {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $messages_name = '';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type splunk_messages_name. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param splunk_wsclient $splunk_webservice_rest Reference sur un objet splunk_wsclient
	 * @param string $message_name Nom de l'instance a traiter
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return splunk_messages_name
	 */
	static function &creer_splunk_messages_name(&$liste_option, &$splunk_webservice_rest, $message_name, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new splunk_messages_name ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"splunk_wsclient" => $splunk_webservice_rest, 
				"messages_name" => $message_name ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return splunk_messages_name
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->setMessagesName ( $liste_class ['messages_name'] ) 
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
		$this ->valid_messages_name ();
		return parent::reset_resource () ->addResource ( $this ->getMessagesNamed () );
	}

	/**
	 * Valid if search_id is not empty. Send Exception if false.
	 * @return splunk_saved_searches_name
	 * @throws  Exception
	 */
	public function valid_messages_name() {
		if (empty ( $this ->getMessagesNamed () )) {
			$this ->onError ( "Il faut un messages_name pour travailler" );
		}
		
		return $this;
	}

	/**
	 * Resource: messages/{name}
	 *   Method: Get 
	 * Show systemwide messages.
	 *
	 * Return system messages
	 * @codeCoverageIgnore
	 * @param   $params				Request Parameters
	 * @throws  Exception
	 */
	public function getSystemMessages($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource () 
			->get ( $params );
		
		return $results;
	}

	/**
	 * Resource: messages/{name}
	 *   Method: Delete 
	 * Remove system message
	 * 
	 * @codeCoverageIgnore
	 * @param   $params				Request Parameters
	 * @throws  Exception
	 */
	public function deleteMessage($params = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		$results = $this ->reset_resource () 
			->delete ( $params );
		
		return $results;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	public function getMessagesNamed() {
		return $this->messages_name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMessagesName($messages_name) {
		$this->messages_name = $messages_name;
		
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
		$help [__CLASS__] ["text"] [] .= "splunk_messages_name :";
		
		return $help;
	}
}
?>
