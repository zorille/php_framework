<?php
/**
 * Gestion de dolibarr.
 * @author dvargas
 */
namespace Zorille\dolibarr;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class products
 *
 * @package Lib
 * @subpackage dolibarr
 */
class products extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type products. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $dolibarr_webservice_rest Reference sur un objet wsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return products
	 */
	static function &creer_products(
			&$liste_option,
			&$dolibarr_webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
				Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new products ( $sort_en_erreur, $entete );
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
	 * @return products
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->reset_resource ();
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
		// Gestion du parent
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Remet l'url par defaut
	 * @return products
	 */
	public function &reset_resource() {
		return parent::reset_resource ()->addResource ( 'products' );
	}

	/**
	 * Resource: products Method: Get Get details of all current searches. params : sortfield,sortorder,limit,page,mode,category,sqlfilters mode : Use this param to filter list (0 for all, 1 for only product, 2 for only service) sqlfilters : Other criteria to filter answers separated by a comma. Syntax example "(t.tobuy:=:0) and (t.tosell:=:1)"
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getAllProducts(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->get ( $params );
		return $this;
	}

	/**
	 * Resource: products Method: Get Get categories for a product params : sortfield,sortorder,limit,page
	 * @codeCoverageIgnore
	 * @param array $params Request Parameters
	 * @throws Exception
	 */
	public function getProductCategories(
			$product_id,
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		$this->reset_resource ()
			->addResource ( $product_id )
			->addResource ( 'categories' )
			->get ( $params );
		return $this;
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
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "products :";
		return $help;
	}
}
?>
