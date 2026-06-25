<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Group
 *
 * @package Lib
 * @subpackage o365
 */
class Group extends Item {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $group_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_group = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Group. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param Boolean|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Group
	 */
	static function &creer_Group(
		Core\options &$liste_option,
		wsclient     &$webservice,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): Group
	{
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Group ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Group
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setObjetO365Wsclient ( $liste_class ['wsclient'] );
	}

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param string|Bool $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		// Gestion de serveur_datas
		parent::__construct ( $sort_en_erreur, $entete );
	}

	/**
	 * ******************************* USERS *********************************
	 */
	/**
	 * Retrouve l'ID d'un utilisateur dans le champ displayname
	 * @param string $nom
	 * @return $this|false
	 * @throws Exception
	 */
	public function retrouve_groupid_par_nom(
		string $nom): bool|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$this->list_groups ();
		foreach ( $this->getListeGroup () as $personne ) {
			if ($personne->displayName == $nom) {
				$this->onDebug ( $nom . " trouve avec l'id " . $personne->id, 1 );
				return $this->setGroupId ( $personne->id );
			}
		}
		return $this->onError ( "Group " . $nom . " introuvable dans la liste", $this->getListeGroup (), 1 );
	}

	/**
	 * Verifie qu'un user id est remplit/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_groupid(): bool
	{
		if (empty ( $this->getGroupId () )) {
			$this->onDebug ( $this->getGroupId (), 2 );
			$this->onError ( "Il faut un group id renvoye par O365 pour travailler" );
			return false;
		}
		return true;
	}

	/**
	 * ******************************* DRIVE URI ******************************
	 */
	public function groups_list_uri(): string
	{
		return '/groups';
	}

	/**
	 * @throws Exception
	 */
	public function group_id_uri(): bool|string
	{
		if (!$this->valide_groupid()) {
			return $this->onError ( "Il n'y pas d'user-id selectionne" );
		}
		return '/groups/' . $this->getGroupId ();
	}
	/**
	 * ******************************* O365 USERS *********************************
	 */
	/**
	 * Recuperer la liste des groupes O365
	 * @param array $params
	 * @return Group|false
	 * @throws Exception
	 */
	public function list_groups(
		array $params = array ()): bool|Group|static
	{
		$this->onDebug ( __METHOD__, 1 );
		$liste_groups_o365 = $this->getObjetO365Wsclient ()
			->getMethod ( $this->groups_list_uri (), $params );
		$this->onDebug ( $liste_groups_o365, 2 );
		if (isset ( $liste_groups_o365->value )) {
			return $this->setListeGroup ( $liste_groups_o365->value );
		}
		return $this->onError ( "Pas de liste groupes", $liste_groups_o365, 1 );
	}

	/**
	 * Recuperer les membres d'un groupe O365. Utilise le getGroupId pour le selectionner le groupe.
	 *
	 * @param array $params
	 * @return array|bool|\SimpleXMLElement|string
	 * @throws Exception
	 */
	public function list_members_group(
		array $params = array ()): \SimpleXMLElement|bool|array|string
	{
				$this->onDebug ( __METHOD__, 1 );
				$liste_groups_o365 = $this->getObjetO365Wsclient ()
				->getMethod ( $this->group_id_uri ()."/members", $params );
				$this->onDebug ( $liste_groups_o365, 2 );
				if (isset ( $liste_groups_o365->value )) {
					return $liste_groups_o365->value;
				}
				return $this->onError ( "Pas de liste de membre du groupe", $liste_groups_o365, 1 );
	}
	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getGroupId(): ?string
	{
		return $this->group_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setGroupId(
			&$group_id): static
	{
		$this->group_id = $group_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeGroup(): array
	{
		return $this->liste_group;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeGroup(
			&$liste_group): static
	{
		$this->liste_group = $liste_group;
		return $this;
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = array ();
		$help [__CLASS__] ["text"] [] .= "Group :";
		return $help;
	}
}
