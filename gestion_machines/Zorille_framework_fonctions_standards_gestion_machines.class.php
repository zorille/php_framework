<?php
/**
 * @author dvargas
 * @package Lib
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class fonctions_standards_gestion_machines.
 * @package Lib
 * @subpackage Gestion_Machine
 */
class fonctions_standards_gestion_machines extends abstract_log {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type fonctions_standards_gestion_machines.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return fonctions_standards_gestion_machines
	 */
	static function &creer_fonctions_standards_gestion_machines(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		$objet = new fonctions_standards_gestion_machines ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return fonctions_standards_gestion_machines
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et set la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Set une valeur donnee pour un champ calculateur donnees.
	 *
	 * @param array &$calculateur Pointeur sur un calculateur.
	 * @param string $nom_case Nom de la case a mettre a jour.
	 * @param string $valeur Valeur a mettre a jour.
	 * @return TRUE
	 */
	public function update_calculateur(&$calculateur, $nom_case, $valeur) {
		$netname = $calculateur;
		if (is_array ( $calculateur ))
			$calculateur [$nom_case] = $valeur;
		else {
			$calculateur = array ();
			if (is_string ( $netname ))
				$calculateur ["netname"] = $netname;
			
			$calculateur [$nom_case] = $valeur;
		}
		
		return true;
	}

	/**
	 * Set une valeur donnee pour un champ calculateur donnees dans la liste des calculateurs.
	 *
	 * @param array &$liste_calculateurs Pointeur sur la liste des calculateurs.
	 * @param array &$liste Pointeur sur les valeurs a mettre a jour.
	 * @param string $nom_case Nom de la case a mettre a jour.
	 * @param string $valeur_defaut Valeur par defaut (optionnel).
	 * @return TRUE
	 */
	public function update_liste_calculateurs(&$liste_calculateurs, &$liste, $nom_case, $valeur_defaut) {
		$pos = 0;
		
		foreach ( $liste as $data ) {
			if (is_array ( $data ) && isset ( $data [$nom_case] )) {
				$this->update_calculateur ( $liste_calculateurs [$pos], $nom_case, $data [$nom_case] );
			} else {
				$this->update_calculateur ( $liste_calculateurs [$pos], $nom_case, str_replace ( "{POS}", $pos, $valeur_defaut ) );
			}
			$pos ++;
		}
		
		return true;
	}

	/**
	 * Verifie la presence d'une variable pour le calculateurs necessaires au traitement.<br>
	 * Retourne la liste d'option mise a jour par la ligne de commande dans le fichier de conf.
	 *
	 * @param array &$liste_calculateurs Pointeur sur la liste des calculateurs.
	 * @param string $option_ligne_commande Position de l'option en ligne de commande.
	 * @param string $valeur_defaut Valeur par defaut (optionnel).
	 * @return TRUE
	 */
	public function organise_variables_calculateurs(&$liste_calculateurs, $option_ligne_commande, $valeur_defaut = "") {
		
		//Si la variable est en ligne de commande
		if ($this->getListeOptions ()
			->verifie_option_existe ( "liste_" . $option_ligne_commande . "_calculateurs", true )) {
			$liste = $this->getListeOptions ()
				->getOption ( "liste_" . $option_ligne_commande . "_calculateurs" );
			if (! is_array ( $liste )) {
				$tempo = explode ( " ", $liste );
				$liste = $tempo;
			}
			foreach ( $liste as $pos => $data )
				$liste_finale [$pos] [$option_ligne_commande] = $data;
			
			$this->update_liste_calculateurs ( $liste_calculateurs, $liste_finale, $option_ligne_commande, $valeur_defaut );
			
			//Sinon, si la variable est en fichier de conf
		} elseif ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"liste_calculateurs",
				"calculateur" 
		), true )) {
			$liste = $this->getListeOptions ()
				->getOption ( array (
					"liste_calculateurs",
					"calculateur" 
			) );
			if (! is_array ( $liste )) {
				$tempo = $liste;
				$liste = array ();
				$liste [0] = $tempo;
			}
			$this->update_liste_calculateurs ( $liste_calculateurs, $liste, $option_ligne_commande, $valeur_defaut );
		} else {
			$data = array (
					$this->getListeOptions ()
						->getOption ( "liste_calculateurs" ) 
			);
			$this->update_liste_calculateurs ( $liste_calculateurs, $data, $option_ligne_commande, $valeur_defaut );
		}
		
		return true;
	}

	/**
	 * Parse les options passees en ligne de commande ou par xml et creer un tableau de calculateurs.<br>
	 * Include : $INCLUDE_FONCTIONS<br>
	 * Arguments reconnus :<br>
	 *  --liste_calculateurs=\"calculateur_1 calculateur_2 ... \"<br>
	 *  --liste_name_calculateurs=\"nom_calc_1 nom_calc_2 ... \"<br>
	 *  --liste_ip_calculateurs=\"ip_calc_1 ip_calc_2 ... \" <br>
	 *  --liste_username_calculateurs=\"username_calc_1 username_calc_2 ... \" <br>
	 *  --liste_password_calculateurs=\"password_calc_1 password_calc_2 ... \" <br>
	 *  --liste_ftppassword_calculateurs=\"ftppassword_calc_1 ftppassword_calc_2 ... \" <br>
	 *  --liste_diskspace_calculateurs=\"diskspace_calc_1 diskspace_calc_2 ... \" <br>
	 *  --liste_ramspace_calculateurs=\"ramspace_calc_1 ramspace_calc_2 ... \" <br>
	 *  --liste_maxramjob_calculateurs=\"maxramjob_calc_1 maxramjob_calc_2 ... \" <br>
	 *  --liste_cpuunit_calculateurs=\"cpuunit_calc_1 cpuunit_calc_2 ... \" <br>
	 *  --liste_mincpujob_calculateurs=\"mincpujob_calc_1 mincpujob_calc_2 ... \" <br>
	 *  --liste_maxcpujob_calculateurs=\"maxcpujob_calc_1 maxcpujob_calc_2 ... \" <br>
	 *
	 * @return array|false Renvoi un tableau de calculateurs ou FALSE en cas d'erreur.
	 */
	public function trouve_attribut_calculateur() {
		$liste_calculateurs = array ();
		
		if ($this->getListeOptions ()
			->verifie_option_existe ( "liste_calculateurs", true )) {
			$liste_calculateurs = $this->getListeOptions ()
				->getOption ( "liste_calculateurs" );
			if (! is_array ( $liste_calculateurs )) {
				$liste = explode ( " ", $liste_calculateurs );
			} else {
				if (isset ( $liste_calculateurs ["calculateur"] ) && is_array ( $liste_calculateurs ["calculateur"] ))
					$liste = $liste_calculateurs ["calculateur"];
				else
					$liste = $liste_calculateurs;
			}
			
			$liste_calculateurs = array ();
			$pos = 0;
			foreach ( $liste as $netname ) {
				if (is_array ( $netname ) && isset ( $netname ['netname'] )) {
					$liste_calculateurs [$pos] ["NetName"] = $netname ['netname'];
				} else {
					$liste_calculateurs [$pos] ["NetName"] = $netname;
				}
				
				$pos ++;
			}
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "Name", "CALCULATOR{POS}" );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "IP", "" );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "Username", "" );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "Password", "" );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "FTPPassword", "" );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "DiskSpace", 50000 );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "RamSpace", 10000 );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "MaxRamJob", 5000 );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "CPUUnit", 81 );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "MinCPUJob", 0 );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "MaxCPUJob", 41 );
			
			$this->organise_variables_calculateurs ( $liste_calculateurs, "MaxNbJob", 20 );
		} else {
			$liste_calculateurs = array ();
		}
		
		$this->onDebug ( $liste_calculateurs, 2 );
		return $liste_calculateurs;
	}

	/**
	 * Si un connexion vers compte est ouverte, on prend la liste des calculateurs et leurs attribues dedans.<br>
	 * Sinon on cherche des calculateurs dans la liste des parametres (cf trouve_attribut_calculateur).<br>
	 * Include : $INCLUDE_FONCTIONS<br>
	 *
	 * @param options &$lien_compte Pointeur sur une base de donnee type compte type gestion_bd_compte.
	 * @return true.
	 */
	public function creer_liste_calculateurs($ramdom_liste = true) {
		$pos = 0;
		$liste_calculateurs = array ();
		
		$liste_calculateurs = $this->trouve_attribut_calculateur ();
		
		if (is_array ( $liste_calculateurs ) && count ( $liste_calculateurs ) > 0) {
			
			if ($ramdom_liste) {
				//On rend la liste des calculateurs aleatoire
				$new_liste = array ();
				$nb_machine = count ( $liste_calculateurs );
				$num_depart = rand ( 0, $nb_machine );
				
				for($i = 0; $i < $nb_machine; $i ++) {
					$num_final = ($i + $num_depart) % $nb_machine;
					$new_liste [$i] = $liste_calculateurs [$num_final];
				}
			} else {
				$new_liste = $liste_calculateurs;
			}
			
			$calculateurs = calculateurs::creer_calculateurs ( $this->getListeOptions () );
			$calculateurs->charge_liste_calculateurs ( $new_liste );
		} else
			$calculateurs = false;
		$this->getListeOptions ()
			->setOption ( "calculateurs_compte", $calculateurs );
		
		$this->onDebug ( $calculateurs, 2 );
		return $calculateurs;
	}

	/**
	 * Renvoi une liste de machines.
	 *
	 * @param string $type Pour avoir le type de machine.
	 * @param string $nom_serveur Pour limiter a un serveur.
	 * @return array Liste de structure des machines.
	 */
	public function renvoi_liste_machines_standard($type = "", $nom_serveur = "") {
		$pos = 0;
		$CODE_RETOUR = array ();
		if (is_array ( $this->getListeOptions ()
			->getOption ( array (
				"filer",
				"liste_machines" 
		) ) )) {
			foreach ( $this->getListeOptions ()
				->getOption ( array (
					"filer",
					"liste_machines" 
			) ) as $machine ) {
				if ($type == "" || $type == $machine ["type"]) {
					if ($nom_serveur == "" || $nom_serveur == $machine ["netname"]) {
						$CODE_RETOUR [$pos] = $machine;
						$pos ++;
					}
				}
			}
		}
		
		return $CODE_RETOUR;
	}
}
?>