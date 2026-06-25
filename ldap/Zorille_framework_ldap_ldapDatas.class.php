<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\framework\ldap;
use Zorille\framework as Core;
use Exception;
/**
 * class ldapDatas
 *
 * @package Lib
 * @subpackage itop
 */
class ldapDatas extends Core\serveur_datas {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $wsdl_data = false;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type ldapDatas.
	 * @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return self
	 */
	static function &creer_ldapDatas(Core\options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): ldapDatas
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new self ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );

		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return self
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );

		$this->retrouve_param ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/

	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return self True est OK, False sinon.
	 */
	public function retrouve_param(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		$donnee_itop = $this->_valideOption ( array (
				"ldap_machines",
				"serveur"
		) );

		$this->setServeurData ( $donnee_itop );

		//Gestion des WSDL
		$wsdl_itop = $this->_valideOption ( array (
				"ldap_machines",
				"wsdl"
		) );

		$this->setWsdlData ( $wsdl_itop );

		return $this;
	}

	/**
	 * Valide la presence de la definition d'un itop nomme : $nom
	 *
	 * @param string $nom
	 * @param string $protocole rest|soap|both par defaut 'both'
	 * @return array|bool false informations de configuration, false sinon.
	 */
	public function valide_presence_data(string $nom, string $protocole='both'): array|bool
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this->valide_presence_serveur_data ( $nom, $protocole );
	}

    /**
     * Valide la presence de la definition d'un itop nomme : $nom
     *
     * @param $wsdl
     * @return array|bool false informations de configuration, false sinon.
     * @throws Exception
     */
	public function retrouve_wsdl($wsdl): bool|array
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_wsdl = $this->getWsdlDatas ();
		if (! isset ( $liste_wsdl [$wsdl] )) {
			return $this->onError ( "Ce wsdl " . $wsdl . " n'existe pas.", "", 5105 );
		}
		if (is_array ( $liste_wsdl [$wsdl] )) {
			return $liste_wsdl [$wsdl] [0];
		}
		return $liste_wsdl [$wsdl];
	}

	/**
	 * Connexion au soap preferences de itop
	 *
	 * @param string $nom nom du itop a connecter
	 * @return array|bool TRUE si connexion ok, FALSE sinon
	 * @throws Exception
	 */
	public function recupere_donnees_serveur(string $nom = "", $wsdl = ""): array|bool {
		$this->onDebug ( __METHOD__, 1 );
		if ($nom == "") {
			return $this->onError ( "Il faut un nom de ldap pour se connecter.", "", 5103 );
		}
		if ($wsdl == "") {
			return $this->onError ( "Il faut un wsdl de ldap pour se connecter.", "", 5104 );
		}
		$serveur_data = $this->valide_presence_data ( $nom, 'soap' );
		if ($serveur_data === false) {
			$this->onWarning ( "Pas de configuration de ldap pour se connecter." );
			return false;
		}

		$serveur_data ["wsdl"] = $this->retrouve_wsdl ( $wsdl );
		return $serveur_data;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getWsdlDatas(): bool|array
    {
		return $this->wsdl_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWsdlData($wsdl_data): self
    {
		if (is_array ( $wsdl_data )) {
			$this->wsdl_data = $wsdl_data;
		}
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
		$help [__CLASS__] ["text"] [] .= "itop Datas :";
		$help [__CLASS__] ["text"] [] .= "\t--ldap_machines_serveur {Donnees du/des serveur/s} Donnees contenus dans le fichier de configuration";
		
		return $help;
	}
}
