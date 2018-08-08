<?php
/**
 * @author Unknow
 * @package Lib
 *
 */
/**
 * class Telnet<br>
 * 
 * Gere une connexion Telnet.
 * @package Lib
 * @subpackage Flux
 */
class Telnet extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $host;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $port;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $timeout;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $socket = NULL;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $buffer = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $prompt;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $errno;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $errstr;
	
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $DC1;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $WILL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $WONT;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $DO;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $DONT;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $IAC;
	
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	const TELNET_ERROR = FALSE;
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	const TELNET_OK = TRUE;
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	const TELNET_NOK = FALSE;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type Telnet.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $host Machine distante a connecter.
	 * @param int $port Port de connexion.
	 * @param int $timeout Duree du timeout.
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return Telnet
	 */
	static function &creer_Telnet(&$liste_option, $host = '127.0.0.1', $port = '23', $timeout = 10, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new Telnet ( $host, $port, $timeout, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Telnet
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Initialise les variables host, port et timeout.
	 * @codeCoverageIgnore
	 * @param string $host Machine distante a connecter.
	 * @param int $port Port de connexion.
	 * @param int $timeout Duree du timeout.
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($host = '127.0.0.1', $port = '23', $timeout = 10, $sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->host = $host;
		$this->port = $port;
		$this->timeout = $timeout;
		
		// set some telnet special characters
		$this->NULL = chr ( 0 );
		$this->DC1 = chr ( 17 );
		$this->WILL = chr ( 251 );
		$this->WONT = chr ( 252 );
		$this->DO = chr ( 253 );
		$this->DONT = chr ( 254 );
		$this->IAC = chr ( 255 );
	}

	/**
	 * Cree la connexion au host.
	 * @codeCoverageIgnore
	 * @return Bool TRUE si OK, FALSE sinon.
	 */
	public function connect() {
		// check if we need to convert host to IP
		if (! preg_match ( '/([0-9]{1,3}\\.){3,3}[0-9]{1,3}/', $this->host )) {
			$ip = gethostbyname ( $this->host );
			if ($this->host == $ip) {
				return $this->onError ( "On arrive pas a resoudre (IP) " . $this->host );
			} else {
				$this->host = $ip;
			}
		}
		
		// attempt connection
		$this->socket = fsockopen ( $this->host, $this->port, $this->errno, $this->errstr, $this->timeout );
		
		if (! $this->socket) {
			return $this->onError ( "Impossible de se connecter sur " . $this->host . " via le port " . $this->port );
			$CODE_RETOUR = self::TELNET_NOK;
		} else
			$CODE_RETOUR = self::TELNET_OK;
		
		return $CODE_RETOUR;
	}

	/**
	 * Ferme la connexion au host.
	 * @codeCoverageIgnore
	 * @return true
	 */
	public function disconnect() {
		if ($this->socket) {
			if (! fclose ( $this->socket ))
				return $this->onError ( "Erreur durant la fermeture de la connexion" );
			
			$this->socket = NULL;
		}
		return self::TELNET_OK;
	}

	/**
	 * Connecte un utilisateur.
	 * @codeCoverageIgnore
	 * @param string $username Nom de l'utilisateur.
	 * @param string $password Mot de passe de l'utilisateur.
	 * @return true
	 */
	public function login($username, $password) {
		try {
			$this->setPrompt ( 'login:' );
			$this->waitPrompt ();
			$this->write ( $username );
			$this->setPrompt ( 'Password:' );
			$this->waitPrompt ();
			$this->write ( $password );
			$this->setPrompt ();
			$this->waitPrompt ();
		} catch ( Exception $e ) {
			return $this->onError ( "Login failed." );
		}
		
		return self::TELNET_OK;
	}

	/**
	 * Si le prompt du telnet distant n'est pas standard,
	 * on charge le nouveau prompt avec cette fonction.
	 * @codeCoverageIgnore
	 * @param string $s Nouveau Prompt (ex : "#zorille>" )
	 * @return true
	 */
	public function setPrompt($s = '$') {
		$this->prompt = $s;
		return self::TELNET_OK;
	}

	/**
	 * Lit les donnees renvoyees jusqu'au prompt.
	 * @codeCoverageIgnore
	 * @return string Donnees lue.
	 */
	public function waitPrompt() {
		return $this->readTo ( $this->prompt );
	}

	/**
	 * Lit un caractere sur la connexion.
	 * @codeCoverageIgnore
	 *
	 * @return string Donnee lue.
	 */
	public function getc() {
		return fgetc ( $this->socket );
	}

	/**
	 * Lit un caractere sur la connexion.
	 * @codeCoverageIgnore
	 *
	 * @return string Donnee lue.
	 */
	public function clearBuffer() {
		$this->buffer = '';
	}

	/**
	 * Lit les donnees renvoyees jusqu'au prompt.
	 * @codeCoverageIgnore
	 *
	 * @return string Donnees lue.
	 */
	public function readTo($prompt) {
		if (! $this->socket)
			return $this->onError ( "Pas de connexion Telnet" );
			
			// clear the buffer
		$this->clearBuffer ();
		
		do {
			$c = $this->getc ();
			if ($c === false) {
				$this->onWarning ( "Caractere lue = FALSE." );
				$CODE_RETOUR = self::TELNET_NOK;
				break;
			}
			
			// Interpreted As Command
			if ($c == $this->IAC)
				if ($this->negotiateTelnetOptions ())
					continue;
				
				// append current char to global buffer
			$this->buffer .= $c;
			
			$CODE_RETOUR = self::TELNET_OK;
			
			// we've encountered the prompt. Break out of the loop
			if ((substr ( $this->buffer, strlen ( $this->buffer ) - strlen ( $prompt ) )) == $prompt)
				break;
		} while ( $c != $this->NULL || $c != $this->DC1 );
		
		return $CODE_RETOUR;
	}

	/**
	 * Execute une commande et lit les donnees renvoyees jusqu'au prompt.
	 * @codeCoverageIgnore
	 * @return string Donnees lue.
	 */
	public function exec($command) {
		$this->write ( $command );
		$this->waitPrompt ();
		return $this->getBuffer ();
	}

	/**
	 * Ecrit des donnees sur la connexion.
	 * var privee
	 * @codeCoverageIgnore
	 *
	 * @param string $buffer Ligne a ecrire.
	 * @param bool $addNewLine Ajoute un retour chariot a la ligne.
	 * @return true
	 */
	public function write($buffer, $addNewLine = true) {
		if (! $this->socket)
			return $this->onError ( "Pas de connexion Telnet" );
			
			// clear buffer from last command
		$this->clearBuffer ();
		
		if ($addNewLine == true)
			$buffer .= "\n";
		
		if (! fwrite ( $this->socket, $buffer ) < 0)
			return $this->onError ( "Erreur d'ecriture sur le telnet" );
		
		return self::TELNET_OK;
	}

	/**
	 * Traite les donnees pour un renvoi correcte a l'affichage.
	 * @codeCoverageIgnore
	 *
	 * @return string Donnees lue.
	 */
	public function getBuffer() {
		// cut last line (is always prompt)
		$buf = explode ( "\n", $this->buffer );
		unset ( $buf [count ( $buf ) - 1] );
		$buf = implode ( "\n", $buf );
		return trim ( $buf );
	}

	/**
	 * Controle des caracteres speciaux de Telnet.
	 * @codeCoverageIgnore
	 *
	 * @return true
	 */
	public function negotiateTelnetOptions() {
		$c = $this->getc ();
		if ($c != $this->IAC) {
			if (($c == $this->DO) || ($c == $this->DONT)) {
				$opt = $this->getc ();
				fwrite ( $this->socket, $this->IAC . $this->WONT . $opt );
			} elseif (($c == $this->WILL) || ($c == $this->WONT)) {
				$opt = $this->getc ();
				fwrite ( $this->socket, $this->IAC . $this->DONT . $opt );
			} else {
				throw new Exception ( 'Error: unknown control character ' . ord ( $c ) );
			}
		} else {
			throw new Exception ( 'Error: Something Wicked Happened' );
		}
		
		return self::TELNET_OK;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct() {
		// cleanup resources
		$this->disconnect ();
		$this->buffer = NULL;
	}

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>