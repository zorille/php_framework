<?php
/**
 * Fichier de configuration du package Cacti
 *
 * @author dvargas
 * @package Lib
 * @subpackage Config
 */
namespace Zorille\framework;
//On creer l'objet $cacti_datas seulement si cacti_machines existe dans la liste d'option
//Obligatoire pour les class suivante
if ($liste_option->verifie_option_existe ( "cacti_machines" ) !== false) {
	$cacti_datas = cacti_datas::creer_cacti_datas ( $liste_option );
} else {
	$cacti_datas = false;
}

// Specifiquement pour cacti, on a des INCLUDES qui permettent de charger les APIs de Cacti
if (isset ( $INCLUDE_CACTI_DEVICE )) {
	if ($cacti_datas !== false && $liste_option->verifie_option_existe ( "cacti_env" ) !== false) {
		//no_http_headers = true;

		$liste_includes = $cacti_datas->getIncludesData ( $liste_option->getOption ( "cacti_env" ) );
		$path = $cacti_datas->getPathData ( $liste_option->getOption ( "cacti_env" ) );

		include_once ($path . $liste_includes ["global"]);
		include_once ($path . $liste_includes ["api_automation_tools"]);
		include_once ($path . $liste_includes ["utility"]);
		include_once ($path . $liste_includes ["api_data_source"]);
		include_once ($path . $liste_includes ["api_graph"]);
		include_once ($path . $liste_includes ["snmp"]);
		include_once ($path . $liste_includes ["data_query"]);
		include_once ($path . $liste_includes ["api_device"]);
	} else {
		abstract_log::onError_standard ( "Il faut un --cacti_env" );
		exit ( $fichier_log->renvoiExit () );
	}
} elseif (isset ( $INCLUDE_CACTI_ADDTREE )) {
	// Specifiquement pour cacti, on a des INCLUDES qui permettent de charger les APIs de Cacti
	if ($cacti_datas !== false && $liste_option->verifie_option_existe ( "cacti_env" ) !== false) {
		//no_http_headers = true;

		$liste_includes=$cacti_datas->getIncludesData($liste_option->getOption ( "cacti_env" ));
		$path=$cacti_datas->getPathData($liste_option->getOption ( "cacti_env" ));

		include_once ($path . $liste_includes["global"]);
		include_once ($path . $liste_includes["api_automation_tools"]);
		include_once ($path . $liste_includes["tree"]);
		include_once ($path . $liste_includes["api_tree"]);
	} else {
		abstract_log::onError_standard ( "Il faut un --cacti_env" );
		exit($fichier_log->renvoiExit());
	}
} elseif (isset ( $INCLUDE_CACTI_IMPORTTEMPLATE )) {
	// Specifiquement pour cacti, on a des INCLUDES qui permettent de charger les APIs de Cacti
	if ($cacti_datas !== false && $liste_option->verifie_option_existe ( "cacti_env" ) !== false) {
		//no_http_headers = true;

		$liste_includes=$cacti_datas->getIncludesData($liste_option->getOption ( "cacti_env" ));
		$path=$cacti_datas->getPathData($liste_option->getOption ( "cacti_env" ));

		include_once ($path . $liste_includes["global"]);
		include_once ($path . $liste_includes["import"]);
	} else {
		abstract_log::onError_standard ( "Il faut un --cacti_env" );
		exit($fichier_log->renvoiExit());
	}
} elseif ($cacti_datas !== false && $liste_option->verifie_option_existe ( "cacti_env" ) !== false) {
	// Specifiquement pour cacti, on a des INCLUDES qui permettent de charger les APIs de Cacti
	//no_http_headers = true;

	$liste_includes=$cacti_datas->getIncludesData($liste_option->getOption ( "cacti_env" ));
	$path=$cacti_datas->getPathData($liste_option->getOption ( "cacti_env" ));

	include_once ($path . $liste_includes["global"]);
	include_once ($path . $liste_includes["api_automation_tools"]);
}


/*
 * Code erreur cacti
 * 5000 CI n'existe pas
 * 5001 Config SNMP fausse
 * 5002 doublon en base
 * 5003 Pas de description
 * 5004 Pas d'IP
 * 5005 Pas de template
 * 5006 Mauvaise version de SNMP
 * 5007 Mauvais port SNMP
 * 5008 Erreur de timeout SNMP
 * 5009 Probleme de username/password de SNMPV3
 * 5010 Pas de version SNMP
 * 5011 Pas de community
 * 5012 Pas de AuthProto SNMPV3
 * 5013 Pas de PrivPass SNMPV3
 * 5014 Pas de PrivProto SNMPV3
 * 5015 Pas de Context SNMPV3
 * 5016 Pas de availability
 * 5017 Pas de notes
 * 5020 Erreur d'ajout/modification du CI
 * 5021 Erreur de suppression du CI
 * 5022 Erreur de validation SNMP du CI
 */

?>