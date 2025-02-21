<?php 

namespace App\Controller\Admin;

use App\Common\BreadcrumbBuilder;
use App\Controller\BaseController;
use App\Entity\HeaderFooterScript;
use App\Repository\HeaderFooterScriptRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/header-footer-scripts', name: 'app_admin_header_footer_scripts_')]
final class HeaderFooterScriptController extends BaseController
{
    public function __construct(private readonly HeaderFooterScriptRepository $repository, private readonly EntityManagerInterface $em)
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $headerFooterScript = $this->repository->findOne();

        $breadcrumbBuilder->add('Header & Footer Script');

        return $this->render('admin/headerFooterScript/index.html.twig', [
            'headerFooterScript' => $headerFooterScript
        ]);
    }

    #[Route('/update', name: 'update', methods: ['POST'])]
    public function update(Request $request): Response
    {
        $headerFooterScript = $this->repository->findOne();
        if (!$headerFooterScript) {
            $headerFooterScript = new HeaderFooterScript();
        }

        $headerFooterScript->setHeaderScript($request->request->get('header_script'));
        $headerFooterScript->setFooterScript($request->request->get('footer_script'));
        
        $this->em->persist($headerFooterScript);
        $this->em->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
