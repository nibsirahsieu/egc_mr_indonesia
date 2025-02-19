<?php 

namespace App\Common;

use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
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
        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm = new Sha256();
        $signingKey = InMemory::plainText($this->secret);

        $now  = new \DateTimeImmutable();
        $tokenBuilder
            // Configures the issuer (iss claim)
            ->issuedBy($this->baseUrl)
            ->issuedAt($now)
            ->expiresAt($now->modify($expiresAt));

        foreach ($claims as $key => $claim) {
            $tokenBuilder = $tokenBuilder->withClaim($key, $claim);
        }

        $token = $tokenBuilder->getToken($algorithm, $signingKey);

        return $token->toString();
    }

    public function parse(string $jwt): UnencryptedToken
    {
        $parser = new Parser(new JoseEncoder());
        
        try {
            return $parser->parse($jwt);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            throw $e;
        }
    }
}
