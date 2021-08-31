<?php

/**
 * Gestion de o365.
 * @author dvargas
 */
namespace Zorille\o365;

use Zorille\framework as Core;
use Exception as Exception;

/**
 * class Item
 *
 * @package Lib
 * @subpackage o365
 */
class Item extends Graph {
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $item_id = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $liste_item = array ();

	/**
	 * ********************* Creation de l'objet ********************
	 */
	/**
	 * Instancie un objet de type Item. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice Reference sur un objet webservice
	 * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs de l'objet gestion_connexion_url
	 * @return Item
	 */
	static function &creer_Item(
			&$liste_option,
			&$webservice,
			$sort_en_erreur = false,
			$entete = __CLASS__) {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new Item ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Item
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
	 * ******************************* USERS *********************************
	 */
	/**
	 * Retrouve l'ID d'un utilisateur dans le champ displayname
	 * @param string $nom
	 * @return \Zorille\o365\Item|false
	 */
	public function retrouve_itemid_par_nom(
			$nom) {
		$this->onDebug ( __METHOD__, 1 );
		$this->list_items ();
		foreach ( $this->getListeItem () as $personne ) {
			if ($personne->displayName == $nom) {
				$this->onDebug ( $nom . " trouve avec l'id " . $personne->id, 1 );
				return $this->setItemId ( $personne->id );
			}
		}
		return $this->onError ( "Item " . $nom . " introuvable dans la liste", $this->getListeItem (), 1 );
	}

	/**
	 * Retrouve l'ID d'un utilisateur dans le champ displayname
	 * @param string $nom
	 * @return \Zorille\o365\Item|false
	 */
	public function retrouve_itemid_par_mail(
			$mail) {
		$this->onDebug ( __METHOD__, 1 );
		$this->list_items ();
		foreach ( $this->getListeItem () as $personne ) {
			if ($personne->mail == $mail) {
				return $this->setItemId ( $personne->id );
			}
		}
		return $this->onError ( "Item " . $mail . " introuvable dans la liste", $this->getListeItem (), 1 );
	}

	/**
	 * Verifie qu'un item id est remplit/existe
	 * @return boolean
	 * @throws Exception
	 */
	public function valide_itemid($error=true) {
		if (empty ( $this->getItemId () )) {
			$this->onDebug ( $this->getItemId (), 2 );
			if($error){
				$this->onError ( "Il faut un item id renvoye par O365 pour travailler" );
			}
			return false;
		}
		return true;
	}

	/**
	 * ******************************* DRIVE URI ******************************
	 */
	public function items_list_uri() {
		return '/items';
	}

	public function item_id_uri() {
		if ($this->valide_itemid () == false) {
			return $this->onError ( "Il n'y pas d'item-id selectionne" );
		}
		return '/items/' . $this->getItemId ();
	}

	/**
	 * ******************************* O365 ITEMS *********************************
	 */
	/**
	 * Recuperer la liste d'utilisateurs O365
	 * @param array $params
	 * @return \Zorille\o365\Item|false
	 */
	public function list_items(
			$params = array ()) {
		$this->onDebug ( __METHOD__, 1 );
		$liste_items_o365 = $this->getObjetO365Wsclient ()
			->getMethod ( $this->items_list_uri (), $params );
		$this->onDebug ( $liste_items_o365, 2 );
		if (isset ( $liste_items_o365->value )) {
			return $this->setListeItem ( $liste_items_o365->value );
		}
		return $this->onError ( "Pas de liste utilisateurs", $liste_items_o365, 1 );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 */
	public function getItemId() {
		return $this->item_id;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setItemId(
			$item_id) {
		$this->item_id = $item_id;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getListeItem() {
		return $this->liste_item;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setListeItem(
			&$liste_item) {
		$this->liste_item = $liste_item;
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
		$help [__CLASS__] ["text"] [] .= "Item :";
		return $help;
	}
}
?>
