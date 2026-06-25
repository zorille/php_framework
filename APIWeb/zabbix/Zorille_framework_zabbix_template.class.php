<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_template
 * templateid 	string 	(readonly) ID of the template.
 * host (required) 	string 	Technical name of the template.
 * name 	string 	Visible name of the host.
 * 		Default: host property value.
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_template extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $templateid = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $host = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $name = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_template.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param zabbix_wsclient $zabbix_ws Reference sur un objet zabbix_wsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return zabbix_template
	 */
	static function &creer_zabbix_template(options &$liste_option, zabbix_wsclient &$zabbix_ws, bool|string $sort_en_erreur = false, string $entete = __CLASS__): zabbix_template
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_template ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option,
				"zabbix_wsclient" => $zabbix_ws 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_template
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de zabbix_fonctions_standard
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return bool|zabbix_template True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param(): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		//Gestion des template
		$template = $this->_valideOption ( array (
				"zabbix",
				"template" 
		) );
		$this->setName ( $template );
		
		return $this;
	}

	/**
	 * Creer un definition d'un template sous forme de tableau
	 * 
	 * @return array;
	 */
	public function creer_definition_template_create_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$name = $this->getName ();
		if ($name == "") {
			$name = $this->getHost ();
		}
		return array (
				"host" => $this->getHost (),
				"name" => $name
		);
	}

	/**
	 * Creer un template dans zabbix
	 *
	 * @return array
	 * @throws Exception
	 */
	public function creer_template(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$datas = $this->creer_definition_template_create_ws ();
		$this->onDebug ( $datas, 1 );
		return $this->getObjetZabbixWsclient ()
			->templateCreate ( $datas );
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getTemplateId(): string
	{
		return $this->templateid;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTemplateId($templateid): static
	{
		$this->templateid = $templateid;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setName($name): static
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHost(): string
	{
		return $this->host;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHost($host): static
	{
		$this->host = $host;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Zabbix Template :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_template 'template' template d'un host";
		
		return $help;
	}
}
