<?php 

namespace App\Command;

use App\Entity\Post;
use App\Entity\RedirectUrl;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
    name: 'app:insights:redirect-url',
    description: 'Generate redirect url for the new format'
)]
final class InsightRedirectUrlCommand extends Command
{
    public function __construct(
        private readonly PostRepository $postRepository, 
        private readonly EntityManagerInterface $em,
        #[Autowire(env: 'APP_IMPORT_URL')]
        private readonly string $importUrl
    ) 
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var array<Post> */
        $insights = $this->postRepository->findAll();
        foreach ($insights as $insight) {
            $redirectUrl = new RedirectUrl();
            $redirectUrl->setOldUrl(sprintf('%s/insights/%s', $this->importUrl, $insight->getSlug()));
            $redirectUrl->setNewUrl(sprintf('%s/insights/%s/%s', $this->importUrl, $insight->getType()->getSlug(), $insight->getSlug()));
            $this->em->persist($redirectUrl);
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}
