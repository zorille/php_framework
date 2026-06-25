<?php

namespace Zorille\framework;

use Exception;
use Zorille\itop\ItopFactory;
use Zorille\salesforce\SalesforceFactory;

/**
 * MainScript est la classe à étendre pour créer un script principale.
 *
 * Cette classe gère entre autre le parsing des flags et donne la possibilité
 * de donné un alias optionnel en variable d'environement (le nom de celle-ci est paramétrable).
 *
 * # Documentation:
 * ## Définir un flag utilisable
 * > Il existe une méthode à override `getAdditionalUsedOptions()` qui doit renvoyer un tableau.
 * <pre>
 * <?php
 * class MyScript extends Zorille\framework\MainScript
 * {
 *     // Tous les flags n'étant pas définis comme optionnel
 *     // sont obligatoire et soulèveront une erreur fatale
 *     // si ils ne sont pas présents
 *     protected function getAdditionalUsedOptions(): array
 *     {
 *         return [
 *             'flag_name' => [
 *                 'value' => <une valeur par default>,
 *                 ['optional' => true,]
 *                 ['bool' => true,]
 *                 ['int' => true,]
 *                 ['float' => true,]
 *             ],
 *             // ...
 *         ]
 *     }
 *
 *     // ...
 * }
 * </pre>
 *
 * ## Création d'un script principal
 * <pre>
 * <?php
 * class MyScript extends Zorille\framework\MainScript
 * {
 *     protected function getAdditionalUsedOptions(): array
 *     {
 *         return [
 *             // vos flags ici
 *         ]
 *     },
 *
 *     public static function help(): int
 *     {
 *         // code help ici
 *         // accessible via le flag --help
 *
 *         return 0;
 *     }
 *
 *     protected function main(): bool
 *     {
 *         // code principal du script ici
 *         // il est conseillé de le diviser en plusieurs sous méthodes
 *
 *         return true;
 *     }
 * }
 * </pre>
 *
 * ## Utilisation
 * <pre>
 * <?php
 * [namespace\]MyScript::batch($args);
 *
 * @method bool getHelpOption()
 * @method self setHelpOption(bool $help)
 */
abstract class MainScript
{
    use FlagsParser;

    private options $list_options;
    private logs $fichier_log;
    private array $argv;

    /**
     * Initialise l'objet $liste_option obligatoire dans la majorité des classes,
     * l'objet $fichier_log, et parse les flags de la commande
     *
     * @throws Exception
     */
    public function __construct(array $argv)
    {
        global $liste_option; global $fichier_log;
        $this->setListOptions($liste_option);
        $this->setLogFile($fichier_log);
        $this->setArgv([...(empty($argv) ? ['fichier'] : $argv)]);

        $argc = $this->getArgc();
        $liste_option->retrouve_options_param($argc, $this->argv);

        $this->setOptionsIfExists();
    }

    /**
     * @throws Exception
     */
    public static function create(array $argv = []): self
    {
        return new static($argv);
    }

    abstract static public function help(): int;
    abstract protected function main(): bool;
    final public function run(): int {
        if ($this->getHelpOption()) {
            $exitCode = $this->help();
            echo "[Exit]{$exitCode}\r\n";
            return 0;
        }

        // Le fichier de log est cree
        $this->onInfo("Heure de depart : " . date("d/m/Y H:i:s"));
        $this->main();
        $this->onInfo("Heure de fin : " . date("d/m/Y H:i:s"));

        return $this->fichier_log->renvoiExit();
    }

    /**
     * Execute le script dans un try catch et formate correctement l'erreur en cas d'erreur.
     *
     * @param array $argv
     * @return void
     */
    public static function batch(array $argv = []): void
    {
        try {
            exit(static::create($argv)->run());
        } catch (Exception $e) {
            abstract_log::onError_standard("{$e->getMessage()} {$e->getFile()}:{$e->getLine()}", $e->getTrace());
        }
    }

    protected function getListOptions(): options
    {
        return $this->list_options;
    }
    private function setListOptions(options $list_options): self
    {
        $this->list_options = $list_options->setSortEnErreur(false);
        return $this;
    }

    protected function getLogFile(): logs
    {
        return $this->fichier_log;
    }
    private function setLogFile(logs $log_file): self
    {
        $this->fichier_log = $log_file;
        return $this;
    }

    protected function getArgv(): array
    {
        return $this->argv;
    }
    private function setArgv(array $argv): self
    {
        $this->argv = $argv;
        return $this;
    }

    protected function getArgc(): int
    {
        return count($this->argv);
    }

    protected function onInfo(mixed $message): static
    {
        $this->getListOptions()->onInfo($message, get_class($this));
        return $this;
    }

    protected function onWarning(mixed $message): static
    {
        $this->getListOptions()->onWarning($message, get_class($this));
        return $this;
    }

    protected function onDebug(mixed $message, int $level = 2): static
    {
        $this->getListOptions()->onDebug($message, $level, get_class($this));
        return $this;
    }

    /**
     * @throws Exception
     */
    protected function onError(mixed $message, string $donnee_sup = "", int $code_retour = 1): bool
    {
        return $this->getListOptions()->onError($message, $donnee_sup, $code_retour, get_class($this));
    }

    protected function getFactory(FactoriesEnum $type): ItopFactory|SalesforceFactory
    {
        $class = "\\Zorille\\{$type->value}\\" . ucfirst($type->value) . "Factory";
        return $class::new();
    }
}