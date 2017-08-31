<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class sftp_z<br>
 * 
 * Gere une connexion SFTP.
 * @package Lib
 * @subpackage Flux
 */
class sftp_z extends ssh_z {
	/**
	 * var privee
	 * @access private
	 * @var handler
	 */
	private $sftp_connexion = false;
	/**
	 * var privee
	 * @access private
	 * @var boolean
	 */
	private $tester_sftp_existe;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type sftp_z.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return sftp_z
	 */
	static function &creer_sftp_z(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new sftp_z ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return sftp_z
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 */
	public function __construct( $sort_en_erreur = false, $entete = __CLASS__) {
		parent::__construct (  $sort_en_erreur, $entete );
	}

	/**
	 * Ouvre la connexion SFTP sur une machine distante.
	 * @codeCoverageIgnore
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function sftp_connect() {
		$this->onDebug ( "Sftp connect", 1 );
		$this->sftp_connexion = ssh2_sftp ( $this->getConnexion () );
		
		if (! $this->tester_sftp_existe ()) {
			return $this->onError ( "Erreur durant la connexion sftp." );
		}
		
		return true;
	}

	/**
	 * Teste l'existance de la connexion SFTP.
	 *
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function tester_sftp_existe() {
		if ($this->sftp_connexion !== false) {
			$this->tester_sftp_existe = true;
		} else {
			$this->tester_sftp_existe = false;
		}
		
		return $this->tester_sftp_existe;
	}

	/**
	 * Creer un repertoire de maniere recursive.
	 * @codeCoverageIgnore
	 * @param string $repertoire Chemin complet du repertoire a creer.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function creer_nouveau_repertoire($repertoire) {
		if (! $this->tester_sftp_existe) {
			return false;
		}
		if ($repertoire == ".") {
			return true;
		}
		if (mkdir ( "ssh2.sftp://" . $this->sftp_connexion . $repertoire )) {
			return true;
		} else {
			if ($this->creer_nouveau_repertoire ( dirname ( $repertoire ) ) === TRUE) {
				return mkdir ( "ssh2.sftp://" . $this->sftp_connexion . $repertoire );
			}
		}
		
		return false;
	}

	/**
	 * Recupere les infos d'un repertoire.
	 * @codeCoverageIgnore
	 * @param string $repertoire Chemin complet du repertoire.
	 * @return array|Bool Renvoi les stats, FALSE en cas d'erreur.
	 */
	public function recupere_info_repertoire($repertoire) {
		if (! $this->tester_sftp_existe) {
			return false;
		}
		return ssh2_sftp_stat ( $this->sftp_connexion, $repertoire );
	}

	/**
	 * Renome un fichier distant.
	 * @codeCoverageIgnore
	 * @param string $from Chemin complet du fichier a renomer.
	 * @param string $to Chemin complet du fichier renomer.
	 * @return Bool TRUE si OK, FALSE en cas d'erreur.
	 */
	public function renome_repertoire($from, $to) {
		if (! $this->tester_sftp_existe) {
			return false;
		}
		return ssh2_sftp_rename ( $this->sftp_connexion, $from, $to );
	}

	/**
	 * Supprime un repertoire.
	 * @codeCoverageIgnore
	 * @param string $repertoire Chemin complet du repertoire a supprimer.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	public function supprimer_repertoire($repertoire = "no_file") {
		if (! $this->tester_sftp_existe) {
			return false;
		}
		return ssh2_sftp_rmdir ( $this->sftp_connexion, $repertoire );
	}

	/**
	 * Envoi un fichier
	 * @codeCoverageIgnore
	 * @param string $source fichier source avec chemin absolu
	 * @param string $dest fichier destination avec chemin absolu
	 */
	public function envoi_fichier($source, $dest) {
		$this->onDebug ( "Envoi en SFTP", 1 );
		if (! $this->tester_sftp_existe) {
			return $this->onError ( "Pas de connexion active." );
		}
		$this->onDebug ( "On active la connexion en SFTP", 2 );
		$stream = fopen ( "ssh2.sftp://" . $this->sftp_connexion . $dest, 'w' );
		if (! $stream) {
			return $this->onError ( "Impossible d'ouvrir le fichier destination : " . $dest );
		}
		$this->onDebug ( "On charge le fichier : " . $source, 2 );
		$filesize = fichier::recupere_taille_fichier ( $source );
		$this->onDebug ( "Filesize : " . $filesize, 1 );
		if ($filesize > 134217728) {
			ini_set ( "memory_limit", ($filesize * 2) );
		}
		$data_to_send = file_get_contents ( $source );
		if ($data_to_send === false) {
			return $this->onError ( "Impossible d'ouvrir le fichier source : " . $source );
		}
		$this->onDebug ( "On envoi", 2 );
		if (@fwrite ( $stream, $data_to_send ) === false) {
			return $this->onError ( "Impossible d'ecrire dans le fichier destination : " . $dest );
		}
		@fclose ( $stream );
		
		return true;
	}

	/**
	 * Recupere un fichier
	 * @codeCoverageIgnore
	 * @param string $source fichier source avec chemin absolu
	 * @param string $dest fichier destination avec chemin absolu
	 */
	public function recupere_fichier($source, $dest) {
		if (! $this->tester_sftp_existe) {
			return $this->onError ( "Pas de connexion active." );
		}
		$stream = @fopen ( "ssh2.sftp://" . $this->sftp_connexion . $source, 'r' );
		if (! $stream) {
			return $this->onError ( "Impossible d'ouvrir le fichier source : $source" );
		}
		$size = $this->getFileSize ( $source );
		$contents = '';
		$read = 0;
		while ( $read < $size && ($buf = fread ( $stream, $size - $read )) ) {
			$read += strlen ( $buf );
			$contents .= $buf;
		}
		file_put_contents ( $dest, $contents );
		@fclose ( $stream );
		
		return true;
	}

	/**
	 * Recupere la taille d'un fichier distant
	 * @codeCoverageIgnore
	 * @param string $file fichier avec chemin absolu
	 */
	public function getFileSize($file) {
		if (! $this->tester_sftp_existe) {
			return $this->onError ( "Pas de connexion active." );
		}
		return filesize ( "ssh2.sftp://" . $this->sftp_connexion . $file );
	}

	/**
	 * Lit un dossier distant
	 * @codeCoverageIgnore
	 * @param unknown $remote_file
	 * @return Ambigous <false, boolean>|multitype:string
	 */
	public function lire_dossier($remote_file) {
		if (! $this->tester_sftp_existe) {
			return $this->onError ( "Pas de connexion active." );
		}
		$dir = "ssh2.sftp://" . $this->sftp_connexion . $remote_file;
		$tempArray = array ();
		$handle = opendir ( $dir );
		//Liste tous les fichiers
		while ( false !== ($file = readdir ( $handle )) ) {
			if (substr ( "$file", 0, 1 ) != ".") {
				if (is_dir ( $file )) {
					//C'est un dossier, on ne fait rien
				} else {
					$tempArray [] = $file;
				}
			}
		}
		closedir ( $handle );
		return $tempArray;
	}

	/**
	 * Supprime un fichier distant
	 * @codeCoverageIgnore
	 * @param string $remote_file
	 * @return Ambigous <false, boolean>
	 */
	public function supprime_fichier($remote_file) {
		if (! $this->tester_sftp_existe) {
			return $this->onError ( "Pas de connexion active." );
		}
		unlink ( "ssh2.sftp://" . $this->sftp_connexion . $remote_file );
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct() {
		$this->ssh_close ();
		unset ( $this );
	}
	
	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getSftpConnexion() {
		return $this->sftp_connexion;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setSftpConnexion($sftp_connexion) {
			$this->sftp_connexion = $sftp_connexion;
			return $this;
	}
	
	/******************************* ACCESSEURS ********************************/
}
?>