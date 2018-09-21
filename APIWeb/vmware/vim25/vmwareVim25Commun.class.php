<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class vmwareVim25Commun<br>
 * @package Lib
 * @subpackage VMWare
 */
class vmwareVim25Commun extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwareWsclient
	 */
	private $objetVmwareWsclient = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var vmwarePropertyCollector
	 */
	private $objetVmwarePropertyCollector = null;

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type vmwareVim25Commun.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param vmwareWsclient $vmware_webservice Reference sur un objet vmwareWsclient
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return vmwareVim25Commun
	 */
	static function &creer_vmwareVim25Commun(&$liste_option, &$vmware_webservice, $sort_en_erreur = false, $entete = __CLASS__) {
		abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new vmwareVim25Commun ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"vmwareWsclient" => $vmware_webservice 
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return vmwareVim25Commun
	 * @throws Exception
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		
		$this->setObjectVmwareWsclient ( $liste_class ['vmwareWsclient'] )
			->setObjectVmwarePropertyCollector ( vmwarePropertyCollector::creer_vmwarePropertyCollector ( $liste_class ['options'], $liste_class ['vmwareWsclient'], $liste_class ['vmwareWsclient']->getObjectServiceInstance () ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete lors de l'affichage.
	 * @return true
	 * @throws Exception
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Retrouve les objets du type demande en fonction des TraversalSpec de l'objet PropertyCollector
	 * @param string $type
	 * @param array $pathSet
	 * @param array $options
	 * @param boolean $full
	 * @return array
	 * @throws Exception
	 */
	public function retrouve_objets($type, $pathSet, $options, $full = false) {
		$resultat_recherche = $this->getObjectVmwarePropertyCollector ()
			->RetrievePropertiesEx ( $type, $full, $pathSet, $options );
		if (! isset ( $resultat_recherche->objects )) {
			return $this->onError ( "Reponse RetrievePropertiesEx non utilisable", $resultat_recherche );
		}
		$donnees_sup = $resultat_recherche;
		while ( isset ( $donnees_sup->token ) ) {
			$donnees_sup = $this->getObjectVmwarePropertyCollector ()
				->ContinueRetrievePropertiesEx ( $donnees_sup->token );
			if (! isset ( $donnees_sup->objects )) {
				return $this->onError ( "Reponse ContinueRetrievePropertiesEx non utilisable", $donnees_sup );
			}
			foreach ( $donnees_sup->objects as $key => $value ) {
				$resultat_recherche->objects [] = $value;
			}
		}
		
		return $resultat_recherche->objects;
	}

	/************************* Accesseurs ***********************/
	/**
	 * @codeCoverageIgnore
	 * @return vmwarePropertyCollector
	 */
	public function &getObjectVmwarePropertyCollector() {
		return $this->objetVmwarePropertyCollector;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwarePropertyCollector(&$objetVmwarePropertyCollector) {
		$this->objetVmwarePropertyCollector = $objetVmwarePropertyCollector;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return vmwareWsclient
	 */
	public function &getObjectVmwareWsclient() {
		return $this->objetVmwareWsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectVmwareWsclient($objetVmwareWsclient) {
		$this->objetVmwareWsclient = $objetVmwareWsclient;
		return $this;
	}

	/************************* Accesseurs ***********************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}

?>
