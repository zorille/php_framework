<?php

/**
 * Gestion de EasyVista Service Manager - Catalog-assets.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class CatalogAssets
 *
 * @package Lib
 * @subpackage servicemanager
 */
class CatalogAssets extends item {

    /**
     * Instancie un objet de type CatalogAssets. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_CatalogAssets(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): CatalogAssets|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new CatalogAssets($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('CatalogAssets');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Catalog Assets List
     * Endpoint: GET /catalog-assets
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getCatalogAssets(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/catalog-assets', $parametres);
    }

    /**
     * Create Catalog-asset
     * Endpoint: POST /catalog-assets
     * @throws Exception
     */
    public function postCatalogAssets(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/catalog-assets', $parametres);
    }

    /**
     * View Catalog Asset
     * Endpoint: GET /catalog-assets/{catalog_id}
     * Path params: catalog_id
     * @throws Exception
     */
    public function getCatalogAssetsCatalogId(array $parametres = array()): static|bool {
        if(!isset($parametres['catalog_id'])) {
			return $this->onError('Il faut un catalog_id','',1);
		}
        return $this->execute_operation('get', '/catalog-assets/{catalog_id}', $parametres);
    }

    /**
     * Updates an existing catalog asset.
     * Endpoint: PUT /catalog-assets/{catalog_id}
     * Path params: catalog_id
     * @throws Exception
     */
    public function putCatalogAssetsCatalogId(array $parametres = array()): static|bool {
        if(!isset($parametres['catalog_id'])) {
			return $this->onError('Il faut un catalog_id','',1);
		}
        return $this->execute_operation('put', '/catalog-assets/{catalog_id}', $parametres);
    }

    /**
     * Updates an existing catalog asset.
     * Endpoint: PATCH /catalog-assets/{catalog_id}
     * Path params: catalog_id
     * @throws Exception
     */
    public function patchCatalogAssetsCatalogId(array $parametres = array()): static|bool {
        if(!isset($parametres['catalog_id'])) {
			return $this->onError('Il faut un catalog_id','',1);
		}
        return $this->execute_operation('patch', '/catalog-assets/{catalog_id}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('CatalogAssets : 5 operations swagger');
        return $help;
    }
}
