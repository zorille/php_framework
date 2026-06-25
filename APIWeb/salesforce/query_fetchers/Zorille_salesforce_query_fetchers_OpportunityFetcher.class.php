<?php

namespace Zorille\salesforce\query_fetchers;

use Exception;
use Zorille\salesforce\data_models\Opportunity;
use Zorille\salesforce\query_builder;
use Zorille\framework\QueryBuilderOperator as QLOperator;
use Zorille\salesforce\SalesforceFactory;

class OpportunityFetcher extends query_builder
{
    private static array $allCustomers = [];

    protected function getAssociatedModel(): string
    {
        return Opportunity::class;
    }
    protected function getObjectName(): string
    {
        return 'Opportunity';
    }

    /**
     * @throws Exception
     */
    protected function beforeFetch(): self
    {
        if (empty(static::$allCustomers)) {
            $customersFetcher = SalesforceFactory::new()->createCustomerQueryBuilder();
            static::$allCustomers = $customersFetcher->select()
                ->where('Type', QLOperator::EQUALS, 'Customer')
                ->build()
                ->getResult();
        }

        return $this;
    }

    protected function afterFetch(): self
    {
        return $this->setResult([
            'records' => array_map(
                fn(array $record) => array_merge(
                    $record,
                    [
                        'Account' => (function (array $record) {
                            $foundCustomers = [...array_filter(
                                $this->getCustomers(),
                                fn (array $account) => $account['Id'] === $record['AccountId']
                            )];

                            return empty($foundCustomers) ? [] : $foundCustomers[0];
                        })($record),
                    ]
                ),
                $this->getResult()['records']
            )
        ], true);
    }

    private function getCustomers(): array
    {
        return static::$allCustomers['records'];
    }
}