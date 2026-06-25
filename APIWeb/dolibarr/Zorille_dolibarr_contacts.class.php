<?php

/**
 * Gestion de dolibarr.
 * @author dvargas
 */
namespace Zorille\dolibarr;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class contacts
 *
 * @package Lib
 * @subpackage dolibarr
 */
class contacts extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type contacts. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $dolibarr_webservice_rest Reference sur un objet wsclient
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return contacts
	 * @throws Exception
	 */
	static function &creer_contacts(
		Core\options &$liste_option,
		wsclient     &$dolibarr_webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): contacts
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new contacts ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $dolibarr_webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return contacts
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->reset_resource ();
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
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return contacts
	 */
	public function &reset_resource(): static {
		return parent::reset_resource ()->addResource ( 'contacts' );
	}

	/**
	 * Resource: contacts Method: Get Get details of all current searches. params : sortfield,sortorder,limit,page,thirdparty_ids,sqlfilters
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getAllContacts(
		array $params = array()): static {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->setMessage404Error ( 'Contacts not found' )
			->get ( $params );
		return $this;
	}

	/**
	 * Resource: contacts Method: Post Start a new search and return the search ID (<sid>)
	 *
	 * @codeCoverageIgnore
	 * @param $liste_donnees
	 * @return ci|contacts
	 * @throws Exception
	 */
	public function insertSingleContact(
			$liste_donnees): ci|contacts {
		$this->onDebug ( __METHOD__, 1 );
		return $this->insertSingleEntry ( $liste_donnees );
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
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "contacts :";
		return $help;
	}
}
