<?php

namespace Zorille\itop\data_models;

class ApplicationSolution extends FunctionalCI
{
    const ENTITY_NAME = 'ApplicationSolution';

    protected array $functionalcis_list = [];
    protected array $businessprocess_list = [];
    protected ?string $status = null;
    protected ?string $redundancy = null;
    protected ?string $friendlyname = null;
    protected ?string $obsolescence_flag = null;
    protected string $finalclass = 'ApplicationSolution';

    /**
     * @return array
     */
    public function getFunctionalcisList(): array
    {
        return $this->functionalcis_list;
    }

    /**
     * @param array $functionalcis_list
     * @return ApplicationSolution
     */
    public function setFunctionalcisList(array $functionalcis_list): ApplicationSolution
    {
        $this->functionalcis_list = $functionalcis_list;
        return $this;
    }

    /**
     * @return array
     */
    public function getBusinessprocessList(): array
    {
        return $this->businessprocess_list;
    }

    /**
     * @param array $businessprocess_list
     * @return ApplicationSolution
     */
    public function setBusinessprocessList(array $businessprocess_list): ApplicationSolution
    {
        $this->businessprocess_list = $businessprocess_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
    /**
     * @param string|null $status
     * @return ApplicationSolution
     */
    public function setStatus(?string $status): ApplicationSolution
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRedundancy(): ?string
    {
        return $this->redundancy;
    }
    /**
     * @param string|null $redundancy
     * @return ApplicationSolution
     */
    public function setRedundancy(?string $redundancy): ApplicationSolution
    {
        $this->redundancy = $redundancy;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }
    /**
     * @param string|null $friendlyname
     * @return ApplicationSolution
     */
    public function setFriendlyname(?string $friendlyname): ApplicationSolution
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getObsolescenceFlag(): ?string
    {
        return $this->obsolescence_flag;
    }
    /**
     * @param string|null $obsolescence_flag
     * @return ApplicationSolution
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): ApplicationSolution
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }
}