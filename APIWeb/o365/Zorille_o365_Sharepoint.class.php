<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Sharepoint
 *
 * @package Lib
 * @subpackage o365
 */
class Sharepoint extends Drive {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $site_id = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Sharepoint. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Sharepoint
	 */
	static function &creer_Sharepoint(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Sharepoint ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Sharepoint
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
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
	 * ******************************* SHAREPOINT *********************************
	 */
	public function retrouve_siteid(
			$nom_site) {
		$Liste_mgt = $this->sharepoints_chercher_site ( $this->prepare_nom_pour_url ( $nom_site ) );
		$this->onDebug ( $Liste_mgt, 2 );
		if (! $this->valide_champ_value ( $Liste_mgt )) {
			return $this;
		}
		$this->setWsReponse ( $Liste_mgt->value );
		foreach ( $Liste_mgt->value as $mgt ) {
			return $this->setSiteId ( $mgt->id );
		}
		return $this->onError ( "Aucun site avec le nom " . $nom_site . " n'a ete trouve", $Liste_mgt, 1 );
	}

	public function recherche_dossier(
			$nom_du_dossier = "") {
		$Liste_drive = $this->sharepoints_search_in_drive ( $this->prepare_nom_pour_url ( $nom_du_dossier ) );
		$this->onDebug ( $Liste_drive, 2 );
		if (! $this->valide_champ_value ( $Liste_drive )) {
			return $this;
		}
		$this->setWsReponse ( $Liste_drive->value );
		foreach ( $Liste_drive->value as $mgt ) {
			return $this->setItemId ( $mgt->id );
		}
		return $this->onError ( "Aucun dossier avec le nom " . $nom_du_dossier . " n'a ete trouve", $Liste_drive, 1 );
	}

	/**
	 * Lit l'item courant pour retrouver toutes les donnees enfants. Si un item correspond a la recherhce, l'item-id est change par celui trouve
	 * @param string $recherche
	 * @return boolean
	 */
	public function retrouve_donnee_dans_le_dossier(
			$recherche) {
		$Liste_items = $this->sharepoints_list_items_enfant ();
		$this->onDebug ( $Liste_items, 2 );
		if ($this->valide_champ_value ( $Liste_items )) {
			$this->setWsReponse ( $Liste_items->value );
			foreach ( $Liste_items->value as $mgt ) {
				if ($mgt->name == $recherche) {
					$this->setItemId ( $mgt->id );
					return true;
				}
			}
		}
		$this->onInfo ( "Aucun dossier ou fichier correspondant à " . $recherche );
		return false;
	}

	public function creer_dossier(
			$nom_du_dossier) {
		$params = array (
				"name" => $nom_du_dossier,
				"folder" => new \stdClass (),
				"@microsoft.graph.conflictBehavior" => "rename"
		);
		$this->onDebug ( json_encode ( $params ), 2 );
		$reponse = $this->sharepoints_create_folder ( $params );
		$this->onDebug ( $reponse, 2 );
		$this->setWsReponse ( $reponse );
		if (isset ( $reponse->id )) {
			return $this->setItemId ( $reponse->id );
		}
		return $this->onError ( "Aucun dossier cree avec le nom " . $nom_du_dossier, $reponse, 1 );
	}

	/**
	 * Verifie qu'un user id est remplit/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_siteid() {
		if (empty ( $this->getSiteId () )) {
			$this->onError ( "Il faut un site id renvoye par O365 pour travailler" );
			return false;
		}
		return true;
	}

	/**
	 * ******************************* O365 SHAREPOINT *********************************
	 */
	/**
	 * Liste de differents sites Sharepoint (SiteId=moncto.sharepoint.com par exemples)
	 * @param array $params
	 * @return \Zorille\o365\Sharepoint
	 */
	public function sharepoints_site_informations(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/sites/' . $this->getSiteId (), $params );
	}

	public function sharepoints_chercher_site(
			$site,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/sites?search=' . $site, $params );
	}

	// Recupere la liste des items du site
	public function sharepoints_get_site_items_list(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/sites/' . $this->getSiteId () . "/lists", $params );
	}

	// Gestion des drives (sous composant de chaque sharepoint)
	public function sharepoints_list_drives(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/sites/' . $this->getSiteId () . $this->list_drives_uri (), $params );
	}

	// File/Folder mgmt
	public function sharepoints_create_folder(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->jsonPostMethod ( '/sites/' . $this->getSiteId () . $this->drive_item_uri () . '/children', $params );
	}

	public function sharepoints_rename(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		// PATCH /sites/{site-id}/drive/items/{item-id}
		return $this->getObjetO365Wsclient ()
			->jsonPatchMethod ( '/sites/' . $this->getSiteId () . $this->drive_item_uri (), $params );
	}

	public function sharepoints_move(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		// PATCH /sites/{site-id}/drive/items/{item-id}
		/* { "parentReference": { "id": "{new-parent-folder-id}" }, "name": "new-item-name.txt" } */
		return $this->getObjetO365Wsclient ()
			->jsonPatchMethod ( '/sites/' . $this->getSiteId () . $this->drive_item_uri (), $params );
	}

	public function sharepoints_put_to_site_minus_4Mo(
			$filename,
			$content) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		// return $this->putContentMethod('/drives/'.$drive_id.'/items/root:/'.$filename.':/content',$content);
		return $this->getObjetO365Wsclient ()
			->putContentMethod ( '/sites/' . $this->getSiteId () . $this->drive_item_uri () . ':/' . $filename . ':/content', $content );
	}

	public function sharepoints_search_in_drive(
			$search,
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/sites/' . $this->getSiteId () . $this->drives_search_uri ( $search ), $params );
	}

	// List
	public function sharepoints_list_items_enfant(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_siteid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/sites/' . $this->getSiteId () . $this->drives_children_uri (), $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getSiteId() {
		return $this->site_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSiteId(
			$site_id) {
		$this->site_id = $site_id;
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
		$help [__CLASS__] ["text"] [] .= "Sharepoint :";
		return $help;
	}
}
?>
