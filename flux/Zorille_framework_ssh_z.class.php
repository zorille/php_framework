<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class ssh_z<br> Gere une connexion SSH.
 * @package Lib
 * @subpackage Flux
 */
class ssh_z extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $machine_distante = "";
	/**
	 * var privee
	 * @access private
	 * @var resource
	 */
	private $connexion = false;
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	private $autentification = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $host = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $username;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $passwd;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $pubkey;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $privkey;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $passphrase;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $type_ssh_key;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $port = 22;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $cmd_ssh;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $nb_retry = 3;
	/**
	 * var privee
	 * @access private
	 * @var flux_datas
	 */
	private $flux_datas = null;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $cli_datas = false;
	/**
	 * var privee
	 * @access private
	 * @var ssh2_commandes
	 */
	private $objet_ssh2_commandes = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type ssh_z. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return ssh_z
	 */
	static function &creer_ssh_z(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new ssh_z ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ssh_z
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->setObjetFluxDatas ( flux_datas::creer_flux_datas ( $liste_class ['options'] ) ) 
			->setObjetSsh2Commandes ( ssh2_commandes::creer_ssh2_commandes ( $liste_class ['options'] ) ) 
			->retrouve_ssh_z_param ();
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur. @codeCoverageIgnore
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Rempli les variables de ssh_z a partir de liste_option (ligne de commande)
	 * @return ssh_z
	 */
	public function retrouve_ssh_z_param() {
		#Valeur par defaut si on ne rtavail pas avec la ligne de commande : NOUSERNAMEZ
		$this ->setUsername ( $this ->getListeOptions () 
			->renvoi_variables_standard ( array (
				"ssh",
				"username" ), "NOUSERNAMEZ" ) );
		
		if ($this ->getUsername () == "NOUSERNAMEZ") {
			#Variables par fichier XML
			$this ->getObjetFluxDatas () 
				->retrouve_flux_param ( "ssh_machines" );
			$this ->setCliDatas ( false );
		} else {
			#variables par ligne de commande
			$this ->setPasswd ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"ssh",
					"password" ), "" ) ) 
				->setPubkey ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"ssh",
					"pubkey" ), "" ) ) 
				->setPrivkey ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"ssh",
					"privkey" ), "" ) ) 
				->setPassphrase ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"ssh",
					"passphrase" ), "" ) ) 
				->setTypeSshKey ( array (
					"hostkey" => $this ->getListeOptions () 
						->renvoi_variables_standard ( array (
							"ssh",
							"type_ssh_key" ), "ssh-dsa" ) ) ) 
				->setCmdSsh ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"ssh",
					"commande",
					"ssh" ), "/usr/bin/ssh" ) );
			$this ->setCliDatas ( true );
		}
		
		return $this;
	}

	/**
	 * Rempli les variables de ssh_z a partir d'un tableau
	 * @return ssh_z
	 */
	public function prepare_ssh_z() {
		if ($this ->getCliDatas ()) {
			return $this ->onWarning ( "Parametre en ligne commande qui remplace les fichier XML" );
		}
		$liste_params = $this ->getObjetFluxDatas () 
			->valide_presence_flux_data ( $this ->getMachineDistante () );
		if ($liste_params == false) {
			return $this ->onError ( $this ->getMachineDistante () . " est introuvable" );
		}
		$this ->onDebug ( $liste_params, 1 );
		$this ->setUsername ( $liste_params ["username"] );
		if (isset ( $liste_params ["host"] )) {
			$this ->setHost ( $liste_params ["host"] );
		} else {
			$this ->setHost ( $this ->getMachineDistante () );
		}
		if (isset ( $liste_params ["password"] )) {
			$this ->setPasswd ( $liste_params ["password"] );
		}
		if (isset ( $liste_params ["pubkey"] )) {
			$this ->setPubkey ( $liste_params ["pubkey"] );
		}
		if (isset ( $liste_params ["privkey"] )) {
			$this ->setPrivkey ( $liste_params ["privkey"] );
		}
		if (isset ( $liste_params ["passphrase"] )) {
			$this ->setPassphrase ( $liste_params ["passphrase"] );
		}
		if (isset ( $liste_params ["type_ssh_key"] ) && $liste_params ["type_ssh_key"] != "") {
			$this ->setTypeSshKey ( array (
					"hostkey" => $liste_params ["type_ssh_key"] ) );
		} else {
			$this ->setTypeSshKey ( array (
					"hostkey" => "ssh-dsa" ) );
		}
		if (isset ( $liste_params ["commande_ssh"] )) {
			$this ->setCmdSsh ( $liste_params ["commande_ssh"] );
		}
		
		return $this;
	}

	/**
	 * Identifie l'utilisateur sur la connexion active. return Bool True si OK, False sinon.
	 * @throws Exception
	 */
	public function autentification() {
		$retour = false;
		$essai = 0;
		
		$this ->onDebug ( "SSH autentification", 1 );
		// Si la connexion n'existe pas, on quitte
		if (! $this ->getSshConnexion ()) {
			return false;
		}
		// On est deja authentifie
		if ($this ->getAutentification ()) {
			return true;
		}
		
		// En cas de cle publique, on active via la cle.
		if ($this ->getPubkey () != "") {
			while ( $retour === false && $essai < $this ->getNbRetry () ) {
				$retour = $this ->getObjetSsh2Commandes () 
					->ssh2_auth_pubkey_file ( $this ->getSshConnexion (), $this ->getUsername (), $this ->getPubkey (), $this ->getPrivkey (), $this ->getPassphrase () );
				$essai ++;
			}
		} else {
			while ( $retour === false && $essai < $this ->getNbRetry () ) {
				$retour = $this ->getObjetSsh2Commandes () 
					->ssh2_auth_password ( $this ->getSshConnexion (), $this ->getUsername (), $this ->getPasswd () );
				$essai ++;
			}
		}
		
		if ($retour === false) {
			return $this ->onError ( "Erreur durant l'autentification vers " . $this ->getMachineDistante () );
		}
		
		$this ->setAutentification ( true );
		
		return true;
	}

	/**
	 * Ouvre la connexion SSH sur une machine distante.
	 *
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function ssh_connect() {
		$this ->onDebug ( "SSH connect", 1 );
		// Si les parametres sont par fichier de conf
		if ($this ->getCliDatas () == false) {
			$this ->prepare_ssh_z ();
		}
		$essai = 0;
		$sleep = 0;
		
		$connexion = false;
		while ( $connexion === false && $essai < $this ->getNbRetry () ) {
			sleep ( $sleep );
			$connexion = $this ->getObjetSsh2Commandes () 
				->ssh2_connect ( $this ->getHost (), $this ->getPort () );
			$essai ++;
			$sleep ++;
		}
		$this ->setSshConnexion ( $connexion );
		
		if ($this ->getSshConnexion () === false) {
			return $this ->onError ( "Erreur durant la connexion vers " . $this ->getMachineDistante () . " Host : " . $this ->getHost () );
		}
		
		$this ->autentification ();
		
		return $this ->getAutentification ();
	}

	/**
	 * @codeCoverageIgnore Ouvre la connexion SSH sur une machine distante.
	 *
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function connect() {
		$this ->ssh_connect ();
	}

	/**
	 * Valide le retour d'une commande ssh
	 * @param array $CODE_RETOUR tableau contenant le champ err
	 * @param boolean $affiche_erreur
	 * @param boolean $force_output
	 * @return ssh_z
	 * @throws Exception
	 */
	public function valide_stderr(&$CODE_RETOUR, $commande, $affiche_erreur = true, $force_output = false) {
		if ($CODE_RETOUR ["err"] !== "") {
			// Dans le cas d'un ajout automatique de cle dsa/rsa, ce n'est pas une erreur
			if (preg_match ( '/^Warning: Permanently added .* \(DSA\) to the list of known hosts.[\n|\r]$/', $CODE_RETOUR ["err"] ) === 1) {
				$CODE_RETOUR ["err"] = false;
			} else {
				if ($affiche_erreur) {
					return $this ->onError ( "Erreur dans la commande ssh : " . $commande, $CODE_RETOUR ["err"] );
				}
				
				if ($force_output) {
					$CODE_RETOUR ["output"] = false;
				}
			}
		} else {
			$CODE_RETOUR ["err"] = false;
		}
		
		return $this;
	}

	/**
	 * Envoi une commande SSH sur la connexion active.
	 * @param string $commande Commande sh a appliquer.
	 * @param bool $affiche_erreur Doit-on afficher l'erreur si elle se produit ?
	 * @param bool $force_output Force le renvoi de l'output malgres un retour en erreur.
	 * @return array Renvoi le retour de la commande dans la case "output", FALSE sinon; idem pour l'erreur dans "err".
	 */
	public function ssh_commande($commande, $affiche_erreur = true, $force_output = false) {
		$CODE_RETOUR = array ();
		
		$streams = $this ->getObjetSsh2Commandes () 
			->ssh2_exec ( $this ->getSshConnexion (), $commande );
		
		// The command may not finish properly if the stream is not read to end
		$CODE_RETOUR ["output"] = stream_get_contents ( $streams ['stdio'] );
		$CODE_RETOUR ["err"] = stream_get_contents ( $streams ['stderr'] );
		
		$this ->valide_stderr ( $CODE_RETOUR, $commande, $affiche_erreur, $force_output );
		
		fclose ( $streams ['stdio'] );
		fclose ( $streams ['stderr'] );
		fclose ( $streams ['stdout'] );
		
		return $CODE_RETOUR;
	}

	/**
	 * Envoi une commande shell sur la connexion active.
	 * @param string $commande Commande sh a appliquer SANS retour chariot.
	 * @param bool $affiche_erreur Doit-on afficher l'erreur si elle se produit ?
	 * @return array Renvoi le retour de la commande dans la case "output", FALSE sinon; idem pour l'erreur dans "err".
	 */
	public function ssh_shell_commande($commande, $affiche_erreur = true, $type_shell = "xterm") {
		$CODE_RETOUR = array ();
		
		$streams = $this ->getObjetSsh2Commandes () 
			->ssh2_shell ( $this ->getSshConnexion (), $type_shell );
		
		// The command may not finish properly if the stream is not read to end
		fwrite ( $streams ['stdout'], trim ( $commande ) . PHP_EOL . "exit 0" . PHP_EOL );
		$CODE_RETOUR ["err"] = stream_get_contents ( $streams ['stderr'] );
		
		$this ->valide_stderr ( $CODE_RETOUR, $commande, $affiche_erreur, true );
		//Si il n'y a pas d'erreur
		if ($CODE_RETOUR ["err"] === false) {
			//On traite la sortie
			$flag=true;
			$CODE_RETOUR ["output"] = "";
			while ( $flag ) {
				$ligne = fgets ( $streams ['stdio'] );
				if (trim ( $ligne ) != "logout")
					$CODE_RETOUR ["output"] .= $ligne;
					
					// sortie
				if (feof ( $streams ['stdio'] ))
					$flag = false;
			}
		}
		
		fclose ( $streams ['stdio'] );
		fclose ( $streams ['stderr'] );
		fclose ( $streams ['stdout'] );
		
		return $CODE_RETOUR;
	}

	/**
	 * Verifie la presence fichier.
	 * @param string $dossier_distant dossier distant a lire.
	 * @param string $nom_fichier Nom du fichier a trouver.
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function verifie_presence_fichier($dossier_distant, $nom_fichier) {
		$output = $this ->ssh_commande ( "ls " . $dossier_distant . "/" . $nom_fichier, false,true );
		if ($output ["output"] === false)
			return false;
		
		return true;
	}

	/**
	 * Creer un repertoire sur le ssh. Attention cette fonction fait un mkdir -p
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function creer_dossier($dossier, $mkdir = "mkdir") {
		$CMD = "if [ ! -d \"" . $dossier . "\" ]; then " . $mkdir . " -p " . $dossier . " ; fi";
		$output = $this ->ssh_commande ( $CMD, false,true );
		if ($output ["output"] === false)
			return false;
		
		return true;
	}

	/**
	 * Fermeture de la connexion SSH.
	 */
	public function ssh_close() {
		unset ( $this->connexion );
		$connexion = false;
		$this ->setSshConnexion ( $connexion );
		$this ->setAutentification ( false );
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct() {
		$this ->ssh_close ();
	}

	/**
	 * **************** Accesseurs *************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return resource
	 */
	public function &getSshConnexion() {
		return $this->connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSshConnexion(&$connexion) {
		$this->connexion = $connexion;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNbRetry() {
		return $this->nb_retry;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNbRetry($nbretry) {
		$this->nb_retry = $nbretry;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return flux_datas
	 */
	public function &getObjetFluxDatas() {
		return $this->flux_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetFluxDatas(&$flux_datas) {
		$this->flux_datas = $flux_datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMachineDistante() {
		return $this->machine_distante;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMachineDistante($machine_distante) {
		$this->machine_distante = $machine_distante;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHost() {
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
	public function getAutentification() {
		return $this->autentification;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAutentification($autentification) {
		$this->autentification = $autentification;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setUsername($username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPasswd() {
		return $this->passwd;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPasswd($passwd) {
		$this->passwd = $passwd;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPubkey() {
		return $this->pubkey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPubkey($pubkey) {
		$this->pubkey = $pubkey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPrivkey() {
		return $this->privkey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPrivkey($privkey) {
		$this->privkey = $privkey;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassphrase() {
		return $this->passphrase;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassphrase($passphrase) {
		$this->passphrase = $passphrase;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTypeSshKey() {
		return $this->type_ssh_key;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTypeSshKey($type_ssh_key) {
		$this->type_ssh_key = $type_ssh_key;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPort() {
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
	public function getCmdSsh() {
		return $this->cmd_ssh;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCmdSsh($cmd_ssh) {
		$this->cmd_ssh = $cmd_ssh;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCliDatas() {
		return $this->cli_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCliDatas($cli_datas) {
		$this->cli_datas = $cli_datas;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetSsh2Commandes() {
		return $this->objet_ssh2_commandes;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetSsh2Commandes(&$objet_ssh2_commandes) {
		$this->objet_ssh2_commandes = $objet_ssh2_commandes;
		return $this;
	}

	/**
	 * **************** Accesseurs *************************
	 */
	
	/**
	 * **************** Statics *************************
	 */
	
	/**
	 *
	 * @static @codeCoverageIgnore Ouvre un tunnel temporaire durant le temps défini dans timeout.
	 * @param string $passerelle Nom du host servant de passerelle
	 * @param int $port_passerelle Port utilisé sur la passerelle
	 * @param string $host_distant Nom du host a atteindre
	 * @param int $port_distant Port du host a atteindre
	 * @param string $ssh_option
	 * @param int $timeout Temps de vie du tunnel
	 */
	static function creer_tunnel_temporaire($passerelle, $port_passerelle, $host_distant, $port_distant, $timeout = 60, $ssh_option = "", $ssh = "/usr/bin/ssh") {
		$cmd=$ssh . " " . $ssh_option . " -f -L " . $port_passerelle . ":" . $host_distant . ":" . $port_distant . " " . $passerelle . " sleep " . $timeout . " >> /tmp/logfile 2>&1";
		$retour = fonctions_standards::applique_commande_systeme ( $cmd );
		
		if ($retour [0] != 0) {
			return abstract_log::onError_standard ( "Connexion ssh en erreur." );
		}
		
		return true;
	}

	/**
	 * **************** Statics *************************
	 */
	
	/**
	 *
	 * @static @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des connexions SSH";
		$help [__CLASS__] ["text"] [] .= "<ssh using=\"oui\" sort_en_erreur=\"oui\">";
		$help [__CLASS__] ["text"] [] .= " <username>echo</ssh_user>";
		$help [__CLASS__] ["text"] [] .= " <password>echo</ssh_passwd>";
		$help [__CLASS__] ["text"] [] .= " <pubkey>xx</pubkey>";
		$help [__CLASS__] ["text"] [] .= " <privkey></privkey>";
		$help [__CLASS__] ["text"] [] .= " <passphrase></passphrase>";
		$help [__CLASS__] ["text"] [] .= " <type_ssh_key>rsa/dsa</type_ssh_key>";
		$help [__CLASS__] ["text"] [] .= " <commande_ssh>/usr/bin/ssh</commande_ssh>";
		$help [__CLASS__] ["text"] [] .= "</ssh>";
		
		return $help;
	}
}
?>