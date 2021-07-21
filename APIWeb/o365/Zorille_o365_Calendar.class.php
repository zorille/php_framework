<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Calendar
 *
 * @package Lib
 * @subpackage o365
 */
class Calendar extends User {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $calendar_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $calendar_content = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $calendar_o365_ref = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Calendar. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Calendar
	 */
	static function &creer_Calendar(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Calendar ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Calendar
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
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

	/**
	 * ******************************* CALENDARS *********************************
	 */
	public function creer_entree_calendrier() {
		// $test_start=new DateTime('20210412 20:00:00');
		// $test_end=new DateTime('20210412 20:30:00');
		// echo $test->format('c')."\n";
		// print_r($ws->user_calendar_view('fbe602e3-75c7-49e2-9def-c764ac03aa5b',array('startDateTime'=>$test_start->format('c'),'endDateTime'=>$test_end->format('c'))));
		print_r ( $ws->user_calendar_create ( '9b30d71f-4e3f-45b9-beb1-a024b48dc9bb', array (
				'subject' => 'Test Zorille to Harry',
				'body' => array (
						'contentType' => 'HTML',
						'content' => 'allez au boulot :)'
				),
				'start' => array (
						'dateTime' => $test_start->format ( 'c' ),
						'timeZone' => 'Europe/Paris'
				),
				'end' => array (
						'dateTime' => $test_end->format ( 'c' ),
						'timeZone' => 'Europe/Paris'
				)
		) ) );
	}

	/**
	 * ******************************* O365 CALENDARS *********************************
	 */
	/**
	 * @return \Zorille\o365\Calendar
	 * @throws \Exception
	 */
	public function list_user_calendars(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_userid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/users/' . $this->getUserId () . '/calendars', $params );
	}

	/**
	 * @return \Zorille\o365\Calendar
	 * @throws \Exception
	 */
	public function list_user_calendar_groups(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_userid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/users/' . $this->getUserId () . '/calendarGroups', $params );
	}

	/**
	 * @return \Zorille\o365\Calendar
	 * @throws \Exception
	 */
	public function user_calendar_view(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_userid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->getMethod ( '/users/' . $this->getUserId () . '/calendarView', $params );
	}

	/**
	 * @return \Zorille\o365\Calendar
	 * @throws \Exception
	 */
	public function user_calendar_create(
			$params = array()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_userid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->jsonPostMethod ( '/users/' . $this->getUserId () . '/calendar/events', $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getCalendarId() {
		return $this->calendar_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCalendarId(
			&$calendar_id) {
		$this->calendar_id = $calendar_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getEmailContent() {
		return $this->calendar_content;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEmailContent(
			&$calendar_content) {
		$this->calendar_content = $calendar_content;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getO356CalendarRef() {
		return $this->calendar_o365_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setO356CalendarRef(
			&$calendar_o365_ref) {
		$this->calendar_o365_ref = $calendar_o365_ref;
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
		$help [__CLASS__] ["text"] [] .= "Calendar :";
		return $help;
	}
}
?>
