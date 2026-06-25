<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class ssh2_commandes 
 * Gere les commandes ssh2 pour les tests unitaires.
 * @codeCoverageIgnore
 * @package Lib
 * @subpackage Flux
 */
class ssh2_commandes extends abstract_log {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type ssh2_commandes. 
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return ssh2_commandes
	 */
	static function &creer_ssh2_commandes(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): ssh2_commandes
	{
		$objet = new ssh2_commandes ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ssh2_commandes
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur. 
	 * @codeCoverageIgnore
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Cree une connexion ssh (voir help du ssh2_connect du plugin ssh2)
	 * @codeCoverageIgnore
	 * @param string $host Nom du host
	 * @param string $port Port du host
	 * @param array $methods Optionnel methode de cryptage
	 * @param array $callback Optionnel Callback
	 * @return resource a resource on success, or false on error.
	 * @throws Exception
	 */
	public function ssh2_connect(string $host, string $port, array $methods = array(), array $callback = array()) {
		$retour= ssh2_connect ( $host, $port, $methods, $callback );
		if($retour==false){
			throw new Exception("ssh2_connect Error");
		}
		return $retour;
	}

	/**
	 * Instancie une connexion avec les cle public/privee (voir help du ssh2_connect du plugin ssh2)
	 * @codeCoverageIgnore
	 * @param resource $session An SSH connection link identifier, obtained from a call to ssh2_connect.
	 * @param string $username Username de connexion
	 * @param string $pubkey Pubkey de connexion
	 * @param string $privkey Privkey de connexion
	 * @param string $passphrase Passphrase de connexion
	 * @return boolean Returns true on success or false on failure.
	 * @throws Exception
	 */
	public function ssh2_auth_pubkey_file(&$session, string $username, string $pubkey, string $privkey, string $passphrase): bool
	{
		$retour= ssh2_auth_pubkey_file ( $session, $username, $pubkey, $privkey, $passphrase );
		if($retour==false){
			throw new Exception("ssh2_auth_pubkey_file Error");
		}
		return true;
	}

	/**
	 * Instancie une connexion avec un user/mot de passe (voir help du ssh2_connect du plugin ssh2)
	 * @codeCoverageIgnore
	 * @param resource $session An SSH connection link identifier, obtained from a call to ssh2_connect.
	 * @param string $username Username de connexion
	 * @param string $password Password de connexion
	 * @return boolean Returns true on success or false on failure.
	 * @throws Exception
	 */
	public function ssh2_auth_password(&$session, string $username, string $password): bool
	{
		$retour= ssh2_auth_password ( $session, $username, $password );
		if($retour==false){
			throw new Exception("ssh2_auth_password Error");
		}
		return true;
	}

	/**
	 * Execute une commande ssh (voir help du ssh2_exec du plugin ssh2)
	 * @codeCoverageIgnore
	 * @param resource $session An SSH connection link identifier, obtained from a call to ssh2_connect.
	 * @param string $commande commande a excuter
	 * @param string $pty
	 * @param array $env
	 * @param int $width
	 * @param int $height
	 * @param int $width_height_type
	 * @return bool|array Returns array of streams on success or false on failure.
	 * @throws Exception
	 */
	public function ssh2_exec(&$session, string $commande, string $pty="", array $env=array(), int $width=80, int $height=25, int $width_height_type=SSH2_TERM_UNIT_CHARS): bool|array
	{
		$stdout_stream = ssh2_exec ( $session, $commande, $pty, $env, $width, $height, $width_height_type );
		if($stdout_stream===false){
			return $this->onError("erreur lors de l'execution de la commande ".$commande);
		}
		$stdio_stream = ssh2_fetch_stream ( $stdout_stream, SSH2_STREAM_STDIO );
		$stderr_stream = ssh2_fetch_stream ( $stdout_stream, SSH2_STREAM_STDERR );
		stream_set_blocking ( $stdio_stream, true );
		stream_set_blocking ( $stderr_stream, true );
		
		return array("stdio"=>$stdio_stream,"stdout"=>$stdout_stream,"stderr"=>$stderr_stream);
	}

	/**
	 * Execute une commande ssh (voir help du ssh2_shell du plugin ssh2)
	 * @codeCoverageIgnore
	 * @param resource $session An SSH connection link identifier, obtained from a call to ssh2_connect.
	 * @param string $type_shell commande a excuter
	 * @param array $env
	 * @param int $width
	 * @param int $height
	 * @param int $width_height_type
	 * @return bool|array Returns array of streams on success or false on failure.
	 * @throws Exception
	 */
	public function ssh2_shell(&$session, string $type_shell="xterm", array $env=array(), int $width=80, int $height=25, int $width_height_type=SSH2_TERM_UNIT_CHARS): bool|array
	{
		$stdout_stream = ssh2_shell ( $session, $type_shell, $env, $width, $height, $width_height_type );
		if($stdout_stream===false){
			return $this->onError("erreur lors de l'execution de la commande ".$commande);
		}
		$stdio_stream = ssh2_fetch_stream ( $stdout_stream, SSH2_STREAM_STDIO );
		$stderr_stream = ssh2_fetch_stream ( $stdout_stream, SSH2_STREAM_STDERR );
		stream_set_blocking ( $stdio_stream, true );
		stream_set_blocking ( $stderr_stream, true );
		flush ( );
	
		return array("stdio"=>$stdio_stream,"stdout"=>$stdout_stream,"stderr"=>$stderr_stream);
	}
	
	/**
	 * envoie un fichier (voir help du ssh2_scp_send du plugin ssh2)
	 * @codeCoverageIgnore
	 * @param resource $session An SSH connection link identifier, obtained from a call to ssh2_connect.
	 * @param string $source Fichier source
	 * @param string $dest Nom complet du fichier de destination
	 * @param string|null $create_mode Code Hexa de creation de fichier
	 * @return boolean Returns true on success or false on failure.
	 * @throws Exception
	 */
	public function ssh2_scp_send(&$session, string $source, string $dest, string $create_mode=null): bool
	{
		$retour = ssh2_scp_send ( $session, $source, $dest, $create_mode );
		if($retour==false){
			throw new Exception("ssh2_scp_send Error");
		}
		return true;
	}

	/**
	 * Recupere un fichier (voir help du ssh2_scp_send du plugin ssh2)
	 * @codeCoverageIgnore
	 * @param resource $session An SSH connection link identifier, obtained from a call to ssh2_connect.
	 * @param string $source Fichier source
	 * @param string $dest Nom complet du fichier de destination
	 * @return boolean Returns true on success or false on failure.
	 * @throws Exception
	 */
	public function ssh2_scp_recv(&$session, string $source, string $dest): bool
	{
		$retour =  ssh2_scp_recv ( $session, $source, $dest );
		if($retour==false){
			throw new Exception("ssh2_scp_send Error");
		}
		return true;
	}

	/**
	 * **************** Accesseurs *************************
	 */

	/**
	 * **************** Accesseurs *************************
	 */

	/**
	 *
	 * @static @codeCoverageIgnore
	 * @return array|string Renvoie le help
	 */
	static function help(): array|string
	{
		return parent::help ();
	}
}
