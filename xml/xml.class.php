<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class xml<br>
 
 *
 * Parse les fichiers XML.
 * @package Lib
 * @subpackage XML
 */
class xml extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var DOMDocument
	 */
	private $dom_local = NULL;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $root_tag = "xml";

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type xml.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return xml
	 */
	static function &creer_xml(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new xml ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return xml
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->prepare_xml ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/*********************** GESTION DOM *********************************/
	/**
	 * Creer on objet DOMDocument
	 * @codeCoverageIgnore
	 * @param string $version 1.0 par defaut
	 * @param string $encode UTF-8 par defaut
	 * @return xml
	 */
	public function creer_domDocument($version = '1.0', $encode = 'UTF-8') {
		$this ->onDebug ( __METHOD__, 2 );
		$dom = new DOMDocument ( $version, $encode );
		return $this ->setDomDatas ( $dom );
	}

	/**
	 * Prepare l'objet DOMDocument qui permet la gestion des XMLs
	 * @return xml
	 */
	public function prepare_xml() {
		$this ->onDebug ( __METHOD__, 2 );
		$this ->creer_domDocument ();
		$element = $this ->getDomDatas () 
			->createElement ( $this ->getEnteteTagXml () );
		$this ->getDomDatas () 
			->appendChild ( $element );
		
		return $this;
	}

	/**
	 * Ouvre le fichier XML, ou s'il fait moins de 1Mo, le charge en memoire.
	 *
	 * @param string $fichier Chemin complet du fichier XML.
	 * @return true
	 * @throws Exception
	 */
	public function open_xml($fichier_xml = "") {
		$this ->onDebug ( __METHOD__, 2 );
		//Ouverture et lecture du fichier
		if (is_file ( $fichier_xml )) {
			$dom = new DOMDocument ();
			$dom ->loadXML ( file_get_contents ( $fichier_xml ) );
			$items = $dom ->getElementsByTagName ( $this ->getEnteteTagXml () );
			foreach ( $items as $item ) {
				foreach ( $item->childNodes as $node ) {
					if ($node instanceof DOMElement) {
						$nodeElement = $this ->getDomDatas () 
							->importNode ( $node, true );
						$this ->ajoute_element_au_dom ( $nodeElement );
					}
				}
			}
		} else {
			return $this ->onError ( "Le fichier " . $fichier_xml . " n'existe pas.", "" );
		}
		
		return $this;
	}

	/**
	 * Renvoie une reponse de type requete XPATH.
	 * Si le $nom est un tableau, il est convertie en string champ1/champ2/..../champX
	 * @param array|string $nom nom du champ xml voulue.
	 * @return DOMNodeList|FALSE resultat sous forme de liste ou FALSE en cas d'erreur.
	 */
	public function renvoi_xpath($nom) {
		$this ->onDebug ( __METHOD__, 2 );
		$resultat = false;
		
		if (is_array ( $nom )) {
			$xpath = implode ( "/", $nom );
		} else {
			$xpath = $nom;
		}
		
		$xpath_object = new DOMXPath ( $this ->getDomDatas () );
		$resultat = $xpath_object ->query ( $xpath );
		
		return $resultat;
	}

	/**
	 * Creer un DomElement pour ajouter un element au DOM Document
	 * @param string $tag
	 * @param string $valeur
	 * @return DomElement
	 * @throws DOMException
	 */
	public function creer_element($tag, $valeur = "") {
		$this ->onDebug ( __METHOD__, 2 );
		$dom_finale = $this ->getDomDatas () 
			->createElement ( $tag, $valeur );
		
		return $dom_finale;
	}

	/**
	 * Ajoute un DOMElement $element au DOMDocument $source
	 * @param DOMDocument $source
	 * @param DomElement $element
	 * @return xml
	 * @throws DOMException
	 */
	public function ajoute_element(&$source, &$element) {
		$this ->onDebug ( __METHOD__, 2 );
		$source ->appendChild ( $element );
		
		return $this;
	}

	/**
	 * Ajoute le donnees d'un DOMElement en supprimant les doublons
	 * @param DOMElement $element_a_ajouter
	 * @return boolean
	 * @throws DOMException
	 */
	public function ajoute_element_au_dom(&$element_a_ajouter) {
		$this ->onDebug ( __METHOD__, 2 );
		$domDatas = $this ->getDomDatas ();
		$items = $domDatas ->getElementsByTagName ( $this ->getEnteteTagXml () );
		if (is_object ( $items )) {
			foreach ( $items as $element_xml ) {
				$this ->ajoute_element_liste ( $element_a_ajouter, $element_xml );
				$this ->ajoute_element ( $domDatas, $element_xml );
			}
		}
		
		return true;
	}

	/**
	 * Trouve dans quel element de $element_config il faut ajouter $element_a_ajouter et fait le travail
	 * @param DOMElement $element_a_ajouter Element a ajouter a l'element de reference
	 * @param DOMElement $element_config Element de reference
	 * @return boolean
	 * @throws DOMException
	 */
	public function ajoute_element_liste($element_a_ajouter, $element_config) {
		$this ->onDebug ( __METHOD__, 2 );
		$flag = true;
		
		if ($element_config ->hasChildNodes ()) {
			//Pour chaque elements de la liste deja existant
			for($i = 0; $i < ( int ) $element_config->childNodes->length; $i ++) {
				//On trouve l'element correspondant a l'element a ajouter
				if ($element_config->childNodes ->item ( $i )->nodeType == XML_ELEMENT_NODE && $element_a_ajouter->tagName == $element_config->childNodes ->item ( $i )->tagName) {
					if ($element_a_ajouter ->hasChildNodes ()) {
						for($j = 0; $j < ( int ) $element_a_ajouter->childNodes->length; $j ++) {
							if ($element_a_ajouter->childNodes ->item ( $j )->nodeType == XML_ELEMENT_NODE) {
								$flag = $this ->ajoute_element_liste ( $element_a_ajouter->childNodes ->item ( $j ), $element_config->childNodes ->item ( $i ) );
							}
						}
					}
				}
			}
		}
		
		if ($flag) {
			$this ->ajoute_element ( $element_config, $element_a_ajouter );
			$flag = false;
		}
		
		return $flag;
	}

	/**
	 * Ferme le fichier XML et desalloue les parser.
	 * @codeCoverageIgnore
	 */
	public function close_xml() {
		$this ->onDebug ( __METHOD__, 2 );
		$this ->setDomDatas ( NULL );
	}

	/**
	 * Supprime un element.
	 *
	 * @param array|string $nom nom du champ xml voulue.
	 * @return bool TRUE
	 */
	public function supprime_element($nom) {
		$this ->onDebug ( __METHOD__, 2 );
		$resultat = $this ->renvoi_xpath ( $nom );
		
		if ($resultat->length > 0) {
			foreach ( $resultat as $valeur ) {
				if ($valeur->nodeType == XML_ELEMENT_NODE) {
					$valeur->parentNode ->removeChild ( $valeur );
				}
			}
		}
		
		return $this;
	}

	/*********************** GESTION DOM *********************************/
	
	/*********************** GESTION DU DOM PAR TABLEAU *********************************/
	/**
	 * renvoie le résultat du XPATH sous forme de tableau/string
	 * @param array|string $nom nom du champ xml voulue.
	 * @return array|string|false resultat ou FALSE en cas d'erreur.
	 */
	public function renvoi_donnee($nom = "ZrootXML") {
		$this ->onDebug ( __METHOD__, 2 );
		
		if ($nom == "ZrootXML") {
			$nom = "/" . $this ->getEnteteTagXml ();
		}
		$resultat = $this ->renvoi_xpath ( $nom );
		
		if ($resultat->length === 0) {
			return false;
		}
		
		if ($resultat->length > 1) {
			$final = array ();
			foreach ( $resultat as $valeur ) {
				if ($valeur->nodeType == XML_ELEMENT_NODE) {
					$final [count ( $final )] = $this ->Dom_To_Array ( $valeur );
				}
			}
		} else {
			if ($resultat ->item ( 0 )->nodeType == XML_ELEMENT_NODE) {
				$final = $this ->Dom_To_Array ( $resultat ->item ( 0 ) );
			}
		}
		
		return $final;
	}

	/**
	 * Ajoute une valeur au DOM en memoire
	 * @param string|array $champ
	 * @param string|numeric $valeur
	 * @return xml
	 */
	public function ajoute_donnee($champ, $valeur) {
		$this ->onDebug ( __METHOD__, 2 );
		if (! is_array ( $champ )) {
			//Le xml n'aime pas avoir le premier caractere d'un champ de type numeric
			if (! is_numeric ( substr ( $champ, 0, 1 ) )) {
				$element = $this ->creer_element ( $champ, ( string ) $valeur );
				if ($element !== false) {
					$this ->ajoute_element_au_dom ( $element );
				}
			}
		} else {
			$domElement = $this ->array_to_dom ( $champ, $valeur );
			
			if ($domElement !== false) {
				$this ->ajoute_element_au_dom ( $domElement );
			}
		}
		
		return $this;
	}

	/*********************** GESTION DU DOM PAR TABLEAU *********************************/
	
	/*********************** gestion transformation donnees en tableau *************/
	/********************************* CONVERTION DOM/ARRAY **************************/
	/**
	 * Transforme un DOMNode en tableau
	 * @param DOMNode $node DOMNode ou DOMElement a convertir
	 * @return array|string tableau ou string contenant les resultats.
	 */
	public function Dom_To_Array(DOMNode $node = null) {
		$this ->onDebug ( __METHOD__, 2 );
		$result = array ();
		$group = array ();
		
		if ($node ->hasAttributes ()) {
			//Les attributs sont transformes en ligne du tableau
			foreach ( $node->attributes as $k => $v ) {
				$result [$v->name] = $v->value;
			}
		}
		//Pas de sous partie, on renvoi un champ vide
		if (! $node ->hasChildNodes ()) {
			return '';
		}
		
		//le node fils est de type texte
		if (( int ) $node->childNodes->length === 1 && $node->childNodes ->item ( 0 )->nodeType === XML_TEXT_NODE) {
			//donc le node en cours est de type element et sa valeur est le texte du node suivant
			return $result [$node->nodeName] = $node->nodeValue;
		}
		
		//Pour tous les nodes fils
		for($i = 0; $i < ( int ) $node->childNodes->length; $i ++) {
			$child = $node->childNodes ->item ( $i );
			//On passe les commentaires
			if ($child->nodeName == "#text" || $child->nodeName == "#comment") {
				continue;
			}
			
			//Si le nom de l'element n'existe pas dans le tableau
			if (! isset ( $result [$child->nodeName] )) {
				//On le creer
				$result [$child->nodeName] = $this ->Dom_To_Array ( $child );
			} else {
				//Sinon on le transforme en sous-tableau
				//$group [$child->nodeName] est un flag en fonction du nodeName
				if (! isset ( $group [$child->nodeName] )) {
					$result [$child->nodeName] = array ( 
							$result [$child->nodeName] );
					$group [$child->nodeName] = 1;
				}
				$result [$child->nodeName] [count ( $result [$child->nodeName] )] = $this ->Dom_To_Array ( $child );
			}
		}
		
		return $result;
	}

	/**
	 * Transforme un tableau en elements DOMElement
	 * @param array $tableau
	 * @param string $valeur
	 * @return DOMElement
	 * @throws DOMException
	 */
	public function array_to_dom($tableau, $valeur) {
		$this ->onDebug ( __METHOD__, 2 );
		$dom_finale = false;
		$tag = array_shift ( $tableau );
		if (count ( $tableau ) > 0) {
			$domElement = $this ->array_to_dom ( $tableau, $valeur );
			if ($domElement !== false) {
				$dom_finale = $this ->creer_element ( $tag );
				$this ->ajoute_element ( $dom_finale, $domElement );
			}
		} else {
			$dom_finale = $this ->creer_element ( $tag, $valeur );
		}
		
		return $dom_finale;
	}

	/********************************* CONVERTION DOM/ARRAY **************************/
	
	/********************************* GESTION PAR SIMPLEXMLELEMENT **************************/
	/**
	 * Importe un SimpleXMLElement dans un DOMDocment
	 * @param SimpleXMLElement $simpleXML_donnees
	 * @param string $entete
	 * @return false|xml
	 * @throws Exception
	 */
	public function import_dom_a_partir_de_simpleXML($simpleXML_donnees, $entete = 'xml') {
		$dom_element = @dom_import_simplexml ( $simpleXML_donnees );
		if (! $dom_element) {
			return $this ->onError ( 'Erreur lors de la conversion du simpleXML en DOMElement' );
		}
		$this ->creer_domDocument ();
		$dom_imported_NODE = $this ->getDomDatas () 
			->importNode ( $dom_element, true );
		$domNode = $this ->getDomDatas () 
			->appendChild ( $dom_imported_NODE );
		
		$this ->setEnteteTagXml ( $entete );
		
		return $this;
	}

	/**
	 * Renvoi la liste des options au format SimpleXMLElement.
	 *
	 * @return SimpleXMLElement resultat ou FALSE en cas d'erreur.
	 */
	public function renvoi_Dom_En_SimpleXmlElement() {
		$this ->onDebug ( __METHOD__, 2 );
		return simplexml_load_string ( $this ->getDomDatas () 
			->saveXML () );
	}

	/**
	 * Conversion un objet SimpleXMLElement en tableau par jsonencode/jsondecode
	 *
	 * @param SimpleXMLElement $xml_object
	 * @return array objet converti en tableau
	 */
	public function simpleXmlElement_to_array($xml_object) {
		$this ->onDebug ( __METHOD__, 2 );
		return json_decode ( json_encode ( ( array ) $xml_object ), true );
	}

	/**
	 * Convertie un objet SimpleXMLElement en tableau par methode interne
	 * @param SimpleXMLElement $donnees
	 * @return array
	 */
	public function simpleXml_to_array($donnees) {
		$this ->onDebug ( __METHOD__, 2 );
		$CODE_RETOUR = array ();
		foreach ( ( array ) $donnees as $nom => $valeur ) {
			if ($nom == "comment") {
				continue;
			}
			if ($valeur instanceof SimpleXMLElement) {
				$this ->recupere_donnee_fils ( $valeur, $CODE_RETOUR [$nom] );
			} else {
				$CODE_RETOUR [$nom] = $valeur;
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Prends la
	 * @param mixed $donnees recupere la valeur "string" de la variable $donnees et l'ajoute au tableau $liste_donnees
	 * @param array $liste_donnees
	 * @return true
	 */
	public function creer_valeur($donnees, &$liste_donnees) {
		$this ->onDebug ( __METHOD__, 2 );
		if (isset ( $liste_donnees )) {
			if (! is_array ( $liste_donnees )) {
				$liste_donnees = array ( 
						$liste_donnees );
			}
			$liste_donnees [count ( $liste_donnees )] = strval ( $donnees );
		} else {
			$liste_donnees = strval ( $donnees );
		}
		
		return $this;
	}

	/**
	 * Convertie un attribut de $pointeur_donnees en tableau dans $liste_donnees
	 * @param SimpleXMLElement $pointeur_donnees
	 * @param array $liste_donnees Tableau de donnees finales
	 * @return true
	 */
	public function retrouve_attribut(&$pointeur_donnees, &$liste_donnees) {
		$this ->onDebug ( __METHOD__, 2 );
		if ($pointeur_donnees instanceof SimpleXMLElement) {
			foreach ( $pointeur_donnees ->attributes () as $nom => $donnees ) {
				$this ->creer_valeur ( $donnees, $liste_donnees [$nom] );
			}
		}
		
		return $this;
	}

	/**
	 * Convertie une valeur de $pointeur_donnees en tableau dans $liste_donnees
	 * @param SimpleXMLElement $pointeur_donnees
	 * @param array $liste_donnees
	 * @return true
	 */
	public function retrouve_valeur(&$pointeur_donnees, &$liste_donnees) {
		$this ->onDebug ( __METHOD__, 2 );
		$nb_boucle = 0;
		
		if ($pointeur_donnees instanceof SimpleXMLElement) {
			foreach ( $pointeur_donnees as $nom => $donnees ) {
				$this ->creer_valeur ( $donnees, $liste_donnees [$nom] );
				$nb_boucle ++;
			}
			if ($nb_boucle == 0) {
				$this ->creer_valeur ( $pointeur_donnees, $liste_donnees );
			}
		}
		return $this;
	}

	/**
	 *
	 * @param SimpleXMLElement $pointeur_donnees
	 * @param array $liste_donnees
	 * @return true
	 */
	public function recupere_donnee_fils(&$pointeur_donnees, &$liste_donnees) {
		$this ->onDebug ( __METHOD__, 2 );
		if (count ( $pointeur_donnees ->children () ) == 0) {
			$this ->retrouve_valeur ( $pointeur_donnees, $liste_donnees );
		} else {
			
			$this ->retrouve_attribut ( $pointeur_donnees, $liste_donnees );
			foreach ( $pointeur_donnees ->children () as $nom => $nouveau_niveau ) {
				if ($nouveau_niveau instanceof SimpleXMLElement) {
					$this ->recupere_donnee_fils ( $nouveau_niveau, $liste_donnees [$nom] );
				}
			}
		}
		
		return $this;
	}

	/**
	 * Returns whether the specified XML element exists.
	 *
	 * @param SimpleXMLElement  $simpleXML
	 * @return boolean
	 */
	public function elementExists($simpleXML) {
		return $simpleXML ->getName () != '';
	}

	/**
	 * @param SimpleXMLElement  $simpleXML
	 * @param string            $attributeName
	 * @return string|NULL
	 */
	public function getAttributeValue($simpleXML, $attributeName) {
		return (isset ( $simpleXML ->attributes ()->$attributeName )) ? ( string ) $simpleXML ->attributes ()->$attributeName : NULL;
	}

	/**
	 * @param SimpleXMLElement  $simpleXML
	 * @return string
	 */
	public function getTextContent($simpleXML) {
		return ( string ) $simpleXML;
	}

	/**
	 * @param SimpleXMLElement  $xml
	 * @param string            $xpathExpr
	 * @return string|NULL
	 */
	public function getTextContentAtXpath($simpleXML, $xpathExpr) {
		$matchingElements = $simpleXML ->xpath ( $xpathExpr );
		return (count ( $matchingElements ) == 0) ? NULL : $this ->getTextContent ( $matchingElements [0] );
	}

	/**
	 * Returns true if the specified SimpleXMLElement represents a unique
	 * element or false if it represents a collection of elements.
	 *
	 * @param SimpleXMLElement  $xml
	 * @return bool
	 */
	public function isSingleElement($simpleXML) {
		$count = 0;
		foreach ( $simpleXML as $item ) {
			$count ++;
			if ($count >= 2)
				return false;
		}
		return ($count == 1);
	}

	/********************************* GESTION PAR SIMPLEXMLELEMENT **************************/
	
	/*********************** gestion transformation donnees en tableau *************/
	
	/*********************** gestion transformation tableau en XML *************/
	/**
	 * Transforme un tableau en XML
	 * @param array $array_src
	 * @param SimpleXMLElement $xml_output
	 */
	public function array_to_xml($array_src, &$xml_output, $encode = "UTF-8") {
		foreach ( $array_src as $key => $value ) {
			if (is_numeric ( $key )) {
				$key = "item";
			}
			
			if (is_array ( $value )) {
				$subnode = $xml_output ->addChild ( $key );
				$this ->array_to_xml ( $value, $subnode, $encode );
			} else {
				if ($encode != "UTF-8") {
					$value = htmlspecialchars ( $value );
				}
				
				$xml_output ->addChild ( $key, $value );
			}
		}
		
		return $this;
	}

	/*********************** gestion transformation tableau en XML *************/
	
	/**
	 * @codeCoverageIgnore
	 */
	public function __clone() {
		// Force la copie de this->dom_local, sinon
		// il pointera vers le meme objet.
		$this->dom_local = clone $this ->getDomDatas ();
	}

	/******************** Accesseurs *****************/
	/**
	 * @codeCoverageIgnore
	 * @return DOMDocument
	 */
	public function &getDomDatas() {
		return $this->dom_local;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setDomDatas($dom_local) {
		$this->dom_local = $dom_local;
		return $this;
	}

	/**
	 * ACCESSEURS get
	 * @codeCoverageIgnore
	 */
	public function getEnteteTagXml() {
		return $this->root_tag;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 */
	public function &setEnteteTagXml($root_tag) {
		$this->root_tag = $root_tag;
		return $this;
	}
/******************** Accesseurs *****************/
}
?>