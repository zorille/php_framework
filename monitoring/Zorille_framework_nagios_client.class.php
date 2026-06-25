<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

/**
 * class nagios_client<br> Gere un point de monitoring.
 *
 * @package Lib
 * @subpackage Monitoring
 */
class nagios_client extends moniteur {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $green = "OK";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $yellow = "WARNING";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $red = "CRITICAL";
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $grey = "UNKNOWN";
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
	 * @var string
	 */
	private $status;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $entete;

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type nagios_client.
	 * @codeCoverageIgnore
	 * @param options $liste_option Reference sur un objet options
	 * @param string $nagios_client_titre
	 * @param string $entete Entete des logs de l'objet
	 * @return nagios_client false un objet MONITEUR ou FALSE en cas d'erreur.
	 */
	static function &creer_nagios_client(
		options &$liste_option,
		string  $nagios_client_titre = "",
		string  $entete = __CLASS__): nagios_client
	{
		$nagios_client = new nagios_client ( $liste_option->verifie_parametre_standard ( "nagios_client[@sort_en_erreur='oui']" ), $entete );
		$nagios_client->_initialise ( array (
				"options" => $liste_option,
				"titre" => $nagios_client_titre
		) );
		return $nagios_client;
	}

	/**
	 * Initialisation de l'objet
	 * @codeCoverageIgnore
	 * @param array $liste_class
	 * @return nagios_client
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		$this->retrouve_nagios_client_param ( $liste_class ["titre"] );
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
			$sort_en_erreur = "oui",
			$entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
		// Par defaut tout est "vert"
		$this->setCouleurEnCours ( $this->getGreen () );
		return $this;
	}

	/**
	 * Ajoute du texte au moniteur en cours.<br>
	 * @return moniteur
	 */
	public function retrouve_nagios_client_param(
			$nagios_client_titre): moniteur
	{
		$this->setStatus ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"nagios_client",
				"status"
		), 30 ) );
		$this->setTypeOS ( $this->getListeOptions ()
			->renvoi_variables_standard ( array (
				"nagios_client",
				"type_os"
		), "linux" ) );
		if ($this->getListeOptions ()
			->verifie_variable_standard ( array (
				"nagios_client",
				"titre"
		) ) !== false && $nagios_client_titre == "") {
			$nagios_client_titre = $this->getListeOptions ()
				->renvoi_variables_standard ( array (
					"nagios_client",
					"titre"
			) );
		}
		$this->setEntete ( $nagios_client_titre );
		return $this;
	}

	/**
	 * Accesseur en lecture<br> Renvoi la couleur en cours de la page du nagios_client (pas d'une ligne).
	 *
	 * @return string Couleur en cours (green,yellow,red).
	 */
	public function renvoi_couleur(): string
	{
		return match ($this->getCouleurEnCours ()) {
			$this->getGreen () => 'green',
			$this->getYellow () => 'yellow',
			$this->getRed () => 'red',
		};
	}

	/**
	 * Accesseur en ecriture<br> Ajoute du texte au nagios_client en cours.<br> On peut ajouter une couleur specifique pour les donnees ajoutees grace a $ajoute_couleur
	 *
	 * @param string $donnees Texte a ajouter dans le nagios_client.
	 * @param bool|string $ajoute_couleur Couleur a ajouter (green,yellow,red), FALSE sinon.
	 * @return nagios_client
	 */
	public function ecrit(
		$donnees,
		bool|string $ajoute_couleur = false): static
	{
		$donnees = match ($this->getCouleurEnCours ()) {
			'green' => '',
			'yellow' => " {$donnees}({$this->getYellow()})<br/>",
			'red' => " {$donnees}({$this->getRed()})<br/>",
            default => $donnees
		};
		parent::ecrit ( $donnees );
		return $this;
	}

	/**
	 * Accesseur en ecriture<br> Met la couleur de la page de monitoring a vert.
	 * @return nagios_client
	 */
	public function green(): static
	{
		return $this->setCouleurEnCours ( $this->getGreen () );
	}

	/**
	 * Accesseur en ecriture<br> Met la couleur de la page de monitoring a orange.
	 * @return nagios_client
	 */
	public function yellow(): static
	{
		abstract_log::$logs->setExit ( 1 );
		return $this->setCouleurEnCours ( $this->getYellow () );
	}

	/**
	 * Accesseur en ecriture<br> Met la couleur de la page de monitoring a rouge.
	 * @return nagios_client
	 */
	public function red(): static
	{
		abstract_log::$logs->setExit ( 2 );
		return $this->setCouleurEnCours ( $this->getRed () );
	}

	/**
	 * Accesseur en ecriture<br> Met la couleur de la page de monitoring a rouge.
	 * @return nagios_client
	 */
	public function grey(): static
	{
		abstract_log::$logs->setExit ( 3 );
		return $this->setCouleurEnCours ( $this->getGrey () );
	}

	/**
	 * Applique la ligne de commande
	 * @param string $CMD
	 * @return array
	 */
	public function applique_commande(
		string $CMD): array
	{
		$this->onDebug ( "applique_commande", 1 );
		if ($this->getUpdate () == "oui") {
			$retour = fonctions_standards::applique_commande_systeme ( $CMD, true );
		} else {
			$this->onWarning ( "Le update est a NON." );
			$retour = array (
					0,
					"Le update est a NON."
			);
		}
		$this->onDebug ( $retour, 2 );
		return $retour;
	}

	/**
	 * Retrouve la valeur du status ou met 30 par defaut
	 * @return string
	 */
	public function retrouve_status(): string
	{
		if ($this->getStatus () != "30") {
			return "status+" . $this->getStatus ();
		}
		return "status";
	}

	/**
	 * Applique la ligne de commande a travers une connexion SSH vers le serveur nagios
	 * @param string $CMD
	 * @return nagios_client
	 */
	public function send_via_ssh(
		string $CMD): static
	{
		$this->getObjetSSH ()
			->setMachineDistante ( $this->getHost () );
		$retour_connexion = $this->getObjetSSH ()
			->ssh_connect ();
		$this->onDebug ( $retour_connexion, 2 );
		if ($retour_connexion) {
			if ($this->getUpdate () == "oui") {
				$this->getObjetSSH ()
					->ssh_commande ( $CMD, true );
			} else {
				$this->onDebug ( $CMD, 1 );
			}
			$this->getObjetSSH ()
				->ssh_close ();
		}
		return $this;
	}

	/**
	 * Envoi le resultat du monitoring au serveur de monitoring type linux.<br>
	 *
	 * @return nagios_client
	 */
	public function send_linux(
			$donnees): static
	{
		$this->onDebug ( "Type : Linux", 2 );
		$status = $this->retrouve_status ();
		$CMD = $this->getBbClientBin () . " " . $this->getHost () . ":" . $this->getPort () . " ";
		$CMD .= "\"" . $status . " " . $this->getCI () . "." . $this->getMoniteur () . " " . $this->getCouleurEnCours () . " " . $donnees . "\"";
		$this->onDebug ( $CMD, 1 );
		if ($this->getActiveSSH () === true) {
			$this->send_via_ssh ( $CMD );
		} else {
			$this->applique_commande ( $CMD );
		}
		return $this;
	}

	/**
	 * Envoi le resultat du monitoring au serveur de monitoring type linux.<br>
	 *
	 * @return nagios_client.
	 */
	public function send_win(
			$donnees): static
	{
		$this->onDebug ( "Type : Windows", 2 );
		$status = $this->retrouve_status ();
		$donnees = str_replace ( "\n", "<br/>", $donnees );
		$CMD = "\"" . $this->getBbClientBin () . "\" " . $this->getHost () . ":" . $this->getPort () . " ";
		$CMD .= "status " . $this->getCI () . " " . $this->getMoniteur () . " " . $this->getCouleurEnCours () . " \"" . $donnees . "\" " . $status;
		// @codeCoverageIgnoreStart
		if (strtoupper ( substr ( PHP_OS, 0, 3 ) ) === 'WIN') {
			$this->onDebug ( "conversion d'encodage : " . PHP_OS, 1 );
			$CMD = mb_convert_encoding ( $CMD, "Windows-1252" );
		}
		// @codeCoverageIgnoreEnd
		$this->applique_commande ( $CMD );
		return $this;
	}

	/**
	 * Envoi le resultat du monitoring au serveur de monitoring.<br>
	 *
	 * @return true.
	 */
	public function send(
			$mail_to,
			$mail_from): bool
	{
		if (strlen ( $this->getDatas () ) > 32000) {
			$donnees = "&" . $this->getYellow () . " Argument list too long\n";
			$this->envoi_mail ( $mail_to, $mail_from );
		} else {
			$donnees = $this->getDatas ();
		}
		if ($this->getTypeOS () == "linux") {
			$this->send_linux ( $donnees );
		} elseif ($this->getTypeOS () == "win") {
			$this->send_win ( $donnees );
		}
		return true;
	}

	public function affiche_status(): static
	{
		if ($this->getListeOptions ()
			->verifie_option_existe ( "console" ) !== false) {
			echo $this->getEntete () . " " . $this->getCouleurEnCours () . " - " . str_replace ( "<br/>", "\n", $this->getDatas () );
		} else {
			echo $this->getEntete () . " " . $this->getCouleurEnCours () . " - " . $this->getDatas ();
		}
		//Consommation;kWh|kWh
		return $this;
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
	public function getGrey(): string
	{
		return $this->grey;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGrey(
			$grey): static
	{
		$this->grey = $grey;
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
	public function getStatus(): string
	{
		return $this->status;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setStatus(
			$status): static
	{
		$this->status = $status;
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
	 */
	public function getEntete(): string
	{
		return $this->entete;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setEntete(
			$entete): static
	{
		$this->entete = $entete;
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
		$help [__CLASS__] ["text"] [] .= "Gestion d'un nagios_client";
		$help [__CLASS__] ["text"] [] .= "\t--nagios_client_bin /usr/bin/bb  chemin de la commande bb on bbwin";
		$help [__CLASS__] ["text"] [] .= "\t--nagios_client_status 30              Temps en minutes";
		$help [__CLASS__] ["text"] [] .= "\t--nagios_client_type_os linux/win      defini l'os utilise par client de monitoring";
		$help [__CLASS__] ["text"] [] .= "\t--nagios_client_titre \"titre\"        Titre affiche dans le nagios_client";
		$help [__CLASS__] ["text"] [] .= "\t--console                              affiche le texte preformatte pour une console";
		return $help;
	}
}
