<?php
/**
 * @author dvargas
 * @package Lib
 * 
*/
/**
 * class shared_memory<br>
 * @codeCoverageIgnore
 * Gere la memoire partagee.(NON TESTE)
 * @package Lib
 * @subpackage Fork
*/
class shared_memory extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	*/
	var $fichier = "FILE";
	/**
	 * var privee
	 * @access private
	 * @var string
	*/
	var $proj = "j";
	/**
	 * var privee
	 * @access private
	 * @var int
	*/
	var $shm_key;
	/**
	 * var privee
	 * @access private
	 * @var int
	*/
	var $shm_id;
	/**
	 * var privee
	 * @access private
	 * @var int
	*/
	var $mutex;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type shared_memory.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $fichier Fichier virtuel de partage.
	 * @param string $proj Non utilise.
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return shared_memory
	 */
	static function &creer_shared_memory(&$liste_option, $fichier = __FILE__, $proj = "", $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new shared_memory ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return shared_memory
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * 
	 * @param string $fichier Fichier virtuel de partage.
	 * @param string $proj Non utilise.
	 * @param Bool $sort_en_erreur Prend les valeurs true/false.
	*/
	function __construct($fichier = __FILE__, $proj = "", $sort_en_erreur = false) {
		$this->setFichier($fichier);
		$this->setProj(chr ( 4 ));
		
		//Gestion de abstract_log
		parent::__construct ( "SHARED MEM", $sort_en_erreur );
		
		return $this;
	}

	/**
	 * Cree la zone de partage.
	 * 
	 * @return Bool TRUE si OK, FALSE sinon.
	 * @throws Exception
	*/
	function initialise() {
		$this->setShmKey( ftok ( $this->getFichier(), $this->getProj() ));
		if ($this->getShmKey() == - 1) {
			return $this->onError ( "Erreur lors de l'initialisation." );
		}
		
		return true;
	}

	/**
	 * Ouvre la zone de partage.
	*/
	public function ouvrir($taille = 1000000, $perm = 0644) {
		$this->setShmId( shm_attach ( $this->getShmKey(), $taille, $perm ));
	}

	/**
	 * Ferme la zone de partage.
	*/
	function close() {
		//shm_detach($this->getShmId());
		shm_remove ( $this->getShmId() );
	}

	/**
	 * calcul la taille d'un variable.
	 * 
	 * @param string $donnees Donnees a mettre en memoire
	*/
	static public function calcul_taille($donnees) {
		return (((strlen ( serialize ( $donnees ) ) + (4 * PHP_INT_SIZE)) / 4) * 4) + 4;
	}

	/**
	 * Ecrit dans une variable de la zone de partage.
	 * 
	 * @param string $nom_var Nom de la variable.
	 * @param string $valeur Donnee a ecrire.
	*/
	function ecrit_variable($nom_var, $valeur) {
		shm_put_var ( $this->getShmId(), $nom_var, $valeur );
	}

	/**
	 * Lit une variable dans la zone de partage.
	 *
	 * @param string $nom Nom de la variable.
	 * @return string Valeur de la variable.
	*/
	function lire_variable($nom_var) {
		print_r ( shm_get_var ( $this->getShmId(), $nom_var ) );
		
		return $local;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function __destruct() {
	}
	
	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function &getShmKey() {
		return $this->shm_key;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setShmKey($shm_key) {
		$this->shm_key = $shm_key;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getShmId() {
		return $this->shm_id;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setShmId($shm_id) {
		$this->shm_id = $shm_id;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getFichier() {
		return $this->fichier;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setFichier($fichier) {
		$this->fichier = $fichier;
		return $this;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function getProj() {
		return $this->proj;
	}
	
	/**
	 * @codeCoverageIgnore
	 */
	public function &setProj($proj) {
		$this->proj = $proj;
		return $this;
	}
	/******************************* ACCESSEURS ********************************/
} //Fin de la class
?>