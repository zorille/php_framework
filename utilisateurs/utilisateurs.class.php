<?php

/**
 * Gestion d'utilisateurs.
 * @author dvargas
 */
/**
 * class utilisateurs
 *
 * @package Lib
 * @subpackage utilisateurs
 */
class utilisateurs extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $cli_entete = "utilisateur";
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_utilisateurs = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $cle_cryt = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $iv = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $iv_size = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $username = "";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $password = "";
	
	const METHOD = 'aes-256-cbc';

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type utilisateurs.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return utilisateurs
	 */
	static function &creer_utilisateurs(
			&$liste_option, 
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new utilisateurs ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return utilisateurs
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_utilisateurs_param ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct(
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		$this->prepare_cryptage ();
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return utilisateurs
	 * @throws Exception
	 */
	public function retrouve_utilisateurs_param() {
		$this->onDebug ( __METHOD__, 1 );
		
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"utilisateurs" 
		) ) !== false) {
			$datas = $this->getListeOptions ()
				->renvoi_variables_standard ( array (
					"utilisateurs" 
			), '' );
			
			if (is_array ( $datas ) && isset ( $datas ["#comment"] )) {
				unset ( $datas ["#comment"] );
			}
			
			$this->setListeUtilisateurs ( $datas );
		}
		
		return $this;
	}

	/**
	 * Prepare le code de cryptage
	 * @return utilisateurs
	 */
	public function prepare_cryptage() {
		$this->onDebug ( __METHOD__, 1 );
		$this->setIvSize(openssl_cipher_iv_length(self::METHOD))
		->setCleCryptage(pack ( 'H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3" ))
		->setIv(openssl_random_pseudo_bytes($this->getIvSize()));
		
		return $this;
	}

	public function retrouve_utilisateur_centralise(
			$utilisateur) {
		$liste_utilisateurs = $this->getListeUtilisateurs ();
		if (isset ( $liste_utilisateurs [$utilisateur] )) {
			$this->retrouve_utilisateurs_array ( $liste_utilisateurs [$utilisateur] );
		} else {
			return $this->onError ( $utilisateur . " n'est pas reconnu" );
		}
		
		return $this;
	}

	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return utilisateurs
	 */
	public function retrouve_utilisateurs_cli() {
		$this->onDebug ( __METHOD__, 1 );
		$username = array (
				$this->getCliEntete (),
				"username" 
		);
		$password = array (
				$this->getCliEntete (),
				"password" 
		);
		$crypt_password = array (
				$this->getCliEntete (),
				"crypt_password" 
		);
		if ($this->getListeOptions ()
			->verifie_variable_standard ( $username ) === false) {
			//Si aucun username n'est fournit
			$username = implode ( "_", $username );
			return $this->onError ( "Il manque le parametre : " . $username );
		}
		if ($this->getListeOptions ()
			->verifie_variable_standard ( $password ) === false && $this->getListeOptions ()
			->verifie_variable_standard ( $crypt_password ) === false) {
			//Si aucun mdp ou mdp crypte n'est fournit, on stoppe en erreur
			$password = implode ( "_", $password );
			$crypt_password = implode ( "_", $crypt_password );
			return $this->onError ( "Il manque le parametre : " . $password . " ou " . $crypt_password );
		}
		
		$this->setUsername ( $this->getListeOptions ()
			->renvoi_variables_standard ( $username ) );
		$this->setPassword ( $this->getListeOptions ()
			->renvoi_variables_standard ( $password ) );
		if ($this->getPassword () === false) {
			//Si le mot de passe n'est pas founit en clair, on prend la version crypte
			$this->setPassword ( $this->decrypt ( $this->getListeOptions ()
				->renvoi_variables_standard ( $crypt_password ) ) );
		}
		
		return $this;
	}

	/**
	 * Retrouve les parametres dans un tableau
	 * username, crypt_password or password
	 * @param array $datas
	 * @param array $datas ["username"]
	 * @param array $datas ["crypt_password"]
	 * @param array $datas ["password"]
	 * @return utilisateurs
	 */
	public function retrouve_utilisateurs_array(
			$datas) {
		$this->onDebug ( __METHOD__, 1 );
		
		if (isset ( $datas ["utilisateur"] )) {
			$this->retrouve_utilisateur_centralise ( $datas ["utilisateur"] );
		} else {
			if (isset ( $datas ["username"] )) {
				$this->setUsername ( $datas ["username"] );
			}
			if (isset ( $datas ["crypt_password"] )) {
				$this->setPassword ( $this->decrypt ( $datas ["crypt_password"] ) );
			} elseif (isset ( $datas ["password"] )) {
				$this->setPassword ( $datas ["password"] );
			}
		}
		
		return $this;
	}

	/**
	 * Renvoi une chaine encryptee
	 * @param string $pure_string ligne a encoder
	 * @return string
	 */
	function encrypt(
			$pure_string) {
		$this->onDebug ( __METHOD__, 1 );
		$encrypted_string = openssl_encrypt($pure_string,self::METHOD, $this->getCleCryptage (), OPENSSL_RAW_DATA, $this->getIv ());
		return base64_encode ( $this->getIv () . $encrypted_string );
	}

	/**
	 * Renvoi la phrase decryptee
	 * @param string $encrypted_string ligne a decoder
	 * @return string
	 */
	function decrypt(
			$encrypted_string) {
		$this->onDebug ( __METHOD__, 1 );
		$ciphertext_dec = base64_decode ( $encrypted_string );
		$iv_dec = substr ( $ciphertext_dec, 0, $this->getIvSize () );
		$ciphertext_dec = substr ( $ciphertext_dec, $this->getIvSize () );
		$decrypted_string = openssl_decrypt( $ciphertext_dec, self::METHOD, $this->getCleCryptage (), OPENSSL_RAW_DATA, $iv_dec);
		return trim ( $decrypted_string );
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getCliEntete() {
		return $this->cli_entete;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCliEntete(
			$cli_entete) {
		$this->cli_entete = $cli_entete;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeUtilisateurs() {
		return $this->liste_utilisateurs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeUtilisateurs(
			$liste_utilisateurs) {
		$this->liste_utilisateurs = $liste_utilisateurs;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCleCryptage() {
		return $this->cle_cryt;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCleCryptage(
			$cle_cryt) {
		$this->cle_cryt = $cle_cryt;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIv() {
		return $this->iv;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIv(
			$iv) {
		$this->iv = $iv;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getIvSize() {
		return $this->iv_size;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setIvSize(
			$iv_size) {
		$this->iv_size = $iv_size;
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
	public function &setUsername(
			$username) {
		$this->username = $username;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setPassword(
			$password) {
		$this->password = $password;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des utilisateurs";
		$help [__CLASS__] ["text"] [] .= "\t--{entete au choix}_username Nom de l'utilisateur ";
		$help [__CLASS__] ["text"] [] .= "\t--{entete au choix}_password Password de l'utilisateur ";
		$help [__CLASS__] ["text"] [] .= "\t--{entete au choix}_crypt_password Password crypte de l'utilisateur ";
		
		return $help;
	}
}
?>
