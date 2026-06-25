<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class OSVersion
 *
 * @package Lib
 * @subpackage itop
 */
class OSVersion extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var OSFamily
	 */
	private $OSFamily = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type OSVersion. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return OSVersion
	 */
	static function &creer_OSVersion(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): OSVersion
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new OSVersion ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return OSVersion
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'OSVersion' )
			->champ_obligatoire_standard ()
			->setObjetItopOSFamily ( OSFamily::creer_OSFamily ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * @return OSVersion
	 */
	public function &champ_obligatoire_standard(): OSVersion
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'osfamily_id' => false
			) );
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_OSVersion(
			$name): ci|OSVersion|bool
	{
		return $this->creer_oql ( array (
				'friendlyname' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_OSVersion(
		array $parametres): array
	{
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			if ($champ == 'osfamily_name') {
				$params ['osfamily_id'] = $this->getObjetItopOSFamily()
					->creer_oql(array(
						'name' => $valeur
					))
					->getOqlCi();
				$this->valide_mandatory_field_filled('osfamily_id', $params ['osfamily_id']);
				if (isset ($params ['osfamily_name'])) {
					unset ($params ['osfamily_name']);
				}
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return OSVersion
	 */
	public function creer_oql_OSVersion(
		array $fields = array ()): OSVersion
	{
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'osfamily_id' :
					$filtre ['osfamily_name'] = $fields ['osfamily_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs standards : name, osfamily_name
	 * @return OSVersion
	 * @throws Exception
	 */
	public function gestion_OSVersion(
			$parametres): OSVersion
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_OSVersion ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_OSVersion ( $parametres )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return OSFamily|null
	 */
	public function &getObjetItopOSFamily(): ?OSFamily
	{
		return $this->OSFamily;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOSFamily(
			&$OSFamily): static
	{
		$this->OSFamily = $OSFamily;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "OSVersion :";
		return $help;
	}
}
