<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class repertoire<br>

 *
 * Gere l'acces aux dossiers
 * @package Lib
 * @subpackage Fichier
 */
class repertoire extends abstract_log {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type repertoire.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return repertoire
	 */
	static function &creer_repertoire(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new repertoire ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return repertoire
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur
	 * @param bool $sort_en_erreur
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * @static
	 * Lit le contenu d'un dossier
	 *
	 * @param string $repertoire Repertoire a lire.
	 * @return array|false Renvoi la liste des fichiers lu, FALSE sinon.
	 */
	static public function lire_repertoire($repertoire = "no_file") {
		if (repertoire::tester_repertoire_existe ( $repertoire ) && $handle = opendir ( $repertoire )) {
			$CODE_RETOUR = array ();
			while ( false !== ($fichier = readdir ( $handle )) ) {
				if ($fichier != "." && $fichier != "..") {
					$CODE_RETOUR [] .= $fichier;
				}
			}
			closedir ( $handle );
		} else
			$CODE_RETOUR = false;
		
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Recupere les infos d'un repertoire.
	 *
	 * @param string $repertoire Chemin complet du repertoire.
	 * @return array|Bool Renvoi les stats, FALSE en cas d'erreur.
	 */
	static public function recupere_info_repertoire($repertoire = "no_file") {
		if ($repertoire == "no_file") {
			$CODE_RETOUR = false;
		} else {
			if (repertoire::tester_repertoire_existe ( $repertoire )) {
				$CODE_RETOUR = stat ( $repertoire );
			} else {
				$CODE_RETOUR = false;
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Teste la presence d'un repertoire.
	 *
	 * @param string $repertoire Chemin complet du repertoire a tester.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	static public function tester_repertoire_existe($repertoire = "no_file") {
		if ($repertoire == "no_file" || ! is_dir ( $repertoire ))
			$CODE_RETOUR = false;
		else
			$CODE_RETOUR = true;
		
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Teste la presence d'un fichier dans le repertoire.
	 *
	 * @param string $repertoire Chemin complet du repertoire a tester.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	static public function tester_repertoire_vide($repertoire = "no_file") {
		if ($repertoire == "no_file")
			$CODE_RETOUR = false;
		else {
			$CODE_RETOUR = true;
			if ($handler = @opendir ( $repertoire )) {
				while ( ($obj = readdir ( $handler )) ) {
					if ($obj == "." || $obj == "..") {
						continue;
					} else {
						$CODE_RETOUR = false;
					}
				}
				closedir ( $handler );
			} else
				$CODE_RETOUR = false;
		}
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Recupere la taille disque d'un repertoire.
	 *
	 * @param string $repertoire Chemin complet du repertoire.
	 * @return int|false Renvoi la taille, FALSE sinon.
	 */
	static public function recupere_taille_disque($repertoire = "no_file") {
		if ($repertoire == "no_file")
			$CODE_RETOUR = false;
		else {
			if (repertoire::tester_repertoire_existe ( $repertoire ))
				$CODE_RETOUR = disk_total_space ( $repertoire );
			else
				$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Recupere la taille libre d'un disque.
	 *
	 * @param string $repertoire Chemin complet du repertoire.
	 * @return int|false Renvoi la taille, FALSE sinon.
	 */
	static public function recupere_taille_libre_disque($repertoire = "no_file") {
		if ($repertoire == "no_file")
			$CODE_RETOUR = false;
		else {
			if (repertoire::tester_repertoire_existe ( $repertoire ))
				$CODE_RETOUR = disk_free_space ( $repertoire );
			else
				$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Supprime un repertoire.
	 *
	 * @param string $repertoire Chemin complet du repertoire a supprimer.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	static public function supprimer_repertoire($repertoire = "no_file") {
		if ($repertoire == "no_file")
			$CODE_RETOUR = false;
		else {
			if (repertoire::tester_repertoire_existe ( $repertoire ) == TRUE && repertoire::tester_repertoire_vide ( $repertoire ) == TRUE) {
				$CODE_RETOUR = rmdir ( $repertoire );
			} else
				$CODE_RETOUR = false;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * @static
	 * Creer un repertoire de maniere recursive.
	 *
	 * @param string $repertoire Chemin complet du repertoire a creer.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	 */
	static public function creer_nouveau_repertoire($repertoire = "no_file") {
		if ($repertoire != "no_file") {
			if (@mkdir ( $repertoire ) || repertoire::tester_repertoire_existe ( $repertoire )) {
				return true;
			} else {
				if (repertoire::creer_nouveau_repertoire ( dirname ( $repertoire ) ) === TRUE) {
					if (@mkdir ( $repertoire ) || repertoire::tester_repertoire_existe ( $repertoire )) {
						// @codeCoverageIgnoreStart
						return true;
						// @codeCoverageIgnoreEnd
					}
				}
			}
		}
		
		return false;
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
		$help [__CLASS__] ["text"] [] .= "Gere les dossiers";
		
		return $help;
	}
}

?>