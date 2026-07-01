# Modules coeur

## `class_globals/`

Contient les briques transverses:

- `abstract_log`: base de logging et erreurs.
- `data_model`: base de modeles convertibles.
- `MainScript`: base de script principal avec cycle `batch/create/run/main`.
- `FlagsParser` et `Flag`: declaration et parsing de flags.
- `SingletonFactory`: stockage simple de singletons par nom.
- `helpers`, `variables_standards`: helpers et substitutions de variables.
- classes de dates fonctionnelles: `DATE_FORMAT`, `DATE_SUB`, `INTERVAL`, `NOW`.
- `CsvFormatterFromTemplate`, `FactoriesEnum`.

## `xml/`

- `xml`: base XML heritant de `abstract_log`.
- `options`: gestion des arguments, fichiers de configuration et options standards.

## `fichier/`

Module fichiers et logs:

- `repertoire`: operations de repertoire.
- `fichier`: herite de `repertoire`.
- `fichier_gz`: gestion de fichiers compresses.
- `logs`: creation et ecriture de logs.
- `definition_fichier`: herite de `variables_standards`.
- `gestion_fichier`: herite de `variables_standards`.
- `fonctions_standards_fichier`: fonctions utilitaires fichier.

## `webService/`

Briques reseau applicatives:

- `wsclient`: client HTTP generique base sur `curl`.
- `gestion_connexion_url`: construit les parametres de connexion et URLs.
- `serveur_datas`: lit et valide des definitions de serveurs.
- `soap`: client SOAP.
- classes `SOAP*`: objets de payload/resultat SOAP.
- `fonctions_standard_webservices`: fonctions utilitaires.

## `flux/`

Transport et communication:

- `curl`: wrapper curl.
- `ftp`: client FTP.
- `ssh_z`, `sftp_z`, `scp_z`, `ssh2_commandes`, `groupe_ssh_z`: SSH et transferts.
- `socket`, `socket_serveur`: sockets.
- `pipe`, `serveur_pipe`: pipes.
- `Telnet`: client Telnet.
- `flux_datas`: donnees de flux.
- `serveur_ssh.inc.php`, `socks5.lib.php`.

## `sgbd/`

Deux sous-ensembles:

- `rep_requete/`: connexions et requetes (`connexion`, `requete`, `sql`, `xql`, `oql`, `PDO_local`, `mongoDbAbstract`, `requeteMongoDB`, `procedure_stockee`, comparaison de resultats).
- `db_connue/`: descriptions et requetes complexes pour bases nommees (`cacti`, `fmanager`, `gestion_*`, `itop`, `power`, `sitescope`, `tools`) et MongoDB.

## `ldap/`

Le module LDAP contient:

- `ldap`, `ldapCredentials`, `ldapDatas`, `ldapFactory`;
- `ldapPasswordGenerator`;
- base `ldap\data_model`;
- base `ldap\query_fetcher`;
- modeles `Group`, `OrganizationalUnit`, `Person`;
- fetchers associes.

## `mail/`

- `enveloppe`: destinataires, sujet, charset, pieces jointes.
- `message`: herite de `enveloppe`, prepare headers MIME, corps texte/html, envoi mail et envoi O365.
- `fonctions_standards_mail`: helpers pour construire et envoyer un message depuis les options.

## `commandline/` et `generation/`

- `CommandLine`: base de gestion ligne de commande heritant de `abstract_log`.
- `parametresStandard`: herite de `CommandLine`; ajoute des options standards comme date, serial, parametres DB, step, fichiers merges.

## `dates/`

- `dates`: manipulation de dates.
- `fonctions_standards_dates`: helpers dates.

## `fork/`

- `fork`: encapsulation de forks.
- `groupe_forks`: gestion de groupes de forks.
- `shared_memory`, `mem_message`: memoire partagee et messages.
- `fonctions_standards_fork`: helpers.

## `monitoring/`

- `moniteur`: base monitoring.
- `nagios_client`, `xymon_client`: clients de supervision.
- `mail_alert`: alertes mail.
- `contraintesHoraire`: contraintes horaires.
- `fonctions_standards_moniteur`: helpers.

## `gestion_machines/`

- `machine`, `machines`;
- `calculateur`, `calculateurs`;
- `gestion_workspace`;
- `relation_fichier_machine`;
- helpers de gestion machines.

## `html/`, `strings/`, `slurm/`, `windows/`, `utilisateurs/`, `copie_donnees/`

- `html` et `javascript`: generation HTML/JS.
- `base64` et `fonctions_standard_strings`: traitements de chaines.
- `slurm`: classe CLI pour appels Slurm.
- `WMI`: classe Windows sous namespace `Zorille\WMI`.
- `utilisateurs`: resolution username/password dans les definitions.
- `copie_donnees`, `serveur_fichier`, helpers de copie de donnees.
