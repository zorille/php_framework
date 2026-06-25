<?php

/**
 * Gestion de EasyVista Service Manager.
 * Classes generees depuis le swagger EasyVista Service Manager REST API.
 */
namespace Zorille\servicemanager;

use stdClass;
use Zorille\framework as Core;
use Exception as Exception;

/**
 * class globalapi
 *
 * @package Lib
 * @subpackage servicemanager
 */
abstract class globalapi extends Core\abstract_log {
    private string $account = '40000';
    private array|stdClass|null $donnees = array();
    private $wsclient = null;

    /**
     * Initialisation de l'objet @codeCoverageIgnore
     * @param array $liste_class
     * @return globalapi
     * @throws Exception
     */
    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setObjetServiceManagerWsclient($liste_class['wsclient']);
    }

    /**
     * Extrait des parametres d'une liste d'option.
     * @codeCoverageIgnore
     */
    protected function _valideOption(array|string $chemin_option): mixed {
        $this->onDebug(__METHOD__, 1);
        if ($this->getListeOptions()->verifie_variable_standard($chemin_option) === false) {
            if (is_array($chemin_option)) {
                $chemin_option = implode('_', $chemin_option);
            }
            return $this->onError('Il manque le parametre : '.$chemin_option);
        }
        $datas = $this->getListeOptions()->renvoi_variables_standard($chemin_option);
        if (is_array($datas) && isset($datas['#comment'])) {
            unset($datas['#comment']);
        }
        return $datas;
    }

    /**
     * URI racine de l'API Service Manager.
     */
    public function globalapi_uri(): string {
        return 'api/v1/'.$this->getAccount();
    }

    public function getAccount(): string {
        return $this->account;
    }

    public function &setAccount(string|int $account): static {
        $this->account = (string) $account;
        return $this;
    }

    public function getDonnees(): array|stdClass|null {
        return $this->donnees;
    }

    public function &setDonnees($donnees): static {
        $this->donnees = $donnees;
        return $this;
    }

    public function &getObjetServiceManagerWsclient() {
        return $this->wsclient;
    }

    public function &setObjetServiceManagerWsclient(&$wsclient): static {
        $this->wsclient = $wsclient;
        return $this;
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('globalapi Service Manager :');
        return $help;
    }
}
