<?php

namespace Zorille\framework\ldap\data_models;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework\ldap\data_model;
use Zorille\framework\ldap\ldap;
use Zorille\framework\ldap\ldapCredentials;
use Zorille\itop\data_models\Team;

/**
 * @method static self create()
 * @method static self convertArrToSelf(array $record, ?self $obj = null)
 * @method null|self findAndComplete()
 * @method null|self createOne(string $dn = '')
 * @method null|self createIfNotExists(string $dn = '')
 * @method findAll(string $dn = '', string $filter = '')
 * @method self setDn(string $dn)
 */
class Group extends data_model
{
    const ENTITY_NAME = 'Groups';

    protected array $objectclass = [];
    protected string $cn = '';
    protected array $member = [];
    protected string $distinguishedname = '';
    protected int $instancetype = 0;
    protected string $whencreated = '';
    protected string $whenchanged = '';
    protected string $usncreated = '';
    protected string $usnchanged = '';
    protected string $name = '';
    protected string $objectguid = '';
    protected string $objectsid = '';
    protected string $samaccountname = '';
    protected string $samaccounttype = '';
    protected string $grouptype = '';
    protected string $objectcategory = '';
    protected array $dscorepropagationdata = [];

    private string $customerCode = '';

    public function getObjectclass(): array
    {
        return $this->objectclass;
    }
    public function setObjectclass(array $objectclass): self
    {
        $this->objectclass = $objectclass;
        return $this;
    }

    public function getCn(): string
    {
        return $this->cn;
    }
    public function setCn(string $cn): self
    {
        $this->cn = $cn;
        return $this;
    }

    public function getMember(bool $model = false): array
    {
        if ($model) {
            return array_map(
                /**
                 * @throws Exception
                 */
                fn(string $dn) => Person::fromDN($dn, $this->getLdap()),
                $this->member
            );
        }
        return $this->member;
    }
    public function setMember(array $member): self
    {
        $this->member = $member;
        return $this;
    }
    public function resetMember(): self
    {
        $this->member = [];
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

    public function getInstancetype(): int
    {
        return $this->instancetype;
    }
    public function setInstancetype(int $instancetype): self
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
        $this->name = str_replace(['(', ')', '*', '/', '.'], '_', $name);
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

    public function getObjectsid(): string
    {
        return $this->objectsid;
    }
    public function setObjectsid(string $objectsid): self
    {
        $this->objectsid = $objectsid;
        return $this;
    }

    public function getSamaccountname(): string
    {
        return $this->samaccountname;
    }
    public function setSamaccountname(string $samaccountname): self
    {
        $this->samaccountname = str_replace(['(', ')', '*', '/', '.'], '_', $samaccountname);
        return $this;
    }

    public function getSamaccounttype(): string
    {
        return $this->samaccounttype;
    }
    public function setSamaccounttype(string $samaccounttype): self
    {
        $this->samaccounttype = $samaccounttype;
        return $this;
    }

    public function getGrouptype(): string
    {
        return $this->grouptype;
    }
    public function setGrouptype(string $grouptype): self
    {
        $this->grouptype = $grouptype;
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

    public function getCustomerCode(): string
    {
        return $this->customerCode;
    }
    public function setCustomerCode(string $customerCode): self
    {
        $this->customerCode = $customerCode;

        return $this;
    }

    /**
     * @param Team|array $record
     * @return self
     * @throws Exception
     */
    public static function convert($record): self
    {
        if (is_array($record)) {
            $obj = static::convertArrToSelf($record);
            preg_match("/OU=([0-9]{5})/m", $obj->getDn(), $matches);
            if (!empty($matches)) {
                $obj->setCustomerCode($matches[1]);
            }
            $obj->setSamaccountname($obj->getName());
        }
		else {
			$obj = static::create()
				->setCustomerCode($record->getCodeClient())
				->setName($record->getName())
				->setSamaccountname($record->getName());
		}

	    try {
		    return $obj->findAndComplete();
	    }
	    catch (Exception $e) {
		    global $liste_option;
		    $obj->setLdap(ldap::creer_ldap(
			    $liste_option,
			    ldapCredentials::creer_ldap_credentials()
				    ->charger_depuis_config()
		    )->connect());
	    }
	    finally {
		    return $obj->findAndComplete();
	    }
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected function findAndCompleteSearchData(): array
    {
        $credentials = $this->getLdap()->getCredentials();

        return [
            'dn' => "OU=" . static::getBaseOu() . "," . $credentials->getLdapRoot(),
            'filter' => "(&{$credentials->getLdapSearchFilters()['Group']}(CN={$this->getName()}))"
        ];
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected function findAllSearchData(): array
    {
        $credentials = $this->getLdap()->getCredentials();

        $dn = "OU=" . static::getBaseOu() . "," . $credentials->getLdapRoot();
        $filter = "(&{$credentials->getLdapSearchFilters()['Group']}(CN=*))";

        return ['dn' => $dn, 'filter' => $filter];
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    protected static function searchDataForFromDn(array $dnParts): array
    {
        $credentials = ldap::getCredentials();
        $confRootDn = $credentials->getLdapRoot();

        $cn = $dnParts['CN'];
        $ous = static::divide($dnParts['OUs'], 'OU');

        $dn = implode(',', array_map(fn(string $ou) => "OU={$ou}", $ous)) . ",{$confRootDn}";

        return [
            'dn' => $dn,
            'filter' => "(&{$credentials->getLdapSearchFilters()['Group']}(CN={$cn}))"
        ];
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
                'sAMAccountName' => $this->getName(),
                'name' => $this->getName(),
                'objectclass' => ['top', 'group'],
            ],
            'dn' => empty($dn)
                ? "CN={$this->getName()},OU=" . $this->getCustomerCode() .
                ",OU=" . static::getBaseOu() . ",{$this->getLdap()->getCredentials()->getLdapRoot()}"
                : $dn,
            'errorLogHeader' => fn() => "Erreur lors de l'ajout du group",
            'successLog' => fn(self $group) => "Le Group {$group->getName()} à été ajouté avec succès",
            'alreadyExistsLog' => fn(array $data) => "Le groupe {$data['name']} existe déjà et n'a donc pas été créé"
        ];
    }

    public function getNonOptionalInputDataForSearch(): array
    {
        return ['name'];
    }

    /**
     * @throws Exception
     */
    public function addAsMember(Person $person): bool
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
        $person->findAndComplete();

        if (!$person->isAlreadyExists()) {
            $this->getListOptions()->onInfo(
                "L'utilisateur {$person->getDisplayname()} n'existe pas et n'a donc pas été ajouté au groupe {$this->getName()}",
                get_class($this)
            );
            return false;
        }

        $data = [
            "member" => $person->getDn()
        ];

        $dn = $this->getDn();

        $this->getListOptions()->onDebug("Group DN => {$dn}", 1, get_class($this));
        $this->getListOptions()->onDebug('Group => ' . print_r($this, true), 1, get_class($this));
        $this->getListOptions()->onDebug('Data to update => ' . print_r($data, true), 1, get_class($this));

        if (!@ldap_mod_add($connexion, $dn, $data)) {
            [$errno, $error, $extended_error] = $this->getLastError();

            if (!str_ends_with(strtolower($error), 'already exists')) {
                return $this->getListOptions()->onError(
                    "Erreur lors de l'ajout de l'utilisateur {$person->getDisplayname()} au groupe {$this->getName()} : {$errno} {$error} | {$extended_error}",
                    entete: get_class($this)
                );
            }

            $this->getListOptions()->onInfo(
                "L'utilisateur {$person->getDisplayname()} n'as pas été ajouté au groupe {$this->getName()} car il y est déjà",
                get_class($this)
            );

            $this->findAndComplete();
            return true;
        }

        $this->getListOptions()->onInfo(
            "L'utilisateur {$person->getDisplayname()} à été ajouté au groupe {$this->getName()} avec succès",
            get_class($this)
        );
        $this->findAndComplete();
        return true;
    }

    /**
     * @throws Exception
     */
    public function removeMember(Person $person): self|null
    {
        if (is_null($person->getLdap())) {
            $person->setLdap($this->getLdap());
        }

        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return null;
        }

        $this->findAndComplete();

        $dn = $this->getDistinguishedname();

        $entry = [
            'member' => $person->getDistinguishedname()
        ];

        $this->getListOptions()->onDebug("DN : {$dn}", 1, get_class($this));
        $this->getListOptions()->onDebug("Data : " . print_r($entry, true), 1, get_class($this));

		if (in_array($this->dn, $person->getMemberof())) {
			if (!@ldap_mod_del($connexion, $dn, $entry)) {
				[$errno, $error, $extended_error] = $this->getLastError();

				$this->getListOptions()->onError(
					"Erreur lors de la suppression de l'utilisateur {$person->getDisplayname()} du groupe {$this->getName()} : {$errno} {$error} | {$extended_error}",
					entete: get_class($this)
				);
				return null;
			}
			$this->getListOptions()->onInfo(
				"L'utilisateur {$person->getDisplayname()} à été supprimé du groupe {$this->getName()} avec succès",
				get_class($this)
			);
		}
		else {
			$this->getListOptions()->onInfo(
				"L'utilisateur {$person->getDisplayname()} n'a pas été supprimé du groupe {$this->getName()} car il n'en était pas membre",
				get_class($this)
			);
		}

        return $this->findAndComplete();
    }

    /**
     * @throws Exception
     */
    public function moveToOrganizationalUnit(OrganizationalUnit $organizationalUnit): self
    {
        $organizationalUnit->createIfNotExists();
        $this->findAndComplete();
        $organizationalUnit->moveGroupIn($this);
        preg_match('/(OU=([^,]*),?)+/m', $this->getDn(), $matches_old);
        preg_match('/(OU=([^,]*),?)+/m', $organizationalUnit->getDn(), $matches_new);
        $this->setDn(str_replace($matches_old[0], $matches_new[0], $this->getDn()))->findAndComplete();

        return $this;
    }
}