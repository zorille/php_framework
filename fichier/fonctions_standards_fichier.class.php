<?php
/**
 * @author dvargas
 * @package Lib
 * 
 */

/**
 * class fonctions_standards_fichier<br>

 *
 * Gere l'acces aux fichiers
 * @package Lib
 * @subpackage Fichier
*/
class fonctions_standards_fichier extends abstract_log {

	/**
	 * Renvoi une structure standardise d'un fichier.
	 * Include : $INCLUDE_FONCTIONS<br>
	 *
	 * @param string $nom Nom du fichier.
	 * @param string $dossier Dossier du fichier.
	 * @param Bool $mandatory Necessaire ou non.
	 * @param Bool $telecharger Telecharger oui ou non.
	 * @return array Renvoi la structure rempli.
	 */
	static public function structure_fichier_standard($nom, $type, $dossier = "", $mandatory = false, $telecharger = false) {
		$structure = array ();
		$structure ["nom"] = $nom;
		$structure ["dossier"] = $dossier;
		$structure ["type"] = $type;
		$structure ["mandatory"] = $mandatory;
		$structure ["telecharger"] = $telecharger;
		
		abstract_log::onDebug_standard ( $structure, 2 );
		return $structure;
	}
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}
}
?>
