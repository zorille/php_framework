<?php

/**
 * Gestion de evobserve.
 * @author dvargas
 */
namespace Zorille\evobserve;

use Exception;
use Zorille\framework as Core;

/**
 * class TimePeriods
 *
 * @package Lib
 * @subpackage evobserve
 */
class TimePeriods extends globalapi {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $timePeriods = array (
			'EXEMPLE' => 1
	);

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type TimePeriods. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return TimePeriods
	 * @throws Exception
	 */
	static function &creer_TimePeriods(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): TimePeriods {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new TimePeriods ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return TimePeriods
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		$this->retrouve_TimePeriods ();
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
	 * ******************************* TimePeriods URI ******************************
	 */
	public function TimePeriods_uri(): string {
		return $this->globalapi_uri () . '/time_slots';
	}

	/**
	 * ******************************* Evobserve TimePeriods *********************************
	 */

	/**
	 * @throws Exception
	 */
	public function retrouve_id_timePeriod(
			$TimePeriods) {
		$this->onDebug ( __METHOD__, 1 );
		if (empty ( $this->getTimePeriods () )) {
			$this->retrouve_TimePeriods ();
		}
		if (isset ( $this->getTimePeriods () [strtoupper ( $TimePeriods )] )) {
			return $this->getTimePeriods () [strtoupper ( $TimePeriods )];
		}
		return $this->onError ( "Le timePeriod " . $TimePeriods . " n'existe pas dans la liste" );
	}

	public function prepare_TimePeriods(): static {
		$this->onDebug ( __METHOD__, 1 );
		$liste_TimePeriods = array ();
		if (isset ( $this->getDonnees ()->_embedded->items )) {
			foreach ( $this->getDonnees ()->_embedded->items as $TimePeriod ) {
				$liste_TimePeriods [mb_strtoupper ( $TimePeriod->name )] = $TimePeriod->id;
			}
		}
		$this->onDebug ( $liste_TimePeriods, 1 );
		return $this->setTimePeriods ( $liste_TimePeriods );
	}

	/**
	 * @throws Exception
	 */
	public function retrouve_TimePeriods(
			$params = array (
					"company" => array (
							1,
							2
					),
					"inheritance" => true,
					"limit" => 1000,
					"sort" => array (
							"+name"
					)
			)): TimePeriods {
		$this->onDebug ( __METHOD__, 1 );
		$resultat = $this->getObjetEvobserveWsclient ()
			->getMethod ( $this->TimePeriods_uri (), $params );
		return $this->setDonnees ( $resultat )
			->prepare_TimePeriods ();
	}

	/**
	 * Creer un host la companie en parametre (cf: company)
	 * @param array $parametres Liste des parametres de la commande host. (parametres obligatoires) : 'host_alias',"host_address","company","collector"
	 * @return TimePeriods
	 */
	public function creerTimePeriods(
		array $parametres): TimePeriods {
		$this->onDebug ( __METHOD__, 1 );
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return array|string
	 */
	public function getTimePeriods(): array|string {
		return $this->timePeriods;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTimePeriods(
			$liste_timePeriods): static {
		$this->timePeriods = $liste_timePeriods;
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
		$help [__CLASS__] ["text"] [] .= "TimePeriods :";
		return $help;
	}
}
