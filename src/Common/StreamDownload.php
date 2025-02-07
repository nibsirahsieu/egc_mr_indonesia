<?php 

namespace App\Common;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class StreamDownload
{
    public function __construct(private FilesystemOperator $publicUploadsFilesystem)
    {
    }

    /**
     *
     * @param string $path path relative to the public upload directory
     * @param string $contentType
     * @param string $filename
     * @param string $disposition attachment or inline
     * @return StreamedResponse
     */
    public function getResponse(string $path, string $contentType, string $filename, string $disposition = 'attachment'): StreamedResponse
    {
        $stream = $this->publicUploadsFilesystem->readStream($path);
        
        return new StreamedResponse(
            function () use ($stream) {
                while (!feof($stream)) {
                    echo fread($stream, 1024);
                    flush();
                }
                fclose($stream);
            },
            Response::HTTP_OK,
            [
                'Content-Transfer-Encoding', 'binary',
                'Content-Type' => $contentType,
                'Content-Disposition' => sprintf('%s; filename="%s"', $disposition, $filename),
                'Content-Length' => fstat($stream)['size'],
            ]
        );
    }
}
