<?php

/**
 * Gestion de EasyVista Service Manager - Questionnaire.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Questionnaire
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Questionnaire extends item {

    /**
     * Instancie un objet de type Questionnaire. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Questionnaire(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Questionnaire|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Questionnaire($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Questionnaire');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Questionnaires list
     * Endpoint: GET /questionnaires
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionnaires(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/questionnaires', $parametres);
    }

    /**
     * View Questionnaires single
     * Endpoint: GET /questionnaires/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionnairesId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/questionnaires/{id}', $parametres);
    }

    /**
     * View Questions List
     * Endpoint: GET /questions
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestions(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/questions', $parametres);
    }

    /**
     * View Questions List
     * Endpoint: GET /questions/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/questions/{id}', $parametres);
    }

    /**
     * View Questions List
     * Endpoint: GET /questions/{id}/{comment}
     * Path params: id, comment
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionsComment(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/questions/{id}/{comment}', $parametres);
    }

    /**
     * View questions from Questionnaire
     * Endpoint: GET /questions-questionnaire
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionsQuestionnaire(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/questions-questionnaire', $parametres);
    }

    /**
     * View questions from Specific Questionnaire
     * Endpoint: GET /questions-questionnaire/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionsQuestionnaireId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/questions-questionnaire/{id}', $parametres);
    }

    /**
     * View questions from Specific Questionnaire
     * Endpoint: GET /questions-questionnaire/{id}/{comment}
     * Path params: id, comment
     * @throws Exception
     */
    public function getQuestionsQuestionnaireIdComment(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/questions-questionnaire/{id}/{comment}', $parametres);
    }

    /**
     * View questions-result
     * Endpoint: GET /questions-result
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionsResult(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/questions-result', $parametres);
    }

    /**
     * View question_result List
     * Endpoint: GET /questions-result/{request_id}
     * Path params: request_id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionsResultRequestId(array $parametres = array()): static|bool {
        if(!isset($parametres['request_id'])) {
			return $this->onError('Il faut un request_id','',1);
		}
        return $this->execute_operation('get', '/questions-result/{request_id}', $parametres);
    }

    /**
     * View questions-result from a question
     * Endpoint: GET /questions-result/{request_id}/{question_id}
     * Path params: request_id, question_id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionsResultRequestIdQuestionId(array $parametres = array()): static|bool {
        if(!isset($parametres['request_id'])) {
			return $this->onError('Il faut un request_id','',1);
		}
        if(!isset($parametres['question_id'])) {
			return $this->onError('Il faut un question_id','',1);
		}
        return $this->execute_operation('get', '/questions-result/{request_id}/{question_id}', $parametres);
    }

    /**
     * Update questions-result from a question
     * Endpoint: PUT /questions-result/{request_id}/{question_id}
     * Path params: request_id, question_id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function putQuestionsResultRequestIdQuestionId(array $parametres = array()): static|bool {
        if(!isset($parametres['request_id'])) {
			return $this->onError('Il faut un request_id','',1);
		}
        if(!isset($parametres['question_id'])) {
			return $this->onError('Il faut un question_id','',1);
		}
        return $this->execute_operation('put', '/questions-result/{request_id}/{question_id}', $parametres);
    }

    /**
     * Create answer from a question
     * Endpoint: POST /questions-result/{request_id}/{question_id}
     * Path params: request_id, question_id
     * @throws Exception
     */
    public function postQuestionsResultRequestIdQuestionId(array $parametres = array()): static|bool {
        if(!isset($parametres['request_id'])) {
			return $this->onError('Il faut un request_id','',1);
		}
        if(!isset($parametres['question_id'])) {
			return $this->onError('Il faut un question_id','',1);
		}
        return $this->execute_operation('post', '/questions-result/{request_id}/{question_id}', $parametres);
    }

    /**
     * Update questions-result from a question
     * Endpoint: PATCH /questions-result/{request_id}/{question_id}
     * Path params: request_id, question_id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function patchQuestionsResultRequestIdQuestionId(array $parametres = array()): static|bool {
        if(!isset($parametres['request_id'])) {
			return $this->onError('Il faut un request_id','',1);
		}
        if(!isset($parametres['question_id'])) {
			return $this->onError('Il faut un question_id','',1);
		}
        return $this->execute_operation('patch', '/questions-result/{request_id}/{question_id}', $parametres);
    }

    /**
     * View question_result List
     * Endpoint: GET /questions-result/{request_id}/{question_id}/{comment}
     * Path params: request_id, question_id, comment
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getQuestionsResultRequestIdComment(array $parametres = array()): static|bool {
        if(!isset($parametres['request_id'])) {
			return $this->onError('Il faut un request_id','',1);
		}
        if(!isset($parametres['question_id'])) {
			return $this->onError('Il faut un question_id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/questions-result/{request_id}/{question_id}/{comment}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Questionnaire : 15 operations swagger');
        return $help;
    }
}
