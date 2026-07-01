# Index technique

## Repertoires

| Repertoire | Fichiers PHP | Notes |
| --- | ---: | --- |
| `APIWeb` | 626 | Clients API et modeles tiers |
| `class_globals` | 14 | Bases transverses |
| `commandline` | 1 | Base CLI |
| `copie_donnees` | 3 | Copie et serveurs de fichiers |
| `dates` | 2 | Dates |
| `fichier` | 7 | Fichiers, repertoires, logs |
| `flux` | 16 | Reseau bas niveau et transferts |
| `fork` | 5 | Forks et memoire partagee |
| `generation` | 1 | Parametres standards |
| `gestion_machines` | 7 | Machines et workspace |
| `html` | 2 | HTML/JavaScript |
| `ldap` | 14 | LDAP |
| `mail` | 3 | Mail |
| `monitoring` | 6 | Supervision |
| `sgbd` | 34 | SQL/MongoDB |
| `slurm` | 1 | Slurm |
| `strings` | 2 | Chaines |
| `utilisateurs` | 1 | Credentials utilisateurs |
| `webService` | 16 | HTTP/SOAP |
| `windows` | 1 | WMI |
| `xml` | 2 | XML/options |

## Classes pivots

| Classe | Fichier | Role |
| --- | --- | --- |
| `Zorille\framework\abstract_log` | `class_globals/Zorille_framework_abstract_log.class.php` | Base logs/erreurs/options |
| `Zorille\framework\logs` | `fichier/Zorille_framework_logs.class.php` | Gestion fichier de log et verbose |
| `Zorille\framework\options` | `xml/Zorille_framework_options.class.php` | Parsing CLI/XML/options |
| `Zorille\framework\data_model` | `class_globals/Zorille_framework_data_model.class.php` | Base modeles convertibles |
| `Zorille\framework\MainScript` | `class_globals/Zorille_framework_MainScript.class.php` | Base scripts |
| `Zorille\framework\FlagsParser` | `class_globals/Zorille_framework_FlagsParser.class.php` | Trait de parsing des flags |
| `Zorille\framework\wsclient` | `webService/Zorille_framework_wsclient.class.php` | Client HTTP generique |
| `Zorille\framework\serveur_datas` | `webService/Zorille_framework_serveur_datas.class.php` | Definitions de serveurs |
| `Zorille\framework\curl` | `flux/Zorille_framework_curl.class.php` | Wrapper curl |
| `Zorille\itop\wsclient_rest` | `APIWeb/itop/Zorille_itop_wsclient_rest.class.php` | Client REST iTop |
| `Zorille\salesforce\wsclient` | `APIWeb/salesforce/Zorille_salesforce_wsclient.class.php` | Client Salesforce |
| `Zorille\servicemanager\item` | `APIWeb/servicemanager/Zorille_servicemanager_item.class.php` | Base ressource Service Manager |
| `Zorille\otrs\item` | `APIWeb/otrs/Zorille_otrs_item.class.php` | Base ressource OTRS |
| `Zorille\evobserve\globalapi` | `APIWeb/evobserve/Zorille_evobserve_globalapi.class.php` | Base API EvObserve |

## Methodes recurrentes dans toutes les classes

- `creer_*($liste_option, ...)`: factory historique.
- `_initialise(array $liste_class)`: injection de dependances.
- `help()`: aide CLI ou documentation de classe.
- `toArray()`, `convert()`, `getFields()`: pattern de modeles.
- `valide_presence_data()` ou `valide_presence_serveur_data()`: resolution de configuration serveur.
- `valide_mandatory_fields()`: validation de champs obligatoires dans les ressources.

## Methodes reccurentes dans le wsclient
- `prepare_connexion($nom)`: preparation d'un serveur/API nomme.
- `prepare_html_entete()`: preparation des headers HTTP.
- `prepare_requete()`: execution logique d'une requete API.
- `getMethod()`, `postMethod()`, `putMethod()`, `deleteMethod()`, `patchMethod()`: wrappers REST dans plusieurs connecteurs.
- `userLogin()`: permet de connecter l'API et recupérer le token
- `valide_retour()`: verifie les codes erreurs de l'API

## Polyfills

`polyfills.php` declare des fonctions si absentes:

- `str_starts_with`;
- `str_ends_with`;
- `str_contains`;
- `json_validate`;
- `csv_decode`;
- `csv_encode`;
- `array_find`;
- `array_find_key`;
- helpers globaux `_DATE_SUB`, `INTERVAL`, `NOW`, `_DATE_FORMAT`.

## Points de vigilance

- L'autoload historique n'est pas PSR-4 pur.
- Certains fichiers semblent melanger code historique et typage PHP moderne.
- Plusieurs classes retournent par reference; eviter de modifier les signatures sans raison.
- Beaucoup de classes dependent implicitement des globaux `$liste_option` et `$fichier_log`.
- Les connecteurs peuvent appeler des services externes; utiliser `dry-run` quand le code le supporte et que l'operation est mutante.
