<?php

namespace Zorille\itop\data_models;

use Zorille\itop\data_model;

class LnkCrmAssetsToFunctionalCI extends data_model
{
    protected ?string $id = null;
    protected ?string $crmasset_id = null;
    protected ?string $functionalci_id = null;
    protected ?string $friendlyname = null;
    protected ?string $crmassets_id_friendlyname = null;
    protected ?string $crmassets_id_obsolescence_flag = null;
    protected ?string $functionalci_id_friendlyname = null;
    protected ?string $functionalci_id_finalclass_recall = null;
    protected ?string $functionalci_id_obsolescence_flag = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): LnkCrmAssetsToFunctionalCI
    {
        $this->id = $id;
        return $this;
    }

    public function getCrmassetId(): ?string
    {
        return $this->crmasset_id;
    }

    public function setCrmassetId(?string $crmasset_id): LnkCrmAssetsToFunctionalCI
    {
        $this->crmasset_id = $crmasset_id;
        return $this;
    }

    public function getFunctionalciId(): ?string
    {
        return $this->functionalci_id;
    }

    public function setFunctionalciId(?string $functionalci_id): LnkCrmAssetsToFunctionalCI
    {
        $this->functionalci_id = $functionalci_id;
        return $this;
    }

    public function getFriendlyname(): ?string
    {
        return $this->friendlyname;
    }

    public function setFriendlyname(?string $friendlyname): LnkCrmAssetsToFunctionalCI
    {
        $this->friendlyname = $friendlyname;
        return $this;
    }

    public function getCrmassetsIdFriendlyname(): ?string
    {
        return $this->crmassets_id_friendlyname;
    }

    public function setCrmassetsIdFriendlyname(?string $crmassets_id_friendlyname): LnkCrmAssetsToFunctionalCI
    {
        $this->crmassets_id_friendlyname = $crmassets_id_friendlyname;
        return $this;
    }

    public function getCrmassetsIdObsolescenceFlag(): ?string
    {
        return $this->crmassets_id_obsolescence_flag;
    }

    public function setCrmassetsIdObsolescenceFlag(?string $crmassets_id_obsolescence_flag): LnkCrmAssetsToFunctionalCI
    {
        $this->crmassets_id_obsolescence_flag = $crmassets_id_obsolescence_flag;
        return $this;
    }

    public function getFunctionalciIdFriendlyname(): ?string
    {
        return $this->functionalci_id_friendlyname;
    }

    public function setFunctionalciIdFriendlyname(?string $functionalci_id_friendlyname): LnkCrmAssetsToFunctionalCI
    {
        $this->functionalci_id_friendlyname = $functionalci_id_friendlyname;
        return $this;
    }

    public function getFunctionalciIdFinalclassRecall(): ?string
    {
        return $this->functionalci_id_finalclass_recall;
    }

    public function setFunctionalciIdFinalclassRecall(?string $functionalci_id_finalclass_recall): LnkCrmAssetsToFunctionalCI
    {
        $this->functionalci_id_finalclass_recall = $functionalci_id_finalclass_recall;
        return $this;
    }

    public function getFunctionalciIdObsolescenceFlag(): ?string
    {
        return $this->functionalci_id_obsolescence_flag;
    }

    public function setFunctionalciIdObsolescenceFlag(?string $functionalci_id_obsolescence_flag): LnkCrmAssetsToFunctionalCI
    {
        $this->functionalci_id_obsolescence_flag = $functionalci_id_obsolescence_flag;
        return $this;
    }
}