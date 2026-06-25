<?php

namespace Zorille\framework;

use Exception;
use ReflectionClass;
use ReflectionProperty;

trait FlagsParser
{
    private array $usedOptions = [
        'help' => [
            'value' => false,
            'bool' => true,
            'optional' => true,
        ],
    ];

    protected function getAdditionalUsedOptions(): array
    {

        $ref = new ReflectionClass(static::class);
        $publicProperties = $ref->getProperties(ReflectionProperty::IS_PUBLIC);
        $flagProperties = array_filter(
            $publicProperties,
            fn(ReflectionProperty $property) => count($property->getAttributes(Flag::class)) >= 1
        );

        return array_reduce($flagProperties, function (array $r, ReflectionProperty $property) {
            return [
                ...$r,
                $property->getName() => [
                    'value' => $property->getValue($this),
                    'optional' => $property->getType()->allowsNull(),
                    'bool' => $property->getType()->getName() === 'bool',
                    'string' => $property->getType()->getName() === 'string',
                    'int' => $property->getType()->getName() === 'int',
                    'float' => $property->getType()->getName() === 'float',
                ]
            ];
        }, []);

//        return [];
    }

    /**
     * Renvoie la valeur du flag dans une propriété virtuelle aillant le nom du flag.
     *
     * @throws Exception
     */
    public function __get($name)
    {
        preg_match("/^([a-z][a-zA-Z]+)Option$/m", $name , $matches);
        if (empty($matches)) {
            throw new Exception("Undefined property: " . get_class($this) . "::\${$name}");
        }

        $optionCamelCase = $matches[1];
        $matches = preg_split("/(?=[A-Z])/", $optionCamelCase);

        $option = implode("_", array_map(static fn (string $p) => strtolower($p), $matches));

        $isOptional = empty($this->usedOptions[$option]['optional']);
        $hasValue = empty($this->usedOptions[$option]['value']) && !$isOptional;
        $optionNotExists = empty($this->usedOptions[$option]);

        if ($isOptional && ($optionNotExists || $hasValue))
            throw new Exception("L'option {$option} n'existe pas !");

        return $this->usedOptions[$option]['value'];
    }

    /**
     * Set la valeur du flag dans une propriété virtuelle aillant le nom du flag et renvoie l'objet courant.
     *
     * @throws Exception
     */
    public function __set($name, $value)
    {
        preg_match("/^([a-z][a-zA-Z]+)Option$/m", $name , $matches);
        if (empty($matches)) {
            throw new Exception("Undefined property: " . get_class($this) . "::\${$name}");
        }

        $optionCamelCase = $matches[1];
        $matches = preg_split("/(?<=[a-z])(?=[A-Z])/", $optionCamelCase);

        $option = implode("_", array_map(fn (string $p) => strtolower($p), $matches));

        if (empty($this->usedOptions[$option]))
            throw new Exception("L'option {$option} n'existe pas !");

        $this->usedOptions[$option]['value'] = $value;
    }

    /**
     * Crée un getter/setter virtuel pour les propriétés virtuelles
     *
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        preg_match("/^get([A-Z][a-zA-Z]+)Option$/m", $name, $getterMatches);
        preg_match("/^set([A-Z][a-zA-Z]+)Option$/m", $name, $setterMatches);

        if (empty($getterMatches) && empty($setterMatches)) {
            throw new Exception("Call to undefined method " . get_class($this) . "::{$name}()");
        }

        if (!empty($setterMatches)) {
            $this->{lcfirst($setterMatches[1])."Option"} = $arguments[0];
            $this->list_options->setOption(lcfirst($setterMatches[1]), $arguments[0]);
            return $this;
        }
        return $this->{lcfirst($getterMatches[1])."Option"};
    }

    /**
     * Récuère les flags au même format que dans les autres scripts
     * et gère un équivalent en variable d'environnement.
     *
     * @throws Exception
     */
    protected function setOptionsIfExists()
    {
        $this->usedOptions = array_merge(
            $this->usedOptions,
            $this->getAdditionalUsedOptions()
        );

        foreach ($this->usedOptions as $option => $detail) {
            $isOption = $this->list_options->verifie_option_existe($option);
            $isEnv = isset($detail['aliasEnv']) && getenv($detail['aliasEnv']) !== false;

            if ($isOption) {
                $v = $this->list_options->getOption($option);

                if (
                    ($detail['bool'] ?? false) === true &&
                    (!!$v || $v === "")
                ) $v = true;

                elseif (
                    ($detail['int'] ?? false) === true &&
                    preg_match("/^([0-9]*)$/", $v, $m) &&
                    !empty($m)
                ) $v = intval($v);

                elseif (
                    ($detail['float'] ?? false) === true &&
                    preg_match("/^([0-9]*)(\.[0-9]*)?$/", $v, $m) &&
                    !empty($m)
                ) $v = floatval($v);

                $this->usedOptions[$option]['value'] = $v;
            }
            elseif ($isEnv) {
                $v = getenv($detail['aliasEnv']);

                if (
                    ($detail['bool'] ?? false) === true &&
                    (!!$v || $v === "")
                ) $v = true;

                elseif (
                    ($detail['int'] ?? false) === true &&
                    preg_match("/^([0-9]*)$/", $v, $m) &&
                    !empty($m)
                ) $v = intval($v);

                elseif (
                    ($detail['float'] ?? false) === true &&
                    preg_match("/^([0-9]*)(\.[0-9]*)?$/", $v, $m) &&
                    !empty($m)
                ) $v = floatval($v);

                $this->usedOptions[$option]['value'] = $v;
                $this->list_options->setOption($option, $v);
            }
            elseif (
                !isset($detail['optional']) &&
                $this->usedOptions['help']['value'] !== true
            ) {
                $msg = "Il faut un parametre --{$option}";
                if (!empty($detail['aliasEnv'])) {
                    $msg .= " ou la variable d'environement {$detail['aliasEnv']}";
                }
                throw new Exception($msg . " pour travailler.");
            }
        }
    }
}