<?php
/**
 * @author nchoquet
 * @package Lib
 */
namespace Zorille\framework\ldap;

use Exception;
use LDAP\Connection;
use Zorille\framework\abstract_log;
use Zorille\framework\ldap\data_models\Person;
use Zorille\framework\options;

/**
 * class ldap<br>
 *
 * Gère la connection, création de donnés,
 * suppression de donnés et modification de donnés d'un serveur ldqp.
 * @package Lib
 * @subpackage LDAP
 */
class ldap extends abstract_log {
    /******************* Définition des propriétées ******************/
    private static ?ldapCredentials $credentials = null;

    private Connection|false $connection;

    /*********************** Creation de l'objet *********************/

    /**
     * Instancie un objet de type ldap.
     * @codeCoverageIgnore
     * @param options $liste_option Reference sur un objet options
     * @param ldapCredentials|null $credentials
     * @param string|Boolean $sort_en_erreur Prend les valeurs oui/non ou true/false
     * @param string $entete Entete des logs de l'objet
     * @return ldap
     * @throws Exception
     */
    public static function &creer_ldap(
        options &$liste_option,
        ?ldapCredentials $credentials = null,
        bool $sort_en_erreur = false,
        string $entete = __CLASS__
    ): self
    {
        $ldap = new ldap(($sort_en_erreur ? "oui" : "non"), $entete);
        $ldap->_initialise([
            "options" => $liste_option,
            "credentials" => $credentials
        ]);

        return $ldap;
    }

    /**
     * Initialisation de l'objet
     * @codeCoverageIgnore
     * @param array $liste_class
     * @return self
     * @throws Exception
     */
    public function &_initialise(array $liste_class): static
    {
        parent::_initialise($liste_class);
        $this->setCredentials($liste_class["credentials"]);
        return $this;
    }

    /**
     * Creer l'objet et set la valeur du sort_en_erreur
     * @codeCoverageIgnore
     * @param string $sort_en_erreur Prend les valeurs oui/non
     */
    public function __construct(
        $sort_en_erreur = "oui",
        $entete = __CLASS__
    )
    {
        //Gestion de abstract_log
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /********************* Définition des méthodes *******************/

    /**
     * @throws Exception
     */
    protected final function getLastError(): array
    {
        $connexion = $this->getConnection();

        $error = ldap_error($connexion);
        $errno = ldap_errno($connexion);
        ldap_get_option($connexion, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);

        return [$errno, $error, $extended_error];
    }

    /**
     * @throws Exception
     */
    public function connect(): self|null
    {
        $credentials = $this->getCredentials();
        $this->setConnection(ldap_connect(
            $credentials->getLdapHost(),
            $credentials->getLdapPort()
        ));
        $connection = $this->getConnection();

        $consts = [
            LDAP_OPT_PROTOCOL_VERSION => 3,
            LDAP_OPT_REFERRALS => 0,
            LDAP_OPT_NETWORK_TIMEOUT => 10,
            LDAP_OPT_X_TLS_REQUIRE_CERT => LDAP_OPT_X_TLS_NEVER,
        ];
        // TODO Investiguer pourquoi le fait de passer la connexion en premier paramètre donne
        //      une erreur de connexion ldap alors que le fait de passer null fonctionne
        foreach ($consts as $const => $val) ldap_set_option(null, $const, $val);

        $binded = @ldap_bind(
            $connection,
            $credentials->getLdapLogin(),
            $credentials->getLdapPassword()
        );

        [$errno, $error, $extended_error] = $this->getLastError();

        if (!$binded) {
            $this->getListeOptions()->onError(
                "Une erreur est survenue lors de la connexion LDAP :  {$errno} {$error} | {$extended_error}",
                entete: get_class($this)
            );
            return null;
        }

        $this->onDebug("Binded to LDAP server", 1, get_class($this));

        return $this;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function disconnect(): bool
    {
        return ldap_close($this->getConnection());
    }

    /**
     * @param data_model[] $objects
     * @return void
     * @throws Exception
     */
    public function createObjects(array $objects): void
    {
        foreach ($objects as $object) {
            $object->createIfNotExists();
        }
    }

    /**
     * @param Person[] $objects
     * @return void
     * @throws Exception
     */
    public function removeObjects(array $objects): void
    {
        foreach ($objects as $object) {
            $object->remove();
        }
    }

    /***************** Définition des getters/setters ***************/

    public function getFactory(): ldapFactory
    {
        return ldapFactory::create()
            ->setLdap($this);
    }

    /**
     * @return false|Connection
     * @throws Exception
     */
    public function getConnection(): false|Connection
    {
        if (!$this->connection) {
            throw new Exception("ldap connection failed");
        }
        return $this->connection;
    }
    protected function setConnection(false|Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @param ldapCredentials|null $credentials
     * @return void
     */
    public function setCredentials(?ldapCredentials $credentials): void
    {
        if (is_null(static::$credentials)) {
            static::$credentials = $credentials;
        }
    }
    /**
     * @param bool $inArray
     * @return array|ldapCredentials|null
     */
    public static function getCredentials(bool $inArray = false): array|ldapCredentials|null
    {
        return $inArray ? static::$credentials->getAll() : static::$credentials;
    }
}
