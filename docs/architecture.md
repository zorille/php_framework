# Architecture

## Vue d'ensemble

Le framework repose sur un bootstrap global (`config.php`) et sur des classes PHP chargees par include path/autoload. La plupart des classes sont dans `Zorille\framework`; les connecteurs plus recents ou plus volumineux utilisent des namespaces dedies sous `Zorille\...`.

Le flux habituel visible dans le code est:

1. Inclure `config.php`.
2. Obtenir ou utiliser le global `$liste_option`.
3. Creer un objet via une factory statique `creer_*()`.
4. Initialiser l'objet avec `_initialise(["options" => $liste_option, ...])`.
5. Utiliser les methodes metier, avec logs et erreurs passant par `abstract_log`.

## Bootstrap et chargement

`config.php` configure l'environnement:

- autoload avec extension `.class.php`;
- conversion de namespaces `Zorille\...` en noms de classes/fichiers avec underscores dans certains cas;
- conversion des backslashes en slashs pour des classes passees sous forme de chemin;
- exclusions pour certains appels contenant `iTop`;
- include paths vers tous les modules coeur et `APIWeb`.

La strategie n'est pas un autoload PSR-4 strict. Elle depend fortement des chemins ajoutes par `set_include_path()` et des noms de fichiers historiques comme `Zorille_framework_*.class.php`.

## Classes transverses

### `Zorille\framework\abstract_log`

`abstract_log` est la base de nombreuses classes. Elle gere:

- `onDebug()`, `onInfo()`, `onWarning()`, `onError()`;
- affichage standard statique `onDebug_standard()`, `onError_standard()`, etc.;
- integration avec `Zorille\framework\logs` via `abstract_log::$logs`;
- comportement d'erreur configurable: exception, sortie process, code retour;
- stockage local de l'objet `options`.

La methode `_initialise(array $liste_class)` attend une entree `"options"` et appelle `setListeOptions()`.

### `Zorille\framework\logs`

`logs` cree et gere le fichier de log via `logs::creer_logs(options &$liste_option)`. Les options reconnues dans le code incluent notamment:

- `create_log_file`;
- `dossier_log`;
- `fichier_log`;
- `fichier_log_unique`;
- `fichier_log_sort_en_erreur`;
- `fichier_log_compresse`;
- `fichier_log_append`;
- `verbose`.

`creer_logs()` assigne aussi la reference globale `abstract_log::$logs`.

### `Zorille\framework\options`

`options` herite de `xml`. Elle parse:

- les arguments CLI de forme `--option` ou `--option=valeur`;
- les fichiers de configuration declares par `--conf`;
- les dossiers de configuration `--conf_dir`;
- les regex de selection de configuration `--conf_regexp`.

`retrouve_options_param()` ajoute aussi des valeurs standards visibles dans le code: `rep_scripts`, `dossier_tempo` si `use_local_dir`, `netname`, `rep_framework`, `Erreur`.

### `Zorille\framework\data_model`

`data_model` est une base de DTO/modeles:

- `convert($record)` transforme un tableau en instance du modele courant;
- `create()` instancie `new static()`;
- `toArray()` exporte les proprietes via reflexion et getters;
- `getFields()` liste les champs exportables;
- `__call()` gere des getters/setters virtuels declares dans `$virtualProperties`;
- `formatArrayKey()` est abstraite et doit etre definie par les modeles concrets.

Les modeles iTop, Salesforce et LDAP uniquement s'appuient sur ce pattern.

### `Zorille\framework\MainScript` et `FlagsParser`

`MainScript` est une base de script principal:

- utilise le trait `FlagsParser`;
- recupere les globaux `$liste_option` et `$fichier_log`;
- parse les arguments via `options`;
- expose `create()`, `batch()`, `run()`;
- impose `help(): int` et `main(): bool`.

`FlagsParser` gere les options declarees dans `getAdditionalUsedOptions()` ou via attribut public `#[Flag]`. Il convertit certains types (`bool`, `int`, `float`) et accepte des alias d'environnement via `aliasEnv`.

## Couche WebService

### `Zorille\framework\wsclient`

`wsclient` est le client HTTP generique. Il compose:

- `gestion_connexion_url`;
- `curl`;
- URL, parametres, payload POST, methode HTTP, headers;
- content type, accept, timeout, SSL, auth, collecte de headers.

`envoi_requete()` prepare l'URL, applique les options curl, envoie la requete, collecte eventuellement les headers et renvoie la reponse brute.

### `Zorille\framework\serveur_datas`

`serveur_datas` porte des definitions de serveurs et les complete avec des identifiants via `utilisateurs`. `valide_presence_serveur_data($nom, $protocole)` cherche un serveur nomme et filtre eventuellement sur `rest`, `soap` ou `both`.

### `Zorille\framework\curl`

`curl` encapsule `curl_init`, `curl_exec`, `curl_getinfo`, options curl et transferts FTP via curl. `send_curl()` teste les erreurs curl et certains codes HTTP.

## Organisation APIWeb

Les connecteurs API suivent souvent ce modele:

- une classe `datas` qui herite de `Core\serveur_datas`;
- une classe `wsclient` qui herite de `Core\wsclient`;
- des classes metier ou ressources qui heritent d'une base commune (`ci`, `globalapi`, etc.);
- des methodes `prepare_connexion()`, `prepare_requete()`, `getMethod()`, `postMethod()`, `putMethod()`, `deleteMethod()` selon les modules;
- Uniquement pour iTop et l'OQL : des modeles de donnees dans `data_models/`;
- Uniquement pour iTop et l'OQL : des fetchers ou query builders dans `query_fetchers/` ou `OQL/`.

## Hierarchie recurrente

Exemples de chaines visibles:

- `options extends xml extends abstract_log`
- `message extends enveloppe extends abstract_log`
- `fichier extends repertoire extends abstract_log`
- `gestion_fichier extends variables_standards extends abstract_log`
- `requete extends connexion`
- `procedure_stockee extends connexion`
- `requeteMongoDB extends mongoDbAbstract`
- `parametresStandard extends CommandLine extends abstract_log`
- `slurm extends CommandLine`
- `query_builder extends wsclient_rest extends Core\wsclient`
- `itop/data_models/* extends data_model`
- `ldap/data_models/* extends ldap\data_model extends framework\data_model`

## Decisions techniques visibles

- Le framework privilegie les references (`&`) dans de nombreuses factories et accesseurs.
- Les methodes fluent retournent frequemment `static` ou `$this`.
- Les erreurs applicatives passent par `onError()` plutot que par des exceptions directes dans la majorite du code historique.
- Les mots de passe sont masques dans certains affichages de tableaux (`abstract_log::affiche_tableau()` masque la cle `password`).
- Plusieurs connecteurs supportent `dry-run` pour eviter des operations mutantes.
- Le code melange des conventions historiques (`creer_*`, noms en francais) et des elements PHP plus recents (`match`, types union, attributes, enums/classes typees).
