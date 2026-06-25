<?php

namespace Zorille\framework\ldap\query_fetchers;

use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework\ldap\data_models\OrganizationalUnit;
use Zorille\framework\ldap\query_fetcher;

/**
 * @method static self create()
 * @method array<OrganizationalUnit> findAll(array $ous = [], string $ou = '*')
 * @method OrganizationalUnit findOne(array $nonOptionalInputData)
 */
class OrganizationalUnitFetcher extends query_fetcher
{
    public function getObjectModel(): string
    {
        return OrganizationalUnit::class;
    }

    public function getDefaultSelectOus(): array
    {
        return [$this->getObjectModel()::getBaseOu()];
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    public function getSelectData(array $ous = [], string $ou = '*'): array
    {
        $credentials = $this->getLdap()->getCredentials();

        return [
            (empty($ous) ? '' : "OU=" . implode(',OU=', $ous) . ",") . $credentials->getLdapRoot(),
            "(&{$credentials->getLdapSearchFilters()[
                $this->getBaseClassName()
            ]}(OU={$ou}))"
        ];
    }
}