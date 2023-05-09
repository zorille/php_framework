<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;
use Zorille\framework\abstract_log;

/**
 * class Tags
 *
 * @package Lib
 * @subpackage coservit
 */
class Tags extends globalapi {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $tags = array (
			'EXEMPLE' => 1
	);

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Tags. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Tags
	 */
	static function &creer_Tags(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Tags ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Tags
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		$this->retrouve_Tags ();
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

	/**
	 * ******************************* Tags URI ******************************
	 */
	public function Tags_uri() {
		return $this->globalapi_uri () . '/tags';
	}

	/**
	 * ******************************* Coservit Tags *********************************
	 */
	public function retrouve_id_tag(
			$Tags) {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getTags () )) {
			$this->retrouve_Tags ();
		}
		if (isset ( $this->getTags () [strtoupper ( $Tags )] )) {
			return $this->getTags () [strtoupper ( $Tags )];
		}
		return $this->onError ( "Le tag " . $Tags . " n'existe pas dans la liste", "", 1 );
	}

	public function prepare_Tags() {
		$this->onDebug ( __METHOD__, 1 );
		$liste_Tags = array ();
		if (isset ( $this->getDonnees ()->_embedded->items )) {
			foreach ( $this->getDonnees ()->_embedded->items as $Tag ) {
				$liste_Tags [mb_strtoupper ( $Tag->name )] = $Tag->id;
			}
		}
		$this->onDebug ( $liste_Tags, 1 );
		return $this->setTags ( $liste_Tags );
	}

	public function retrouve_Tags(
			$params = array (
					"company" => array (
							1,
							2
					),
					"inheritance" => true,
					"limit" => 1000,
					"sort" => array (
							"+name"
					)
			)) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetCoservitWsclient ()
			->getMethod ( $this->Tags_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_Tags ();
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return \Zorille\coservit\Company
	 */
	public function creerTags(
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
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTags(
			$liste_tags) {
		$this->tags = $liste_tags;
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
		$help [__CLASS__] ["text"] [] .= "Tags :";
		return $help;
	}
}
?>
