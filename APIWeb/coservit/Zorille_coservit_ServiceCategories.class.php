<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Exception;
use Zorille\framework as Core;

/**
 * class ServiceCategories
 *
 * @package Lib
 * @subpackage coservit
 */
class ServiceCategories extends globalapi {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $categories = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type ServiceCategories. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ServiceCategories
	 * @throws Exception
	 */
	static function &creer_ServiceCategories(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): ServiceCategories
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ServiceCategories ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ServiceCategories
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		$this->retrouve_param ();
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
		string      $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_param(): static
	{
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * ******************************* ServiceCategories URI ******************************
	 */
	public function categories_list_uri(): string {
		return $this->globalapi_uri () . '/service_categories';
	}

	/**
	 * ******************************* Coservit ServiceCategories *********************************
	 */

	/**
	 * @throws Exception
	 */
	public function retrouve_id_category(
		$category) {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getServiceCategories () )) {
			$this->retrouve_categories ();
		}
		if (isset ( $this->getServiceCategories () [mb_strtoupper ( $category )] )) {
			return $this->getServiceCategories () [mb_strtoupper ( $category )];
		}
		return $this->onError ( "Le categorie " . $category . " n'existe pas dans la liste" );
	}

	public function prepare_categories(): static {
		$this->onDebug ( __METHOD__, 1 );
		$liste_categories = array ();
		if (isset ( $this->getDonnees ()->_embedded->items )) {
			foreach ( $this->getDonnees ()->_embedded->items as $category ) {
				$liste_categories [mb_strtoupper ( $category->name )] = $category->id;
			}
		}
		$this->onDebug ( $liste_categories, 1 );
		return $this->setServiceCategories ( $liste_categories );
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_categories(
			$params = array (
					"sort" => "+name"
			)): ServiceCategories {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->categories_list_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_categories ();
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return ServiceCategories
	 */
	public function creerServiceCategories(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return array|string
	 */
	public function getServiceCategories(): array|string {
		return $this->categories;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setServiceCategories(
			$liste_categories): static {
		$this->categories = $liste_categories;
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
		$help [__CLASS__] ["text"] = [
			'ServiceCategories :'
		];
		return $help;
	}
}
