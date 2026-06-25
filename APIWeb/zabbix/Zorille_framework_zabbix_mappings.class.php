<?php
/**
 * Gestion de zabbix.
 * @author dvargas
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class zabbix_mappings
 *
 * mappingid 	string 	(readonly) ID of the mapping.
 * value (required) 	string 	Value to map.
 * newvalue (required) 	string 	Mapped Value of the mapping.
 * valuemapid 	string 	(readonly) ID of the value mapping for this mapping.
 *  
 * @package Lib
 * @subpackage Zabbix
 */
class zabbix_mappings extends zabbix_fonctions_standard {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_mapping = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type zabbix_mappings.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return abstract_log|zabbix_mappings
	 */
	static function &creer_zabbix_mappings(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): abstract_log|zabbix_mappings
	{
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new zabbix_mappings ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return zabbix_mappings
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
	 * @return bool|zabbix_mappings True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_zabbix_param(): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_mapping = $this->_valideOption ( array (
				"zabbix",
				"mappings",
				"values" 
		), array () );
		if (! is_array ( $liste_mapping )) {
			$liste_mapping = array (
					$liste_mapping 
			);
		}
		
		// @codeCoverageIgnoreStart
		if (empty ( $liste_mapping )) {
			$mappingFile = $this->_valideOption ( array (
					"zabbix",
					"mappings",
					"mappingFile" 
			), "/tmp" );
			$liste_mapping = fichier::Lit_integralite_fichier_en_tableau ( $mappingFile );
		}
		// @codeCoverageIgnoreEnd
		

		$this->retrouve_mappings ( $liste_mapping );
		
		return $this;
	}

	/**
	 * Extrait les valeurs et le Map (newvalue) correspondant a partir d'un tableau.
	 * @param array $liste_mappings Liste de mapping au format value=>newvalue dans chaque entree du tableau
	 * @return bool|zabbix_mappings
	 * @throws Exception
	 */
	public function retrouve_mappings($liste_mappings): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		if (! is_array ( $liste_mappings )) {
			return $this->onError ( "Il faut un tableau de mapping" );
		}
		$liste_mapping_finale = array ();
		foreach ( $liste_mappings as $mapping ) {
			if (preg_match ( '/(?<titre>.*)=>(?<valeur>.*)/', $mapping, $resultat )) {
				$liste_mapping_finale [$resultat ['titre']] = array (
						"value" => $resultat ['titre'],
						"newvalue" => $resultat ['valeur'] 
				);
			}
		}
		
		if (empty ( $liste_mapping_finale )) {
			return $this->onError ( "Il faut des donnees de mapping" );
		}
		$this->onDebug ( $liste_mapping_finale, 2 );
		return $this->setListeMapping ( $liste_mapping_finale );
	}

	/**
	 * Creer un definition d'un mappings sous forme de tableau
	 * @return array;
	 * @throws Exception
	 */
	public function creer_definition_mappings_create_ws(): array
	{
		$this->onDebug ( __METHOD__, 1 );
		$mappingsid = array ();
		foreach ( $this->getListeMapping () as $mapping ) {
			$mappingsid[] = array(
				"value" => $mapping ["value"],
				"newvalue" => $mapping ["newvalue"]
			);
		}
		
		return $mappingsid;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeMapping(): array
	{
		return $this->liste_mapping;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeMapping($ListeMapping): static
	{
		$this->liste_mapping = $ListeMapping;
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
		$help [__CLASS__] ["text"] [] .= "Zabbix Mappings :";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mappings_values 'value=>mapvalue' 'value=>mapvalue' Liste des valeurs de mapping ";
		$help [__CLASS__] ["text"] [] .= "\t--zabbix_mappings_mappingFile mapping.txt fichier contenant chaque mapping : value=>mapvalue , 1 par ligne ";
		return array_merge ( $help, fichier::help () );
	}
}
