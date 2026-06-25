<?php

/**
 * Gestion de EasyVista Service Manager - Manufacturer.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Manufacturer
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Manufacturer extends item {

    /**
     * Instancie un objet de type Manufacturer. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Manufacturer(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Manufacturer|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Manufacturer($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Manufacturer');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Manufacturer List
     * Endpoint: GET /manufacturers
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getManufacturers(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/manufacturers', $parametres);
    }

    /**
     * Create a Manufacturer
     * Endpoint: POST /manufacturers
     * @throws Exception
     */
    public function postManufacturers(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/manufacturers', $parametres);
    }

    /**
     * View Manufacturer
     * Endpoint: GET /manufacturers/{manufacturer_id}
     * Path params: manufacturer_id
     * @throws Exception
     */
    public function getManufacturersManufacturerId(array $parametres = array()): static|bool {
        if(!isset($parametres['manufacturer_id'])) {
			return $this->onError('Il faut un manufacturer_id','',1);
		}
        return $this->execute_operation('get', '/manufacturers/{manufacturer_id}', $parametres);
    }

    /**
     * Update manufacturer
     * Endpoint: PUT /manufacturers/{manufacturer_id}
     * Path params: manufacturer_id
     * @throws Exception
     */
    public function putManufacturersManufacturerId(array $parametres = array()): static|bool {
        if(!isset($parametres['manufacturer_id'])) {
			return $this->onError('Il faut un manufacturer_id','',1);
		}
        return $this->execute_operation('put', '/manufacturers/{manufacturer_id}', $parametres);
    }

    /**
     * Update manufacturer
     * Endpoint: PATCH /manufacturers/{manufacturer_id}
     * Path params: manufacturer_id
     * @throws Exception
     */
    public function patchManufacturersManufacturerId(array $parametres = array()): static|bool {
        if(!isset($parametres['manufacturer_id'])) {
			return $this->onError('Il faut un manufacturer_id','',1);
		}
        return $this->execute_operation('patch', '/manufacturers/{manufacturer_id}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Manufacturer : 5 operations swagger');
        return $help;
    }
}
