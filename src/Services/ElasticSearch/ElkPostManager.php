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

use App\Entity\Post;

class ElkPostManager extends AbstractElkManager implements ElkManagerInterface
{

    /**
     * @return Elasticsearch|Promise
     */
    public function createIndex()
    {
        $params = [
            'index' => 'blog',
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'id' => [
                            'type' => 'integer'
                        ],
                        'title' => [
                            'type' => 'text'
                        ],
                        'summary' => [
                            'type' => 'text'
                        ],
                        'content' => [
                            'type' => 'text'
                        ],
                    ]
                ]
            ]
        ];

        return $this->client->indices()->create($params);
    }


    /**
     * @return Elasticsearch|Promise
     */
    public function removeIndex()
    {
        $params = ['index' => 'blog'];
        return $this->client->indices()->delete($params);
    }


    /**
     * @return Elasticsearch|Promise
     */
    public function indexPost(Post $post)
    {
        $params = [
            'index' => 'blog',
            'body'  => [
                'id'    => $post->getId(),
                'title' => $post->getTitle(),
                'summary' => $post->getSummary(),
                'content' => $post->getContent()
            ]
        ];

        // Document will be indexed to my_index/_doc/my_id
        return $this->client->index($params);
    }
}
