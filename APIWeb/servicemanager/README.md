# Classes PHP EasyVista Service Manager

Swagger: EasyVista Swagger Service Manager Rest API 1.9.3

## Fichiers generes

- `Zorille_servicemanager_Actions.class.php`
- `Zorille_servicemanager_AssetCharacteristic.class.php`
- `Zorille_servicemanager_Assets.class.php`
- `Zorille_servicemanager_CatalogAssets.class.php`
- `Zorille_servicemanager_ConfigurationItemsCI.class.php`
- `Zorille_servicemanager_Departments.class.php`
- `Zorille_servicemanager_Domain.class.php`
- `Zorille_servicemanager_EYourTable.class.php`
- `Zorille_servicemanager_Employees.class.php`
- `Zorille_servicemanager_Groups.class.php`
- `Zorille_servicemanager_KnownErrors.class.php`
- `Zorille_servicemanager_Locations.class.php`
- `Zorille_servicemanager_Manufacturer.class.php`
- `Zorille_servicemanager_News.class.php`
- `Zorille_servicemanager_Others.class.php`
- `Zorille_servicemanager_Problems.class.php`
- `Zorille_servicemanager_Questionnaire.class.php`
- `Zorille_servicemanager_RequestsAndIncidents.class.php`
- `Zorille_servicemanager_Slas.class.php`
- `Zorille_servicemanager_Suppliers.class.php`
- `Zorille_servicemanager_Token.class.php`
- `Zorille_servicemanager_Urgency.class.php`
- `Zorille_servicemanager_globalapi.class.php`
- `Zorille_servicemanager_item.class.php`

## Utilisation type

```php
use Zorille\servicemanager\Assets;

$assets = Assets::creer_Assets($options, $wsclient, false, 'Assets', '40000');
$assets->getAssets(array('max_rows' => 10));
$asset = Assets::creer_Assets($options, $wsclient);
$asset->getAssetsAssetId(array('asset_id' => 12345));
```

Les variables de chemin swagger, par exemple `{asset_id}`, doivent etre passees dans le tableau de parametres. Pour les variables nommees `id` ou finissant par `_id`, l'objet peut aussi utiliser `setId()` si la variable n'est pas fournie explicitement.
