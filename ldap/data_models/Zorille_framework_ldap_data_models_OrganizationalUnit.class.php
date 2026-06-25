<?php

namespace Zorille\framework\ldap\data_models;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework\ldap\data_model;
use Zorille\framework\ldap\ldap;

/**
 * @method static self create()
 * @method static self convert(array $record)
 * @method null|self findAndComplete()
 * @method null|self createOne(string $dn = '')
 * @method null|self createIfNotExists(string $dn = '')
 * @method self setDn(string $dn)
 */
class OrganizationalUnit extends data_model
{
    const ENTITY_NAME = "OrganizationalUnits";

    protected array $objectclass = [];
    protected string $ou = '';
    protected string $distinguishedname = '';
    protected string $instancetype = '';
    protected string $whencreated = '';
    protected string $whenchanged = '';
    protected string $usncreated = '';
    protected string $usnchanged = '';
    protected string $name = '';
    protected string $objectguid = '';
    protected string $objectcategory = '';
    protected array $dscorepropagationdata = [];

    public function getObjectclass(): array
    {
        return $this->objectclass;
    }
    public function setObjectclass(array $objectclass): self
    {
        $this->objectclass = $objectclass;
        return $this;
    }

    public function getOu(): string
    {
        return $this->ou;
    }
    public function setOu(string $ou): self
    {
        $this->ou = $ou;
        return $this;
    }

    public function getDistinguishedname(): string
    {
        return $this->distinguishedname;
    }
    public function setDistinguishedname(string $distinguishedname): self
    {
        $this->distinguishedname = $distinguishedname;
        return $this;
    }

    public function getInstancetype(): string
    {
        return $this->instancetype;
    }
    public function setInstancetype(string $instancetype): self
    {
        $this->instancetype = $instancetype;
        return $this;
    }

    public function getWhencreated(): string
    {
        return $this->whencreated;
    }
    public function setWhencreated(string $whencreated): self
    {
        $this->whencreated = $whencreated;
        return $this;
    }

    public function getWhenchanged(): string
    {
        return $this->whenchanged;
    }
    public function setWhenchanged(string $whenchanged): self
    {
        $this->whenchanged = $whenchanged;
        return $this;
    }

    public function getUsncreated(): string
    {
        return $this->usncreated;
    }
    public function setUsncreated(string $usncreated): self
    {
        $this->usncreated = $usncreated;
        return $this;
    }

    public function getUsnchanged(): string
    {
        return $this->usnchanged;
    }
    public function setUsnchanged(string $usnchanged): self
    {
        $this->usnchanged = $usnchanged;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getObjectguid(): string
    {
        return $this->objectguid;
    }
    public function setObjectguid(string $objectguid): self
    {
        $this->objectguid = $objectguid;
        return $this;
    }

    public function getObjectcategory(): string
    {
        return $this->objectcategory;
    }
    public function setObjectcategory(string $objectcategory): self
    {
        $this->objectcategory = $objectcategory;
        return $this;
    }

    public function getDscorepropagationdata(): array
    {
        return $this->dscorepropagationdata;
    }
    public function setDscorepropagationdata(array $dscorepropagationdata): self
    {
        $this->dscorepropagationdata = $dscorepropagationdata;
        return $this;
    }

    #[ArrayShape([
        'template' => 'array',
        'dn' => 'string',
        'errorLogHeader' => 'callable',
        'successLog' => 'callable',
        'alreadyExistsLog' => 'callable',
    ])]
    public function getCreationData(string $dn = '', bool $withTemplate = true): array
    {
        return [
            'template' => [
                'name' => strtolower($this->getName()),
                'objectclass' => [
                    'top',
                    'organizationalUnit',
                ],
            ],
            'dn' => empty($dn) ? "OU=" . strtolower($this->getName()) . ",OU=" . static::getBaseOu() . ",{$this->getLdap()->getCredentials()->getLdapRoot()}" : $dn,
            'errorLogHeader' => fn() => "Erreur lors de l'ajout de l'OU :",
            'successLog' => fn(self $ou) => "L'OU {$ou->getName()} à été ajouté avec succès",
            'alreadyExistsLog' => fn(array $data) => "L'OU " . ($data['name'] ?? '') . " existe déjà et n'a donc pas été créé"
        ];
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected function findAndCompleteSearchData(): array
    {
        $credentials = $this->getLdap()->getCredentials();

        return [
            'dn' => "OU=" . static::getBaseOu() . "," . $credentials->getLdapRoot(),
            'filter' => "(&{$credentials->getLdapSearchFilters()['OrganizationalUnit']}(OU=" . strtolower($this->getName()) . "))"
        ];
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected function findAllSearchData(): array
    {
        $credentials = $this->getLdap()->getCredentials();

        return [
            'dn' => "OU=" . static::getBaseOu() . ",{$credentials->getLdapRoot()}",
            'filter' => "(&{$credentials->getLdapSearchFilters()['OrganizationalUnit']}(OU=*))"
        ];
    }

    public function getNonOptionalInputDataForSearch(): array
    {
        return ['name'];
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected static function searchDataForFromDn(array $dnParts): array
    {
        $credentials = ldap::getCredentials();
        $confRootDn = $credentials->getLdapRoot();

        $ous = static::divide($dnParts['OUs'], 'OU');

        $dn = implode(',', array_map(fn(string $ou) => "OU={$ou}", $ous)) . ",{$confRootDn}";

        return [
            'dn' => $dn,
            'filter' => "(&{$credentials->getLdapSearchFilters()['OrganizationalUnit']}(OU={$ous[0]}))"
        ];
    }

    /**
     * @throws Exception
     */
    public function movePersonIn(Person $person): bool
    {
        if (is_null($person->getLdap())) {
            $person->setLdap($this->getLdap());
        }

        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            return $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
        }

        $this->findAndComplete();

        $personDn = $person->getDn();
        $organizationalUnitDn = $this->getDn();

        $personNewDn = "CN={$person->getDisplayname()}";

        $this->getListOptions()->onDebug("Person DN : {$personDn}", 1, get_class($this));
        $this->getListOptions()->onDebug("Person new DN : {$personNewDn}", 1, get_class($this));
        $this->getListOptions()->onDebug("OU DN : {$organizationalUnitDn}", 1, get_class($this));

        if (!@ldap_rename(
            $connexion,
            $personDn,
            $personNewDn,
            $organizationalUnitDn,
            true
        )) {
            return $this->getListOptions()->onError(
                "Erreur lors du déplacement de l'utilisateur {$person->getDisplayname()} vers l'OU {$this->getName()}: " . ldap_error($connexion),
                entete: get_class($this)
            );
        }

        $this->getListOptions()->onInfo(
            "L'utilisateur {$person->getDisplayname()} à bien été déplacé dans l'OU {$this->getName()}",
            get_class($this)
        );
        $this->findAndComplete();
        return true;
    }

    /**
     * @throws Exception
     */
    public function moveGroupIn(Group $group): bool
    {
        if (is_null($group->getLdap())) {
            $group->setLdap($this->getLdap());
        }

        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            return $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
        }

        $this->findAndComplete();

        $personDn = $group->getDn();
        $organizationalUnitDn = $this->getDn();

        $personNewDn = "CN={$group->getName()}";

        $this->getListOptions()->onDebug("Group DN : {$personDn}", 1, get_class($this));
        $this->getListOptions()->onDebug("Group new DN : {$personNewDn}", 1, get_class($this));
        $this->getListOptions()->onDebug("OU DN : {$organizationalUnitDn}", 1, get_class($this));

        if (!@ldap_rename(
            $connexion,
            $personDn,
            $personNewDn,
            $organizationalUnitDn,
            true
        )) {
            return $this->getListOptions()->onError(
                "Erreur lors du déplacement de l'utilisateur {$group->getName()} vers l'OU {$this->getName()}: " . ldap_error($connexion),
                entete: get_class($this)
            );
        }

        $this->getListOptions()->onInfo(
            "L'utilisateur {$group->getName()} à bien été déplacé dans l'OU {$this->getName()}",
            get_class($this)
        );
        $this->findAndComplete();
        return true;
    }
}