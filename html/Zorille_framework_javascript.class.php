<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class javascript<br>

 *
 * Gere la creation de code javascript standard
 * @package Lib
 * @subpackage HTML
 */
class javascript extends abstract_log {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type javascript.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return javascript
	 */
	static function &creer_javascript(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new javascript ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return javascript
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	/**
	 * Creer l'objet et set la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	public function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
 	 * Ajoute les entetes et pied de page standard a du code js.
 	 *
 	 * @param string $code Code javascript.
 	 * @return string Code avec entete et pied de page JS.
 	 */
	public function template_javascript($code) {
		$javascript = "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">\n";
		$javascript .= "<!--\n";
		$javascript .= $code;
		$javascript .= "//-->\n</SCRIPT>\n\n";
		return $javascript;
	}

	/**
     * Ajoute un bouton retour.
     *
     * @param int $nb_etage Nombre de page dans l'history
     * @return string Code du bouton
     */
	public function affiche_boutton_retour($nb_etage) {
		return "<center><script=\"Javascript\"><form><input type=reset value=\"Retour\"  onClick=\"history.go(-$nb_etage)\"></form></script></center>";
	}

	/**
     *
     * @return string Code JS
     */
	public function affiche_lien_html($var, $exec = "index.php?") {
		$javascript = "function go(form) {\n";
		$javascript .= "valeur=form.$var.options[form.$var.selectedIndex].value\n";
		$javascript .= "self.location = \"$exec$var=\"+valeur\n";
		$javascript .= "}\n";
		return $this->template_javascript ( $javascript );
	}

	/**
     *
     * @return string Code JS
     */
	public function affiche_lien_html_multi_vars($var, $exec = "index.php?", $location = "self") {
		$virgule = "";
		$javascript = "function go(form) {\n";
		$javascript .= $location . ".location = \"$exec";
		
		$size = sizeof ( $var );
		if ($size == 0 || ($size % 2) != 0)
			return 0;
		for($i = 0; $i < $size; $i ++) {
			$javascript .= $virgule . $var [$i] . "=\"+form.";
			$i ++;
			$javascript .= $var [$i] . ".options[form." . $var [$i] . ".selectedIndex].value";
			$virgule = "+\"&";
		}
		$javascript .= "\n}\n";
		return $this->template_javascript ( $javascript );
	}

	/**
     *
     * @return string Code JS
     */
	public function javascript_fonction_go($location, $selecteur, $option = "") {
		$javascript = "function go(form) {\n";
		if ($option != "")
			$javascript .= "$location=\"$option\"+form.$selecteur.options[form.$selecteur.selectedIndex].value \n }\n";
		else
			$javascript .= "$location=form.$selecteur.options[form.$selecteur.selectedIndex].value \n }\n";
		
		return $this->template_javascript ( $javascript );
	}

	/**
     *
     * @return string Code JS
     */
	public function error($nb_etage) {
		$bouton = $this->affiche_boutton_retour ( $nb_etage );
		return $bouton . "</body></HTML>";
	}

	/**
     *
     * @return string Code JS
     */
	public function reload($lien) {
		$javascript = "document.location.replace('" . $lien . "')\n";
		return $this->template_javascript ( $javascript );
	}

	/**
     *
     * @return string Code JS
     */
	public function reload_frame($frame) {
		$javascript = "self.parent.frames." . $frame . ".location.reload()";
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_ready($code) {
		$javascript = "$(document).ready(function() {
					" . $code . "
			});
			";
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_load($div_dest, $fichier_dest, $form_vars = "", $args_supp = "") {
		if ($form_vars != "") {
			if (strpos ( $form_vars, "serialize()" ) === false)
				$form_vars = "$('#" . $form_vars . "').serialize()";
			if ($args_supp != "") {
				$form_vars = $form_vars . " + '&" . $args_supp . "'";
			}
		} else {
			if ($args_supp != "")
				$form_vars = "'" . $args_supp . "'";
			else
				$form_vars = "''";
		}
		$javascript = "$('#" . $div_dest . "').load('" . $fichier_dest . "'," . $form_vars . ");";
		$javascript .= $this->setCurseur ( "progress" );
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_change($div, $code) {
		$javascript = "$('" . $div . "').change(function() {
					" . $code . "
			});";
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_click($div, $code) {
		$javascript = "$('" . $div . "').click(function() {
					" . $code . "
			});";
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_submit($div, $code) {
		$javascript = "$('" . $div . "').submit(function() {
					" . $code . "
			});";
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_toggle_view_div($div, $speed = "'slow'") {
		$javascript = "$('" . $div . "').toggle(" . $speed . ");";
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_clean_div($div) {
		$javascript = "$('#" . $div . "').html('');\n";
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_active_change($selectbox) {
		$javascript = $this->jq_ready ( "$('#" . $selectbox . "').change();\n" );
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function jq_exec_change($selectbox) {
		$javascript = "$('#" . $selectbox . "').change();\n";
		return $javascript;
	}

	/**
     *
     * @return string Code JS
     */
	public function prepare_curseur($style = 'auto') {
		return "document.body.style.cursor='" . $style . "';";
	}

	/**
     *
     * @return string Code JS
     */
	public function setCurseur($style = 'auto') {
		$javascript = $this->prepare_curseur ( $style );
		return $this->jq_ready ( $javascript );
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
