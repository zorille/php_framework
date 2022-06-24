<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Device
 *
 * @package Lib
 * @subpackage o365
 */
class Device extends Item {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $device_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_device = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Device. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Device
	 */
	static function &creer_Device(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Device ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Device
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetO365Wsclient ( $liste_class ['wsclient'] );
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
	 * ******************************* USERS *********************************
	 */
	/**
	 * Retrouve l'ID d'un utilisateur dans le champ displayname
	 * @param string $nom
	 * @return $this|false
	 */
	public function retrouve_deviceid_par_nom(
			$nom) {
		$this->onDebug ( __METHOD__, 1 );
		$this->list_devices ();
		foreach ( $this->getListeDevice () as $personne ) {
			if ($personne->displayName == $nom) {
				$this->onDebug ( $nom . " trouve avec l'id " . $personne->id, 1 );
				return $this->setDeviceId ( $personne->id );
			}
		}
		return $this->onError ( "Device " . $nom . " introuvable dans la liste", $this->getListeDevice (), 1 );
	}

	/**
	 * Verifie qu'un user id est remplit/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_deviceid() {
		if (empty ( $this->getDeviceId () )) {
			$this->onDebug ( $this->getDeviceId (), 2 );
			$this->onError ( "Il faut un device id renvoye par O365 pour travailler" );
			return false;
		}
		return true;
	}

	/**
	 * ******************************* DRIVE URI ******************************
	 */
	public function devices_list_uri() {
		return '/devices';
	}

	public function device_id_uri() {
		if ($this->valide_deviceid () == false) {
			return $this->onError ( "Il n'y pas d'user-id selectionne" );
		}
		return '/devices/' . $this->getDeviceId ();
	}
	/**
	 * ******************************* O365 USERS *********************************
	 */
	/**
	 * Recuperer la liste des devicees O365
	 * @param array $params
	 * @return \Zorille\o365\Device|false
	 */
	public function list_devices(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$liste_devices_o365 = $this->getObjetO365Wsclient ()
			->getMethod ( $this->devices_list_uri (), $params );
		$this->onDebug ( $liste_devices_o365, 2 );
		if (isset ( $liste_devices_o365->value )) {
			return $this->setListeDevice ( $liste_devices_o365->value );
		}
		return $this->onError ( "Pas de liste devicees", $liste_devices_o365, 1 );
	}
	
	/**
	 * Recuperer les membres d'un devicee O365. Utilise le getDeviceId pour le selectionner le devicee.
	 * 
	 * @param array $params
	 * @return \Zorille\o365\Device|false
	 */
	public function list_members_device(
			$params = array ()) {
				$this->onDebug ( __METHOD__, 1 );
				$liste_devices_o365 = $this->getObjetO365Wsclient ()
				->getMethod ( $this->device_id_uri ()."/members", $params );
				$this->onDebug ( $liste_devices_o365, 2 );
				if (isset ( $liste_devices_o365->value )) {
					return $liste_devices_o365->value;
				}
				return $this->onError ( "Pas de liste de membre du devicee", $liste_devices_o365, 1 );
	}
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getDeviceId() {
		return $this->device_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDeviceId(
			&$device_id) {
		$this->device_id = $device_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeDevice() {
		return $this->liste_device;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeDevice(
			&$liste_device) {
		$this->liste_device = $liste_device;
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
		$help [__CLASS__] ["text"] [] .= "Device :";
		return $help;
	}
}
?>
