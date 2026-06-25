<?php

namespace Zorille\salesforce;

use Zorille\framework as Core;

class data_model extends Core\data_model
{
    private string $salesforce_serveur;

    protected static function formatArrayKey($property): string
    {
        return is_string($property) ? ucfirst($property) : ucfirst($property->getName());
    }

    protected function getSalesforceServeur(): string
    {
        return $this->salesforce_serveur;
    }
    public function setSalesforceServeur(string $salesforce_serveur): self
    {
        $this->salesforce_serveur = $salesforce_serveur;
        return $this;
    }
}
