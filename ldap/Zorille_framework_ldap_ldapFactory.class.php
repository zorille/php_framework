<?php

namespace Zorille\framework\ldap;

use Error;
use Zorille\framework\ldap\data_models\{
    Group,
    OrganizationalUnit,
    Person
};
use ReflectionObject;
use Zorille\framework\options;
use Zorille\itop\data_models\Team;

/**
 * @method Array<Person> getAllPersons(array $ous = [], string $cn = '*')
 * @method Person getNewPerson()
 * @method Person getPersonFromDn(string $dn)
 * @method Person convertToPerson(array|\Zorille\itop\data_models\Person $item)
 * @method Array<Group> getAllGroups(array $ous = [], string $cn = '*')
 * @method Group getNewGroup()
 * @method Group getGroupFromDn(string $dn)
 * @method Group convertToGroup(array|Team $item)
 * @method Array<OrganizationalUnit> getAllOrganizationalUnits(array $ous = [], string $ou = '*')
 * @method OrganizationalUnit getNewOrganizationalUnit()
 * @method OrganizationalUnit getOrganizationalUnitFromDn(string $dn)
 */
class ldapFactory
{
    private ?ldap $ldap = null;
    private ?options $list_options = null;

    public static function create(): self
    {
        return new self();
    }

    public function __call(string $name, array $arguments): data_model|array|null
    {
        if (!str_contains((new ReflectionObject($this))->getDocComment(), $name)) {
            throw new Error('Call to undefined method ' . get_class($this) . '::' . $name . '()');
        }

        if (str_starts_with($name, 'get')) {
            if (str_starts_with(substr($name, 3), 'New')) {
                $class = '\\Zorille\\framework\\ldap\\data_models\\' . substr($name, 6);
                if (class_exists($class)) {
                    return $class::create()
                        ->setLdap($this->getLdap());
                }
            }

            if (str_ends_with(substr($name, 3), 'FromDn')) {
                $class = '\\Zorille\\framework\\ldap\\data_models\\' . substr($name, 3, -strlen('FromDn'));
                if (class_exists($class)) {
                    return $class::fromDn($arguments[0], $this->getLdap());
                }
            }

            if (
                str_starts_with(substr($name, 3), 'All') &&
                str_ends_with(substr($name, 3), 's')
            ) {
                $class = '\\Zorille\\framework\\ldap\\query_fetchers\\' . substr(substr($name, 3 + strlen('All')), 0, -1) . 'Fetcher';
                if (class_exists($class)) {
                    return array_map(fn($item) => $item->setLdap($this->getLdap()), $class::create()->findAll(...$arguments));
                }
            }
        }

        if (str_starts_with($name, 'convertTo')) {
            $class = '\\Zorille\\framework\\ldap\\data_models\\' . substr($name, strlen('convertTo'));
            if (class_exists($class)) {
                return $class::convert($arguments[0])->setLdap($this->getLdap());
            }
        }

        return null;
    }

    public function getLdap(): ?ldap
    {
        return $this->ldap;
    }
    public function setLdap(?ldap $ldap): self
    {
        $this->ldap = $ldap;
        $this->setListOptions($ldap->getListeOptions());
        return $this;
    }

    public function getListOptions(): ?options
    {
        return $this->list_options;
    }
    public function setListOptions(?options $list_options): self
    {
        $this->list_options = $list_options;
        return $this;
    }
}