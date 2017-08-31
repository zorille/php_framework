<?php

/**
 * @author dvargas
 * @package Lib
 * @subpackage Flux
*/

/**
 * Permet d'ouvrir le pipe du serveur.
 * @codeCoverageIgnore
 * @param pipe &$pipe Pointeur sur un objet pipe.
 * @return Bool TRUE si ok, FALSE sinon.
*/
function ouvrir_pipe_ssh(&$liste_option, &$pipe, $nom_pipe = "/tmp/ssh_zpipe.pipe") {
	if (repertoire::tester_repertoire_existe ( dirname ( $nom_pipe ) ) != true)
		repertoire::creer_nouveau_repertoire ( dirname ( $nom_pipe ) );
	$pipe = pipe::creer_pipe ( $liste_option, $nom_pipe );
	$CODE_RETOUR = $pipe->init_serveur ();
	
	return $CODE_RETOUR;
}

/**
 * Permet de lire le pipe du serveur.<br>
 * Si le message = "close", le pipe se ferme.
 * @codeCoverageIgnore
 * @param logs &$fichier_log Pointeur sur un objet logs pour l'affichage.
 * @param pipe &$pipe Pointeur sur un objet pipe.
 * @return true
*/
function lire_pipe_ssh(&$fichier_log, &$pipe) {
	$message = "no_mesg";
	$liste_ssh = array ();
	//block and read from the pipe
	while ( $message != "close" ) {
		@usleep ( 10 );
		$message = "";
		$message = $pipe->lire ();
		if (trim ( $message ) != "close" && $message != "")
			traite_message_ssh ( $fichier_log, $message, $liste_ssh );
	}
	
	return true;
}

/**
 * Permet de traiter les demandes.
 * @codeCoverageIgnore
 * @param logs &$fichier_log Pointeur sur un objet logs pour l'affichage.
 * @param string $message Message lu sur le pipe au format db|fonction|donnee.
 * @param array &$liste_ssh tableau de pointeur sur une liste d'objet ssh.
 * @return true
*/
function traite_message(&$fichier_log, $message, &$liste_ssh) {
	$liste_donnees = explode ( "||", $message );
	switch ($liste_donnees [0]) {
		case "creer" :
			creer_connexion_ssh_serveur ( $liste_donnees, $liste_ssh );
			break;
		case "connect" :
			connecter_ssh ( $liste_donnees, $liste_ssh );
			break;
		case "commande" :
			commande_ssh ( $liste_donnees, $liste_ssh );
			break;
		default :
	}
	return true;
}

/**
 * Permet de creer les connexions.
 * @codeCoverageIgnore
 * @param string $message Message lu sur le pipe au format db|fonction|donnee.
 * @param array &$liste_ssh tableau de pointeur sur une liste d'objet ssh.
 * @return true
*/
function creer_connexion_ssh_serveur(&$liste_donnees, &$liste_ssh) {
	if (count ( $liste_donnees ) == 11) {
		$machine_distante = $liste_donnees [1];
		if (! isset ( $liste_ssh [$liste_donnees [1]] )) {
			$liste_ssh [$liste_donnees [1]] = ssh_z::creer_ssh_z ( $liste_option, $liste_donnees [2], $liste_donnees [3], $liste_donnees [4], $liste_donnees [5], $liste_donnees [6], $liste_donnees [7], $liste_donnees [8], $liste_donnees [9], $liste_donnees [10] );
		}
	}
	
	return true;
}

/**
 * Permet de se connecter.
 * @codeCoverageIgnore
 * @param string $message Message lu sur le pipe au format db|fonction|donnee.
 * @param array &$liste_ssh tableau de pointeur sur une liste d'objet ssh.
 * @return true
*/
function connecter_ssh(&$liste_donnees, &$liste_ssh) {
	if (count ( $liste_donnees ) == 3) {
		if (! isset ( $liste_ssh [$liste_donnees [0]] )) {
			$CODE_RETOUR = $liste_ssh [$liste_donnees [0]]->ssh_connect ( $liste_donnees [0], $liste_donnees [2] );
		}
	}
	
	return true;
}

/**
 * Permet d'execute une commande.
 * @codeCoverageIgnore
 * @param string $message Message lu sur le pipe au format db|fonction|donnee.
 * @param array &$liste_ssh tableau de pointeur sur une liste d'objet ssh.
 * @return true
*/
function commande_ssh(&$liste_donnees, &$liste_ssh) {
	if (count ( $liste_donnees ) == 3) {
		if (! isset ( $liste_ssh [$liste_donnees [0]] )) {
			$CODE_RETOUR = $liste_ssh [$liste_donnees [0]]->ssh_commande ( $liste_donnees [1], $liste_donnees [2] );
		}
	}
	
	return true;
}

/**
 * Cree le serveur ssh.
 * @codeCoverageIgnore
 * @param logs &$fichier_log Pointeur sur un objet logs pour l'affichage.
 * @param options &$liste_option Pointeur sur les arguments.
 * @param array &$liste_erreurs Pointeur sur les erreurs de rotate.
 * @return TRUE
*/
function lance_serveur_ssh(&$fichier_log, &$liste_option, &$liste_erreurs) {
	$CODE_RETOUR = true;
	$fork_database = fork::creer_fork ( $liste_option );
	$pid = $fork_database->fork_local ();
	if ($pid == 2) {
		//on creer le pipe
		$pipe = "";
		$CODE_RETOUR = ouvrir_pipe_ssh ( $pipe, $liste_option->getOption ( "nom_pipe_ssh" ) );
		
		if ($CODE_RETOUR) {
			lire_pipe_ssh ( $fichier_log, $pipe );
			
			$pipe->close_serveur ();
		} else {
			abstract_log::onError_standard ( "Ouverture du pipe impossible.", "" );
		}
		
		exit ( 0 );
	} elseif ($pid != 1) {
		abstract_log::onError_standard ( "Erreur lors du fork pour le serveur ssh.", "" );
		$liste_erreurs [count ( $liste_erreurs )] = "Erreur lors du fork pour le serveur ssh.";
		$fork_database = false;
	}
	
	sleep ( 1 );
	return $fork_database;
}

/**
 * Ferme le serveur SSH.
 * @codeCoverageIgnore
 * @param logs &$fichier_log Pointeur sur un objet logs pour l'affichage.
 * @param options &$liste_option Pointeur sur les arguments.
 * @param array &$liste_erreurs Pointeur sur les erreurs de rotate.
 * @param array &$fork_database Pointeur sur processus fils de database.
 * @return TRUE
*/
function ferme_serveur_ssh(&$fichier_log, &$liste_option, &$liste_erreurs, &$fork_database) {
	abstract_log::onInfo_standard ( "Fermeture du pipe." );
	$CODE_RETOUR = true;
	$fork_fermeture = fork::creer_fork ( $liste_option );
	$pid = $fork_fermeture->fork_local ();
	if ($pid == 2) {
		//on creer le pipe
		$pipe = pipe::creer_pipe ( $liste_option, $liste_option->getOption ( "nom_pipe_ssh" ) );
		$pipe->init_client ();
		$pipe->ecrit ( "close" );
		$pipe->close ();
		
		exit ( 0 );
	} elseif ($pid != 1) {
		abstract_log::onError_standard ( "Erreur lors du fork pour le serveur SSH.", "" );
		$liste_erreurs [count ( $liste_erreurs )] = "Erreur lors du fork pour la fermeture du pipe.";
		$fork_fermeture = false;
	}
	
	sleep ( 1 );
	abstract_log::onInfo_standard ( "Pipe ferme." );
	return $CODE_RETOUR;
}

?>
