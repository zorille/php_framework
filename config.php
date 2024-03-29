<?php
/**
 * Fichier de configuration du package Lib
 *
 * @author dvargas
 * @package Lib
 * @subpackage Config
 */
date_default_timezone_set ( "Europe/Paris" );
// Gestion de l'autochargement des classes
spl_autoload_extensions ( '.class.php' );

function my_autoloader(
		$class) {
			if (strpos($class,"Zorille\\")!==false) {
				if (strpos ( $class, '\\' )) {
					$class = str_replace ( '\\', '_', $class );
				}
				require_once $class . spl_autoload_extensions ();
			} else if (strpos($class,".php")!==false) {
				require_once str_replace ( '\\', '/', $class );
			} else if (strpos($class,"iTop")!==false) {
				//En cas d'appelle aux namspace iTop
			} else {
				require_once str_replace ( '\\', '/', $class ).".php";
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
 * d'abord les class globals et la gestion des dependances
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
 * Inclue Les class d'appel a utilisateurs
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_lib . "/utilisateurs" );
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
	$liste_option = Zorille\framework\options::creer_options ( $argc, $argv, 0, 20000, "", $rep_lib, true );
}
// On met en place les logs
$fichier_log = Zorille\framework\logs::creer_logs ( $liste_option );
$fichier_log->setIsWeb ( $is_web );

/**
 * Inclue THRIFT
 */
if (isset ( $INCLUDE_THRIFT )) {
	set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_outils . "/thrift" );
	// set THRIFT_ROOT to php directory of the hive distribution
	$GLOBALS ['THRIFT_ROOT'] = $rep_outils . '/thrift/';
	require_once "config_thrift.php";
}
/**
 * Inclue GRAPHVIZ
 */
if (isset ( $INCLUDE_GRAPHVIZ )) {
	set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_outils . "/graphviz" );
}

/**
 * Inclue EXCEL
 */
if (isset ( $INCLUDE_EXCEL )) {
	set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_outils . "/Excel" );
	set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_outils . "/Excel/PhpOffice/PhpSpreadsheet" );
}


/*
 * Gestion des API REST ou SOAP
 */
$rep_APIWeb = $rep_lib . "/APIWeb";
/**
 * Inclue Les class d'appel a Zabbix
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/zabbix" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/zabbix/administration" );
/**
 * Inclue Les class d'appel a iTop
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/itop" );
/**
 * Inclue Les class d'appel a OPNSense
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/opnsense" );
/**
 * Inclue Les class d'appel a Office 365
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/o365" );
/**
 * Inclue Les class d'appel a PingDom
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/pingdom" );
/**
 * Inclue Les class d'appel a Dolibarr
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/dolibarr" );
/**
 * Inclue Les class d'appel a Pipedrive
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/pipedrive" );
/**
 * Inclue Les class d'appel a Pipedrive
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/coservit" );
/**
 * Inclue Les class d'appel a Splunk
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/splunk" );
/**
 * Inclue Les class d'appel a LibreNMS
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/librenms" );
/**
 * Inclue Les class d'appel a SolarWinds
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/solarwinds" );
/**
 * Inclue Les class d'appel a Veeam
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/veeam" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/veeam/veeamone" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/veeam/veeambetr" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/veeam/veeamman" );
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/veeam/veeamspc" );
/**
 * Inclue Les class d'appel a bladelogic
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/bladelogic" );
/**
 * Inclue Les class d'appel a vmware
 */
require_once $rep_APIWeb . "/vmware/config_vmware.php";
/**
 * Inclue Les class d'appel a aws_cloudwatch
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/aws_cloudwatch" );
/**
 * Inclue Cacti
 */
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_APIWeb . "/cacti" );
require_once "config_cacti.php";
/**
 * Inclue HP
 */
$rep_HP = $rep_APIWeb . "/HP";
$rep_sitescope = $rep_HP . "/sitescope";
$rep_HPOM = $rep_HP . "/HPOM";
$rep_Stars = $rep_HP . "/Stars";
$rep_UCMDB = $rep_HP . "/ucmdb";
set_include_path ( get_include_path () . PATH_SEPARATOR . $rep_HP . PATH_SEPARATOR . $rep_sitescope . PATH_SEPARATOR . $rep_HPOM . PATH_SEPARATOR . $rep_Stars . PATH_SEPARATOR . $rep_UCMDB );

?>