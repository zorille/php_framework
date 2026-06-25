<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class Software
 *
 * @package Lib
 * @subpackage itop
 */
class Software extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Software. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Software
	 * @throws Exception
	 */
	static function &creer_Software(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): Software
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Software ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Software|Organization
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Software' )
			->champ_obligatoire_standard ();
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return Software
	 */
	public function &champ_obligatoire_standard(): static
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'vendor' => false,
					'version' => false
			) );
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_Software(
			$friendlyname,
			$version): ci|Software|bool
	{
		return $this->creer_oql ( array (
				'friendlyname' => $friendlyname,
				'version' => $version
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_Software(
		array $parametres): array
	{
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Software
	 */
	public function creer_oql_Software(
		array $fields = array ()): Software
	{
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			$filtre [$field] = match ($field) {
				default => $fields [$field],
			};
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs necessaires : name, vendor, version, type
	 * @param $parametres
	 * @return Software
	 * @throws Exception
	 */
	public function gestion_Software(
			$parametres): Software
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_StockElement ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_Software ( $parametres )
			->creer_ci ( $params ['name'] . " " . $params ['version'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Software :";
		return $help;
	}
}
