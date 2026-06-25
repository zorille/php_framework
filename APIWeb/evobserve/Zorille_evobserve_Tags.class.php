<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

use Exception;
use Zorille\framework as Core;

/**
 * class Tags
 *
 * @package Lib
 * @subpackage evobserve
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
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Tags
	 * @throws Exception
	 */
	static function &creer_Tags(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): Tags {
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
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		$this->retrouve_Tags ();
		return $this;
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
	 * ******************************* Tags URI ******************************
	 */
	public function Tags_uri(): string {
		return $this->globalapi_uri () . '/tags';
	}

	/**
	 * ******************************* Evobserve Tags *********************************
	 */

	/**
	 * @throws Exception
	 */
	public function retrouve_id_tag(
			$Tags): bool|int
	{
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getTags () )) {
			$this->retrouve_Tags ();
		}
		if (isset ( $this->getTags () [strtoupper ( $Tags )] )) {
			return $this->getTags () [strtoupper ( $Tags )];
		}
		return $this->onError ( "Le tag " . $Tags . " n'existe pas dans la liste" );
	}

	public function prepare_Tags(): static {
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

	/**
	 * @throws Exception
	 */
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
			)): Tags {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetEvobserveWsclient ()
			->getMethod ( $this->Tags_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_Tags ();
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return Tags
	 */
	public function creerTags(
		array $parametres): Tags {
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return array|int[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTags(
			$liste_tags): static {
		$this->tags = $liste_tags;
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
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Tags :";
		return $help;
	}
}
