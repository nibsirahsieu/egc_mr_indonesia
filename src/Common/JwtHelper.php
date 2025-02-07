<?php 

namespace App\Common;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class JwtHelper
{
    public function __construct(
        #[Autowire(env: 'APP_BASE_URL')]
        private string $baseUrl,
        #[Autowire(env: 'APP_SECRET')]
        private string $secret,
    )
    {
    }

    /**
     * Generate JWT token
     *
     * @param array<string, mixed> $claims
     * @param string $expiresAt
     * @return string
     */
    public function generate(array $claims, string $expiresAt = '+1 month'): string
    {
        $iat = new \DateTimeImmutable(); 
        $exp = $iat->modify($expiresAt);

        $payload = [
            'iss' => $this->baseUrl,
            'iat' => $iat->getTimestamp(),
            'exp' => $exp->getTimestamp()
        ];
        foreach ($claims as $key => $claim) {
            $payload[$key] = $claim;
        }

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    /**
     * parse JWT
     *
     * @param string $jwt
     * @return stdClass The JWT's payload as a PHP object
     */
    public function parse(string $jwt): object
    {
        //JWT::$leeway = 60;
        return JWT::decode($jwt, new Key($this->secret, 'HS256'));
    }
}
