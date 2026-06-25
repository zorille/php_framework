<?php

namespace Zorille\salesforce\query_fetchers;

use Exception;
use Zorille\framework\abstract_log;
use Zorille\salesforce\data_models\Asset;
use Zorille\salesforce\data_models\Order;
use Zorille\salesforce\query_builder;
use Zorille\salesforce\SalesforceFactory;

/**
 * @method static self create()
 */
class AssetFetcher extends query_builder
{
    /** @var Order[] $orders */
    private static array $orders = [];

    protected function getAssociatedModel(): string
    {
        return Asset::class;
    }

    protected function getObjectName(): string
    {
        return 'Asset';
    }

    /**
     * @throws Exception
     */
    protected function beforeFetch(): self
    {
        if (empty(static::$orders)) {
            try {
                static::$orders = SalesforceFactory::new()->createOrderQueryBuilder()
                    ->select()->build()->toModel()['records'];
            } catch (Exception $e) {
                static::$orders = [];

                abstract_log::onWarning_standard($e->getMessage() . "({$e->getFile()}:{$e->getLine()})");
            }
        }

        return $this;
    }

    protected function afterFetch(): self
    {
       $assets = $this->getResult();

        /** @var Asset $asset */
        foreach ($assets['records'] as $k => $asset) {
            $order = array_values(array_filter(static::$orders, fn ($order) => $order->getId() === $asset['Order__c']))[0] ?? null;
            if (!is_null($order)) {
                $assets['records'][$k]['Order'] = $order;
            }
        }

        $this->setResult($assets);

        return $this;
    }
}