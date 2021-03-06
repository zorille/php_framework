<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class ApplicationSolution
 *
 * @package Lib
 * @subpackage itop
 */
class ApplicationSolution extends FunctionalCI {


	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type ApplicationSolution. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return ApplicationSolution
	 */
	static function &creer_ApplicationSolution(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ApplicationSolution ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return ApplicationSolution
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'ApplicationSolution' ) 
			->setObjetItopOrganization ( Organization::creer_Organization ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );
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
	 * Retrouve l'ApplicationSolution nomme $name
	 * @param string $name
	 * @return ApplicationSolution
	 * @throws Exception
	 */
	public function retrouve_ApplicationSolution($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	/**
	 * Cree un requete OQL de type SELECT pour retrouver $name
	 * @param string $name
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return ApplicationSolution
	 */
	public function creer_oql (
	    $name,
	    $fields = array()) {
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . " WHERE name='" . $name . "'" );
	}

	/**
	 * Cree ou retrouve un CI de type ApplicationSolution
	 * Necessite la class Organization
	 * @param string $name
	 * @param string $org_name
	 * @param string $status
	 * @param string $business_criticity
	 * @param array $functionalcis_list
	 * @param array $businessprocess_list
	 * @param string $move2production
	 * @param string $redundancy
	 * @return ApplicationSolution
	 * @throws Exception
	 */
	public function gestion_ApplicationSolution($name, $org_name, $status, $business_criticity, $functionalcis_list, $businessprocess_list, $move2production, $redundancy = '') {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'name' => $name, 
				'org_id' => $this ->getObjetItopOrganization () 
					->creer_oql ( $org_name ) 
					->getOqlCi (), 
				'status' => $status, 
				'business_criticity' => $business_criticity, 
				'functionalcis_list' => $functionalcis_list, 
				'move2production' => $move2production );
		if (! empty ( $businessprocess_list )) {
			$params ['businessprocess_list'] = $businessprocess_list;
		}
		if (! empty ( $redundancy )) {
			$params ['redundancy'] = $redundancy;
		}
		return $this ->creer_oql ( $name ) 
			->creer_ci ( $name, $params );
	}

	/**
	 * Creer un lnkApplicationSolutionToBusinessProcess en fonction de l'ApplicationSolution
	 * @param string $businessprocess_id
	 * @param string $businessprocess_name
	 * @return array
	 * @throws Exception
	 */
	public function creer_lnkApplicationSolutionToBusinessProcess($businessprocess_id = '', $businessprocess_name = '') {
		$lnkApplicationSolutionToBusinessProcess = array ();
		if (empty ( $this ->getId () )) {
			return $this ->onError ( "Il faut un ID a cette ApplicationSolution" );
		}
		$lnkApplicationSolutionToBusinessProcess ['applicationsolution_id'] = $this ->getId ();
		$tableau=$this ->getDonnees ();
		if (isset ( $tableau['name'] )) {
			$lnkApplicationSolutionToBusinessProcess ['applicationsolution_name'] = $tableau['name'];
		}
		
		if (! empty ( $businessprocess_id )) {
			$lnkApplicationSolutionToBusinessProcess ['businessprocess_id'] = $businessprocess_id;
		}
		if (! empty ( $businessprocess_name )) {
			$lnkApplicationSolutionToBusinessProcess ['businessprocess_name'] = $businessprocess_name;
		}
		
		return $lnkApplicationSolutionToBusinessProcess;
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
		$help [__CLASS__] ["text"] [] .= "ApplicationSolution :";
		
		return $help;
	}
}
?>
