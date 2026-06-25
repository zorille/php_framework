<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\Ticket;
use Zorille\itop\query_builder;

class TicketsFetcher extends query_builder
{
    protected static function getObjectName(): string
    {
        return 'Ticket';
    }

    protected function getAssociatedModel(): string
    {
        return Ticket::class;
    }
}