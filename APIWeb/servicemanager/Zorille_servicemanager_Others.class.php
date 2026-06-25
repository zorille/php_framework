<?php

/**
 * Gestion de EasyVista Service Manager - Others.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Others
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Others extends item {

    /**
     * Instancie un objet de type Others. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Others(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Others|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Others($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Others');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * Execute Internal Query
     * Endpoint: GET /internalqueries
     * Query params: queryguid, filterguid, viewguid, max_rows
     * @throws Exception
     */
    public function getInternalqueries(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/internalqueries', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Others : 1 operations swagger');
        return $help;
    }
}
