<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class Organization
 *
 * @package Lib
 * @subpackage itop
 */
class Organization extends ci {
	/**
	 * var privee
	 *
	 * @access private
	 * @var $DeliveryModel
	 */
	private $DeliveryModel = null;
	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Organization. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Organization
	 */
	static function &creer_Organization(&$liste_option, &$webservice_rest, $sort_en_erreur = false, $entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Organization ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array ( 
				"options" => $liste_option, 
				"wsclient_rest" => $webservice_rest ) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Organization
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		return $this ->setFormat ( 'Organization' )
			->setObjetItopDeliveryModel ( DeliveryModel::creer_DeliveryModel ( $liste_class ['options'], $liste_class ['wsclient_rest'] ) );;
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
	* @return Organization
	*/
	public function champ_obligatoire_standard(){
		if(empty($this->getMandatory())) {
			$this->setMandatory(
				array(
					'name'=>false
					)
				);
		}
		return $this;
	}
	
	/**
	 * Retrouve une organisation
	 * @return Organization
	 */
	public function retrouve_Organization($name) {
		return $this ->creer_oql ( $name ) 
			->retrouve_ci ();
	}

	/**
	 *
	 * @param string $name Nom du CI
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return Organization
	 */
	public function creer_oql (
			$name,
			$fields = array()) {
		$fields['name']=$name;
		return parent::creer_oql ( $fields );
	}

	/**
	 * Creer un CI de type Organization
	 * 'name', 'code', 'status', 'parent_name', 'deliverymodel_name'
 	 * @param array $parametres Liste des critères. Le nom de la case= le nom du champ itop, la valeur de la case est la valeur dans itop.
	 * @return Organization
	*/
	public function gestion_Organization(
			$parametres) {
		$this->onDebug ( __METHOD__, 1 );
		$params=array();
		$this->champ_obligatoire_standard();
		foreach($parametres as $champ=>$valeur) {
			if(isset($mandatory[$champ]) && !empty($valeur)) {
				$mandatory[$champ]=true;
			}
			switch ($champ) {
				case 'parent_name':
					$params['parent_id']=$this->creer_oql ( $valeur )
						->getOqlCi ();
					break;
				case 'deliverymodel_name':
					$params['deliverymodel_id']=$this->getObjetItopDeliveryModel ()
						->creer_oql ( $valeur )
						->getOqlCi ();
					break;
				default :
					$params[$champ]=$valeur;
			}
		}
		$this->valide_mandatory_fields();
		$this->creer_oql ( $params['name'] )
			->creer_ci ( $params['name'], $params );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return DeliveryModel
	 */
	public function &getObjetItopDeliveryModel() {
		return $this->DeliveryModel;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetItopDeliveryModel(&$DeliveryModel) {
		$this->DeliveryModel = $DeliveryModel;
		
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
		$help [__CLASS__] ["text"] [] .= "Organization :";
		
		return $help;
	}
}
?>
