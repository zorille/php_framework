<?php

namespace Zorille\itop;

use Exception;
use Zorille\itop;

/**
 * @method string getItopServeurOption()
 */
abstract class QueryBuilderFactory
{
    use FlagsParser {
        __call as traitCall;
    }

    protected mixed $instance = null;
    protected ?options $list_options = null;

    protected abstract function getQueryBuildersPath(): string;
    protected abstract function getQueryBuildersNamespace(): string;
    protected abstract function getQueryBuildersPrefix(): string;

    public static function new(): static
    {
        return new static();
    }

    /**
     * @throws Exception
     */
    public function __construct()
    {
        global $liste_option;
        $this->setListOptions($liste_option);

        $this->setOptionsIfExists();
    }

    protected function beforeInitialize()
    {}

    protected function initialize()
    {}

    /**
     * @throws Exception
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (str_starts_with($name, "create") && str_ends_with($name, "QueryBuilder")) {
            global $liste_option;
            if (str_contains(strtolower(get_class($this)), 'itop')) {
                itop\query_builder::setItopServer($this->getItopServeurOption());
                itop\query_builder::setListOptions($liste_option);
            }

            $name = substr($name, strlen("create"));
            $name = substr($name, 0, strlen($name) - strlen("QueryBuilder"));

            $fullPath = $this->getQueryBuildersPath() . "/{$this->getQueryBuildersPrefix()}_{$name}Fetcher.class.php";
            /** @var query_builder|string $class */
            $class = substr("{$this->getQueryBuildersNamespace()}\\{$name}Fetcher", 1);
            $message = "\\{$class} class doesn't exists";

            if (!realpath($fullPath) || !class_exists($class)) throw new Exception($message);

            $this->beforeInitialize();
            $this->instance = $class::create(...$arguments);
            $this->initialize();

            return $this->instance;
        }

        return $this->traitCall($name, $arguments);
    }

    protected function setListOptions(options $list_options): static
    {
        $this->list_options = $list_options->setSortEnErreur(false);
        return $this;
    }

    public static function createFromClassName(string $className): query_builder
    {
        return static::new()->{"create" . ucfirst($className) . "QueryBuilder"}();
    }
}