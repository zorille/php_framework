<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

use Exception as Exception;

/**
 * class enveloppe<br> Gere la creation et l'envoi de mail
 * @package Lib
 * @subpackage Mail
 */
class enveloppe extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $mail_to = array ();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mail_from = "";
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $mail_cc = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $mail_bcc = array ();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mail_charset = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mail_corp_text = "";
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	private $mail_corp_text_flag = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mail_corp_html = "";
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	private $mail_corp_html_flag = false;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $sujet = "";
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	private $mail_sujet_encode = true;
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $fichier_attache = array ();
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	private $fichier_attache_flag = false;
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	private $no_mail = true;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type message. Arguments reconnus :<br> --mail_using=oui/non <br> --mail_to=\"xx xx ... xx\" <br> --mail_cc=\"xx xx ... xx\" <br> --mail_bcc=\"xx xx ... xx\" <br> --mail_from=xx <br> --mail_charset=xx <br> --mail_sort_en_erreur=oui/non<br> --no_mail <br> Permet de desactiver l'envoi du mail dans la fonction creer_liste_mail.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return message
	 */
	static function &creer_enveloppe(
			&$liste_option,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		$objet = new enveloppe ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return message
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		$this->prepare_param ();
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	function __construct(
			$sort_en_erreur = "oui",
			$entete = __CLASS__) {
		// Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		return $this;
	}

	/**
	 * Prepare les parametres recuperes en ligne de commande
	 * @return enveloppe
	 */
	public function prepare_param() {
		$this->prepare_to ()
			->prepare_cc ()
			->prepare_bcc ()
			->prepare_from ()
			->prepare_charset ();
		if ($this->getListeOptions ()
			->verifie_option_existe ( "no_mail" ) !== false) {
			$mail->setNoMail ( true );
		} else {
			$this->setNoMail ( false );
		}
		return $this;
	}

	/**
	 * Prepare le TO a partir de la configuration ou de la ligne de commande
	 * @return enveloppe
	 * @throws Exception
	 */
	public function prepare_to() {
		$liste_mail = array ();
		if ($this->getListeOptions ()
			->verifie_option_existe ( "mail_to", true ) !== false) {
			$liste_mail = $this->getListeOptions ()
				->getOption ( "mail_to" );
			if (! is_array ( $liste_mail )) {
				$liste_mail = explode ( " ", $liste_mail );
			}
			$this->getListeOptions ()
				->setOption ( "mail_using", "oui" );
		} elseif ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"mail",
				"to"
		), true ) !== false) {
			$liste_mail = $this->getListeOptions ()
				->getOption ( array (
					"mail",
					"to"
			) );
			if (! is_array ( $liste_mail )) {
				$liste_mail = array (
						$liste_mail
				);
			}
		} else {
			$this->onWarning ( "Mail TO non existant" );
		}
		return $this->setMailTo ( $liste_mail );
	}

	/**
	 * Prepare le CC a partir de la configuration ou de la ligne de commande
	 * @return enveloppe
	 * @throws Exception
	 */
	public function prepare_cc() {
		if ($this->getListeOptions ()
			->verifie_option_existe ( "mail_cc", true ) !== false) {
			$liste_mail = $this->getListeOptions ()
				->getOption ( "mail_cc" );
			if (! is_array ( $liste_mail )) {
				$liste_mail = explode ( " ", $liste_mail );
			}
		} elseif ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"mail",
				"cc"
		), true ) !== false) {
			$liste_mail = $this->getListeOptions ()
				->getOption ( array (
					"mail",
					"cc"
			) );
			if (! is_array ( $liste_mail )) {
				$liste_mail = array (
						$liste_mail
				);
			}
		} else
			$liste_mail = array ();
		return $this->setMailCc ( $liste_mail );
	}

	/**
	 * Prepare le BCC a partir de la configuration ou de la ligne de commande
	 * @return enveloppe
	 * @throws Exception
	 */
	public function prepare_bcc() {
		if ($this->getListeOptions ()
			->verifie_option_existe ( "mail_bcc", true ) !== false) {
			$liste_mail = explode ( " ", $this->getListeOptions ()
				->getOption ( "mail_bcc" ) );
		} elseif ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"mail",
				"bcc"
		), true ) !== false) {
			$liste_mail = $this->getListeOptions ()
				->getOption ( array (
					"mail",
					"bcc"
			) );
			if (! is_array ( $liste_mail )) {
				$liste_mail = array (
						$liste_mail
				);
			}
		} else
			$liste_mail = array ();
		return $this->setMailBcc ( $liste_mail );
	}

	/**
	 * Prepare le FROM a partir de la configuration ou de la ligne de commande
	 * @return enveloppe
	 * @throws Exception
	 */
	public function prepare_from() {
		if ($this->getListeOptions ()
			->verifie_option_existe ( "mail_from", true ) !== false) {
			$mail_from = $this->getListeOptions ()
				->getOption ( "mail_from" );
		} elseif ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"mail",
				"from"
		) ) !== false) {
			$mail_from = $this->getListeOptions ()
				->getOption ( array (
					"mail",
					"from"
			) );
		} else
			$mail_from = "nobody@societe.com";
		return $this->setMailFrom ( $mail_from );
	}

	/**
	 * Prepare le FROM a partir de la configuration ou de la ligne de commande
	 * @return enveloppe
	 * @throws Exception
	 */
	public function prepare_charset() {
		if ($this->getListeOptions ()
			->verifie_option_existe ( "mail_charset", true ) !== false) {
			$charset = $this->getListeOptions ()
				->getOption ( "mail_charset" );
		} elseif ($this->getListeOptions ()
			->verifie_option_existe ( array (
				"mail",
				"charset"
		) ) !== false) {
			$charset = $this->getListeOptions ()
				->getOption ( array (
					"mail",
					"charset"
			) );
		} else
			$charset = "ISO-8859-1";
		return $this->setCharset ( $charset );
	}

	/**
	 * Accesseur en ecriture<br> Ajoute du texte quelquesoit soit format.
	 *
	 * @param string $texte Texte a ajouter dans le mail.
	 * @return enveloppe
	 */
	public function ecrit_text(
			$texte) {
		$this->setMailCorpText ( $this->getMailCorpText () . $texte );
		$this->setMailCorpTextFlag ( true );
		return $this;
	}

	/**
	 * Accesseur en ecriture<br> Ajoute du texte HTML.
	 *
	 * @param string $texte Texte HTML a ajouter dans le mail.
	 * @return enveloppe
	 */
	public function ecrit_html(
			$texte) {
		$this->setMailCorpHtml ( $this->getMailCorpHtml () . $texte );
		$this->setMailCorpHtmlFlag ( true );
		return $this;
	}

	/**
	 * Cree le sujet du mail qui contient du texte.
	 *
	 * @return string Renvoi le sujet du mail.
	 */
	public function prepare_sujet() {
		if ($this->getMailSujetEncode ())
			$sujet_local = "=?" . $this->getCharset () . "?B?" . base64_encode ( $this->getSujet () ) . "?=";
		else
			$sujet_local = $this->getSujet ();
		return $sujet_local;
	}

	/**
	 * Prepare la liste des fichiers a attacher
	 * @return enveloppe
	 * @throws Exception
	 */
	public function prepare_liste_fichiers_attaches(
			$liste_fichiers) {
		if (is_array ( $liste_fichiers ) && ! empty ( $liste_fichiers )) {
			return $this->setFichierAttache ( $liste_fichiers )
				->setFichierAttacheFlag ( true );
		}
		return $this->setFichierAttache ( explode ( " ", $liste_fichiers ) )
			->setFichierAttacheFlag ( true );
	}

	/**
	 * ******************* ACCESSEURS **************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getMailTo() {
		return $this->mail_to;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailTo(
			$mail_to) {
		if ($this->getListeOptions ()
			->verifie_option_existe ( "force_mail", true )) {
			$this->mail_to = array (
					$this->getListeOptions ()
						->getOption ( "force_mail" )
			);
			$this->onInfo ( "On force l'email sur : " . $this->mail_to[0] );
		} else {
			$this->mail_to = $mail_to;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailFrom() {
		return $this->mail_from;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailFrom(
			$mail_from) {
		$this->mail_from = $mail_from;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailCc() {
		return $this->mail_cc;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailCc(
			$mail_cc) {
		if ($this->getListeOptions ()
			->verifie_option_existe ( "force_mail", true )) {
			$this->mail_cc = array ();
		} else {
			$this->mail_cc = $mail_cc;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailBcc() {
		return $this->mail_bcc;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailBcc(
			$mail_bcc) {
		if ($this->getListeOptions ()
			->verifie_option_existe ( "force_mail", true )) {
			$this->mail_bcc = array ();
		} else {
			$this->mail_bcc = $mail_bcc;
		}
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getNoMail() {
		return $this->no_mail;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setNoMail(
			$no_mail) {
		$this->no_mail = $no_mail;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSujet() {
		return $this->sujet;
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $sujet Sujet a ajouter au mail.
	 * @param bool $encode Encode le sujet ou pas.
	 */
	public function &setSujet(
			$sujet,
			$encode = true) {
		$this->sujet = $sujet;
		$this->setMailSujetEncode ( $encode );
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailSujetEncode() {
		return $this->mail_sujet_encode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailSujetEncode(
			$mail_sujet_encode) {
		$this->mail_sujet_encode = $mail_sujet_encode;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCharset() {
		return $this->mail_charset;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCharset(
			$charset) {
		$this->mail_charset = $charset;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFichierAttache() {
		return $this->fichier_attache;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFichierAttache(
			$fichier_attache) {
		$this->fichier_attache = $fichier_attache;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getFichierAttacheFlag() {
		return $this->fichier_attache_flag;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setFichierAttacheFlag(
			$fichier_attache_flag) {
		$this->fichier_attache_flag = $fichier_attache_flag;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailCorpText() {
		return $this->mail_corp_text;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailCorpText(
			$mail_corp_text) {
		$this->mail_corp_text = $mail_corp_text;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailCorpTextFlag() {
		return $this->mail_corp_text_flag;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailCorpTextFlag(
			$mail_corp_text_flag) {
		$this->mail_corp_text_flag = $mail_corp_text_flag;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailCorpHtml() {
		return $this->mail_corp_html;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailCorpHtml(
			$mail_corp_html) {
		$this->mail_corp_html = $mail_corp_html;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailCorpHtmlFlag() {
		return $this->mail_corp_html_flag;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailCorpHtmlFlag(
			$mail_corp_html_flag) {
		$this->mail_corp_html_flag = $mail_corp_html_flag;
		return $this;
	}

	/**
	 * ******************* ACCESSEURS **************
	 */
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des enveloppes mails";
		$help [__CLASS__] ["text"] [] .= "\t--mail_using=oui/non";
		$help [__CLASS__] ["text"] [] .= "\t--mail_to=\"xx xx ... xx\"";
		$help [__CLASS__] ["text"] [] .= "\t--mail_cc=\"xx xx ... xx\"";
		$help [__CLASS__] ["text"] [] .= "\t--mail_bcc=\"xx xx ... xx\"";
		$help [__CLASS__] ["text"] [] .= "\t--mail_from=xx";
		$help [__CLASS__] ["text"] [] .= "\t--mail_charset=UTF8 //optionnel par defaut ISO-8859-1";
		$help [__CLASS__] ["text"] [] .= "\t--no_mail Permet de desactiver l'envoi du mail";
		$help [__CLASS__] ["text"] [] .= "\t--force_mail adresse@domaine.com  Permet de forcer l'envoi du mail sur le mail en parametre (adresse@domaine.com)";
		return $help;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function __destruct() {
		return true;
	}
}
?>
