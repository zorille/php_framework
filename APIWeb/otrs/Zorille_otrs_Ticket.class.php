<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception;
use Zorille\framework as Core;

/**
 * class Ticket
 *
 * @package Lib
 * @subpackage otrs
 */
class Ticket extends Tickets {

	/**
	 * Instancie un objet de type Ticket. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs
	 * @return Ticket|static
	 * @throws Exception
	 */
	static function &creer_Ticket(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): Ticket|static {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Ticket ( $sort_en_erreur, $entete );
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
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'Ticket' );
	}

	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * Champs obligatoires pour TicketCreate.
	 * @return $this
	 */
	public function &champ_obligatoire_TicketCreate(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'Ticket.Title' => false,
				'Ticket.QueueID' => false,
				'Ticket.State' => false,
				'Ticket.PriorityID' => false,
				'Ticket.CustomerUser' => false,
				'Article.Subject' => false,
				'Article.CommunicationChannel' => false,
				'Article.Body' => false,
				'Article.ContentType' => false
		) );
		return $this;
	}

	/**
	 * Champs obligatoires pour TicketUpdate.
	 * @return $this
	 */
	public function &champ_obligatoire_TicketUpdate(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'TicketID' => false
		) );
		return $this;
	}

	/**
	 * Champs obligatoires pour TicketSearch.
	 * @return $this
	 */
	public function &champ_obligatoire_TicketSearch(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'ServiceID' => false,
				'State' => false
		) );
		return $this;
	}

	/**
	 * Champs obligatoires pour TicketGet.
	 * @return $this
	 */
	public function &champ_obligatoire_TicketGet(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'TicketID' => false
		) );
		return $this;
	}

	/**
	 * Prepare les parametres standards d'un ticket.
	 * @param array $parametres
	 * @return array
	 */
	public function prepare_params_Ticket(
		array $parametres): array {
		return $this->prepare_standard_params ( $parametres );
	}

	/**
	 * ******************************* OTRS Ticket *******************************
	 */
	/**
	 * @param array $parametres
	 * @return $this
	 * @throws Exception
	 */
	public function creerTicket(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_TicketCreate ()
			->prepare_params_Ticket ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->ticket_create_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * @param array $parametres
	 * @return $this
	 * @throws Exception
	 */
	public function updateTicket(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_TicketUpdate ()
			->prepare_params_Ticket ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->ticket_update_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * @param array $parametres
	 * @return $this
	 * @throws Exception
	 */
	public function searchTicket(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_TicketSearch ()
			->prepare_params_Ticket ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->ticket_search_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * @param array $parametres
	 * @return $this
	 * @throws Exception
	 */
	public function retrouveTicket(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_TicketGet ()
			->prepare_params_Ticket ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->ticket_get_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = [
			'Ticket :'
		];
		return $help;
	}
}
