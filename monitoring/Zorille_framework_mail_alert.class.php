<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

use Zorille\o365 as o365;
use Exception as Exception;
use Zorille\o365\Message;

/**
 * class mail_alert<br> Gere un point de monitoring.
 *
 * @package Lib
 * @subpackage Monitoring
 */
class mail_alert extends moniteur {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $green = "green";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $yellow = "yellow";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $red = "red";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $type_os = "Linux";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $couleur_en_cours;
	/**
	 * var privee
	 *
	 * @access private
	 * @var o365\Message|message
	 */
	private $message = null;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type mail_alert.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $entete Entete des logs de l'objet
	 * @return mail_alert false un objet MONITEUR ou FALSE en cas d'erreur.
	 * @throws Exception
	 */
	static function &creer_mail_alert(
		options &$liste_option,
		string  $entete = __CLASS__): mail_alert
	{
		$mail_alert = new mail_alert ();
		$mail_alert->_initialise ( array (
				"options" => $liste_option
		) );
		return $mail_alert;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return mail_alert
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		$this->retrouve_mail_alert_param ();
		return $this;
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Creer l'objet et prepare la valeur du sort_en_erreur.
	 * @codeCoverageIgnore
	 * @param string $sort_en_erreur
	 * @param string $entete
	 */
	public function __construct(
			$sort_en_erreur = "non",
			$entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		// Par defaut tout est "vert"
		$this->setCouleurEnCours ( $this->getGreen () );
		return $this;
	}

	/**
	 * Ajoute du texte au moniteur en cours.<br>
	 * @return moniteur
	 * @throws Exception
	 */
	public function retrouve_mail_alert_param(): moniteur|bool
	{
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"mail_alert",
				"titre"
		) ) === false) {
			return $this->onError ( "Il faut un parametre mail_alert_titre pour continuer" );
		}
		if ($this->getListeOptions ()
			->verifie_option_existe ( "o365_serveur_mail", false ) === true) {
			// On creer l'objet O365
			$o365_webservice = o365\wsclient::creer_wsclient ( $this->getListeOptions (), o365\datas::creer_datas ( $this->getListeOptions () ) );
			// On gere les envois
			$o365_message = o365\Message::creer_Message ( $this->getListeOptions (), $o365_webservice );
			return $this->setObjectMessage ( $o365_message );
		}
		$message = message::creer_message ( $this->getListeOptions () );
		return $this->setObjectMessage ( $message );
	}

    /**
     * Prepare le mail et l'envoi si il y a au moins un fichier
     * @param array $liste_fichiers Liste des fichiers (de moins de 4Mo) a envoyer avec leur chemin relatif/absolu
     * @return mail_alert
     */
	public function prepare_message(array $liste_fichiers = []): static
	{
		// Charset, le sujet et le From par ligne de commande
		if (! empty ( $liste_fichiers )) {
			$this->getObjectMessage ()
				->attache_fichier ( $liste_fichiers );
		}
		$this->getObjectMessage ()
			->getObjEnveloppe ()
			->setSujet ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
                    "mail_alert",
                    "titre"
            ) ) )
			->ecrit_html ( $this->getDatas () );
		// Si le no_mail n'est pas active
		if (! $this->getObjectMessage ()
			->getObjEnveloppe ()
			->getNoMail ()) {
			$this->getObjectMessage ()
				->envoi_message_par_enveloppe ();
		} else {
			$this->onInfo ( "Contenu du mail : " . $this->getDatas () );
		}
		return $this;
	}

	/**
	 * Accesseur en lecture<br> Renvoi la couleur en cours de la page du mail_alert (pas d'une ligne).
	 *
	 * @return string Couleur en cours (green,yellow,red).
	 */
	public function renvoi_couleur(): string
	{
		return match ($this->getCouleurEnCours()) {
			$this->getYellow() => $this->getYellow(),
			$this->getRed() => $this->getRed(),
			default => $this->getGreen(),
		};
	}

	/**
	 * Accesseur en ecriture<br> Ajoute du texte au mail_alert en cours.<br> On peut ajouter une couleur specifique pour les donnees ajoutees grace a $ajoute_couleur
	 *
	 * @param string $donnees Texte a ajouter dans le mail_alert.
	 * @param bool|string $ajoute_couleur Couleur a ajouter (green,yellow,red), FALSE sinon.
	 * @return mail_alert
	 */
	public function ecrit(
		$donnees,
		bool|string $ajoute_couleur = false) {
		$donnees = match($ajoute_couleur) {
			'green' => "&{$this->getGreen ()} {$donnees}",
			'yellow' => "&{$this->getYellow ()} {$donnees}",
			'red' => "&{$this->getRed ()} {$donnees}"
		};
		parent::ecrit ( $donnees );
		return $this;
	}

	/**
	 * Accesseur en ecriture<br> Met la couleur de la page de monitoring a vert.
	 * @return mail_alert
	 */
	public function green(): static
	{
		return $this->setCouleurEnCours ( $this->getGreen () );
	}

	/**
	 * Accesseur en ecriture<br> Met la couleur de la page de monitoring a orange.
	 * @return mail_alert
	 */
	public function yellow(): static
	{
		return $this->setCouleurEnCours ( $this->getYellow () );
	}

	/**
	 * Accesseur en ecriture<br> Met la couleur de la page de monitoring a rouge.
	 * @return mail_alert
	 */
	public function red(): static
	{
		return $this->setCouleurEnCours ( $this->getRed () );
	}

	/**
	 * Envoi le resultat du monitoring au serveur de monitoring.<br>
	 *
	 * @return true.
	 */
	public function send(
			$liste_fichier = array ()): bool
	{
		if ($this->getListeOptions ()
			->verifie_option_existe ( "envoi_uniquement_alerte", false ) !== false) {
			// On se limite a la couleur rouge
			if ($this->getCouleurEnCours () == "red") {
				$this->prepare_message ( $liste_fichier );
			}
		} else {
			// On envoi dans tous les cas
			$this->prepare_message ( $liste_fichier );
		}
		return true;
	}

	/**
	 * *********************** Accesseurs ***********************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getGreen(): string
	{
		return $this->green;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGreen(
			$green): static
	{
		$this->green = $green;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getYellow(): string
	{
		return $this->yellow;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setYellow(
			$yellow): static
	{
		$this->yellow = $yellow;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getRed(): string
	{
		return $this->red;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setRed(
			$red): static
	{
		$this->red = $red;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getCouleurEnCours(): string
	{
		return $this->couleur_en_cours;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setCouleurEnCours(
			$couleur_en_cours): static
	{
		$this->couleur_en_cours = $couleur_en_cours;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTypeOS(): string
	{
		return $this->type_os;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTypeOS(
			$type_os): static
	{
		$this->type_os = $type_os;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 * @return Message|null
	 */
	public function &getObjectMessage(): o365\Message|null
	{
		return $this->message;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjectMessage(
			$message): static
	{
		$this->message = $message;
		return $this;
	}

	/**
	 * *********************** Accesseurs ***********************
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
		$help [__CLASS__] ["text"] [] .= "Gestion d'un mail_alert";
		$help [__CLASS__] ["text"] [] .= "\t--mail_alert_status 30              Temps en minutes";
		$help [__CLASS__] ["text"] [] .= "\t--mail_alert_type_os linux/win      defini l'os utilise par client de monitoring";
		$help [__CLASS__] ["text"] [] .= "\t--mail_alert_titre \"titre\"        Titre affiche dans le mail_alert";
		$help [__CLASS__] ["text"] [] .= "\t--envoi_uniquement_alerte	        Envoi le mail uniquement en cas d'erreur";
		return $help;
	}
}
