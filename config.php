<?php
/**
 * Fichier de configuration du package Lib
 *
 * @author dvargas
 * @package Lib
 * @subpackage Config
 */
date_default_timezone_set ( "Europe/Paris" );

//Gestion de l'autochargement des classes
spl_autoload_extensions ( '.class.php' );

function my_autoloader($class) {
	if (strpos ( $class, "PHPExcel" ) !== false) {
		/**
		 * Class PHPExcel
		*/
		require_once "/TOOLS/php_outils/Excel/PHPExcel/PHPExcel.php";
		/**
		 * Class IOFactory
		 */
		require_once "/TOOLS/php_outils/Excel/PHPExcel/PHPExcel/IOFactory.php";
	} elseif (preg_match ( "/^(PHPUnit|Composer|ZipArchive|Instantiator|LazyMap)/", $class ) !== 0) {
		//On ne fait rien, ce sont les tests unitaires
	} else {
		require_once $class . spl_autoload_extensions ();
	}
}
spl_autoload_register ( 'my_autoloader' );

if (! isset ( $rep_document ) && $rep_document != "")
	$rep_document = ".";

$rep_lib = $rep_document . "/php_framework";
$rep_outils = $rep_document . "/php_outils";
/**
 * Inclue les fonctions standards
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib );
/**
 * d'abord les class globals
 * et la gestion des dependances
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/class_globals" );
/**
 * Inclue FICHIER
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/fichier" );
/**
 * Inclue le XML
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/xml" );
/**
 * Inclue FORK
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/fork" );
/**
 * Inclue MAIL
*/
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/mail" );
/**
 * Inclue SGBD
*/
$rep_database = $rep_lib . "/sgbd";
$rep_customers = $rep_database . "/customers";
$rep_requete = $rep_database . "/rep_requete";
$rep_db_connue = $rep_database . "/db_connue";
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_database . PATH_SEPARATOR . $rep_requete . PATH_SEPARATOR . $rep_db_connue . PATH_SEPARATOR . $rep_customers );
/**
 * Inclue FLUX
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/flux" );
/**
 * Inclue DATES
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/dates" );
/**
 * Inclue GESTION MACHINES
*/
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/gestion_machines" );
/**
 * Inclue MONITEUR
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/monitoring" );
/**
 * Inclue HTML
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/html" );
/**
 * Inclue COMMANDLINE
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/commandline" );
/**
 * Inclue GENERATION
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/generation" );
/**
 * Inclue HTML
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/copie_donnees" );
/**
 * Inclue Les class de traitement des strings
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/strings" );
/**
 * Inclue le WebService
*/
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/webService" );
/**
 * Inclue Les class d'appel a slurm
*/
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/slurm" );
/**
 * Inclue Les class d'appel a Zabbix
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/zabbix" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/zabbix/administration" );
/**
 * Inclue Les class d'appel a iTop
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/itop" );
/**
 * Inclue Les class d'appel a PingDom
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/pingdom" );
/**
 * Inclue Les class d'appel a Splunk
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/splunk" );
/**
 * Inclue Les class d'appel a SolarWinds
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/solarwinds" );
/**
 * Inclue Les class d'appel a bladelogic
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/bladelogic" );
/**
 * Inclue Les class d'appel a vmware
 */
require_once $rep_lib . "/vmware/config_vmware.php";
/**
 * Inclue Les class d'appel a aws_cloudwatch
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/aws_cloudwatch" );
/**
 * Inclue Les class d'appel a utilisateurs
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/utilisateurs" );
/**
 * Inclue HP
 */
$rep_HP = $rep_lib . "/HP";
$rep_sitescope = $rep_HP . "/sitescope";
$rep_HPOM = $rep_HP . "/HPOM";
$rep_Stars = $rep_HP . "/Stars";
$rep_UCMDB = $rep_HP . "/ucmdb";
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_HP . PATH_SEPARATOR . $rep_sitescope . PATH_SEPARATOR . $rep_HPOM . PATH_SEPARATOR . $rep_Stars . PATH_SEPARATOR . $rep_UCMDB );
/**
 * Inclue Windows
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/windows" );

// passage par internet
if (! isset ( $argv ) && ! isset ( $argc ) && isset ( $_SERVER ["SCRIPT_FILENAME"] )) {
	$argv = array ();
	$argv [0] = $_SERVER ["SCRIPT_FILENAME"];
	
	if (isset ( $liste_variables_systeme )) {
		// Gestion des valeurs lies au systeme type --conf ou autre valeur fixe
		foreach ( $liste_variables_systeme as $nom => $valeur ) {
			$argv [] .= "--" . $nom;
			if (is_array ( $valeur )) {
				$argv = array_merge ( $argv, $valeur );
			} elseif ($valeur !== "") {
				array_push ( $argv, $valeur );
			}
		}
	}
	
	if (isset ( $_REQUEST )) {
		foreach ( $_REQUEST as $nom => $valeur ) {
			$argv [] .= "--" . $nom;
			$argv [] .= $valeur;
		}
	}
	$argc = count ( $argv );
	
	$is_web = true;
} else {
	if (! isset ( $is_web )) {
		$is_web = false;
	}
}

if (isset ( $argc ) && isset ( $argv ) && ! isset ( $liste_option )) {
	$liste_option = options::creer_options ( $argc, $argv, 0, 20000, "", $rep_lib, true );
}

// On met en place les logs
$fichier_log = logs::creer_logs ( $liste_option );
$fichier_log->setIsWeb ( $is_web );

/**
 * Inclue Cacti
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/cacti" );
require_once "config_cacti.php";

/**
 * Inclue THRIFT
 */
if (isset ( $INCLUDE_THRIFT )) {
	set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_outils . "/thrift" );
	// set THRIFT_ROOT to php directory of the hive distribution
	$GLOBALS ['THRIFT_ROOT'] = $rep_outils . '/thrift/';
	require_once "config_thrift.php";
}

?>