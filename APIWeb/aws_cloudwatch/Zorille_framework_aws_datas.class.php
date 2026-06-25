<?php
/**
 * Gestion de Aws.
 * @author dvargas
 */
namespace Zorille\framework;
/**
 * class aws_datas
 *
 * @package Lib
 * @subpackage Aws
 */
class aws_datas extends serveur_datas {

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type aws_datas.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return aws_datas
	 */
	static function &creer_aws_datas(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): aws_datas {
		$objet = new aws_datas ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option 
		) );
		
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return aws_datas
	 */
	public function &_initialise(array $liste_class): static {
		parent::_initialise ( $liste_class );
		
		$this->retrouve_aws_param ();
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Constructeur.
	 *
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct($sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 *
	 * @return aws_datas|boolean aws_datas si OK, False sinon.
	 */
	public function retrouve_aws_param(): aws_datas|bool {
		$donnee_aws = $this->_valideOption ( array (
				"aws_machines",
				"serveur" 
		) );
		
		if (!$donnee_aws) {
			return false;
		}
		
		return $this->setServeurData ( $donnee_aws );
	}

	/**
	 * Valide la presence de la definition d'un aws nomme : $nom
	 *
	 * @param string $nom        	
	 * @return array false informations de configuration, false sinon.
	 */
	public function valide_presence_aws_data(string $nom): array {
		return $this->valide_presence_serveur_data ( $nom );
	}

	/******************************* ACCESSEURS ********************************/
	
	/******************************* ACCESSEURS ********************************/
	
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
