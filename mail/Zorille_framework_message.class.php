<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;
use \Exception as Exception;
/**
 * class message<br>
 *
 * Gere la creation et l'envoi de mail
 * @package Lib
 * @subpackage Mail
 */
class message extends abstract_log {
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $crlf = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mailing_list = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mail_entete = "";
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $mail_entete_content = array ();
	/**
	 * var privee
	 * @access private
	 * @var array
	 */
	private $mail_Content = array ();
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mail_footer = "";
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
	private $mail_additionnal = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $sujet = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $separateur = "";
	/**
	 * var privee
	 * @access private
	 * @var bool
	 */
	private $mail_sujet_encode = true;
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $fichier_attache;
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

	/*********************** Creation de l'objet *********************/
	/**
	 * Instancie un objet de type message.
	 * Arguments reconnus :<br>
	 * --mail_using=oui/non <br>
	 * --mail_to=\"xx xx ... xx\" <br>
	 * --mail_cc=\"xx xx ... xx\" <br>
	 * --mail_bcc=\"xx xx ... xx\" <br>
	 * --mail_from=xx <br>
	 * --mail_charset=xx <br>
	 * --mail_sort_en_erreur=oui/non<br>
	 * --no_mail <br>
	 *     Permet de desactiver l'envoi du mail dans la fonction creer_liste_mail.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return message
	 */
	static function &creer_message(&$liste_option, $sort_en_erreur = false, $entete = __CLASS__) {
		if ($liste_option->verifie_option_existe ( "mail_to", true ) !== false) {
			$liste_mail = $liste_option->getOption ( "mail_to" );
			if (! is_array ( $liste_mail )) {
				$liste_mail = explode ( " ", $liste_mail );
			}
			$liste_option->setOption ( "mail_using", "oui" );
		} elseif ($liste_option->verifie_option_existe ( array (
				"mail",
				"to" 
		), true ) !== false) {
			$liste_mail = $liste_option->getOption ( array (
					"mail",
					"to" 
			) );
			if (! is_array ( $liste_mail )) {
				$liste_mail = array (
						$liste_mail 
				);
			}
		} else {
			abstract_log::onDebug_standard ( "Mail to non existant", 1 );
			$retour=false;
			return $retour;
		}
		
		if ($liste_option->verifie_option_existe ( "mail_cc", true ) !== false) {
			$liste_mail_cc = $liste_option->getOption ( "mail_cc" );
			if (! is_array ( $liste_mail_cc )) {
				$liste_mail_cc = explode ( " ", $liste_mail_cc );
			}
		} elseif ($liste_option->verifie_option_existe ( array (
				"mail",
				"cc" 
		), true ) !== false) {
			$liste_mail_cc = $liste_option->getOption ( array (
					"mail",
					"cc" 
			) );
			if (! is_array ( $liste_mail_cc )) {
				$liste_mail_cc = array (
						$liste_mail_cc 
				);
			}
		} else
			$liste_mail_cc = "";
		
		if ($liste_option->verifie_option_existe ( "mail_bcc", true ) !== false) {
			$liste_mail_bcc = explode ( " ", $liste_option->getOption ( "mail_bcc" ) );
		} elseif ($liste_option->verifie_option_existe ( array (
				"mail",
				"bcc" 
		), true ) !== false) {
			$liste_mail_bcc = $liste_option->getOption ( array (
					"mail",
					"bcc" 
			) );
			if (! is_array ( $liste_mail_bcc )) {
				$liste_mail_bcc = array (
						$liste_mail_bcc 
				);
			}
		} else
			$liste_mail_bcc = "";
		
		if ($liste_option->verifie_option_existe ( "mail_from", true ) !== false) {
			$mail_from = $liste_option->getOption ( "mail_from" );
		} elseif ($liste_option->verifie_option_existe ( array (
				"mail",
				"from" 
		) ) !== false) {
			$mail_from = $liste_option->getOption ( array (
					"mail",
					"from" 
			) );
		} else
			$mail_from = "nobody@societe.com";
		
		if ($liste_option->verifie_option_existe ( "mail_sort_en_erreur", true ) !== false) {
			$sort_en_erreur = $liste_option->getOption ( "mail_sort_en_erreur" );
		} elseif ($liste_option->verifie_option_existe ( "mail[@sort_en_erreur='non']", true ) !== false) {
			$sort_en_erreur = "non";
		} else
			$sort_en_erreur = "oui";
		
		if ($liste_option->verifie_option_existe ( "mail_charset", true ) !== false) {
			$charset = $liste_option->getOption ( "mail_charset" );
		} elseif ($liste_option->verifie_option_existe ( array (
				"mail",
				"charset" 
		) ) !== false) {
			$charset = $liste_option->getOption ( array (
					"mail",
					"charset" 
			) );
		} else
			$charset = "ISO-8859-1";
		
		if (($liste_option->getOption ( "mail_using", true ) == "oui") || ($liste_option->verifie_option_existe ( "mail[@using='oui']", true ) !== false)) {
			$mail = new message ( $sort_en_erreur, $entete );
			$mail->setHeaders ( $liste_mail, $mail_from, $liste_mail_cc, $liste_mail_bcc )
				->setCharset ( $charset );
			if ($liste_option->verifie_option_existe ( "no_mail" ) !== false) {
				$mail->setNoMail ( true );
			} else {
				$mail->setNoMail ( false );
			}
			$mail->_initialise ( array (
					"options" => $liste_option 
			) );
		} else {
			abstract_log::onDebug_standard ( "Mail using non existant ou a non", 1 );
			$retour=false;
			return $retour;
		}
		
		abstract_log::onDebug_standard ( $mail, 2 );
		return $mail;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return message
	 */
	public function &_initialise($liste_class) {
		parent::_initialise ( $liste_class );
		return $this;
	}

	/*********************** Creation de l'objet *********************/
	
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur Prend les valeurs oui/non
	 */
	function __construct($sort_en_erreur = "oui", $entete = __CLASS__) {
		//Gestion de abstract_log
		parent::__construct ( $sort_en_erreur, $entete );
		
		
		$this->prepare_message_param ();
		
		return $this;
	}

	/**
	 * Mets en place les differents paramatres pour creer un mail
	 * @return message
	 */
	public function prepare_message_param() {
		$this->setCrlf ( chr ( 13 ) . chr ( 10 ) );
		
		$separateur = array (
				"mixed" => "=_mixed " . md5 ( uniqid ( time () ) ) . "_=",
				"alternative" => "=_NextPart_" . md5 ( uniqid ( time () ) ) . "_=" 
		);
		$this->setSeparateur ( $separateur );
		
		$mail_Content = array (
				"text" => "Content-Type: text/plain; charset=\"_charset_\"",
				"html" => "Content-Type: text/html; charset=\"_charset_\"",
				"mixed" => "Content-Type: multipart/mixed; boundary=\"" . $this->getOneSeparateur ( "mixed" ) . "\"",
				"alternative" => "Content-Type: multipart/alternative; boundary=\"" . $this->getOneSeparateur ( "alternative" ) . "\"" 
		);
		$this->setMailContent ( $mail_Content );
		
		$mail_footer = array (
				"mixed" => "--" . $this->getOneSeparateur ( "mixed" ) . "--" . $this->getCrlf (),
				"alternative" => "--" . $this->getOneSeparateur ( "alternative" ) . "--" . $this->getCrlf () 
		);
		$this->setMailFooter ( $mail_footer );
		
		$mail_entete = "MIME-Version: 1.0" . $this->getCrlf ();
		$mail_entete .= "X-Mailer: PHP/" . phpversion () . $this->getCrlf ();
		$mail_entete .= "[CONTENT_TYPE]" . $this->getCrlf ();
		$mail_entete .= "[CONTENT_ENCODE]";
		$this->setMailEntete ( $mail_entete )
			->setCharset ( "ISO-8859-1" );
		
		$mail_Encode = array (
				"text" => "Content-Transfer-Encoding: quoted-printable" . $this->getCrlf (),
				"html" => "Content-Transfer-Encoding: 7bit" . $this->getCrlf () 
		);
		$this->setMailEncode ( $mail_Encode );
		
		$this->setMailAdditionnal ( "-f " );
		
		return $this;
	}

	/**
	 * Accesseur en ecriture<br>
	 * Ajoute du texte quelquesoit soit format.
	 *
	 * @param string $texte Texte a ajouter dans le mail.
	 * @return message
	 */
	public function ecrit($texte) {
		$this->setMailCorpText ( $this->getMailCorpText () . $texte );
		$this->setMailCorpTextFlag ( true );
		
		return $this;
	}

	/**
	 * Accesseur en ecriture<br>
	 * Ajoute du texte HTML.
	 *
	 * @param string $texte Texte HTML a ajouter dans le mail.
	 * @return message
	 */
	public function ecrit_html($texte) {
		$this->setMailCorpHtml ( $this->getMailCorpHtml () . $texte );
		$this->setMailCorpHtmlFlag ( true );
		
		return $this;
	}

	/**
	 * Attache un fichier.<br>
	 *
	 * @param string $fichier Chemin complet du fichier a attacher.
	 * @param string $mime_type Ajout d'un mime type s'il est connue.
	 * @return Bool TRUE si tout est OK, FALSE sinon.
	 */
	public function attache_fichier($fichier, $mime_type = "application/octet-stream") {
		$str = @file_get_contents ( $fichier );
		if ($str) {
			$this->setFichierAttache ( $this->getFichierAttache () . $this->getCrlf () . "--" . $this->getOneSeparateur ( "mixed" ) . $this->getCrlf () );
			$this->setFichierAttache ( $this->getFichierAttache () . "Content-Type: " . $mime_type . "; name=\"" . basename ( $fichier ) . "\"" . $this->getCrlf () );
			$this->setFichierAttache ( $this->getFichierAttache () . "Content-Disposition: attachment; filename=\"" . basename ( $fichier ) . "\"" . $this->getCrlf () );
			$this->setFichierAttache ( $this->getFichierAttache () . "Content-Transfer-Encoding: base64" . $this->getCrlf () . $this->getCrlf () );
			
			$this->setFichierAttache ( $this->getFichierAttache () . chunk_split ( base64_encode ( $str ) ) );
			$this->setFichierAttache ( $this->getFichierAttache () . $this->getCrlf () );
			
			$this->setFichierAttacheFlag ( true );
			
			$CODE_RETOUR = true;
		} else
			$CODE_RETOUR = false;
		
		return $CODE_RETOUR;
	}

	/**
	 * Prepare l'entete MIME du mail avec le CONTENT TYPE.<br>
	 * Si il y a du texte et de l'html, il ajoute la balise "alternative".<br>
	 * Si il y a un ou plusieurs fichier attache(s), il ajoute la balise "mixed"
	 *
	 * @return message
	 */
	public function ajoute_mail_header_content_type() {
		//Si on a un fichier, le content est de type mixed
		if ($this->getFichierAttacheFlag ())
			$this->setMailEntete ( str_replace ( "[CONTENT_TYPE]", $this->getOneMailContent ( "mixed" ), $this->getMailEntete () ) );
			//Sinon, si on a du texte et du html, le content est de type alternative
		elseif ($this->getMailCorpTextFlag () && $this->getMailCorpHtmlFlag ())
			$this->setMailEntete ( str_replace ( "[CONTENT_TYPE]", $this->getOneMailContent ( "alternative" ), $this->getMailEntete () ) );
			//Sinon, si on a que du texte, le content est de type text
		elseif ($this->getMailCorpTextFlag ()) {
			$entete_text = str_replace ( "_charset_", $this->getCharset (), $this->getOneMailContent ( "text" ) );
			$this->setMailEntete ( str_replace ( "[CONTENT_TYPE]", $entete_text, $this->getMailEntete () ) );
			//Sinon, si on a que du html, le content est de type html
		} elseif ($this->getMailCorpHtmlFlag ()) {
			$entete_html = str_replace ( "_charset_", $this->getCharset (), $this->getOneMailContent ( "html" ) );
			$this->setMailEntete ( str_replace ( "[CONTENT_TYPE]", $entete_html, $this->getMailEntete () ) );
		}
		
		return $this;
	}

	/**
	 * Prepare l'entete MIME du mail avec le CONTENT TRANSFERT ENCODING.<br>
	 * Si il y a la balise "alternative" et ou mixed, le transfert encoding est dans chaque sous parties.<br>
	 * Sinon on le met dans l'entete.
	 *
	 * @return message
	 */
	public function ajoute_mail_header_encoding() {
		//Si on a un fichier, le encoding est dans le body
		if ($this->getFichierAttacheFlag ())
			$this->setMailEntete ( str_replace ( "[CONTENT_ENCODE]", "", $this->getMailEntete () ) );
			//Sinon, si on a du texte et du html, le encoding est dans le body aussi
		elseif ($this->getMailCorpTextFlag () && $this->getMailCorpHtmlFlag ())
			$this->setMailEntete ( str_replace ( "[CONTENT_ENCODE]", "", $this->getMailEntete () ) );
			//Sinon, si on a que du texte, le encoding est dans l'entete
		elseif ($this->getMailCorpTextFlag ())
			$this->setMailEntete ( str_replace ( "[CONTENT_ENCODE]", $this->getOneMailEncode ( "text" ), $this->getMailEntete () ) );
			//Sinon, si on a que du html, le encoding est dans l'entete
		elseif ($this->getMailCorpHtmlFlag ())
			$this->setMailEntete ( str_replace ( "[CONTENT_ENCODE]", $this->getOneMailEncode ( "html" ), $this->getMailEntete () ) );
		
		return $this;
	}

	/**
	 * Cree le corp TEXTE du mail.<br>
	 *
	 * @param bool $ajoute_entete Permet d'ajouter les entetes MIME ou non.
	 * @return string Renvoi le corp au format texte.
	 */
	public function ajoute_corp_text($ajoute_entete = false) {
		$corp = "";
		if ($ajoute_entete) {
			$corp .= str_replace ( "_charset_", $this->getCharset (), $this->getOneMailContent ( "text" ) ) . $this->getCrlf ();
			$corp .= $this->getOneMailEncode ( "text" ) . $this->getCrlf ();
		}
		$corp .= $this->getMailCorpText ();
		
		return $corp;
	}

	/**
	 * Cree le corp HTML du mail.<br>
	 *
	 * @param bool $ajoute_entete Permet d'ajouter les entetes MIME ou non.
	 * @return string Renvoi le corp au format html.
	 */
	public function ajoute_corp_html($ajoute_entete = false) {
		$corp = "";
		if ($ajoute_entete) {
			$corp .= str_replace ( "_charset_", $this->getCharset (), $this->getOneMailContent ( "html" ) ) . $this->getCrlf ();
			$corp .= $this->getOneMailEncode ( "html" ) . $this->getCrlf ();
		}
		$corp .= $this->getCrlf () . $this->getMailCorpHtml();
		
		return $corp;
	}

	/**
	 * Cree le corp du mail qui contient du texte (html ou text).
	 *
	 * @return string Renvoi le corp contenant du texte.
	 */
	public function prepare_corp_textuel() {
		$corp = "";
		if ($this->getMailCorpTextFlag () && $this->getMailCorpHtmlFlag ()) {
			$corp .= $this->getCrlf () . "--" . $this->getOneSeparateur ( "alternative" ) . $this->getCrlf ();
			$corp .= $this->ajoute_corp_text ( true );
			$corp .= $this->getCrlf () . "--" . $this->getOneSeparateur ( "alternative" ) . $this->getCrlf ();
			$corp .= $this->ajoute_corp_html ( true );
			$corp .= $this->getCrlf () . $this->getOneMailFooter ( "alternative" ) . $this->getCrlf ();
		} elseif ($this->getMailCorpTextFlag ()) {
			if ($this->getFichierAttacheFlag ())
				$corp = $this->ajoute_corp_text ( true );
			else
				$corp = $this->ajoute_corp_text ( false );
		} elseif ($this->getMailCorpHtmlFlag ()) {
			if ($this->getFichierAttacheFlag ())
				$corp = $this->ajoute_corp_html ( true );
			else
				$corp = $this->ajoute_corp_html ( false );
		}
		
		return $corp;
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
	 * Prepare le corp du mail.<br>
	 * Si il y a du texte et de l'html, il ajoute la balise "alternative".<br>
	 * Si il y a un ou plusieurs fichier attache(s), il ajoute la balise "mixed"
	 * et attache le(s) fichier(s).
	 *
	 * @return string Renvoi le corp du texte contenant le mail.
	 */
	public function prepare_envoi() {
		$corp = "";
		//on traite l'entete
		$this->ajoute_mail_header_content_type ();
		$this->ajoute_mail_header_encoding ();
		
		if ($this->getMailCorpTextFlag () && $this->getMailCorpHtmlFlag ()) {
			if ($this->getFichierAttacheFlag ())
				$corp .= $this->getOneMailContent ( "alternative" ) . $this->getCrlf ();
		}
		$corp .= $this->prepare_corp_textuel ();
		
		if ($this->getFichierAttacheFlag ()) {
			$corp = $this->getCrlf () . "--" . $this->getOneSeparateur ( "mixed" ) . $this->getCrlf () . $corp;
			$corp .= $this->getFichierAttache ();
			$corp .= $this->getCrlf () . $this->getOneMailFooter ( "mixed" ) . $this->getCrlf ();
		}
		
		return $corp;
	}

	/**
	 * Envoi le mail.<br>
	 * Une liste d'adresse peut venir surcharger la liste existante
	 * au moment de l'envoi grace a "$to".
	 *
	 * @param string $to Liste d'adresse mail separes par des virgules.
	 * @return Bool TRUE si tout est OK, FALSE sinon.
	 * @throws Exception
	 */
	public function envoi($to = "") {
		if ($to == "")
			$to = $this->getMailingList ();
		$sujet_local = $this->prepare_sujet ();
		
		$this->onDebug ( "Mail To " . $to . " Sujet : " . $sujet_local, 1 );
		if ($this->getNoMail () === false) {
			// @codeCoverageIgnoreStart
			$CODE_RETOUR = mail ( $to, $sujet_local, $this->prepare_envoi (), $this->getMailEntete (), $this->getMailAdditionnal () );
			if (! $CODE_RETOUR) {
				return $this->onError ( "Probleme lors de l'envoi du mail" );
			} else {
				$this->onInfo ( "Mail envoye" );
			}
		} else {
			// @codeCoverageIgnoreEnd
			$this->onInfo ( "Envoi de mail desactive." );
			$CODE_RETOUR=true;
		}
		return $CODE_RETOUR;
	}

	/********************* ACCESSEURS ***************/
	/**
	 * @codeCoverageIgnore
	 */
	public function getCrlf() {
		return $this->crlf;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCrlf($crlf) {
		$this->crlf = $crlf;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSeparateur() {
		return $this->separateur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneSeparateur($type) {
		return $this->separateur [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSeparateur($separateur) {
		$this->separateur = $separateur;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailContent() {
		return $this->mail_Content;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneMailContent($type) {
		return $this->mail_Content [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailContent($mail_Content) {
		$this->mail_Content = $mail_Content;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailEncode() {
		return $this->mail_Encode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneMailEncode($type) {
		return $this->mail_Encode [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailEncode($mail_Encode) {
		$this->mail_Encode = $mail_Encode;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailFooter() {
		return $this->mail_footer;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneMailFooter($type) {
		return $this->mail_footer [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailFooter($mail_Encode) {
		$this->mail_footer = $mail_Encode;
		
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
	function &setNoMail($no_mail) {
		$this->no_mail = $no_mail;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getMailAdditionnal() {
		return $this->mail_additionnal;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setMailAdditionnal($mail_additionnal) {
		$this->mail_additionnal = $mail_additionnal;
		
		return $this;
	}

	/**
	 * Accesseur en lecture<br>
	 * Renvoi la liste des adresses mails separes par des virgules.
	 * @codeCoverageIgnore
	 * @return string Liste des adresses mails.
	 */
	public function getMailingList() {
		return $this->mailing_list;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailingList($mailing_list) {
		$this->mailing_list = $mailing_list;
		
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
	public function &setSujet($sujet, $encode = true) {
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
	public function &setMailSujetEncode($mail_sujet_encode) {
		$this->mail_sujet_encode = $mail_sujet_encode;
		
		return $this;
	}

	/**
	 * Accesseur en ecriture<br>
	 * Ajoute un sujet au mail.
	 * @deprecated
	 * @codeCoverageIgnore
	 * @param string $sujet Sujet a ajouter au mail.
	 * @param bool $encode Encode le sujet ou pas.
	 * @return true
	 */
	public function sujet($sujet, $encode = true) {
		return $this->setSujet ( $sujet, $encode );
	}

	/**
	 * Creer le Header du mail avec les adresses d'envoi
	 *
	 * @param string $to Adresses Emails separes par des virgules.
	 * @param string $from Adresse Email de l'expediteur.
	 * @param string $cc Adresses Emails en copie separes par des virgules.
	 * @param string $bcc Adresses Emails en copie cache separes par des virgules.
	 * @return message
	 */
	public function &setHeaders($to, $from, $cc = "", $bcc = "") {
		if (is_array ( $to ))
			$this->setMailingList ( implode ( ", ", $to ) );
		$entete = $this->getMailEntete ();
		$entete .= "From: " . $from . $this->getCrlf ();
		$this->setMailAdditionnal ( $this->getMailAdditionnal () . $from );
		if ($cc != "" && is_array ( $cc ))
			$entete .= "Cc: " . implode ( ", ", $cc ) . $this->getCrlf ();
		if ($bcc != "" && is_array ( $bcc ))
			$entete .= "Bcc: " . implode ( ", ", $bcc ) . $this->getCrlf ();
		$entete .= "Reply-To: " . $from . $this->getCrlf ();
		
		$this->setMailEntete ( $entete );
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
	public function &setCharset($charset) {
		$this->mail_charset = $charset;
		
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailEntete() {
		return $this->mail_entete;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailEntete($mail_entete) {
		$this->mail_entete = $mail_entete;
		
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
	public function &setFichierAttache($fichier_attache) {
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
	public function &setFichierAttacheFlag($fichier_attache_flag) {
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
	public function &setMailCorpText($mail_corp_text) {
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
	public function &setMailCorpTextFlag($mail_corp_text_flag) {
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
	public function &setMailCorpHtml($mail_corp_html) {
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
	public function &setMailCorpHtmlFlag($mail_corp_html_flag) {
		$this->mail_corp_html_flag = $mail_corp_html_flag;
		
		return $this;
	}

	/********************* ACCESSEURS ***************/
	
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @param string $echo Affiche le help
	 * @return string Renvoi le help
	 */
	static function help() {
		$help = parent::help ();
		
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des mails";
		$help [__CLASS__] ["text"] [] .= "\t--mail_using=oui/non";
		$help [__CLASS__] ["text"] [] .= "\t--mail_to=\"xx xx ... xx\"";
		$help [__CLASS__] ["text"] [] .= "\t--mail_cc=\"xx xx ... xx\"";
		$help [__CLASS__] ["text"] [] .= "\t--mail_bcc=\"xx xx ... xx\"";
		$help [__CLASS__] ["text"] [] .= "\t--mail_from=xx";
		$help [__CLASS__] ["text"] [] .= "\t--mail_sujet \"sujet\"";
		$help [__CLASS__] ["text"] [] .= "\t--mail_charset=UTF8 //optionnel par defaut ISO-8859-1";
		$help [__CLASS__] ["text"] [] .= "\t--mail_sort_en_erreur=oui/non";
		$help [__CLASS__] ["text"] [] .= "\t--no_mail Permet de desactiver l'envoi du mail";
		
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
