<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use SimpleXMLElement;
use stdClass;
use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Reports
 *
 * @package Lib
 * @subpackage o365
 */
class Reports extends Graph {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $Reports_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $url_reports = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Reports. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Reports
	 */
	static function &creer_Reports(
		Core\options &$liste_option,
		wsclient     &$webservice,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): Reports
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Reports ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Reports
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * ******************************* REPORTS *********************************
	 */
	/**
	 * Retrouve l'ID d'un utilisateur dans le champ displayname
	 * @param string $nom
	 * @return Reports|false
	 * @throws Exception
	 */
	public function retrouve_Reportsid_par_nom(
		string $nom): bool|Reports|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->list_Reportss ();
		foreach ( $this->getListeReports () as $personne ) {
			if ($personne->displayName == $nom) {
				$this->onDebug($nom." trouve avec l'id ".$personne->id, 1);
				return $this->setReportsId ( $personne->id );
			}
		}
		return $this->onError ( "Reports " . $nom . " introuvable dans la liste", $this->getListeReports (), 1 );
	}

	/**
	 * Retrouve l'ID d'un utilisateur dans le champ displayname
	 * @param $mail
	 * @return Reports|false
	 * @throws Exception
	 */
	public function retrouve_Reportsid_par_mail(
			$mail): bool|Reports|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->list_Reportss ();
		foreach ( $this->getListeReports () as $personne ) {
			if ($personne->mail == $mail) {
				return $this->setReportsId ( $personne->id );
			}
		}
		return $this->onError ( "Reports " . $mail . " introuvable dans la liste", $this->getListeReports (), 1 );
	}
	
	/**
	 * Verifie qu'un Reports id est remplit/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_Reportsid(): bool
	{
		if(empty($this->getReportsId ())){
			$this->onDebug($this->getReportsId (), 2);
			$this->onError("Il faut un Reports id renvoye par O365 pour travailler");
			return false;
		}
		return true;
	}	

	/**
	 * Renvoi la liste des licences sur O365
	 * @return array
	 */
	public function liste_licenses_vendues(): array
	{
		$correspondances=$this->getTableauLicence();
		$vendu=array();
		$lisenses=$this->reports_subscribedLicenses();
		if($this->valide_champ_value($lisenses)) {
			foreach($lisenses->value as $licence){
				if(isset($correspondances[$licence->skuPartNumber])){
					$vendu[$correspondances[$licence->skuPartNumber]]=$licence;
				}
			}
		}
		ksort($vendu);
		return $vendu;
	}
	/**
	 * ******************************* O365 REPORTS *********************************
	 */
	/**
	 *Recupere les activations
	 * @param array $params
	 * @return array|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function reports_activations(
		array $params = array()): SimpleXMLElement|array|string|stdClass
	{
				$this->onDebug ( __METHOD__, 1 );
				return $this->getObjetO365Wsclient ()
				->getMethod ( '/reports/getOffice365ActivationCounts', $params );
	}

	/**
	 *Recupere les activations
	 * @param array $params
	 * @return array|SimpleXMLElement|string
	 * @throws Exception
	 */
	public function reports_user_activation_detail(
		array $params = array()): SimpleXMLElement|array|string|stdClass
	{
				$this->onDebug ( __METHOD__, 1 );
				return $this->getObjetO365Wsclient ()
				->getMethod ( '/reports/getOffice365ActivationsUserDetail', $params );
	}

	/**
	 *Recupere les Skus (Subscribed licenses)
	 * @param array $params
	 * @return SimpleXMLElement|stdClass|array|string
	 * @throws Exception
	 */
	public function reports_subscribedLicenses(
		array $params = array()): SimpleXMLElement|stdClass|array|string
	{
				$this->onDebug ( __METHOD__, 1 );
				return $this->getObjetO365Wsclient ()
				->getMethod ( '/subscribedSkus', $params );
	}
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getReportsId(): ?string
	{
		return $this->Reports_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setReportsId(
			$Reports_id): static
	{
		$this->Reports_id = $Reports_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeReports(): array
	{
		return $this->url_reports;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeReports(
			&$url_reports): static
	{
		$this->url_reports = $url_reports;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Reports :";
		return $help;
	}
}
