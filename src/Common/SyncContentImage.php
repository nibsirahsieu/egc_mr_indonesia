<?php 

namespace App\Common;

use App\Entity\FileUploaded;
use Doctrine\ORM\EntityManagerInterface;

final class SyncContentImage
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function sync(string $content, int $entityId): string
    {
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD);
        //$doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));

        $images = $doc->getElementsByTagName('img');
        
        /** @var \DOMElement $image */
        foreach ($images as $image) {
            $fileId = $image->getAttribute('data-file-id');
            $fileSynced = $image->getAttribute('data-file-synced');
            $needToBeSynced = '' !== $fileSynced && !filter_var($fileSynced, FILTER_VALIDATE_BOOLEAN) && '' !== $fileId;
            
            if (!$needToBeSynced) {
                continue;
            }

            if ($fileUploaded = $this->entityManager->getRepository(FileUploaded::class)->find((int) $fileId)) {
                $image->setAttribute('data-file-synced', 'true');

                $fileUploaded->setUsedBy($entityId);
                $this->entityManager->persist($fileUploaded);
                $this->entityManager->flush();
            }
        }
        
        return $doc->saveHTML();
    }
}
