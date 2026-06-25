<?php

/**
 * Gestion de veeamman.
 * @author dvargas
 */
namespace Zorille\veeamman;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class query
 *
 * @package Lib
 * @subpackage veeamman
 */
class query extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $liste_query = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_svc = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var \SimpleXMLElement
	 */
	private $current_page_info = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var boolean
	 */
	private $last_page = false;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instanquerye un objet de type query. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return query
	 */
	static function &creer_veeamman_query(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): query
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new query ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return query
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setObjetVeeamWsclientRest ( $liste_class ["wsclient"] );
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
		// Gestion de query
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function reset_query(): void
	{
		$this->setDernierePage ( false )
			->setPageActuelle ( null );
	}

	public function prepare_liste_query(
			$resultat_querysvc): static
	{
		$liste_svc = array ();
		foreach ( $resultat_querysvc->Links->Link as $svc ) {
			parse_str ( parse_url ( $svc->attributes ()->Href, PHP_URL_QUERY ), $params );
			if (isset ( $params ['type'] )) {
				$liste_svc [$params ['type']] = $params;
			}
		}
		$this->onDebug ( $liste_svc, 1 );
		return $this->setListeServices ( $liste_svc );
	}

	/**
	 * Permet de trouver la liste des query dans veeamman et enregistre les donnees des query dans l'objet
	 * @return query|bool
	 * @throws Exception
	 */
	public function retrouve_querysvc(): query
	{
		$this->onDebug ( __METHOD__, 1 );
		$query = $this->getObjetVeeamWsclientRest ()
			->querySvc ();
		if (! isset ( $query->Links )) {
			// Le query n'existe pas donc on emet une exception
			return $this->onError ( "Probleme avec la recuperation des querysvc." );
		}
		$this->onDebug ( $query, 2 );
		return $this->setListeQuery ( $query )
			->prepare_liste_query ( $query );
	}

	/**
	 * Permet de recupere 1 page de résultat
	 * @return boolean true si c'est recupere, false si on a atteint la derniere page
	 * @throws Exception
	 */
	public function recupere_query_par_page(
			$params = array ()): bool
	{
		return $this->page_suivante ( $params );
	}

	/**
	 * Permet de trouver les donnees d'une query en fonction des parametres fournit
	 * @return query
	 * @throws Exception
	 */
	public function recupere_resultat_query(
			$params = array ()): query
	{
		$this->onDebug ( __METHOD__, 1 );
		$query = $this->getObjetVeeamWsclientRest ()
			->query ( $params );
		$this->onDebug ( $query, 2 );
		return $this->setDonnees ( $query )
			->recupere_page_info ()
			->valide_derniere_page ();
	}

	/**
	 * Recupere la page actuelle a partir de la requete Si il n'y a pas de PagingInfo, la valeur est NULL
	 * @return query
	 * @throws Exception
	 */
	public function recupere_page_info(): static
	{
		$donnees = $this->getDonnees ();
		if (isset ( $donnees->PagingInfo )) {
			return $this->setPageActuelle ( $donnees->PagingInfo );
		}
		return $this->setPageActuelle ( null );
	}

	/**
	 * Valide que c'est la dernière page a partir de la Page Actuelle. Si la Page Actuelle est a NULL, il n'y a pas de page donc c'est la dernière par defaut
	 * @return query
	 * @throws Exception
	 */
	public function valide_derniere_page(): static
	{
		$donnees = $this->getPageActuelle ();
		if ($donnees == null || ( int ) $donnees->attributes () ["PageNum"] == ( int ) $donnees->attributes () ["PagesCount"]) {
			return $this->setDernierePage ( true );
		}
		return $this->setDernierePage ( false );
	}

	/**
	 * Permet de recupere la page suivante d'une query. Recupere la premiere page si aucune page actuelle n'est definit
	 * @return boolean true si c'est recupere, false si on a atteint la derniere page
	 * @throws Exception
	 */
	public function page_suivante(
			$params = array ()): bool
	{
		$this->onDebug ( __METHOD__, 1 );
		if (! $this->getDernierePage ()) {
			$page = $this->getPageActuelle ();
			if (! is_null ( $page )) {
				$params ["page"] = ( int ) $page->attributes () ['PageNum'] + 1;
			}
			$this->recupere_resultat_query ( $params );
			return true;
		}
		return false;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeQuery(): ?\SimpleXMLElement
	{
		return $this->liste_query;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeQuery(
			$liste_query): static
	{
		$this->liste_query = $liste_query;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeServices(): array
	{
		return $this->liste_svc;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeServices(
			$liste_svc): static
	{
		$this->liste_svc = $liste_svc;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPageActuelle(): ?\SimpleXMLElement
	{
		return $this->current_page_info;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPageActuelle(
			$current_page_info): static
	{
		$this->current_page_info = $current_page_info;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getDernierePage(): bool
	{
		return $this->last_page;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDernierePage(
			$last_page): static
	{
		$this->last_page = $last_page;
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
		$help [__CLASS__] ["text"] [] .= "query :";
		return $help;
	}
}
