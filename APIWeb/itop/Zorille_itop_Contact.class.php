<?php

/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Contact
 *
 * @package Lib
 * @subpackage itop
 */
class Contact extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization
	 */
	private $Organization = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Contact. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Contact
	 */
	static function &creer_Contact(
		Core\options  &$liste_option,
		wsclient_rest &$webservice_rest,
		bool|string   $sort_en_erreur = false,
		string        $entete = __CLASS__): Contact
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Contact ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Contact
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Contact' )
			->champ_obligatoire_standard ()
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * @return Contact|Person|Team|UserLocal
	 */
	public function &champ_obligatoire_standard(): Person|UserLocal|Team|static
	{
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'name' => false,
					'org_id' => false
			) );
		}
		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_Contact(
			$name,
			$email,
			$org_name = ''): ci|Contact|bool
	{
		return $this->creer_oql ( array (
				'name' => $name,
				'email' => $email,
				'org_name' => $org_name
		) )
			->retrouve_ci ();
	}

	/**
	 * Prepare les parametres standards d'un objet
	 * @param array $parametres
	 * @return array liste des parametres au format iTop
	 */
	public function prepare_params_Contact(
		array $parametres): array
	{
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * Fait un requete OQL sur les champs Mandatory
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Contact
	 */
	public function creer_oql_Contact(
		array $fields = array ()): Contact
	{
		$filtre = array ();
		foreach ( $this->getMandatory () as $field => $inutile ) {
			switch ($field) {
				case 'org_id' :
					$filtre ['org_name'] = $fields ['org_name'];
					break;
				default :
					$filtre [$field] = $fields [$field];
			}
		}
		return parent::creer_oql ( $filtre );
	}

	/**
	 * Creer un lnkContactToFunctionalCI en fonction de la Contact
	 * @param string $FunctionalCI_id
	 * @param string $FunctionalCI_name
	 * @return array|bool lnkContactToFunctionalCI
	 * @throws Exception
	 */
	public function creer_lnkContactToFunctionalCI(
		string $FunctionalCI_id = '',
		string $FunctionalCI_name = ''): array|bool
	{
		$lnkContactToFunctionalCI = array ();
		if (empty ( $this->getId () )) {
			return $this->onError ( "Il faut un ID a cette " . $this->getFormat () );
		}
		$lnkContactToFunctionalCI ['contact_id'] = $this->getId ();
		$tableau = $this->getDonnees ();
		if (isset ( $tableau ['name'] )) {
			$lnkContactToFunctionalCI ['contact_name'] = $tableau ['name'];
		}
		if (! empty ( $FunctionalCI_id )) {
			$lnkContactToFunctionalCI ['functionalci_id'] = $FunctionalCI_id;
		}
		if (! empty ( $FunctionalCI_name )) {
			$lnkContactToFunctionalCI ['functionalci_name'] = $FunctionalCI_name;
		}
		return $lnkContactToFunctionalCI;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization|null
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
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Contact :";
		return $help;
	}
}
