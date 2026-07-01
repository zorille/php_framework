# Conventions et patterns

## Nommage

Les fichiers historiques suivent majoritairement:

- `Zorille_framework_<nom>.class.php` pour `Zorille\framework`;
- `Zorille_<produit>_<nom>.class.php` pour les namespaces specialises;
- classes en minuscules pour beaucoup de composants historiques (`options`, `wsclient`, `datas`, `requete`);
- classes en PascalCase pour des modeles plus recents ou generes (`Organization`, `VirtualMachine`, `CustomizationSpec`).

Les namespaces observes incluent:

- `Zorille\framework`;
- `Zorille\itop`;
- `Zorille\salesforce`;
- `Zorille\VMware`;
- `Zorille\ldap` via `Zorille\framework\ldap`;
- `Zorille\o365`, `Zorille\dolibarr`, `Zorille\veeam*`, `Zorille\otrs`, `Zorille\servicemanager`, etc.

## Creation d'objets

Le pattern dominant est une factory statique:

```php
$objet = Zorille\framework\fichier::creer_fichier(options $liste_option, ... , bool|string $sort_en_erreur = false,string      $entete = __CLASS__);
```

La factory:

1. instancie `new classe($sort_en_erreur, $entete)`;
2. appelle `_initialise([...])`;
3. retourne l'objet, par reference.

## Initialisation

Les classes qui heritent de `abstract_log` appellent generalement:

```php
parent::_initialise(["options" => $liste_option]);
```

Certaines classes exigent des dependances supplementaires:

- `wsclient_rest` attend `"datas"`;
- plusieurs ressources API attendent `"wsclient"`;
- certains objets metier attendent une connexion ou un client specialise.

## Logging et erreurs

Utiliser les methodes de `abstract_log` quand la classe en herite:

- `onDebug($message, $niveau)`;
- `onInfo($message)`;
- `onWarning($message)`;
- `onError($message, $donnee_sup = "", $code_retour = 1)`.

`onError()` peut lever une `Exception`, retourner `false`, ou terminer le process selon `throw_exception` et `sort_en_erreur`.

## Options et configuration

Le depot utilise massivement `Zorille\framework\options`.

Sources visibles:

- arguments CLI;
- valeurs `$_REQUEST` transformees en arguments quand `config.php` detecte un contexte web;
- fichiers XML de configuration via `--conf`;
- dossiers de configuration via `--conf_dir`;
- options standards dans `$liste_option`.

Les modules lisent ensuite les valeurs par:

- `verifie_option_existe()`;
- `getOption()`;
- `renvoi_variables_standard()`;
- `verifie_variable_standard()`.

## Requetes HTTP

Le pattern courant dans les connecteurs REST est:

1. `prepare_connexion($nom)` lit la definition du serveur et configure URL/auth.
2. Une methode metier configure HTTP method, URL, params ou body.
3. `prepare_html_entete()` pose les headers.
4. `envoi_requete()` appelle curl.
5. `prepare_retour()` ou `traite_retour_json()` decode la reponse.
6. `valide_retour()` declenche `onError()` si une erreur API est visible.

## Bonnes pratiques deduites du code

- Inclure `config.php` avant d'utiliser les classes du framework.
- Passer l'objet `$liste_option` aux factories.
- Respecter `_initialise()` et ne pas contourner les dependances attendues.
- Ne pas appeler directement `curl_*` depuis un connecteur si `wsclient` fournit deja le comportement.
- Utiliser `onError()` pour rester coherent avec la gestion de logs et codes retour.
- Ne pas ajouter un autoload PSR-4 parallele sans verifier l'impact sur `my_autoloader()` et les include paths.
- Ne pas renommer les fichiers/classes sans verifier les dependances de l'autoload historique.
