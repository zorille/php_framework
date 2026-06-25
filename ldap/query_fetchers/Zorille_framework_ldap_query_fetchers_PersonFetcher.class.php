<?php

namespace Zorille\framework\ldap\query_fetchers;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework\ldap\data_model;
use Zorille\framework\ldap\data_models\Person;
use Zorille\framework\ldap\query_fetcher;

/**
 * @method static self create()
 * @method array<Person> findAll(array $ous = [], string $cn = '*')
 * @method Person findOne(array $nonOptionalInputData)
 */
class PersonFetcher extends query_fetcher
{
    public function getObjectModel(): string
    {
        return Person::class;
    }

    /**
     * @throws Exception
     */
    public function getDefaultSelectOus(): array
    {
        /** @var data_model $model */
        $model = $this->getObjectModel();
        return [$model::getBaseOu()];
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    public function getSelectData(array $ous = [], string $cn = '*'): array
    {
        if (empty($ous)) {
            $ous = $this->getDefaultSelectOus();
        }
        $credentials = $this->getLdap()->getCredentials();

        return [
            "OU=" . implode(',OU=', $ous) . ",{$credentials->getLdapRoot()}",
            "(&{$credentials->getLdapSearchFilters()[
                $this->getBaseClassName()
            ]}(CN={$cn}))"
        ];
    }
}