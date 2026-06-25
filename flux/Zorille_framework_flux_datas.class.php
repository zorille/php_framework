<?php
/**
 * Serveur de serveur.
 * @author dvargas
 */
namespace Zorille\framework;

use Exception;

/**
 * class flux_datas
 * 
 * @package Lib
 * @subpackage serveur
 */
class flux_datas extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var utilisateurs
	 */
	private $class_utilisateurs = false;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $flux_data = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type flux_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet serveur_connexion_url
	 * @return flux_datas
	 * @throws Exception
	 */
	static function &creer_flux_datas(options &$liste_option, bool|string $sort_en_erreur = false, string $entete = __CLASS__): flux_datas
	{
		$objet = new flux_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return flux_datas
	 * @throws Exception
	 */
	public function &_initialise(
		array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		$this->setObjetUtilisateurs ( utilisateurs::creer_utilisateurs ( $liste_class ["options"] ) );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 * @codeCoverageIgnore
	 * @param Bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
		// Serveur de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
	}

	/**
	 * Extrait des parametres d'un liste d'option
	 * @codeCoverageIgnore
	 * @param array|string $chemin_option
	 * @return boolean string array
	 * @throws Exception
	 */
	protected function _valideOption(array|string $chemin_option): mixed
	{
		if ($this->getListeOptions ()
			->verifie_variable_standard ( $chemin_option ) === false) {
			if (is_array ( $chemin_option )) {
				$chemin_option = implode ( "_", $chemin_option );
			}
			return $this->onError ( "Il manque le parametre : " . $chemin_option );
		}
		
		$datas = $this->getListeOptions ()
			->renvoi_variables_standard ( $chemin_option );
		
		if (is_array ( $datas ) && isset ( $datas ["#comment"] )) {
			unset ( $datas ["#comment"] );
		}
		
		return $datas;
	}
	
	/**
	 * Retrouve les parametres dans la ligne de commande/fichier de conf
	 * @return flux_datas True est OK, False sinon.
	 * @throws Exception
	 */
	public function retrouve_flux_param($tag_liste_flux): flux_datas
	{
		$donnee_flux = $this->_valideOption ( array (
				$tag_liste_flux,
				"serveur"
		) );
	
		return $this->setFluxData ( $donnee_flux );
	}

	/**
	 * Valide la presence de la definition d'un username nomme : $nom
	 *
	 * @param string $nom
	 * @return bool|array false informations de configuration, false sinon.
	 */
	public function valide_presence_flux_data(string $nom): bool|array
	{
		$this->onDebug ("valide_presence_flux_data",1);
		foreach ( $this->getFluxDatas() as $flux_data ) {
			if (strtolower ( $nom ) != strtolower ( $flux_data ["nom"] )) {
				$this->onDebug ( strtolower ( $nom ) . " != " . strtolower ( $flux_data ["nom"] ), 2 );
				continue;
			}
			
			$this->getObjetUtilisateurs ()
				->retrouve_utilisateurs_array ( $flux_data );
			$flux_data ["username"] = $this->getObjetUtilisateurs ()
				->getUsername ();
			$flux_data ["password"] = $this->getObjetUtilisateurs ()
				->getPassword ();
			$this->onDebug ( $flux_data, 2 );
			return $flux_data;
		}
		
		return false;
	}

	/******************************* ACCESSEURS ********************************/

	/**
	 * @codeCoverageIgnore
	 */
	public function &getFluxDatas(): array
	{
		return $this->flux_data;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFluxData($flux_data): static
	{
		if (is_array ( $flux_data )) {
			$this->flux_data = $flux_data;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &getObjetUtilisateurs(): utilisateurs|bool
	{
		return $this->class_utilisateurs;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetUtilisateurs(&$class_utilisateurs): static
	{
		$this->class_utilisateurs = $class_utilisateurs;
		return $this;
	}

	/******************************* ACCESSEURS ********************************/
	
	/**
	 * Affiche le help.<br>
	 * @codeCoverageIgnore
	 */
	static public function help(): array|string
	{
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		
		return $help;
	}
}
