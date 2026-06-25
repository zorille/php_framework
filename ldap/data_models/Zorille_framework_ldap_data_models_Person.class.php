<?php

namespace Zorille\framework\ldap\data_models;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework\ldap\data_model;
use Zorille\framework\ldap\ldap;
use Zorille\framework\ldap\ldapCredentials;

/**
 * @method static self create()
 * @method static self convertArrToSelf(array $record, ?self $obj = null)
 * @method null|self findAndComplete()
 * @method null|self createOne(string $dn = '')
 * @method null|self createIfNotExists(string $dn = '')
 * @method array<self> findAll(string $dn = '', string $filter = '')
 * @method self setDn(string $dn)
 */
class Person extends data_model
{
    const ENTITY_NAME = 'Persons';

    const DISABLED = 514;
    const ENABLED = 544;

    protected array $objectclass = [];
    protected string $cn = '';
    protected string $sn = '';
    protected string $description = '';
    protected string $physicaldeliveryofficename = '';
    protected string $givenname = '';
    protected string $distinguishedname = '';
    protected int $instancetype = 0;
    protected string $whencreated = '';
    protected string $whenchanged = '';
    protected string $displayname = '';
    protected string $usncreated = '';
    protected string $usnchanged = '';
    protected string $company = '';
    protected string $name = '';
    protected string $objectguid = '';
    protected int $useraccountcontrol = 0;
    protected int $codepage = 0;
    protected int $countrycode = 0;
    protected string $pwdlastset = '';
    protected int $primarygroupid = 0;
    protected string $objectsid = '';
    protected string $accountexpires = '';
    protected string $samaccountname = '';
    protected string $samaccounttype = '';
    protected string $userprincipalname = '';
    protected string $objectcategory = '';
    protected array $dscorepropagationdata = [];
    protected string $mail = '';
    protected array $memberof = [];
    protected int $badpwdcount = 0;
    protected string $badpasswordtime = '';
    protected string $lastlogoff = '';
    protected string $lastlogon = '';
    protected int $logoncount = 0;
    protected string $manager = '';
    protected int $msds_supportedencryptiontypes = 0;
    protected int $lastlogontimestamp = 0;
    protected int $admincount = 0;
    protected string $msmqsigncertificates = '';
    protected string $msmqdigests = '';
    protected string $msds_authenticatedatdc = '';
    protected string $scriptpath = '';
    protected int $lockouttime = 0;
    protected string $userparameters = '';
    protected string $msnpallowdialin = '';
    protected array $directreports = [];
    protected string $info = '';
    protected string $userpassword = '';
    protected string $unicodepwd = '';

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

    public function getSn(): string
    {
        return $this->sn;
    }
    public function setSn(string $sn): self
    {
        $this->sn = $sn;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPhysicaldeliveryofficename(): ?string
    {
        return $this->physicaldeliveryofficename;
    }
    public function setPhysicaldeliveryofficename(?string $physicaldeliveryofficename): self
    {
        $this->physicaldeliveryofficename = $physicaldeliveryofficename;
        return $this;
    }

    public function getGivenname(): string
    {
        return $this->givenname;
    }
    public function setGivenname(string $givenname): self
    {
        $this->givenname = $givenname;
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

    public function getDisplayname(): string
    {
        if (empty($this->displayname)) {
            $this->setDisplayname("{$this->getSn()}.{$this->getGivenname()}");
        }
        return $this->displayname;
    }
    public function setDisplayname(string $displayname): self
    {
        $this->displayname = $displayname;
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

    public function getCompany(): ?string
    {
        return $this->company;
    }
    public function setCompany(?string $company): self
    {
        $this->company = $company;
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

    public function getUseraccountcontrol(): int
    {
        return $this->useraccountcontrol;
    }
    public function setUseraccountcontrol(int $useraccountcontrol): self
    {
        $this->useraccountcontrol = $useraccountcontrol;
        return $this;
    }

    public function getCodepage(): int
    {
        return $this->codepage;
    }
    public function setCodepage(int $codepage): self
    {
        $this->codepage = $codepage;
        return $this;
    }

    public function getCountrycode(): int
    {
        return $this->countrycode;
    }
    public function setCountrycode(int $countrycode): self
    {
        $this->countrycode = $countrycode;
        return $this;
    }

    public function getPwdlastset(): string
    {
        return $this->pwdlastset;
    }
    public function setPwdlastset(string $pwdlastset): self
    {
        $this->pwdlastset = $pwdlastset;
        return $this;
    }

    public function getPrimarygroupid(): int
    {
        return $this->primarygroupid;
    }
    public function setPrimarygroupid(int $primarygroupid): self
    {
        $this->primarygroupid = $primarygroupid;
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

    public function getAccountexpires(): string
    {
        return $this->accountexpires;
    }
    public function setAccountexpires(string $accountexpires): self
    {
        $this->accountexpires = $accountexpires;
        return $this;
    }

    public function getSamaccountname(): string
    {
        return $this->samaccountname;
    }
    public function setSamaccountname(string $samaccountname): self
    {
        $this->samaccountname = $samaccountname;
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

    public function getUserprincipalname(): string
    {
        return $this->userprincipalname;
    }
    public function setUserprincipalname(string $userprincipalname): self
    {
        $this->userprincipalname = $userprincipalname;
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

    public function getMail(): string
    {
        return str_replace("nlighten.eu", "nlighten.com", strtolower($this->mail));
    }
    public function setMail(string $mail): self
    {
        $this->mail = str_replace("nlighten.eu", "nlighten.com", strtolower($mail));
        return $this;
    }

    /**
     * @throws Exception
     */
    public function getMemberof(bool $model = false): ?array
    {
        if ($model) {
            return array_map(
                function(string $dn) {
                    $group = Group::fromDN($dn, $this->getLdap());
                    $this->getListOptions()->onDebug('Group : ' . print_r($group, true), 1, get_class($this));
                    return $group;
                },
                $this->memberof
            );
        }
        return $this->memberof;
    }
    public function setMemberof(?array $memberof): self
    {
        $this->memberof = $memberof;
        return $this;
    }

    public function getBadpwdcount(): ?int
    {
        return $this->badpwdcount;
    }
    public function setBadpwdcount(?int $badpwdcount): self
    {
        $this->badpwdcount = $badpwdcount;
        return $this;
    }

    public function getBadpasswordtime(): ?string
    {
        return $this->badpasswordtime;
    }
    public function setBadpasswordtime(?string $badpasswordtime): self
    {
        $this->badpasswordtime = $badpasswordtime;
        return $this;
    }

    public function getLastlogoff(): ?string
    {
        return $this->lastlogoff;
    }
    public function setLastlogoff(?string $lastlogoff): self
    {
        $this->lastlogoff = $lastlogoff;
        return $this;
    }

    public function getLastlogon(): ?string
    {
        return $this->lastlogon;
    }
    public function setLastlogon(?string $lastlogon): self
    {
        $this->lastlogon = $lastlogon;
        return $this;
    }

    public function getLogoncount(): ?int
    {
        return $this->logoncount;
    }
    public function setLogoncount(?int $logoncount): self
    {
        $this->logoncount = $logoncount;
        return $this;
    }

    public function getManager(): ?string
    {
        return $this->manager;
    }
    public function setManager(?string $manager): self
    {
        $this->manager = $manager;
        return $this;
    }

    public function getMsdsSupportedencryptiontypes(): ?int
    {
        return $this->msds_supportedencryptiontypes;
    }
    public function setMsdsSupportedencryptiontypes(?int $msds_supportedencryptiontypes): self
    {
        $this->msds_supportedencryptiontypes = $msds_supportedencryptiontypes;
        return $this;
    }

    public function getLastlogontimestamp(): ?int
    {
        return $this->lastlogontimestamp;
    }
    public function setLastlogontimestamp(?int $lastlogontimestamp): self
    {
        $this->lastlogontimestamp = $lastlogontimestamp;
        return $this;
    }

    public function getAdmincount(): ?int
    {
        return $this->admincount;
    }
    public function setAdmincount(?int $admincount): self
    {
        $this->admincount = $admincount;
        return $this;
    }

    public function getMsmqsigncertificates(): ?string
    {
        return $this->msmqsigncertificates;
    }
    public function setMsmqsigncertificates(?string $msmqsigncertificates): self
    {
        $this->msmqsigncertificates = $msmqsigncertificates;
        return $this;
    }

    public function getMsmqdigests(): ?string
    {
        return $this->msmqdigests;
    }
    public function setMsmqdigests(?string $msmqdigests): self
    {
        $this->msmqdigests = $msmqdigests;
        return $this;
    }

    public function getMsdsAuthenticatedatdc(): ?string
    {
        return $this->msds_authenticatedatdc;
    }
    public function setMsdsAuthenticatedatdc(?string $msds_authenticatedatdc): self
    {
        $this->msds_authenticatedatdc = $msds_authenticatedatdc;
        return $this;
    }

    public function getScriptpath(): ?string
    {
        return $this->scriptpath;
    }
    public function setScriptpath(?string $scriptpath): self
    {
        $this->scriptpath = $scriptpath;
        return $this;
    }

    public function getLockouttime(): ?int
    {
        return $this->lockouttime;
    }
    public function setLockouttime(?int $lockouttime): self
    {
        $this->lockouttime = $lockouttime;
        return $this;
    }

    public function getUserparameters(): ?string
    {
        return $this->userparameters;
    }
    public function setUserparameters(?string $userparameters): self
    {
        $this->userparameters = $userparameters;
        return $this;
    }

    public function getMsnpallowdialin(): ?string
    {
        return $this->msnpallowdialin;
    }
    public function setMsnpallowdialin(?string $msnpallowdialin): self
    {
        $this->msnpallowdialin = $msnpallowdialin;
        return $this;
    }

    public function getDirectreports(): ?array
    {
        return $this->directreports;
    }
    public function setDirectreports(?array $directreports): self
    {
        $this->directreports = $directreports;
        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }
    public function setInfo(?string $info): self
    {
        $this->info = $info;
        return $this;
    }

    public function getUnicodepwd(): ?string
    {
        return $this->unicodepwd;
    }
    public function setUnicodepwd(?string $unicodepwd): self
    {
        $this->unicodepwd = $unicodepwd;
        return $this;
    }

    public function getUpn(): string
    {
        return explode('@', $this->getMail())[1];
    }

    public function generatePassword(int $size = 10, int $expectedNbNumbers = 3, int $expectedNbSymbols = 2): self
    {
        $this->userpassword = "";

        $nbSymbols = 0;
        $nbNumbers = 0;

        $NUMBERS = 0;
        $SYMBOLS = 1;
        $CHARS = 2;

        $available = [
            $NUMBERS => "123456789",
            $SYMBOLS => '?!._-',
            $CHARS => "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ",
        ];

        $userpassword = '';
        do {
            $break = false;
            $inject = rand(0, 2);

            switch ($inject) {
                case $NUMBERS:
                    if ($nbNumbers >= $expectedNbNumbers) {
                        $break = true;
                        break;
                    }
                    $nbNumbers++;
                    break;
                case $SYMBOLS:
                    if ($nbSymbols >= $expectedNbSymbols) {
                        $break = true;
                        break;
                    }
                    $nbSymbols++;
                    break;
            }
            if ($break) continue;

            $injectedChar = $available[$inject][rand(0, strlen($available[$inject]) - 1)];

            $userpassword .= $injectedChar;
        }
        while (strlen($userpassword) < $size);

        $this->setUserpassword($userpassword);

        $this->getListOptions()->onInfo(
            "Generated password: '{$this->userpassword}'",
            get_class($this)
        );

        return $this;
    }
    public function setUserpassword(string $userpassword): self
    {
        $this->userpassword = $userpassword;
        return $this;
    }
    public function getUserpassword(): string
    {
        return $this->userpassword;
    }

	/**
	 * @param \Zorille\itop\data_models\Person|array $record
	 * @return self
	 * @throws Exception
	 */
    public static function convert($record): self
    {
        if (is_array($record)) {
            $obj = static::convertArrToSelf($record);
        }
		else {
			$obj = static::create()
				->setObjectclass([
					'top',
					'person',
					'organizationalPerson',
					'user'
				])
				->setSn($record->getCodeClient())
				->setGivenname($record->getId())
				->setMail($record->getEmail())
				->setPhysicaldeliveryofficename($record->getCodeClient())
				->setDescription($record->getEmail())
				->setCompany($record->getCodeClient())
				->setDisplayname("{$record->getCodeClient()}.{$record->getId()}")
				->setUserprincipalname($record->getEmail());
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

    public function getNonOptionalInputDataForSearch(): array
    {
        return ['givenname', 'sn'];
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

        $dn = "OU=" . static::getBaseOu() . "," . $credentials->getLdapRoot();
        $filter = "(&{$credentials->getLdapSearchFilters()['Person']}(CN={$this->getDisplayname()}))";

        return ['dn' => $dn, 'filter' => $filter];
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

        $dn = "OU=" . static::getBaseOu() . ",{$credentials->getLdapRoot()}";
        $filter = "(&{$credentials->getLdapSearchFilters()['Person']}(CN=*))";

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
        $filter = "(&{$credentials->getLdapSearchFilters()['Person']}(CN={$cn}))";

        return ['dn' => $dn, 'filter' => $filter];
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'template' => 'array',
        'dn' => 'string',
        'errorLogHeader' => 'callable',
        'successLog' => 'callable',
        'alreadyExistsLog' => 'callable',
    ])]
    public function getCreationData(string $dn = '', bool $withTemplate = true): array
    {
        $password = $this->generatePassword(13, 7, 1)
            ->getUserpassword();

        $encoded_password = mb_convert_encoding( "\"{$password}\"", "UTF-16LE", "UTF-8");

        return [
            'template' => [
                'name' => strtolower($this->getDisplayname()),
                'givenName' => $this->getGivenName(),
                'userAccountControl' => self::ENABLED, // Activer le compte utilisateur et nécessite un mot de passe
                'userPrincipalName' => $this->getMail(),
                'mail' => $this->getMail(),
                'sn' => $this->getSn(),
                'description' => $this->getMail(),
                'physicalDeliveryOfficeName' => $this->getSn(),
                'displayName' => $this->getDisplayname(),
                'company' => $this->getSn(),
                'sAMAccountName' => strtolower($this->getDisplayname()),
                'unicodePwd' => $encoded_password,
                'department' => $password,
                'cn' => $this->getDisplayname(),
                'objectclass' => [
                    'top',
                    'person',
                    'organizationalPerson',
                    'user'
                ],
            ],
            'dn' => empty($dn) ? "CN={$this->getDisplayname()},OU={$this->getSn()},OU=" . static::getBaseOu() . ",{$this->getLdap()->getCredentials()->getLdapRoot()}" : $dn,
            'errorLogHeader' => fn () => "Erreur lors de l'ajout de l'utilisateur :",
            'successLog' => fn(self $person) => "L'utilisateur {$person->getDisplayname()} à été ajouté avec succès",
            'alreadyExistsLog' => function (array $data) {
				$displayName = $data['displayname'] ?? $data['displayName'] ?? '';
				return "L'utilisateur {$displayName} existe déjà et n'a donc pas été créé";
			}
        ];
    }

    /**
     * @throws Exception
     */
    public function addToGroup(Group $group): self|null
    {
        if (is_null($group->getLdap())) {
            $group->setLdap($this->getLdap());
        }

        $group->findAndComplete();
        $this->findAndComplete();

        $group->addAsMember($this);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function removeFromGroup(Group $group): self|null
    {
        if (is_null($group->getLdap())) {
            $group->setLdap($this->getLdap());
        }

        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return null;
        }

        if (is_null($group->getConnection())) {
            $group->setConnection($connexion);
        }

        $group->findAndComplete();

        $group->removeMember($this);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function moveToOrganizationalUnit(OrganizationalUnit $organizationalUnit): self
    {
        $organizationalUnit->createIfNotExists();
        $this->findAndComplete();
        $organizationalUnit->movePersonIn($this);
        preg_match('/(OU=([^,]*),?)+/m', $this->getDn(), $matches_old);
        preg_match('/(OU=([^,]*),?)+/m', $organizationalUnit->getDn(), $matches_new);
        $this->setDn(str_replace($matches_old[0], $matches_new[0], $this->getDn()))->findAndComplete();

        return $this;
    }

    /**
     * @throws Exception
     */
    public function remove(): self|null
    {
        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return null;
        }

        $this->findAndComplete();

        if (!$this->isAlreadyExists()) {
            $this->getListOptions()->onInfo(
                "L'utilisateur {$this->getDisplayname()} n'à pas été supprimé car il n'existait pas",
                get_class($this)
            );
            return $this;
        }

        $dn = $this->getDn();

        $this->getListOptions()->onDebug("DN : {$dn}", 1, get_class($this));
        $this->getListOptions()->onDebug("Data : " . print_r($this, true), 1, get_class($this));

        // Suppression de l'utilisateur
        if (!@ldap_delete($connexion, $dn)) {
            [$errno, $error, $extended_error] = $this->getLastError();

            $this->getListOptions()->onError(
                "Erreur lors de la suppression de l'utilisateur {$this->getDisplayname()} : {$errno} {$error} | {$extended_error}",
                entete: get_class($this)
            );
            return null;
        }

        $this->getListOptions()->onInfo(
            "L'utilisateur {$this->getDisplayname()} à été supprimé avec succès",
            get_class($this)
        );

        return $this;
    }

    /**
     * @throws Exception
     */
    function disable(): self|null
    {
        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return null;
        }

        $this->findAndComplete();

        if (!$this->isAlreadyExists()) {
            $this->getListOptions()->onInfo(
                "L'utilisateur {$this->getDisplayname()} n'a pas été désactivé car il n'existe pas",
                get_class($this)
            );
            return $this;
        }

        $dn = $this->getDn();

        $this->getListOptions()->onDebug("DN : {$dn}", 1, get_class($this));
        $this->getListOptions()->onDebug(
            "Data : " . print_r(['userAccountControl' => self::DISABLED], true),
            1,
            get_class($this)
        );

        // Désactivation de l'utilisateur
        if (!@ldap_modify($connexion, $dn, ['useraccountcontrol' => self::DISABLED])) {
            [$errno, $error, $extended_error] = $this->getLastError();

            $this->getListOptions()->onError(
                "Erreur lors de la desactivation de l'utilisateur {$this->getDisplayname()} : {$errno} {$error} | {$extended_error}",
                entete: get_class($this)
            );
        }

        $this->getListOptions()->onInfo(
            "L'utilisateur {$this->getDisplayname()} à été désactivé avec succès",
            get_class($this)
        );
        return $this->findAndComplete();
    }

    /**
     * @throws Exception
     */
    function enable(): self|null
    {
        $connexion = $this->getConnection();
        if (is_null($connexion)) {
            $this->getListOptions()->onError(
                "La connexion LDAP n'est pas initialisée",
                entete: get_class($this)
            );
            return null;
        }

        $this->findAndComplete();

        if (!$this->isAlreadyExists()) {
            $this->getListOptions()->onInfo(
                "L'utilisateur {$this->getDisplayname()} n'a pas été activé car il n'existe pas",
                get_class($this)
            );
            return $this;
        }

        $dn = $this->getDn();

        $this->getListOptions()->onDebug("DN : {$dn}", 1, get_class($this));
        $this->getListOptions()->onDebug(
            "Data : " . print_r(['userAccountControl' => self::ENABLED], true),
            1,
            get_class($this)
        );

        // Activation de l'utilisateur
        if (!@ldap_modify($connexion, $dn, ['useraccountcontrol' => self::ENABLED])) {
            [$errno, $error, $extended_error] = $this->getLastError();

            $this->getListOptions()->onError(
                "Erreur lors de l'activation de l'utilisateur {$this->getDisplayname()} : {$errno} {$error} | {$extended_error}",
                entete: get_class($this)
            );
        }

        $this->getListOptions()->onInfo(
            "L'utilisateur {$this->getDisplayname()} à été activé avec succès",
            get_class($this)
        );

        return $this->findAndComplete();
    }
}
