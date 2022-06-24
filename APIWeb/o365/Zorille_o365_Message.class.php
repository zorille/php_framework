<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Message
 *
 * @package Lib
 * @subpackage o365
 */
class Message extends User {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $message_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $message_content = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $message_o365_ref = array ();
	/**
	 * var privee
	 *
	 * @access private
	 * @var Core\enveloppe
	 */
	private $obj_enveloppe = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Message. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Message
	 */
	static function &creer_Message(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Message ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Message
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->initialise_ressources ();
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
	 * On valide les variables obligatoires
	 * @return $this
	 * @throws Exception
	 */
	public function &initialise_ressources() {
		if ($this->getListeOptions ()
			->verifie_option_existe ( "envoi_par_mail", false ) !== false) {
			// Gestion de Office365
			$this->onInfo ( "On se connecte a o365 pour envoyer un mail" );
			if ($this->getListeOptions ()
				->verifie_option_existe ( "o365_serveur_mail", false ) === false) {
				return $this->onError ( "Il manque le paramettre o365_serveur_mail pour continuer" );
			}
			if ($this->getListeOptions ()
				->verifie_option_existe ( "o365_user_message", false ) === false) {
				return $this->onError ( "Il manque le paramettre o365_user_message pour continuer" );
			}
			// On creer l'objet connecte a O365
			if ($this->getObjetO365Wsclient ()
				->getConnected () === false) {
				$this->getObjetO365Wsclient ()
					->prepare_connexion ( $this->getListeOptions ()
					->getOption ( "o365_serveur_mail" ) );
			}
			// On connecte un compte existant sur o365
			$this->retrouve_userid_par_nom ( $this->getListeOptions ()
				->getOption ( "o365_user_message" ) )
				->prepare_enveloppe ();
			return $this;
		}
		# $this->onWarning ( "Option envoi_par_mail non active" );
		return $this;
	}

	/**
	 * ******************************* MESSAGES *********************************
	 */
	/**
	 * Verifie qu'un message id est remplit/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_messageid() {
		if (empty ( $this->getMessageId () )) {
			$this->onDebug ( $this->getMessageId (), 2 );
			$this->onError ( "Il faut un message id renvoye par O365 pour travailler" );
			return false;
		}
		return true;
	}

	public function envoi_message(
			$info_message) {
		$this->onInfo ( "On envoie un message email" );
		return $this->fabrique_message ( $info_message )
			->user_message_create ()
			->user_message_send ();
	}

	public function fabrique_message(
			$params) {
		// On fabrique le message
		$message = array (
				'subject' => isset ( $params ['sujet'] ) ? $params ['sujet'] : '',
				'importance' => isset ( $params ['importance'] ) ? $params ['importance'] : 'Low',
				'body' => array (
						"contentType" => "HTML",
						"content" => isset ( $params ['content'] ) ? $params ['content'] : "Pas de contenu"
				)
		);
		$liste_to = array (
				'emailAddress' => array ()
		);
		if (isset ( $params ['emailAddressTo'] ) && is_array ( $params ['emailAddressTo'] )) {
			foreach ( $params ['emailAddressTo'] as $to ) {
				$liste_to ['emailAddress'] = array (
						'address' => $to
				);
			}
		}
		$message ['toRecipients'] = array (
				$liste_to
		);
		$this->onDebug ( $message, 1 );
		return $this->setEmailContent ( $message );
	}

	public function prepare_from(
			$emailAddressFrom,
			&$message) {
		if (! empty ( $emailAddressFrom )) {
			$message ['from'] = array (
					'emailAddress' => array (
							'address' => $emailAddressFrom
					)
			);
		}
		return $this;
	}

	public function prepare_to(
			$emailAddressTo,
			&$message) {
		$liste_to = array ();
		if (is_array ( $emailAddressTo )) {
			foreach ( $emailAddressTo as $to ) {
				$liste_to [count ( $liste_to )] ['emailAddress'] = array (
						'address' => $to
				);
			}
		} else {
			$liste_to [count ( $liste_to )] ['emailAddress'] = array (
					'address' => $emailAddressTo
			);
		}
		if (! empty ( $liste_to )) {
			$message ['toRecipients'] = $liste_to;
		}
		return $this;
	}

	public function prepare_cc(
			$emailAddressCc,
			&$message) {
		$liste_to = array ();
		if (is_array ( $emailAddressCc )) {
			foreach ( $emailAddressCc as $to ) {
				$liste_to [count ( $liste_to )] ['emailAddress'] = array (
						'address' => $to
				);
			}
		} else if (! empty ( $emailAddressCc )) {
			$liste_to [count ( $liste_to )] ['emailAddress'] = array (
					'address' => $emailAddressCc
			);
		}
		if (! empty ( $liste_to )) {
			$message ['ccRecipients'] = $liste_to;
		}
		return $this;
	}

	public function prepare_bcc(
			$emailAddressBcc,
			&$message) {
		$liste_to = array ();
		if (is_array ( $emailAddressBcc )) {
			foreach ( $emailAddressBcc as $to ) {
				$liste_to [count ( $liste_to )] ['emailAddress'] = array (
						'address' => $to
				);
			}
		} else if (! empty ( $emailAddressBcc )) {
			$liste_to [count ( $liste_to )] ['emailAddress'] = array (
					'address' => $emailAddressBcc
			);
		}
		if (! empty ( $liste_to )) {
			$message ['bccRecipients'] = $liste_to;
		}
		return $this;
	}

	/**
	 * Integration de la liste des fichiers de moins de 4 Mo
	 * @param array $liste_fichiers Liste des fichiers (de moins de 4Mo) a envoyer avec leur chemin relatif
	 * @return \Zorille\o365\Message
	 */
	public function attache_fichier(
			$liste_fichiers) {
		$this->onDebug ( __METHOD__, 1 );
		$this->getObjEnveloppe ()
			->prepare_liste_fichiers_attaches ( $liste_fichiers );
		return $this;
	}

	/**
	 * ******************************* MESSAGE URI ******************************
	 */
	public function messages_source_uri() {
		return '/messages';
	}

	public function messages_list_uri() {
		return $this->user_id_uri () . $this->messages_source_uri ();
	}

	public function mailfolder_list_uri() {
		return $this->user_id_uri () . '/mailFolders';
	}

	public function messages_source_attachments() {
		return '/attachments';
	}

	/**
	 * ******************************* O365 GESTION MESSAGES *********************************
	 */
	/**
	 * Renvoi le contenu du message
	 * @param string $message_id
	 * @return SimpleXMLElement
	 */
	public function lire_message(
			$message_id) {
		return $this->getObjetO365Wsclient ()
			->GetMethod ( $this->messages_list_uri () . "/" . $message_id );
	}

	/**
	 * Renvoi le heaser du message
	 * @param string $message_id
	 * @param string $chemin
	 * @return SimpleXMLElement
	 */
	public function lire_header_message(
			$message_id) {
		return $this->getObjetO365Wsclient ()
			->GetMethod ( $this->messages_list_uri () . "/" . $message_id . '/?$select=internetMessageHeaders' );
	}

	/**
	 * Recupere le message en mime
	 * @param string $message_id
	 * @param string $chemin
	 * @return string message au format RAW
	 */
	public function lire_mimeType_message(
			$message_id) {
		$retour = $this->getObjetO365Wsclient ()
			->setTypeRetour ( 'raw' )
			->GetMethod ( $this->messages_list_uri () . "/" . $message_id . '/$value' );
		$this->getObjetO365Wsclient ()
			->setTypeRetour ( 'json' );
		return $retour;
	}

	/**
	 * Supprime le message
	 * @param string $message_id
	 * @return SimpleXMLElement
	 */
	public function supprime_message(
			$message_id) {
		if(empty($message_id)){
			return $this->onError("Il faut un Message Id pour supprimer le message : ".$message_id);
		}
		return $this->getObjetO365Wsclient ()
			->DeleteMethod ( $this->messages_list_uri () . "/" . $message_id );
	}

	/**
	 * ******************************* O365 DOSSIER MESSAGES *********************************
	 */
	/**
	 * Retrouve le nombre de message dans la boite
	 * @param string $chemin
	 * @return int
	 */
	public function compte_message(
			$chemin) {
		$details = $this->lire_donnees_dossier ( $chemin );
		return $details->totalItemCount;
	}

	public function retrouve_id_dossier(
			$chemin = 'Preprod') {
		$liste_dossier = $this->retrouve_liste_dossier ();
		foreach ( $liste_dossier->value as $dossier ) {
			if ($dossier->displayName == $chemin) {
				return $dossier->id;
			}
		}
		return NULL;
	}

	public function retrouve_liste_dossier() {
		return $this->getObjetO365Wsclient ()
			->GetMethod ( $this->mailfolder_list_uri () . '?$top=50' );
	}

	public function recupere_id_dossier(
			$chemin) {
		if ($chemin != "Inbox") {
			$id = $this->retrouve_id_dossier ( $chemin );
			if ($id == NULL) {
				return $this->onError ( "Pas d'ID correspondant au dossier " . $chemin );
			}
		} else {
			$id = $chemin;
		}
		return $id;
	}

	public function lire_donnees_dossier(
			$chemin = 'Inbox') {
		$id = $this->recupere_id_dossier ( $chemin );
		return $this->getObjetO365Wsclient ()
			->GetMethod ( $this->mailfolder_list_uri () . '/' . $id );
	}

	public function lire_liste_message(
			$chemin = 'Inbox',
			$param = array ()) {
		$id = $this->recupere_id_dossier ( $chemin );
		return $this->getObjetO365Wsclient ()
			->GetMethod ( $this->mailfolder_list_uri () . '/' . $id . $this->messages_source_uri (), $param );
	}

	/**
	 * ******************************* O365 ATTACHEMENTS MESSAGES *********************************
	 */
	public function liste_attachments(
			$messageId,
			$param = array ()) {
		return $this->getObjetO365Wsclient ()
			->GetMethod ( $this->messages_list_uri () . '/' . $messageId . $this->messages_source_attachments (), $param );
	}

	public function proprietes_attachment(
			$attachmentId,
			$param = array ()) {
		return $this->getObjetO365Wsclient ()
			->GetMethod ( $this->messages_list_uri () . '/' . $messageId . $this->messages_source_attachments () . '/' . $attachmentId, $param );
	}

	/**
	 * ******************************* MESSAGES PAR Enveloppe *********************************
	 */
	/**
	 * On prepare une enveloppe. Si le parametre mail_to n'est pas fournit, on catch l'Exception
	 * @return \Zorille\o365\Message
	 */
	public function prepare_enveloppe() {
		try {
			$enveloppe = Core\enveloppe::creer_enveloppe ( $this->getListeOptions () );
		} catch ( Exception $e ) {
			$this->onWarning ( $e->getMessage () );
		}
		return $this->setObjEnveloppe ( $enveloppe );
	}

	public function envoi_message_par_enveloppe() {
		$this->onInfo ( "On envoie un message email via l'enveloppe" );
		return $this->fabrique_message_par_enveloppe ()
			->user_message_create ()
			->ajoute_fichier_attache_par_enveloppe ()
			->user_message_send ();
	}

	public function fabrique_message_par_enveloppe() {
		// On fabrique le message
		$message = array (
				'subject' => $this->getObjEnveloppe ()
					->getSujet (),
				'importance' => 'Low'
		);
		if ($this->getObjEnveloppe ()
			->getMailCorpHtmlFlag ()) {
			$message ['body'] = array (
					"contentType" => "HTML",
					"content" => $this->getObjEnveloppe ()
						->getMailCorpHtml ()
			);
		} else if ($this->getObjEnveloppe ()
			->getMailCorpTextFlag ()) {
			$message ['body'] = array (
					"contentType" => "TEXT",
					"content" => $this->getObjEnveloppe ()
						->getMailCorpText ()
			);
		}
		if ($this->getObjEnveloppe ()
			->getSignatureFlag ()) {
			$message ['body'] ["content"] .= $this->getObjEnveloppe ()
				->getSignature ();
		}
		if ($this->getObjEnveloppe ()
			->getFichierAttacheFlag ()) {
			$message ["hasAttachments"] = true;
		}
		$this->prepare_from ( $this->getObjEnveloppe ()
			->getMailFrom (), $message );
		$this->prepare_to ( $this->getObjEnveloppe ()
			->getMailTo (), $message );
		$this->prepare_cc ( $this->getObjEnveloppe ()
			->getMailCc (), $message );
		$this->prepare_bcc ( $this->getObjEnveloppe ()
			->getMailBcc (), $message );
		$this->onDebug ( $message, 1 );
		return $this->setEmailContent ( $message );
	}

	public function ajoute_fichier_attache_par_enveloppe() {
		$this->onDebug ( __METHOD__, 1 );
		if (! $this->getObjEnveloppe ()
			->getFichierAttacheFlag ()) {
			return $this;
		}
		if ($this->valide_userid () == false && $this->valide_messageid () == false) {
			return $this;
		}
		foreach ( $this->getObjEnveloppe ()
			->getFichierAttache () as $name => $fichier ) {
			if (is_numeric ( $name )) {
				// Si $name est un numeric, alors on a une liste de fichiers sur le disk
				$str = @file_get_contents ( $fichier );
				$nom = $fichier;
			} else {
				// Si $name contient un nom de fichier, le contenu du fichier est dans $fichier
				$str = $fichier;
				$nom = $name;
			}
			$this->onDebug ( "Filename : " . $nom, 1 );
			$donnees = array (
					"@odata.type" => "#microsoft.graph.fileAttachment",
					"name" => basename ( $nom ),
					"contentBytes" => base64_encode ( $str )
			);
			$this->onDebug ( $donnees, 1 );
			$fichier_attached = $this->getObjetO365Wsclient ()
				->jsonPostMethod ( '/users/' . $this->getUserId () . '/messages/' . $this->getMessageId () . '/attachments', $donnees );
			$this->onDebug ( $fichier_attached, 2 );
		}
		return $this;
	}

	/**
	 * ******************************* Fin de MESSAGES PAR Enveloppe *********************************
	 */
	/**
	 * ******************************* O365 MESSAGES MIME *********************************
	 */
	public function envoi_message_mime(
			$info_message) {
		$this->onInfo ( "On envoie un message email au format MIME" );
		$current_content_type = $this->getObjetO365Wsclient ()
			->getContentType ();
		$this->getObjetO365Wsclient ()
			->setContentType ( "text/plain" );
		$this->onInfo ( $info_message );
		$retour = $this->setEmailContent ( base64_encode ( $info_message ) )
			->user_message_send_mime ();
		$this->getObjetO365Wsclient ()
			->setContentType ( $current_content_type );
		return $retour;
	}

	public function user_message_send_mime(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_userid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->PostMethod ( '/users/' . $this->getUserId () . '/sendMail', $this->getEmailContent () );
	}

	/**
	 * ******************************* Fin de O365 MESSAGES MIME *********************************
	 */
	/**
	 * Creer un message dans le brouillon d'un utilisateur. Necessite un id utilisateur valide.
	 * @return \Zorille\o365\Message
	 * @throws \Exception
	 */
	public function user_message_create() {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_userid () == false) {
			return $this;
		}
		$message_created = $this->getObjetO365Wsclient ()
			->jsonPostMethod ( '/users/' . $this->getUserId () . '/messages', $this->getEmailContent () );
		$this->onDebug ( $message_created, 2 );
		if (isset ( $message_created->id ) && ! empty ( $message_created->id )) {
			return $this->setO356MessageRef ( $message_created )
				->setMessageId ( $message_created->id );
		}
		return $this->onError ( 'Pas d\'ID durant la creation du message de O365', $message_created, 1 );
	}

	/**
	 * Envoi le message definit par son ID dans les brouillons Necessite un id utilisateur valide.
	 * @param array $params
	 * @return \Zorille\o365\Message|\SimpleXMLElement
	 */
	public function user_message_send(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		if ($this->valide_userid () == false) {
			return $this;
		}
		return $this->getObjetO365Wsclient ()
			->jsonPostMethod ( '/users/' . $this->getUserId () . '/messages/' . $this->getMessageId () . '/send', $params );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getMessageId() {
		return $this->message_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setMessageId(
			&$message_id) {
		$this->message_id = $message_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getEmailContent() {
		return $this->message_content;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEmailContent(
			$message_content) {
		$this->message_content = $message_content;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getO356MessageRef() {
		return $this->message_o365_ref;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setO356MessageRef(
			&$message_o365_ref) {
		$this->message_o365_ref = $message_o365_ref;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * return Core\enveloppe
	 */
	public function &getObjEnveloppe() {
		return $this->obj_enveloppe;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjEnveloppe(
			$obj_enveloppe) {
		$this->obj_enveloppe = $obj_enveloppe;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help() {
		$help = Core\enveloppe::help ();
		$help = array_merge ( $help, parent::help () );
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Message O365 :";
		$help [__CLASS__] ["text"] [] .= "	--envoi_par_mail Active l'envoi par mail sur Office365";
		$help [__CLASS__] ["text"] [] .= "	--o365_serveur_mail Nom du serveur Outlook dans les fichiers de conf";
		$help [__CLASS__] ["text"] [] .= "	--o365_user_message 'Damien Vargas' Nom de l'utilisateur o365 autorise a envoyer un email";
		return $help;
	}
}
?>
