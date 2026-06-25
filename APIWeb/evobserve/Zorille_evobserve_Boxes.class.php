<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

use Exception;
use Zorille\framework as Core;

/**
 * class Boxe
 *
 * @package Lib
 * @subpackage evobserve
 */
class Boxes extends item {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $boxe = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Boxe. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return $this
	 * @throws Exception
	 */
	static function &creer_Boxes(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): Boxes|static
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Boxes ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return $this
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
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
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format evobserve
	 */
	public function prepare_params_Boxe(
		array $parametres): array {
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * ******************************* Boxe URI ******************************
	 */
	public function boxes_uri(): string {
		return $this->globalapi_uri () . '/boxes';
	}

	public function boxe_conf_uri(): string {
		return $this->boxes_uri () . '/configurations';
	}

	/**
	 * ******************************* Evobserve Boxe *********************************
	 * @throws Exception
	 */
	public function retrouve_id_boxe(
			$Boxe,
			$company_id = 2) {
		$this->onDebug ( __METHOD__, 1 );
		if ($company_id != 2) {
			$this->onDebug ( "Company ID : ".$company_id, 2 );
			$this->retrouve_Boxes ( $company_id );
		}
		if (isset ( $this->getBoxes () [strtoupper ( $Boxe )] )) {
			return $this->getBoxes () [strtoupper ( $Boxe )];
		}
		return $this->onError ( "Le Collecteur " . $Boxe . " n'existe pas dans la liste", "", 1 );
	}

	public function prepare_Boxes(): static {
		$this->onDebug ( __METHOD__, 1 );
		$liste_Boxe = array ();
		if (isset ( $this->getDonnees ()->_embedded->items )) {
			foreach ( $this->getDonnees ()->_embedded->items as $Boxe ) {
				$liste_Boxe [mb_strtoupper ( $Boxe->name )] = $Boxe->id;
			}
		}
		$this->onDebug ( $liste_Boxe, 1 );
		return $this->setBoxes ( $liste_Boxe );
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_Boxes(
			$company_id = 2,
			$params = array (
				"company" => array (
						1,
						2
				),
				"inheritance" => true,
				"limit" => 1000,
				"sort" => array (
						"+name"
				)
			)): Boxes {
		$this->onDebug ( __METHOD__, 1 );
		if (! in_array ( $company_id, $params ["company"] )) {
			$params ["company"] [] .= $company_id;
		}
		$resultat = $this->getObjetEvobserveWsclient ()
			->getMethod ( $this->boxes_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_Boxes ();
	}

	/**
	 * @throws Exception
	 */
	public function verifie_presence_boxes(): static {
		if(empty($this->getBoxes ())){
			$this->retrouve_Boxes();
		}
		return $this;
	}

	/**
	 * Met a jour les configurations de toutes les boxes de collecte
	 * @return $this
	 * @throws Exception
	 */
	public function updateConfigurationToutesBoxes(): static {
		$parametres = array (
				"collectorIds" => array ()
		);
		foreach ( $this->verifie_presence_boxes()->getBoxes () as $box_name=>$boxe_id ) {
			$this->onInfo("On update ".$box_name);
			$parametres ["collectorIds"] [0] = $boxe_id;
			$this->updateConfiguration ( $parametres );
		}
		return $this;
	}

	/**
	 * Met a jour les configurations des boxes de collecte
	 * @param array $parametres
	 * @return $this
	 * @throws Exception
	 */
	public function updateConfiguration(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$this->setMandatory ( array (
				"collectorIds" => false
		) );
		$params = $this->prepare_params_Boxe ( $parametres );
		$this->onDebug ( $params, 1 );
		$this->valide_mandatory_fields ()
			->getObjetEvobserveWsclient ()
			->postMethod ( $this->boxe_conf_uri (), $params );
		return $this;
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return Boxes
	 */
	public function creerBoxe(
		array $parametres): Boxes {
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return array
	 */
	public function getBoxes(): array {
		return $this->boxe;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBoxes(
			$liste_boxe): static {
		$this->boxe = $liste_boxe;
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
		$help [__CLASS__] ["text"] [] .= "Boxes :";
		return $help;
	}
}
