<?php

namespace BitWaspNew\Bitcoin\Crypto\Random;

use BitWaspNew\Bitcoin\Crypto\EcAdapter\Adapter\EcAdapterInterface;
use BitWaspNew\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Buffertools\Buffer;
use BitWasp\Buffertools\BufferInterface;
use Mdanter\Ecc\Crypto\Key\PrivateKey as MdPrivateKey;
use Mdanter\Ecc\Random\HmacRandomNumberGenerator;
use Mdanter\Ecc\Random\RandomGeneratorFactory;

class Rfc6979 implements RbgInterface
{

    /**
     * @var EcAdapterInterface
     */
    private $ecAdapter;

    /**
     * @var HmacRandomNumberGenerator
     */
    private $hmac;

    /**
     * @param EcAdapterInterface $ecAdapter
     * @param PrivateKeyInterface $privateKey
     * @param BufferInterface $messageHash
     * @param string $algo
     */
    public function __construct(
        EcAdapterInterface $ecAdapter,
        PrivateKeyInterface $privateKey,
        BufferInterface $messageHash,
        $algo = 'sha256'
    ) {
        $mdPk = new MdPrivateKey($ecAdapter->getMath(), $ecAdapter->getGenerator(), gmp_init($privateKey->getInt(), 10));
        $this->ecAdapter = $ecAdapter;
        $this->hmac = RandomGeneratorFactory::getHmacRandomGenerator($mdPk, gmp_init($messageHash->getInt(), 10), $algo);
    }

    /**
     * @param int $bytes
     * @return Buffer
     */
    public function bytes($bytes)
    {
        $integer = $this->hmac->generate($this->ecAdapter->getGenerator()->getOrder());
        return Buffer::int(gmp_strval($integer, 10), $bytes, $this->ecAdapter->getMath());
    }
}
