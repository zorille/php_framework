<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class cacti_wsclient<br>
 *
 * Renvoi des information via un webservice.
 * @package Lib
 * @subpackage Cacti
 */
class cacti_wsclient extends wsclient {
	/**
	 * var privee
	 * @access private
	 * @var cacti_datas
	 */
	private $cacti_datas = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type cacti_wsclient.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param cacti_datas &$cacti_datas Reference sur un objet cacti_datas
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return cacti_wsclient
	 * @throws Exception
	 */
	static function &creer_cacti_wsclient(
		options     &$liste_option,
		cacti_datas &$cacti_datas,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): cacti_wsclient
	{
		$objet = new cacti_wsclient ( $sort_en_erreur, $entete );
		$objet ->_initialise ( array (
				"options" => $liste_option,
				"cacti_datas" => $cacti_datas ) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return self|bool
	 * @throws Exception
	 */
	public function &_initialise(array $liste_class): static
	{
		parent::_initialise ( $liste_class );
		
		if (! isset ( $liste_class ["cacti_datas"] )) {
			$r = $this ->onError ( "il faut un objet de type cacti_datas" );
			return $r;
		}
		$this ->setObjetCactiDatas ( $liste_class ["cacti_datas"] );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Prepare l'url de connexion au cacti nomme $nom
	 * @param string $nom
	 * @return boolean|cacti_wsclient
	 * @throws Exception
	 */
	public function prepare_connexion(string $nom): bool|cacti_wsclient|static
	{
		$liste_data_cacti = $this ->getObjetCactiDatas () 
			->valide_presence_cacti_data ( $nom );
		if (!$liste_data_cacti) {
			return $this ->onError ( "Aucune definition de cacti pour " . $nom );
		}
		$this ->getGestionConnexionUrl () 
			->retrouve_connexion_params ( $liste_data_cacti ) 
			->prepare_prepend_url ( "/cacti" );
		
		return $this;
	}

	/************************* GESTION CACTI HOST ****************************/
	/**
	 * Applique une requete au webservice ajoute_device.php
	 * @return array|false tableau de retour ou false en cas d'erreur.
	 * @throws Exception
	 */
	public function appel_listeDevices(): bool|array
	{
		if (count ( $this ->getParams () ) == 0) {
			return $this ->onError ( "Il faut des parametres pour cette appel au Webservice" );
		}
		
		$this ->setUrl ( "/php_depot/cacti/extraire_full_informations.php" );
		
		$retour_json = $this ->envoi_requete ();
		return $this ->traite_retour_json ( $retour_json );
	}

	/**
	 * Applique une requete au webservice ajoute_device.php
	 * @return array|false tableau de retour ou false en cas d'erreur.
	 * @throws Exception
	 */
	public function appel_ajouteDevice(): bool|array
	{
		if (count ( $this ->getParams () ) == 0) {
			return $this ->onError ( "Il faut des parametres pour cette appel au Webservice" );
		}
		
		$this ->setUrl ( "/php_depot/cacti/ajoute_device.php" );
		$retour_json = $this ->envoi_requete ();
		return $this ->traite_retour_json ( $retour_json );
	}

	/**
	 * Applique une requete au webservice ajoute_device.php avec le param update
	 * @return array|false tableau de retour ou false en cas d'erreur.
	 * @throws Exception
	 */
	public function appel_udateDevice(): bool|array
	{
		if (count ( $this ->getParams () ) == 0) {
			return $this ->onError ( "Il faut des parametres pour cette appel au Webservice" );
		}
		//On ajoute de force la parma update
		$this ->setParams ( array (
				"update" => true ) );
		
		$this ->setUrl ( "/php_depot/cacti/ajoute_device.php" );
		$retour_json = $this ->envoi_requete ();
		return $this ->traite_retour_json ( $retour_json );
	}

	/**
	 * Applique une requete au webservice supprime_device.php
	 * @return array|false tableau de retour ou false en cas d'erreur.
	 * @throws Exception
	 */
	public function appel_supprimeDevice(): bool|array
	{
		if (count ( $this ->getParams () ) == 0) {
			return $this ->onError ( "Il faut des parametres pour cette appel au Webservice" );
		}
		
		$this ->setUrl ( "/php_depot/cacti/supprime_device.php" );
		$retour_json = $this ->envoi_requete ();
		return $this ->traite_retour_json ( $retour_json );
	}

	/************************* GESTION CACTI HOST ****************************/

	/**
	 * Applique une requete au webservice
	 * @codeCoverageIgnore
	 * @return array|false tableau de retour ou false en cas d'erreur.
	 * @throws Exception
	 */
	public function appel_exempleAppel(): bool|array
	{
		$this ->setParams ( array () );
		$this ->setUrl ( "/cacti" );
		
		//Envoi une requete avec retour en json
		$retour_json = $this ->envoi_requete ();
		return $this ->traite_retour_json ( $retour_json );
	}

	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return cacti_datas|null
	 */
	public function &getObjetCactiDatas(): ?cacti_datas
	{
		return $this->cacti_datas;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetCactiDatas(&$cacti_datas): static
	{
		$this->cacti_datas = $cacti_datas;
		return $this;
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
