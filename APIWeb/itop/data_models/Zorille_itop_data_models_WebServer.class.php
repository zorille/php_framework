<?php

namespace Zorille\itop\data_models;

class WebServer extends FunctionalCI
{
    const ENTITY_NAME = 'WebServer';

    protected ?string $system_id = null;
    protected ?string $system_name = null;
    protected ?string $software_id = null;
    protected ?string $software_name = null;
    protected ?string $softwarelicence_id = null;
    protected ?string $softwarelicence_name = null;
    protected ?string $path = null;
    protected ?string $status = null;
    protected ?string $webapp_list = null;
    protected ?string $fqdn = null;
    protected ?string $friendlyname = null;
    protected string $finalclass = 'WebServer';
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
     * @return WebServer
     */
    public function setSystemId(?string $system_id): WebServer
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
     * @return WebServer
     */
    public function setSystemName(?string $system_name): WebServer
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
     * @return WebServer
     */
    public function setSoftwareId(?string $software_id): WebServer
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
     * @return WebServer
     */
    public function setSoftwareName(?string $software_name): WebServer
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
     * @return WebServer
     */
    public function setSoftwarelicenceId(?string $softwarelicence_id): WebServer
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
     * @return WebServer
     */
    public function setSoftwarelicenceName(?string $softwarelicence_name): WebServer
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
     * @return WebServer
     */
    public function setPath(?string $path): WebServer
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
     * @return WebServer
     */
    public function setStatus(?string $status): WebServer
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebappList(): ?string
    {
        return $this->webapp_list;
    }

    /**
     * @param string|null $webapp_list
     * @return WebServer
     */
    public function setWebappList(?string $webapp_list): WebServer
    {
        $this->webapp_list = $webapp_list;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFqdn(): ?string
    {
        return $this->fqdn;
    }

    /**
     * @param string|null $fqdn
     * @return WebServer
     */
    public function setFqdn(?string $fqdn): WebServer
    {
        $this->fqdn = $fqdn;
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
     * @return WebServer
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): WebServer
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
     * @return WebServer
     */
    public function setSystemIdFriendlyname(?string $system_id_friendlyname): WebServer
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
     * @return WebServer
     */
    public function setSystemIdFinalclassRecall(?string $system_id_finalclass_recall): WebServer
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
     * @return WebServer
     */
    public function setSystemIdObsolescenceFlag(?string $system_id_obsolescence_flag): WebServer
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
     * @return WebServer
     */
    public function setSoftwareIdFriendlyname(?string $software_id_friendlyname): WebServer
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
     * @return WebServer
     */
    public function setSoftwarelicenceIdFriendlyname(?string $softwarelicence_id_friendlyname): WebServer
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
     * @return WebServer
     */
    public function setSoftwarelicenceIdObsolescenceFlag(?string $softwarelicence_id_obsolescence_flag): WebServer
    {
        $this->softwarelicence_id_obsolescence_flag = $softwarelicence_id_obsolescence_flag;
        return $this;
    }
}