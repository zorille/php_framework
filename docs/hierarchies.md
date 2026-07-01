# Hierarchies de classes

Cette page resume les hierarchies directes visibles dans le code. Elle ne pretend pas remplacer une analyse statique exhaustive fichier par fichier; elle documente les familles qui structurent le depot.

## Socle framework

```text
abstract_log
├─ xml
│  └─ options
├─ CommandLine
│  ├─ parametresStandard
│  └─ slurm
├─ fonctions_standards
├─ variables_standards
│  ├─ definition_fichier
│  └─ gestion_fichier
├─ repertoire
│  └─ fichier
├─ curl
├─ wsclient
├─ serveur_datas
├─ soap
├─ html
├─ javascript
├─ dates
├─ mail/enveloppe
│  └─ message
├─ monitoring/moniteur
│  ├─ nagios_client
│  └─ xymon_client
└─ nombreux helpers et classes de modules
```

`abstract_log` est le parent le plus frequent. Les classes qui n'en heritent pas directement sont souvent des DTO simples, des enums/classes de valeur, ou des modeles heritant de `data_model`.

## Donnees et modeles

```text
data_model
├─ Zorille\framework\ldap\data_model
│  ├─ ldap\data_models\Group
│  ├─ ldap\data_models\OrganizationalUnit
│  └─ ldap\data_models\Person
├─ Zorille\itop\data_models\*
└─ Zorille\salesforce\data_models\*
```

Le contrat commun est:

- `formatArrayKey($property)`;
- `create()`;
- `convert($record)`;
- `toArray()`;
- `getFields()`.

## WebService et connecteurs

```text
Core\wsclient
├─ Zorille\itop\wsclient_rest
│  └─ Zorille\itop\query_builder
├─ Zorille\salesforce\wsclient
├─ Zorille\dolibarr\wsclient
├─ Zorille\o365\wsclient
├─ Zorille\enedis\wsclient
├─ Zorille\veeamone\wsclient
├─ Zorille\veeambetr\wsclient
├─ Zorille\veeamman\wsclient
├─ Zorille\veeamspc\wsclient
└─ plusieurs wsclient historiques sous Zorille\framework
```

Les classes `datas` des connecteurs heritent le plus souvent de:

```text
Core\serveur_datas
└─ <service>\datas
```

## iTop

```text
Core\wsclient
└─ Zorille\itop\wsclient_rest
   └─ Zorille\itop\query_builder
      └─ query_fetchers\*Fetcher
```

Les fetchers definissent:

- `getObjectName()`;
- `getAssociatedModel()`.

Les modeles iTop heritent de `data_model`; plusieurs modeles etendent d'autres modeles iTop, par exemple les familles de CI, tickets, contacts, contrats, services et monitoring.

## Service Manager et OTRS

Service Manager:

```text
Core\abstract_log
└─ Zorille\servicemanager\globalapi
   └─ Zorille\servicemanager\ci
      └─ ressources Service Manager
```

OTRS:

```text
Core\abstract_log
└─ Zorille\otrs\globalapi
   └─ Zorille\otrs\ci
      └─ tickets, config items, customers
```

Les deux familles gerent un identifiant, des donnees courantes et des champs obligatoires.

## VMware

Le module VMware contient une grande hierarchie de DTO SOAP/vim25. Les branches visibles incluent:

```text
Core\abstract_log
├─ VirtualMachineCommun
├─ Vmware_Description
├─ Customization*
├─ VirtualDevice
│  ├─ VirtualController
│  ├─ VirtualDisk
│  ├─ VirtualEthernetCard
│  └─ autres devices virtuels
├─ VirtualDeviceBackingInfo
│  ├─ VirtualDeviceFileBackingInfo
│  ├─ VirtualDeviceDeviceBackingInfo
│  ├─ VirtualDevicePipeBackingInfo
│  └─ VirtualDeviceRemoteDeviceBackingInfo
├─ vmwareVim25ManagedObject
└─ vmwareVim25ManagedEntity
```

Ces classes utilisent souvent `renvoi_donnees_soap()` pour produire une structure SOAP.

## Bases API par ressource

Plusieurs modules creent leur propre base de ressource:

- `dolibarr\ci extends Core\abstract_log`;
- `pipedrive\ci`;
- `veeamman\ci`;
- `veeamspc\ci extends restapi`;
- `evobserve\globalapi`;
- `servicemanager\globalapi`;
- `otrs\globalapi`;
- classes Zabbix autour du `zabbix_wsclient`.

Le pattern visible est d'isoler dans la base commune:

- la reference au client webservice;
- les donnees courantes;
- les URI globales;
- les validations communes;
- les helpers de methodes HTTP.
