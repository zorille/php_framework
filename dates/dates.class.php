<?php
/**
 * @author dvargas
 * @package Lib
 */

/**
 * class dates<br>
 * Gere des dates au format standard (YYYYMMDD) :
 * <ul>
 * <li>Une liste de date pour chaque jour</li>
 * <li>Une liste de lundi</li>
 * <li>Une liste de premier du mois</li>
 * </ul>
 *
 * @package Lib
 * @subpackage standard
 */
class dates extends abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_dates = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_week = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_month = array ();
	
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_feries = array ();

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type dates.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return dates
	 */
	static function &creer_dates(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		if ($liste_option->verifie_option_existe ( 'date', true ) !== false) {
			$liste_dates = new dates ( $liste_option->getOption ( 'date' ), "no_date", $sort_en_erreur, $entete );
			if ($liste_option->verifie_option_existe ( "ajouter_week_extreme" ) !== false) {
				$liste_dates->ajout_date ( $liste_dates->retrouve_lundi_precedent ( $liste_dates->recupere_premier_jour () ), "week" );
			}
			if ($liste_option->verifie_option_existe ( "ajouter_month_extreme" ) !== false) {
				$liste_dates->ajout_date ( $liste_dates->retrouve_month ( $liste_dates->recupere_premier_jour (), 0 ), "month" );
			}
			
			//Dans le cas ou $liste_option['date'] est relatif (+/- x day/week/year ...)
			$liste_option->setOption ( 'date', $liste_dates->recupere_premier_jour () );
		} else {
			if ($liste_option->verifie_option_existe ( 'date_debut', true ) !== false)
				$date_debut = $liste_option->getOption ( 'date_debut' );
			else
				$date_debut = date ( "Ymd", time () );
			
			if ($liste_option->verifie_option_existe ( 'date_fin' ) !== false)
				$date_fin = $liste_option->getOption ( 'date_fin' );
			else
				$date_fin = date ( "Ymd", time () );
			
			$liste_dates = new dates ( $date_debut, $date_fin, $sort_en_erreur, $entete );
			$date_debut = $liste_dates->recupere_premier_jour ();
			$date_fin = $liste_dates->recupere_dernier_jour ();
			if ($liste_option->verifie_option_existe ( "ajouter_week_extreme" ) !== false) {
				$liste_dates->ajout_date ( $liste_dates->retrouve_lundi_precedent ( $date_debut ), "week" );
				$liste_dates->ajout_date ( $liste_dates->retrouve_lundi_precedent ( $date_fin ), "week" );
			}
			if ($liste_option->verifie_option_existe ( "ajouter_month_extreme" ) !== false) {
				$liste_dates->ajout_date ( $liste_dates->retrouve_month ( $date_debut, 0 ), "month" );
				$liste_dates->ajout_date ( $liste_dates->retrouve_month ( $date_fin, 0 ), "month" );
			}
			
			//Dans le cas ou $liste_option['date'] est relatif (+/- x day/week/year ...)
			$liste_option->setOption ( 'date_debut', $date_debut );
			$liste_option->setOption ( 'date_fin', $date_fin );
		}
		if ($liste_option->verifie_option_existe ( "ajouter_dates_feries" ) !== false) {
			$liste_dates->creer_jours_feries ();
		}
		
		$liste_dates->_initialise ( array (
				"options" => $liste_option 
		) );
		abstract_log::onDebug_standard ( "Liste des Dates : ", 2 );
		abstract_log::onDebug_standard ( $liste_dates, 2 );
		return $liste_dates;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return dates
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Prend une date de depart au format YYYYMMDD ou une date au format anglais US
	 * et peut prendre un date de fin au format YYYYMMDD ou une date au format anglais US.<br>
	 * La chaine au format anglais US doit etre en accord avec la syntaxe des dates GNU.<br><br>
	 * Par defaut la date de fin prend la valeur du jour uniquement dans le cas du param --date
	 * Si date de fin vaut "" alors il prend la date du jour en cours dans le cas du param --date_debut
	 *
	 * @param string $date_depart Date au format YYYYMMDD ou au format anglais US
	 * @param string $date_fin Date au format YYYYMMDD ou au format anglais US
	 * @return Bool Renvoi FALSE en cas d'erreur, TRUE sinon.
	 * @throws Exception
	 */
	public function __construct($date_depart, $date_fin = "no_date", $sort_en_erreur = false, $entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		if ($date_fin != "no_date") {
			if ($date_fin === "")
				$CODE_RETOUR = $this->creer_liste_dates ( $this->extraire_date ( $date_depart ), date ( "Ymd" ) );
			else
				$CODE_RETOUR = $this->creer_liste_dates ( $this->extraire_date ( $date_depart ), $this->extraire_date ( $date_fin ) );
		} else
			$CODE_RETOUR = $this->creer_date ( $this->extraire_date ( $date_depart ) );
		return $CODE_RETOUR;
	}

	/**
	 * Prend une date au format YYYYMMDD et renvoi un tableau year/month/day
	 *
	 * @param string $date Date au format YYYYMMDD
	 * @return array false un tableau avec les cases year/month/day remplie ou FALSE si le format ne correspond pas.
	 * @throws Exception
	 */
	public function parse_date($date) {
		if (strlen ( $date ) == 8) {
			$date_parser ['year'] = substr ( $date, 0, 4 );
			$date_parser ['month'] = substr ( $date, 4, 2 );
			$date_parser ['day'] = substr ( $date, 6 );
		} else {
			return $this->onError ( "la date n'est pas au bon format" );
		}
		
		return $date_parser;
	}

	/**
	 * Prend une date au format YYYYMMDD ou une date au format anglais US.<br>
	 * La chaine au format anglais US doit etre en accord avec la syntaxe des dates GNU.
	 *
	 * @param string $date Date au format YYYYMMDD ou au format anglais US
	 * @return string false une string au format YYYYMMDD ou FALSE si le format ne correspond pas.
	 */
	public function extraire_date($date) {
		if (! preg_match ( "/^[0-9]{8}$/", $date )) {
			$stamp_date = strtotime ( $date );
			if ($stamp_date !== false)
				$CODE_RETOUR = date ( "Ymd", $stamp_date );
			else
				$CODE_RETOUR = false;
		} else
			$CODE_RETOUR = $date;
		
		return $CODE_RETOUR;
	}

	/**
	 * Prend un timestamp epoc.<br>
	 *
	 * @param int $timestamp timestamp Epoc
	 * @return string false une string au format YYYYMMDD ou FALSE si le format ne correspond pas.
	 */
	public function extraire_date_timestamp($timestamp) {
		if (is_int ( $timestamp )) {
			return date ( "Ymd", $timestamp );
		}
		
		return false;
	}

	/**
	 * Prend une date au format Ymd et renvoi le timestamp.<br>
	 *
	 * @param int $date date au format Ymd
	 * @return int false un timestamp epoc ou FALSE si le format ne correspond pas.
	 * @throws Exception
	 */
	public function extraire_timestamp($date, $hour = "00:00:00") {
		$parse_date = $this->parse_date ( $date );
		$split = explode ( ":", $hour );
		if ($parse_date !== false && count ( $split ) == 3) {
			return mktime ( $split [0], $split [1], $split [2], $parse_date ["month"], $parse_date ["day"], $parse_date ["year"] );
		}
		
		return false;
	}

	/**
	 * Prend un timestamp epoc et renvoi une date au format mysql.<br>
	 *
	 * @param int $timestamp timestamp Epoc
	 * @return string false une string au format YYYYMMDD ou FALSE si le format ne correspond pas.
	 */
	public function extraire_date_mysql_timestamp($timestamp) {
		if (is_numeric ( $timestamp )) {
			return date ( "Y-m-d H:i:s", substr ( $timestamp, 0, 10 ) );
		}
		
		return false;
	}

	/**
	 * Prend une date au format Ymd et renvoi le timestamp.<br>
	 *
	 * @param int $date date au format Ymd
	 * @return int false un timestamp epoc ou FALSE si le format ne correspond pas.
	 * @throws Exception
	 */
	public function extraire_date_mysql_standard($date, $hour = "00:00:00") {
		$ts_date = $this->extraire_timestamp ( $date, $hour );
		if ($ts_date !== false) {
			return date ( "Y-m-d H:i:s", $ts_date ); // dd-MM-yyyy HH:mm:ss
		}
		
		return false;
	}

	/**
	 * Transforme une date mysql au format Y-m-d H:i:s vers un objet DateTime
	 *
	 * @param string $mysql_date        	
	 * @return DateTime
	 */
	public function prepare_date_mysql($mysql_date) {
		$retour = date_parse_from_format ( "Y-m-d H:i:s", $mysql_date );
		$this->onDebug ( $retour, 2 );
		if (is_array ( $retour ) && $retour ["error_count"] > 0) {
			return false;
		}
		
		return $retour;
	}

	/**
	 * Renvoi le timestamp d'une date au format "Y-m-d H:i:s"
	 *
	 * @param string $mysql_date Date au format "Y-m-d H:i:s"
	 * @return int Timestamp de la date en argument
	 */
	public function timestamp_mysql_date($mysql_date) {
		// On recupere le timestamp du last_done
		$array_mysql_date = $this->prepare_date_mysql ( $mysql_date );
		
		if (is_array ( $array_mysql_date )) {
			$ts_mysql_date = mktime ( $array_mysql_date ["hour"], $array_mysql_date ["minute"], $array_mysql_date ["second"], $array_mysql_date ["month"], $array_mysql_date ["day"], $array_mysql_date ["year"] );
		} else {
			$ts_mysql_date = 0;
		}
		$this->ondebug ( "Timestamp date : " . $ts_mysql_date, 2 );
		
		return $ts_mysql_date;
	}

	/**
	 *
	 *
	 *
	 * Prend une date, la verifie
	 * et l'insere dans la liste des dates.<br>
	 * Il verifie aussi si cette date est un lundi ou un debut de mois.
	 * Si oui il l'insere dans le bon tableau.
	 *
	 * @param string $date Date au format YYYYMMDD
	 * @return Bool Renvoi FALSE en cas d'erreur, TRUE sinon.
	 * @throws Exception
	 */
	public function creer_date($date) {
		// permet de verifier le format de la date
		$date_depart_hash = $this->parse_date ( $date );
		
		$this->liste_dates [] .= date ( "Ymd", mktime ( 0, 0, 0, $date_depart_hash ['month'], $date_depart_hash ['day'], $date_depart_hash ['year'] ) );
		$this->week_day ( $date_depart_hash );
		$this->month_day ( $date_depart_hash );
		
		return true;
	}

	/**
	 * Prend une date de debut et une date de fin, les verifies
	 * et les inseres dans la liste des dates. Il ordonne cette liste.<br>
	 * Il verifie aussi si ces dates sont un lundi ou un debut de mois.
	 * Si oui il insere les dates correspondantes dans le bon tableau.
	 *
	 * @param string $date_depart Date de debut au format YYYYMMDD
	 * @param string $date_fin Date de fin au format YYYYMMDD
	 * @return Bool Renvoi FALSE en cas d'erreur, TRUE sinon.
	 * @throws Exception
	 */
	public function creer_liste_dates($date_depart, $date_fin) {
		$date_depart_hash = $this->parse_date ( $date_depart );
		$date_fin_hash = $this->parse_date ( $date_fin );
		
		if (mktime ( 0, 0, 0, $date_depart_hash ['month'], $date_depart_hash ['day'], $date_depart_hash ['year'] ) <= mktime ( 0, 0, 0, $date_fin_hash ['month'], $date_fin_hash ['day'], $date_fin_hash ['year'] )) {
			for($i = 0; mktime ( 0, 0, 0, $date_depart_hash ['month'], $date_depart_hash ['day'] + $i, $date_depart_hash ['year'] ) <= mktime ( 0, 0, 0, $date_fin_hash ['month'], $date_fin_hash ['day'], $date_fin_hash ['year'] ); $i ++) {
				$date = date ( "Ymd", mktime ( 0, 0, 0, $date_depart_hash ['month'], $date_depart_hash ['day'] + $i, $date_depart_hash ['year'] ) );
				if (! in_array ( $date, $this->liste_dates ))
					$this->liste_dates [] .= $date;
			}
			sort ( $this->liste_dates );
		} else {
			return false;
		}
		
		$CODE_RETOUR = $this->week_day ();
		if ($CODE_RETOUR)
			$CODE_RETOUR = $this->month_day ();
		
		return $CODE_RETOUR;
	}

	/**
	 * Il verifie si une ou la liste des dates sont des lundi ou pas.<br>
	 * Lorsqu'il trouve un lundi, il l'ajoute a la liste des lundis. Il ordonne cette liste.<br>
	 *
	 * @param array $hash_day Date au format array(year/month/day
	 * @return Bool Renvoi FALSE en cas d'erreur, TRUE sinon.
	 * @throws Exception
	 */
	public function week_day($hash_day = "non") {
		if ($hash_day == "non") {
			foreach ( $this->liste_dates as $date ) {
				$date_locale = $this->parse_date ( $date );
				if ($date_locale && strftime ( "%A", mktime ( 0, 0, 0, $date_locale ['month'], $date_locale ['day'], $date_locale ['year'] ) ) == "Monday") {
					if (! in_array ( $date, $this->liste_week )) {
						$this->liste_week [] .= $date;
					}
				}
			}
		} elseif (strftime ( "%A", mktime ( 0, 0, 0, $hash_day ['month'], $hash_day ['day'], $hash_day ['year'] ) ) == "Monday")
			$this->liste_week [] .= date ( "Ymd", mktime ( 0, 0, 0, $hash_day ['month'], $hash_day ['day'], $hash_day ['year'] ) );
		sort ( $this->liste_week );
		
		return TRUE;
	}

	/**
	 * Il verifie si une ou la liste des dates sont des debut de mois ou pas.<br>
	 * Lorsqu'il trouve un debut de mois, il l'ajoute a la liste des mois. Il ordonne cette liste.<br>
	 *
	 * @param array $hash_day Date au format array(year/month/day)
	 * @return Bool Renvoi FALSE en cas d'erreur, TRUE sinon.
	 * @throws Exception
	 */
	public function month_day($hash_day = "non") {
		if ($hash_day == "non") {
			foreach ( $this->liste_dates as $date ) {
				$date_locale = $this->parse_date ( $date );
				if ($date_locale && $date_locale ['day'] == "01") {
					if (! in_array ( $date, $this->liste_month ))
						$this->liste_month [] .= $date;
				}
			}
		} elseif ($hash_day ['day'] == "01")
			$this->liste_month [] .= date ( "Ymd", mktime ( 0, 0, 0, $hash_day ['month'], $hash_day ['day'], $hash_day ['year'] ) );
		
		sort ( $this->liste_month );
		
		return TRUE;
	}

	/**
	 * Convertie une date au format standard et optionnellement un heure en timestamp.
	 *
	 * @param string $date Date de debut au format YYYYMMDD
	 * @param string $time Horaire au format HH:MM:SS
	 * @return int false si OK, False sinon.
	 * @throws Exception
	 */
	public function renvoi_timestamp($date, $time = "00:00:00") {
		$date_hash = $this->parse_date ( $date );
		
		$liste_hour = explode ( ":", $time );
		
		if (is_array ( $liste_hour ) && count ( $liste_hour ) == 3) {
			$ts_day = mktime ( $liste_hour [0], $liste_hour [1], $liste_hour [2], $date_hash ["month"], $date_hash ["day"], $date_hash ["year"] );
		} else {
			$ts_day = false;
		}
		
		return $ts_day;
	}
	
	// Fonction d'acces aux variables
	

	/**
	 * Accesseur en lecture a une date
	 *
	 * @return array Renvoi la date.
	 */
	public function recupere_date($case, $type = "day") {
		$CODE_RETOUR = false;
		switch ($type) {
			case "week" :
				if (count ( $this->liste_week ) > $case) {
					$CODE_RETOUR = $this->liste_week [$case];
				}
				break;
			case "month" :
				if (count ( $this->liste_month ) > $case) {
					$CODE_RETOUR = $this->liste_month [$case];
				}
				break;
			default :
				if (count ( $this->liste_dates ) > $case) {
					$CODE_RETOUR = $this->liste_dates [$case];
				}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * Accesseur en lecture a la liste du premier jour de la liste des jours
	 *
	 * @return array Renvoi le premier jour de la liste des jours.
	 */
	public function recupere_premier_jour() {
		return $this->recupere_date ( 0, "day" );
	}

	/**
	 * Accesseur en lecture a la liste du dernier jour de la liste des jours
	 *
	 * @return array Renvoi la liste du dernier jour de la liste des jours
	 */
	public function recupere_dernier_jour() {
		$compteur = count ( $this->getListeDates () ) - 1;
		if ($compteur < 0) {
			return false;
		}
		return $this->recupere_date ( $compteur, "day" );
	}

	/**
	 *
	 *
	 *
	 * Accesseur en ecriture aux listes
	 *
	 * @param string $date Date au format YYYYMMDD
	 * @param string $type Type de date : day/week/month
	 * @return array Renvoi la liste des mois.
	 * @throws Exception
	 */
	public function ajout_date($date, $type = "day") {
		$date_locale = $this->parse_date ( $date );
		if ($date_locale) {
			switch ($type) {
				case "week" :
					$this->liste_week [] .= $date;
					$this->liste_week = array_unique ( $this->liste_week );
					sort ( $this->liste_week, SORT_NUMERIC );
					break;
				case "month" :
					$this->liste_month [] .= $date;
					$this->liste_month = array_unique ( $this->liste_month );
					sort ( $this->liste_month, SORT_NUMERIC );
					break;
				default :
					$this->liste_dates [] .= $date;
					$this->liste_dates = array_unique ( $this->liste_dates );
					sort ( $this->liste_dates, SORT_NUMERIC );
			}
		}
		
		return true;
	}

	/**
	 * Valide qu'une date fais partie de la liste.
	 *
	 * @param string $date Date au format YYYYMMDD
	 * @return bool true si vrai, false sinon.
	 */
	public function date_existe_dans_liste_day($date) {
		return in_array ( $date, $this->getListeDates () );
	}

	/**
	 *
	 * Permet de retrouver un date a partir d'une autre date et de leur decalage numerique
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @param int $nb_jours Nombre de jours de decalage
	 * @param Bool $anterieur TRUE si la date calculee est anterieur a la date de depart
	 * @return string Renvoi la date calculee.
	 * @throws Exception
	 */
	public function retrouve_jour($date, $nb_jours, $anterieur = false) {
		$hash_date = $this->parse_date ( $date );
		if ($hash_date && is_int ( $nb_jours )) {
			if ($anterieur)
				$date_timestamp = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'] - $nb_jours, $hash_date ['year'] );
			else
				$date_timestamp = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'] + $nb_jours, $hash_date ['year'] );
			$CODE_RETOUR = date ( "Ymd", $date_timestamp );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 *
	 * Permet de retrouver le jour de la semaine pour une date.
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @return string Renvoi le jour de la semain
	 * @throws Exception
	 */
	public function retrouve_nom_jour_semaine($date) {
		$hash_date = $this->parse_date ( $date );
		return strftime ( "%A", mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'], $hash_date ['year'] ) );
	}

	/**
	 *
	 * Permet de retrouver la date d'un lundi a partir
	 *         d'une autre date et de leur decalage numerique en nombre de semaine.
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @param int $nb_week Nombre de semaine de decalage
	 * @param Bool $anterieur TRUE si la date calculee est anterieur a la date de depart
	 * @return string Renvoi la date calculee.
	 * @throws Exception
	 */
	public function retrouve_week($date, $nb_week, $anterieur = false) {
		$hash_date = $this->parse_date ( $date );
		if ($hash_date && is_int ( $nb_week )) {
			$nb_week *= 7;
			if ($anterieur)
				$date_timestamp = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'] - $nb_week, $hash_date ['year'] );
			else
				$date_timestamp = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'] + $nb_week, $hash_date ['year'] );
			while ( strftime ( "%A", $date_timestamp ) != "Monday" ) {
				$nb_week ++;
				if ($anterieur)
					$date_timestamp = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'] - $nb_week, $hash_date ['year'] );
				else
					$date_timestamp = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'] + $nb_week, $hash_date ['year'] );
			}
			$CODE_RETOUR = date ( "Ymd", $date_timestamp );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 *
	 * Permet de retrouver la date d'un debut de mois a partir
	 *         d'une autre date et de leur decalage numerique en nombre de mois.
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @param int $nb_month Nombre de mois de decalage
	 * @param Bool $anterieur TRUE si la date calculee est anterieur a la date de depart
	 * @return string Renvoi la date calculee.
	 * @throws Exception
	 */
	public function retrouve_month($date, $nb_month, $anterieur = false) {
		$hash_date = $this->parse_date ( $date );
		if ($hash_date && is_int ( $nb_month )) {
			if ($anterieur)
				$date_timestamp = mktime ( 0, 0, 0, $hash_date ['month'] - $nb_month, 01, $hash_date ['year'] );
			else
				$date_timestamp = mktime ( 0, 0, 0, $hash_date ['month'] + $nb_month, 01, $hash_date ['year'] );
			$CODE_RETOUR = date ( "Ymd", $date_timestamp );
		}
		
		return $CODE_RETOUR;
	}

	/**
	 *
	 * Permet de retrouver le trimestre correspondant a la date en argument
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @return string Renvoi la date calculee au format YYYYMM01.
	 * @throws Exception
	 */
	public function retrouve_trimestre($date) {
		$hash_date = $this->parse_date ( $date );
		switch ($hash_date ['month']) {
			case "01" :
			case "02" :
			case "03" :
				return $hash_date ['year'] . "0101";
				break;
			case "04" :
			case "05" :
			case "06" :
				return $hash_date ['year'] . "0401";
				break;
			case "07" :
			case "08" :
			case "09" :
				return $hash_date ['year'] . "0701";
				break;
			case "10" :
			case "11" :
			case "12" :
				return $hash_date ['year'] . "1001";
				break;
		}
		
		return false;
	}

	/**
	 *
	 * Permet comparer des dates.<br>
	 *         Renvoi TRUE si $date < $date_reference<br>
	 *         Renvoi FALSE si $date >= $date_reference
	 *        
	 * @param string $date Date a comparer au format YYYYMMDD
	 * @param int $date_reference Date de reference au format YYYYMMDD
	 * @return Bool Renvoi TRUE si la date est inferieur a la date de reference ou FALSE sinon.
	 * @throws Exception
	 */
	public function compare_dates($date, $date_reference) {
		// renvoi true si $date < $date_reference
		// renvoi false si $date >= $date_reference
		$hash_date = $this->parse_date ( $date );
		$hash_date_reference = $this->parse_date ( $date_reference );
		$date = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'], $hash_date ['year'] );
		$date_reference = mktime ( 0, 0, 0, $hash_date_reference ['month'], $hash_date_reference ['day'], $hash_date_reference ['year'] );
		if ($date < $date_reference)
			$CODE_RETOUR = TRUE;
		else
			$CODE_RETOUR = FALSE;
		
		return $CODE_RETOUR;
	}

	/**
	 *
	 * Retrouve le lundi qui precede la date donnee.
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @return string false la date au format YYYYMMDD ou FALSE en cas d'erreur
	 * @throws Exception
	 */
	public function retrouve_lundi_precedent($date) {
		$hash_date = $this->parse_date ( $date );
		$CODE_RETOUR = FALSE;
		if ($hash_date) {
			for($i = 0; $i < 8; $i ++) {
				$timestamp = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'] - $i, $hash_date ['year'] );
				if (strftime ( "%A", $timestamp ) == "Monday") {
					$CODE_RETOUR = date ( "Ymd", $timestamp );
					break;
				}
			}
		}
		return $CODE_RETOUR;
	}

	/**
	 *
	 * Retrouve le dimanche qui suit la date donnee.
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @return string false la date au format YYYYMMDD ou FALSE en cas d'erreur
	 * @throws Exception
	 */
	public function retrouve_dimanche_suivant($date) {
		$hash_date = $this->parse_date ( $date );
		$CODE_RETOUR = FALSE;
		if ($hash_date) {
			for($i = 0; $i < 8; $i ++) {
				$timestamp = mktime ( 0, 0, 0, $hash_date ['month'], $hash_date ['day'] + $i, $hash_date ['year'] );
				if (strftime ( "%A", $timestamp ) == "Sunday") {
					$CODE_RETOUR = date ( "Ymd", $timestamp );
					break;
				}
			}
		}
		return $CODE_RETOUR;
	}

	/**
	 *
	 * Retrouve le dernier jour du mois de la date donnee.
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @return string false la date au format YYYYMMDD ou FALSE en cas d'erreur
	 * @throws Exception
	 */
	public function retrouve_dernier_du_mois($date) {
		$hash_date = $this->parse_date ( $date );
		$CODE_RETOUR = FALSE;
		if ($hash_date) {
			$timestamp_mois_suivant = mktime ( 0, 0, 0, $hash_date ['month'] + 1, 01, $hash_date ['year'] );
			$timestamp = mktime ( 0, 0, 0, date ( "m", $timestamp_mois_suivant ), date ( "d", $timestamp_mois_suivant ) - 1, date ( "Y", $timestamp_mois_suivant ) );
			$CODE_RETOUR = date ( "Ymd", $timestamp );
		}
		return $CODE_RETOUR;
	}

	/**
	 *
	 * Retrouve le dernier jour du mois de la date donnee.
	 *        
	 * @param string $date Date de depart au format YYYYMMDD
	 * @return string false la date au format YYYYMMDD ou FALSE en cas d'erreur
	 * @throws Exception
	 */
	public function retrouve_mois_str_fr($date) {
		$hash_date = $this->parse_date ( $date );
		$CODE_RETOUR = FALSE;
		if ($hash_date) {
			switch ($hash_date ['month']) {
				case '01' :
					$CODE_RETOUR = "Janvier";
					break;
				case '02' :
					$CODE_RETOUR = "F&eacute;vrier";
					break;
				case '03' :
					$CODE_RETOUR = "Mars";
					break;
				case '04' :
					$CODE_RETOUR = "Avril";
					break;
				case '05' :
					$CODE_RETOUR = "Mai";
					break;
				case '06' :
					$CODE_RETOUR = "Juin";
					break;
				case '07' :
					$CODE_RETOUR = "Juillet";
					break;
				case '08' :
					$CODE_RETOUR = "Ao&ucirc;t";
					break;
				case '09' :
					$CODE_RETOUR = "Septembre";
					break;
				case '10' :
					$CODE_RETOUR = "Octobre";
					break;
				case '11' :
					$CODE_RETOUR = "Novembre";
					break;
				case '12' :
					$CODE_RETOUR = "D&eacute;cembre";
					break;
			}
		}
		
		return $CODE_RETOUR;
	}

	/**
	 * valide que la date passee en argument est un jour ferie.
	 *
	 * @param string $date Date de depart au format YYYYMMDD
	 * @return int | boolean 1 si OK, 0 si NOK, false si erreur
	 * @throws Exception
	 */
	public function est_feries($date) {
		$hash_date = $this->parse_date ( $date );
		$lstJF = $this->getjoursferies ();
		if (count ( $lstJF ) == 0) {
			// @codeCoverageIgnoreStart
			return $this->onError ( "Impossible de recuperer les jours feries" );
			// @codeCoverageIgnoreEnd
		}
		if (! isset ( $lstJF [$hash_date ["year"]] )) {
			return $this->onError ( "la date n'est pas dans la bonne annee" );
		}
		if (in_array ( $this->extraire_timestamp ( $date ), $lstJF [$hash_date ["year"]] )) {
			return 1;
		}
		return 0;
	}

	/**
	 * Calcule la liste des jours feries francais.
	 *
	 * @param string $date Date de depart au format YYYYMMDD
	 * @return array liste des jours feries
	 * @throws Exception
	 */
	public function creer_jours_feries() {
		$holidays = array ();
		foreach ( $this->getListeDates () as $date ) {
			try {
				$hash_date = $this->parse_date ( $date );
			} catch ( Exception $e ) {
				continue;
			}
			if (isset ( $holidays [$hash_date ['year']] )) {
				continue;
			}
			
			$easterDate = easter_date ( $hash_date ['year'] );
			$easterDay = date ( 'j', $easterDate );
			$easterMonth = date ( 'n', $easterDate );
			$easterYear = date ( 'Y', $easterDate );
			
			$holidays [$hash_date ['year']] = array (
					// Jours feries fixes
					mktime ( 0, 0, 0, 1, 1, $hash_date ['year'] ), // 1er janvier
					mktime ( 0, 0, 0, 5, 1, $hash_date ['year'] ), // Fete du travail
					mktime ( 0, 0, 0, 5, 8, $hash_date ['year'] ), // Victoire des allies
					mktime ( 0, 0, 0, 7, 14, $hash_date ['year'] ), // Fete nationale
					mktime ( 0, 0, 0, 8, 15, $hash_date ['year'] ), // Assomption
					mktime ( 0, 0, 0, 11, 1, $hash_date ['year'] ), // Toussaint
					mktime ( 0, 0, 0, 11, 11, $hash_date ['year'] ), // Armistice
					mktime ( 0, 0, 0, 12, 25, $hash_date ['year'] ), // Noel
					

					// Jour feries qui dependent de paques
					mktime ( 0, 0, 0, $easterMonth, $easterDay + 1, $easterYear ), // Lundi de paques
					mktime ( 0, 0, 0, $easterMonth, $easterDay + 39, $easterYear ), // Ascension
					mktime ( 0, 0, 0, $easterMonth, $easterDay + 50, $easterYear )  // Pentecote
			);
			
			sort ( $holidays [$hash_date ['year']] );
			$this->ajouteListeJoursFeries ( $hash_date ['year'], $holidays [$hash_date ['year']] );
		}
		
		return true;
	}

	/**
	 * ************* ACCESSEURS ******************
	 */
	/**
	 * ACCESSEURS get
	 * @throws Exception
	 * @codeCoverageIgnore
	 */
	public function getjoursferies() {
		if (count ( $this->liste_feries ) === 0) {
			$this->creer_jours_feries ();
		}
		
		return $this->liste_feries;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 */
	public function setjoursferies($liste_feries) {
		$this->liste_feries = $liste_feries;
		return $this;
	}

	/**
	 * ACCESSEURS set
	 * @codeCoverageIgnore
	 */
	public function ajouteListeJoursFeries($year, $liste_feries) {
		$this->liste_feries [$year] = $liste_feries;
		return $this;
	}

	/**
	 * Accesseur en lecture a la liste des dates
	 * @codeCoverageIgnore
	 *
	 * @return array Renvoi la liste des dates.
	 */
	public function getListeDates() {
		return $this->liste_dates;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $liste_dates
	 * @return dates
	 */
	public function setListeDates($liste_dates) {
		$this->liste_dates = $liste_dates;
		return $this;
	}

	/**
	 * Accesseur en lecture a la liste des lundis
	 * @codeCoverageIgnore
	 *
	 * @return array Renvoi la liste des lundis.
	 */
	public function getListeWeek() {
		return $this->liste_week;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $liste_week
	 * @return dates
	 */
	public function setListeWeek($liste_week) {
		$this->liste_week = $liste_week;
		return $this;
	}

	/**
	 * Accesseur en lecture a la liste des mois
	 * @codeCoverageIgnore
	 *
	 * @return array Renvoi la liste des mois.
	 */
	public function getListeMonth() {
		return $this->liste_month;
	}

	/**
	 * @codeCoverageIgnore
	 * @param array $liste_month
	 * @return dates
	 */
	public function setListeMonth($liste_month) {
		$this->liste_month = $liste_month;
		return $this;
	}

	/**
	 * ************* ACCESSEURS ******************
	 */
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echoAffichie le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "en ligne de commande :";
		$help [__CLASS__] ["text"] [] .= "\t--date=YYYYMMDD";
		$help [__CLASS__] ["text"] [] .= "\t--date_debut=YYYYMMDD";
		$help [__CLASS__] ["text"] [] .= "\t--date_fin=YYYYMMDD";
		$help [__CLASS__] ["text"] [] .= "\t--ajouter_week_extreme";
		$help [__CLASS__] ["text"] [] .= "\t--ajouter_month_extreme";
		$help [__CLASS__] ["text"] [] .= "\t--ajouter_dates_feries";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "par XML : date | date_debut | date_fin | ajouter_week_extreme | ajouter_month_extreme | ajouter_dates_feries";
		$help [__CLASS__] ["text"] [] .= "";
		$help [__CLASS__] ["text"] [] .= "Si date_fin n'est pas precise alors on prend la date du jour en cours";
		$help [__CLASS__] ["text"] [] .= "Les textes type \"-1 day\" peuvent etre utilises";
		
		return $help;
	}
}
// Fin de la class
?>
