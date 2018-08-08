<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class fichier_gz<br>

 *
 * Gere l'acces aux fichiers compresses en GZ
 * @package Lib
 * @subpackage Fichier
 */
class fichier_gz extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var resource
	 */
	var $handler;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	var $fichier;
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	var $etat;
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	var $creer;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fichier_gz.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $fichier Chemin complet du fichier.
	 * @param string $creer Si le fichier n'existe pas, doit-on le creer oui/non ?
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fichier_gz
	 */
	static function &creer_fichier_gz(&$liste_option, $fichier, $creer = "non", $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fichier ( $fichier, $creer, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
	
		return $objet;
	}
	
	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fichier_gz
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}
	
	/*********************** Creation de l'objet *********************/
	
	/**
	 * Prend le chemin complet d'un fichier compresse, teste sa presence sur le file system
	 * et set la valeur du sort_en_erreur.<br>
	 * Il renvoi une erreur si il ne trouve pas le fichier et qu'il ne doit pas le creer.
	 * @codeCoverageIgnore
	 * @param string $fichier Chemin complet du fichier.
	 * @param string $creer Si le fichier n'existe pas, doit-on le creer oui/non ?
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($fichier, $creer = "non", $sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		$this->fichier = $fichier;
		$exist = fichier::tester_fichier_existe ( $this->fichier );
		$this->etat = false;
		$this->creer = false;
		if (! $exist && $creer == "non") {
			return $this->onError ( "Erreur le fichier GZ : " . $this->fichier . " n'existe pas" );
		} elseif (! $exist && $creer == "oui") {
			$this->creer = true;
		}
	}

	/**
	 * Permet d'ouvrir un fichier.<br>
	 * les mode sont standard : r,w,a,r+,w+,a+
	 *
	 * @param string $mode Mode d'ouverture du fichier.
	 */
	public function ouvrir($mode = "r") {
		if (fichier::tester_fichier_existe ( $this->fichier ) === false && $this->creer) {
			touch ( $this->fichier );
		}
		if (! $this->etat && fichier::tester_fichier_existe ( $this->fichier )) {
			if (! $this->handler = gzopen ( $this->fichier, $mode )) {
				// @codeCoverageIgnoreStart
				return $this->onError ( "Erreur lors de l'ouverture du fichier GZ : " . $this->fichier . "\n" );
			} else {
				// @codeCoverageIgnoreEnd
				$this->etat = true;
			}
		}
		
		return $this->etat;
	}

	/**
	 * Ecrit une ligne dans un fichier.
	 *
	 * @param string $texte Ligne a ecrire.
	 */
	public function ecrit($texte) {
		if ($this->etat === false) {
			// @codeCoverageIgnoreStart
			return $this->onError ( "Erreur le fichier n'est pas ouvert : " . $this->fichier );
			// @codeCoverageIgnoreEnd
		} elseif (gzwrite ( $this->handler, $texte ) == FALSE) {
			// @codeCoverageIgnoreStart
			return $this->onError ( "Erreur lors de l'ecriture dans le fichier GZ : " . $this->fichier );
			// @codeCoverageIgnoreEnd
		}
		
		return true;
	}

	public function lit_une_ligne($nb_caracteres = 8096) {
		if ($this->etat && ! feof ( $this->handler )) {
			if ($nb_caracteres == "non")
				$ligne = gzgets ( $this->handler );
			else
				$ligne = gzgets ( $this->handler, $nb_caracteres );
		} else {
			// @codeCoverageIgnoreStart
			$ligne = false;
			// @codeCoverageIgnoreEnd
		}
		
		return $ligne;
	}

	/**
	 * @static
	 * Lit le fichier.
	 *
	 * @param string $fichier Chemin complet du fichier a lire.
	 * @return string|false Renvoi toutes les lignes lues, FALSE sinon.
	 */
	static function lit_fichier($fichier = "no_file") {
		if (fichier::tester_fichier_existe ( $fichier )) {
			return gzfile ( $fichier );
		}
		
		return false;
	}

	/**
	 * Compresse le fichier.
	 *
	 * @param string $fichier Chemin complet du fichier a lire.
	 * @param int $level Niveau de compression
	 * @return Bool Renvoi OK si le fichier est compresse, FALSE sinon.
	 */
	public function compresse($fichier, $level = 9) {
		$str = @file_get_contents ( $fichier );
		if ($str) {
			$this->ecrit ( $str );
		} else {
			return $this->onError ( "Le fichier " . $fichier . " ne peut pas etre lu." );
		}
		
		return true;
	}

	/**
	 * Decompresse le fichier.
	 *
	 * @param string $fichier Chemin complet du fichier a decompresser.
	 * @param string $fichier_final Chemin complet du fichier decompresse.
	 * @return Bool Renvoi OK si le fichier est decompresse, FALSE sinon.
	 */
	public function decompresse($fichier, $fichier_final) {
		if ($fichier != "" && $fichier_final != "") {
			$str = fichier_gz::lit_fichier ( $fichier );
			if ($str) {
				$fichier_sortie = fichier::creer_fichier ( $this->getListeOptions(), $fichier_final, "oui" );
				$fichier_sortie->ouvrir ( "w" );
				for($i = 0; $i < sizeof ( $str ); $i ++) {
					$fichier_sortie->ecrit ( $str [$i] );
				}
				$fichier_sortie->close ();
			} else {
				return $this->onError ( "Le fichier " . $fichier . " ne peut pas etre lu." );
			}
		} else {
			return $this->onError ( "Il manque un nom de fichier : " . $fichier . " " . $fichier_final );
		}
		
		return true;
	}

	/**
	 * Ferme un fichier ouvert.
	 *
	 * @return true Renvoi TRUE.
	 */
	public function close() {
		if ($this->etat) {
			$this->handler = gzclose ( $this->handler );
			$this->etat = false;
		}
		return true;
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
		$help [__CLASS__] ["text"] [] .= "Gestion des fichiers GZ";
		$help [__CLASS__] ["text"] [] .= "\t--fichier=<nom du fichier>";
		$help [__CLASS__] ["text"] [] .= "\t--level=<niveau de cryptage de 1 a 9>";
		$help [__CLASS__] ["text"] [] .= "\t--fichier_final=<nom du fichier cible>";
		$help [__CLASS__] ["text"] [] .= "\t--creer fichier=oui/non";
		$help [__CLASS__] ["text"] [] .= "\t--sort_en_erreur=oui/non";
		
		return $help;
	}

	function __destruct() {
		return $this->close ();
	}
}

?>
