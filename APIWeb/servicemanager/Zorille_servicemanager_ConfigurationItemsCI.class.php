<?php

/**
 * Gestion de EasyVista Service Manager - Configuration items (CI).
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class ConfigurationItemsCI
 *
 * @package Lib
 * @subpackage servicemanager
 */
class ConfigurationItemsCI extends item {

    /**
     * Instancie un objet de type ConfigurationItemsCI. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_ConfigurationItemsCI(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): ConfigurationItemsCI|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new ConfigurationItemsCI($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('ConfigurationItemsCI');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Configuration Items List
     * Endpoint: GET /configuration-items
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getConfigurationItems(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/configuration-items', $parametres);
    }

    /**
     * View Configuration Item
     * Endpoint: GET /configuration-items/{ci_id}
     * Path params: ci_id
     * @throws Exception
     */
    public function getConfigurationItemsCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['ci_id'])) {
			return $this->onError('Il faut un ci_id','',1);
		}
        return $this->execute_operation('get', '/configuration-items/{ci_id}', $parametres);
    }

    /**
     * PUT /configuration-items/{ci_id}
     * Endpoint: PUT /configuration-items/{ci_id}
     * Path params: ci_id
     * @throws Exception
     */
    public function putConfigurationItemsCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['ci_id'])) {
			return $this->onError('Il faut un ci_id','',1);
		}
        return $this->execute_operation('put', '/configuration-items/{ci_id}', $parametres);
    }

    /**
     * POST /configuration-items/{ci_id}
     * Endpoint: POST /configuration-items/{ci_id}
     * Path params: ci_id
     * @throws Exception
     */
    public function postConfigurationItemsCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['ci_id'])) {
			return $this->onError('Il faut un ci_id','',1);
		}
        return $this->execute_operation('post', '/configuration-items/{ci_id}', $parametres);
    }

    /**
     * View Configuration Item
     * Endpoint: GET /configuration-items/{ci_id}/{comment}
     * Path params: ci_id, comment
     * @throws Exception
     */
    public function getConfigurationItemsCommentCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['ci_id'])) {
			return $this->onError('Il faut un ci_id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/configuration-items/{ci_id}/{comment}', $parametres);
    }

    /**
     * View Configuration Item Links
     * Endpoint: GET /configuration-items/{ci_id}/item-links
     * Path params: ci_id
     * Query params: max_depth
     * @throws Exception
     */
    public function getConfigurationItemsCiIdItemLinks(array $parametres = array()): static|bool {
        if(!isset($parametres['ci_id'])) {
			return $this->onError('Il faut un ci_id','',1);
		}
        return $this->execute_operation('get', '/configuration-items/{ci_id}/item-links', $parametres);
    }

    /**
     * View Impacted Configuration Item Links
     * Endpoint: GET /configuration-items/{ci_id}/item-links/impacted
     * Path params: ci_id
     * Query params: linked_ci
     * @throws Exception
     */
    public function getConfigurationItemsCiIdItemLinksImpacted(array $parametres = array()): static|bool {
        if(!isset($parametres['ci_id'])) {
			return $this->onError('Il faut un ci_id','',1);
		}
        return $this->execute_operation('get', '/configuration-items/{ci_id}/item-links/impacted', $parametres);
    }

    /**
     * View Impacting  Configuration Item Links
     * Endpoint: GET /configuration-items/{ci_id}/item-links/impacting
     * Path params: ci_id
     * Query params: linked_ci
     * @throws Exception
     */
    public function getConfigurationItemsCiIdItemLinksImpacting(array $parametres = array()): static|bool {
        if(!isset($parametres['ci_id'])) {
			return $this->onError('Il faut un ci_id','',1);
		}
        return $this->execute_operation('get', '/configuration-items/{ci_id}/item-links/impacting', $parametres);
    }

    /**
     * View Configuration Item Link
     * Endpoint: GET /configuration-items/{parent_ci_id}/item-links/{child_ci_id}
     * Path params: parent_ci_id, child_ci_id
     * @throws Exception
     */
    public function getConfigurationItemsParentCiIdItemLinksChildCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['parent_ci_id'])) {
			return $this->onError('Il faut un parent_ci_id','',1);
		}
        if(!isset($parametres['child_ci_id'])) {
			return $this->onError('Il faut un child_ci_id','',1);
		}
        return $this->execute_operation('get', '/configuration-items/{parent_ci_id}/item-links/{child_ci_id}', $parametres);
    }

    /**
     * Update Configuration Item Link
     * Endpoint: PUT /configuration-items/{parent_ci_id}/item-links/{child_ci_id}
     * Path params: parent_ci_id, child_ci_id
     * @throws Exception
     */
    public function putConfigurationItemsParentCiIdItemLinksChildCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['parent_ci_id'])) {
			return $this->onError('Il faut un parent_ci_id','',1);
		}
        if(!isset($parametres['child_ci_id'])) {
			return $this->onError('Il faut un child_ci_id','',1);
		}
        return $this->execute_operation('put', '/configuration-items/{parent_ci_id}/item-links/{child_ci_id}', $parametres);
    }

    /**
     * Create Configuration Item Link
     * Endpoint: POST /configuration-items/{parent_ci_id}/item-links/{child_ci_id}
     * Path params: parent_ci_id, child_ci_id
     * @throws Exception
     */
    public function postConfigurationItemsParentCiIdItemLinksChildCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['parent_ci_id'])) {
			return $this->onError('Il faut un parent_ci_id','',1);
		}
        if(!isset($parametres['child_ci_id'])) {
			return $this->onError('Il faut un child_ci_id','',1);
		}
        return $this->execute_operation('post', '/configuration-items/{parent_ci_id}/item-links/{child_ci_id}', $parametres);
    }

    /**
     * Delete Configuration Item Link
     * Endpoint: DELETE /configuration-items/{parent_ci_id}/item-links/{child_ci_id}
     * Path params: parent_ci_id, child_ci_id
     * @throws Exception
     */
    public function deleteConfigurationItemsParentCiIdItemLinksChildCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['parent_ci_id'])) {
			return $this->onError('Il faut un parent_ci_id','',1);
		}
        if(!isset($parametres['child_ci_id'])) {
			return $this->onError('Il faut un child_ci_id','',1);
		}
        return $this->execute_operation('delete', '/configuration-items/{parent_ci_id}/item-links/{child_ci_id}', $parametres);
    }

    /**
     * Update Configuration Item Link
     * Endpoint: PATCH /configuration-items/{parent_ci_id}/item-links/{child_ci_id}
     * Path params: parent_ci_id, child_ci_id
     * @throws Exception
     */
    public function patchConfigurationItemsParentCiIdItemLinksChildCiId(array $parametres = array()): static|bool {
        if(!isset($parametres['parent_ci_id'])) {
			return $this->onError('Il faut un parent_ci_id','',1);
		}
        if(!isset($parametres['child_ci_id'])) {
			return $this->onError('Il faut un child_ci_id','',1);
		}
        return $this->execute_operation('patch', '/configuration-items/{parent_ci_id}/item-links/{child_ci_id}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('ConfigurationItemsCI : 13 operations swagger');
        return $help;
    }
}
