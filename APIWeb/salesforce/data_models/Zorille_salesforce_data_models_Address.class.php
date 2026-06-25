<?php

namespace Zorille\salesforce\data_models;

use Zorille\salesforce\data_model;

/**
 * @method static self convert(self|array $address)
 */
class Address extends data_model
{
    const ENTITY_NAME = 'Address';

    protected ?string $city = '';
    protected ?string $country = '';
    protected ?string $geocodeAccuracy = null;
    protected ?float $latitude = null;
    protected ?float $longitude = null;
    protected ?string $postalCode = '';
    protected ?string $state = null;
    protected ?string $street = '';

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getGeocodeAccuracy(): ?string
    {
        return $this->geocodeAccuracy;
    }

    public function setGeocodeAccuracy(?string $geocodeAccuracy): self
    {
        $this->geocodeAccuracy = $geocodeAccuracy;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }
}