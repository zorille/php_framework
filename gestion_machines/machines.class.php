<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
/**
 * class machines<br> Gere l'attribution et la liberation des jobs sur plusieurs machines.<br> Necessite la class machine pour fonctionner.
 * @package Lib
 * @subpackage Gestion_Machine
 */
class machines extends abstract_log {
	/**
	 * @access private
	 * @var array
	 */
	private $liste_machine = array ();
	/**
	 * @ignore
	 *
	 * @access private
	 * @var machine
	 */
	private $machine_ref = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type machines.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return machines
	 */
	static function &creer_machines(
			&$liste_option,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		$objet = new machines ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return machines
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		$this->setObjetMachineRef ( machine::creer_machine ( $liste_class ["options"] ) );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Creer l'objet et set la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	public function __construct(
			$sort_en_erreur = "oui",
			$entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prend une liste de machines avec leurs caracteristiques (cf class machine) et cree la liste de machine avec la class machine
	 *
	 * @param array $liste liste de machine avec tous leurs attribues (cf class machine sans le s)
	 * @return Bool Renvoi TRUE si des machines sont creer, FALSE sinon.
	 * @throws Exception
	 */
	public function &charge_liste_machines() {
		if($this->getListeOptions()->verifie_option_existe ( "machines" )){
			$liste_machine = $this->getListeMachines ();
			foreach($this->getListeOptions()->getOption ( "machines" ) as $nom_machine=>$donnees_machine){
				$machine=clone $this->getObjetMachineRef();
				$liste_machine [$nom_machine] =$machine->retrouve_machine_param($nom_machine,"machines");
			}
			$this->setListeMachines($liste_machine);
		} else {
			return $this->onError ( "Il manque le parametre machines" );
		}
		if (count ( $this->getListeMachines () ) == 0) {
			return $this->onError ( "Pas de machines dans la liste des machines" );
		}
		$this->onDebug ( $this->getListeMachines (), 2 );
		return $this;
	}

	/**
	 * *********************** Accesseurs ***********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getListeMachines() {
		return $this->liste_machine;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeMachines(
			$liste_machine) {
		$this->liste_machine = $liste_machine;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return machine
	 */
	public function &getObjetMachineRef() {
		return $this->machine_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetMachineRef(
			$machine_ref) {
		$this->machine_ref = $machine_ref;
		return $this;
	}

	/**
	 * *********************** Accesseurs ***********************
	 */
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "";
		return $help;
	}
}
?>