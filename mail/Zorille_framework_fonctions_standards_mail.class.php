<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class fonctions_standards_mail<br>
 * @package Lib
 * @subpackage Mail
 */
class fonctions_standards_mail extends abstract_log {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fonctions_standards_mail.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standards_mail
	 */
	static function &creer_fonctions_standards_mail(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fonctions_standards_mail ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standards_mail
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml et creer un objet dates.<br>
	 *
	 * @param options &$pointeur_liste_option
	 * @return message|false Renvoi un objet MESSAGE ou FALSE en cas d'erreur ou de --no_mail.
	 */
	static public function creer_liste_mail(&$liste_option) {
		return message::creer_message ( $liste_option );
	}

	/**
	 * Verifie la presence de mail_encode_sujet dans les options
	 * @param options $liste_option
	 * @return boolean
	 */
	static public function encode_sujet(&$liste_option) {
		if ($liste_option->renvoi_variables_standard ( array (
				"mail",
				"encode_sujet" 
		), "non" ) == "non") {
			return false;
		}
		return true;
	}

	/**
	 * Verifie la presence de mail_sujet dans les options
	 * @param options $liste_option
	 * @param message $mail
	 * @param string $sujet Sujet du mail
	 * @return boolean
	 */
	static public function sujet(&$liste_option, &$mail, $sujet_par_defaut) {
		$encode = fonctions_standards_mail::encode_sujet ( $liste_option );
		if ($sujet_par_defaut == "no_sujet") {
			$sujet_par_defaut = "Ci-joint votre mail";
		}
		$mail->setSujet ( $liste_option->renvoi_variables_standard ( array (
				"mail",
				"sujet" 
		), $sujet_par_defaut ), $encode );
		
		return true;
	}

	/**
	 * Verifie la presence d'un message et l'ajoute au corp du mail
	 * @param options $liste_option
	 * @param message $mail
	 * @param array $affichage Liste des textes a envoyer
	 * @return boolean
	 */
	static public function corp(&$liste_option, &$mail, $affichage) {
		$flag = false;
		if (isset ( $affichage ["text"] ) && $affichage ["text"] != "") {
			$mail->ecrit ( $affichage ["text"] );
			$flag = true;
		}
		if (isset ( $affichage ["html"] ) && $affichage ["html"] != "") {
			$mail->ecrit_html ( $affichage ["html"] );
			$flag = true;
		}
		
		if (! $flag) {
			$mail->ecrit ( "Bonjour, \n\n Ci-joint votre mail." );
		}
		
		return true;
	}

	/**
	 * Verifie la presence d'un message et l'ajoute au corp du mail
	 * @param options $liste_option
	 * @param message $mail
	 * @param array $fichiers Liste des fichiers a envoyer
	 * @return boolean
	 */
	static public function fichier(&$liste_option, &$mail, $fichiers) {
		if (is_array ( $fichiers )) {
			foreach ( $fichiers as $fichier ) {
				if ($fichier != "") {
					$mail->attache_fichier ( $fichier );
				}
			}
		}
		
		return true;
	}

	/**
	 * Envoi un mail.<br>
	 * Le tableau $affichage contient au moins une des deux entrees suivantes:<br>
	 * $affichage["text"]="...."
	 * $affichage["html"]="...."
	 *
	 * @param options &$liste_option Pointeur sur les arguments.
	 * @param string $sujet Sujet du mail
	 * @param array $affichage Liste des textes a envoyer
	 * @param array $fichiers Liste des fichiers a envoyer
	 * @return bool true si OK, false sinon.
	 */
	static public function envoieMail_standard(&$liste_option, $sujet = "no_sujet", $affichage = false, $fichiers = false) {
		abstract_log::onInfo_standard ( "(MAIL) Envoi du mail de confirmation." );
		$mail = fonctions_standards_mail::creer_liste_mail ( $liste_option );
		if ($mail !== false) {
			//Enfin on envoi le(s) mail(s)
			//On prepare le sujet
			fonctions_standards_mail::sujet ( $liste_option, $mail, $sujet );
			
			//puis le corp
			fonctions_standards_mail::corp ( $liste_option, $mail, $affichage );
			
			//Enfin on attache les fichiers
			fonctions_standards_mail::fichier ( $liste_option, $mail, $fichiers );
			
			return $mail->envoi ();
		} else {
			abstract_log::onWarning_standard ( "(MAIL) Probleme lors de la creation du message." );
			return false;
		}
		
		return true;
	}
}
?>