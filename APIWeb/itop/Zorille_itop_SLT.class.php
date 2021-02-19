<?php
/**
 * Gestion de itop.
 * @author dvargas
 */
namespace Zorille\itop;
use Zorille\framework as Core;
/**
 * class SLT
 *
 * @package Lib
 * @subpackage itop
 */
class SLT extends ci {

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * @codeCoverageIgnore
	 * Instancie un objet de type SLT.
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient_rest $webservice_rest Reference sur un objet webservice_rest
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return SLT
	 */
	static function &creer_SLT(
			&$liste_option,
			&$webservice_rest,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new SLT ( $sort_en_erreur, $entete );
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
	 * @return SLT
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'SLT' );
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

	public function retrouve_SLT(
			$name) {
		return $this->creer_oql ( $name )
			->retrouve_ci ();
	}

	/**
	 * @param string $name Nom du SLT
	 * @param array $fields Liste de champs pour filtrer la requete au format ['champ']='valeur'
	 * @return SLT
	 */
	public function creer_oql(
			$name = '') {
		$where = "";
		if (! empty ( $name )) {
			$where .= " WHERE name='" . $name . "'";
		}
		return $this->setOqlCi ( "SELECT " . $this->getFormat () . $where );
	}

	/**
	 * Creer une entree SLT
	 * @param string $name Nom de la famille de service
	 * @param string $service_name Nom de service existant
	 * @return SLT
	 */
	public function gestion_SLT(
			$name,
			$service_name = '') {
		$this->onDebug ( __METHOD__, 1 );
		$params = array (
				'name' => $name
		);

		$this->creer_oql ( $name )
			->creer_ci ( $name, $params );
		return $this;
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
		$help [__CLASS__] ["text"] [] .= "SLT :";
		return $help;
	}
}
?>
