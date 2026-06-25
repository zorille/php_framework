<?php

namespace Zorille\framework\ldap\query_fetchers;

use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework\ldap\data_models\Group;
use Zorille\framework\ldap\query_fetcher;

/**
 * @method static self create()
 * @method array<Group> findAll(array $ous = [], string $cn = '*')
 * @method Group findOne(array $nonOptionalInputData)
 */
class GroupFetcher extends query_fetcher
{
    public function getObjectModel(): string
    {
        return Group::class;
    }

    public function getDefaultSelectOus(): array
    {
        return [];
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    public function getSelectData(array $ous = [], string $cn = '*'): array
    {
        $credentials = $this->getLdap()->getCredentials();

        return [
            (empty($ous) ? '' : "OU=" . implode(',OU=', $ous) . ",") . "{$credentials->getLdapRoot()}",
            "(&{$credentials->getLdapSearchFilters()[
                $this->getBaseClassName()
            ]}(CN={$cn}))"
        ];
    }
}