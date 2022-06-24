<?php

/**
 * @author dvargas
 * @package Lib
 *
 */
namespace Zorille\o365;

use Zorille\framework as Core;

/**
 * class VirtualMachineCommun<br>
 * @package Lib
 * @subpackage VMWare
 */
abstract class Graph extends Core\abstract_log {
	/**
	 * var privee
	 *
	 * @access private
	 * @var wsclient
	 */
	private $wsclient = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var string
	 */
	private $ws_reponse = null;
	/**
	 * var privee
	 *
	 * @access private
	 * @var array
	 */
	private $tableau_licence = array (
			// "WINDOWS_STORE"=>"Microsoft Windows",
			// "STANDARDPACK"=>"Office 365 E1",
			// "ENTERPRISEPACK"=>"Office 365 E3",
			// "ENTERPRISEPREMIUM"=>"Office 365 E5",
			// "PROJECTESSENTIALS"=>"Projects Online Essential",
			// "FLOW_FREE"=>"Microsoft Power Automate Free",
			// "PROJECT_P1"=>"Project (plan 1)",
			"ADV_COMMS" => "Advanced Communications",
			"CDSAICAPACITY" => "Module complémentaire capacité d’AI Builder",
			"SPZA_IW" => "APP CONNECT IW",
			"MCOMEETADV" => "Audioconférence Microsoft 365",
			"AAD_BASIC" => "AZURE ACTIVE DIRECTORY BASIC",
			"AAD_PREMIUM" => "AZURE ACTIVE DIRECTORY PREMIUM P1",
			"AAD_PREMIUM_P2" => "AZURE ACTIVE DIRECTORY PREMIUM P2",
			"RIGHTSMANAGEMENT" => "AZURE INFORMATION PROTECTION PLAN 1",
			"SMB_APPS" => "Business Apps (gratuit)",
			"MCOCAP" => "TÉLÉPHONE D’ESPACE COMMUN",
			"MCOCAP_GOV" => "Téléphone d’espace commun pour GCC",
			"CDS_DB_CAPACITY" => "Capacité de base de données Common Data Service",
			"CDS_LOG_CAPACITY" => "Capacité du journal Common Data Service",
			"MCOPSTNC" => "CRÉDITS DE COMMUNICATION",
			"CRMSTORAGE" => "Dynamics 365 - Stockage de base de données supplémentaire (offre qualifiée)",
			"CRMINSTANCE" => "Dynamics 365 - Instance de production supplémentaire (offre qualifiée)",
			"CRMTESTINSTANCE" => "Dynamics 365 - Instance hors production supplémentaire (offre qualifiée)",
			"SOCIAL_ENGAGEMENT_APP_USER" => "Dynamics 365 AI for Market Insights (Preview)",
			"DYN365_ASSETMANAGEMENT" => "Ressources supplémentaires de gestion des ressources Dynamics 365",
			"DYN365_BUSCENTRAL_ADD_ENV_ADDON" => "Module complémentaire d’environnement supplémentaire Dynamics 365 Business Central",
			"DYN365_BUSCENTRAL_DB_CAPACITY" => "Capacité de la base de données Dynamics 365 Business Central",
			"DYN365_BUSCENTRAL_ESSENTIAL" => "Dynamics 365 Business Central Essentials",
			"DYN365_FINANCIALS_ACCOUNTANT_SKU" => "Comptable externe Dynamics 365 Business Central",
			"PROJECT_MADEIRA_PREVIEW_IW_SKU" => "Dynamics 365 Business Central pour les travailleurs de l’information",
			"DYN365_BUSCENTRAL_PREMIUM" => "Dynamics 365 Business Central Premium",
			"DYN365_ENTERPRISE_PLAN1" => "Dynamics 365 Customer Engagement Plan",
			"DYN365_CUSTOMER_INSIGHTS_VIRAL" => "Dynamics 365 Customer Insights Viral",
			"Dynamics_365_Customer_Service_Enterprise_viral_trial" => "Dynamics 365 Customer Service Enterprise Viral Trial",
			"DYN365_AI_SERVICE_INSIGHTS" => "Version d’évaluation de Dynamics 365 Customer Service",
			"FORMS_PRO" => "Dynamics 365 Customer Voice - Essai",
			"DYN365_CUSTOMER_SERVICE_PRO" => "Dynamics 365 Customer Service Professional",
			"Forms_Pro_AddOn" => "Dynamics 365 Customer Voice Additional Responses",
			"DYN365_CUSTOMER_VOICE_ADDON" => "Dynamics 365 Customer Voice Additional Responses",
			"Forms_Pro_USL" => "Dynamics 365 Customer Voice USL",
			"CRM_ONLINE_PORTAL" => "Dynamics 365 Édition Entreprise - Portail supplémentaire (offre qualifiée)",
			"Dynamics_365_Field_Service_Enterprise_viral_trial" => "Dynamics 365 Field Service Viral Trial",
			"DYN365_FINANCE" => "Dynamics 365 Finance",
			"DYN365_ENTERPRISE_CUSTOMER_SERVICE" => "Dynamics 365 for Customer Service, édition Entreprise",
			"DYN365_FINANCIALS_BUSINESS_SKU" => "DYNAMICS 365 POUR LES FINANCES, ÉDITION BUSINESS",
			"DYN365_ENTERPRISE_SALES_CUSTOMERSERVICE" => "DYNAMICS 365 POUR LES VENTES ET LE SERVICE CLIENT, ÉDITION ENTREPRISE",
			"DYN365_ENTERPRISE_SALES" => "DYNAMICS 365 POUR LES VENTES, ÉDITION ENTREPRISE",
			"D365_SALES_PRO" => "Dynamics 365 for Sales Professional",
			"D365_SALES_PRO_IW" => "Dynamics 365 for Sales Professional Trial",
			"D365_SALES_PRO_ATTACH" => "Dynamics 365 Sales Professional Attach to Qualifying Dynamics 365 Base Offer",
			"DYN365_SCM" => "DYNAMICS 365 POUR LA GESTION DE LA CHAÎNE LOGISTIQUE",
			"SKU_Dynamics_365_for_HCM_Trial" => "Dynamics 365 for Talent",
			"DYN365_ENTERPRISE_TEAM_MEMBERS" => "DYNAMICS 365 POUR LES MEMBRE DE L’ÉQUIPE, ÉDITION ENTREPRISE",
			"GUIDES_USER" => "Dynamics 365 Guides",
			"DYN365_BUSINESS_MARKETING" => "Dynamics 365 Marketing Business Edition",
			"Dynamics_365_for_Operations_Devices" => "Dynamics 365 Operations – Appareil",
			"Dynamics_365_for_Operations_Sandbox_Tier2_SKU" => "Dynamics 365 Operations - Sandbox niveau 2 : Test d’acceptation standard",
			"Dynamics_365_for_Operations_Sandbox_Tier4_SKU" => "Dynamics 365 Operations - Sandbox niveau 4 : Test de performances standard",
			"DYN365_ENTERPRISE_P1_IW" => "ESSAI GRATUIT DE DYNAMICS 365 P1 POUR LES TRAVAILLEURS DE L’INFORMATION",
			"DYN365_REGULATORY_SERVICE" => "Dynamics 365 Regulatory Service - Enterprise Edition Trial",
			"MICROSOFT_REMOTE_ASSIST" => "Dynamics 365 Remote Assist",
			"MICROSOFT_REMOTE_ASSIST_HOLOLENS" => "Dynamics 365 Remote Assist HoloLens",
			"D365_SALES_ENT_ATTACH" => "Dynamics 365 Sales Enterprise Attach to Qualifying Dynamics 365 Base Offer",
			"Dynamics_365_Sales_Premium_Viral_Trial" => "Dynamics 365 Sales Premium Viral Trial",
			"Dynamics_365_Hiring_SKU" => "Dynamics 365 Talent : Attract",
			"DYNAMICS_365_ONBOARDING_SKU" => "DYNAMICS 365 TALENT : ONBOARD",
			"DYN365_TEAM_MEMBERS" => "DYNAMICS 365 TEAM MEMBERS",
			"Dynamics_365_for_Operations" => "DYNAMICS 365 UNF OPS PLAN ENT EDITION",
			"EMS" => "ENTERPRISE MOBILITY + SECURITY E3",
			"EMSPREMIUM" => "ENTERPRISE MOBILITY + SECURITY E5",
			"EMS_GOV" => "Enterprise Mobility + Security G3 GCC",
			"EMSPREMIUM_GOV" => "Enterprise Mobility + Security G5 GCC",
			"EOP_ENTERPRISE_PREMIUM" => "Exchange Enterprise CAL Services (EOP, DLP)",
			"EXCHANGESTANDARD" => "Exchange Online (plan 1)",
			"EXCHANGEENTERPRISE" => "EXCHANGE ONLINE (PLAN 2)",
			"EXCHANGEARCHIVE_ADDON" => "ARCHIVAGE EN LIGNE EXCHANGE POUR EXCHANGE ONLINE",
			"EXCHANGEARCHIVE" => "ARCHIVAGE EN LIGNE EXCHANGE POUR EXCHANGE SERVER",
			"EXCHANGEESSENTIALS" => "EXCHANGE ONLINE ESSENTIALS (BASÉ SUR ExO P1)",
			"EXCHANGE_S_ESSENTIALS" => "EXCHANGE ONLINE ESSENTIALS",
			"EXCHANGEDESKLESS" => "EXCHANGE ONLINE KIOSK",
			"EXCHANGETELCO" => "EXCHANGE ONLINE POP",
			"INTUNE_A" => "INTUNE",
			"AX7_USER_TRIAL" => "Version d’essai de Microsoft Dynamics AX7",
			"MFA_STANDALONE" => "Microsoft Azure Multi-Factor Authentication",
			"THREAT_INTELLIGENCE" => "Microsoft Defender pour Office 365 (Plan 2)",
			"M365EDU_A1" => "Microsoft 365 A1",
			"M365EDU_A3_FACULTY" => "Microsoft 365 A3 pour enseignants",
			"M365EDU_A3_STUDENT" => "MICROSOFT 365 A3 POUR ÉTUDIANTS",
			"M365EDU_A3_STUUSEBNFT" => "Microsoft 365 A3 pour étudiants",
			"M365EDU_A3_STUUSEBNFT_RPA1" => "Microsoft 365 A3 - Licence sans assistance pour étudiants",
			"M365EDU_A5_FACULTY" => "MICROSOFT 365 A5 pour Enseignants",
			"M365EDU_A5_STUDENT" => "MICROSOFT 365 A5 POUR ÉTUDIANTS",
			"M365EDU_A5_STUUSEBNFT" => "Microsoft 365 A5 pour étudiants",
			"M365EDU_A5_NOPSTNCONF_STUUSEBNFT" => "Microsoft 365 A5 sans audioconférence pour étudiants",
			"O365_BUSINESS" => "APPLICATIONS MICROSOFT 365 POUR LES ENTREPRISES",
			"SMB_BUSINESS" => "APPLICATIONS MICROSOFT 365 POUR LES ENTREPRISES",
			"OFFICESUBSCRIPTION" => "APPLICATIONS MICROSOFT 365 POUR LES ENTREPRISES",
			"MCOMEETADV_GOC" => "AUDIOCONFÉRENCE MICROSOFT 365 POUR GCC",
			"O365_BUSINESS_ESSENTIALS" => "MICROSOFT 365 BUSINESS BASIC",
			"SMB_BUSINESS_ESSENTIALS" => "MICROSOFT 365 BUSINESS BASIC",
			"O365_BUSINESS_PREMIUM" => "MICROSOFT 365 BUSINESS STANDARD",
			"SMB_BUSINESS_PREMIUM" => "MICROSOFT 365 BUSINESS STANDARD – HÉRITÉ PRÉPAYÉ",
			"SPB" => "MICROSOFT 365 BUSINESS PREMIUM",
			"BUSINESS_VOICE_MED2_TELCO" => "Microsoft 365 Business Voice (É.-U.)",
			"BUSINESS_VOICE_DIRECTROUTING" => "Microsoft 365 Business Voice (sans forfait d'appels)",
			"BUSINESS_VOICE_DIRECTROUTING_MED" => "Microsoft 365 Business Voice (sans forfait d’appels) pour les États-Unis",
			"MCOPSTN_5" => "PLAN D'APPELS NATIONAUX MICROSOFT 365 (120 minutes)",
			"MCOPSTN_1_GOV" => "Forfait d’appels nationaux Microsoft 365 pour le cloud de la communauté du secteur public",
			"SPE_E3" => "MICROSOFT 365 E3",
			"SPE_E3_RPA1" => "Microsoft 365 E3 - Licence sans assistance",
			"SPE_E3_USGOV_DOD" => "Microsoft 365 E3_USGOV_DOD",
			"SPE_E3_USGOV_GCCHIGH" => "Microsoft 365 E3_USGOV_GCCHIGH",
			"SPE_E5" => "Microsoft 365 E5",
			"INFORMATION_PROTECTION_COMPLIANCE" => "Conformité Microsoft 365 E5",
			"IDENTITY_THREAT_PROTECTION" => "Sécurité Microsoft 365 E5",
			"IDENTITY_THREAT_PROTECTION_FOR_EMS_E5" => "Sécurité Microsoft 365 E5 pour EMS E5",
			"SPE_E5_NOPSTNCONF" => "Microsoft 365 E5 sans audioconférence",
			"M365_F1" => "Microsoft 365 F1",
			"SPE_F1" => "Microsoft 365 F3",
			"SPE_F5_SECCOMP" => "Module complémentaire de sécurité et de conformité Microsoft 365 F5",
			"FLOW_FREE" => "MICROSOFT FLOW GRATUIT",
			"MCOMEETADV_GOV" => "AUDIOCONFÉRENCE MICROSOFT 365 POUR GCC",
			"M365_E5_SUITE_COMPONENTS" => "Fonctionnalités de Microsoft 365 E5 Suite",
			"M365_F1_COMM" => "Microsoft 365 F1",
			"M365_G3_GOV" => "MICROSOFT 365 G3 POUR GCC",
			"MCOEV" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365",
			"MCOEV_DOD" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365 POUR LE DOD",
			"MCOEV_FACULTY" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365 POUR L’ENSEIGNEMENT",
			"MCOEV_GOV" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365 POUR GCC",
			"MCOEV_GCCHIGH" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365 POUR GCCHIGH",
			"MCOEVSMB_1" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365 POUR LES PETITES ET MOYENNES ENTREPRISES",
			"MCOEV_STUDENT" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365 POUR LES ÉTUDIANTS",
			"MCOEV_TELSTRA" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365 POUR TELSTRA",
			"MCOEV_USGOV_DOD" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365_USGOV_DOD",
			"MCOEV_USGOV_GCCHIGH" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365_USGOV_GCCHIGH",
			"PHONESYSTEM_VIRTUALUSER" => "SYSTÈME TÉLÉPHONIQUE MICROSOFT 365 - UTILISATEUR VIRTUEL",
			"PHONESYSTEM_VIRTUALUSER_GOV" => "Système téléphonique Microsoft 365 - Utilisateur virtuel pour le GCC",
			"M365_SECURITY_COMPLIANCE_FOR_FLW" => "Sécurité et conformité Microsoft 365 pour les travailleurs en première ligne",
			"MICROSOFT_BUSINESS_CENTER" => "MICROSOFT BUSINESS CENTER",
			"ADALLOM_STANDALONE" => "Microsoft Cloud App Security",
			"WIN_DEF_ATP" => "MICROSOFT DEFENDER POUR POINT DE TERMINAISON",
			"MDATP_Server" => "Microsoft Defender pour les serveurs point de terminaison",
			"CRMPLAN2" => "MICROSOFT DYNAMICS CRM ONLINE BASIC",
			"ATA" => "Microsoft Defender pour Identity",
			"THREAT_INTELLIGENCE_GOV" => "Microsoft Defender pour Office 365 (Plan 2) GCC",
			"CRMSTANDARD" => "MICROSOFT DYNAMICS CRM ONLINE",
			"IT_ACADEMY_AD" => "MS IMAGINE ACADEMY",
			"INTUNE_A_D" => "Appareil Microsoft Intune",
			"INTUNE_A_D_GOV" => "APPAREIL MICROSOFT INTUNE POUR LE SECTEUR PUBLIC",
			"POWERAPPS_VIRAL" => "Microsoft Power Apps Plan 2 évaluation",
			"POWERAPPS_DEV" => "Microsoft Power Apps pour les développeurs",
			"FLOW_P2" => "MICROSOFT POWER AUTOMATE PLAN 2",
			"INTUNE_SMB" => "MICROSOFT INTUNE SMB",
			"POWERFLOW_P2" => "Microsoft Power Apps Plan 2 (offre qualifiée)",
			"Flux" => "MICROSOFT STREAM",
			"STREAM_P2" => "Microsoft Stream Plan 2",
			"STREAM_STORAGE" => "Stockage supplémentaire Microsoft Stream (500 GB)",
			"TEAMS_FREE" => "MICROSOFT TEAMS (GRATUIT)",
			"TEAMS_EXPLORATORY" => "MICROSOFT TEAMS EXPLORATORY",
			"MEETING_ROOM" => "Microsoft Teams Rooms Standard",
			"MS_TEAMS_IW" => "Evaluation Microsoft Teams",
			"EXPERTS_ON_DEMAND" => "Microsoft Threat Experts - Experts à la demande",
			"OFFICE365_MULTIGEO" => "Fonctionnalités multigéographiques dans Office 365",
			"MTR_PREM" => "Salles Teams Premium",
			"ENTERPRISEPACKPLUS_FACULTY" => "Office 365 A3 pour le corps enseignant",
			"ENTERPRISEPACKPLUS_STUDENT" => "Office 365 A3 pour les étudiants",
			"ENTERPRISEPREMIUM_FACULTY" => "Office 365 A5 pour les enseignants",
			"ENTERPRISEPREMIUM_STUDENT" => "Office 365 a5 pour les étudiants",
			"EQUIVIO_ANALYTICS" => "Compatibilité avancée Office 365",
			"ATP_ENTERPRISE" => "Microsoft Defender pour Office 365 (Plan 1)",
			"SHAREPOINTSTORAGE_GOV" => "Stockage de fichiers supplémentaire Office 365 pour GCC",
			"TEAMS_COMMERCIAL_TRIAL" => "Cloud commercial Microsoft Teams",
			"ADALLOM_O365" => "Sécurité des applications cloud Office 365",
			"SHAREPOINTSTORAGE" => "Stockage de fichiers supplémentaire Office 365",
			"STANDARDPACK" => "OFFICE 365 E1",
			"STANDARDWOFFPACK" => "OFFICE 365 E2",
			"ENTERPRISEPACK" => "Office 365 E3",
			"DEVELOPERPACK" => "OFFICE 365 E3 DEVELOPER",
			"ENTERPRISEPACK_USGOV_DOD" => "Office 365 E3_USGOV_DOD",
			"ENTERPRISEPACK_USGOV_GCCHIGH" => "Office 365 E3_USGOV_GCCHIGH",
			"ENTERPRISEWITHSCAL" => "OFFICE 365 E4",
			"ENTERPRISEPREMIUM" => "Office 365 E5",
			"ENTERPRISEPREMIUM_NOPSTNCONF" => "OFFICE 365 E5 SANS AUDIOCONFÉRENCE",
			"DESKLESSPACK" => "OFFICE 365 F3",
			"ENTERPRISEPACK_GOV" => "OFFICE 365 G3 POUR GCC",
			"ENTERPRISEPREMIUM_GOV" => "Office 365 G5 GCC",
			"MIDSIZEPACK" => "OFFICE 365 MIDSIZE BUSINESS",
			"LITEPACK" => "OFFICE 365 SMALL BUSINESS",
			"LITEPACK_P2" => "OFFICE 365 SMALL BUSINESS PREMIUM",
			"WACONEDRIVESTANDARD" => "ONEDRIVE FOR BUSINESS (PLAN 1)",
			"WACONEDRIVEENTERPRISE" => "ONEDRIVE FOR BUSINESS (PLAN 2)",
			"POWERAPPS_INDIVIDUAL_USER" => "POWERAPPS ET FLUX LOGIQUES",
			"POWERAPPS_PER_APP_IW" => "Accès à la ligne de base PowerApps par application",
			"POWERAPPS_PER_APP" => "Plan Power Apps par application",
			"POWERAPPS_PER_USER" => "Power Apps par plan utilisateur",
			"FLOW_BUSINESS_PROCESS" => "Plan par flux Power Automate",
			"FLOW_PER_USER" => "Power Automate par plan utilisateur",
			"FLOW_PER_USER_DEPT" => "Power Automate par service de plan utilisateur",
			"POWERAUTOMATE_ATTENDED_RPA" => "Power Automate par utilisateur avec plan RPA avec assistance",
			"POWERAUTOMATE_UNATTENDED_RPA" => "Module complémentaire RPA sans assistance Power Automate",
			"POWER_BI_INDIVIDUAL_USER" => "Power BI",
			"POWER_BI_STANDARD" => "Power BI (gratuit)",
			"POWER_BI_ADDON" => "POWER BI FOR OFFICE 365 ADD-ON",
			"PBI_PREMIUM_P1_ADDON" => "Power BI Premium P1",
			"PBI_PREMIUM_PER_USER" => "Power BI Premium par utilisateur",
			"PBI_PREMIUM_PER_USER_ADDON" => "Power BI Premium Per User Add-On",
			"PBI_PREMIUM_PER_USER_DEPT" => "Power BI Premium par service d’utilisateurs",
			"POWER_BI_PRO" => "Power BI Pro",
			"POWER_BI_PRO_CE" => "Power BI Pro CE",
			"POWER_BI_PRO_DEPT" => "Power BI Pro Dept",
			"VIRTUAL_AGENT_BASE" => "Agent Power Virtual",
			"CCIBOTS_PRIVPREV_VIRAL" => "Version d’évaluation Power Virtual Agents Viral",
			"PROJECTCLIENT" => "PROJECT FOR OFFICE 365",
			"PROJECTESSENTIALS" => "Project Online Essentials",
			"PROJECTPREMIUM" => "PROJECT ONLINE PREMIUM",
			"PROJECTONLINE_PLAN_1" => "PROJECT ONLINE PREMIUM SANS PROJECT CLIENT",
			"PROJECTONLINE_PLAN_2" => "PROJECT ONLINE AVEC PROJECT FOR OFFICE 365",
			"PROJECT_P1" => "PROJECT PLAN 1",
			"PROJECT_PLAN1_DEPT" => "Project Plan 1 (pour Département)",
			"PROJECTPROFESSIONAL" => "Project Plan 3",
			"PROJECT_PLAN3_DEPT" => "Project Plan 3 (pour Département)",
			"PROJECTPROFESSIONAL_GOV" => "Project Plan 3 pour GCC",
			"PROJECTPREMIUM_GOV" => "Project Plan 5 pour GCC",
			"RIGHTSMANAGEMENT_ADHOC" => "Rights Management Adhoc",
			"RMSBASIC" => "Protection du contenu de base pour le service Rights Management",
			"DYN365_IOT_INTELLIGENCE_ADDL_MACHINES" => "Module complémentaire Data Intelligence pour les appareils supplémentaires pour la gestion de la chaîne logistique Dynamics 365",
			"DYN365_IOT_INTELLIGENCE_SCENARIO" => "Module complémentaire Data Intelligence pour les scénarios de gestion de la chaîne logistique Dynamics 365",
			"SHAREPOINTSTANDARD" => "SHAREPOINT ONLINE (PLAN 1)",
			"SHAREPOINTENTERPRISE" => "SHAREPOINT ONLINE (PLAN 2)",
			"Intelligent_Content_Services" => "SharePoint Syntex",
			"MCOIMP" => "SKYPE FOR BUSINESS ONLINE (PLAN 1)",
			"MCOSTANDARD" => "SKYPE FOR BUSINESS ONLINE (PLAN 2)",
			"MCOPSTN2" => "SKYPE FOR BUSINESS PSTN DOMESTIC AND INTERNATIONAL CALLING",
			"MCOPSTN1" => "SKYPE FOR BUSINESS PSTN DOMESTIC CALLING",
			"MCOPSTN5" => "APPELS NATIONAUX PSTN SKYPE ENTREPRISE (120 minutes)",
			"MCOPSTNEAU2" => "APPELS TELSTRA POUR O365",
			"UNIVERSAL_PRINT" => "Impression universelle",
			"VISIO_PLAN1_DEPT" => "Visio Plan 1",
			"VISIO_PLAN2_DEPT" => "Visio Plan 2",
			"VISIOONLINE_PLAN1" => "VISIO ONLINE PLAN 1",
			"VISIOCLIENT" => "VISIO ONLINE PLAN 2",
			"VISIOCLIENT_GOV" => "VISIO PLAN 2 POUR GCC",
			"TOPIC_EXPERIENCES" => "Viva Topics",
			"WIN10_ENT_A3_FAC" => "Windows 10 Entreprise A3 pour les enseignants",
			"WIN10_ENT_A3_STU" => "Windows 10 Enterprise A3 pour les étudiants",
			"WIN10_PRO_ENT_SUB" => "WINDOWS 10 ENTERPRISE E3",
			"WIN10_VDA_E3" => "WINDOWS 10 ENTERPRISE E3",
			"WIN10_VDA_E5" => "Windows 10 Entreprise E5",
			"WINE5_GCC_COMPAT" => "Windows 10 Enterprise E5 Commercial (compatible GCC)",
			"CPC_B_2C_4RAM_64GB" => "Windows 365 Business 2 vCPU, 4 GB, 64 GB",
			"CPC_B_4C_16RAM_128GB_WHB" => "Windows 365 Entreprise, 4 processeurs virtuels, 16 Go, 128 Go (avec Windows Hybrid Benefit)",
			"CPC_E_2C_4GB_64GB" => "Windows 365 Enterprise 2 vCPU, 4 GB, 64 GB",
			"CPC_E_2C_8GB_128GB" => "Windows 365 Enterprise 2 vCPU, 8 Go, 128 Go",
			"CPC_LVL_2" => "Windows 365 Enterprise 2 vCPU, 8 Go, 128 Go (préversion)",
			"CPC_LVL_3" => "Windows 365 Enterprise 4 vCPU, 16 Go, 256 Go (préversion)",
			"WINDOWS_STORE" => "WINDOWS STORE POUR ENTREPRISES",
			"WORKPLACE_ANALYTICS" => "Microsoft Workplace Analytics"
	);

	/**
	 * Initialisation de l'objet @codeCoverageIgnore
	 * @param array $liste_class
	 * @return Item
	 */
	public function &_initialise(
			$liste_class) {
		parent::_initialise ( $liste_class );
		return $this->setObjetO365Wsclient ( $liste_class ['wsclient'] );
	}

	public function valide_champ_value(
			$reponse) {
		if (! isset ( $reponse->value )) {
			$this->onWarning ( "Il n'y a pas de donnees dans la response" );
			return false;
		}
		return true;
	}

	public function prepare_nom_pour_url(
			$recherche) {
		return str_replace ( " ", "%20", $recherche );
	}

	/**
	 * ***************************** ACCESSEURS *******************************
	 */
	/**
	 * @codeCoverageIgnore
	 * @return wsclient
	 */
	public function &getObjetO365Wsclient() {
		return $this->wsclient;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setObjetO365Wsclient(
			&$wsclient) {
		$this->wsclient = $wsclient;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getWsReponse() {
		return $this->ws_reponse;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setWsReponse(
			$ws_reponse) {
		$this->ws_reponse = $ws_reponse;
		return $this;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function getTableauLicence() {
		return $this->tableau_licence;
	}

	/**
	 * @codeCoverageIgnore
	 */
	public function &setTableauLicence(
			$tableau_licence) {
		$this->tableau_licence = $tableau_licence;
		return $this;
	}
/**
 * ***************************** ACCESSEURS *******************************
 */
}
?>
