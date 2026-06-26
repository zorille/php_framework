<?php

namespace Zorille\itop;

abstract class query_builder_default_selected_fields
{
    const Contact = ["name", "org_id_friendlyname", "status", "friendlyname", "email"];
    const CrmAssets = ["name", "org_id_friendlyname", "status", "friendlyname", "tickets_list"];
    const Organization = ["name", "code", "status", "friendlyname"];
    const CustomerContract = ["name", "friendlyname", "org_id_friendlyname", "provider_id_friendlyname", "services_list"];
    const Ticket = ["ref", "title", "operational_status", "org_id_friendlyname", "start_date", "close_date"];
    const Incident = ["ref", "title", "status", "org_id_friendlyname", "start_date", "close_date"];
    const UserRequest = ["ref", "title", "status", "org_id_friendlyname", "start_date", "close_date"];
    const Change = ["ref", "title", "status", "org_id_friendlyname", "start_date", "close_date"];
    const Project = ["ref", "title", "status", "org_id_friendlyname", "start_date", "close_date"];
    const WorkOrder = ["ticket_ref", "name", "team_id_friendlyname", "agent_id_friendlyname"];
    const PhysicalDevice = ["name", "friendlyname", "brand_name", "model_name", "status", "location_name", "serialnumber", "asset_number", "purchase_date", "description", "finalclass"];
    const NetworkDevice = ["name", "fqdn", "org_id", "managementip", "location_name", "networkdevicetype_name"];
    const VirtualMachine = ["name", "managementip", "business_criticity", "move2production", "osfamily_name", "osversion_name", "cpu", "ram"];
    const LogicalVolume = ["name"];
    const Server = ["name", "managementip", "business_criticity", "move2production", "osfamily_name", "osversion_name", "cpu", "ram"];
    const Rack = ["name", "friendlyname", "pod_id", "status", "org_id_friendlyname", "brand_id_friendlyname", "model_id_friendlyname", "description", "device_list"];
    const Pdu = ["id", "name", "friendlyname", "status", "org_id_friendlyname", "rack_id_friendlyname", "location_name", "brand_id_friendlyname", "model_id_friendlyname", "description"];
    const Middleware = ["name", "friendlyname", "software_id_friendlyname", "system_name"];
    const MiddlewareInstance = ["name", "friendlyname", "middleware_id_friendlyname"];
    const PCSoftware = ["name", "friendlyname", "software_id_friendlyname", "system_name"];
    const OtherSoftware = ["name", "friendlyname", "software_id_friendlyname", "system_name"];
    const WebServer = ["name", "friendlyname", "software_id_friendlyname", "system_name"];
    const WebApplication = ["name"];
    const IPInterface = ["name", "friendlyname", "ipaddress"];
    const BoitierGTC = ["name", "friendlyname", "fqdn", "brand_name", "model_name", "status", "location_name", "serialnumber", "asset_number", "purchase_date", "description"];
    const VCenter = ["name", "managementip", "business_criticity", "move2production", "osfamily_name", "osversion_name", "cpu", "ram"];
    const FunctionalCI = [
        "id", "name", "description", "organization_name",
        "business_criticity", "move2production", "contacts_list", "documents_list",
        "applicationsolution_list", "softwares_list", "providercontracts_list", "services_list",
        "tickets_list", "accesspermissions_list", "overview", "monitoring_list",
        "needmonitoring", "backups_list", "uniq_name", "crmassetss_list",
        "finalclass", "friendlyname", "obsolescence_date",
        "org_id_friendlyname", "org_id_obsolescence_flag"
    ];
	const CMDBChangeOp = [
		'id', 'change', 'date', 'userinfo',
		'user_id', 'objclass', 'objkey', 'finalclass',
		'friendlyname', 'change_friendlyname', 'user_id_friendlyname'
	];
	const CMDBChangeOpSetAttributeLinksAddRemove = [
		'id', 'change', 'date', 'userinfo',
		'user_id', 'objclass', 'objkey', 'attcode',
		'item_class', 'item_id', 'type', 'finalclass',
		'friendlyname', 'change_friendlyname', 'user_id_friendlyname'
	];
    const ApplicationSolution = [
        "id", "name", "description", "organization_name",
        "business_criticity", "move2production", "contacts_list", "documents_list",
        "applicationsolution_list", "softwares_list", "providercontracts_list", "services_list",
        "tickets_list", "accesspermissions_list", "overview", "monitoring_list",
        "needmonitoring", "backups_list", "uniq_name", "crmassetss_list",
        "finalclass", "friendlyname", "obsolescence_flag", "obsolescence_date",
        "org_id_friendlyname", "org_id_obsolescence_flag", "functionalcis_list", "businessprocess_list",
        "status", "redundancy", "organization_euclyde_id"
    ];
    const AppsMonitoring = [
        "id", "name", "org_id", "organization_name", "organization_euclyde_id", "business_criticity",
        "status", "functionalci_id", "functionalci_name", "modele_id", "modele_name", "monitoring_id", "monitoring_url",
        "etiquettes", "monitoring_box_id", "monitoring_box_name", "monitoring_host_id", "monitoring_host_name",
        "monitoring_apps_standard_account", "service_params", "finalclass", "friendlyname", "obsolescence_flag",
        "obsolescence_date", "org_id_friendlyname", "org_id_obsolescence_flag", "functionalci_id_friendlyname",
        "functionalci_id_finalclass_recall", "functionalci_id_obsolescence_flag", "modele_id_friendlyname",
        "monitoring_box_id_friendlyname", "monitoring_box_id_finalclass_recall", "monitoring_box_id_obsolescence_flag",
        "monitoring_host_id_friendlyname", "monitoring_host_id_finalclass_recall", "monitoring_host_id_obsolescence_flag",
    ];
}
