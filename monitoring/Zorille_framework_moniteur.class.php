<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class moniteur<br>
 * Gere un point de monitoring.
 *
 * @package Lib
 * @subpackage Monitoring
 */
class moniteur extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var ssh_z
	 */
	private $ssh_z;
	/**
	 * var privee
	 *
	 * @access private
	 * @var boolean
	 */
	private $active_ssh = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $host;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $port;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $ci;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $moniteur;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $status;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $data = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $update = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type moniteur.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 * @param string $entete Entete des logs de l'objet
	 * @return moniteur false un objet MONITEUR ou FALSE en cas d'erreur.
	 */
	static function &creer_moniteur(&$liste_option, $sort_en_erreur = "oui", $entete = __CLASS__) {
		$objet = new moniteur ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return moniteur
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_moniteur_param ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// On met a jour l'entete des logs uniquement
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Ajoute du texte au moniteur en cours.<br>
	 * @return moniteur
	 */
	public function retrouve_moniteur_param() {
		$this->setMoniteur ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"moniteur",
				"check" 
		), "testz" ) )
			->setCI ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"moniteur",
				"ci" 
		), "CI_to_check" ) )
			->setHost ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"moniteur",
				"serveur" 
		), "serverMonitoring" ) )
			->setPort ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"moniteur",
				"port" 
		), "10050" ) )
			->setUpdate ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"moniteur",
				"update" 
		), "oui" ) )
			->setActiveSSH ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"moniteur",
				"active_ssh" 
		), false ) );
		
		if ($this->getActiveSSH ()) {
			$this->retrouve_moniteur_ssh_param ();
		}
		
		return $this;
	}

	/**
	 * Ajoute du texte au moniteur en cours.<br>
	 * @return moniteur
	 */
	public function retrouve_moniteur_ssh_param() {
		$ssh_z = ssh_z::creer_ssh_z ( $this->getListeOptions (), $this->getSortEnErreur (), $this->getEntete () );
		$ssh_z->setMachineDistante($this->getHost())->prepare_ssh_z (  );
		$this->setObjetSSH ( $ssh_z );
		
		return $this;
	}

	/**
	 * Ajoute du texte au moniteur en cours.<br>
	 *
	 * @param string $donnees Texte a ajouter dans le moniteur.
	 * @return moniteur
	 */
	public function ecrit($donnees) {
		$this->setDatas ( $this->getDatas () . $donnees );
		
		return $this;
	}

	/**
	 * Envoi le mail contenant le resultat du monitoring.
	 * @param string $mail_to Adresse mail pour l'envoi
	 * @param string $mail_from Adresse mail pour le from
	 * @return boolean|moniteur
	 */
	public function envoi_mail($mail_to, $mail_from = "nobody@no.com") {
		// On creer un objet options pour creer proprement un objet mail.
		$liste_option = options::creer_options(1, array( 'moniteur.class.php'),1,2,"","no",false);
		
		$liste_option->setOption ( array (
				"mail",
				"to" 
		), $mail_to );
		$liste_option->setOption ( array (
				"mail",
				"from" 
		), $mail_from );
		$liste_option->setOption ( "mail_using", "oui" );
		// @codeCoverageIgnoreStart
		if ($this->getUpdate () == "oui") {
			$titre = "Monitoring " . $this->getCI () . "." . $this->getMoniteur ();
			return fonctions_standards_mail::envoieMail_standard ( $liste_option, $titre, array (
					"text" => $this->getDatas () 
			), array () );
		}
		// @codeCoverageIgnoreEnd
		
		$this->onDebug ( $this->getDatas (), 1 );
		return $this;
	}

	/************************* Accesseurs ************************/
	/**
	 * @codeCoverageIgnore
	 * @return ssh_z
	 */
	public function &getObjetSSH() {
		return $this->ssh_z;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSSH(&$ssh_z) {
		$this->ssh_z = $ssh_z;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getActiveSSH() {
		return $this->active_ssh;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setActiveSSH($active_ssh) {
		$this->active_ssh = $active_ssh;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getDatas() {
		return $this->data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDatas($data) {
		$this->data = $data;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getCI() {
		return $this->ci;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCI($ci) {
		$this->ci = $ci;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getHost() {
		return $this->host;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHost($host) {
		$this->host = $host;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getPort() {
		return $this->port;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPort($port) {
		$this->port = $port;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getMoniteur() {
		return $this->moniteur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMoniteur($moniteur) {
		$this->moniteur = $moniteur;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getUpdate() {
		return $this->update;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUpdate($update) {
		$this->update = $update;
		
		return $this;
	}

	/************************* Accesseurs ************************/
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion d'un moniteur";
		$help [__CLASS__] ["text"] [] .= "\t--moniteur_ci zorille  		   CI defini dans l'outil de monitoring";
		$help [__CLASS__] ["text"] [] .= "\t--moniteur_check testz            Point de monitoring ou item";
		$help [__CLASS__] ["text"] [] .= "\t--moniteur_serveur ServeurMonitoring Nom du Serveur de monitoring";
		$help [__CLASS__] ["text"] [] .= "\t--moniteur_port 10050 Port du Serveur de monitoring";
		$help [__CLASS__] ["text"] [] .= "\t--moniteur_update oui/non         Permet de ne pas updater le monitoring";
		$help [__CLASS__] ["text"] [] .= "\t--moniteur_active_ssh false       optionnel active la mise a jour a travers une connexion ssh";
		$help [__CLASS__] ["text"] [] .= "\t--moniteur_user=user1             optionnel user de connexion ssh";
		$help [__CLASS__] ["text"] [] .= "SSH";
		$help  = array_merge ( $help , ssh_z::help () );
		
		return $help;
	}
}
?>