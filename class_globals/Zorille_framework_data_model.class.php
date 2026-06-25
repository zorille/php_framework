<?php

namespace Zorille\framework;

use Exception;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

abstract class data_model
{
    protected array $virtualProperties = [];
    private options $list_options;

    public function __construct()
    {
        global $liste_option;

        $this->setListOptions($liste_option);
    }

    /**
     * @throws Exception
     */
    public function __call(string $name, $arguments)
    {
        $isGetter = str_starts_with($name, 'get');
        $isSetter = str_starts_with($name, 'set');

        if ($isGetter || $isSetter) {
            $propName = substr($name, strlen('get'));

            if (isset($this->virtualProperties[$propName])) {
                if ($isGetter) {
                    return $this->virtualProperties[$propName]['value'] ?? null;
                }

                if (isset($this->virtualProperties[$propName]['class']) && is_array($arguments[0])) {
                    $modelClass = $this->virtualProperties[$propName]['class'];
                    $this->virtualProperties[$propName]['value'] = $modelClass::convert($arguments[0]);
                }
                else {
                    $this->virtualProperties[$propName]['value'] =
                        $arguments[0] ?? null;
                }

                return $this;
            }

            return $isGetter ? null : $this;
        }

        throw new Exception("Call unknown method " . get_class($this) . "::{$name}()");
    }

    public static function convert($record): self
    {
        if (gettype($record) !== 'array') {
            return $record;
        }

        $model = static::create();
        foreach ($record as $key => $val) {
            $model->{'set'.ucfirst(str_replace('_', '', $key))}($val);
        }
        return $model;
    }

    public static function create(): self
    {
        return new static();
    }

    /**
     * @param ReflectionProperty|string $property
     * @return string
     */
    protected abstract static function formatArrayKey($property): string;

    public function toArray(): array
    {
        $array = [];
        $ref = new ReflectionClass(get_class($this));
        $properties = $ref->getProperties();

        $skipProperties = ['virtualProperties', 'salesforce_serveur'];

        foreach ($properties as $property) {
            if (in_array($property->getName(), $skipProperties)) continue;

            $camelCasePropertyName = str_replace(
                '_', '',
                implode(
                    '_',
                    array_map(
                        fn(string $c) => ucfirst($c),
                        explode('_', $property->getName())
                    )
                )
            );

            $getter = is_object($property->getType()) && $property->getType()->getName() === 'bool'
                ? (str_starts_with($property->getName(), 'is')
                    ? lcfirst($camelCasePropertyName)
                    : "is{$camelCasePropertyName}")
                : "get{$camelCasePropertyName}";

            $array[$this->formatArrayKey($property)] = $this->$getter();

            if (
                is_array($array[$this->formatArrayKey($property)]) &&
                !empty($array[$this->formatArrayKey($property)][0]) &&
                is_object($array[$this->formatArrayKey($property)][0])
            ) {
                $ref = new ReflectionClass(self::class);
                if ($ref->isInstance($array[$this->formatArrayKey($property)][0])) {
                    $array[$this->formatArrayKey($property)] = array_map(
                        fn(self $model) => $model->toArray(),
                        $array[$this->formatArrayKey($property)]
                    );
                }
            }
        }

        foreach ($this->virtualProperties as $virtualProperty => $meta) {
            $array[ucfirst($virtualProperty)] = $meta['value'];
        }

        $array['class'] = static::ENTITY_NAME;

        return $array;
    }

    public static function getFields(): array
    {
        $array = [];
        $ref = new ReflectionClass(static::class);
        $properties = $ref->getProperties(ReflectionProperty::IS_PRIVATE|ReflectionProperty::IS_PROTECTED);

        $skipProperties = ['virtualProperties', 'salesforce_serveur'];

        foreach ($properties as $property) {
            if (in_array($property->getName(), $skipProperties)) continue;

            if (!in_array(static::formatArrayKey($property), $array)) {
                $array[] = static::formatArrayKey($property);
            }
        }

        return $array;
    }

    public function getListOptions(): options
    {
        return $this->list_options;
    }
    public function setListOptions(options $list_options): self
    {
        $this->list_options = $list_options->setSortEnErreur(false);
        return $this;
    }

    public function __debugInfo(): ?array
    {
        $ref = new ReflectionObject($this);
        $props = $ref->getProperties(ReflectionProperty::IS_PROTECTED);

        $properties = [];

        foreach ($props as $prop) {
            if ($prop->getName() === "virtualProperties") continue;

            $properties[$prop->getName()] = $prop->getValue($this);
        }

        return $properties;
    }
}