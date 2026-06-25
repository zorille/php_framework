<?php

/**
 * Gestion de EasyVista Service Manager - Assets.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Assets
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Assets extends item {

    /**
     * Instancie un objet de type Assets. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Assets(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Assets|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Assets($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Assets');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Assets List
     * Endpoint: GET /assets
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getAssets(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/assets', $parametres);
    }

    /**
     * Create Asset
     * Endpoint: POST /assets
     * @throws Exception
     */
    public function postAssets(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/assets', $parametres);
    }

    /**
     * View Asset
     * Endpoint: GET /assets/{asset_id}
     * Path params: asset_id
     * @throws Exception
     */
    public function getAssetsAssetId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        return $this->execute_operation('get', '/assets/{asset_id}', $parametres);
    }

    /**
     * Update Asset
     * Endpoint: PUT /assets/{asset_id}
     * Path params: asset_id
     * @throws Exception
     */
    public function putAssetsId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        return $this->execute_operation('put', '/assets/{asset_id}', $parametres);
    }

    /**
     * Update Asset
     * Endpoint: PATCH /assets/{asset_id}
     * Path params: asset_id
     * @throws Exception
     */
    public function patchAssetsId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        return $this->execute_operation('patch', '/assets/{asset_id}', $parametres);
    }

    /**
     * View Asset
     * Endpoint: GET /assets/{asset_id}/{comment}
     * Path params: asset_id, comment
     * @throws Exception
     */
    public function getAssetsCommentAssetId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/assets/{asset_id}/{comment}', $parametres);
    }

    /**
     * View Asset Links
     * Endpoint: GET /assets/{asset_id}/asset-links
     * Path params: asset_id
     * @throws Exception
     */
    public function getAssetsAssetIdAssetLinks(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        return $this->execute_operation('get', '/assets/{asset_id}/asset-links', $parametres);
    }

    /**
     * View Asset Link
     * Endpoint: GET /assets/{asset_id}/asset-links/{parent_asset_id}
     * Path params: asset_id, parent_asset_id
     * @throws Exception
     */
    public function getAssetsAssetIdAssetLinksParentAssetId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['parent_asset_id'])) {
			return $this->onError('Il faut un parent_asset_id','',1);
		}
        return $this->execute_operation('get', '/assets/{asset_id}/asset-links/{parent_asset_id}', $parametres);
    }

    /**
     * Update Asset Link
     * Endpoint: PUT /assets/{asset_id}/asset-links/{parent_asset_id}
     * Path params: asset_id, parent_asset_id
     * @throws Exception
     */
    public function putAssetsAssetIdAssetLinksParentAssetId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['parent_asset_id'])) {
			return $this->onError('Il faut un parent_asset_id','',1);
		}
        return $this->execute_operation('put', '/assets/{asset_id}/asset-links/{parent_asset_id}', $parametres);
    }

    /**
     * Create Asset Link
     * Endpoint: POST /assets/{asset_id}/asset-links/{parent_asset_id}
     * Path params: asset_id, parent_asset_id
     * @throws Exception
     */
    public function postAssetsAssetIdAssetLinksParentAssetId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['parent_asset_id'])) {
			return $this->onError('Il faut un parent_asset_id','',1);
		}
        return $this->execute_operation('post', '/assets/{asset_id}/asset-links/{parent_asset_id}', $parametres);
    }

    /**
     * Delete Asset Link
     * Endpoint: DELETE /assets/{asset_id}/asset-links/{parent_asset_id}
     * Path params: asset_id, parent_asset_id
     * @throws Exception
     */
    public function deleteAssetsAssetIdAssetLinksParentAssetId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['parent_asset_id'])) {
			return $this->onError('Il faut un parent_asset_id','',1);
		}
        return $this->execute_operation('delete', '/assets/{asset_id}/asset-links/{parent_asset_id}', $parametres);
    }

    /**
     * Update Asset Link
     * Endpoint: PATCH /assets/{asset_id}/asset-links/{parent_asset_id}
     * Path params: asset_id, parent_asset_id
     * @throws Exception
     */
    public function patchAssetsAssetIdAssetLinksParentAssetId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['parent_asset_id'])) {
			return $this->onError('Il faut un parent_asset_id','',1);
		}
        return $this->execute_operation('patch', '/assets/{asset_id}/asset-links/{parent_asset_id}', $parametres);
    }

    /**
     * Delete asset characteristic link
     * Endpoint: DELETE /assets/{asset_id}/characteristics/{char_id}
     * Path params: asset_id, char_id
     * @throws Exception
     */
    public function deleteAssetsAssetIdCharacteristicsCharId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['char_id'])) {
			return $this->onError('Il faut un char_id','',1);
		}
        return $this->execute_operation('delete', '/assets/{asset_id}/characteristics/{char_id}', $parametres);
    }

    /**
     * Update characteristics of asset
     * Endpoint: PATCH /assets/{asset_id}/characteristics/{char_id}
     * Path params: asset_id, char_id
     * @throws Exception
     */
    public function patchAssetCharacteristicsAssetIdCharacteristicsCharId(array $parametres = array()): static|bool {
        if(!isset($parametres['asset_id'])) {
			return $this->onError('Il faut un asset_id','',1);
		}
        if(!isset($parametres['char_id'])) {
			return $this->onError('Il faut un char_id','',1);
		}
        return $this->execute_operation('patch', '/assets/{asset_id}/characteristics/{char_id}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Assets : 14 operations swagger');
        return $help;
    }
}
