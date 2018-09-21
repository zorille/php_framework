<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
use \Exception as Exception;
/**
 * class FunctionalCI
 *
 * @package Lib
 * @subpackage itop
 */
class FunctionalCI extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var Organization
	 */
	private $Organization = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type FunctionalCI. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return FunctionalCI
	 */
	static function &creer_FunctionalCI (
			&$liste_option, 
			&$webservice_rest, 
			$sort_en_erreur = false, 
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new FunctionalCI ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return FunctionalCI
	 */
	public function &_initialise (
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this ->setFormat ( 'FunctionalCI' );
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

	/**
	 * 
	 * @param string $name
	 * @return FunctionalCI
	 */
	public function retrouve_FunctionalCI (
			$name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	/**
	 * 
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return FunctionalCI
	 */
	public function creer_oql (
			$name, 
			$fields = array()) {
		return $this ->setOqlCi ( "SELECT " . $this ->getFormat () . " WHERE name='" . $name . "'" . $this->prepare_oql_fields($fields) );
	}

	/**
	 * Creer un lien itop de type lnkApplicationSolution vers un FunctionalCI
	 * @param string $friendlyname
	 * @param string $functionalci_id_friendlyname
	 * @return array
	 */
	public function creer_lnkApplicationSolutionToFunctionalCI (
			$friendlyname = '', 
			$functionalci_id_friendlyname = '') {
		$lnkApplicationSolutionToFunctionalCI = $this ->creer_lnkToFunctionalCI ();
		if (! empty ( $friendlyname )) {
			$lnkApplicationSolutionToFunctionalCI ['friendlyname'] = $friendlyname;
		}
		if (! empty ( $functionalci_id_friendlyname )) {
			$lnkApplicationSolutionToFunctionalCI ['functionalci_id_friendlyname'] = $functionalci_id_friendlyname;
		}
		$lnkApplicationSolutionToFunctionalCI ['functionalci_id_finalclass_recall'] = $this ->getFormat ();
		return $lnkApplicationSolutionToFunctionalCI;
	}

	/**
	 * Creer un lnkContactToFunctionalCI en fonction du CI
	 * @param string $contact_id
	 * @param string $contact_name
	 * @return array lnkContactToFunctionalCI
	 */
	public function creer_lnkContactToFunctionalCI (
			$contact_id = '', 
			$contact_name = '') {
		$lnkContactToFunctionalCI = $this ->creer_lnkToFunctionalCI ();
		if (! empty ( $contact_id )) {
			$lnkContactToFunctionalCI ['contact_id'] = $contact_id;
		}
		if (! empty ( $contact_name )) {
			$lnkContactToFunctionalCI ['contact_name'] = $contact_name;
		}
		return $lnkContactToFunctionalCI;
	}

	/**
	 * Creer un lnkToFunctionalCI en fonction du CI
	 * @return array ['functionalci_id']/['functionalci_name']
	 */
	public function creer_lnkToFunctionalCI () {
		$lnkToFunctionalCI = array ();
		if ($this ->getId ()) {
			$lnkToFunctionalCI ['functionalci_id'] = $this ->getId ();
		}
		$tableau = $this ->getDonnees ();
		if (isset ( $tableau ['name'] )) {
			$lnkToFunctionalCI ['functionalci_name'] = $tableau ['name'];
		}
		return $lnkToFunctionalCI;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopOrganization () {
		return $this ->Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization (
			&$Organization) {
		$this ->Organization = $Organization;
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
		$help [__CLASS__] ["text"] [] .= "FunctionalCI :";
		return $help;
	}
}
?>
