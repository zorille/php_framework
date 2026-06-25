<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class bladelogic_wsclient<br>
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage bladelogic
 */
class bladelogic_wsclient extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var bladelogic_datas
	 */
	private $bladelogic_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $auth = '';
	/**
	 * var privee
	 * @access private
	 * @var string.
	 */
	private $nom_serveur = '';
	/**
	 * var privee
	 * @access private
	 * @var array.
	 */
	private $defaultParams = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var soap
	 */
	private $soap = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type bladelogic_wsclient. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param bladelogic_datas &$bladelogic_datas Reference sur un objet bladelogic_datas
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return bladelogic_wsclient
	 * @throws Exception
	 */
	static function &creer_bladelogic_wsclient(
		options          &$liste_option,
		bladelogic_datas &$bladelogic_datas,
		bool|string      $sort_en_erreur = false,
		string           $entete = __CLASS__): bladelogic_wsclient {
		$objet = new bladelogic_wsclient ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"bladelogic_datas" => $bladelogic_datas ) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return bool|self
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["bladelogic_datas"] )) {
			$r = $this->onError ( "il faut un objet de type bladelogic_datas" );
			return $r;
		}
		$this ->setObjetBladelogicDatas ( $liste_class ["bladelogic_datas"] ) 
			->setObjetSoap ( soap::creer_soap ( $liste_class ["options"] ) );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		//Gestion de wsclient
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au bladelogic nomme $nom
	 * @param string $nom
	 * @return bladelogic_wsclient|bool bladelogic_wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(string $nom): static|bool {
		$liste_data_bladelogic = $this ->getObjetBladelogicDatas () 
			->valide_presence_bladelogic_data ( $nom );
		if (!$liste_data_bladelogic) {
			return $this ->onError ( "Aucune definition de bladelogic pour " . $nom );
		}
		$this ->setNomServeur ( $nom );
		
		if (! isset ( $liste_data_bladelogic ["username"] )) {
			return $this ->onError ( "Il faut un username dans la liste des parametres bladelogic" );
		}
		if (! isset ( $liste_data_bladelogic ["password"] )) {
			return $this ->onError ( "Il faut un password dans la liste des parametres bladelogic" );
		}
		if (! isset ( $liste_data_bladelogic ["url"] )) {
			return $this ->onError ( "Il faut une url dans la liste des parametres bladelogic" );
		}
		
		$this ->loginUsingUserCredential ();
		return $this;
	}

	/**
	 * @param string $wsdl
	 * @return bladelogic_wsclient|soap|bool
	 * @throws Exception
	 */
	public function &connecte_bsa(string $wsdl): static|soap|bool {
		$this ->onDebug ( "connecte_bsa", 1 );
		//On gere la partie Soap webService
		$this ->getObjetSoap () 
			->setCacheWsdl ( WSDL_CACHE_NONE ) 
			->retrouve_variables_tableau ( $this ->getObjetBladelogicDatas () 
			->recupere_donnees_bladelogic_serveur ( $this ->getNomServeur (), $wsdl ) ) 
			->connect ();
		
		if ($this ->getAuth () != "") {
			$header = array ();
			$header [] = new \SoapHeader ( "http://bladelogic.com/webservices/framework/xsd", 'sessionId', $this ->getAuth () );
			
			$this ->getObjetSoap () 
				->getSoapClient () 
				->__setSoapHeaders ( $header );
		}
		return $this;
	}

	/**
	 * Execute la demande soap
	 * @param string $fonction Fonction SOAP demandee
	 * @param array $params Parametres de la fonction
	 * @return boolean
	 * @throws Exception
	 */
	public function applique_requete_soap(
		string $fonction,
		array  $params = array()): bool {
		$this ->onDebug ( "applique_requete_soap", 1 );
		
		try {
			if ($this ->getListeOptions () 
				->getOption ( "dry-run" ) !== false) {
				$this ->onWarning ( "DRY RUN : " . $fonction . " NON EXECUTE" );
				$resultat = false;
			} else {
				/** @var callable $fn */
				$fn = $this ->getObjetSoap ()->getSoapClient ();
				$resultat = $fn( $fonction, $params );
				
				$this ->onDebug ( $this ->getObjetSoap () 
					->getSoapClient () 
					->__getLastRequest (), 2 );
			}
		} catch ( Exception $e ) {
			return $this ->onError ( $e ->getMessage (), $this ->getObjetSoap () 
				->getSoapClient () 
				->__getLastRequest (), $e ->getCode () );
		}
		
		return $resultat;
	}

	/**
	 * ***************************** LoginService *******************************
	 */
	/**
	 * Connecte le user
	 *
	 * @return bool|array false en cas d'erreur
	 * @throws Exception
	 */
	public function loginUsingUserCredential(): bool|array {
		$this ->onDebug ( "loginUsingUserCredential", 1 );
		
		$this ->connecte_bsa ( "LoginService" );
		$blSessionId = $this ->applique_requete_soap ( "loginUsingUserCredential", array (
				"userName" => $this ->getObjetSoap () 
					->getLogin (),
				"password" => $this ->getObjetSoap () 
					->getPassword (),
				"authenticationType" => "SRP" ) );
		
		if (isset ( $blSessionId->returnSessionId )) {
			$this ->setAuth ( $blSessionId->returnSessionId );
		} else {
			return $this ->onError ( "Pas de session ID", $blSessionId, 4000 );
		}
		
		$this ->onDebug ( $blSessionId, 2 );
		return $blSessionId;
	}

	/**
	 * ***************************** LoginService *******************************
	 */
	
	/**
	 * ***************************** AssumeRoleService *******************************
	 */
	/**
	 * Connecte le user
	 *
	 * @return bool false en cas d'erreur
	 * @throws Exception
	 */
	public function AssumeRole($role): bool {
		$this ->onDebug ( "AssumeRole", 1 );
		
		$this ->connecte_bsa ( "AssumeRoleService" );
		$resultat = $this ->applique_requete_soap ( "AssumeRole", array (
				"roleName" => $role ) );
		
		$this ->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * ***************************** AssumeRoleService *******************************
	 */
	
	/**
	 * ***************************** CLITunnelService *******************************
	 */
	/**
	 * Execute une commande
	 *
	 * @return bool false en cas d'erreur
	 * @throws Exception
	 */
	public function executeCommandByParamString($nameSpace, $commandeName): bool {
		$this ->onDebug ( "executeCommandByParamString", 1 );
		
		$this ->connecte_bsa ( "executeCommandByParamString" );
		$resultat = $this ->applique_requete_soap ( "AssumeRole", array (
				"nameSpace" => $nameSpace,
				"commandName" => $commandeName ) );
		
		$this ->onDebug ( $resultat, 2 );
		return $resultat;
	}

	/**
	 * ***************************** CLITunnelService *******************************
	 */

	/**
	 * @throws Exception
	 */
	public function RESTRequestService(): bool {
		$this ->onDebug ( "RESTRequestService", 1 );
		$this ->connecte_bsa ( "RESTRequestService" );
		
		$this ->_retrouveDonneesSoap ();
		return true;
	}

	/**
	 * @throws Exception
	 */
	public function HandshakeService(): bool {
		$this ->onDebug ( "HandshakeService", 1 );
		
		$this ->connecte_bsa ( "HandshakeService" );
		$this ->_retrouveDonneesSoap ();
		return true;
	}

	/**
	 * @throws Exception
	 */
	public function StandardAttributeInterfaceService(): bool {
		$this ->onDebug ( "StandardAttributeInterfaceService", 1 );
		
		$this ->connecte_bsa ( "StandardAttributeInterfaceService" );
		$this ->_retrouveDonneesSoap ();
		return true;
	}

	/**
	 * Methode temporaire @codeCoverageIgnore
	 * @return boolean
	 * @throws Exception
	 */
	private function _retrouveDonneesSoap(): bool {
		try {
			$functions = $this ->getObjetSoap () 
				->getSoapClient () 
				->__getFunctions ();
			if (! is_array ( $functions )) {
				return true;
			}
			foreach ( $this ->getObjetSoap () 
				->getSoapClient () 
				->__getFunctions () as $function ) {
				$this ->onInfo ( $function );
			}
			$Types = $this ->getObjetSoap () 
				->getSoapClient () 
				->__getTypes ();
			if (! is_array ( $functions )) {
				return true;
			}
			foreach ( $Types as $types ) {
				$this ->onInfo ( print_r ( $types, true ) );
			}
		} catch ( Exception $e ) {
			$this ->onDebug ( $this ->getObjetSoap () 
				->getSoapClient () 
				->__getLastRequest (), 2 );
			return $this ->onError ( $e ->getMessage (), "", $e ->getCode () );
		}

		return true;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return bladelogic_datas|null
	 */
	public function &getObjetBladelogicDatas(): ?bladelogic_datas {
		return $this->bladelogic_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetBladelogicDatas(&$bladelogic_datas): static {
		$this->bladelogic_datas = $bladelogic_datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNomServeur(): string {
		return $this->nom_serveur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNomServeur($nom_serveur): static {
		$this->nom_serveur = $nom_serveur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getAuth(): string {
		return $this->auth;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAuth($auth): static {
		$this->auth = $auth;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return soap
	 */
	public function &getObjetSoap(): soap {
		return $this->objet_soap;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSoap(&$objet_soap): static {
		$this->objet_soap = $objet_soap;
		return $this;
	}

	/**
	 * *********************** Accesseurs **********************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = [];
		
		return $help;
	}
}
