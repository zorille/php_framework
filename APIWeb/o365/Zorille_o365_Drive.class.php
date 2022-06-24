<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Drive
 *
 * @package Lib
 * @subpackage o365
 */
class Drive extends Item {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $drive_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $user = "";

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Drive. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Drive
	 */
	static function &creer_Drive(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Drive ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Drive
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
	 * ******************************* DRIVE *********************************
	 */
	public function retrouve_driveid(
			$nom_du_drive = "Documents",
			$type = "documentLibrary") {
		$Liste_drive = $this->list_drives ();
		$this->onDebug ( $Liste_drive, 2 );
		if (! $this->valide_champ_value ( $Liste_drive )) {
			return $this;
		}
		$this->setWsReponse ( $Liste_drive->value );
		foreach ( $Liste_drive->value as $mgt ) {
			if ($mgt->name == $nom_du_drive && $mgt->driveType == $type) {
				return $this->setDriveId ( $mgt->id );
			}
		}
		return $this->onError ( "Aucun drive avec le nom " . $nom_du_drive . " et le type " . $type . " ont ete trouve", $Liste_drive, 1 );
	}

	/**
	 * ******************************* DRIVE URI ******************************
	 */
	public function drive_onedrive() {
		if(empty($this->getUser())){
			return $this->onError("Un OneDrive doit avoir un utilisateur");
		}
		return $this->getUser () . '/drive/root';
	}
	
	public function list_drives_uri() {
		return $this->getUser () . '/drives';
	}

	public function drive_item_uri() {
		if ($this->valide_itemid () == false) {
			return $this->onError ( "Il n'y pas d'item-id selectionne" );
		}
		return $this->getUser () . '/drive/items/' . $this->getItemId ();
	}

	public function drives_children_uri() {
		return $this->drive_item_uri () . '/children';
	}
	
	public function drives_permissions_uri() {
		return $this->drive_item_uri () . '/permissions';
	}

	public function drives_search_uri(
			$search) {
		return $this->getUser () . '/drive/root/search(q=\'' . $search . "')";
	}

	/**
	 * ******************************* O365 DRIVE *********************************
	 */
	// Gestion des drives (sous composant de chaque drive)
	public function list_drives(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		return $this->getObjetO365Wsclient ()
			->getMethod ( $this->list_drives_uri (), $params );
	}
	
	// Recupere la liste des items du site
	public function get_drive_permissions(
			$params = array ()) {
				$this->onDebug ( __METHOD__, 1 );
				return $this->getObjetO365Wsclient ()
				->getMethod ( $this->drives_permissions_uri (), $params );
	}
	
	// Recupere la liste des items du site
	public function get_details_permission($permissionId,
			$params = array ()) {
				$this->onDebug ( __METHOD__, 1 );
				return $this->getObjetO365Wsclient ()
				->getMethod ( $this->drives_permissions_uri ()."/".$permissionId, $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getDriveId() {
		return $this->drive_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDriveId(
			$drive_id) {
		$this->drive_id = $drive_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUser(
			$user) {
		$this->user = $user;
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
		$help [__CLASS__] ["text"] [] .= "Drive :";
		return $help;
	}
}
?>
