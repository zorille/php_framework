<?php

/**
 * Gestion de EasyVista Service Manager - Token.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Token
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Token extends item {

    /**
     * Instancie un objet de type Token. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Token(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Token|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Token($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Token');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * Generate token
     * Endpoint: POST /tokens
     * @throws Exception
     */
    public function postTokens(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/tokens', $parametres);
    }

    /**
     * DELETE /tokens/{token}
     * Endpoint: DELETE /tokens/{token}
     * Path params: token
     * @throws Exception
     */
    public function deleteTokensId(array $parametres = array()): static|bool {
        if(!isset($parametres['token'])) {
			return $this->onError('Il faut un token','',1);
		}
        return $this->execute_operation('delete', '/tokens/{token}', $parametres);
    }

    /**
     * Delete current token
     * Endpoint: DELETE /tokens/self
     * @throws Exception
     */
    public function deleteTokensSelf(array $parametres = array()): static|bool {
        return $this->execute_operation('delete', '/tokens/self', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Token : 3 operations swagger');
        return $help;
    }
}
