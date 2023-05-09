<?php

/**
 * Gestion de coservit.
 * @author dvargas
 */
namespace Zorille\coservit;

use Zorille\framework as Core;

/**
 * class Company
 *
 * @package Lib
 * @subpackage coservit
 */
class Company extends Companies {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $Hosts = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $Services = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Company. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return $this
	 */
	static function &creer_Company(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Company ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return $this
	 * @exception
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->champ_obligatoire_standard ()
			->setFormat ( 'Company' );
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
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Met les valeurs obligatoires par defaut pour cette class, sauf si des valeurs sont déjà présentes Format array('nom du champ obligatoire'=>false, ... )
	 * @return $this
	 */
	public function &champ_obligatoire_standard() {
		if (empty ( $this->getMandatory () )) {
			$this->setMandatory ( array (
					'id' => false
			) );
		}
		return $this;
	}

	/**
	 * Prepare les parametres standards d'un objet + org_name s'il existe
	 * @param array $parametres
	 * @return array liste des parametres au format coservit
	 */
	public function prepare_params_Company(
			$parametres) {
		$params = $this->prepare_standard_params ( $parametres );
		foreach ( $parametres as $champ => $valeur ) {
			switch ($champ) {
				default :
			}
		}
		return $params;
	}

	/**
	 * ******************************* Company URI ******************************
	 */
	public function item_id_uri() {
		if ($this->valide_item_id () == false) {
			return $this->onError ( "Il n'y pas d'id de Company selectionne" );
		}
		return $this->companies_list_uri () . '/' . $this->getId ();
	}

	public function company_tree_uri() {
		return $this->item_id_uri () . '/tree';
	}

	public function company_hosts_uri() {
		return $this->item_id_uri () . '/hosts';
	}

	public function company_services_uri() {
		return $this->item_id_uri () . '/services';
	}

	/**
	 * ******************************* Coservit Company *********************************
	 */
	/**
	 * @param \stdClass $liste_companies
	 * @return $this
	 */
	public function separe_donnees_companies_customers(
			$liste_companies) {
		$this->onDebug ( __METHOD__, 1 );
		$this->onDebug ( $liste_companies, 2);
		if (isset ( $liste_companies->children ) && ! empty ( $liste_companies->children )) {
			foreach ( $liste_companies->children as $children ) {
				$this->onDebug ( $children, 1 );
				$listeCompanies = $this->getCompanies ();
				$listeCompanies [$children->id] = $children;
				$this->setCompanies ( $listeCompanies )
					->separe_donnees_companies_customers ( $children );
			}
		}
		if (isset ( $liste_companies->clients ) && ! empty ( $liste_companies->clients )) {
			foreach ( $liste_companies->clients as $customer ) {
				$this->onDebug ( $customer, 1 );
				$listeClients = $this->getCustomers ();
				$listeClients [$customer->id] = $customer;
				$this->setCustomers ( $listeClients )
					->separe_donnees_companies_customers ( $customer );
			}
		}
		return $this;
	}

	/**
	 * Recupere la liste des companies et des clients sous la companie en parametre (cf: id)
	 * @param array $parametres Liste des parametres de la commande tree. ("id"=> x est un parametre obligatoire)
	 * @return $this
	 */
	public function recupere_company_tree(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Company ( $parametres );
		$this->onDebug ( $params, 1 );
		$liste_companies = $this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->getMethod ( $this->company_tree_uri (), $params );
		return $this->separe_donnees_companies_customers ( $liste_companies );
	}

	/**
	 * Recupere la liste des hosts sous la companie en parametre (cf: id)
	 * @param array $parametres Liste des parametres de la commande tree. ("id"=> x est un parametre obligatoire)
	 * @return $this
	 */
	public function recupere_company_hosts(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Company ( $parametres );
		$this->onDebug ( $params, 1 );
		$liste_hosts = $this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->getMethod ( $this->company_hosts_uri (), $params );
		$this->onDebug ( $liste_hosts, 2 );
		return $this->setHosts ( $liste_hosts );
	}

	/**
	 * Recupere la liste des hosts sous la companie en parametre (cf: id)
	 * @param array $parametres Liste des parametres de la commande tree. ("id"=> x est un parametre obligatoire)
	 * @return $this
	 */
	public function recupere_company_services(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->prepare_params_Company ( $parametres );
		$this->onDebug ( $params, 1 );
		$liste_services = $this->valide_mandatory_fields ()
			->getObjetCoservitWsclient ()
			->getMethod ( $this->company_services_uri (), $params );
		$this->onDebug ( $liste_services, 2 );
		return $this->setServices ( $liste_services );
	}
	
	public function retrouve_id_company($nom_company){
		foreach($this->getCompanies() as $company){
			if($company->name==$nom_company){
				return $company->id;
			}
		}
		foreach($this->getCustomers() as $company){
			if($company->name==$nom_company){
				return $company->id;
			}
		}
		return $this->onError("Cette companie n'existe pas : ".$nom_company,1);
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return array
	 */
	public function &getHosts() {
		return $this->Hosts;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setHosts(
			&$liste_Hosts) {
		$this->Hosts = $liste_Hosts;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return array
	 */
	public function &getServices() {
		return $this->Services;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setServices(
			&$liste_Services) {
		$this->Services = $liste_Services;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Company :";
		return $help;
	}
}
?>
