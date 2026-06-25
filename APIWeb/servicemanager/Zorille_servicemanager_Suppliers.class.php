<?php

/**
 * Gestion de EasyVista Service Manager - Suppliers.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Suppliers
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Suppliers extends item {

    /**
     * Instancie un objet de type Suppliers. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Suppliers(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Suppliers|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Suppliers($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Suppliers');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * GET List of Suppliers
     * Endpoint: GET /suppliers
     * @throws Exception
     */
    public function getSuppliers(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/suppliers', $parametres);
    }

    /**
     * Create a new supplier
     * Endpoint: POST /suppliers
     * @throws Exception
     */
    public function postSuppliers(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/suppliers', $parametres);
    }

    /**
     * GET one supplier
     * Endpoint: GET /suppliers/{id}
     * Path params: id
     * @throws Exception
     */
    public function getSuppliersId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/suppliers/{id}', $parametres);
    }

    /**
     * Update one supplier
     * Endpoint: PUT /suppliers/{id}
     * Path params: id
     * @throws Exception
     */
    public function putSuppliersId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('put', '/suppliers/{id}', $parametres);
    }

    /**
     * Update one supplier
     * Endpoint: PATCH /suppliers/{id}
     * Path params: id
     * @throws Exception
     */
    public function patchSuppliersId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('patch', '/suppliers/{id}', $parametres);
    }

    /**
     * GET comment of a supplier
     * Endpoint: GET /suppliers/{id}/{comment}
     * Path params: id, comment
     * @throws Exception
     */
    public function getSuppliersIdComment(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/suppliers/{id}/{comment}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Suppliers : 6 operations swagger');
        return $help;
    }
}
