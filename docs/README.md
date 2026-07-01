# Documentation du depot php_framework

Cette documentation est basee uniquement sur le code present dans le depot. Elle decrit les conventions visibles, les classes structurantes, les modules et les usages recurrents observes dans les fichiers PHP.

## Sommaire

- [Architecture](architecture.md)
- [Conventions et patterns](conventions.md)
- [Modules coeur](modules.md)
- [Clients APIWeb](apiweb.md)
- [Hierarchies de classes](hierarchies.md)
- [Recettes d'utilisation](recettes.md)
- [Index technique](index-technique.md)

## Nature du depot

Le depot est un framework PHP organise autour du namespace principal `Zorille\framework` et de namespaces specialises pour certains connecteurs (`Zorille\itop`, `Zorille\salesforce`, `Zorille\VMware`, `Zorille\otrs`, `Zorille\servicemanager`, etc.).

La racine contient le fichier d'amorcage `config.php`, des polyfills PHP, une classe de fonctions standards et des repertoires fonctionnels. Le sous-dossier `APIWeb/` concentre la majorite du code avec des clients REST/SOAP et des modeles de donnees pour des outils tiers.

## Inventaire rapide

Le depot contient 767 fichiers PHP hors `.git`. Les principaux repertoires PHP sont:

| Repertoire | Role visible | Fichiers PHP |
| --- | --- | ---: |
| `APIWeb/` | Connecteurs API REST/SOAP, modeles et clients specialises | 626 |
| `class_globals/` | Classes transverses: logs, data model, flags, helpers, script principal | 14 |
| `sgbd/` | Connexions, requetes SQL/MongoDB, descriptions de bases connues | 34 |
| `webService/` | Client web generique, SOAP, donnees de serveurs, connexion URL | 16 |
| `flux/` | Curl, FTP, SSH/SFTP/SCP, sockets, pipes, Telnet | 16 |
| `ldap/` | Client LDAP, credentials, factory, modeles et fetchers | 14 |
| `fichier/` | Fichiers, repertoires, logs, definitions de fichiers | 7 |
| `gestion_machines/` | Machines, calculateurs, workspace, relations fichier/machine | 7 |
| `monitoring/` | Moniteurs, Nagios, Xymon, alertes mail, contraintes horaires | 6 |
| `fork/` | Forks, groupes de forks, memoire partagee, messages | 5 |
| autres | Dates, mail, HTML, XML, commandline, generation, strings, slurm, windows, utilisateurs | 1 a 3 chacun |

## Point d'entree technique

`config.php`:

- fixe le timezone a `Europe/Paris`;
- configure `spl_autoload_extensions('.class.php')`;
- declare `my_autoloader()`;
- ajoute les nombreux repertoires du framework dans l'`include_path`;
- transforme les requetes web en arguments `$argv` quand le script est appele via HTTP;
- cree globalement `$liste_option` via `Zorille\framework\options::creer_options(...)`;
- cree globalement `$fichier_log` via `Zorille\framework\logs::creer_logs(...)`;
- charge `polyfills.php`;
- charge explicitement des configurations VMware et Cacti.

## Avertissements documentaires

- La documentation ne garantit pas le comportement de services externes; elle decrit seulement les appels et structures visibles dans le code.
- Les exemples de recettes utilisent les factories et signatures observees. Les parametres exacts dependent des options XML/CLI chargees par `options`.
- Aucun systeme de tests automatise n'a ete trouve dans l'inventaire initial du depot.
