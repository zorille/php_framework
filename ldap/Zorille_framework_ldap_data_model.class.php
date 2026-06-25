<?php

namespace Zorille\framework\ldap;

use Error;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use LDAP\Connection;
use ReflectionClass;
use ReflectionProperty;

abstract class data_model extends \Zorille\framework\data_model
{
    private Connection|null $connection = null;
    private ?ldap $ldap = null;
    protected string $dn = '';
    private bool $exists = false;
    protected static string $baseOu = '';

    protected static function formatArrayKey($property): string
    {
        if (is_string($property)) return $property;
        return $property->getName();
    }
    #[ArrayShape([
        'template' => 'array',
        'dn' => 'string',
        'errorLogHeader' => 'callable',
        'successLog' => 'callable',
        'alreadyExistsLog' => 'callable',
    ])]
    public abstract function getCreationData(string $dn = '', bool $withTemplate = true): array;

    /**
     * @throws Exception
     */
    public function createOne(string $dn = ''): self|null
    {
        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return null;
        }

        [
            'dn' => $dn,
            'template' => $data,
            'errorLogHeader' => $errorLogHeader,
            'successLog' => $successLog,
        ] = $this->getCreationData($dn);

        $this->getListOptions()->onDebug("DN: {$dn}", 1, get_class($this));
        $this->getListOptions()->onDebug("Data: " . print_r($data, true), 1, get_class($this));

        // Création de l'utilisateur
        if (!@ldap_add($connexion, $dn, $data)) {
            $error = ldap_error($connexion);
            ldap_get_option($connexion, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);
            $this->getListOptions()->onError(
                "{$errorLogHeader()} {$error} | {$extended_error}",
                entete: get_class($this)
            );
            return null;
        }

        $this->getListOptions()->onInfo($successLog($this->findAndComplete()), get_class($this));
        return $this;
    }

    /**
     * @throws Exception
     */
    private function firstTryForCreation(string $dn, callable $secondTry)
    {
        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return null;
        }

        [
            'dn' => $dn,
            'template' => $data,
            'errorLogHeader' => $errorLogHeader,
            'successLog' => $successLog,
            'alreadyExistsLog' => $alreadyExistsLog,
        ] = $this->getCreationData($dn);

        $this->getListOptions()->onDebug(
            sprintf("create %s : %s", static::ENTITY_NAME, print_r($data, true)),
            2,
            get_class($this)
        );
        $this->getListOptions()->onDebug(
            sprintf("used DN : %s", $dn),
            2,
            get_class($this)
        );

        // Création de l'objet
        if (!@ldap_add($connexion, $dn, $data)) {
            $error = ldap_error($connexion);
            $errno = ldap_errno($connexion);
            ldap_get_option($connexion, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);

            $isUserAlreadyExistsInAnotherOU =
                str_contains($extended_error, '(CONSTRAINT_ATT_TYPE)') ||
                str_contains($extended_error, '(userPrincipalName)');
            $isUserAlreadyExistsInSameOU = str_contains($extended_error, '(ENTRY_EXISTS)');

            if (!$isUserAlreadyExistsInAnotherOU && !$isUserAlreadyExistsInSameOU) {
                $this->getListOptions()->onError(
                    "{$errorLogHeader()} {$data['name']} : {$errno} {$error} | {$extended_error}",
                    entete: get_class($this)
                );
                return null;
            }

            if (str_contains($extended_error, '(WILL_NOT_PERFORM)')) {
                return $secondTry([
                    'dn' => $dn,
                    'template' => $data,
                    'errorLogHeader' => $errorLogHeader,
                    'successLog' => $successLog,
                    'alreadyExistsLog' => $alreadyExistsLog
                ]);
            }

            $this->getListOptions()->onInfo($alreadyExistsLog($data), get_class($this));
            return $this->findAndComplete();
        }

        $this->getListOptions()->onInfo($successLog($this->findAndComplete()), get_class($this));
        return $this;
    }

    /**
     * @throws Exception
     */
    public function createIfNotExists(string $dn = ''): self|null
    {
        $this->findAndComplete();

        ['alreadyExistsLog' => $alreadyExistsLog] = $this->getCreationData($dn, false);

        if (!$this->isAlreadyExists()) {
            return $this->firstTryForCreation($dn, function (array $creationData) {
                [
                    'dn' => $dn,
                    'errorLogHeader' => $errorLogHeader,
                    'template' => $data,
                    'successLog' => $successLog
                ] = $creationData;

                $connexion = $this->connection;

                if (isset($data['unicodePwd'])) {
                    $unicodePwd = $data['unicodePwd'];
                    unset($data['unicodePwd']);
                    $data['userAccountControl'] = 514;

                    $this->getListOptions()->onDebug(
                        sprintf("create %s : %s", static::ENTITY_NAME, print_r($data, true)),
                        2,
                        get_class($this)
                    );
                    $this->getListOptions()->onDebug(
                        sprintf("used DN : %s", $dn),
                        2,
                        get_class($this)
                    );

                    if (!@ldap_add($this->connection, $dn, $data)) {
                        [$errno, $error, $extended_error] = $this->getLastError();

                        $isUserAlreadyExistsInAnotherOU =
                            str_contains($extended_error, '(CONSTRAINT_ATT_TYPE)') ||
                            str_contains($extended_error, '(userPrincipalName)');
                        $isUserAlreadyExistsInSameOU = str_contains($extended_error, '(ENTRY_EXISTS)');

                        if (!$isUserAlreadyExistsInAnotherOU && !$isUserAlreadyExistsInSameOU) {
                            $this->getListOptions()->onError(
                                "{$errorLogHeader()} {$data['name']} : {$errno} {$error} | {$extended_error}",
                                entete: get_class($this)
                            );
                            return null;
                        }
                    }

                    $password_info = ["unicodePwd" => $unicodePwd];
                    if (!@ldap_modify($connexion, $dn, $password_info)) {
                        [$errno, $error, $extended_error] = $this->getLastError();

                        $this->getListOptions()->onError(
                            "{$errorLogHeader()} Erreur lors de la création du mot de passe de l'utilisateur {$data['name']} : {$errno} {$error} | {$extended_error}",
                            entete: get_class($this)
                        );
                        return null;
                    }

                    $activation_info = ["userAccountControl" => 512]; // 512 pour activer le compte
                    if (!@ldap_modify($connexion, $dn, $activation_info)) {
                        $error = ldap_error($connexion);
                        $errno = ldap_errno($connexion);
                        ldap_get_option($connexion, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);

                        $this->getListOptions()->onError(
                            "{$errorLogHeader()} Erreur lors de l'activation de l'utilisateur {$data['name']} : {$errno} {$error} | {$extended_error}",
                            entete: get_class($this)
                        );
                        return null;
                    }

                    $this->getListOptions()->onInfo($successLog($this->findAndComplete()), get_class($this));
                    return $this;
                }

                return $this->findAndComplete();
            });
        }
        else {
            $this->getListOptions()->onDebug("DN: {$dn}", 1, get_class($this));
            $this->getListOptions()->onDebug(print_r($this, true), 1, get_class($this));

            $this->getListOptions()->onInfo($alreadyExistsLog($this->toArray()), get_class($this));
        }
        return $this;
    }

    /**
     * @param array $record
     * @return self
     */
    public static function convert($record): self
    {
        return static::convertArrToSelf($record);
    }
    protected static function divide(string $origin, string $prefix): array
    {
        preg_match_all("/{$prefix}=([^,]*)/m", $origin, $matches, PREG_SET_ORDER);
        return array_map(fn(array $p) => $p[1], $matches);
    }
    protected static function convertArrToSelf(array $record, ?self $obj = null): self
    {
        if (is_null($obj)) {
            $obj = static::create();
        }

        $ref = new ReflectionClass(static::class);
        $properties = array_filter(
            $ref->getProperties(ReflectionProperty::IS_PROTECTED),
            fn($p) => $p->getDeclaringClass()->getName() === static::class
        );

        foreach ($properties as $property) {
            if (!empty($record[$property->getName()])) {
                $value = $record[$property->getName()];

                if (is_array($value)) {
                    if ($value['count'] !== null) {
                        if ($value['count'] === 1) {
                            $value = $value[0];
                        } elseif ($value['count'] > 1) {
                            unset($value['count']);
                        }
                    }

                    $obj->{$property->getName()} = $property->getType()->getName() === 'array'
                        ? (is_array($value) ? $value : [$value])
                        : $value;
                }
            } elseif (in_array('reset' . ucfirst($property->getName()), get_class_methods(static::class))) {
                $obj->{'reset' . ucfirst($property->getName())}();
            }
        }

        if ($ref->hasProperty('dn') && isset($record['dn'])) {
            $obj->dn = $record['dn'];
        }

        return $obj;
    }
    /**
     * @throws Exception
     */
    public static function fromDN(string $dn, ldap $ldap): self|null
    {
        global $liste_option;

        $connection = $ldap->getConnection();
        if (!$connection) {
            $liste_option->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: static::class
            );
            return null;
        }

        $entityName = static::ENTITY_NAME;

        $confRootDn = ldap::getCredentials()->getLdapRoot();
        preg_match(
            "/^(CN=(?<CN>[^,]*),)?(?<OUs>OU=.*,)?(?<DCs>({$confRootDn}))$/m",
            $dn, $matches
        );

        if (empty($matches)) {
            throw new Exception("invalid DN format: {$dn}");
        }

        [
            'dn' => $dn,
            'filter' => $filter,
        ] = static::searchDataForFromDn($matches);

        $liste_option->onDebug("DN: {$dn}", 1, static::class);
        $liste_option->onDebug("Filter: {$filter}", 1, static::class);

        $ldapSearcher = ldap_search($connection, $dn, $filter);

        if (is_bool($connection) || is_bool($ldapSearcher)) {
            throw new Error("Unable to get ldap entries");
        }

        $resultList = ldap_get_entries($connection, $ldapSearcher);

        $iNumberUser = count($resultList) - 1;
        $liste_option->onDebug(
            "($entityName) Number of entries found on LDAP: {$iNumberUser}",
            1,
            static::class
        );

        if ($iNumberUser >= 1) {
//            var_dump($resultList[0]);
            return static::convert($resultList[0])->setLdap($ldap);
        }

        return null;
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected abstract function findAndCompleteSearchData(): array;

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected abstract function findAllSearchData(): array;

    public function toArray(): array
    {
        $arr = parent::toArray();

        return array_reduce(
            array_filter(
                array_keys($arr),
                fn($k) => !empty($arr[$k])
            ),
            fn($r, $c) => array_merge($r, [$c => $arr[$c]]),
            []
        );
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected abstract static function searchDataForFromDn(array $dnParts): array;

    public abstract function getNonOptionalInputDataForSearch(): array;

    /**
     * @throws Exception
     */
    public function findAndComplete(int $maxTry = 3): self|null
    {
        $connection = $this->getConnection();
        if (is_null($connection)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return null;
        }

        $entityName = static::ENTITY_NAME;

        [
            'dn' => $dn,
            'filter' => $filter
        ] = $this->findAndCompleteSearchData();

        $this->getListOptions()->onDebug("DN: {$dn}", 1, get_class($this));
        $this->getListOptions()->onDebug("Filter: {$filter}", 1, get_class($this));

        $ldapSearcher = @ldap_search($connection, $dn, $filter);

        if (!$connection || is_bool($ldapSearcher)) {
            [$errno, $error, $extended_error] = $this->getLastError();
            if (str_starts_with($extended_error, 'Referral:') && $maxTry > 0) {
                $this->getLdap()->connect();
                return $this->findAndComplete($maxTry - 1);
            }
            $this->getListOptions()->onError(
                "Unable to get ldap entries : {$errno} {$error} | {$extended_error}",
                entete: get_class($this)
            );
            return null;
        }

        $resultList = ldap_get_entries($connection, $ldapSearcher);

        $iNumberUser = count($resultList) - 1;
        $this->getListOptions()->onDebug(
            "($entityName) Number of entries found on LDAP: {$iNumberUser}",
            1,
            get_class($this)
        );

        if ($resultList['count'] === 0) {
            $this->exists = false;
            return $this;
        }

        $this->exists = true;
        return static::convertArrToSelf($resultList[0], $this);
    }

    /**
     * @return self[]
     * @throws Exception
     */
    public function findAll(string $dn = '', string $filter = ''): array
    {
        $connection = $this->getConnection();
        if (is_null($connection)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return [];
        }

        $entityName = static::ENTITY_NAME;

        if (!empty($dn) || !empty($filter)) {
            $data = $this->findAllSearchData();

            if (empty($filter)) $filter = $data['filter'];
            if (empty($dn)) $dn = $data['dn'];
        }

        $this->getListOptions()->onDebug("DN: {$dn}", 1, get_class($this));
        $this->getListOptions()->onDebug("Filter: {$filter}", 1, get_class($this));

        $cookie = '';
        $nb_results = 0;
        $results = [];

        do {
            // Créer le contrôle paginé
            $paged_control = [
                'oid' => LDAP_CONTROL_PAGEDRESULTS,
                'value' => ['size' => 100, 'cookie' => $cookie]
            ];

            // Effectuer la recherche LDAP avec le contrôle paginé
            $search = ldap_search($connection, $dn, $filter, [], 0, 0, 0, LDAP_DEREF_NEVER, [$paged_control]);

            if (!$connection || is_bool($search)) {
	            [, $error, $extended_error] = $this->getLastError();
                throw new Error("Unable to get ldap entries: {$error} {$extended_error}");
            }

            if ($search) {
                // Récupérer les résultats de la page
                $entries = ldap_get_entries($connection, $search);

                $nb_results += ldap_count_entries($connection, $search);

                $results = [...$results, ...$entries];

                // Extraire le contrôle de la réponse pour obtenir le cookie de pagination
                ldap_parse_result(
                    $connection,
                    $search,
                    $errcode,
                    $matcheddn,
                    $errmsg, $referrals,
                    $controls
                );

                // Mettre à jour le cookie pour la page suivante
                $cookie = $controls[LDAP_CONTROL_PAGEDRESULTS]['value']['cookie'] ?? '';
            }
        } while (!empty($cookie)); // Continuer tant que le cookie n'est pas vide

        unset($results['count']);

        $this->getListOptions()->onDebug(
            "($entityName) Number of entries found on LDAP: {$nb_results}",
            1,
            get_class($this)
        );

        return array_map(
            fn(array $item) => static::convertArrToSelf($item),
            $results
        );
    }

    protected final function getConnection(): ?Connection
    {
        return $this->connection;
    }
    protected final function setConnection(?Connection $connection): self
    {
        $this->connection = $connection;
        return $this;
    }

    public final function getLdap(): ?ldap
    {
        return $this->ldap;
    }
    /**
     * @throws Exception
     */
    public final function setLdap(?ldap $ldap): self
    {
        $this->ldap = $ldap;
        if (!empty($ldap)) {
            $this->setConnection($ldap->getConnection());
            $this->setListOptions($ldap->getListeOptions());
            static::$baseOu = $this->ldap->getCredentials()->getLdapBaseOu();
        }
        return $this;
    }

    public final function getDn(): string
    {
        return $this->dn;
    }
    public final function setDn(string $dn): self
    {
        $this->dn = $dn;
        return $this;
    }

    /**
     * @throws Exception
     */
    public final static function getBaseOu(): string
    {
        return static::$baseOu === '' ? ldapCredentials::creer_ldap_credentials()
            ->charger_depuis_config()->getLdapBaseOu() : static::$baseOu;
    }

    public final function isAlreadyExists(): bool
    {
        return $this->exists;
    }

    protected final function getLastError(): array
    {
        $connexion = $this->getConnection();

        $error = ldap_error($connexion);
        $errno = ldap_errno($connexion);
        ldap_get_option($connexion, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);

        return [$errno, $error, $extended_error];
    }
}