<?php 

namespace App\Common;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final readonly class OasisApiClient
{
    public function __construct(
        private HttpClientInterface $httpClient,
        #[Autowire(env: 'APP_OASIS_API_URL')]
        private string $apiUrl,
        #[Autowire(env: 'APP_OASIS_API_KEY')]
        private string $apiKey
    )
    {   
    }

    private function withHeaders(array $headers = []): array
    {
        return [
            'headers' => array_merge([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-API-KEY' => $this->apiKey,
            ], $headers),
        ];
    }

    public function post(string $path, array $data = []): ResponseInterface
    {
        return $this->httpClient->request(
            'POST', rtrim($this->apiUrl, '/') . $path, 
            array_merge(
                $this->withHeaders(),
                ['json' => $data]
            )
        );
    }
}
