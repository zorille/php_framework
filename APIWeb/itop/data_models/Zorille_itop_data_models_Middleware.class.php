<?php

namespace Zorille\itop\data_models;

class Middleware extends FunctionalCI
{
    const ENTITY_NAME = 'Middleware';

    protected ?string $system_id = null;
    protected ?string $system_name = null;
    protected ?string $software_id = null;
    protected ?string $software_name = null;
    protected ?string $softwarelicence_id = null;
    protected ?string $softwarelicence_name = null;
    protected ?string $path = null;
    protected ?string $status = null;
    protected ?string $middlewareinstance_list = null;
    protected ?string $friendlyname = null;
    protected string $finalclass = 'Middleware';
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
     * @return Middleware
     */
    public function setSystemId(?string $system_id): Middleware
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
     * @return Middleware
     */
    public function setSystemName(?string $system_name): Middleware
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
     * @return Middleware
     */
    public function setSoftwareId(?string $software_id): Middleware
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
     * @return Middleware
     */
    public function setSoftwareName(?string $software_name): Middleware
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
     * @return Middleware
     */
    public function setSoftwarelicenceId(?string $softwarelicence_id): Middleware
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
     * @return Middleware
     */
    public function setSoftwarelicenceName(?string $softwarelicence_name): Middleware
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
     * @return Middleware
     */
    public function setPath(?string $path): Middleware
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
     * @return Middleware
     */
    public function setStatus(?string $status): Middleware
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMiddlewareinstanceList(): ?string
    {
        return $this->middlewareinstance_list;
    }

    /**
     * @param string|null $middlewareinstance_list
     * @return Middleware
     */
    public function setMiddlewareinstanceList(?string $middlewareinstance_list): Middleware
    {
        $this->middlewareinstance_list = $middlewareinstance_list;
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
     * @return Middleware
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): Middleware
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
     * @return Middleware
     */
    public function setSystemIdFriendlyname(?string $system_id_friendlyname): Middleware
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
     * @return Middleware
     */
    public function setSystemIdFinalclassRecall(?string $system_id_finalclass_recall): Middleware
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
     * @return Middleware
     */
    public function setSystemIdObsolescenceFlag(?string $system_id_obsolescence_flag): Middleware
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
     * @return Middleware
     */
    public function setSoftwareIdFriendlyname(?string $software_id_friendlyname): Middleware
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
     * @return Middleware
     */
    public function setSoftwarelicenceIdFriendlyname(?string $softwarelicence_id_friendlyname): Middleware
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
     * @return Middleware
     */
    public function setSoftwarelicenceIdObsolescenceFlag(?string $softwarelicence_id_obsolescence_flag): Middleware
    {
        $this->softwarelicence_id_obsolescence_flag = $softwarelicence_id_obsolescence_flag;
        return $this;
    }
}