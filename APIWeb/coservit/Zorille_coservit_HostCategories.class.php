<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;
use Zorille\framework\abstract_log;

/**
 * class HostCategories
 *
 * @package Lib
 * @subpackage coservit
 */
class HostCategories extends globalapi {
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
	 * Instancie un objet de type HostCategories. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return HostCategories
	 */
	static function &creer_HostCategories(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new HostCategories ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return HostCategories
	 */
	public function &_initialise(
			$liste_class) {
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
	 * @return true
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_param() {
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * ******************************* HostCategories URI ******************************
	 */
	public function categories_list_uri() {
		return $this->globalapi_uri () . '/host_categories';
	}

	/**
	 * ******************************* Coservit HostCategories *********************************
	 */
	public function retrouve_id_categorie(
			$categorie) {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getHostCategories () )) {
			$this->retrouve_categories ();
		}
		if (isset ( $this->getHostCategories () [mb_strtoupper ( $categorie )] )) {
			return $this->getHostCategories () [mb_strtoupper ( $categorie )];
		}
		return $this->onError ( "Le categorie " . $categorie . " n'existe pas dans la liste", "", 1 );
	}

	public function prepare_categories() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_categories = array ();
		if (isset ( $this->getDonnees ()->_embedded->items )) {
			foreach ( $this->getDonnees ()->_embedded->items as $category ) {
				$liste_categories [mb_strtoupper ( $category->name )] = $category->id;
			}
		}
		$this->onDebug ( $liste_categories, 1 );
		return $this->setHostCategories ( $liste_categories );
	}

	public function retrouve_categories(
			$params = array (
					"sort" => "+name"
			)) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->categories_list_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_categories ();
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return \Zorille\coservit\Company
	 */
	public function creerHostCategories(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getHostCategories() {
		return $this->categories;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHostCategories(
			$liste_categories) {
		$this->categories = $liste_categories;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "HostCategories :";
		return $help;
	}
}
?>
