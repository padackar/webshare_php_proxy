<?php

namespace omrva\WebsharePhpProxy\Models;

class ProxyServer
{
    public string $id, $username, $password, $proxy_address;
    public int $port, $asn_number;
    public bool $valid, $high_country_confidence;
    public $last_verification, $created_at;
    public string $country_code, $city_name, $asn_name;

    public function getId(): string
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getProxyAddress(): string
    {
        return $this->proxy_address;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getAsnNumber(): int
    {
        return $this->asn_number;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function isHighCountryConfidence(): bool
    {
        return $this->high_country_confidence;
    }

    /**
     * @return mixed
     */
    public function getLastVerification()
    {
        return $this->last_verification;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getCountryCode(): string
    {
        return $this->country_code;
    }

    public function getCityName(): string
    {
        return $this->city_name;
    }

    public function getAsnName(): string
    {
        return $this->asn_name;
    }
}