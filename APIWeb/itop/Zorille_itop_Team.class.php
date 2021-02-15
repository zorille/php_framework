<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class Team
 *
 * @package Lib
 * @subpackage itop
 */
class Team extends Contact {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Team. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Team
	 */
	static function &creer_Team(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Team ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Team
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'Team' );
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
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	* Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes
	* Format array('nom du champ obligatoire'=>false, ... )
	* @return Person
	*/
	public function champ_obligatoire_standard(){
		if(empty($this->getMandatory())) {
			$this->setMandatory(
				array(
					'name'=>false,
					'org_id'=>false
					)
				);
		}
		return $this;
	}

	public function retrouve_Team($name='', $email='', $org_id='') {
		return $this ->retrouve_Contact($name, $email, $org_id);
	}

	/**
	* Récupère une team existante suivant les critères données ou créer cette team si elle n'existe pas
	* @param array $parametres Liste des critères. Le nom de la case= le nom du champ itop, la valeur de la case est la valeur dans itop.
	* @return Team
	*/
	public function gestion_Team(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params=array();
		$this->champ_obligatoire_standard();
		foreach($parametres as $champ=>$valeur) {
			if(isset($mandatory[$champ]) && !empty($valeur)) {
				$mandatory[$champ]=true;
			}
			switch ($champ) {
				case 'org_id':
					$params[$champ]=$this->getObjetItopOrganization ()
						->creer_oql ( $parametres[$champ] )
						->getOqlCi ();
					break;
				default :
					$params[$champ]=$valeur;
			}
		}
		$this->valide_mandatory_fields();
		$this->creer_oql ( $params['name'], '', $params['org_id'] )
			->creer_ci ( $params['name'], $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Team :";
		
		return $help;
	}
}
?>
