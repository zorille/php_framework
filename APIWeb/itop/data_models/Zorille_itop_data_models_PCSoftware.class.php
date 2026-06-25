<?php

namespace Zorille\itop\data_models;

class PCSoftware extends FunctionalCI
{
    const ENTITY_NAME = 'PCSoftware';

    protected ?string $system_id = null;
    protected ?string $system_name = null;
    protected ?string $software_id = null;
    protected ?string $software_name = null;
    protected ?string $softwarelicence_id = null;
    protected ?string $softwarelicence_name = null;
    protected ?string $path = null;
    protected ?string $status = null;
    protected ?string $friendlyname = null;
    protected string $finalclass = 'PCSoftware';
    protected ?string $obsolescence_flag = null;
    protected ?string $system_id_friendlyname = null;
    protected ?string $system_id_finalclass_recall = null;
    protected ?string $system_id_obsolescence_flag = null;
    protected ?string $software_id_friendlyname = null;
    protected ?string $softwarelicence_id_friendlyname = null;
    protected ?string $softwarelicence_id_obsolescence_flag = null;

    /**
     * @return string|null
     */
    public function getSystemId(): ?string
    {
        return $this->system_id;
    }

    /**
     * @param string|null $system_id
     * @return PCSoftware
     */
    public function setSystemId(?string $system_id): PCSoftware
    {
        $this->system_id = $system_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSystemName(): ?string
    {
        return $this->system_name;
    }

    /**
     * @param string|null $system_name
     * @return PCSoftware
     */
    public function setSystemName(?string $system_name): PCSoftware
    {
        $this->system_name = $system_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwareId(): ?string
    {
        return $this->software_id;
    }

    /**
     * @param string|null $software_id
     * @return PCSoftware
     */
    public function setSoftwareId(?string $software_id): PCSoftware
    {
        $this->software_id = $software_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwareName(): ?string
    {
        return $this->software_name;
    }

    /**
     * @param string|null $software_name
     * @return PCSoftware
     */
    public function setSoftwareName(?string $software_name): PCSoftware
    {
        $this->software_name = $software_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwarelicenceId(): ?string
    {
        return $this->softwarelicence_id;
    }

    /**
     * @param string|null $softwarelicence_id
     * @return PCSoftware
     */
    public function setSoftwarelicenceId(?string $softwarelicence_id): PCSoftware
    {
        $this->softwarelicence_id = $softwarelicence_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwarelicenceName(): ?string
    {
        return $this->softwarelicence_name;
    }

    /**
     * @param string|null $softwarelicence_name
     * @return PCSoftware
     */
    public function setSoftwarelicenceName(?string $softwarelicence_name): PCSoftware
    {
        $this->softwarelicence_name = $softwarelicence_name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     * @return PCSoftware
     */
    public function setPath(?string $path): PCSoftware
    {
        $this->path = $path;
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
     * @return PCSoftware
     */
    public function setStatus(?string $status): PCSoftware
    {
        $this->status = $status;
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
     * @return PCSoftware
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): PCSoftware
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSystemIdFriendlyname(): ?string
    {
        return $this->system_id_friendlyname;
    }

    /**
     * @param string|null $system_id_friendlyname
     * @return PCSoftware
     */
    public function setSystemIdFriendlyname(?string $system_id_friendlyname): PCSoftware
    {
        $this->system_id_friendlyname = $system_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSystemIdFinalclassRecall(): ?string
    {
        return $this->system_id_finalclass_recall;
    }

    /**
     * @param string|null $system_id_finalclass_recall
     * @return PCSoftware
     */
    public function setSystemIdFinalclassRecall(?string $system_id_finalclass_recall): PCSoftware
    {
        $this->system_id_finalclass_recall = $system_id_finalclass_recall;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSystemIdObsolescenceFlag(): ?string
    {
        return $this->system_id_obsolescence_flag;
    }

    /**
     * @param string|null $system_id_obsolescence_flag
     * @return PCSoftware
     */
    public function setSystemIdObsolescenceFlag(?string $system_id_obsolescence_flag): PCSoftware
    {
        $this->system_id_obsolescence_flag = $system_id_obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwareIdFriendlyname(): ?string
    {
        return $this->software_id_friendlyname;
    }

    /**
     * @param string|null $software_id_friendlyname
     * @return PCSoftware
     */
    public function setSoftwareIdFriendlyname(?string $software_id_friendlyname): PCSoftware
    {
        $this->software_id_friendlyname = $software_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwarelicenceIdFriendlyname(): ?string
    {
        return $this->softwarelicence_id_friendlyname;
    }

    /**
     * @param string|null $softwarelicence_id_friendlyname
     * @return PCSoftware
     */
    public function setSoftwarelicenceIdFriendlyname(?string $softwarelicence_id_friendlyname): PCSoftware
    {
        $this->softwarelicence_id_friendlyname = $softwarelicence_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSoftwarelicenceIdObsolescenceFlag(): ?string
    {
        return $this->softwarelicence_id_obsolescence_flag;
    }

    /**
     * @param string|null $softwarelicence_id_obsolescence_flag
     * @return PCSoftware
     */
    public function setSoftwarelicenceIdObsolescenceFlag(?string $softwarelicence_id_obsolescence_flag): PCSoftware
    {
        $this->softwarelicence_id_obsolescence_flag = $softwarelicence_id_obsolescence_flag;
        return $this;
    }
}