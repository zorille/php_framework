<?php

namespace Zorille\framework\ldap;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Zorille\framework\options;

abstract class query_fetcher
{
    private ?ldap $ldap = null;
    private options $list_options;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        global $liste_option;

        $this->setListOptions($liste_option);

        $this->setLdap(ldap::creer_ldap(
            $this->list_options,
            ldapCredentials::creer_ldap_credentials()
                ->charger_depuis_config()
        )->connect());
    }

    public static function create(): self
    {
        return new static();
    }

    public abstract function getObjectModel(): string;
    public abstract function getDefaultSelectOus(): array;

    public function getBaseClassName(): string
    {
        return basename(
            str_replace('\\', '/', $this->getObjectModel())
        );
    }

    /**
     * @return data_model[]
     * @throws Exception
     */
    public function findAll(array $ous = [], string $selectionData = '*'): array
    {
        $class = $this->getBaseClassName();

        if (empty($ous)) {
            $ous = $this->getDefaultSelectOus();
        }

        $data = $this->getSelectData($ous, $selectionData);

        return $this->getLdap()
            ->getFactory()
            ->{"getNew{$class}"}()
            ->findAll(...$data);
    }

    /**
     * @throws Exception
     */
    public function findOne(array $nonOptionalInputData = []): data_model|null
    {
        $class = $this->getBaseClassName();

        /** @var data_model $model */
        $model = $this->getLdap()->getFactory()->{"getNew{$class}"}();

        $containsAllNonOptionalData = true;
        $notInputData = [];
        foreach ($model->getNonOptionalInputDataForSearch() as $nonOptionalInputDataForSearch) {
            if (!in_array($nonOptionalInputDataForSearch, array_keys($nonOptionalInputData))) {
                $containsAllNonOptionalData = false;
                $notInputData[] = $nonOptionalInputDataForSearch;
            }
        }

        if (!$containsAllNonOptionalData) {
            $this->getListOptions()->onError(
                "Expected field" . (count($notInputData) > 1 ? 's' : '') . " " . implode(",", $notInputData) . " " . ((count($notInputData) > 1 ? 'are' : 'is')) . " unknown.",
                entete: get_class($this)
            );
            return null;
        }

        if ($containsAllNonOptionalData) {
            foreach ($nonOptionalInputData as $key => $value) {
                $model->{'set' . ucfirst($key)}($value);
            }
        }
        return $model->findAndComplete();
    }

    #[ArrayShape([
        'dn' => 'string',
        'filter' => 'string'
    ])]
    public abstract function getSelectData(array $ous = [], string $selectionData = '*'): array;

    public function getLdap(): ?ldap
    {
        return $this->ldap;
    }
    public function setLdap(?ldap $ldap): self
    {
        $this->ldap = $ldap;
        return $this;
    }

    public function getListOptions(): options
    {
        return $this->list_options;
    }
    public function setListOptions(options $list_options): self
    {
        $this->list_options = $list_options->setSortEnErreur(false);
        return $this;
    }
}