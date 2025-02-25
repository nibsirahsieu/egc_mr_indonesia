<?php 

namespace App\Controller\Admin\UrlRedirection;

use App\Controller\BaseController;
use App\Entity\RedirectUrl;
use App\Repository\RedirectUrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenSpout\Reader\CSV\Options;
use OpenSpout\Reader\CSV\Reader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ImportCsvController extends BaseController
{
    public function __construct(
        private readonly RedirectUrlRepository $redirectUrlRepository,
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/url-redirections/import', name: "app_admin_url_redirections_import", methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('file');

        $options = new Options();
        $options->FIELD_DELIMITER = ";";
        
        $reader = new Reader($options);
        $reader->open($file->getPathname());

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                if ($index === 1) {
                    //skip header
                    continue;
                }
                $cells = $row->toArray();
                $redirectUrl = $this->redirectUrlRepository->findOneBy(['oldUrl' => $cells[0]]);
                if (!$redirectUrl) {
                    $redirectUrl = new RedirectUrl();
                }
                $redirectUrl->setOldUrl($cells[0]);
                $redirectUrl->setNewUrl($cells[1]);

                $this->em->persist($redirectUrl);
            }
        }

        $this->em->flush();
        $reader->close();

        $this->addFlash('success', 'Data has been imported.');

        return $this->redirectToRoute('app_admin_url_redirections_index');
    }
}
