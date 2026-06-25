<?php

namespace Zorille\salesforce;

use Zorille\framework\options;
use Zorille\framework\SingletonFactory;

interface connexion_connector
{
    public function getListOptions(): options;
    public function getData(): SingletonFactory;
}