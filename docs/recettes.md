# Recettes d'utilisation

Ces recettes sont volontairement limitees a ce que le code montre.

## Initialiser un script utilisant le framework

```php
<?php
require_once __DIR__ . '/config.php';

// config.php cree globalement $liste_option et $fichier_log.
```

`config.php` accepte aussi un contexte web: il transforme `$_REQUEST` et certaines variables systeme en arguments de type `--nom valeur`.

## Creer une classe historique

Pattern observe:

```php
<?php
use Zorille\framework\fonctions_standards;

$fonctions = fonctions_standards::creer_fonctions_standards($liste_option);
$texte = $fonctions->retrouve_valeur_octet(1024);
```

La plupart des factories demandent `$liste_option` en premier parametre.

## Creer un script avec `MainScript`

```php
<?php
use Zorille\framework\MainScript;

final class MyScript extends MainScript
{
    protected function getAdditionalUsedOptions(): array
    {
        return [
            'server' => [
                'value' => null,
                'aliasEnv' => 'SERVER',
            ],
            'dry_run' => [
                'value' => false,
                'bool' => true,
                'optional' => true,
            ],
        ];
    }

    public static function help(): int
    {
        echo "--server <nom>\n";
        return 0;
    }

    protected function main(): bool
    {
        $this->onInfo('Traitement');
        return true;
    }
}

MyScript::batch($argv);
```

Le code de `MainScript` montre que `batch()` capture les exceptions et formate les erreurs avec `abstract_log::onError_standard()`.

## Lire une option

```php
if ($liste_option->verifie_option_existe('verbose') !== false) {
    $verbose = $liste_option->getOption('verbose');
}
```

Pour les structures standard:

```php
$server = $liste_option->renvoi_variables_standard(['wsclient']);
```

## Creer un client web generique

```php
use Zorille\framework\wsclient;

$client = wsclient::creer_wsclient($liste_option);
$client
    ->setUrl('https://example.invalid')
    ->setHttpMethod('GET')
    ->prepare_html_entete();

$resultat = $client->envoi_requete();
```

Les connecteurs specialises encapsulent en general cette sequence.

## Utiliser un modele `data_model`

```php
$model = SomeModel::convert($record);
$payload = $model->toArray();
$fields = SomeModel::getFields();
```

Les champs exportes dependent de `formatArrayKey()` dans le modele concret.

## Construire une requete iTop

Les fetchers iTop heritent de `Zorille\itop\query_builder`. Le pattern visible est:

```php
$fetcher = SomeFetcher::create($liste_option);
$fetcher
    ->select()
    ->where('name', Zorille\itop\QueryBuilderOperator::EQUAL, 'value');
```

Le query builder gere aussi `join()` et des alias de classes.

## Ajouter un nouveau connecteur APIWeb dans le style du depot

Approche coherente avec le code existant:

1. Ajouter un sous-dossier dans `APIWeb/<service>`.
2. Ajouter un `datas` heritant de `Core\serveur_datas` si le connecteur lit une definition serveur.
3. Ajouter un `wsclient` heritant de `Core\wsclient`.
4. Implementer `prepare_connexion($nom)`.
5. Ajouter des ressources heritant d'une base commune (`ci`, `item`, `globalapi`) si plusieurs endpoints partagent les memes comportements.
6. Ajouter le repertoire dans `config.php` via `set_include_path()` si le chargement automatique en depend.
7. Utiliser `onDebug`, `onInfo`, `onError` pour rester coherent avec la gestion des logs.

## Verifier la syntaxe PHP

Le depot ne contient pas de configuration de tests visible. Une verification minimale possible est:

```powershell
Get-ChildItem -Recurse -File -Include *.php |
  Where-Object { $_.FullName -notmatch '\\.git\\' } |
  ForEach-Object { php -l $_.FullName }
```

Cette commande depend de la presence de `php` dans le PATH.
