<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class socket<br>
 * 
 * Gere une socket reseau.
 * @package Lib
 * @subpackage Flux
 */
class socket extends abstract_log {
	/**
 	 * var privee
	 * @access private
	 * @var handler
	 */
    private $connexion;
    /**
     * var privee
	 * @access private
     * @var handler
     */
    private $socket;
    /**
     * var privee
	 * @access private
     * @var string
     */
    private $nom_socket;
    /**
     * var privee
	 * @access private
     * @var string
     */
    private $port_socket;
    /**
     * var privee
	 * @access private
     * @var string
     */
    private $type_socket;
    /**
     * var privee
	 * @access private
     * @var string
     */
    private $identite;

    /*********************** Creation de l'objet *********************/
    /**
     * Instancie un objet de type socket.
     * @codeCoverageIgnore
     * @param options $liste_option Reference sur un objet options
     * @param string $nom_socket Nom de la socket.
     * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
     * @param string $entete Entete des logs de l'objet
     * @return socket
     */
    static function &creer_socket(&$liste_option, $nom_socket="/tmp/zsocket.sock",$port_socket="",$type_socket="unix",$sort_en_erreur = false, $entete = __CLASS__) {
    	$objet = new socket ( $nom_socket,$port_socket,$type_socket,$sort_en_erreur, $entete );
    	$objet->_initialise ( array (
    			"options" => $liste_option
    	) );
    
    	return $objet;
    }
    
    /**
     * Initialisation de l'objet
     * @codeCoverageIgnore
     * @param array $liste_class
     * @return socket
     */
    public function &_initialise($liste_class) {
    	parent::_initialise($liste_class);
    	return $this;
    }
    
    /*********************** Creation de l'objet *********************/
    
    /**
     * Creer l'objet et prepare la valeur du sort_en_erreur.
     * @codeCoverageIgnore
     * @param string $nom_socket Nom de la socket.
     * @param string $sort_en_erreur Prend les valeurs true/false.
     */
    public function __construct($nom_socket="/tmp/zsocket.sock",$port_socket="",$type_socket="unix",$sort_en_erreur=true, $entete = __CLASS__) {
    //Gestion de abstract_log
        parent::__construct( $entete,$sort_en_erreur);

        $this->socket=false;
        $this->nom_socket=$nom_socket;
        $this->port_socket=$port_socket;
        $this->type_socket=$type_socket;
    }

    /**
     * Ouvre la socket.<br>
     * @codeCoverageIgnore
     * @return Bool Renvoi TRUE si OK, FALSE sinon.
     */
    public function init($retry=1,$timeout=30,$retry_time=500000) {
        $errno=61;
        $errstr="";
        $tentative=0;

        $nom_complet=$this->type_socket."://".$this->nom_socket;

        while($retry>$tentative && $this->socket===false && ($errno==61 || $errno==54)) {
            $this->socket=@fsockopen($nom_complet,$this->port_socket, $errno, $errstr,$timeout);
            if(!$this->socket) {
                $tentative++;
                usleep($retry_time);
            }
        }

        if(!$this->socket) {
            return $this->onError($errstr." (".$errno.") sur ".$nom_complet);
            $CODE_RETOUR=false;
        } else {
            $CODE_RETOUR=true;
        }

        return $CODE_RETOUR;

    }

    /**
     * Active ou desactive le "No Hang Up" sur la socket.
     * @codeCoverageIgnore
     * @param Bool $active TRUE active le NOHANGUP, FALSE le des-active.
     * @return Bool Renvoi TRUE si OK, FALSE sinon.
     */
    public function active_NOHUP($active=true) {
        if($active) {
            $CODE_RETOUR=stream_set_blocking($this->socket,0);
        } else {
            $CODE_RETOUR=stream_set_blocking($this->socket,1);
        }

        if($CODE_RETOUR===false) {
            return $this->onError("Erreur lors du set_blocking");
        }

        return $CODE_RETOUR;
    }


    /**
     * Active ou desactive le "No Hang Up" sur la socket.
     * @codeCoverageIgnore
     * @param Bool $active TRUE active le NOHANGUP, FALSE le des-active.
     * @return Bool Renvoi TRUE si OK, FALSE sinon.
     */
	public function setTimeout($timeout) {
        stream_setTimeout($this->socket,$timeout);

        return true;
    }

    /**
     * Active ou desactive le "No Hang Up" sur la socket.
     * @codeCoverageIgnore
     * @param Bool $active TRUE active le NOHANGUP, FALSE le des-active.
     * @return Bool Renvoi TRUE si OK, FALSE sinon.
     */
    public function renvoi_infos() {
        $info = stream_get_meta_data($this->socket);

        return $info;
    }

    /**
     * Lit les donnees sur la socket.
     * @codeCoverageIgnore
     * @return string Donnees lue.
     */
    public function lire() {
        $donnee_recu="";
        $char="";
        if(is_resource($this->socket)) {
            while (!feof($this->socket)) {
                $char=fgets($this->socket);
                $donnee_recu .= $char;
            }
            $donnee_recu=trim($donnee_recu);
        }

        return $donnee_recu;
    }

    /**
     * Ecrit des donnees sur la socket.
     * @codeCoverageIgnore
     * @param string $message Donnees a ecrire.
     */
    public function ecrit($message) {
        $retour=fputs($this->socket,$message."\n");
        if ($retour===false) {
            return $this->onError("Impossible d'ecrire sur la socket.");
        }
        @ob_flush();
        @flush();

        return $retour;
    }

    /**
     * Ferme une socket.
     * @codeCoverageIgnore
     */
    public function close_connexion() {
        fclose($this->socket);
        $this->socket=false;
    }

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
}
?>