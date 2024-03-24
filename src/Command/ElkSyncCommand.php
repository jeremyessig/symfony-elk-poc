<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Services\ElasticSearch\ElkPostManager;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\Psr18Client;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:elk-sync',
    description: 'Add a short description for your command',
)]
class ElkSyncCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client,
        private PostRepository $postRepository,
        private ElkPostManager $elkPostManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        // $this
        //     ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //     ->addOption('index', null, InputOption::VALUE_NONE, 'Option description')
        //     ->addOption('mapping', null, InputOption::VALUE_NONE, 'Option description')
        //     ->addOption('add', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);





        try {
            $response = $this->elkPostManager->removeIndex();
        } catch (ClientResponseException $e) {
            // Si l'index n'existe pas, on catch l'erreur et continue le programme
            if ($e->getCode() !== 404) {
                throw $e;
            }
        }

        $response = $this->elkPostManager->createIndex();

        /** @var array<Post> $posts */
        $posts = $this->postRepository->findAll();
        foreach ($posts as $post) {
            $response = $this->elkPostManager->indexPost($post);
            dump($response->asArray());
        }


        $io->success('New index created !');

        return Command::SUCCESS;
    }
}
