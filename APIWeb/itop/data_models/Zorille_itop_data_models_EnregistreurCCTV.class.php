<?php

namespace Zorille\itop\data_models;

class EnregistreurCCTV extends PhysicalDevice
{
    const ENTITY_NAME = 'EnregistreurCCTV';

    protected ?string $powerstart_name = null;
    protected ?string $facilities_id = null;
    protected array $cameravideo_list = [];
    protected ?string $redundancy = null;
    protected ?string $softwareinstance_id = null;
    protected ?string $softwareinstance_name = null;
    protected string $finalclass = 'EnregistreurCCTV';
    protected ?string $friendlyname = null;
    protected ?string $fqdn = null;
    protected ?string $obsolescence_flag = null;
    protected ?string $powerstart_id_friendlyname = null;
    protected ?string $powerstart_id_finalclass_recall = null;
    protected ?string $powerstart_id_obsolescence_flag = null;
    protected ?string $softwareinstance_id_friendlyname = null;
    protected ?string $softwareinstance_id_finalclass_recall = null;
    protected ?string $softwareinstance_id_obsolescence_flag = null;

    public function getPowerstartName(): ?string
    {
        return $this->powerstart_name;
    }

    public function setPowerstartName(?string $powerstart_name): EnregistreurCCTV
    {
        $this->powerstart_name = $powerstart_name;
        return $this;
    }

    public function getFacilitiesId(): ?string
    {
        return $this->facilities_id;
    }

    public function setFacilitiesId(?string $facilities_id): EnregistreurCCTV
    {
        $this->facilities_id = $facilities_id;
        return $this;
    }

    public function getCameravideoList(): array
    {
        return $this->cameravideo_list;
    }

    public function setCameravideoList(array $cameravideo_list): EnregistreurCCTV
    {
        $this->cameravideo_list = $cameravideo_list;
        return $this;
    }

    public function getRedundancy(): ?string
    {
        return $this->redundancy;
    }

    public function setRedundancy(?string $redundancy): EnregistreurCCTV
    {
        $this->redundancy = $redundancy;
        return $this;
    }

    public function getSoftwareinstanceId(): ?string
    {
        return $this->softwareinstance_id;
    }

    public function setSoftwareinstanceId(?string $softwareinstance_id): EnregistreurCCTV
    {
        $this->softwareinstance_id = $softwareinstance_id;
        return $this;
    }

    public function getSoftwareinstanceName(): ?string
    {
        return $this->softwareinstance_name;
    }

    public function setSoftwareinstanceName(?string $softwareinstance_name): EnregistreurCCTV
    {
        $this->softwareinstance_name = $softwareinstance_name;
        return $this;
    }

    public function getFinalclass(): string
    {
        return $this->finalclass;
    }

    public function setFinalclass(string $finalclass): EnregistreurCCTV
    {
        $this->finalclass = $finalclass;
        return $this;
    }

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }

    public function setFriendlyname(?string $friendlyname): EnregistreurCCTV
    {
        $this->friendlyname = $friendlyname;
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
     * @return BoitierGTC
     */
    public function setFqdn(?string $fqdn): EnregistreurCCTV
    {
        $this->fqdn = $fqdn;
        return $this;
    }

    public function getObsolescenceFlag(): ?string
    {
        return $this->obsolescence_flag;
    }

    public function setObsolescenceFlag(?string $obsolescence_flag): EnregistreurCCTV
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    public function getPowerstartIdFriendlyname(): ?string
    {
        return $this->powerstart_id_friendlyname;
    }

    public function setPowerstartIdFriendlyname(?string $powerstart_id_friendlyname): EnregistreurCCTV
    {
        $this->powerstart_id_friendlyname = $powerstart_id_friendlyname;
        return $this;
    }

    public function getPowerstartIdFinalclassRecall(): ?string
    {
        return $this->powerstart_id_finalclass_recall;
    }

    public function setPowerstartIdFinalclassRecall(?string $powerstart_id_finalclass_recall): EnregistreurCCTV
    {
        $this->powerstart_id_finalclass_recall = $powerstart_id_finalclass_recall;
        return $this;
    }

    public function getPowerstartIdObsolescenceFlag(): ?string
    {
        return $this->powerstart_id_obsolescence_flag;
    }

    public function setPowerstartIdObsolescenceFlag(?string $powerstart_id_obsolescence_flag): EnregistreurCCTV
    {
        $this->powerstart_id_obsolescence_flag = $powerstart_id_obsolescence_flag;
        return $this;
    }

    public function getSoftwareinstanceIdFriendlyname(): ?string
    {
        return $this->softwareinstance_id_friendlyname;
    }

    public function setSoftwareinstanceIdFriendlyname(?string $softwareinstance_id_friendlyname): EnregistreurCCTV
    {
        $this->softwareinstance_id_friendlyname = $softwareinstance_id_friendlyname;
        return $this;
    }

    public function getSoftwareinstanceIdFinalclassRecall(): ?string
    {
        return $this->softwareinstance_id_finalclass_recall;
    }

    public function setSoftwareinstanceIdFinalclassRecall(?string $softwareinstance_id_finalclass_recall): EnregistreurCCTV
    {
        $this->softwareinstance_id_finalclass_recall = $softwareinstance_id_finalclass_recall;
        return $this;
    }

    public function getSoftwareinstanceIdObsolescenceFlag(): ?string
    {
        return $this->softwareinstance_id_obsolescence_flag;
    }

    public function setSoftwareinstanceIdObsolescenceFlag(?string $softwareinstance_id_obsolescence_flag): EnregistreurCCTV
    {
        $this->softwareinstance_id_obsolescence_flag = $softwareinstance_id_obsolescence_flag;
        return $this;
    }
}
