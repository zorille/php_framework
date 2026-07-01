<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception;
use Zorille\framework as Core;

/**
 * class CustomerUser
 *
 * @package Lib
 * @subpackage otrs
 */
class CustomerUser extends CustomerUsers {

	/**
	 * Instancie un objet de type CustomerUser. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs
	 * @return CustomerUser|static
	 * @throws Exception
	 */
	static function &creer_CustomerUser(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): CustomerUser|static {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new CustomerUser ( $sort_en_erreur, $entete );
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
		return $this->setFormat ( 'CustomerUser' );
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

	public function &champ_obligatoire_CustomerUserCreate(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'CustomerUserLogin' => false,
				'UserEmail' => false,
				'UserFirstname' => false,
				'UserLastname' => false,
				'UserCustomerID' => false,
				'ValidID' => false
		) );
		return $this;
	}

	public function &champ_obligatoire_CustomerUserUpdate(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'ID' => false,
				'UserEmail' => false
		) );
		return $this;
	}

	public function &champ_obligatoire_CustomerUserGet(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'CustomerUser' => false
		) );
		return $this;
	}

	public function creerCustomerUser(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_CustomerUserCreate ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->customer_user_create_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	public function updateCustomerUser(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_CustomerUserUpdate ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->customer_user_update_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	public function retrouveCustomerUser(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_CustomerUserGet ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->customer_user_get_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = [
			'CustomerUser :'
		];
		return $help;
	}
}
