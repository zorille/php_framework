<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class Incident
 *
 * @package Lib
 * @subpackage itop
 */
class Incident extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization
	 */
	private $Organization = null;

    private ?Contact $Contact = null;

    /**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Incident. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Incident
	 * @throws Exception
	 */
	static function &creer_Incident(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): Incident
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Incident ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Incident
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Incident' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopContact ( Contact::creer_Contact ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * @return self
	 */
	public function &champ_obligatoire_standard(): static
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'title' => false,
					'org_id' => false,
					'caller_id' => false,
					'description' => false
			) );
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_Incident(
			$name): ci|bool|Incident
	{
		return $this->creer_oql ( array (
				'title' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_Incident(
		array $parametres): array
	{
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Incident
	 */
	public function creer_oql_Incident(
		array $fields = array ()): Incident
	{
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'org_id' :
					$filtre ['org_name'] = $fields ['org_name'];
					break;
				case 'caller_id' :
					$filtre ['caller_id'] = $fields ['caller_id'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		if (! isset ( $filtre ['status'] )) {
			$filtre ['status'] = " NOT IN ('closed')";
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs existants : title, org_name, description, impact, urgency, origin, caller_id, contacts_list, functionalcis_list, workorders_list
	 * @param $parametres
	 * @return Incident
	 * @throws Exception
	 */
	public function gestion_Incident(
			$parametres): Incident|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Incident ( $parametres );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_Incident ( $parametres )
			->creer_ci ( $params ['title'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopOrganization(): ?Organization
	{
		return $this->Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization(
			&$Organization): static
	{
		$this->Organization = $Organization;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return Contact
	 */
	public function &getObjetItopContact(): Contact {
		return $this->Contact;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopContact(Contact &$Contact): self {
		$this->Contact = $Contact;
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
		$help [__CLASS__] ["text"] [] .= "Incident :";
		return $help;
	}
}
