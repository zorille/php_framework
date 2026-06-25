<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

use Exception as Exception;

/**
 * class message<br> Gere la creation et l'envoi de mail en mode MIME
 * @package Lib
 * @subpackage Mail
 */
class message extends enveloppe {
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
	 * @var string
	 */
	private $mail_destinations = "";
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
	private $content_type = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $content_encode = "";
	/**
	 * var privee
	 * @access private
	 * @var string
	 */
	private $mail_Encode = "";
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
	private $separateur = "";

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type message. Arguments reconnus :<br> --mail_using=oui/non <br> --mail_to=\"xx xx ... xx\" <br> --mail_cc=\"xx xx ... xx\" <br> --mail_bcc=\"xx xx ... xx\" <br> --mail_from=xx <br> --mail_charset=xx <br> --mail_sort_en_erreur=oui/non<br> --no_mail <br> Permet de desactiver l'envoi du mail dans la fonction creer_liste_mail.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet
	 * @return message
	 */
	static function &creer_message(
		options     &$liste_option,
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__): message
	{
		$objet = new message ( $sort_en_erreur, $entete );
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
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		$this->prepare_message_param ();
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
	}

	/**
	 * Mets en place les differents paramatres pour creer un mail
	 * @return message
	 */
	public function prepare_message_param(): static
	{
		$this->setCrlf ( chr ( 13 ) . chr ( 10 ) );
		$this->prepare_MIME_Headers ();
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
	 * Creer le Header MIME du mail avec les adresses d'envoi
	 * @return message
	 */
	public function prepare_MIME_Headers(): static
	{
		if (is_array ( $this->getMailTo () )) {
			$this->setMailingList ( implode ( ", ", $this->getMailTo () ) );
		} else {
			$this->setMailingList ( $this->getMailTo () );
		}
		$entete = "From: " . $this->getMailFrom () . $this->getCrlf ();
		$this->setMailAdditionnal ( $this->getMailAdditionnal () . $this->getMailFrom () );
		if (! empty ( $this->getMailCc () )) {
			if (is_array ( $this->getMailCc () )) {
				$entete .= "Cc: " . implode ( ", ", $this->getMailCc () ) . $this->getCrlf ();
			} else {
				$entete .= "Cc: " . $this->getMailCc () . $this->getCrlf ();
			}
		}
		if (! empty ( $this->getMailBcc () )) {
			if (is_array ( $this->getMailBcc () )) {
				$entete .= "Bcc: " . implode ( ", ", $this->getMailBcc () ) . $this->getCrlf ();
			} else {
				$entete .= "Bcc: " . $this->getMailBcc () . $this->getCrlf ();
			}
		}
		$entete .= "Reply-To: " . $this->getMailFrom () . $this->getCrlf ();
		return $this->setMailHeaderDestination ( $entete );
	}

	/**
	 * Accesseur en ecriture<br> Ajoute du texte quelquesoit soit format.
	 *
	 * @param string $texte Texte a ajouter dans le mail.
	 * @return message
	 */
	public function ecrit(
		string $texte): message
	{
		return $this->ecrit_text ( $texte );
	}

	/**
	 * Compatibilite avec l'objet Message-0365
	 * @param array $liste_fichiers Liste des fichiers a envoyer avec leur chemin relatif
	 * @return message
	 */
	public function attache_fichier(
		array $liste_fichiers): static
	{
		$this->onDebug ( __METHOD__, 1 );
		foreach ( $liste_fichiers as $fichier ) {
			$this->attache_un_fichier ( $fichier );
		}
		return $this;
	}

	/**
	 * Attache un fichier.<br>
	 *
	 * @param string $fichier Chemin complet du fichier a attacher.
	 * @param string $mime_type Ajout d'un mime type s'il est connue.
	 * @return Bool TRUE si tout est OK, FALSE sinon.
	 */
	public function attache_un_fichier(
		string $fichier,
		string $mime_type = "application/octet-stream"): bool
	{
		$str = @file_get_contents ( $fichier );
		if ($str) {
			$liste_fichiers_attaches = $this->getFichierAttache ();
			$pos = count ( $liste_fichiers_attaches );
			$liste_fichiers_attaches [$pos] = $this->getCrlf () . "--" . $this->getOneSeparateur ( "mixed" ) . $this->getCrlf ();
			$liste_fichiers_attaches [$pos] .= "Content-Type: " . $mime_type . "; name=\"" . basename ( $fichier ) . "\"" . $this->getCrlf ();
			$liste_fichiers_attaches [$pos] .= "Content-Disposition: attachment; filename=\"" . basename ( $fichier ) . "\"" . $this->getCrlf ();
			$liste_fichiers_attaches [$pos] .= "Content-Transfer-Encoding: base64" . $this->getCrlf () . $this->getCrlf ();
			$liste_fichiers_attaches [$pos] .= chunk_split ( base64_encode ( $str ) );
			$liste_fichiers_attaches [$pos] .= $this->getCrlf ();
			$this->setFichierAttache ( $liste_fichiers_attaches );
			$this->setFichierAttacheFlag ( true );
			$CODE_RETOUR = true;
		} else
			$CODE_RETOUR = false;
		return $CODE_RETOUR;
	}

	/**
	 * Prepare l'entete MIME du mail avec le CONTENT TYPE.<br> Si il y a du texte et de l'html, il ajoute la balise "alternative".<br> Si il y a un ou plusieurs fichier attache(s), il ajoute la balise "mixed"
	 *
	 * @return message
	 */
	public function ajoute_mail_header_content_type(): static
	{
		// Si on a un fichier, le content est de type mixed
		if ($this->getFichierAttacheFlag ())
			$this->setContentType ( $this->getOneMailContent ( "mixed" ) );
		// Sinon, si on a du texte et du html, le content est de type alternative
		elseif ($this->getMailCorpTextFlag () && $this->getMailCorpHtmlFlag ())
			$this->setContentType ( $this->getOneMailContent ( "alternative" ) );
		// Sinon, si on a que du texte, le content est de type text
		elseif ($this->getMailCorpTextFlag ()) {
			$entete_text = str_replace ( "_charset_", $this->getCharset (), $this->getOneMailContent ( "text" ) );
			$this->setContentType ( $entete_text );
			// Sinon, si on a que du html, le content est de type html
		} elseif ($this->getMailCorpHtmlFlag ()) {
			$entete_html = str_replace ( "_charset_", $this->getCharset (), $this->getOneMailContent ( "html" ) );
			$this->setContentType ( $entete_html );
		}
		return $this;
	}

	/**
	 * Prepare l'entete MIME du mail avec le CONTENT TRANSFERT ENCODING.<br> Si il y a la balise "alternative" et ou mixed, le transfert encoding est dans chaque sous parties.<br> Sinon on le met dans l'entete.
	 *
	 * @return message
	 */
	public function ajoute_mail_header_encoding(): static
	{
		// Si on a un fichier, le encoding est dans le body
		if ($this->getFichierAttacheFlag ())
			$this->setContentEncode ( "" );
		// Sinon, si on a du texte et du html, le encoding est dans le body aussi
		elseif ($this->getMailCorpTextFlag () && $this->getMailCorpHtmlFlag ())
			$this->setContentEncode ( "" );
		// Sinon, si on a que du texte, le encoding est dans l'entete
		elseif ($this->getMailCorpTextFlag ())
			$this->setContentEncode ( $this->getOneMailEncode ( "text" ) );
		// Sinon, si on a que du html, le encoding est dans l'entete
		elseif ($this->getMailCorpHtmlFlag ())
			$this->setContentEncode ( $this->getOneMailEncode ( "html" ) );
		return $this;
	}

	/**
	 * Cree le corp TEXTE du mail.<br>
	 *
	 * @param bool $ajoute_entete Permet d'ajouter les entetes MIME ou non.
	 * @return string Renvoi le corp au format texte.
	 */
	public function ajoute_corp_text(
		bool $ajoute_entete = false): string
	{
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
	public function ajoute_corp_html(
		bool $ajoute_entete = false): string
	{
		$corp = "";
		if ($ajoute_entete) {
			$corp .= str_replace ( "_charset_", $this->getCharset (), $this->getOneMailContent ( "html" ) ) . $this->getCrlf ();
			$corp .= $this->getOneMailEncode ( "html" ) . $this->getCrlf ();
		}
		$corp .= $this->getCrlf () . $this->getMailCorpHtml ();
		return $corp;
	}

	/**
	 * Cree le corp du mail qui contient du texte (html ou text).
	 *
	 * @return string Renvoi le corp contenant du texte.
	 */
	public function prepare_corp_textuel(): string
	{
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
	 * Prepare le corp du mail.<br> Si il y a du texte et de l'html, il ajoute la balise "alternative".<br> Si il y a un ou plusieurs fichier attache(s), il ajoute la balise "mixed" et attache le(s) fichier(s).
	 *
	 * @return string Renvoi le corp du texte contenant le mail.
	 */
	public function prepare_envoi(): string
	{
		$corp = "";
		// on traite l'entete
		$this->ajoute_mail_header_content_type ();
		$this->ajoute_mail_header_encoding ();
		if ($this->getMailCorpTextFlag () && $this->getMailCorpHtmlFlag ()) {
			if ($this->getFichierAttacheFlag ())
				$corp .= $this->getOneMailContent ( "alternative" ) . $this->getCrlf ();
		}
		$corp .= $this->prepare_corp_textuel ();
		if ($this->getFichierAttacheFlag ()) {
			$corp = $this->getCrlf () . "--" . $this->getOneSeparateur ( "mixed" ) . $this->getCrlf () . $corp;
			// On ajoute les fichiers attaches
			foreach ( $this->getFichierAttache () as $fichier_attache ) {
				$corp .= $fichier_attache;
			}
			$corp .= $this->getCrlf () . $this->getOneMailFooter ( "mixed" ) . $this->getCrlf ();
		}
		return $corp;
	}

	/**
	 * Prepare le corp du mail.<br> Si il y a du texte et de l'html, il ajoute la balise "alternative".<br> Si il y a un ou plusieurs fichier attache(s), il ajoute la balise "mixed" et attache le(s) fichier(s).
	 *
	 * @return string Renvoi le corp du texte contenant le mail.
	 */
	public function prepare_message_mime(): string
	{
		// on traite l'entete, Office 365 nécessite lr To, From , CC, bcc en premier
		$this->ajoute_mail_header_content_type ();
		$this->ajoute_mail_header_encoding ();
		$mail = "To: " . $this->getMailingList () . $this->getCrlf ();
		$mail .= $this->getMailHeaderDestination ();
		$mail .= "Subject: " . $this->prepare_sujet () . $this->getCrlf ();
		$mail .= $this->getMailEntete ();
		if (! empty ( $this->getContentType () ))
			$mail .= $this->getContentType () . $this->getCrlf ();
		if (! empty ( $this->getContentEncode () ))
			$mail .= $this->getContentEncode () . $this->getCrlf ();
		$mail .= $this->prepare_envoi ();
		return $mail;
	}

	/**
	 * Envoi le mail.<br> Une liste d'adresse peut venir surcharger la liste existante au moment de l'envoi grace a "$to".
	 *
	 * @param string $to Liste d'adresse mail separes par des virgules.
	 * @return bool|message
	 * @throws Exception
	 */
	public function envoi(
		string $to = ""): bool|static
	{
		if ($to == "")
			$to = $this->getMailingList ();
		$sujet_local = $this->prepare_sujet ();
		$this->onDebug ( "Mail To " . $to . " Sujet : " . $sujet_local, 1 );
		if ($this->getNoMail () === false) {
			// @codeCoverageIgnoreStart
			$body = $this->prepare_envoi ();
			$entete = $this->getMailEntete () . $this->getCrlf () . $this->getContentType () . $this->getCrlf () . $this->getContentEncode () . $this->getCrlf () . $this->getMailHeaderDestination () . $this->getCrlf ();
			$CODE_RETOUR = mail ( $to, $sujet_local, $body, $entete, $this->getMailAdditionnal () );
			if (! $CODE_RETOUR) {
				return $this->onError ( "Probleme lors de l'envoi du mail" );
			} else {
				$this->onInfo ( "Mail envoye" );
			}
		} else {
			// @codeCoverageIgnoreEnd
			$this->onInfo ( "Envoi de mail desactive." );
		}
		return $this;
	}

	/**
	 * Envoi le mail.<br> Une liste d'adresse peut venir surcharger la liste existante au moment de l'envoi grace a "$to".
	 *
	 * @param &$objO365Message Zorille\o365\Message Objet de messagerie 0365 connecte sur un user valide
	 * @return message
	 */
	public function envoi_o365(&$objO365Message): static
	{
		$this->onDebug ( "Mail To " . print_r ( $this->getMailTo (), true ) . " Sujet : " . $this->prepare_sujet (), 1 );
		if ($this->getNoMail () === false) {
			// @codeCoverageIgnoreStart
			// On gere le message au format MIME
			$objO365Message->envoi_message_mime ( $this->prepare_message_mime () );
			$this->onInfo ( "Mail envoye" );
		} else {
			// @codeCoverageIgnoreEnd
			$this->onInfo ( "Envoi de mail desactive." );
		}
		return $this;
	}

	/**
	 * Compatibilite avec l'objet Message-0365
	 * @throws Exception
	 */
	public function envoi_message_par_enveloppe(): bool|message|static
	{
		return $this->envoi ();
	}

	/**
	 * ******************* ACCESSEURS **************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getMailHeaderDestination(): string
	{
		return $this->mail_destinations;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setMailHeaderDestination(
			$mail_destinations): static
	{
		$this->mail_destinations = $mail_destinations;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCrlf(): string
	{
		return $this->crlf;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCrlf(
			$crlf): static
	{
		$this->crlf = $crlf;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getSeparateur(): string
	{
		return $this->separateur;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneSeparateur(
			$type): string
	{
		return $this->separateur [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setSeparateur(
			$separateur): static
	{
		$this->separateur = $separateur;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailContent(): array
	{
		return $this->mail_Content;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneMailContent(
			$type) {
		return $this->mail_Content [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailContent(
			$mail_Content): static
	{
		$this->mail_Content = $mail_Content;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getContentType(): string
	{
		return $this->content_type;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setContentType(
			$content_type): static
	{
		$this->content_type = $content_type;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getContentEncode(): string
	{
		return $this->content_encode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function setContentEncode(
			$content_encode): static
	{
		$this->content_encode = $content_encode;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailEncode(): string
	{
		return $this->mail_Encode;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneMailEncode(
			$type): string
	{
		return $this->mail_Encode [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailEncode(
			$mail_Encode): static
	{
		$this->mail_Encode = $mail_Encode;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailFooter(): string
	{
		return $this->mail_footer;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getOneMailFooter(
			$type): string
	{
		return $this->mail_footer [$type];
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailFooter(
			$mail_Encode): static
	{
		$this->mail_footer = $mail_Encode;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function getMailAdditionnal(): string
	{
		return $this->mail_additionnal;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function &setMailAdditionnal(
			$mail_additionnal): static
	{
		$this->mail_additionnal = $mail_additionnal;
		return $this;
	}

	/**
	 * Accesseur en lecture<br> Renvoi la liste des adresses mails separes par des virgules.
	 * @codeCoverageIgnore
	 * @return string Liste des adresses mails.
	 */
	public function getMailingList(): string
	{
		return $this->mailing_list;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailingList(
			$mailing_list): static
	{
		$this->mailing_list = $mailing_list;
		return $this;
	}

	/**
	 * Accesseur en ecriture<br> Ajoute un sujet au mail.
	 * @param string $sujet Sujet a ajouter au mail.
	 * @param bool $encode Encode le sujet ou pas.
	 * @return message
	 * @deprecated
	 * @codeCoverageIgnore
	 */
	public function sujet(
		string $sujet,
		bool   $encode = true): message
	{
		return $this->setSujet ( $sujet, $encode );
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getMailEntete(): string
	{
		return $this->mail_entete;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMailEntete(
			$mail_entete): static
	{
		$this->mail_entete = $mail_entete;
		return $this;
	}

	/**
	 * Compatibilite avec l'objet Message-O365
	 * @codeCoverageIgnore
	 * @return $this
	 */
	public function &getObjEnveloppe(): static
	{
		return $this;
	}

	/**
	 * ******************* ACCESSEURS **************
	 */
	/**
	 * @static
	 * @codeCoverageIgnore
	 * @return array|string Renvoi le help
	 */
	static function help(): array|string
	{
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Gestion des mails";
		return $help;
	}

	/**
	 * @codeCoverageIgnore
	 */
	function __destruct() {
		return true;
	}
}

