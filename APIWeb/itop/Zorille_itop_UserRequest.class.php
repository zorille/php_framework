<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Exception;
use Zorille\framework as Core;

/**
 * class UserRequest
 *
 * @package Lib
 * @subpackage itop
 */
class UserRequest extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization|null
	 */
	private ?Organization $Organization = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var Contact|null
	 */
	private ?Contact $Contact = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type UserRequest. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return UserRequest
	 * @throws Exception
	 */
	static function &creer_UserRequest(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		string|bool   $sort_en_erreur = false,
		string        $entete = __CLASS__
	): self {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new UserRequest ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return UserRequest
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'UserRequest' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) )
			->setObjetItopContact ( Contact::creer_Contact ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	public function retrouve_UserRequest($name): self {
		return $this->creer_oql ( array (
				'title' => $name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parameters
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_UserRequest(array $parameters): array {
		$params = $this->prepare_standard_params ( $parameters );
		foreach ($parameters as $champ => $valeur ) {
			switch ($champ) {
				case 'caller_email' :
					$params ['caller_id'] = $this->getObjetItopContact ()
						->creer_oql ( array (
							'email' => $valeur
					) )
						->getOqlCi ();
					$this->valide_mandatory_field_filled ( 'caller_id', $params ['caller_id'] );
					if (isset ( $params ['caller_email'] )) {
						unset ( $params ['caller_email'] );
					}
					break;
			}
		}
		return $params;
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return self
	 */
	public function creer_oql_UserRequest(array $fields = []): self {
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'caller_email' :
					$filtre ['caller_id'] = "SELECT Person WHERE email='{$fields ['caller_email']}";
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		if (! isset ( $filtre ['status'] )) {
			$filtre ['status'] = " NOT IN ('closed')";
		}
		$this->onDebug ( $filtre, 1 );
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Champs existants : title, org_name, description, impact, urgency, caller_email, contacts_list, functionalcis_list, workorders_list
	 * @param array $parameters
	 * @return UserRequest
	 * @throws Exception
	 */
	public function gestion_UserRequest(array $parameters): self {
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( $parameters, 1 );
		$params = $this->prepare_params_UserRequest ( $parameters );
		$this->onDebug ( $params, 1 );
		return $this->valide_mandatory_fields ()
			->creer_oql_UserRequest ( $params )
			->creer_ci ( $params ['title'], $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopOrganization(): Organization {
		return $this->Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization(Organization &$Organization): self {
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
     * @throws Exception
     */
    public function core_stimulus(
        string     $class,
        string     $stimulus,
        array|string $fields,
        string     $output_fields = '*',
        string     $comment = ''): array|string
    {
        return $this->getObjetItopWsclientRest()
            ->core_stimulus(
                $class, $stimulus,
                $this->getId(), $fields,
                $output_fields, $comment
            );
    }

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = [
            "UserRequest :"
        ];
		return $help;
	}
}
