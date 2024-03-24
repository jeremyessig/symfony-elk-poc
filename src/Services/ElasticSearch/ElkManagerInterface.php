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

interface ElkManagerInterface
{
    /**
     * @return Elasticsearch|Promise
     */
    public function createIndex();

    /**
     * @return Elasticsearch|Promise
     */
    public function removeIndex();
}
