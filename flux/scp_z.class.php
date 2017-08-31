<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class scp_z<br> Gere une copie SSH.
 * @package Lib
 * @subpackage Flux
 */
class scp_z extends ssh_z {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $cmd_scp;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $scp_type = 'cli';

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type scp_z. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return scp_z
	 */
	static function &creer_scp_z(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new scp_z ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return scp_z
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this ->retrouve_scp_z_param ();
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur. @codeCoverageIgnore
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Rempli les variables de scp_z a partir de liste_option (ligne de commande)
	 * @return scp_z
	 */
	public function retrouve_scp_z_param() {
		$this ->retrouve_ssh_z_param ();
		
		if ($this ->getCliDatas () === true) {
			#variables par ligne de commande
			$this ->setCmdScp ( $this ->getListeOptions () 
				->renvoi_variables_standard ( array (
					"ssh",
					"commande",
					"scp" ), "/usr/bin/scp" ) );
		}
		
		return $this;
	}

	/**
	 * Rempli les variables de ssh_z a partir d'un tableau
	 * @return scp_z
	 * @throws Exception
	 */
	public function prepare_scp_z() {
		$this ->onDebug ( __METHOD__, 1 );
		if ($this ->getCliDatas ()) {
			return $this ->onWarning ( "Parametre en ligne commande qui remplace les fichier XML" );
		}

		$this ->prepare_ssh_z ();
		$liste_params = $this ->getObjetFluxDatas () 
			->valide_presence_flux_data ( $this ->getMachineDistante () );
		
		$this ->onDebug ( $liste_params, 1 );
		if (isset ( $liste_params ["commande_scp"] )) {
			$this ->setCmdScp ( $liste_params ["commande_scp"] );
		}
		
		return $this;
	}

	/**
	 * Prepare et execute la commande bash scp
	 * @param string $send_type type de copie : envoie/recupere/dryrun.
	 * @param string $source Fichier(s)/Dossier(s) a copier.
	 * @param string $dest Chemin complet de la copie.
	 * @param string $affiche_erreur Doit-on afficher l'erreur si elle se produit ?
	 * @param string $option Option standard du scp (ex: -r en cas de de dossier).
	 * @return int Renvoie 0 si OK, 1 sinon.
	 * @throws Exception
	 */
	public function scp_cli($send_type, $source, $dest = "", $affiche_erreur = true, $option = "") {
		$this ->onDebug ( __METHOD__, 1 );
		switch ($send_type) {
			case 'envoie' :
				$CMD = $this ->creer_scp_send_cli ( $source, $dest, $option );
				break;
			case 'recupere' :
				$CMD = $this ->creer_scp_send_cli ( $source, $dest, $option );
				break;
			default :
				$CMD = "echo DRYRUN  SCP : " . $send_type;
		}
		return $this ->applique_commande ( $CMD, $affiche_erreur );
	}

	/**
	 * Creer une commande bash scp pour envoyer des fichiers/dossiers
	 * @param string $source Fichier(s)/Dossier(s) a copier.
	 * @param string $dest Chemin complet de la copie.
	 * @param string $option Option standard du scp (ex: -r ).
	 * @return string
	 */
	public function creer_scp_send_cli($source, $dest = "", $option = "") {
		$this ->onDebug ( __METHOD__, 1 );
		if ($dest == "")
			$dest = $source;
		if ($this ->getUsername () != "")
			$username = $this ->getUsername () . "@";
		if ($this ->getPrivkey () != "")
			$privkey = " -i " . $this ->getPrivkey ();
		return $this ->getCmdScp () . " -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no " . $option . $privkey . " " . $source . " " . $username . $this ->getMachineDistante () . ":" . $dest;
	}

	/**
	 * Creer une commande bash scp pour recuperer des fichiers/dossiers
	 * @param string $source Fichier(s)/Dossier(s) a recuperer.
	 * @param string $dest Chemin complet de la copie.
	 * @param string $option Option standard du scp (ex: -r ).
	 * @return string
	 */
	public function creer_scp_recv_cli($source, $dest = "", $option = "") {
		$this ->onDebug ( __METHOD__, 1 );
		if ($dest == "")
			$dest = $source;
		if ($this ->getUsername () != "")
			$username = $this ->getUsername () . "@";
		if ($this ->getPrivkey () != "")
			$privkey = " -i " . $this ->getPrivkey ();
		return $this ->getCmdScp () . " -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no " . $option . $privkey . " " . $username . $this ->getMachineDistante () . ":" . $source . " " . $dest;
	}

	/**
	 * Execute la commande bash
	 * @param string $CMD Commande a executer
	 * @param boolean $affiche_erreur Doit-on afficher l'erreur si elle se produit ?
	 * @return int Renvoie 0 si OK, 1 sinon.
	 * @throws Exception
	 */
	public function applique_commande($CMD, $affiche_erreur) {
		$this ->onDebug ( __METHOD__, 1 );
		$this ->onDebug ( $CMD, 1 );
		//try {
			exec ( $CMD, $output, $CODE_RETOUR );
// 		} catch ( Exception $e ) {
// 			$CODE_RETOUR = 1;
// 			$output = $e ->getMessage ();
// 		}
		
		// traitement des erreurs
		if ($CODE_RETOUR === true || $CODE_RETOUR === 0)
			$CODE_RETOUR = 0;
		else
			$CODE_RETOUR = 1;
		
		if ($CODE_RETOUR != 0 && $affiche_erreur) {
			return $this ->onError ( "Erreur durant la copie : ", $CMD );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Prepare et execute la commande bash scp
	 * @param string $send_type type de copie : envoie/recupere/dryrun.
	 * @param string $source Fichier a copier.
	 * @param string $dest Chemin complet et nom du fichier de destination.
	 * @param string $affiche_erreur Doit-on afficher l'erreur si elle se produit ?
	 * @param string $create_mode chmod au format hexa ex : 0644, NULL par defaut
	 * @return boolean Renvoie 0 si OK, 1 sinon.
	 * @throws Exception
	 */
	public function scp_php($send_type, $source, $dest = "", $affiche_erreur = true, $create_mode = NULL) {
		$this ->onDebug ( __METHOD__, 1 );
		try {
			switch ($send_type) {
				case 'envoie' :
					$retour = $this ->getObjetSsh2Commandes () 
						->ssh2_scp_send ( $this ->getSshConnexion (), $source, $dest, $create_mode );
						break;
				case 'recupere' :
					$retour = $this ->getObjetSsh2Commandes () 
						->ssh2_scp_recv ( $this ->getSshConnexion (), $source, $dest );
						break;
				default :
					$this ->onInfo ( "DRYRUN : transfert scp de " . $source . " vers " . $dest );
					$retour=true;
			}
		} catch ( Exception $e ) {
			if ($affiche_erreur) {
				return $this ->onError ( "Erreur durant le transfert scp", $e ->getMessage (), $e ->getCode () );
			}
		}
		
		if ($retour === true) {
			return 0;
		}
		return 1;
	}

	/**
	 * Fait une copie scp d'un fichier (pas de repertoire).
	 * @param string $send_type type de copie : envoie/recupere/dryrun.
	 * @param string $source Fichier a copier.
	 * @param string $dest Chemin complet de la copie.
	 * @param bool $affiche_erreur Doit-on afficher l'erreur si elle se produit ?
	 * @param string $option Option standard du scp (ex: -r ) ou chmod au format hexa ex : 0644, NULL par defaut.
	 * @return int Renvoie 0 si OK, 1 sinon.
	 */
	public function scp($send_type, $source, $dest = "", $affiche_erreur = true, $option = NULL) {
		$this ->onDebug ( __METHOD__, 1 );
		
		switch ($this ->getScpType ()) {
			case "php" :
				$CODE_RETOUR = $this ->scp_php ( $send_type, $source, $dest, $affiche_erreur, $option );
				break;
			case "cli" :
			default :
				$CODE_RETOUR = $this ->scp_cli ( $send_type, $source, $dest, $affiche_erreur, $option );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Renvoie le type (cli|php) cli par defaut cli = Command Line Interface : command shell scp php : ssh2_scp_send or ssh2_scp_recv
	 * @param string $value
	 * @return number string
	 */
	public function retrouve_Type($value) {
		$this ->onDebug ( __METHOD__, 1 );
		
		switch (strtolower ( $value )) {
			case "php" :
				return "php";
			case "cli" :
			default :
		}
		
		return "cli";
	}

	/**
	 * **************** Accesseurs *************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getCmdScp() {
		return $this->cmd_scp;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCmdScp($cmd_scp) {
		$this->cmd_scp = $cmd_scp;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getScpType() {
		return $this->scp_type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setScpType($type) {
		$this->scp_type = $this ->retrouve_Type ( $type );
		return $this;
	}

	/**
	 * **************** Accesseurs *************************
	 */
	
	/**
	 * **************** Statics *************************
	 */
	
	/**
	 * **************** Statics *************************
	 */
	
	/**
	 *
	 * @static @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoie le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des copies SCP";
		$help [__CLASS__] ["text"] [] .= "<ssh using=\"oui\" sort_en_erreur=\"oui\">";
		$help [__CLASS__] ["text"] [] .= " <commande_scp>/usr/bin/scp</commande_scp>";
		$help [__CLASS__] ["text"] [] .= "</ssh>";
		
		return $help;
	}
}
?>