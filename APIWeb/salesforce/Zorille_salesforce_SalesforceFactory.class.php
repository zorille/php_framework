<?php

namespace Zorille\salesforce;

use Exception;
use Zorille\framework\QueryBuilderFactory;
use Zorille\salesforce\query_fetchers\AssetFetcher;
use Zorille\salesforce\query_fetchers\ContactFetcher;
use Zorille\salesforce\query_fetchers\ContentDocumentLinkFetcher;
use Zorille\salesforce\query_fetchers\ContentVersionFetcher;
use Zorille\salesforce\query_fetchers\CustomerFetcher;
use Zorille\salesforce\query_fetchers\LocationFetcher;
use Zorille\salesforce\query_fetchers\OpportunityFetcher;
use Zorille\salesforce\query_fetchers\OpportunityProductFetcher;
use Zorille\salesforce\query_fetchers\OrderFetcher;

/**
 * <h1>Documentation</h1>
 * <pre>
 * <?php
 *      use Zorille\framework\QueryBuilderOperator as QLOperator;
 *
 *      $fields = [
 *          'Id',
 *          // ...
 *      ];
 *
 *      $query = Zorille\salesforce\SalesforceFactory::new()->createContactQueryBuilder()
 *          ->select(...$fields)
 *          ->where('IsDeleted', QLOperator::EQUALS, false)
 *          ->and()
 *          ->where('Email', QLOperator::DIF, '');
 *
 *      // Pour avoir la requête exécutée
 *      $request = $query->getQuery();
 *
 *      // Pour avoir le résultat de la requête
 *      $result = $query->build()->toModel()['records'];
 * ?>
 * </pre>
 *
 * @method AssetFetcher createAssetQueryBuilder()
 * @method ContactFetcher createContactQueryBuilder()
 * @method ContentDocumentLinkFetcher createContentDocumentLinkQueryBuilder()
 * @method ContentVersionFetcher createContentVersionQueryBuilder()
 * @method CustomerFetcher createCustomerQueryBuilder()
 * @method LocationFetcher createLocationQueryBuilder()
 * @method OpportunityFetcher createOpportunityQueryBuilder()
 * @method OpportunityProductFetcher createOpportunityProductQueryBuilder()
 * @method OrderFetcher createOrderQueryBuilder()
 *
 * @method static query_builder createFromClassName(string $className)
 *
 * @method string getSalesforceServeurOption()
 *
 * @property query_builder $instance
 */
class SalesforceFactory extends QueryBuilderFactory
{
    protected function getAdditionalUsedOptions(): array
    {
        return [
            ...parent::getAdditionalUsedOptions(),
            'salesforce_serveur' => [
                'value' => ''
            ]
        ];
    }

    protected function getQueryBuildersPath(): string
    {
        return __DIR__ . "/query_fetchers";
    }

    protected function getQueryBuildersNamespace(): string
    {
        return "\\Zorille\\salesforce\\query_fetchers";
    }

    protected function getQueryBuildersPrefix(): string
    {
        return "Zorille_salesforce_query_fetchers";
    }

    /**
     * @throws Exception
     */
    protected function initialize(): void
    {
        $this->instance->setSalesforceServeurOption(
            $this->getSalesforceServeurOption()
        );
    }
}
