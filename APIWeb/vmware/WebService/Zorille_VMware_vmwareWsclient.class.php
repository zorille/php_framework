<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\VMware;

use Zorille\framework as Core;
use Exception as Exception;
use SimpleXMLElement as SimpleXMLElement;
use stdClass as stdClass;
use Zorille\framework\gestion_connexion_url;
use Zorille\framework\options;
use Zorille\framework\soap;

/**
 * class vmwareWsclient<br> Renvoi des information via un webservice.
 * @package Lib
 * @subpackage VMWare
 */
class vmwareWsclient extends Core\abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var vmwareDatas
	 */
	private $vmwareDatas = null;
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
	 * @access private
	 * @var vmwareServiceInstance
	 */
	private $ObjectServiceInstance = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var Core\soap
	 */
	private $objet_soap = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type vmwareWsclient. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareDatas &$vmwareDatas Reference sur un objet vmwareDatas
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareWsclient
	 * @throws Exception
	 */
	static function &creer_vmwareWsclient(
		Core\options &$liste_option,
		vmwareDatas  &$vmwareDatas,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): vmwareWsclient
	{
		$objet = new vmwareWsclient ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareDatas" => $vmwareDatas
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareWsclient|bool
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		if (! isset ( $liste_class ["vmwareDatas"] )) {
			$r = $this->onError ( "il faut un objet de type vmwareDatas" );
			return $r;
		}
		$this->setObjetVMWareDatas ( $liste_class ["vmwareDatas"] )
			->setObjetSoap ( Core\soap::creer_soap ( $liste_class ["options"] ) )
			->setObjectServiceInstance ( vmwareServiceInstance::creer_vmwareServiceInstance ( $liste_class ["options"] ) );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @throws Exception
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au vmware nomme $nom
	 * @param string $nom
	 * @return boolean|vmwareWsclient
	 * @throws Exception
	 */
	public function prepare_connexion(
		string $nom): bool|vmwareWsclient|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_data_vmware = $this->getObjetVMWareDatas ()
			->valide_presence_vmware_data ( $nom );
		if ($liste_data_vmware === false) {
			return $this->onError ( "Aucune definition de vmware pour " . $nom );
		}
		$this->setNomServeur ( $nom );
		if (! isset ( $liste_data_vmware ["username"] )) {
			return $this->onError ( "Il faut un username dans la liste des parametres vmware" );
		}
		if (! isset ( $liste_data_vmware ["password"] )) {
			return $this->onError ( "Il faut un password dans la liste des parametres vmware" );
		}
		if (! isset ( $liste_data_vmware ["url"] )) {
			return $this->onError ( "Il faut une url dans la liste des parametres vmware" );
		}
		// On gere la partie Soap webService
		$this->getObjetSoap ()
			->setCacheWsdl ( WSDL_CACHE_NONE )
			->retrouve_variables_tableau ( $this->getObjetVMWareDatas ()
			->recupere_donnees_vmware_serveur ( $nom, "vimService" ) )
			->connect ();
		// On creer un ServiceInstance de VMWare pour connaitre la liste des services disponible
		$ServiceInstance = $this->applique_requete_soap ( $this->getObjectServiceInstance ()
			->getNomFonction (), array (
				$this->getObjectServiceInstance ()
					->prepare_SoapMessage ()
		) );
		$this->getObjectServiceInstance ()
			->setAuth ( $ServiceInstance );
		// On se connecte
		$this->login ( $liste_data_vmware ["username"], $liste_data_vmware ["password"] );
		return $this;
	}

	/**
	 * ***************************** vimService *******************************
	 */
	/**
	 * Execute la demande soap
	 * @param string $fonction Fonction SOAP demandee
	 * @param array $params Parametres de la fonction
	 * @return boolean
	 * @throws Exception
	 */
	public function applique_requete_soap(
		string $fonction,
		array  $params = array ()): bool
	{
		$this->onDebug ( __METHOD__, 1 );
		try {
			if ($this->getListeOptions ()
				->getOption ( "dry-run" ) !== false) {
				$this->onWarning ( "DRY RUN : " . $fonction . " NON EXECUTE" );
				$resultat = false;
			} else {
				$this->onDebug ( "Function : " . $fonction, 2 );
				$this->onDebug ( $params, 2 );
				$result = $this->getObjetSoap ()
					->getSoapClient ()
					->__soapCall ( $fonction, $params );
				if (isset ( $result->returnval )) {
					$resultat = $result->returnval;
				} else {
					$resultat = $result;
				}
				$this->onDebug ( $this->getObjetSoap ()
					->getSoapClient ()
					->__getLastRequest (), 2 );
			}
		} catch ( Exception $e ) {
			return $this->onError ( $e->getMessage (), $this->getObjetSoap ()
				->getSoapClient ()
				->__getLastRequest (), $e->getCode () );
		}
		return $resultat;
	}

	/**
	 * Connecte le user
	 *
	 * @return array|false false en cas d'erreur
	 * @throws Exception
	 */
	public function login(
			$username,
			$password): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		// login via sessionManager
		$soap_message = $this->getObjectServiceInstance ()
			->creer_entete_sessionManager_this ();
		$soap_message->userName = $username;
		$soap_message->password = $password;
		$soap_session = $this->applique_requete_soap ( "Login", array (
				$soap_message
		) );
		$this->onDebug ( $soap_session, 2 );
		return $soap_session;
	}

	/**
	 * logout
	 * @return array|boolean
	 * @throws Exception
	 */
	public function logout(): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		// logout via sessionManager
		$soap_message = $this->getObjectServiceInstance ()
			->creer_entete_sessionManager_this ();
		$soap_session = $this->applique_requete_soap ( "Logout", array (
				$soap_message
		) );
		$this->onDebug ( $soap_session, 2 );
		return $soap_session;
	}

	/**
	 * Convertit les donnees renvoyees par le soap en array ou xml
	 * @param stdClass $donnees
	 * @param string $type array|xml
	 * @return array|Core\xml
	 * @throws Exception
	 */
	public function convertit_donnees(
		stdClass $donnees,
		string   $type = "xml"): Core\xml|array
	{
		$xml_vm_info = new SimpleXMLElement ( "<?xml version=\"1.0\" encoding=\"UTF-8\"?><objects/>" );
		$this->renvoi_donnees_xml ( $donnees, $xml_vm_info );
		$xml = Core\xml::creer_xml ( $this->getListeOptions () );
		$xml->import_dom_a_partir_de_simpleXML ( $xml_vm_info, "objects" );
		switch ($type) {
			case "array" :
				return $xml->renvoi_donnee ( "/objects" );
			case "xml" :
			default :
			// retour standard de la fonction
		}
		return $xml;
	}

	/**
	 * Transforme un soap VMWare en SimpleXMLElement
	 * @param array|stdClass $object_src
	 * @param SimpleXMLElement $xml_output
	 * @return vmwareWsclient
	 */
	public function renvoi_donnees_xml(
		array|stdClass   $object_src,
		SimpleXMLElement &$xml_output): static
	{
		if (is_array ( $object_src )) {
			foreach ( $object_src as $key => $value ) {
				$this->traite_valeur_xml ( $key, $value, $xml_output );
			}
		} elseif (is_object ( $object_src )) {
			if (isset ( $object_src->name ) && isset ( $object_src->val )) {
				$this->traite_valeur_xml ( $object_src->name, $object_src->val, $xml_output );
			} else {
				foreach ( $object_src as $key => $value ) {
					$this->traite_valeur_xml ( $key, $value, $xml_output );
				}
			}
		}
		return $this;
	}

	/**
	 * Choix du traitement en fonction de la valeur. Traitement particulier pour propSet
	 * @param string $key
	 * @param array|string|stdClass $value
	 * @param SimpleXMLElement $xml_output
	 * @return vmwareWsclient
	 */
	public function traite_valeur_xml(
		string                $key,
		array|string|stdClass $value,
		SimpleXMLElement      &$xml_output): static
	{
		if (is_numeric ( $key )) {
			$this->renvoi_donnees_xml ( $value, $xml_output );
		} elseif (is_array ( $value )) {
			// Le propSet a une exection
			if ($key != "propSet" && isset ( $value [0] )) {
				foreach ( $value as $key_MOR => $value_MOR ) {
					$subnode = $xml_output->addChild ( $key );
					$this->traite_valeur_xml ( $key_MOR, $value_MOR, $subnode );
				}
			} else {
				$subnode = $xml_output->addChild ( $key );
				$this->renvoi_donnees_xml ( $value, $subnode );
			}
		} elseif (is_object ( $value )) {
			$subnode = $xml_output->addChild ( $key );
			$this->renvoi_donnees_xml ( $value, $subnode );
		} else {
			$xml_output->addChild ( $key, $value );
		}
		return $this;
	}

	/**
	 * ***************************** vimService *******************************
	 */
	/**
	 * *********************** Accesseurs **********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function __clone() {
		// Force la copie de this->xxx, sinon
		// il pointera vers le meme objet.
		if (is_object ( $this->ObjectServiceInstance ))
			$this->ObjectServiceInstance = clone $this->getObjectServiceInstance ();
		if (is_object ( $this->objet_soap ))
			$this->objet_soap = clone $this->getObjetSoap ();
		if (is_object ( $this->vmwareDatas ))
			$this->vmwareDatas = clone $this->getObjetVMWareDatas ();
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareDatas
	 */
	public function &getObjetVMWareDatas(): ?vmwareDatas
	{
		return $this->vmwareDatas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetVMWareDatas(
			&$vmwareDatas): static
	{
		$this->vmwareDatas = $vmwareDatas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNomServeur(): string
	{
		return $this->nom_serveur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNomServeur(
			$nom_serveur): static
	{
		$this->nom_serveur = $nom_serveur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareServiceInstance|null
	 */
	public function &getObjectServiceInstance(): ?vmwareServiceInstance
	{
		return $this->ObjectServiceInstance;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectServiceInstance(
			$ObjectServiceInstance): static
	{
		$this->ObjectServiceInstance = $ObjectServiceInstance;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return soap|null
	 */
	public function &getObjetSoap(): ?Core\soap
	{
		return $this->objet_soap;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSoap(
			&$objet_soap): static
	{
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
		$help [__CLASS__] ["text"] = array ();
		return $help;
	}
}
