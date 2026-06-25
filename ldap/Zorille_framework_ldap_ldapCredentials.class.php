<?php
/**
 * @author nchoquet
 * @package Lib
 *
 */
namespace Zorille\framework\ldap;
use Exception;
use ReflectionObject;
use ReflectionProperty;
use Zorille\framework\Flag;
use Zorille\framework\FlagsParser;
use Zorille\framework\options;

/**
 * class ldapCredentials<br>
 *
 * Stock les informations de connexion au ldap.
 * @package Lib
 * @subpackage LDAP
 *
 * @method string getLdapServeurOption()
 */
class ldapCredentials {
    use FlagsParser;

    protected ?options $list_options = null;

    private static ?self $instance = null;

    #[Flag]
    public string $ldap_serveur = '';

    /**
     * @throws Exception
     */
    protected function __construct(
        private string $ldap_host,
        private int    $ldap_port,
        private string $ldap_root,
        private string $ldap_filter,
        private string  $ldap_base_ou,
        private string $ldap_login,
        private string $ldap_password,
        private array  $ldap_search_filters
    ) {
        global $liste_option;
        $this->list_options = $liste_option;

        $this->setOptionsIfExists();
    }

    /*protected function getAdditionalUsedOptions(): array
    {
        return [
            'ldap_serveur' => [
                'value' => ''
            ]
        ];
    }*/

    /**
     * @throws Exception
     */
    public static function &creer_ldap_credentials(
        string $ldap_host = 'localhost',
        int $ldap_port = 389,
        string $ldap_root = 'DC=company,DC=com',
        string $ldap_filter = '(&(objectClass=user)(objectCategory=person))',
        string $ldap_base_ou = 'Customers',
        string $ldap_login = 'CN=ITOP-LDAP,DC=company,DC=com',
        string $ldap_password = 'password',
        array $ldap_search_filters = []
    ): self {
        if (is_null(self::$instance)) {
            static::$instance = new static(
                $ldap_host,
                $ldap_port,
                $ldap_root,
                $ldap_filter,
                $ldap_base_ou,
                $ldap_login,
                $ldap_password,
                $ldap_search_filters
            );
        }

        return static::$instance;
    }

    /**
     * @throws Exception
     */
    public function charger_depuis_config(): self {
        global $liste_option;
        $data = ldapDatas::creer_ldapDatas($liste_option);
        $retrievedData = $data->valide_presence_data($this->getLdapServeurOption());
        [
            'host' => $ldap_host,
            'port' => $ldap_port,
            'root' => $ldap_root,
            'filter' => $ldap_filter,
            'base_ou' => $ldap_base_ou,
            'username' => $ldap_login,
            'password' => $ldap_password,
            'searchFilters' => $ldap_search_filters,
        ] = $retrievedData;

        foreach ($retrievedData as $key => $value) {
            if ($key === 'nom' || $key === 'utilisateur') continue;
            if (is_array($value)) {
                $value = print_r($value, true);
            }
            if (strtolower($key) === 'password') {
                $this->list_options->onDebug(
                    ucfirst($key) . ": " . str_repeat('*', strlen($value)),
                    1,
                    get_class($this)
                );
            }
            else {
                $this->list_options->onDebug(ucfirst($key) . ": {$value}", 1, get_class($this));
            }
        }

        $this->setLdapHost($ldap_host);
        $this->setLdapPort($ldap_port);
        $this->setLdapRoot($ldap_root);
        $this->setLdapFilter($ldap_filter);
        $this->setLdapBaseOu($ldap_base_ou);
        $this->setLdapLogin($ldap_login);
        $this->setLdapPassword($ldap_password);
        $this->setLdapSearchFilters($ldap_search_filters);

        return $this;
    }

    public function getAll(): array {
        $excludeProperties = ["classUtilsUsed"];
        $return = [];

        $ref = new ReflectionObject($this);
        foreach ($ref->getProperties(ReflectionProperty::IS_PRIVATE) as $property) {
            $propName = $property->getName();

            if (!in_array($propName, $excludeProperties)) {
                $return[$propName] = $this->{$propName};
            }
        }

        return $return;
    }

    public function getLdapHost(): string {
        return $this->ldap_host;
    }
    public function setLdapHost(string $ldap_host): void {
        $this->ldap_host = $ldap_host;
    }

    public function getLdapPort(): int {
        return $this->ldap_port;
    }
    private function setLdapPort(int $ldap_port): void {
        $this->ldap_port = $ldap_port;
    }

    public function getLdapRoot(): string {
        return $this->ldap_root;
    }
    private function setLdapRoot(string $ldap_root): void {
        $this->ldap_root = $ldap_root;
    }

    public function getLdapFilter(): string {
        return $this->ldap_filter;
    }
    private function setLdapFilter(string $ldap_filter): void {
        $this->ldap_filter = $ldap_filter;
    }

    public function getLdapBaseOu(): string {
        return $this->ldap_base_ou;
    }
    private function setLdapBaseOu(string $ldap_base_ou): void {
        $this->ldap_base_ou = $ldap_base_ou;
    }

    public function getLdapLogin(): string {
        return $this->ldap_login;
    }
    private function setLdapLogin(string $ldap_login): void {
        $this->ldap_login = $ldap_login;
    }

    public function getLdapPassword(): string {
        return $this->ldap_password;
    }
    private function setLdapPassword(string $ldap_password): void {
        $this->ldap_password = $ldap_password;
    }

    public function getLdapSearchFilters(): array
    {
        return $this->ldap_search_filters;
    }
    public function setLdapSearchFilters(array $ldap_search_filters): ldapCredentials
    {
        $this->ldap_search_filters = $ldap_search_filters;
        return $this;
    }
}