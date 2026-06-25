<?php

namespace Zorille\itop\query_fetchers;

use Zorille\itop\data_models\UserRequest;

class UserRequestsFetcher extends TicketsFetcher
{
    protected static function getObjectName(): string
    {
        return 'UserRequest';
    }

    protected function getAssociatedModel(): string
    {
        return UserRequest::class;
    }
}