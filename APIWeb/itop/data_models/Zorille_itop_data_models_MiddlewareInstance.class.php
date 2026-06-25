<?php

namespace Zorille\itop\data_models;

class MiddlewareInstance extends FunctionalCI
{
    const ENTITY_NAME = 'MiddlewareInstance';

    protected ?string $middleware_id = null;
    protected ?string $middleware_name = null;
    protected ?string $friendlyname = null;
    protected string $finalclass = 'MiddlewareInstance';
    protected ?string $obsolescence_flag = null;
    protected ?string $middleware_id_friendlyname = null;
    protected ?string $middleware_id_obsolescence_flag = null;

    /**
     * @return string|null
     */
    public function getMiddlewareId(): ?string
    {
        return $this->middleware_id;
    }

    /**
     * @param string|null $middleware_id
     * @return MiddlewareInstance
     */
    public function setMiddlewareId(?string $middleware_id): MiddlewareInstance
    {
        $this->middleware_id = $middleware_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMiddlewareName(): ?string
    {
        return $this->middleware_name;
    }

    /**
     * @param string|null $middleware_name
     * @return MiddlewareInstance
     */
    public function setMiddlewareName(?string $middleware_name): MiddlewareInstance
    {
        $this->middleware_name = $middleware_name;
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
     * @return MiddlewareInstance
     */
    public function setObsolescenceFlag(?string $obsolescence_flag): MiddlewareInstance
    {
        $this->obsolescence_flag = $obsolescence_flag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMiddlewareIdFriendlyname(): ?string
    {
        return $this->middleware_id_friendlyname;
    }

    /**
     * @param string|null $middleware_id_friendlyname
     * @return MiddlewareInstance
     */
    public function setMiddlewareIdFriendlyname(?string $middleware_id_friendlyname): MiddlewareInstance
    {
        $this->middleware_id_friendlyname = $middleware_id_friendlyname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMiddlewareIdObsolescenceFlag(): ?string
    {
        return $this->middleware_id_obsolescence_flag;
    }

    /**
     * @param string|null $middleware_id_obsolescence_flag
     * @return MiddlewareInstance
     */
    public function setMiddlewareIdObsolescenceFlag(?string $middleware_id_obsolescence_flag): MiddlewareInstance
    {
        $this->middleware_id_obsolescence_flag = $middleware_id_obsolescence_flag;
        return $this;
    }
}