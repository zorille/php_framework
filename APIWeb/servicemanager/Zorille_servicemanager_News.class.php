<?php

/**
 * Gestion de EasyVista Service Manager - News.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class News
 *
 * @package Lib
 * @subpackage servicemanager
 */
class News extends item {

    /**
     * Instancie un objet de type News. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_News(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): News|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new News($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('News');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * Get news
     * Endpoint: GET /news
     * @throws Exception
     */
    public function getNews(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/news', $parametres);
    }

    /**
     * Create single news
     * Endpoint: POST /news
     * @throws Exception
     */
    public function postNews(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/news', $parametres);
    }

    /**
     * Get single news
     * Endpoint: GET /news/{document_id}
     * Path params: document_id
     * @throws Exception
     */
    public function getNewsDocumentId(array $parametres = array()): static|bool {
        if(!isset($parametres['document_id'])) {
			return $this->onError('Il faut un document_id','',1);
		}
        return $this->execute_operation('get', '/news/{document_id}', $parametres);
    }

    /**
     * Update single news
     * Endpoint: PUT /news/{document_id}
     * Path params: document_id
     * @throws Exception
     */
    public function putNewsDocumentId(array $parametres = array()): static|bool {
        if(!isset($parametres['document_id'])) {
			return $this->onError('Il faut un document_id','',1);
		}
        return $this->execute_operation('put', '/news/{document_id}', $parametres);
    }

    /**
     * Update single news
     * Endpoint: PATCH /news/{document_id}
     * Path params: document_id
     * @throws Exception
     */
    public function patchNewsDocumentId(array $parametres = array()): static|bool {
        if(!isset($parametres['document_id'])) {
			return $this->onError('Il faut un document_id','',1);
		}
        return $this->execute_operation('patch', '/news/{document_id}', $parametres);
    }

    /**
     * Get single news
     * Endpoint: GET /news/{document_id}/{comment}
     * Path params: document_id, comment
     * @throws Exception
     */
    public function getNewsDocumentIdComment(array $parametres = array()): static|bool {
        if(!isset($parametres['document_id'])) {
			return $this->onError('Il faut un document_id','',1);
		}
		if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/news/{document_id}/{comment}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('News : 6 operations swagger');
        return $help;
    }
}
