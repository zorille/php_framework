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

	public function attache_fichier(
			$liste_fichiers) {
		$this->onDebug ( __METHOD__, 1 );
		$this->getObjEnveloppe ()
			->prepare_liste_fichiers_attaches ( $liste_fichiers );
		return $this;
	}

	/**
	 * ******************************* O365 MESSAGES *********************************
	 */
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
			$this->onWarning($e->getMessage());
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
			->getFichierAttache () as $fichier ) {
			$str = @file_get_contents ( $fichier );
			$donnees = array (
					"@odata.type" => "#microsoft.graph.fileAttachment",
					"name" => basename ( $fichier ),
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
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Message :";
		return $help;
	}
}
?>
