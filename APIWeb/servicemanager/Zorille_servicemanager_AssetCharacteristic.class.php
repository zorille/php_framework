<?php

/**
 * Gestion de EasyVista Service Manager - Asset characteristic.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class AssetCharacteristic
 *
 * @package Lib
 * @subpackage servicemanager
 */
class AssetCharacteristic extends item {

    /**
     * Instancie un objet de type AssetCharacteristic. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_AssetCharacteristic(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): AssetCharacteristic|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new AssetCharacteristic($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('AssetCharacteristic');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * get  all characteristics asset
     * Endpoint: GET /asset-characteristics
     * @throws Exception
     */
    public function getAssetCharacteristics(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/asset-characteristics', $parametres);
    }

    /**
     * get characteristics of asset
     * Endpoint: GET /asset-characteristics/{asset_id}
     * Path params: asset_id
     * @throws Exception
     */
    public function getAssetCharacteristicsAssetId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        return $this->execute_operation('get', '/asset-characteristics/{asset_id}', $parametres);
    }

    /**
     * Update characteristics of asset
     * Endpoint: PUT /assets/{asset_id}/characteristics/{char_id}
     * Path params: asset_id, char_id
     * @throws Exception
     */
    public function putAssetCharacteristicsAssetIdCharacteristicsCharId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['char_id'])) {
			return $this->onError('Il faut un char_id','',1);
		}
        return $this->execute_operation('put', '/assets/{asset_id}/characteristics/{char_id}', $parametres);
    }

    /**
     * attach characteristic to asset
     * Endpoint: POST /assets/{asset_id}/characteristics/{char_id}
     * Path params: asset_id, char_id
     * @throws Exception
     */
    public function postAssetsAssetIdCharacteristicsCharacId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['char_id'])) {
			return $this->onError('Il faut un char_id','',1);
		}
        return $this->execute_operation('post', '/assets/{asset_id}/characteristics/{char_id}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('AssetCharacteristic : 4 operations swagger');
        return $help;
    }
}
