<?php

namespace Zorille\itop\data_models;

class FiberRocade extends NetworkInterface {
    public ?string $fiber_quantity = null;
    public ?string $location_name = null;
    public ?string $firstfiberpanel_id = null;
    public ?string $firstfiberpanel_name = null;
    public ?string $location_dest_id = null;
    public ?string $location_dest_name = null;
    public ?string $secondfiberpanel_id = null;
    public ?string $secondfiberpane_name = null;
    public ?string $fibermode = null;
    public array $pdl_list = [];
    public ?string $firstfiberpanel_id_friendlyname = null;
    public ?string $firstfiberpanel_id_obsolescence_flag = null;
    public ?string $location_dest_id_friendlyname = null;
    public ?string $location_dest_id_obsolescence_flag = null;
    public ?string $secondfiberpanel_id_friendlyname = null;
    public ?string $secondfiberpanel_id_obsolescence_flag = null;

    public string $finalclass = 'FiberRocade';

    public function getFiberQuantity(): ?string
    {
        return $this->fiber_quantity;
    }

    public function setFiberQuantity(?string $fiber_quantity): static
    {
        $this->fiber_quantity = $fiber_quantity;
        return $this;
    }

    public function getLocationName(): ?string
    {
        return $this->location_name;
    }

    public function setLocationName(?string $location_name): static
    {
        $this->location_name = $location_name;
        return $this;
    }

    public function getFirstfiberpanelId(): ?string
    {
        return $this->firstfiberpanel_id;
    }

    public function setFirstfiberpanelId(?string $firstfiberpanel_id): static
    {
        $this->firstfiberpanel_id = $firstfiberpanel_id;
        return $this;
    }

    public function getFirstfiberpanelName(): ?string
    {
        return $this->firstfiberpanel_name;
    }

    public function setFirstfiberpanelName(?string $firstfiberpanel_name): static
    {
        $this->firstfiberpanel_name = $firstfiberpanel_name;
        return $this;
    }

    public function getLocationDestId(): ?string
    {
        return $this->location_dest_id;
    }

    public function setLocationDestId(?string $location_dest_id): static
    {
        $this->location_dest_id = $location_dest_id;
        return $this;
    }

    public function getLocationDestName(): ?string
    {
        return $this->location_dest_name;
    }

    public function setLocationDestName(?string $location_dest_name): static
    {
        $this->location_dest_name = $location_dest_name;
        return $this;
    }

    public function getSecondfiberpanelId(): ?string
    {
        return $this->secondfiberpanel_id;
    }

    public function setSecondfiberpanelId(?string $secondfiberpanel_id): static
    {
        $this->secondfiberpanel_id = $secondfiberpanel_id;
        return $this;
    }

    public function getSecondfiberpaneName(): ?string
    {
        return $this->secondfiberpane_name;
    }

    public function setSecondfiberpaneName(?string $secondfiberpane_name): static
    {
        $this->secondfiberpane_name = $secondfiberpane_name;
        return $this;
    }

    public function getFibermode(): ?string
    {
        return $this->fibermode;
    }

    public function setFibermode(?string $fibermode): static
    {
        $this->fibermode = $fibermode;
        return $this;
    }

    public function getPdlList(): array
    {
        return $this->pdl_list;
    }

    public function setPdlList(array $pdl_list): static
    {
        $this->pdl_list = $pdl_list;
        return $this;
    }

    public function getFirstfiberpanelIdFriendlyname(): ?string
    {
        return $this->firstfiberpanel_id_friendlyname;
    }

    public function setFirstfiberpanelIdFriendlyname(?string $firstfiberpanel_id_friendlyname): static
    {
        $this->firstfiberpanel_id_friendlyname = $firstfiberpanel_id_friendlyname;
        return $this;
    }

    public function getFirstfiberpanelIdObsolescenceFlag(): ?string
    {
        return $this->firstfiberpanel_id_obsolescence_flag;
    }

    public function setFirstfiberpanelIdObsolescenceFlag(?string $firstfiberpanel_id_obsolescence_flag): static
    {
        $this->firstfiberpanel_id_obsolescence_flag = $firstfiberpanel_id_obsolescence_flag;
        return $this;
    }

    public function getLocationDestIdFriendlyname(): ?string
    {
        return $this->location_dest_id_friendlyname;
    }

    public function setLocationDestIdFriendlyname(?string $location_dest_id_friendlyname): static
    {
        $this->location_dest_id_friendlyname = $location_dest_id_friendlyname;
        return $this;
    }

    public function getLocationDestIdObsolescenceFlag(): ?string
    {
        return $this->location_dest_id_obsolescence_flag;
    }

    public function setLocationDestIdObsolescenceFlag(?string $location_dest_id_obsolescence_flag): static
    {
        $this->location_dest_id_obsolescence_flag = $location_dest_id_obsolescence_flag;
        return $this;
    }

    public function getSecondfiberpanelIdFriendlyname(): ?string
    {
        return $this->secondfiberpanel_id_friendlyname;
    }

    public function setSecondfiberpanelIdFriendlyname(?string $secondfiberpanel_id_friendlyname): static
    {
        $this->secondfiberpanel_id_friendlyname = $secondfiberpanel_id_friendlyname;
        return $this;
    }

    public function getSecondfiberpanelIdObsolescenceFlag(): ?string
    {
        return $this->secondfiberpanel_id_obsolescence_flag;
    }

    public function setSecondfiberpanelIdObsolescenceFlag(?string $secondfiberpanel_id_obsolescence_flag): static
    {
        $this->secondfiberpanel_id_obsolescence_flag = $secondfiberpanel_id_obsolescence_flag;
        return $this;
    }
}