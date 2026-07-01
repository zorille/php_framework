# APIWeb

`APIWeb/` contient 626 fichiers PHP et regroupe les connecteurs vers des services externes. La documentation ci-dessous decrit les structures visibles dans le code; elle ne decrit pas les API externes au-dela des methodes appelees par le depot.

## Inventaire

| Sous-dossier | Fichiers PHP | Structure visible |
| --- | ---: | --- |
| `aws_cloudwatch` | 4 | `aws_datas`, `aws_wsclient`, ressources CloudWatch/EC2 |
| `bladelogic` | 2 | `bladelogic_datas`, `bladelogic_wsclient` |
| `cacti` | 12 | config Cacti, classes hosts/graphs/trees/templates |
| `dolibarr` | 11 | namespace `Zorille\dolibarr`, base `ci`, `datas`, `wsclient`, ressources |
| `enedis` | 2 | `datas`, `wsclient` REST |
| `evobserve` | 22 | base `globalapi`, `item`, ressources host/service/group/company/maintenance |
| `HP` | 16 | HPOM, Sitescope, Stars, UCMDB |
| `itop` | 180 | REST/SOAP, data models, query builders OQL, fetchers |
| `librenms` | 2 | `datas`, `wsclient` |
| `o365` | 17 | Graph, User, Group, Drive, Message, Calendar, Reports, etc. |
| `opnsense` | 2 | `datas`, `wsclient` |
| `otrs` | 12 | base `globalapi`, `item`, tickets, config items, customers |
| `pingdom` | 2 | `datas`, `wsclient` |
| `pipedrive` | 7 | `ci`, `datas`, `wsclient`, deals/leads/orgs/persons |
| `salesforce` | 29 | `datas`, `wsclient`, factory, data models, query fetchers |
| `servicemanager` | 26 | EasyVista Service Manager, base `globalapi`, `item`, operations REST |
| `solarwinds` | 3 | `datas`, `wsclient`, `query` |
| `splunk` | 28 | `datas`, `wsclient`, classes d'objets Splunk |
| `veeam` | 29 | sous-modules `veeamone`, `veeambetr`, `veeamman`, `veeamspc` |
| `vmware` | 184 | objets VMware/vim25, managed entities, devices, backing, customization |
| `zabbix` | 36 | `zabbix_wsclient`, ressources action/host/item/template/user/proxy/maps |

## Pattern commun des connecteurs

La plupart des connecteurs suivent une architecture en couches:

- `datas`: herite souvent de `Core\serveur_datas`; lit les definitions de serveurs depuis les options.
- `wsclient`: herite souvent de `Core\wsclient`; gere URL, headers, auth, requetes et parsing.
- `globalapi`, `ci`, `item` ou classe equivalente: base de ressource.
- classes ressources: exposent les operations metier.
- modeles et fetchers: representent les donnees et les requetes.

## iTop

Le module iTop est l'un des plus structures.

Elements visibles:

- `Zorille_itop_wsclient_rest.class.php`: client REST.
- `Zorille_itop_wsclient_soap.class.php`: client SOAP.
- `Zorille_itop_datas.class.php`: definitions serveurs iTop.
- `OQL/`: query builders et operateurs.
- `data_models/`: modeles d'entites iTop.
- `query_fetchers/`: fetchers associes aux modeles.
- `Zorille_itop_ItopFactory.class.php`: factory.

Le client REST prepare les payloads iTop avec `version`, `auth_user`, `auth_pwd`, `json_data`, puis appelle des operations visibles comme:

- `list_operations`;
- `core/create`;
- `core/get`;
- autres operations REST implementees plus bas dans le fichier.

Le query builder construit des requetes OQL avec `select`, `join`, `where`, alias de classes, operateurs et pagination.

## Salesforce

Le module Salesforce contient:

- `Zorille_salesforce_wsclient.class.php`;
- `Zorille_salesforce_datas.class.php`;
- `SalesforceFactory`;
- data models (`Account`, `Asset`, `Contact`, `Opportunity`, `Order`, etc.);
- query fetchers.

Le client:

- lit la definition serveur par `prepare_connexion($nom)`;
- exige `username`, `password`, `url`;
- effectue `userLogin()` avec `login`, `password`, `grant_type`;
- utilise des headers JSON et `Authorization: Bearer ...` quand un token est disponible;
- decode les reponses JSON via `prepare_retour()`;
- valide plusieurs formes d'erreurs (`code`, `httpStatusCode`, `error`).

## Service Manager

`APIWeb/servicemanager` est structure autour de `globalapi` et `item`.

`item` apporte:

- `build_uri($template, &$params)`: remplace les variables `{id}` ou `{xxx_id}` et les retire des params;
- `execute_operation($method, $template, $parametres)`: route vers `getMethod`, `postMethod`, `putMethod`, `patchMethod`, `deleteMethod`;
- validation d'identifiant et de champs obligatoires;
- stockage de `format`, `id`, `mandatory`.

Ce module expose plusieurs classes de ressources correspondant aux endpoints Service Manager presents dans le code.

## OTRS

`APIWeb/otrs` utilise aussi un pattern `globalapi` + `item`.

`item` gere:

- `prepare_standard_params()`;
- `post_operation($operation, $parametres)`;
- memorisation d'un identifiant retourne (`TicketID`, `ConfigItemID`, `CustomerUserLogin`, `CustomerID`);
- validation recursive de champs obligatoires;
- construction d'URI via `operation_uri()`.

## EvObserve

`globalapi` porte:

- langue par defaut `fr_FR`;
- donnees courantes;
- reference au `wsclient`;
- URI globale `servicenav/<lang>`.

Les classes du module representent des ressources comme boxes, companies, hosts, services, groups, maintenances, categories, tags, time periods.

## VMware

Le module VMware est majoritairement compose d'objets representant des structures VMware/vim25:

- configuration de machines virtuelles;
- devices;
- backing infos;
- customization specs;
- managed entities;
- managed objects;
- services;
- client webservice.

Beaucoup de classes heritent de `Core\abstract_log` ou de classes abstraites VMware specialisees et exposent des factories `creer_*()` ainsi que des methodes de conversion SOAP comme `renvoi_donnees_soap()`.

## Zabbix

Le module Zabbix contient:

- `zabbix_wsclient`;
- `zabbix_datas`;
- objets action, conditions, operations;
- objets host, hostgroups, interfaces, items, maps, screens, templates, users, usergroups, proxies, media types.

Les classes ressources heritent de bases communes Zabbix et s'initialisent avec `options` et souvent une reference au `zabbix_wsclient`.

## Veeam

`APIWeb/veeam` contient quatre sous-modules:

- `veeamone`;
- `veeambetr`;
- `veeamman`;
- `veeamspc`.

Les clients `wsclient` heritent de `Core\wsclient`. `veeamspc` contient une base `restapi`, une base `ci`, puis des ressources comme organizations, companies, sites, infrastructures, backup servers, jobs, protected workloads, virtual machines et backups.

## Cacti

`APIWeb/cacti/config_cacti.php` inclut des fichiers Cacti externes selon des listes d'includes. Les classes visibles couvrent ajout/suppression de devices, hosts, graphes, graph tree items, templates et trees.

## HP

Le dossier `HP/` regroupe:

- `HPOM`: fiche categorie, client, datas, SOAP Incident service;
- `sitescope`: datas, fonctions standards, SOAP configuration/preferences/report monitor/sitescope, template datas;
- `Stars`: datas, SOAP IncidentManagement;
- `ucmdb`: datas, wsclient.

## Autres connecteurs

Les modules `aws_cloudwatch`, `bladelogic`, `dolibarr`, `enedis`, `librenms`, `o365`, `opnsense`, `pingdom`, `pipedrive`, `solarwinds`, `splunk` suivent le meme esprit: un couple `datas`/`wsclient`, puis des classes de ressources ou requetes propres au service.
