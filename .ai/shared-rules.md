# Regles communes aux assistants IA

## Contexte

Depot PHP historique avec bootstrap global `config.php`, namespace principal `Zorille\framework`, nombreux connecteurs sous `APIWeb/`, factories `creer_*()`, initialisation `_initialise()`, logs via `abstract_log`.

## A faire

- Lire `docs/README.md`, `docs/architecture.md` et le fichier PHP concerne avant modification.
- Inclure `config.php` mentalement comme point d'entree du framework.
- Respecter l'autoload existant base sur `.class.php`, include paths et noms historiques.
- Utiliser les factories `creer_*()` quand elles existent.
- Passer `$liste_option` aux objets qui l'attendent.
- Appeler `parent::_initialise($liste_class)` dans les classes qui heritent d'une base du framework.
- Utiliser `onDebug()`, `onInfo()`, `onWarning()`, `onError()` dans les classes heritant de `abstract_log`.
- Conserver les retours fluent `static`/`$this` quand le fichier les utilise.
- Masquer ou eviter d'exposer mots de passe, tokens et secrets dans les logs.
- Pour les connecteurs API, chercher d'abord le `datas`, le `wsclient` et la classe base (`ci`, `item`, `globalapi`, etc.).
- Verifier la syntaxe PHP des fichiers modifies avec `php -l` quand PHP est disponible.

## A eviter

- Ne pas introduire PSR-4/composer sans demande explicite.
- Ne pas renommer fichiers/classes historiques sans analyser `config.php` et les include paths.
- Ne pas remplacer `onError()` par des exceptions directes dans du code existant sans motif local.
- Ne pas contourner `wsclient`/`curl` pour des appels HTTP dans un connecteur existant.
- Ne pas supprimer les `&` de factories/accesseurs sans verifier les effets.
- Ne pas ajouter de dependance externe sans demande explicite.
- Ne pas documenter des comportements non visibles dans le code.

## Verification minimale

```powershell
php -l chemin\du\fichier.php
```

Pour plusieurs fichiers:

```powershell
Get-ChildItem -Recurse -File -Include *.php |
  Where-Object { $_.FullName -notmatch '\\.git\\' } |
  ForEach-Object { php -l $_.FullName }
```
