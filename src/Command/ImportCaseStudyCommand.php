<?php 

namespace App\Command;

use App\CommandService\CaseStudyCommandService;
use App\CommandService\RedirectUrlCommandService;
use App\CommandService\UploadCommandService;
use App\Common\UploadNamer;
use App\Entity\UploadPurpose;
use App\Message\CreateBlurhash;
use App\Request\CaseStudyRequest;
use App\Request\RedirectUrlRequest;
use App\Request\UploadRequest;
use Oneup\UploaderBundle\Uploader\File\FilesystemFile;
use Oneup\UploaderBundle\Uploader\Storage\StorageInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:data:import-case-studies',
    description: 'Import case studies from json file'
)]
final class ImportCaseStudyCommand extends Command
{
    public function __construct(
        private readonly UploadCommandService $uploadCommandService,
        private readonly CaseStudyCommandService $caseStudyCommandService,
        private readonly RedirectUrlCommandService $redirectUrlCommandService,
        private readonly StorageInterface $publicLocalStorage,
        private readonly MessageBusInterface $messageBus,
        #[Autowire(env: 'APP_IMPORT_URL')]
        private string $importUrl,
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir
    )
    {
        parent::__construct();    
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $jsonFile = "{$this->projectDir}/case_studies.json";
        $data = json_decode(file_get_contents($jsonFile), true);
        
        $this->redirectUrlCommandService->create(new RedirectUrlRequest(
            sprintf('%s/our-projects/', $this->importUrl),
            sprintf('%s/case-studies', $this->importUrl)
        ));
        
        foreach ($data as $item) {
            $originalName = basename($item['image']);
            $file = $this->saveExternalImage($item['image'], null);

            $uploadView = $this->uploadCommandService->create(new UploadRequest(
                $file->getBasename(),
                $originalName,
                $file->getMimeType(),
                $file->getSize(),
                $file->getExtension(),
                UploadPurpose::CASE_STUDY->value
            ));

            $this->messageBus->dispatch(new CreateBlurhash($uploadView->getId()));

            $this->caseStudyCommandService->create(new CaseStudyRequest(
                $item['title'],
                $item['slug'],
                $item['clients'],
                $item['issues'],
                $item['solution'],
                $item['approach'],
                $item['recommendations'],
                $item['roi'],
                "",
                "",
                1,
                [],
                (string) $uploadView->getId(),
                new \DateTimeImmutable($item['published_at'])
            ));

            $this->redirectUrlCommandService->create(new RedirectUrlRequest(
                sprintf('%s/projects/%s/', $this->importUrl, $item['slug']),
                sprintf('%s/case-studies/%s', $this->importUrl, $item['slug'])
            ));
        }

        return Command::SUCCESS;
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
