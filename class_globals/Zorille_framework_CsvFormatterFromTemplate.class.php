<?php

namespace Zorille\framework;

use stdClass;

trait CsvFormatterFromTemplate
{
    private int $idx = 0;
    private static array $records = [];

    abstract protected function getOutputCsvItemTemplate(): array;

    /**
     * Formate une ligne du CSV de retour en fonction du template défini plus haut
     * et de l'itération courrante du tableau d'objet retourné par salesforce.
     *
     * @param data_model|array $record
     * @return array
     */
    private function formatCsvItem($record): array
    {
        $isArray = is_array($record);
        $recordArray = $isArray ? $record : $record->toArray();
        $itemTpl = $this->getOutputCsvItemTemplate();

        $item = array_reduce(
            array_keys($itemTpl),
            fn(array $r, string $key) => array_merge($r, [
                $key => is_array($itemTpl[$key])
                    ? (is_object($itemTpl[$key][0])
                        ? call_user_func($itemTpl[$key], $record)
                        : array_reduce(
                            $itemTpl[$key],
                            fn (string $r, string $item) =>
                                $r . (in_array($item, array_keys($recordArray))
                                    ? ($isArray
                                        ? $record[$item]
                                        : $record->{'get'.ucfirst(str_replace('_', '', $item))}())
                                    : $item),
                            ''
                        ))
                    : ($itemTpl[$key] !== '??'
                        ? (is_callable($itemTpl[$key])
                            ? (($v = $itemTpl[$key]($record)) !== '??' ? $v : '')
                            : (in_array($itemTpl[$key], array_keys($recordArray))
                                ? ($isArray
                                    ? $record[$itemTpl[$key]]
                                    : $record->{'get'.ucfirst(str_replace('_', '', $itemTpl[$key]))}())
                                : $itemTpl[$key]))
                        : '')
            ]),
            []
        );

        return $this->filterCsvItem($item);
    }

    public function filterCsvItem(array $item): array|stdClass {
        return $item;
    }
}