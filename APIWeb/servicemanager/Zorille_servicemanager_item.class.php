<?php

/**
 * Gestion de EasyVista Service Manager.
 */
namespace Zorille\servicemanager;

use Exception as Exception;
use Zorille\framework as Core;

/**
 * class item
 *
 * @package Lib
 * @subpackage servicemanager
 */
abstract class item extends globalapi {
    private string $format = '';
    private string $id = '';
    private array $mandatory = array();

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this;
    }

    /**
     * Remplace les variables {id} d'un template URI par les valeurs fournies.
     * Les valeurs utilisees sont retirees du tableau $params pour separer path/query/body.
     * @throws Exception
     */
    protected function build_uri(string $template, array &$params = array()): string|bool {
        $uri = $template;
        if (preg_match_all('/\{([^}]+)\}/', $template, $matches)) {
            foreach ($matches[1] as $name) {
                if (array_key_exists($name, $params)) {
                    $value = $params[$name];
                    unset($params[$name]);
                } elseif ($name === 'id' && $this->valide_item_id(false)) {
                    $value = $this->getId();
                } elseif (str_ends_with($name, '_id') && $this->valide_item_id(false)) {
                    $value = $this->getId();
                } else {
                    return $this->onError('Il manque le parametre de chemin : '.$name);
                }
                $uri = str_replace('{'.$name.'}', rawurlencode((string) $value), $uri);
            }
        }
        return $uri;
    }

    /**
     * Execute une operation REST standard via wsclient.
     * GET recoit les parametres restants en query params ; POST/PUT/PATCH en body.
     * @throws Exception
     */
    protected function execute_operation(string $method, string $template, array $parametres = array()): static|bool {
        $this->onDebug(__METHOD__.' '.$method.' '.$template, 1);
        $params = $this->prepare_standard_params($parametres);
        $uri = $this->build_uri($template, $params);
        if ($uri === false) {
            return false;
        }
        $client = $this->getObjetServiceManagerWsclient();
        switch (strtolower($method)) {
            case 'get':
                $resultat = $client->getMethod($uri, $params);
                break;
            case 'post':
                $resultat = $client->postMethod($uri, $params);
                break;
            case 'put':
                $resultat = $client->putMethod($uri, $params);
                break;
            case 'patch':
                if (method_exists($client, 'patchMethod')) {
                    $resultat = $client->patchMethod($uri, $params);
                } else {
                    $resultat = $client->putMethod($uri, $params);
                }
                break;
            case 'delete':
                $resultat = $client->deleteMethod($uri);
                break;
            default:
                return $this->onError('Methode REST non supportee : '.$method);
        }
        return $this->setDonnees($resultat);
    }

    public function reset_donnees(): static {
        return $this->setId('')->setDonnees(array());
    }

    public function valide_mandatory_fields(): static|bool {
        $this->onDebug(__METHOD__, 1);
        $retour = array();
        foreach ($this->getMandatory() as $champ => $valeur) {
            if ($valeur === false) {
                $retour[] = $champ;
            }
        }
        if (count($retour) !== 0) {
            return $this->onError('Il manque des champs obligatoires : ', $retour);
        }
        return $this;
    }

    public function valide_mandatory_field_filled(string $champ, mixed $valeur): bool {
        if (isset($this->getMandatory()[$champ]) && (!empty($valeur) || $valeur === 0 || $valeur === '0')) {
            $this->setMandatoryField($champ);
        }
        return true;
    }

    public function prepare_standard_params(array $parametres): array {
        $params = array();
        foreach ($parametres as $champ => $valeur) {
            switch ($champ) {
                case 'id':
                    $this->setId($valeur);
                    $this->valide_mandatory_field_filled($champ, $valeur);
                    break;
                default:
                    $this->valide_mandatory_field_filled($champ, $valeur);
                    $params[$champ] = $valeur;
            }
        }
        return $params;
    }

    public function valide_item_id(bool $error = true): bool {
        if (empty($this->getId())) {
            $this->onDebug($this->getId(), 2);
            if ($error) {
                $this->onError('Il faut un item id pour travailler');
            }
            return false;
        }
        return true;
    }

    public function getFormat(): string { return $this->format; }
    public function &setFormat($format): static { $this->format = (string) $format; return $this; }
    public function getId(): string { return $this->id; }
    public function &setId($id): static { $this->id = (string) $id; return $this; }
    public function getMandatory(): array { return $this->mandatory; }
    public function &setMandatory($mandatory): static { if (is_array($mandatory)) { $this->mandatory = $mandatory; } return $this; }
    public function &setMandatoryField(string $field): static { if (isset($this->mandatory[$field])) { $this->mandatory[$field] = true; } return $this; }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('item Service Manager :');
        return $help;
    }
}
