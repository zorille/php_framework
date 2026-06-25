<?php
/**
 * Prepare une ligne de commande.
 * @author dvargas
 */
namespace Zorille\framework;

use Zorille\framework\relation_fichier_machine as relation_fichier_machine;
use Exception;
/**
 * class CommandLine
 * @package Lib
 * @subpackage Commandline
 */
class CommandLine extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $workspace = ".";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $cmd = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $log_retour = "";
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $nb_ligne_merged = array ();
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $ram_Mo = 0;
	/**
	 * var privee
	 * @access private
	 * @var int
	 */
	private $time = 0;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $numbers = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type CommandLine.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return CommandLine
	 * @throws Exception
	 */
	static function &creer_CommandLine(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): CommandLine
	{
		$objet = new CommandLine ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return CommandLine
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param bool $sort_en_erreur Prend les valeurs true/false.
	 * @param string $entete entete pour les logs.
	 */
	public function __construct($sort_en_erreur = false, string $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Creer une liste de fichier a traiter.<br>
	 * Cette fonction decoupe la liste si elle depasse 4000 entrees.
	 *
	 * @param array $liste_fichiers
	 * @param string $separateur separateur en tre les fichier.
	 * @param Bool $is_file_exist le fichier de la liste doit exister, sinon (false) il est remplace par un nom automatique.
	 * @return bool|string Lignes contenant la liste des fichiers separe par $separateur.
	 */
	public function creerListeFichiers(array $liste_fichiers, string $separateur = ",", bool $is_file_exist = false): bool|string
	{
		$liste_ligne_commande = false;
		$taille_liste_fichier = count ( $liste_fichiers );
		
		//Si la ligne est trop grande, il faut la splitter
		if (count ( $liste_fichiers ) > 0) {
			$liste_ligne_commande = "";
			//On creer des listes
			foreach ( $liste_fichiers as $fichier ) {
				//Si l'entree est vide on boucle
				if ($fichier === "") {
					continue;
				}
				//Si la liste final n'est pas vide, on ajoute le separateur
				if ($liste_ligne_commande != "") {
					$liste_ligne_commande .= $separateur;
				}
				
				//enfin on ajoute le fichier
				if ($is_file_exist) {
					$liste_ligne_commande .= $this->extraireFichier ( $fichier );
				} else {
					$liste_ligne_commande .= $this->RenvoieNomFichier ( $fichier );
				}
			}
		} elseif ($taille_liste_fichier == 0) {
			$this->onWarning ( "Il n'y a pas de fichier a traiter." );
		}
		
		return $liste_ligne_commande;
	}

	/**
	 * Renvoi un nom de fichier.<br>
	 * Par defaut, renvoi "fichier_{pid}_{random}" si le nom est vide.
	 *
	 * @param string|relation_fichier_machine $fichier Nom du fichier.
	 * @return relation_fichier_machine|string nom du fichier.
	 */
	public function renvoieNomFichier($fichier): \Zorille\framework\relation_fichier_machine|string
	{
		$nom_final = $this->extraireFichier ( $fichier );
		
		if ($nom_final === "") {
			$nom_final = "fichier_" . getmypid () . "_" . mt_rand ();
		}
		
		return $nom_final;
	}

	/**
	 * Renvoi le nom du fichier si ce fichier est dans un objet relation_fichier_machine.<br>
	 * sinon renvoi le fichier donne en argument.
	 *
	 * @param string|relation_fichier_machine $fichier Nom du fichier.
	 * @return relation_fichier_machine|string nom du fichier.
	 */
	public function extraireFichier($fichier): \Zorille\framework\relation_fichier_machine|string
	{
		//On accepte une structure standard de fichier
		if ($fichier instanceof relation_fichier_machine) {
			$nom_final = $fichier->renvoi_parametre_fichier ( "dossier" ) . "/" . $fichier->renvoi_parametre_fichier ( "nom" );
		} else {
			$nom_final = $fichier;
		}
		
		return $nom_final;
	}

	/**
	 * Separe les objet de la liste par un separateur.
	 *
	 * @param array $liste Liste des valeurs.
	 * @param string $separateur
	 * @param bool $ajoute_guillement
	 * @return string ligne forme avec le parametre.
	 * @throws Exception
	 */
	public function ConcatDataWithValue(array $liste, string $separateur = ",", bool $ajoute_guillement = true): string
	{
		$retour = "";
		if (is_array ( $liste )) {
			foreach ( $liste as $data ) {
				if ($data != "") {
					if ($retour == "") {
						$retour = $data;
					} else {
						$retour .= $separateur . $data;
					}
				}
			}
		} else {
			return $this->onError ( "La liste doit etre un tableau.", $liste );
		}
		
		if ($retour !== "" && $ajoute_guillement) {
			$retour = "\"" . $retour . "\"";
		}
		
		return $retour;
	}

	/**
	 * Creer une ligne "param" "$valeur".
	 * Enter description here ...
	 * @param string $param
	 * @param string $valeur
	 * @param Bool $mandatory parametre obligatoire ou non.
	 * @param String $separateur separateur entre le parametre et la valeur
	 * @return string|null
	 * @throws Exception
	 */
	public function AddParam(string $param, string $valeur, bool $mandatory = false, string $separateur = " "): ?string
	{
		if ($valeur !== false && $valeur !== "") {
			return " " . $param . $separateur . $valeur;
		} elseif ($mandatory) {
			//Si le sort en erreur est a false
			$this->setCmd ( "exit 1;" );
			return $this->onError ( "Le parametre " . $param . " est obligatoire." );
		}
		
		return null;
	}

	/**
	 * Execute la ligne de commande.
	 *
	 * @param string $service nom du service.
	 * @return int|false le code retour systeme de l'execution, false en cas d'erreur php.
	 * @throws Exception
	 */
	public function executeCommandLine(string $service): bool|int
	{
		$this->onInfo ( "Execute CMD : " . $this->getCmd () );
		if ($this->getCmd () === "") {
			$this->onDebug ( "CommandLine vide, retour FALSE", 2 );
			return false;
		}
		
		$var_return = fonctions_standards::applique_commande_systeme ( "cd " . $this->getWorkspace () . ";" . $this->getCmd (), false );
		$this->onDebug ( "executeCommandLine : " . print_r ( $var_return, true ), 2 );
		$retour = $var_return [0];
		if ($retour === 0) {
			$this->AfficheLogs ( $var_return );
			$this->recupereInfoDesLogs ( $var_return );
			array_shift ( $var_return );
			$this->setlogs ( $var_return );
		} else {
			return $this->onError ( "Le service " . $service . " s'est termine en erreur.", $var_return );
		}
		
		return $retour;
	}

	/********************* Post traitement ***********************/
	/**
	 * Recupere une liste d'information dans les logs des programmes C.
	 *
	 * @param array &$tableau_output Pointeur sur les logs de merge.
	 * @return Bool true si OK, false sinon.
	 */
	public function AfficheLogs(array $tableau_output): bool
	{
		if (is_array ( $tableau_output )) {
			foreach ( $tableau_output as $ligne ) {
				$this->onInfo ( $ligne );
			}
			return true;
		}
		return false;
	}

	/**
	 * Recupere une liste d'information dans les logs des programmes C.
	 *
	 * @param array &$tableau_output Pointeur sur les logs de merge.
	 * @return Bool true si OK, false sinon.
	 */
	public function recupereInfoDesLogs(array &$tableau_output): bool
	{
		if (is_array ( $tableau_output )) {
			foreach ( $tableau_output as $ligne ) {
				if (str_contains($ligne, "[Ram]")) {
					$this->setRamMo ( str_replace ( "[Ram]", "", $ligne ) );
				} elseif (str_contains($ligne, "[Lines]")) {
					$this->setNbLigne ( str_replace ( "[Lines]", "", $ligne ) );
				} elseif (str_contains($ligne, "[Time]")) {
					$this->setTime ( str_replace ( "[Time]", "", $ligne ) );
				}
			}
			return true;
		}
		return false;
	}

	/********************* Post traitement ***********************/
	
	/******************************* ACCESSEURS ********************************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getNbLigneParSerial($serial = "NoSerial") {
		if (isset ( $this->nb_ligne_merged [$serial] )) {
			return $this->nb_ligne_merged [$serial];
		}
		return 0;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNbLigne(): array|int
	{
		return $this->nb_ligne_merged;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNbLigne($nb_ligne_merged, $serial = "NoSerial"): static
	{
		$this->nb_ligne_merged [$serial] = $nb_ligne_merged;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRam(): int
	{
		return $this->ram_Mo;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRamMo($ram): static
	{
		if ($ram > $this->ram_Mo) {
			$this->ram_Mo = $ram;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRamOctet($ram): static
	{
		$fonctions_standards = new fonctions_standards ();
		$coef = $fonctions_standards->renvoi_coef_octet ( $ram );
		$ram_tempo = ceil ( ($ram [0] * $coef) / (1024 * 1024) );
		$this->setRamMo ( $ram_tempo );
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTime(): int
	{
		return $this->time;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTime($time): static
	{
		if ($time == "0") {
			$this->time = "1";
		} else {
			$this->time = $time;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getNumbers(): array
	{
		return $this->numbers;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setNumbers($nom, $numbers): static
	{
		if ($nom != "") {
			$this->numbers [$nom] = $numbers;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getWorkspace(): string
	{
		return $this->workspace;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWorkspace($workspace): static
	{
		if ($workspace != "") {
			$this->workspace = $workspace;
		}
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCmd(): string
	{
		return $this->cmd;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCmd($cmd): static
	{
		$this->cmd = $cmd;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getlogs(): string
	{
		return $this->log_retour;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setlogs($logs): static
	{
		$this->log_retour = $logs;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &addToCmd($cmd): static
	{
		if ($cmd != "") {
			$this->cmd .= $cmd;
		}
		
		return $this;
	}

	/******************************* ACCESSEURS ********************************/

	/**
	 * @static
	 * @codeCoverageIgnore
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Execute une ligne de commande et pre-traite le retour (ram/time/number of...)";
		
		return $help;
	}
}
