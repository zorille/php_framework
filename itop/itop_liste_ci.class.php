<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
/**
 * class itop_liste_ci
 *
 * @package iTop
 * @subpackage liste_ci
 */
class itop_liste_ci extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_ci = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var itop_wsclient_rest
	 */
	private $itop_wsclient_rest = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type itop_liste_ci. @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param itop_wsclient_rest $itop_webservice_rest Reference sur un objet itop_webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return itop_liste_ci
	 */
	static function &creer_itop_liste_ci (
			&$liste_option, 
			&$itop_webservice_rest, 
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new itop_liste_ci ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"itop_wsclient_rest" => $itop_webservice_rest 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return itop_liste_ci
	 */
	public function &_initialise (
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this ->setObjetItopWsclientRest ( $liste_class ["itop_wsclient_rest"] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 * @return true
	 */
	public function __construct (
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/************************************ Gestion de la liste CI par objet PHP **********************************/
	/**
	 * Ajoute un ci a la liste
	 * @param itop_ci $ci
	 * @return itop_liste_ci
	 * @throws Exception
	 */
	public function ajoute_ci (
			&$ci) {
		if (! $ci instanceof itop_ci) {
			return $this ->onError ( '$ci doit etre une instance de itop_ci' );
		}
		$liste_ci = $this ->getListeCi ();
		$liste_ci [$ci ->getFormat () . "::" . $ci ->getId ()] = $ci;
		$get_donnees = $ci ->getDonnees ();
		if (isset ( $get_donnees ["friendlyname"] )) {
			$liste_ci [$ci ->getFormat () . "::" . $get_donnees ["friendlyname"]] = &$liste_ci [$ci ->getFormat () . "::" . $ci ->getId ()];
		} elseif (! isset ( $get_donnees ["name"] )) {
			return $this ->onError ( "Pas de Name pour le ci de type " . $ci ->getFormat (), $ci ->getOqlCi () );
		} else {
			$liste_ci [$ci ->getFormat () . "::" . $get_donnees ["name"]] = &$liste_ci [$ci ->getFormat () . "::" . $ci ->getId ()];
		}
		return $this ->setListeCi ( $liste_ci );
	}

	public function valide_ci_existe (
			$ci) {
		if (! $ci instanceof itop_ci) {
			return $this ->onError ( '$ci doit etre une instance de itop_ci' );
		}
		$get_liste_ci = $this ->getListeCi ();
		return isset ( $get_liste_ci [$ci ->getFormat () . "::" . $ci ->getId ()] );
	}

	public function renvoie_ci (
			$ci) {
		if ($this ->valide_ci_existe ( $ci )) {
			$get_liste_ci = $this ->getListeCi ();
			return $get_liste_ci [$ci ->getFormat () . "::" . $ci ->getId ()];
		}
		return null;
	}

	public function retrouve_ci_par_nom (
			$nom_ci, 
			$type_ci) {
		$get_liste_ci = $this ->getListeCi ();
		if (isset ( $get_liste_ci [$type_ci . "::" . $nom_ci] )) {
			return $get_liste_ci [$type_ci . "::" . $nom_ci];
		}
		//		$get_donnees=$ci ->getDonnees ();
		// 		foreach ( $this ->getListeCi () as $type => $ci ) {
		// 			if (strpos ( $type, $type_ci ) === 0) {
		// 				if ($get_donnees['name'] == $nom_ci || (isset ( $ci ->getDonnees ()['friendlyname'] ) && $ci ->getDonnees ()['friendlyname'] == $nom_ci)) {
		// 					return $ci;
		// 				}
		// 			}
		// 		}
		return NULL;
	}

	/************************************ Gestion de la liste CI par objet PHP **********************************/
	/************************************ Gestion de la liste CI par requete OQL **********************************/
	public function recupere_CI (
			$type, 
			$liste_champ, 
			$field_reference = 'name') {
		$donnees_par_machine = $this ->getListeCi ();
		$this ->onInfo ( $type );
		$liste_cis = $this ->getObjetItopWsclientRest () 
			->core_get ( $type, 'SELECT ' . $type, $liste_champ );
		foreach ( $liste_cis ['objects'] as $ci ) {
			if (! isset ( $donnees_par_machine [$ci ['fields'] [$field_reference]] )) {
				$donnees_par_machine [$ci ['fields'] [$field_reference]] = array ();
			}
			$ci ['fields'] ['class'] = $ci ['class'];
			$donnees_par_machine [$ci ['fields'] [$field_reference]] [count ( $donnees_par_machine [$ci ['fields'] [$field_reference]] )] = $ci ['fields'];
		}
		return $this ->setListeCi ( $donnees_par_machine );
	}

	public function recupere_Farm (
			$liste_champ = "name,status,business_criticity,move2production") {
		return $this ->recupere_CI ( 'Farm', $liste_champ );
	}

	public function recupere_VirtualHost (
			$liste_champ = "name,finalclass,status,business_criticity,move2production") {
		return $this ->recupere_CI ( 'VirtualHost', $liste_champ );
	}

	public function recupere_VirtualMachine (
			$liste_champ = "name,business_criticity,move2production,osfamily_name,osversion_name,cpu,ram,virtualhost_name,virtualhost_id") {
		return $this ->recupere_CI ( 'VirtualMachine', $liste_champ );
	}

	public function recupere_Server (
			$liste_champ = "name,managementip,business_criticity,move2production,osfamily_name,osversion_name,cpu,ram") {
		return $this ->recupere_CI ( 'Server', $liste_champ );
	}

	public function recupere_Middleware (
			$liste_champ = "name,friendlyname,software_id_friendlyname,system_name") {
		return $this ->recupere_CI ( 'Middleware', $liste_champ, 'system_name' );
	}

	public function recupere_MiddlewareInstance (
			$liste_champ = "name,friendlyname,middleware_id_friendlyname") {
		$donnees_par_machine = $this ->getListeCi ();
		$this ->onInfo ( "MiddlewareInstance" );
		$liste_cis = $this ->getObjetItopWsclientRest () 
			->core_get ( 'MiddlewareInstance', 'SELECT MiddlewareInstance', $liste_champ );
		foreach ( $liste_cis ['objects'] as $ci ) {
			$donnees = explode ( " ", $ci ['fields'] ['middleware_id_friendlyname'] );
			$system_name = array_pop ( $donnees );
			if (! isset ( $donnees_par_machine [$system_name] )) {
				$donnees_par_machine [$system_name] = array ();
			}
			$ci ['fields'] ['class'] = $ci ['class'];
			$donnees_par_machine [$system_name] [count ( $donnees_par_machine [$system_name] )] = $ci ['fields'];
		}
		return $this ->setListeCi ( $donnees_par_machine );
	}

	public function recupere_PCSoftware (
			$liste_champ = "name,friendlyname,software_id_friendlyname,system_name") {
		return $this ->recupere_CI ( 'PCSoftware', $liste_champ, 'system_name' );
	}

	public function recupere_OtherSoftware (
			$liste_champ = "name,friendlyname,software_id_friendlyname,system_name") {
		return $this ->recupere_CI ( 'OtherSoftware', $liste_champ, 'system_name' );
	}

	public function recupere_WebServer (
			$liste_champ = "name,friendlyname,software_id_friendlyname,system_name") {
		return $this ->recupere_CI ( 'WebServer', $liste_champ, 'system_name' );
	}

	public function recupere_WebApplication (
			$liste_champ = "name") {
		$donnees_par_machine = $this ->getListeCi ();
		$this ->onInfo ( "WebApplication" );
		$liste_cis = $this ->getObjetItopWsclientRest () 
			->core_get ( 'WebApplication', 'SELECT WebApplication', $liste_champ );
		foreach ( $liste_cis ['objects'] as $ci ) {
			$datas = explode ( " ", $ci ['fields'] ["name"] );
			$last = array_pop ( $datas );
			if (! isset ( $donnees_par_machine [$last] )) {
				$donnees_par_machine [$last] = array ();
			}
			$ci ['fields'] ['class'] = $ci ['class'];
			$donnees_par_machine [$last] [count ( $donnees_par_machine [$last] )] = $ci ['fields'];
		}
		return $this ->setListeCi ( $donnees_par_machine );
	}

	public function recupere_IPInterface (
			$liste_champ = "name,friendlyname,ipaddress") {
		$donnees_par_machine = $this ->getListeCi ();
		$this ->onInfo ( "IPInterface" );
		$liste_cis = $this ->getObjetItopWsclientRest () 
			->core_get ( 'IPInterface', 'SELECT IPInterface', $liste_champ );
		foreach ( $liste_cis ['objects'] as $ci ) {
			$donnees = explode ( " ", $ci ['fields'] ['friendlyname'] );
			$system_name = array_pop ( $donnees );
			if (! isset ( $donnees_par_machine [$system_name] )) {
				$donnees_par_machine [$system_name] = array ();
			}
			$ci ['fields'] ['class'] = $ci ['class'];
			$donnees_par_machine [$system_name] [count ( $donnees_par_machine [$system_name] )] = $ci ['fields'];
		}
		return $this ->setListeCi ( $donnees_par_machine );
	}

	/************************************ Gestion de la liste CI par requete OQL **********************************/
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeCi () {
		return $this ->liste_ci;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCiParNom (
			$ci_name, 
			$type_ci) {
		return $this ->retrouve_ci_par_nom ( $ci_name, $type_ci );
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeCi (
			$liste_ci) {
		$this ->liste_ci = $liste_ci;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return itop_wsclient_rest
	 */
	public function &getObjetItopWsclientRest () {
		return $this ->itop_wsclient_rest;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopWsclientRest (
			&$itop_wsclient_rest) {
		$this ->itop_wsclient_rest = $itop_wsclient_rest;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help () {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "itop_liste_ci :";
		return $help;
	}
}
?>
