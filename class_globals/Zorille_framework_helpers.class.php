<?php
/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\framework;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use StdClass;

class helpers extends abstract_log {
    /**
     * @throws Exception
     */
    private static function checkClassExists(string $class) {
        if (!class_exists($class)) {
            throw new Exception("Class {$class} not found");
        }
    }

    public static function convertArrayToDataModel(string $class, array $data = []): data_model {
        /** @var data_model $class */
        return $class::convert($data);
    }

    /**
     * @param array $data
     * @return StdClass
     */
    public static function convertArrayToStdClass(array $data): object {
        $obj = new StdClass();
        foreach ($data as $k => $v) {
            if (is_string($k)) {
                if (is_array($v) && isset($v['count'])) {
                    if (count($v) > 2) unset($v["count"]);

                    $obj->$k = count($v) > 1 ? $v : $v[0];
                } elseif (is_string($v)) {
                    $obj->$k = $v;
                }
            }
        }

        return $obj;
    }

    /**
     * @param string $class
     * @param array[] $data
     * @return data_model[]
     * @throws Exception
     */
    public static function convertArrayToDataModelArray(string $class, array $data): array {
        static::checkClassExists($class);

        return array_reduce(
            array_keys($data),
            function(array $r, $itemKey) use ($class, $data) {
                return array_merge($r, [
                    $itemKey => static::convertArrayToDataModel($class, $data[$itemKey])
                ]);
            },
            []
        );
    }

    /**
     * @param array[] $data
     * @return StdClass[]
     */
    public static function convertArrayToStdClassArray(array $data): array {
        return array_map(
            fn(array $item) => static::convertArrayToStdClass($item),
            $data
        );
    }
}
