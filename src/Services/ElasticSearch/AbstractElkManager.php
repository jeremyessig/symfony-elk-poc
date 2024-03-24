<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\ElasticSearch;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

abstract class AbstractElkManager
{

    protected $client;

    public function __construct()
    {
        $this->client = $this->createClient();
    }

    protected function createClient()
    {
        return ClientBuilder::create()
            ->setHosts(['http://localhost:9200'])
            ->build();
    }
}
