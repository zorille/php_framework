<?php

/**
 * Gestion de otrs.
 * @author dvargas
 */
namespace Zorille\otrs;

use Exception;
use Zorille\framework as Core;

/**
 * class ConfigItem
 *
 * @package Lib
 * @subpackage otrs
 */
class ConfigItem extends ConfigItems {

	/**
	 * Instancie un objet de type ConfigItem. @codeCoverageIgnore
	 * @param Core\options $liste_option Reference sur un objet options
	 * @param wsclient $webservice_rest Reference sur un objet webservice_rest
	 * @param bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete Entete des logs
	 * @return ConfigItem|static
	 * @throws Exception
	 */
	static function &creer_ConfigItem(
		Core\options &$liste_option,
		wsclient     &$webservice_rest,
		bool|string  $sort_en_erreur = false,
		string       $entete = __CLASS__): ConfigItem|static {
		Core\abstract_log::onDebug_standard ( __METHOD__, 1 );
		$objet = new ConfigItem ( $sort_en_erreur, $entete );
		$objet->_initialise ( array (
				"options" => $liste_option,
				"wsclient" => $webservice_rest
		) );
		return $objet;
	}

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return $this
	 * @throws Exception
	 */
	public function &_initialise(
        array $liste_class): static {
		parent::_initialise ( $liste_class );
		return $this->setFormat ( 'ConfigItem' );
	}

	/**
	 * Constructeur. @codeCoverageIgnore
	 * @param bool|string $sort_en_erreur Prend les valeurs oui/non ou true/false
	 * @param string $entete entete de log
	 */
	public function __construct(
		bool|string $sort_en_erreur = false,
		string      $entete = __CLASS__) {
		parent::__construct ( $sort_en_erreur, $entete );
	}

	public function &champ_obligatoire_ConfigItemCreate(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'ConfigItem.Class' => false,
				'ConfigItem.Name' => false,
				'ConfigItem.DeplState' => false,
				'ConfigItem.InciState' => false,
				'ConfigItem.CIXMLData.Vendor' => false,
				'ConfigItem.CIXMLData.Model' => false,
				'ConfigItem.CIXMLData.Description' => false,
				'ConfigItem.CIXMLData.Type' => false
		) );
		return $this;
	}

	public function &champ_obligatoire_ConfigItemUpdate(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'ConfigItemID' => false,
				'ConfigItem.Class' => false,
				'ConfigItem.Name' => false,
				'ConfigItem.DeplState' => false,
				'ConfigItem.InciState' => false,
				'ConfigItem.CIXMLData.Vendor' => false
		) );
		return $this;
	}

	public function &champ_obligatoire_ConfigItemDelete(): static {
		$this->setMandatory ( array (
				'UserLogin' => false,
				'Password' => false,
				'ConfigItemID' => false
		) );
		return $this;
	}

	public function creerConfigItem(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_ConfigItemCreate ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->config_item_create_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	public function updateConfigItem(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_ConfigItemUpdate ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->config_item_update_uri (), $params );
		$this->enregistre_id_depuis_resultat ( $resultat );
		return $this->setDonnees ( $resultat );
	}

	public function deleteConfigItem(
		array $parametres): static {
		$this->onDebug ( __METHOD__, 1 );
		$params = $this->champ_obligatoire_ConfigItemDelete ()
			->prepare_standard_params ( $parametres );
		$resultat = $this->valide_mandatory_fields ()
			->getObjetOtrsWsclient ()
			->postMethod ( $this->config_item_delete_uri (), $params );
		return $this->setDonnees ( $resultat );
	}

	/**
	 * Affiche le help.<br> @codeCoverageIgnore
	 */
	static public function help(): array|string {
		$help = parent::help ();
		$help [__CLASS__] ["text"] = [
			'ConfigItem :'
		];
		return $help;
	}
}
