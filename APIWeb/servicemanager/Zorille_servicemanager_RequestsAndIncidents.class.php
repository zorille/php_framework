<?php

/**
 * Gestion de EasyVista Service Manager - Requests and Incidents.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class RequestsAndIncidents
 *
 * @package Lib
 * @subpackage servicemanager
 */
class RequestsAndIncidents extends item {

    /**
     * Instancie un objet de type RequestsAndIncidents. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_RequestsAndIncidents(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): RequestsAndIncidents|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new RequestsAndIncidents($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('RequestsAndIncidents');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Catalog Requests List
     * Endpoint: GET /catalog-requests
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getCatalogRequests(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/catalog-requests', $parametres);
    }

    /**
     * View Catalog Request
     * Endpoint: GET /catalog-requests/{catalog_id}
     * Path params: catalog_id
     * @throws Exception
     */
    public function getCatalogRequestsCatalogId(array $parametres = array()): static|bool {
        if(!isset($parametres['catalog_id'])) {
			return $this->onError('Il faut un catalog_id','',1);
		}
        return $this->execute_operation('get', '/catalog-requests/{catalog_id}', $parametres);
    }

    /**
     * View Catalog Request
     * Endpoint: GET /catalog-requests/{catalog_id}/{comment}
     * Path params: catalog_id, comment
     * @throws Exception
     */
    public function getCatalogCommentRequestsCatalogId(array $parametres = array()): static|bool {
        if(!isset($parametres['catalog_id'])) {
			return $this->onError('Il faut un catalog_id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/catalog-requests/{catalog_id}/{comment}', $parametres);
    }

    /**
     * View Catalog Requests Path List
     * Endpoint: GET /catalog-requests-paths
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getCatalogRequestsPaths(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/catalog-requests-paths', $parametres);
    }

    /**
     * View Catalog Request Path
     * Endpoint: GET /catalog-requests-paths/{catalog_id}
     * Path params: catalog_id
     * @throws Exception
     */
    public function getCatalogRequestsPathsCatalogId(array $parametres = array()): static|bool {
        if(!isset($parametres['catalog_id'])) {
			return $this->onError('Il faut un catalog_id','',1);
		}
        return $this->execute_operation('get', '/catalog-requests-paths/{catalog_id}', $parametres);
    }

    /**
     * Download the document
     * Endpoint: GET /documents/{id}
     * Path params: id
     * @throws Exception
     */
    public function getDocumentsIdName(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/documents/{id}', $parametres);
    }

    /**
     * Delete Document
     * Endpoint: DELETE /documents/{id}
     * Path params: id
     * @throws Exception
     */
    public function deleteDocumentsIdName(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('delete', '/documents/{id}', $parametres);
    }

    /**
     * View Requests / Incidents list
     * Endpoint: GET /requests
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getRequests(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/requests', $parametres);
    }

    /**
     * Create Request / Incident
     * Endpoint: POST /requests
     * @throws Exception
     */
    public function postRequests(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/requests', $parametres);
    }

    /**
     * View Request / Incident
     * Endpoint: GET /requests/{rfc_number}
     * Path params: rfc_number
     * @throws Exception
     */
    public function getRequestsRfcNumber(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('get', '/requests/{rfc_number}', $parametres);
    }

    /**
     * Update Request / Incident
     * Endpoint: PUT /requests/{rfc_number}
     * Path params: rfc_number
     * Query params: append
     * @throws Exception
     */
    public function putRequestsRfcNumber(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('put', '/requests/{rfc_number}', $parametres);
    }

    /**
     * Update Request / Incident
     * Endpoint: PATCH /requests/{rfc_number}
     * Path params: rfc_number
     * @throws Exception
     */
    public function patchRequestsRfcNumber(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('patch', '/requests/{rfc_number}', $parametres);
    }

    /**
     * Create an action related to an incident
     * Endpoint: POST /requests/{rfc_number}/actions
     * Path params: rfc_number
     * @throws Exception
     */
    public function postRequestsRfcNumberActions(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('post', '/requests/{rfc_number}/actions', $parametres);
    }

    /**
     * Get Questionnaires from non terminated actions
     * Endpoint: GET /requests/{currentIncident}/actions/questionnaire
     * Path params: currentIncident
     * @throws Exception
     */
    public function getQuestionnairesFromNonTerminatedActions(array $parametres = array()): static|bool {
        if(!isset($parametres['currentIncident'])) {
			return $this->onError('Il faut un currentIncident','',1);
		}
        return $this->execute_operation('get', '/requests/{currentIncident}/actions/questionnaire', $parametres);
    }

    /**
     * Get questionnaire from specific actions
     * Endpoint: GET /requests/{currentIncident}/actions/{actionId}/questionnaire
     * Path params: actionId, currentIncident
     * @throws Exception
     */
    public function getQuestionnaireFromSpecificActions(array $parametres = array()): static|bool {
        if(!isset($parametres['currentIncident'])) {
			return $this->onError('Il faut un currentIncident','',1);
		}
        if(!isset($parametres['actionId'])) {
			return $this->onError('Il faut un actionId','',1);
		}
        return $this->execute_operation('get', '/requests/{currentIncident}/actions/{actionId}/questionnaire', $parametres);
    }

    /**
     * View Request / Incident Comment
     * Endpoint: GET /requests/{rfc_number}/{comment}
     * Path params: rfc_number, comment
     * @throws Exception
     */
    public function getRequestsRfcNumberComment(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/requests/{rfc_number}/{comment}', $parametres);
    }

    /**
     * Get the list of Documents of a Request / Incident
     * Endpoint: GET /requests/{rfc_number}/documents
     * Path params: rfc_number
     * @throws Exception
     */
    public function getRequestsRfcNumberDocuments(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('get', '/requests/{rfc_number}/documents', $parametres);
    }

    /**
     * Upload and attach a Document  to a Request / Incident
     * Endpoint: POST /requests/{rfc_number}/documents
     * Path params: rfc_number
     * @throws Exception
     */
    public function postRequestsRfcNumberDocuments(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('post', '/requests/{rfc_number}/documents', $parametres);
    }

    /**
     * Get a specific of Document of a Request / Incident
     * Endpoint: GET /requests/{rfc_number}/documents/{id}
     * Path params: rfc_number, id
     * @throws Exception
     */
    public function getRequestsRfcNumberSpecificDocuments(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
		if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('get', '/requests/{rfc_number}/documents/{id}', $parametres);
    }

    /**
     * Delete a request document
     * Endpoint: DELETE /requests/{rfc_number}/documents/{id}
     * Path params: rfc_number, id
     * @throws Exception
     */
    public function deleteRequestsDocuments(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
		if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('delete', '/requests/{rfc_number}/documents/{id}', $parametres);
    }

    /**
     * View Request / Incident Problem
     * Endpoint: GET /requests/{rfc_number}/problem
     * Path params: rfc_number
     * @throws Exception
     */
    public function getRequestsRfcNumberProblem(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('get', '/requests/{rfc_number}/problem', $parametres);
    }

    /**
     * Create Task
     * Endpoint: POST /requests/{rfc_number}/tasks
     * Path params: rfc_number
     * @throws Exception
     */
    public function postRequestsRfcNumberTasks(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('post', '/requests/{rfc_number}/tasks', $parametres);
    }

    /**
     * Close Request / Incident
     * Endpoint: PUT /requests/{rfc_number}/close
     * Path params: rfc_number
     * @throws Exception
     */
    public function putRequestsRfcNumberClose(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('put', '/requests/{rfc_number}/close', $parametres);
    }

    /**
     * Close Request / Incident
     * Endpoint: PATCH /requests/{rfc_number}/close
     * Path params: rfc_number
     * @throws Exception
     */
    public function patchRequestsRfcNumberClose(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('patch', '/requests/{rfc_number}/close', $parametres);
    }

    /**
     * Suspend Request / Incident
     * Endpoint: PUT /requests/{rfc_number}/suspend
     * Path params: rfc_number
     * @throws Exception
     */
    public function putRequestsRfcNumberSuspend(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('put', '/requests/{rfc_number}/suspend', $parametres);
    }

    /**
     * Suspend Request / Incident
     * Endpoint: PATCH /requests/{rfc_number}/suspend
     * Path params: rfc_number
     * @throws Exception
     */
    public function patchRequestsRfcNumberSuspend(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('patch', '/requests/{rfc_number}/suspend', $parametres);
    }

    /**
     * Restart Request / Incident
     * Endpoint: PUT /requests/{rfc_number}/restart
     * Path params: rfc_number
     * @throws Exception
     */
    public function putRequestsRfcNumberRestart(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('put', '/requests/{rfc_number}/restart', $parametres);
    }

    /**
     * Restart Request / Incident
     * Endpoint: PATCH /requests/{rfc_number}/restart
     * Path params: rfc_number
     * @throws Exception
     */
    public function patchRequestsRfcNumberRestart(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('patch', '/requests/{rfc_number}/restart', $parametres);
    }

    /**
     * View Request / Incident Comment (deprecated)
     * Endpoint: GET /requests/comment/{rfc_number}
     * Path params: rfc_number
     * @throws Exception
     */
    public function getRequestsCommentRfcNumber(array $parametres = array()): static|bool {
        if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('get', '/requests/comment/{rfc_number}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('RequestsAndIncidents : 29 operations swagger');
        return $help;
    }
}
