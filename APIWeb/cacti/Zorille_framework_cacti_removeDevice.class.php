<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class cacti_removeDevice<br>
 *
 * Prepare une ligne de commande de suppression.
 *
 * @package Lib
 * @subpackage Cacti
 */
class cacti_removeDevice extends cacti_addDevice {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_removeDevice.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return cacti_removeDevice
	 */
	static function &creer_cacti_removeDevice(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new cacti_removeDevice ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return cacti_removeDevice
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @return true
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de cacti_globals
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Ajoute un device
	 *
	 * @return Integer/false Renvoi l'id du device, false en cas d'erreur.
	 * @throws Exception
	 */
	public function executeCacti_removeDevice() {
		//L'IP est obligatoire
		if ($this->getIp () == "") {
			return $this->onError ( "Il faut une Ip.", "", 5004 );
		}
		
		//Si le CI n'existe pas, on stoppe la suppression
		//La validation ajoute le numero du CI dans le HOSTId
		if (! $this->valide_host_ip ()) {
			return $this->onError ( "Ce CI n'existe pas en base.", "", 5000 );
		}
		
		$this->onDebug ( "On supprime le CI : " . $this->getIp () . "(" . $this->getHostId () . ")", 1 );
		
		api_device_remove ( $this->getHostId () );
		
		if (is_error_message ()) {
			return $this->onError ( "Probleme de suppression du CI", "", 5021 );
		}
		
		$this->onInfo ( "Success" );
		return true;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 *
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Creer et execute le programme cacti_removeDevice";
		$help [__CLASS__] ["text"] [] .= "NECESSITE au moins un fichier de conf machines/cacti.xml";
		$help [__CLASS__] ["text"] [] .= "\t--cacti_env mut/tlt/dev/perso permet de recuperer l'env dans la conf cacti";
		
		return $help;
	}
}
?>
