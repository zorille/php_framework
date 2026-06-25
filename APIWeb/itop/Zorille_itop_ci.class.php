<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class ci
 *
 * @package Lib
 * @subpackage itop
 */
class ci extends Core\abstract_log {
	private string $format = '';
	private string $id = '';
	private array $donnees = [];
	private array $mandatory = [];
	private string $oql_ci = '';
	private bool $update = false;
	private ?wsclient_rest $wsclient_rest = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type ci. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ci
	 * @throws Exception
	 */
	static function &creer_itop_ci(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): ci
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ci ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ci
	 * @throws Exception
	 */
	public function &_initialise(
		array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setObjetItopWsclientRest ( $liste_class ["wsclient_rest"] );
	}

	public function reinitialise(): self {
		return $this->setFormat('')
			->setId('')
			->setDonnees([])
			->setMandatory([])
			->setOqlCi('')
			->setUpdate(false);
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
	 * Cree un ligne type ' AND champ1='valeur1' AND champ2='valeur2' ... etc
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return string
	 */
	public function prepare_oql_fields(
		array $fields): string
	{
		$liste_fields = "";
		foreach ( $fields as $champ => $valeur ) {
			if (! $liste_fields == "") {
				$liste_fields .= " AND ";
			}
			if($champ=='org_id' && is_array($valeur)){
				$liste_fields .= $champ. " IN (SELECT Organization WHERE Organization.name='" . str_replace ( "'", "\'", $valeur['name'] ) . "')";
			} else if (preg_match ( '/( IN | LIKE )/', $valeur )) {
				$liste_fields .= $champ . $valeur;
			} else {
				$liste_fields .= $champ . "='" . str_replace ( "'", "\'", $valeur ) . "'";
			}
		}
		return $liste_fields;
	}

	/**
	 * Prepare une requete OQL de recherche dans itop
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return $this
	 */
	public function creer_oql(
		array $fields = array ()): static
	{
		$where = $this->prepare_oql_fields ( $fields );
		if (! empty ( $where )) {
			$where = " WHERE " . $where;
		}
		$this->onDebug ( "OQL : " . "SELECT " . $this->getFormat () . $where, 1 );
		return $this->setOqlCi ( "SELECT " . $this->getFormat () . $where );
	}

	/**
	 * Enregistre les donnees class, key et fields du premier objet de la reponse REST
	 * @param array $ci Retour d'un requete REST sur itop
	 * @return $this
	 */
	public function enregistre_ci_a_partir_rest(
		array $ci): static
	{
		foreach ( $ci ['objects'] as $donnees ) {
			$this->setFormat ( $donnees ['class'] )
				->setId ( $donnees ['key'] )
				->setDonnees ( $donnees ['fields'] );
			break;
		}
		return $this;
	}

	/**
	 * Permet de trouver un CI dans itop a partir d'une requete OQL
	 * @return $this|false False en cas d'erreur sans leve d'Exception ($error=false)
	 * @throws Exception
	 */
	public function recupere_ci_dans_itop(string $collect_data="*"): bool|static|array
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( $this->getFormat ()." ".$this->getOqlCi () , 2 );
		// Sinon, on requete iTop
		return $this->getObjetItopWsclientRest ()
			->core_get ( $this->getFormat (), $this->getOqlCi (), $collect_data );
	}

	/**
	 * Permet de trouver un CI dans itop a partir d'une requete OQL et enregistre les donnees du CI dans l'objet
	 * @return ci|bool
	 * @throws Exception
	 */
	public function retrouve_ci(): static|bool
	{
		// Si il y a deja un objet ci, alors le ci existe
		if ($this->getDonnees ()) {
			return $this;
		}
		// Sinon, on requete iTop
		$ci = $this->recupere_ci_dans_itop ();
		$this->onDebug ( $ci, 1 );
		if ($ci ['message'] != 'Found: 1') {
			// Le ci n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la requete : " . $this->getOqlCi () . " : " . $ci ['message'] );
		}
		return $this->enregistre_ci_a_partir_rest ( $ci );
	}

	/**
	 * Valide que le CI existe et est unique dans itop et enregistre les donnees du CI dans l'objet s'il est trouve
	 * @return $this|null
	 * @throws Exception
	 */
	public function valide_ci_existe(
		bool $collect_only_id=false): ?static
	{
		$this->onDebug ( __METHOD__, 1 );
		// Si il y a deja un objet ci, alors le ci existe
		if ($this->getDonnees ()) {
			return $this;
		}
		// Sinon, on requete iTop
		if($collect_only_id) {
			$collect_data="id";
		} else {
			$collect_data="*";
		}
		$ci = $this->recupere_ci_dans_itop ($collect_data);
		if ($ci ['message'] != 'Found: 1') {
			// Le ci n'existe pas
			$this->onDebug ( "Probleme avec la requete : " . $this->getOqlCi () . " : " . $ci ['message'], 1 );
			return null;
		}
		return $this->enregistre_ci_a_partir_rest ( $ci );
	}

	/**
	 * Creer un CI dans itop du format de l'objet
	 * @param string $name
	 * @param array $params
	 * @return $this
	 * @throws Exception
	 */
	public function creer_ci(
		string $name,
		array  $params): static
	{
		$this->onDebug ( __METHOD__, 1 );
		if (! $this->valide_ci_existe ()) {
			$this->onInfo ( "Ajout de : " . $name );
			$ci = $this->getObjetItopWsclientRest ()
				->core_create ( $this->getFormat (), '', $params );
			$this->enregistre_ci_a_partir_rest ( $ci );
		} else if ($this->getUpdate ()) {
			$this->onInfo ( "Update de : " . $name );
			$ci = $this->getObjetItopWsclientRest ()
				->core_update ( $this->getFormat (), $this->getId (), $params );
			$this->enregistre_ci_a_partir_rest ( $ci );
		}
		return $this;
	}

	/**
	 * Update un CI dans itop du format de l'objet
	 * @param string $name
	 * @param array $params
	 * @param bool $force_update bypass validation CI exist
	 * @param string $output_data Limit output fields from core-update
	 * @return $this
	 * @throws Exception
	 */
	public function update_ci(
		string $name,
		array  $params,
		bool $force_update=false,
		string $output_data="*"): static
	{
		$this->onDebug ( __METHOD__, 1 );
		if ($force_update || $this->valide_ci_existe ()) {
			$this->onInfo ( "Update de : " . $name . " ID = ".$this->getId () );
			$ci = $this->getObjetItopWsclientRest ()
				->core_update ( $this->getFormat (), $this->getId (), $params, $output_data );
			$this->enregistre_ci_a_partir_rest ( $ci );
		}
		return $this;
	}

	/**
	 * Valide si tous les champs nécessaires sont remplis avec une données
	 * @return ci|bool
	 * @throws Exception
	 */
	public function valide_mandatory_fields(): static|bool
	{
		$this->onDebug ( __METHOD__, 1 );
		$retour = array ();
		foreach ( $this->getMandatory () as $champ => $valeur ) {
			if ($valeur === false) {
				$retour [] .= $champ;
			}
		}
		if (count ( $retour ) != 0) {
			return $this->onError ( "Il manque des champs obligatoires : ", $retour, 1 );
		}
		return $this;
	}

	/**
	 * Valide que valeur a des donnees et que le champ esr Mandatory
	 * @param string $champ
	 * @param mixed $valeur
	 * @return true
	 */
	public function valide_mandatory_field_filled(
		string $champ,
        mixed $valeur): bool
	{
		if (isset ( $this->getMandatory () [$champ] ) && ! empty ( $valeur )) {
			$this->setMandatoryField ( $champ );
		}
		return true;
	}

	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_standard_params(
		array $parametres): array
	{
		$params = array ();
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				case 'org_name' :
				case 'organization_name' :
					$params ['org_id'] = $this->getObjetItopOrganization ()
						->prepare_params_obligatoire ( array (
							'name' => $valeur
					) );
					$this->valide_mandatory_field_filled ( 'org_id', $params ['org_id'] );
					break;
				default :
					$this->valide_mandatory_field_filled ( $champ, $valeur );
					$params [$champ] = $valeur;
			}
		}
		return $params;
	}

	/**
	 * Prepare les parametres obligatoire d'un objet. Necessite les champ type org_id deja rempli correctement.
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_obligatoire(
		array $parametres): array
	{
		$params = array ();
		foreach ( $this->getMandatory () as $champ => $inutile ) {
			if (isset ( $parametres [$champ] )) {
				$params [$champ] = $parametres [$champ];
			}
		}
		return $params;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getFormat(): string
	{
		return $this->format;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFormat(
			$format): static
	{
		$this->format = $format;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getId(): string
	{
		return $this->id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setId(
			$id): static
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDonnees(): array
	{
		return $this->donnees;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDonnees(
			$donnees): static
	{
		if (is_array ( $donnees )) {
			$this->donnees = $donnees;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMandatory(): array
	{
		return $this->mandatory;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMandatory(
			$mandatory): static
	{
		if (is_array ( $mandatory )) {
			$this->mandatory = $mandatory;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $field
	 * @return ci
	 */
	public function &setMandatoryField(
		string $field): static
	{
		if (isset ( $this->mandatory [$field] )) {
			$this->mandatory [$field] = true;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return wsclient_rest|null
	 */
	public function &getObjetItopWsclientRest(): ?wsclient_rest
	{
		return $this->wsclient_rest;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopWsclientRest(
			&$wsclient_rest): static
	{
		$this->wsclient_rest = $wsclient_rest;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOqlCi(): string
	{
		return $this->oql_ci;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setOqlCi(
			$oql_ci): static
	{
		$this->oql_ci = $oql_ci;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUpdate(): bool
	{
		return $this->update;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUpdate(
			$update): static
	{
		$this->update = $update;
		return $this;
	}

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
		$help [__CLASS__] ["text"] [] .= "ci :";
		return $help;
	}
}
