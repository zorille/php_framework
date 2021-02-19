<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class DeliveryModel
 *
 * @package Lib
 * @subpackage itop
 */
class DeliveryModel extends ci {
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
	 * @codeCoverageIgnore
	 * Instancie un objet de type DeliveryModel.
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return DeliveryModel
	 */
	static function &creer_DeliveryModel(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new DeliveryModel ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient_rest" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * @codeCoverageIgnore
	 * Initialisation de l'objet
	 * @param array $liste_class
	 * @return DeliveryModel
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'DeliveryModel' )
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
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function retrouve_DeliveryModel(
			$name) {
		return $this->creer_oql ( array('name'=>$name) )
			->retrouve_ci ();
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

	/**
	 * Creer un CI de type DeliveryModel
	 * 'name', 'description', 'org_name'
 	 * @param array $parametres Liste des critères. Le nom de la case= le nom du champ itop, la valeur de la case est la valeur dans itop.
	 * @return DeliveryModel
	*/
	public function gestion_DeliveryModel(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params=array();
		$this->champ_obligatoire_standard();
		foreach($parametres as $champ=>$valeur) {
			if(isset($mandatory[$champ]) && !empty($valeur)) {
				$mandatory[$champ]=true;
			}
			switch ($champ) {
				case 'org_name':
					$params['org_id']=$this->getObjetItopOrganization ()
						->creer_oql ( $valeur )
						->getOqlCi ();
					break;
				default :
					$params[$champ]=$valeur;
			}
		}
		$this->valide_mandatory_fields();
		$this->creer_oql ( array('name'=>$params['name'], 'organization_name'=>$parametres['org_name']) )
			->creer_ci ( $params['name'], $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	 /**
	 * @codeCoverageIgnore
	 * @return Organization
	 */
	public function &getObjetItopOrganization() {
		return $this->Organization;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopOrganization(&$Organization) {
		$this->Organization = $Organization;
		
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
		$help [__CLASS__] ["text"] [] .= "DeliveryModel :";
		return $help;
	}
}
?>
