<?php

/**
 * Gestion de EasyVista Service Manager - E_Your_Table.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class EYourTable
 *
 * @package Lib
 * @subpackage servicemanager
 */
class EYourTable extends item {

    /**
     * Instancie un objet de type EYourTable. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_EYourTable(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): EYourTable|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new EYourTable($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('EYourTable');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View E_Your_Table List
     * Endpoint: GET /{E_Your_Table}
     * Path params: E_Your_Table
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getEYourTable(array $parametres = array()): static|bool {
        if(!isset($parametres['E_Your_Table'])) {
			return $this->onError('Il faut un E_Your_Table','',1);
		}
        return $this->execute_operation('get', '/{E_Your_Table}', $parametres);
    }

    /**
     * Create E_Your_Table record
     * Endpoint: POST /{E_Your_Table}
     * Path params: E_Your_Table
     * @throws Exception
     */
    public function postEYourTable(array $parametres = array()): static|bool {
        if(!isset($parametres['E_Your_Table'])) {
			return $this->onError('Il faut un E_Your_Table','',1);
		}
        return $this->execute_operation('post', '/{E_Your_Table}', $parametres);
    }

    /**
     * View E_Your_Table record
     * Endpoint: GET /{E_Your_Table}/{id}
     * Path params: E_Your_Table, id
     * @throws Exception
     */
    public function getEYourTableId(array $parametres = array()): static|bool {
        if(!isset($parametres['E_Your_Table'])) {
			return $this->onError('Il faut un E_Your_Table','',1);
		}
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/{E_Your_Table}/{id}', $parametres);
    }

    /**
     * Update E_Your_Table record
     * Endpoint: PUT /{E_Your_Table}/{id}
     * Path params: E_Your_Table, id
     * @throws Exception
     */
    public function putEYourTableId(array $parametres = array()): static|bool {
        if(!isset($parametres['E_Your_Table'])) {
			return $this->onError('Il faut un E_Your_Table','',1);
		}
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('put', '/{E_Your_Table}/{id}', $parametres);
    }

    /**
     * Delete E_Your_Table record
     * Endpoint: DELETE /{E_Your_Table}/{id}
     * Path params: E_Your_Table, id
     * @throws Exception
     */
    public function deleteEYourTableId(array $parametres = array()): static|bool {
        if(!isset($parametres['E_Your_Table'])) {
			return $this->onError('Il faut un E_Your_Table','',1);
		}
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('delete', '/{E_Your_Table}/{id}', $parametres);
    }

    /**
     * Update E_Your_Table record
     * Endpoint: PATCH /{E_Your_Table}/{id}
     * Path params: E_Your_Table, id
     * @throws Exception
     */
    public function patchEYourTableId(array $parametres = array()): static|bool {
        if(!isset($parametres['E_Your_Table'])) {
			return $this->onError('Il faut un E_Your_Table','',1);
		}
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('patch', '/{E_Your_Table}/{id}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('EYourTable : 6 operations swagger');
        return $help;
    }
}
