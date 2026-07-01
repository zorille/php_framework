<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception;
use Zorille\framework as Core;

/**
 * class CustomerCompany
 *
 * @package Lib
 * @subpackage otrs
 */
class CustomerCompany extends CustomerCompanies {

	/**
	 * Instancie un objet de type CustomerCompany. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs
	 * @return CustomerCompany|static
	 * @throws Exception
	 */
	static function &creer_CustomerCompany(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): CustomerCompany|static {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomerCompany ( $sort_en_erreur, $entete );
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
		return $this->setFormat ( 'CustomerCompany' );
	}

	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function &champ_obligatoire_CustomerCompanyCreate(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'CustomerID' => false,
				'CustomerCompanyName' => false,
				'CustomerCompanyStreet' => false,
				'CustomerCompanyCity' => false,
				'CustomerCompanyURL' => false,
				'CustomerCompanyComment' => false,
				'ValidID' => false
		) );
		return $this;
	}

	public function &champ_obligatoire_CustomerCompanyUpdate(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'CustomerID' => false,
				'CustomerCompanyName' => false
		) );
		return $this;
	}

	public function &champ_obligatoire_CustomerCompanyGet(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'CustomerID' => false
		) );
		return $this;
	}

	public function creerCustomerCompany(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_CustomerCompanyCreate ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->customer_company_create_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	public function updateCustomerCompany(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_CustomerCompanyUpdate ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->customer_company_update_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	public function retrouveCustomerCompany(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_CustomerCompanyGet ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->customer_company_get_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = [
			'CustomerCompany :'
		];
		return $help;
	}
}
