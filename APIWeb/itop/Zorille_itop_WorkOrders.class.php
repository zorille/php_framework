<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class WorkOrder
 *
 * @package Lib
 * @subpackage itop
 */
class WorkOrders extends ci {
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type WorkOrder. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return WorkOrders
	 * @throws Exception
	 */
	static function &creer_WorkOrders(
		Core\options  $liste_option,
		wsclient_rest $webservice_rest,
		string|bool   $sort_en_erreur = false,
		string        $entete = __CLASS__
	): self {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new WorkOrders ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return WorkOrders
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'WorkOrder' )
			->champ_obligatoire_standard ();
	}

    public function reinitialise(
        Core\options|null  $liste_option = null,
        wsclient_rest|null $webservice_rest = null,
    ): self
    {
        parent::reinitialise();
        $this->_initialise(array (
            "options" => $liste_option,
            "wsclient_rest" => $webservice_rest
        ) );
        return $this;
    }

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		string|bool $sort_en_erreur = false,
		string      $entete = __CLASS__
	) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return self
	 */
	public function &champ_obligatoire_standard(): self {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ([
                'name' => true,
                'description' => true,
                'team_id' => true,
                'time_spent' => true
            ]);
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_WorkOrders($name): self {
		return $this->creer_oql ( array (
            'name' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parameters
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_WorkOrders(array $parameters): array {
		return $this->prepare_standard_params ( $parameters );
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return self
	 */
	public function creer_oql_WorkOrders(array $fields = []): self {
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
            $filtre [$field] = $fields [$field];
		}
		if (! isset ( $filtre ['status'] )) {
			$filtre ['status'] = " NOT IN ('closed')";
		}
		$this->onDebug ( $filtre, 1 );
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs existants: name, status, description, ticket_id, ticket_ref, team_id, team_name, agent_id, agent_email, start_date, log, time_spent
     * @param array $parameters
	 * @return WorkOrders
	 * @throws Exception
	 */
	public function gestion_WorkOrders(array $parameters): self {
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( $parameters, 1 );
		$params = $this->prepare_params_WorkOrders ( $parameters );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_WorkOrders ( $params )
			->creer_ci ( $params ['name'], $params );
	}

	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = [
            "WorkOrders :"
        ];
		return $help;
	}
}
