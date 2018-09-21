<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class fichier<br>

 *
 * Gere l'acces aux fichiers
 * @package Lib
 * @subpackage Fichier
 */
class fichier extends repertoire {
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
	 * @var string
	 */
	var $stat;
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
	 * Instancie un objet de type fichier.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $fichier Chemin complet du fichier.
	 * @param string $creer Si le fichier n'existe pas, doit-on le creer oui/non ?
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fichier
	 */
	static function &creer_fichier(&$liste_option, $fichier, $creer = "non", $sort_en_erreur = false, $entete = __CLASS__) {
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
	 * @return fichier
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Prend le chemin complet d'un fichier, teste sa presence sur le file system
	 * et set la valeur du sort_en_erreur.<br>
	 * Il renvoi une erreur si il ne trouve pas le fichier et qu'il ne doit pas le creer.
	 * @codeCoverageIgnore
	 * @param string $fichier Chemin complet du fichier.
	 * @param string $creer Si le fichier n'existe pas, doit-on le creer oui/non ?
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @throws Exception
	 */
	public function __construct($fichier, $creer = "non", $sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de repertoire
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		$this->fichier = $fichier;
		$exist = fichier::tester_fichier_existe ( $this->fichier );
		$this->etat = false;
		$this->creer = false;
		if (! $exist && $creer == "non") {
			return $this->onError ( "Erreur le fichier : " . $this->fichier . " n'existe pas" );
		} elseif (! $exist && $creer == "oui") {
			$this->creer = true;
		}
	}

	/**
	 * Permet d'ouvrir un fichier.<br>
	 * les mode sont standard : r,w,a,r+,w+,a+
	 *
	 * @param string $mode Mode d'ouverture du fichier.
	 * @throws Exception
	 */
	public function ouvrir($mode = "r") {
		if (fichier::tester_fichier_existe ( $this->fichier ) === false && $this->creer) {
			touch ( $this->fichier );
		}
		if (! $this->etat && fichier::tester_fichier_existe ( $this->fichier )) {
			if (! $this->handler = fopen ( $this->fichier, $mode )) {
				// @codeCoverageIgnoreStart
				$this->etat = false;
				return $this->onError ( "Erreur lors de l'ouverture du fichier : " . $this->fichier );
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
		} elseif (fwrite ( $this->handler, $texte ) == FALSE) {
			// @codeCoverageIgnoreStart
			return $this->onError ( "Erreur lors de l'ecriture dans le fichier : " . $this->fichier );
		} else {
			// @codeCoverageIgnoreEnd
			$retour = true;
		}
		
		return $retour;
	}

	/**
	 * Lit une ligne dans un fichier.
	 *
	 * @param string $nb_caracteres Nombre de caractere a lire
	 * @return string|false Renvoi la ligne lue, FALSE sinon.
	 */
	public function lit_une_ligne($nb_caracteres = 4096, $fin_de_ligne = "\n\r") {
		if ($this->etat && ! feof ( $this->handler )) {
			$ligne = stream_get_line ( $this->handler, $nb_caracteres, $fin_de_ligne );
			
			//if($ligne === FALSE ) $this->onError("Erreur lors de la lecture dans le fichier : ".$this->fichier."\n");
		} else {
			// @codeCoverageIgnoreStart
			$ligne = false;
			// @codeCoverageIgnoreEnd
		}
		
		return $ligne;
	}

	/**
	 * Lit le fichier.
	 *
	 * @param string $nb_caracteres Nombre de caractere a lire
	 * @param bool $return_array Renvoi le resultat sous forme de tableau
	 * @return string|array|false Renvoi toutes les lignes lues en string ou en array, FALSE sinon.
	 */
	public function charge_fichier($nb_caracteres = 4096, $return_array = false, $fin_de_ligne = "\n\r") {
		if ($return_array) {
			$ligne = array ();
		} else {
			$ligne = "";
		}
		if ($this->etat) {
			while ( ! feof ( $this->handler ) ) {
				if ($return_array) {
					$ligne [] .= $this->lit_une_ligne ( $nb_caracteres, $fin_de_ligne );
				} else {
					$ligne .= $this->lit_une_ligne ( $nb_caracteres, $fin_de_ligne );
				}
			}
		} else {
			// @codeCoverageIgnoreStart
			$ligne = false;
			// @codeCoverageIgnoreEnd
		}
		
		return $ligne;
	}

	/**
	 * Applique des droits sur le fichier.
	 * @codeCoverageIgnore
	 * @param int $chmod Peut etre sous la forme 755 ou 0755.
	 * @return bool true si OK, false sinon.
	 */
	public function ajoute_droits($chmod) {
		if ($this->etat) {
			return chmod ( $this->fichier, octdec($chmod) );
		}
		return false;
	}

	/**
	 * @static
	 * Copie un fichier source vers une destination.<br>
	 * Retour Erreur :  O si OK,
	 * 1 si erreur de copie,
	 * 2 si la source ou la dest n'existe pas/pas defini,
	 * 3 si la destination existe.
	 * 4 impossible de creer le repertoire de destination.
	 *
	 * @param string $source Chemin du fichier source
	 * @param string $dest Chemin du fichier destination
	 * @param string $ecrase Ecrase le fichier de destination s'il existe oui/non.
	 * @return int Renvoi O si OK, different de 0 sinon.
	 */
	static function copie($source, $dest, $ecrase = "oui") {
		if (! ($dest != "" && $source != "" && fichier::tester_fichier_existe ( $source ))) {
			return 2;
		}
		if ($ecrase == "non" && fichier::tester_fichier_existe ( $dest )) {
			return 3;
		}
		if (repertoire::creer_nouveau_repertoire ( dirname ( $dest ) ) === FALSE) {
			return 4;
		}
		
		if (copy ( $source, $dest ) === FALSE)
			return 1;
		
		return 0;
	}

	/**
	 * @static
	 * Deplace un fichier source vers une destination.<br>
	 * Retour Erreur :  O si OK,
	 * 1 si erreur de copie,
	 * 2 si la destination existe.
	 *
	 * @param string $source Chemin du fichier source
	 * @param string $dest Chemin du fichier destination
	 * @param string $ecrase Ecrase le fichier de destination s'il existe oui/non.
	 * @return int Renvoi O si OK, different de 0 sinon.
	 */
	static function deplace($source, $dest, $ecrase = "oui") {
		if ($ecrase == "non" && is_file ( $dest )) {
			$continu = FALSE;
		} else {
			$continu = TRUE;
		}
		if ($continu && $source != "" && $dest != "") {
			$rep_exist = repertoire::tester_repertoire_existe ( dirname ( $dest ) );
			if ($rep_exist === FALSE)
				$rep_exist = repertoire::creer_nouveau_repertoire ( dirname ( $dest ) );
			if (fichier::renomme ( $source, $dest ) === false)
				$OUTPUT = 1;
			else {
				$OUTPUT = 0;
				if (isset ( $this ))
					$this->fichier = $dest;
			}
		} else
			$OUTPUT = 2;
		return $OUTPUT;
	}

	/**
	 * @static
	 * Supprime un fichier.
	 *
	 * @param string $fichier Chemin complet du fichier a supprimer.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	static function supprime_fichier($fichier = "") {
		if (fichier::tester_fichier_existe ( $fichier )) {
			if (unlink ( $fichier ) === FALSE) {
				// @codeCoverageIgnoreStart
				return false;
				// @codeCoverageIgnoreEnd
			}
		}
		
		return true;
	}

	/**
	 * @static
	 * renomme un fichier.
	 *
	 * @param string $source Chemin du fichier source
	 * @param string $dest Chemin du fichier destination
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	static function renomme($source, $dest) {
		// @codeCoverageIgnoreStart
		if ($source != "" && $dest != "" && fichier::tester_fichier_existe ( $source )) {
			if (@rename ( $source, $dest ))
				return true;
		}
		// @codeCoverageIgnoreEnd
		return false;
	}

	/**
	 * Ferme un fichier ouvert.
	 *
	 */
	public function close() {
		if ($this->etat) {
			fclose ( $this->handler );
			$this->etat = false;
		}
		
		return true;
	}

	/**
	 * @static
	 * Teste la presence d'un fichier.
	 *
	 * @param string $fichier Chemin complet du fichier a tester.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	static function tester_fichier_existe($fichier = "no_file") {
		$state = file_exists ( $fichier );
		clearstatcache ();
		return $state;
	}

	/**
	 * @static
	 * Teste la presence d'un fichier.
	 *
	 * @param string $fichier Chemin complet du fichier a tester.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	static function tester_fichier_standard($fichier = "no_file") {
		$state = is_file ( $fichier );
		clearstatcache ();
		return $state;
	}

	/**
	 * @static
	 * Recupere les infos d'un fichier.
	 *
	 * @param string $fichier Chemin complet du fichier.
	 * @return array|Bool Renvoi les stats en appel static sinon TRUE si OK, FALSE en cas d'erreur.
	 */
	static function recupere_info_fichier($fichier = "no_file") {
		if (fichier::tester_fichier_existe ( $fichier )) {
			$CODE_RETOUR = stat ( $fichier );
		} else {
			$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Recupere la taille d'un fichier.
	 *
	 * @param string $fichier Chemin complet du fichier.
	 * @return int|false Renvoi la taille, FALSE sinon.
	 */
	static function recupere_taille_fichier($fichier = "no_file") {
		if (fichier::tester_fichier_existe ( $fichier )) {
			$CODE_RETOUR = filesize ( $fichier );
		} else {
			$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Recupere l'integralite d'un fichier sous forme de string.
	 *
	 * @param string $fichier Chemin complet du fichier.
	 * @return int|false Renvoi la taille, FALSE sinon.
	 */
	static function Lit_integralite_fichier($fichier = "no_file") {
		if (fichier::tester_fichier_existe ( $fichier )) {
			return file_get_contents ( $fichier );
		}
		
		return false;
	}

	/**
	 * @static
	 * Recupere l'integralite d'un fichier sous forme de string.
	 *
	 * @param string $fichier Chemin complet du fichier.
	 * @return int|false Renvoi la taille, FALSE sinon.
	 */
	static function Lit_integralite_fichier_en_tableau($fichier = "no_file") {
		if (fichier::tester_fichier_existe ( $fichier )) {
			return file ( $fichier, FILE_SKIP_EMPTY_LINES );
		}
		
		return false;
	}

	/*************** Accesseur *************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function renvoi_nom_fichier() {
		return $this->fichier;
	}

	/*************** Accesseur *************************/
	
	/**
	 * @codeCoverageIgnore
	 */
	public function __destruct() {
		$this->close ();
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
		$help [__CLASS__] ["text"] [] .= "Gestion des fichiers";
		
		return $help;
	}
}

?>