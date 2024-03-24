<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\MessageHandler;

use App\Entity\Post;
use App\Message\ElkIndexPostMessage;
use App\Repository\PostRepository;
use App\Services\ElasticSearch\ElkPostManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ElkIndexPostMessageHandler
{

    public function __construct(
        private ElkPostManager $elkPostManager,
        private PostRepository $postRepository,
        private EntityManagerInterface $em
    ) {
    }

    public function __invoke(ElkIndexPostMessage $message)
    {
        $postId = $message->getPostId();
        dump($postId); // Affiche l'ID correct

        /** @var Post $post */
        $post = $this->postRepository->find($postId);
        dump($post); // Affiche null en async alors que l'article existe en BDD
        $this->elkPostManager->indexPost($post);
    }
}
