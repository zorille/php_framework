<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

class Order extends data_model
{
    const ENTITY_NAME = 'Order';

    protected ?string $id = null;
    protected ?string $sOF_Number__c = null;
    protected ?string $customer_Expected_Delivery_Date__c = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getSOFNumberC(): ?string
    {
        return $this->sOF_Number__c;
    }
    public function setSOFNumberC(?string $sOF_Number__c): self
    {
        $this->sOF_Number__c = $sOF_Number__c;
        return $this;
    }

    public function getCustomerExpectedDeliveryDateC(): ?string
    {
        return $this->customer_Expected_Delivery_Date__c;
    }
    public function setCustomerExpectedDeliveryDateC(?string $customer_Expected_Delivery_Date__c): self
    {
        $this->customer_Expected_Delivery_Date__c = $customer_Expected_Delivery_Date__c;
        return $this;
    }
}