<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
/**
 * class html<br>

 *
 * Gere la creation et l'envoi de html
 * @package Lib
 * @subpackage HTML
 */
class html extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $html_entete = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $html_option = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $html_doctype = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $body_option = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $body = "";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type html.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $content Content dans l'entete META
	 * @param string $description Description dans l'entete META
	 * @param string $keywords Keywords dans l'entete META
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return html
	 */
	static function &creer_html(&$liste_option, $content = "Damien V.: zorille@free.fr", $description = "", $keywords = "", $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new html ( $content, $description, $keywords, $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return html
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare l'entete
	 * @codeCoverageIgnore
	 * @param string $content Content dans l'entete META
	 * @param string $description Description dans l'entete META
	 * @param string $keywords Keywords dans l'entete META
	 * @return true
	 */
	public function __construct($content = "Damien V.: zorille@free.fr", $description = "", $keywords = "", $sort_en_erreur = false, $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		$this->setHtmlDoctype ( "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//FR\"
	   \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">" );
		
		$this->setHtmlOption ( "xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\"" );
		
		$entete = __CLASS__;
		$entete .= "<META HTTP-EQUIV=\"Cache-Control\" CONTENT=\"no-cache\">\n";
		$entete .= "<META http-equiv=\"content-type\" content=\"text/html; charset=UTF-8 \" >\n";
		$entete .= "<META NAME=\"GENERATOR\" CONTENT=\"" . $content . "\">\n";
		$entete .= "<META NAME=\"FORMATTER\" CONTENT=\"" . $content . "\">\n";
		$entete .= "<META name=\"description\" content=\"" . $description . "\">\n";
		$entete .= "<META name=\"keywords\" content=\"" . $keywords . "\">\n";
		
		return $this;
	}

	/**
	 * Accesseur en ecriture<br>
	 * Ajoute un titre dans l'entete.
	 *
	 * @param string $titre Titre a ajouter.
	 * @return html
	 */
	public function &titre($titre) {
		return $this->setAddEntete ( "<TITLE>" . $titre . "</TITLE>\n" );
	}

	/**
	 * Cree une ligne de parametre type "GET".<br>
	 * ligne=?variable1=valeur1&......<br>
	 * Tableau d'entree :<br>
	 * case1=variable1,case2=valeur1 .... etc
	 *
	 * @param array $variable Tableau contenant les couples variable/valeur.
	 * @return string|false Ligne de parametre, FALSE sinon.
	 */
	public function creer_liste_variable($variable) {
		if (is_array ( $variable ) && sizeof ( $variable ) % 2 == 0) {
			$ligne = "?" . $variable [0] . "=" . addslashes ( $variable [1] );
			for($i = 2; $i < sizeof ( $variable ); $i += 2) {
				$ligne .= "&" . $variable [$i] . "=" . addslashes ( $variable [($i + 1)] );
			}
		} else
			$ligne = false;
		
		return $ligne;
	}

	/**
	 * Cree une ligne type HREF<br>
	 *
	 * @param string $fichier Fichier cible du lien.
	 * @param string $lien Texte affiche pour le lien.
	 * @param array $variable Tableau contenant les couples variable/valeur du lien.
	 * @param string $option Ligne contenant des options de href.
	 * @param string $target Cible du href.
	 * @return string|false HREF complet, FALSE sinon.
	 */
	public function creer_lienHTML($fichier, $lien, $variable = "", $option = "", $target = "", $id = "") {
		if ($id != "") {
			$id = "id=\"" . $id . "\"";
		}
		//On verifie que le lien ne soit pas vide
		if ($lien != "" && $fichier != "") {
			//On verifie les options
			if ($target != "")
				$target = " TARGET=\"" . $target . "\"";
			if (is_array ( $variable ) && $variable != "") {
				$liste_variable = $this->creer_liste_variable ( $variable );
			} else
				$liste_variable = $variable;
				
				//on creer le lien
			$ligne = "<A HREF=\"" . $fichier . $liste_variable . "\" " . $id . " " . $target . " " . $option . ">" . stripslashes ( $lien ) . "</A>";
		} else
			$ligne = false;
		
		return $ligne;
	}

	/**
	 * Cree une ligne type INPUT
	 *
	 * @param string $name Nom du input.
	 * @param string $type Type du input (par defaut text).
	 * @param string $option Ligne contenant des options de input.
	 * @return string INPUT complet.
	 */
	public function input_form($name, $type = "text", $option = "") {
		$id = str_replace ( "[]", "", $name );
		return "<input type=\"" . $type . "\" name=\"" . $name . "\" id=\"" . $id . "\" " . $option . ">\n";
	}

	/**
	 * Cree les options d'un select
	 * @param array $tableau
	 * @param integer $i
	 * @param string $selected
	 * @return string
	 */
	public function gere_option_select(&$tableau, &$i, $selected = "") {
		$select = "          <option value=\"" . $tableau [$i] . "\" " . $selected;
		$i ++;
		if (is_array ( $tableau [$i] )) {
			if (isset ( $tableau [$i] ["option"] ))
				$select .= " " . $tableau [$i] ["option"] . " ";
			if (isset ( $tableau [$i] ["texte"] ))
				$select .= ">" . $tableau [$i] ["texte"] . "</option>\n";
		} else
			$select .= ">" . $tableau [$i] . "</option>\n";
		
		return $select;
	}

	/**
	 * Valide que la valeur et la "selected value"
	 * @param array $valeur
	 * @param string $selected_value
	 * @return string
	 */
	public function valide_selected($valeur, $selected_value) {
		if (in_array ( $valeur, $selected_value ))
			return "SELECTED";
		
		return "";
	}

	/**
	 * Cree un SELECT
	 *
	 * @param string $name Nom du select.
	 * @param array $tableau Tableau contenant les couples option/valeur du select.
	 * @param string $selected_value Si une valeur est selectionnee.
	 * @param string $option Ligne contenant des options de select.
	 * @return string|false select complet, FALSE sinon.
	 */
	public function select_form($name, $tableau, $selected_value = "", $option = "") {
		$size = sizeof ( $tableau );
		$id = str_replace ( "[]", "", $name );
		
		if ($size != 0 && ($size % 2) == 0) {
			$select = "<select name=\"" . $name . "\" id=\"" . $id . "\" " . $option . ">\n";
			if (! is_array ( $selected_value ))
				$selected_value = array (
						$selected_value 
				);
			
			for($i = 0; $i < $size; $i ++) {
				$selected =$this->valide_selected($tableau [$i], $selected_value);
				$select .= $this->gere_option_select ( $tableau, $i, $selected );
			}
			$select .= "</select>\n";
		} else
			$select = false;
		
		return $select;
	}

	/**
	 * Cree une ligne type TEXTAREA
	 *
	 * @param string $name Nom du textarea.
	 * @param string $value Texte a afficher par defaut.
	 * @param string $option Ligne contenant des options de textarea.
	 * @return string TEXTAREA complet.
	 */
	public function textearea_form($name, $value = "", $option = "") {
		$id = str_replace ( "[]", "", $name );
		return "<textarea name=\"" . $name . "\" id=\"" . $id . "\" " . $option . ">" . stripslashes ( $value ) . "</textarea>\n";
	}

	/**
	 * Transforme du texte brut en HTML.
	 *
	 * @param string $texte Texte a modifier.
	 * @return string texte au format HTML.
	 */
	public function texte2html($texte) {
		$tempo = stripslashes ( $texte );
		$tempo = trim ( $tempo );
		$tempo = str_replace ( "\n", "<br>", $tempo );
		$tempo = str_replace ( "\r", "<br>", $tempo );
		$tempo = str_replace ( "<br><br>", "<br>", $tempo );
		$tempo = addslashes ( $tempo );
		
		return $tempo;
	}

	/**
	 * Cree une ligne type IMG
	 *
	 * @param string $photo Image a ajouter.
	 * @param string $alt Texte alternatif.
	 * @param string $option Ligne contenant des options de img.
	 * @return string IMG complet.
	 */
	public function creer_photo($photo, $alt = "photo", $option = "") {
		return "<IMG src=" . $photo . " alt=" . $alt . " " . $option . ">";
	}

	/**
	 * Cree un affichage de "titre" grace a un tableau.
	 *
	 * @param string $titre Titre a afficher.
	 * @param string $option Ligne contenant des options pour un tag TD.
	 * @return string Tableau contenant le titre.
	 */
	public function creer_titre($titre, $option = "") {
		$ligne = $this->creer_entete_tableau ( "width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\"" );
		$ligne .= $this->creer_ligne_tableau ( trim ( $titre ), "", $option );
		$ligne .= $this->creer_end_tableau ();
		
		return $ligne;
	}
	
	//Creation des tableaux de donnees
	/**
	* Cree une entete de tableau.
	*
	* @param string $option Ligne contenant des options pour un tag TABLE.
	* @return string Entete de tableau.
	*/
	public function creer_entete_tableau($option = "") {
		return "<table " . $option . ">\n";
	}

	/**
	 * Cree un pied de page de tableau.
	 *
	 * @return string Pied de page de tableau.
	 */
	public function creer_end_tableau() {
		return "</table>\n";
	}

	/**
	 * Cree des lignes de tableau.<br>
	 * $variable doit contenir les tag <td></td> car il ne sont pas ajoutes.<br>
	 * parametre $type :<br>
	 * 0=Une case de la ligne du tableau par ligne de $variable.<br>
	 * 1=Une ligne de tableau par ligne de $variable
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le code de chaque ligne du tableau.
	 * @param string $TR_option Ligne contenant des options pour un tag TR.
	 * @param int $type 0 ou 1.
	 * @return string Ligne de tableau.
	 */
	public function creer_ligne_tableau_sans_td($variable, $TR_option = "", $type = 0) {
		$ligne="";
		if (is_array ( $variable )) {
			if ($type == 1)
				for($i = 0; $i < sizeof ( $variable ); $i ++)
					$ligne .= " <TR " . $TR_option . ">\n" . $variable [$i] . "</TR>\n";
			else {
				$ligne = " <TR " . $TR_option . ">\n";
				for($i = 0; $i < sizeof ( $variable ); $i ++)
					$ligne .= $variable [$i] . "\n";
				$ligne .= " </TR>\n";
			}
		} else
			$ligne .= " <TR " . $TR_option . ">\n" . $variable . " </TR>\n\n";
		return $ligne;
	}

	/**
	 * Cree une ligne de tableau.<br>
	 * $variable contient la valeur de chaque case du tableau.
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le texte de chaque case du tableau.
	 * @param string $TR_option Ligne contenant des options pour un tag TR.
	 * @param string $TD_option Ligne contenant des options pour un tag TD.
	 * @return string Ligne de tableau.
	 */
	public function creer_ligne_tableau($variable, $TR_option = "", $TD_option = "") {
		$ligne = "";
		if (is_array ( $variable )) {
			foreach ( $variable as $donnees ) {
				$ligne .= " <TR " . $TR_option . ">\n";
				$ligne .= $this->creer_case_tableau ( $donnees, $TD_option );
				$ligne .= " </TR>\n";
			}
		} else {
			$ligne .= " <TR " . $TR_option . ">\n";
			$ligne .= $this->creer_case_tableau ( $variable, $TD_option );
			$ligne .= " </TR>\n";
		}
		
		return $ligne;
	}

	/**
	 * Cree une case ou une liste de cases de tableau.<br>
	 * $variable contient la valeur de chaque case du tableau.
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le texte de chaque case du tableau.
	 * @param string $TD_option Ligne contenant des options pour un tag TD.
	 * @return string Ligne de tableau.
	 */
	public function creer_case_tableau($variable, $TD_option = "") {
		$ligne="";
		if (is_array ( $variable ))
			for($i = 0; $i < sizeof ( $variable ); $i ++)
				$ligne .= "  <td " . $TD_option . ">" . $variable [$i] . "</td>\n";
		else
			$ligne = "  <td " . $TD_option . ">" . $variable . "</td>\n";
		
		return $ligne;
	}

	/**
	 * Cree une ligne de titre de tableau.<br>
	 * $variable contient la valeur de chaque case du tableau.
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le texte de chaque case du tableau.
	 * @param string $TR_option Ligne contenant des options pour un tag TR.
	 * @param string $TH_option Ligne contenant des options pour un tag TH.
	 * @return string Ligne de tableau.
	 */
	public function creer_titre_tableau($variable, $TR_option = "", $TH_option = "") {
		$ligne = " <TR " . $TR_option . ">\n";
		$ligne .= $this->creer_case_titre_tableau ( $variable, $TH_option );
		$ligne .= " </TR>\n";
		
		return $ligne;
	}

	/**
	 * Cree une case ou une liste de cases type titre de tableau.<br>
	 * $variable contient la valeur de chaque case du tableau.
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le texte de chaque case du tableau.
	 * @param string $TH_option Ligne contenant des options pour un tag TH.
	 * @return string Ligne de tableau.
	 */
	public function creer_case_titre_tableau($variable, $TH_option = "") {
		$ligne="";
		if (is_array ( $variable ))
			for($i = 0; $i < sizeof ( $variable ); $i ++)
				$ligne .= "  <th " . $TH_option . ">" . $variable [$i] . "</th>\n";
		else
			$ligne = "  <th " . $TH_option . ">" . $variable . "</th>\n";
		
		return $ligne;
	}

	/**
	 * Cree une ligne de titre de tableau.<br>
	 * $variable contient la valeur de chaque case du tableau.
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le texte de chaque case du tableau.
	 * @param string $TABLE_option Ligne contenant des options pour un tag TABLE.
	 * @param string $TR_option Ligne contenant des options pour un tag TR.
	 * @param string $Th_option Ligne contenant des options pour un tag Th.
	 * @return string Ligne de tableau.
	 */
	public function creer_tableau($variable, $TABLE_option = "", $TR_option = "", $TD_option = "") {
		$ligne = $this->creer_entete_tableau ( $TABLE_option );
		$ligne .= $this->creer_ligne_tableau ( $variable, $TR_option, $TD_option );
		$ligne .= $this->creer_end_tableau ();
		return $ligne;
	}

	/**
	 * Cree une ligne div.<br>
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le texte du div.
	 * @param string $option Ligne contenant des options pour un tag div.
	 * @return string Ligne de tableau.
	 */
	public function creer_div($variable, $name = "", $option = "", $fin_div = true) {
		$ligne = "";
		
		if ($name == "") {
			$id = "";
		} else {
			$id = "id=\"" . $name . "\"";
		}
		$entete = "<div ".$id." ".$option.">\n";
		if ($fin_div) {
			$pied_page = "\n</div>\n";
		} else {
			$pied_page = "";
		}
		
		if (is_array ( $variable ))
			foreach ( $variable as $data )
				$ligne .= $entete . $data . $pied_page;
		else
			$ligne = $entete . $variable . $pied_page;
		
		return $ligne;
	}

	/**
	 * Cree une ligne span.<br>
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le texte du span.
	 * @param string $option Ligne contenant des options pour un tag div.
	 * @return string Ligne de tableau.
	 */
	public function creer_span($variable, $name = "", $option = "", $fin_span = true) {
		$ligne = "";
		
		if ($name == "") {
			$id = "";
		} else {
			$id = "id=\"" . $name . "\"";
		}
		$entete = "<span ".$id." ".$option.">\n";
		if ($fin_span) {
			$pied_page = "\n</span>\n";
		} else {
			$pied_page = "";
		}
		
		if (is_array ( $variable ))
			foreach ( $variable as $data )
				$ligne .= $entete . $data . $pied_page;
		else
			$ligne = $entete . $variable . $pied_page;
		
		return $ligne;
	}

	/**
	 * Cree une ligne form.<br>
	 *
	 * @param string|array $variable Ligne ou tableau de ligne contenant le texte du div.
	 * @param string $option Ligne contenant des options pour un tag div.
	 * @return string Ligne de tableau.
	 */
	public function creer_form($variable, $name = "", $method = "", $action = "", $option = "", $fin_form = true) {
		$ligne = "";
		
		if ($name == "") {
			$id = "";
		} else {
			$id = "id=\"" . $name . "\"";
		}
		if ($method != "") {
			$method = "method=\"" . $method . "\"";
		} else {
			$method = "method=\"post\"";
		}
		if ($action == "") {
			$action = "action=\"\"";
		} else {
			$action = "action=\"" . $action . "\"";
		}
		
		$entete = "<form ".$id." ".$method." ".$action." ".$option.">\n";
		if ($fin_form) {
			$pied_page = "\n</form>\n";
		} else {
			$pied_page = "";
		}
		
		if (is_array ( $variable )) {
			foreach ( $variable as $data ) {
				$ligne .= $entete . $data . $pied_page;
			}
		} else {
			$ligne = $entete . $variable . $pied_page;
		}
		
		return $ligne;
	}

	/**
	 * Ajoute les donnees d'un fichier dans l'entete.
	 *
	 * @param string $file Chemin complet du fichier a ajouter.
	 * @return html
	 */
	public function &importer_fichier_dans_entete($file) {
		$fichier = file ( $file );
		foreach ( $fichier as $ligne )
			$this->setAddEntete ( "\n" . $ligne . "\n" );
		
		return $this;
	}

	/**
	 * Envoi un fichier<br />
	 * ATTENTION cette fonction envoi le header
	 * @codeCoverageIgnore
	 * @param string $file Chemin complet du fichier a ajouter.
	 * @param string $mimetype Mime Type du fichier
	 * @return html
	 */
	public function &envoyer_fichier($file, $mimetype = "application/octet-stream") {
		//if($charset!="NO"){
		//	$header_charset="; charset=".$charset;
		//}
		header ( 'charset: UTF-8' );
		header ( 'Content-Description: File Transfer' );
		header ( 'Content-Type: ' . $mimetype );
		header ( 'Content-Disposition: attachment; filename=' . str_replace ( " ", "_", basename ( $file ) ) );
		header ( 'Content-Transfer-Encoding: binary' );
		header ( 'Expires: 0' );
		header ( 'Cache-Control: must-revalidate' );
		header ( 'Pragma: public' );
		header ( 'Content-Length: ' . filesize ( $file ) );
		ob_clean ();
		flush ();
		readfile ( $file );
		
		return $this;
	}

	/**
	 * Affiche la page HTML sur la sortie standard.
	 *
	 * @return html
	 */
	public function &afficher_page_html() {
		echo $this->construit_page_html ();
		
		return $this;
	}

	/**
	 * Affiche la page HTML sur la sortie standard.
	 *
	 * @return html
	 */
	public function &afficher_json() {
		echo json_encode ( $this->getBody () );
		
		return $this;
	}

	/**
	 * Copie la page HTML dans un fichier.
	 * @codeCoverageIgnore
	 * @param string $filename Chemin complet du fichier a remplir.
	 * @return true
	 */
	public function exporter_html_dans_fichier($filename) {
		if (! $handle = fopen ( $filename, 'w' )) {
			echo "Impossible d'ouvrir le fichier ($filename)";
			exit ();
		}
		if (fwrite ( $handle, $this->construit_page_html () ) == FALSE) {
			echo "Impossible d'ecrire dans le fichier (" . $filename . ")";
			exit ();
		}
		
		fclose ( $handle );
		return true;
	}

	/**
	 * Renvoi la page HTML complete.
	 *
	 * @return string Renvoi de la page html.
	 */
	public function construit_page_html() {
		$RETOUR = $this->getHtmlDoctype () . "\n";
		$RETOUR .= "<HTML " . $this->getHtmlOption () . ">\n <HEAD>\n";
		$RETOUR .= $this->getHTMLEntete ();
		$RETOUR .= " </HEAD>\n";
		$RETOUR .= " <BODY " . $this->getBodyOption () . " >";
		$RETOUR .= $this->getBody ();
		$RETOUR .= " </BODY>\n</HTML>\n";
		
		return $RETOUR;
	}

	/***************** ACCESSEURS *******************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setBody($body_sup) {
		$this->body = $body_sup;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAddBody($body_sup) {
		$this->body .= $body_sup;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHTMLEntete() {
		return $this->html_entete;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAddEntete($entete_sup) {
		$this->html_entete .= $entete_sup;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getBodyOption() {
		return $this->body_option;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setAddBodyOption($body_option_sup) {
		$this->body_option .= $body_option_sup;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHtmlDoctype() {
		return $this->html_doctype;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHtmlDoctype($html_doctype) {
		$this->html_doctype = $html_doctype;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getHtmlOption() {
		return $this->html_option;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHtmlOption($html_option) {
		$this->html_option = $html_option;
		return $this;
	}

	/***************** ACCESSEURS *******************/
	
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
} //Fin de la class HTML


?>
