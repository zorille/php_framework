<?php

namespace Zorille\itop;

use Zorille\framework\Flag;
use Zorille\framework\QueryBuilderFactory;
use Zorille\itop\query_builder as itop_query;
use Zorille\itop\query_fetchers\AppsMonitoringFetcher;
use Zorille\itop\query_fetchers\BoitierGTCFetcher;
use Zorille\itop\query_fetchers\CMDBChangeOpCreateFetcher;
use Zorille\itop\query_fetchers\CMDBChangeOpFetcher;
use Zorille\itop\query_fetchers\CMDBChangeOpSetAttributeLinksAddRemoveFetcher;
use Zorille\itop\query_fetchers\CMDBChangeOpSetAttributeScalarFetcher;
use Zorille\itop\query_fetchers\ContactFetcher;
use Zorille\itop\query_fetchers\CMDBChangeOpSetAttributeTextFetcher;
use Zorille\itop\query_fetchers\CrmAssetsFetcher;
use Zorille\itop\query_fetchers\CustomerContractFetcher;
use Zorille\itop\query_fetchers\FacilitiesMonitoringFetcher;
use Zorille\itop\query_fetchers\FunctionalCiFetcher;
use Zorille\itop\query_fetchers\IncidentFetcher;
use Zorille\itop\query_fetchers\LocationFetcher;
use Zorille\itop\query_fetchers\MiddlewareFetcher;
use Zorille\itop\query_fetchers\MiddlewareInstanceFetcher;
use Zorille\itop\query_fetchers\NetworkMonitoringFetcher;
use Zorille\itop\query_fetchers\OnduleurFetcher;
use Zorille\itop\query_fetchers\OrganizationFetcher;
use Zorille\itop\query_fetchers\OSMonitoringFetcher;
use Zorille\itop\query_fetchers\OtherSoftwareFetcher;
use Zorille\itop\query_fetchers\PCSoftwareFetcher;
use Zorille\itop\query_fetchers\PDUFetcher;
use Zorille\itop\query_fetchers\PersonFetcher;
use Zorille\itop\query_fetchers\PhysicalDeviceFetcher;
use Zorille\itop\query_fetchers\RackFetcher;
use Zorille\itop\query_fetchers\RoutineChangeFetcher;
use Zorille\itop\query_fetchers\ServerFetcher;
use Zorille\itop\query_fetchers\ServerMonitoringFetcher;
use Zorille\itop\query_fetchers\TeamFetcher;
use Zorille\itop\query_fetchers\TicketsFetcher;
use Zorille\itop\query_fetchers\UserRequestsFetcher;
use Zorille\itop\query_fetchers\VirtualMachineFetcher;
use Zorille\itop\query_fetchers\VMMonitoringFetcher;
use Zorille\itop\query_fetchers\ApplicationSolutionFetcher;
use Zorille\itop\query_fetchers\LnkCrmAssetsToFunctionalCIFetcher;
use Zorille\itop\query_fetchers\WebServerFetcher;
use Zorille\itop\query_fetchers\WorkOrderFetcher;

/**
 * <h1>Documentation</h1>
 * <pre>
 * <?php
 *      use Zorille\framework\QueryBuilderOperator as OperatorQL;
 *
 *      $fields = [
 *          'id',
 *          // ...
 *      ];
 *
 *      $query = Zorille\itop\ItopFactory::new()->createCMDBChangeOpQueryBuilder()
 *          ->select(...$fields)
 *          ->join(Person::class, on: 'objkey = ' . Person::class . '.id')
 *          ->where('objclass', OperatorQL::EQUALS, 'Person');
 *
 *      // Pour avoir la requête exécutée
 *      $request = $query->getQuery();
 *
 *      // Pour avoir le résultat de la requête
 *      $result = $query->build()->toModel()['objects'];
 * ?>
 * </pre>
 *
 * @method AppsMonitoringFetcher createAppsMonitoringQueryBuilder()
 * @method CMDBChangeOpCreateFetcher createCMDBChangeOpCreateQueryBuilder()
 * @method CMDBChangeOpFetcher createCMDBChangeOpQueryBuilder()
 * @method CMDBChangeOpSetAttributeLinksAddRemoveFetcher createCMDBChangeOpSetAttributeLinksAddRemoveQueryBuilder()
 * @method CMDBChangeOpSetAttributeScalarFetcher createCMDBChangeOpSetAttributeScalarQueryBuilder()
 * @method CMDBChangeOpSetAttributeTextFetcher createCMDBChangeOpSetAttributeTextQueryBuilder()
 * @method CrmAssetsFetcher createCrmAssetsQueryBuilder()
 * @method CustomerContractFetcher createCustomerContractQueryBuilder()
 * @method FacilitiesMonitoringFetcher createFacilitiesMonitoringQueryBuilder()
 * @method FunctionalCiFetcher createFunctionalCiQueryBuilder()
 * @method IncidentFetcher createIncidentQueryBuilder()
 * @method OrganizationFetcher createOrganizationQueryBuilder()
 * @method OSMonitoringFetcher createOSMonitoringQueryBuilder()
 * @method PersonFetcher createPersonQueryBuilder()
 * @method TeamFetcher createTeamQueryBuilder()
 * @method TicketsFetcher createTicketsQueryBuilder()
 * @method UserRequestsFetcher createUserRequestsQueryBuilder()
 * @method VMMonitoringFetcher createVMMonitoringQueryBuilder()
 * @method NetworkMonitoringFetcher createNetworkMonitoringQueryBuilder()
 * @method ServerMonitoringFetcher createServerMonitoringQueryBuilder()
 * @method PDUFetcher createPDUQueryBuilder()
 * @method BoitierGTCFetcher createBoitierGTCQueryBuilder()
 * @method ApplicationSolutionFetcher createApplicationSolutionQueryBuilder()
 * @method LnkCrmAssetsToFunctionalCIFetcher createLnkCrmAssetsToFunctionalCIQueryBuilder()
 * @method MiddlewareFetcher createMiddlewareQueryBuilder()
 * @method MiddlewareInstanceFetcher createMiddlewareInstanceQueryBuilder()
 * @method PCSoftwareFetcher createPCSoftwareQueryBuilder()
 * @method OtherSoftwareFetcher createOtherSoftwareQueryBuilder()
 * @method WebServerFetcher createWebServerQueryBuilder()
 * @method WorkOrderFetcher createWorkOrderQueryBuilder()
 * @method PhysicalDeviceFetcher createPhysicalDeviceQueryBuilder()
 * @method VirtualMachineFetcher createVirtualMachineQueryBuilder()
 * @method ServerFetcher createServerQueryBuilder()
 * @method RackFetcher createRackQueryBuilder()
 * @method ContactFetcher createContactQueryBuilder()
 * @method OnduleurFetcher createOnduleurQueryBuilder()
 * @method LocationFetcher createLocationQueryBuilder()
 * @method RoutineChangeFetcher createRoutineChangeQueryBuilder()
 *
 * @method static query_builder createFromClassName(string $className)
 *
 * @method string getItopServeurOption()
 */
class ItopFactory extends QueryBuilderFactory
{
    #[Flag]
    public string $itop_serveur = '';

    /*protected function getAdditionalUsedOptions(): array
    {
        return [
            'itop_serveur' => [
                'value' => ''
            ]
        ];
    }*/

    protected function getQueryBuildersPath(): string
    {
        return __DIR__ . "/query_fetchers";
    }

    protected function getQueryBuildersNamespace(): string
    {
        return "\\Zorille\\itop\\query_fetchers";
    }

    protected function getQueryBuildersPrefix(): string
    {
        return "Zorille_itop_query_fetchers";
    }

    protected function beforeInitialize(): void
    {
        global $liste_option;
        itop_query::setListOptions($liste_option);
        itop_query::setItopServer($this->getItopServeurOption());
    }
}
