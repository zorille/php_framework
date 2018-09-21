<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class BusinessProcess
 *
 * @package Lib
 * @subpackage itop
 */
class BusinessProcess extends FunctionalCI {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type BusinessProcess. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return BusinessProcess
	 */
	static function &creer_BusinessProcess(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new BusinessProcess ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return BusinessProcess
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'BusinessProcess' ) 
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

	public function retrouve_BusinessProcess($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	/**
	 *
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return BusinessProcess
	 */
	public function creer_oql (
	    $name,
	    $fields = array()) {
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . " WHERE name='" . $name . "'" );
	}

	public function gestion_BusinessProcess($name, $org_name, $status, $business_criticity, $move2production, $description = '', $contacts_list = array(), $applicationSolution_list = array()) {
		$this ->onDebug ( __METHOD__, 1 );
		
		$params = array ( 
				'name' => $name, 
				'org_id' => $this ->getObjetItopOrganization () 
					->creer_oql ( $org_name ) 
					->getOqlCi (), 
				'status' => $status, 
				'business_criticity' => $business_criticity, 
				'description' => $description, 
				'move2production' => $move2production );
		if (! empty ( $contacts_list )) {
			$params ['contacts_list'] = $contacts_list;
		}
		if (! empty ( $applicationSolution_list )) {
			$params ['applicationsolutions_list'] = $applicationSolution_list;
		}
		return $this ->creer_oql ( $name ) 
			->creer_ci ( $name, $params );
	}

	/**
	 * Creer un lnkApplicationSolutionToBusinessProcess en fonction du BusinessProcess
	 * @param string $applicationsolution_id
	 * @param string $applicationsolution_name
	 * @return array
	 * @throws Exception
	 */
	public function creer_lnkApplicationSolutionToBusinessProcess($applicationsolution_id = '', $applicationsolution_name = '') {
		$lnkApplicationSolutionToBusinessProcess = array ();
		if (empty ( $this ->getId () )) {
			return $this ->onError ( "Il faut un ID a ce BusinessProcess" );
		}
		$lnkApplicationSolutionToBusinessProcess ['businessprocess_id'] = $this ->getId ();
		$tableau=$this ->getDonnees ();
		if (isset ( $tableau['name'] )) {
			$lnkApplicationSolutionToBusinessProcess ['businessprocess_name'] = $tableau['name'];
		}
		
		if (! empty ( $applicationsolution_id )) {
			$lnkApplicationSolutionToBusinessProcess ['applicationsolution_id'] = $applicationsolution_id;
		}
		if (! empty ( $applicationsolution_name )) {
			$lnkApplicationSolutionToBusinessProcess ['applicationsolution_name'] = $applicationsolution_name;
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
		$help [__CLASS__] ["text"] [] .= "BusinessProcess :";
		
		return $help;
	}
}
?>
