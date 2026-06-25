<?php

namespace Zorille\salesforce\query_fetchers;

use Zorille\salesforce\data_models\Contact;
use Zorille\salesforce\query_builder;
use Exception;
use Zorille\salesforce\SalesforceFactory;

class ContactFetcher extends query_builder
{
    private static array $allCustomers = [];

    protected function getAssociatedModel(): string
    {
        return Contact::class;
    }

    protected function getObjectName(): string
    {
        return 'Contact';
    }

    /**
     * @throws Exception
     */
    protected function beforeFetch(): self
    {
        if (empty(static::$allCustomers)) {
            static::$allCustomers = SalesforceFactory::new()->createCustomerQueryBuilder()
                ->select()->build()->getResult();
        }

        return $this;
    }

    protected function afterFetch(): self
    {
        return $this->setResult([
            'records' => array_map(
                fn (array $record) => array_merge(
                    $record,
                    [
                        'Account' => (function (array $record) {
                            $foundCustomers = [...array_filter(
                                $this->getCustomers(),
                                fn (array $customer) => $customer['Id'] === $record['AccountId']
                            )];

                            return empty($foundCustomers) ? [] : $foundCustomers[0];
                        })($record),
                    ]
                ),
                $this->getResult()['records']
            )
        ], true)
            ->setResult([
                'records' => [...array_filter(
                    $this->getResult()['records'],
                    fn(array $record) => !empty($record['Account'])
                )]
            ], true);
    }

    private function getCustomers(): array
    {
        return static::$allCustomers['records'];
    }
}