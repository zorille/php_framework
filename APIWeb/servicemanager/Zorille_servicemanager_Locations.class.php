<?php

/**
 * Gestion de EasyVista Service Manager - Locations.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Locations
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Locations extends item {

    /**
     * Instancie un objet de type Locations. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Locations(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Locations|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Locations($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Locations');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Locations List
     * Endpoint: GET /licenses
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getLocations(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/licenses', $parametres);
    }

    /**
     * View Locations List
     * Endpoint: GET /locations
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getAllLocations(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/locations', $parametres);
    }

    /**
     * View Locations
     * Endpoint: GET /locations/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getLocationsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/locations/{id}', $parametres);
    }

    /**
     * Update Locations
     * Endpoint: PUT /locations/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function putLocationsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('put', '/locations/{id}', $parametres);
    }

    /**
     * Update Locations
     * Endpoint: PATCH /locations/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function patchLocationsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('patch', '/locations/{id}', $parametres);
    }

    /**
     * View Locations
     * Endpoint: GET /locations/{id}/{comment}
     * Path params: id, comment
     * @throws Exception
     */
    public function getLocationsIdComment(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/locations/{id}/{comment}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Locations : 6 operations swagger');
        return $help;
    }
}
