<?php

namespace Zorille\itop\query_fetchers;

use Exception;
use Zorille\itop\data_models\CrmAsset;
use Zorille\itop\data_models\Organization;
use Zorille\itop\ItopFactory;
use Zorille\itop\query_builder;

class CrmAssetsFetcher extends query_builder
{
    /** @var Organization[] $organizations */
    protected array $organizations = [];

    protected array $organizationFields = ['id', 'name', 'euclyde_id'];

    protected static function getObjectName(): string
    {
        return 'CrmAssets';
    }

    protected function getAssociatedModel(): string
    {
        return CrmAsset::class;
    }

    /**
     * @throws Exception
     */
    protected function beforeFetch(): self
    {
        $this->organizations = ItopFactory::new()->createOrganizationQueryBuilder()
            ->select(...$this->organizationFields)
            ->build()->toModel()['objects'];

		return $this;
    }

    protected function afterFetch(): self
    {
        $results = $this->toModel();
        /** @var CrmAsset $object */
        foreach ($results['objects'] as $object) {
            $org = $this->organizations["Organization::{$object->getOrgId()}"] ?? null;
            if (!is_null($org)) {
                $object->setOrganization($org->toArray());
            }
        }

        return $this->setResult($results);
    }
}