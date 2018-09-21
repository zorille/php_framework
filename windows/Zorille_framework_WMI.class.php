<?php
/**
 * Gestion de WMI.
 * @author dvargas
 */

namespace Zorille\framework;
use \Exception as Exception;
use \WMI as WMI;
/**
 * class WMI
 * 
 * @package Lib
 * @subpackage Windows
 */
class WMI extends abstract_log {
	private $methodes = array ();
	private $properties = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type WMI.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return WMI
	 */
	static function &creer_WMI(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new WMI ( $sort_en_erreur, $entete );
		return $objet->_initialise ( array (
				"options" => $liste_option 
		) );
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return abstract_log
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		$this->prepare_wmi ();
		
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
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Met en place les defines necessaire
	 * @return WMI
	 */
	public function prepare_wmi() {
		$this->onDebug ( __METHOD__, 1 );
		define ( "CIM_BOOLEAN", 11 );
		define ( "CIM_DATETIME", 101 );
		define ( "CIM_OBJECT", 13 );
		define ( "CIM_SINT32", 3 );
		define ( "CIM_STRING", 8 );
		
		return $this;
	}

	/**
	 * 
	 * @param WMI $objet
	 * @return multitype:multitype:
	 * @codeCoverageIgnore
	 */
	public function retrouve_type($objet) {
		$this->onDebug ( __METHOD__, 1 );
		$resultat ["methodes"] = array ();
		$resultat ["Properties"] = array ();
		if (isset ( $objet->Methods_ ) && isset ( $objet->Properties_ )) {
			//C'est la definition d'un objet
			foreach ( $objet->Methods_ as $methode ) {
				$this->lire_Methods ( $methode, $resultat ["methodes"] );
			}
			foreach ( $objet->Properties_ as $propertie ) {
				$this->lire_properties ( $propertie, $resultat ["Properties"] );
			}
			foreach ( $objet->Qualifiers_ as $qualifier ) {
				$this->lire_Qualifiers ( $qualifier, $resultat ["Qualifiers"] );
			}
			//com_print_typeinfo($objet->SystemProperties_);
			//com_print_typeinfo($objet->Path_);
		} elseif (isset ( $objet->CIMType )) {
			//C'est une propertie
			$this->lire_properties ( $objet, $resultat ["Properties"] );
		} elseif (isset ( $objet->InParameters ) && isset ( $objet->OutParameters )) {
			//C'est une definition de methode
			$this->lire_Methods ( $objet, $resultat ["methodes"] );
		} else {
			$this->onDebug ( $objet, 1 );
		}
		
		return $resultat;
	}

	/**
	 * 
	 * @param object $donnees
	 * @param array $properties
	 * @return WMI
	 * @codeCoverageIgnore
	 */
	public function lire_properties(&$donnees, &$properties) {
		$this->onDebug ( __METHOD__, 1 );
		if ($donnees->value () == NULL) {
			$properties [$donnees->Name] = $donnees->CIMType;
			return $this;
		}
		switch ($donnees->CIMType) {
			case CIM_BOOLEAN :
				//echo $donnees->Name . " : Bool " . (( boolean ) $donnees->value () ? "TRUE" : "FALSE") . "\n\r";
				$properties [$donnees->Name] = (( boolean ) $donnees->value () ? true : false);
				break;
			case CIM_DATETIME :
				//echo $donnees->Name . " : DateTime " . ( string ) $donnees->value () . "\n\r";
				$properties [$donnees->Name] = ( string ) $donnees->value ();
				break;
			case CIM_STRING :
				//echo $donnees->Name . " : String " . ( string ) $donnees->value () . "\n\r";
				$properties [$donnees->Name] = ( string ) $donnees->value ();
				break;
			case CIM_SINT32 :
				//echo $donnees->Name . " : Int " . ( integer ) $donnees->value () . "\n\r";
				$properties [$donnees->Name] = ( integer ) $donnees->value ();
				break;
			case CIM_OBJECT :
				$properties [$donnees->Name] = array ();
				//echo $donnees->Name . " : Variant\n\r";
				if ($donnees->IsArray) {
					foreach ( $donnees->value () as $pos => $CMA_list ) {
						$properties [$donnees->Name] [$pos] = array ();
						if (isset ( $CMA_list->Properties_ )) {
							$properties [$donnees->Name] [$pos] = array ();
							foreach ( $CMA_list->Properties_ as $CMA ) {
								$this->lire_properties ( $CMA, $properties [$donnees->Name] [$pos] );
							}
						}
					}
				} else {
					$this->lire_properties ( $donnees->value (), $properties [$donnees->Name] );
				}
				break;
			default :
				$this->onInfo ( $donnees->Name );
				$this->onInfo ( "type : " . variant_get_type ( $donnees ) );
				$this->onInfo ( "CMItype : " . $donnees->CIMType );
		}
		return $this;
	}

	/**
	 * 
	 * @param object $donnees
	 * @param array $tableau
	 * @return WMI
	 */
	public function lire_Methods($donnees, &$tableau) {
		$this->onDebug ( __METHOD__, 1 );
		if (isset ( $donnees->Name )) {
			$tableau [$donnees->Name] = array ();
			$this->onDebug ( "Nom : " . $donnees->Name, 0 );
			if ($donnees->InParameters != NULL) {
				$tableau [$donnees->Name] ["InParameters"] = $this->retrouve_type ( $donnees->InParameters );
			}
			if ($donnees->OutParameters != NULL) {
				//com_print_typeinfo ( $donnees->InParameters );
				$tableau [$donnees->Name] ["OutParameters"] = $this->retrouve_type ( $donnees->OutParameters );
			}
		}
		
		return $this;
	}

	/**
	 * 
	 * @param object $donnees
	 * @param array $tableau
	 * @return WMI
	 */
	public function lire_Qualifiers($donnees, &$tableau) {
		$this->onDebug ( __METHOD__, 1 );
		if (isset ( $donnees->Name )) {
			$tableau [$donnees->Name] = array ();
			$this->onDebug ( "Nom qualifier : " . $donnees->Name, 0 );
		}
		
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
?>
