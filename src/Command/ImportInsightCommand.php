<?php 

namespace App\Command;

use App\CommandService\PostCommandService;
use App\CommandService\RedirectUrlCommandService;
use App\CommandService\UploadCommandService;
use App\Common\UploadHelper;
use App\Common\UploadNamer;
use App\Entity\PostStatus;
use App\Entity\UploadPurpose;
use App\Message\CreateBlurhash;
use App\QueryService\PostQueryService;
use App\Repository\PostTypeRepository;
use App\Request\PostRequest;
use App\Request\RedirectUrlRequest;
use App\Request\UploadRequest;
use Oneup\UploaderBundle\Uploader\File\FileInterface;
use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Oneup\UploaderBundle\Uploader\Storage\StorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:data:import-insights',
    description: 'Import insights from Wordpress'
)]
final class ImportInsightCommand extends Command
{
    public function __construct(
        private readonly PostTypeRepository $postTypeRepository,
        private readonly HttpClientInterface $httpClient,
        private readonly StorageInterface $publicLocalStorage,
        private readonly UploadCommandService $uploadCommandService,
        private readonly PostCommandService $postCommandService,
        private readonly RedirectUrlCommandService $redirectUrlCommandService,
        private readonly MessageBusInterface $messageBus,
        private readonly PostQueryService $postQueryService,
        private readonly UploadHelper $uploadHelper,
        #[Autowire(env: 'APP_IMPORT_URL')]
        private string $importUrl,
    ) 
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '-1');
        
        $endPoints = $this->importUrl . '/wp-json/wp/v2/posts?per_page=100&page=1&_fields=id,title,slug,date_gmt,modified_gmt,excerpt,content,_links,_embedded&_links=wp:featuredmedia&_embed=wp:featuredmedia';
        $response = $this->httpClient->request('GET', $endPoints);
        if (200 !== $response->getStatusCode()) {
            return Command::SUCCESS;
        }
        
        $articleType = $this->postTypeRepository->findOneBy(['name' => 'Article']);
        if (!$articleType) {
            $output->writeln('Err: Missing post types.');

            return Command::SUCCESS;
        }

        $insights = json_decode($response->getContent(), true);
        foreach ($insights as $insight) {
            $slug = $insight['slug'];
            if ($this->postQueryService->slugExists($slug, null)) {
                continue;
            }

            $output->writeln(sprintf("Import article %s", $slug));

            //save header image
            $imageLink = $insight['_embedded']['wp:featuredmedia'][0]['source_url'];
            $originalName = basename($imageLink);
            $file = $this->saveExternalImage($imageLink, $insight['_embedded']['wp:featuredmedia'][0]['mime_type']);

            $uploadView = $this->uploadCommandService->create(new UploadRequest(
                $file->getBasename(),
                $originalName,
                $file->getMimeType(),
                $file->getSize(),
                $file->getExtension(),
                UploadPurpose::POST->value
            ));

            $this->messageBus->dispatch(new CreateBlurhash($uploadView->getId()));

            $postContent = $this->extractAndUploadImagesFromContent($insight['content']['rendered']);
            $this->postCommandService->create(new PostRequest(
                $insight['title']['rendered'],
                $slug,
                $insight['excerpt']['rendered'],
                $postContent,
                null,
                null,
                $uploadView->getId(),
                [],
                $articleType->getId(),
                null,
                new \DateTimeImmutable($insight['date_gmt']),
                'Marketing & Communications',
                null,
                PostStatus::PUBLISHED->value
            ));

            $this->redirectUrlCommandService->create(new RedirectUrlRequest(
                sprintf('%s/%s/', $this->importUrl, $slug),
                sprintf('%s/insights/%s/%s', $this->importUrl, $articleType->getSlug(), $slug)
            ));
        }
        
        return Command::SUCCESS;
    }

    private function extractAndUploadImagesFromContent(string $content): string
    {
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD);
        $images = $doc->getElementsByTagName('img');

        if (count($images) === 0) {
            return $content;
        }

        /** @var \DOMElement $image */
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            if (!str_contains($src, $this->importUrl)) {
                $src = sprintf('%s/%s', $this->importUrl, $src);
            }

            $file = $this->saveExternalImage($src, null);
            $uploadView = $this->uploadCommandService->create(new UploadRequest(
                $file->getBasename(),
                basename($src),
                $file->getMimeType(),
                $file->getSize(),
                $file->getExtension(),
                UploadPurpose::POST->value
            ));

            $this->messageBus->dispatch(new CreateBlurhash($uploadView->getId()));
            
            $newSrc = $this->uploadHelper->getRelativeUrl($uploadView->getRelativePath());
            $image->setAttribute('src', $newSrc);
            $image->setAttribute('data-file-id', $uploadView->getId());
            $image->setAttribute('data-file-synced', false);
            $image->setAttribute('data-lazy-load', true);
            $image->removeAttribute('loading');
            $image->removeAttribute('decoding');
            $image->removeAttribute('width');
            $image->removeAttribute('height');
            $image->removeAttribute('sizes');
            $image->removeAttribute('srcset');
        }

        return $doc->saveHTML();
    }

    /**
     *
     * @param string $imageUrl
     * @param string $mimeType|null
     * @return FileInterface|File
     */
    private function saveExternalImage(string $imageUrl, ?string $mimeType)
    {
        $namer = new UploadNamer();
        $tempFile = tempnam(sys_get_temp_dir(), '_upload_');;
        file_put_contents($tempFile, file_get_contents($imageUrl));
        
        $originalName = basename($imageUrl);
        $uploadedFile = new UploadedFile($tempFile, $originalName, $mimeType, null, true);
        $filesystemFile = new FilesystemFile($uploadedFile);
        $filename = $namer->name($filesystemFile);

        return $this->publicLocalStorage->upload($filesystemFile, $filename);
    }
}
