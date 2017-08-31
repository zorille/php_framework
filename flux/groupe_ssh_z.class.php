<?php
/**
 * @author dvargas
 * @package Lib
 * 
*/
/**
 * class ssh_z<br>
 * 
 * Gere une liste de connexion SSH.
 * @package Lib
 * @subpackage Flux
*/
class groupe_ssh_z extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	*/
	var $liste_machine_distante = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	*/
	var $liste_connexion = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type groupe_ssh_z.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return groupe_ssh_z
	 */
	static function &creer_groupe_ssh_z(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new groupe_ssh_z ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return groupe_ssh_z
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 */
	function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		return true;
	}

	/**
	 * Ajoute une machine a la liste de machine distante
	 * @param string $machine_distante
	 * @param number $nb_max_connexion
	 * @return boolean
	 */
	function ajouter_machine($machine_distante, $nb_max_connexion = 20) {
		$liste_machine_distante = $this->getListeMachineDistante ();
		if (! isset ( $liste_machine_distante [$machine_distante] )) {
			$liste_machine_distante [$machine_distante] = array ();
		}
		
		$liste_machine_distante [$machine_distante] ["nb_max_connexion"] = $nb_max_connexion;
		return $this->setListeMachineDistante($liste_machine_distante);
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $machine_distante Nom de la machine a connecter.
	 * @param string $username Utilisateur de connexion.
	 * @param string $passwd Mot de passe pour la connexion.
	 * @param string $pubkey Cle public de l'utilisateur.
	 * @param string $privkey Cle privee de l'utilisateur.
	 * @param string $passphrase Passphrase de l'utilisateur.
	 * @param string $type_ssh_key Type dsa/rsa etc ...
	 * @param string $commande_ssh Chemin de la commande systeme ssh. 
	 * @param string $commande_scp Chemin de la commande systeme scp.
	*/
	function ajouter_connexion_ssh($machine_distante, $username = "echo", $passwd = "", $pubkey = "", $privkey = "", $passphrase = "", $type_ssh_key = "", $commande_ssh = "/usr/bin/ssh", $commande_scp = "/usr/bin/scp") {
		$CODE_RETOUR = false;
		if (isset ( $this->liste_connexion [$machine_distante] )) {
			if (! isset ( $this->liste_connexion [$machine_distante] ))
				$this->liste_connexion [$machine_distante] = array ();
			$pos = count ( $this->liste_connexion [$machine_distante] );
			if ($pos < $this->liste_machine_distante [$machine_distante] ["nb_max_connexion"]) {
				$this->liste_connexion [$machine_distante] [$pos] = ssh_z::creer_ssh_z ( $liste_option, $username, $passwd, $pubkey, $privkey, $passphrase, $type_ssh_key, $commande_ssh, $commande_scp, $this->getSortEnErreur () );
				array_push ( $this->liste_machine_distante [$machine_distante] ["pos_libre"], $pos );
				$CODE_RETOUR = true;
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Ouvre la connexion SSH sur une machine distante.
	 * @codeCoverageIgnore
	 * @param string $machine_distante Nom de la machine a connecter.
	 * @param int $port Port de connexion.
	 * @return Bool Renvoi TRUE si OK, FALSE sinon.
	*/
	function ssh_connect($machine_distante, $port = 22) {
		$CODE_RETOUR = false;
		if (isset ( $this->liste_connexion [$machine_distante] ) && count ( $this->liste_machine_distante [$machine_distante] ["pos_libre"] ) > 0) {
			$pos = array_shift ( $this->liste_machine_distante [$machine_distante] ["pos_libre"] );
			array_push ( $this->liste_machine_distante [$machine_distante] ["pos_utilise"], $pos );
			$CODE_RETOUR = $pos;
			$retour_local = $this->liste_connexion [$machine_distante] [$pos]->ssh_connect ( $machine_distante, $port );
			if ($retour_local === false)
				$CODE_RETOUR = $retour_local;
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Fermeture d'une connexion SSH.
	 * @codeCoverageIgnore
	*/
	function ssh_close($machine_distante, $pos) {
		$CODE_RETOUR = false;
		if (isset ( $this->liste_connexion [$machine_distante] ) && in_array ( $pos, $this->liste_machine_distante [$machine_distante] ["pos_utilise"] )) {
			$to_delete = array_search ( $pos, $this->liste_machine_distante [$machine_distante] ["pos_utilise"] );
			unset ( $this->liste_machine_distante [$machine_distante] ["pos_utilise"] [$to_delete] );
			array_push ( $this->liste_machine_distante [$machine_distante] ["pos_libre"], $pos );
			$this->liste_connexion [$machine_distante] [$pos]->ssh_close ( $machine_distante );
			$CODE_RETOUR = true;
		}
		
		return $CODE_RETOUR;
	}

	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeMachineDistante() {
		return $this->liste_machine_distante;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeMachineDistante($liste_machine_distante) {
		$this->liste_machine_distante = $liste_machine_distante;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeConnexion() {
		return $this->liste_connexion;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeConnexion($liste_connexion) {
		$this->liste_connexion = $liste_connexion;
		return $this;
	}

/******************************* ACCESSEURS ********************************/
}
?>