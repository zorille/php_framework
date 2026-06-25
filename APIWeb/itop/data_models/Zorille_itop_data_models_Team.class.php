<?php

namespace Zorille\itop\data_models;

class Team extends Contact
{
    const ENTITY_NAME = 'Team';

    protected array $persons_list = [];
    protected array $tickets_list = [];
    protected string $finalclass = 'Team';

    public function getPersonsList(): array
    {
        return $this->persons_list;
    }
    public function setPersonsList(array $persons_list): Team
    {
        $this->persons_list = $persons_list;
        return $this;
    }

    public function getTicketsList(): array
    {
        return $this->tickets_list;
    }
    public function setTicketsList(array $tickets_list): Team
    {
        $this->tickets_list = $tickets_list;
        return $this;
    }

    public function getCodeClient(): string
    {
        preg_match_all(
            '/(?<code_client>[0-9S]+)/m',
            $this->getOrgIdFriendlyName(),
            $matches,
            PREG_SET_ORDER
        );

        if (empty($matches[0])) {
            return "";
        }

        return $matches[0]['code_client'];
    }
}