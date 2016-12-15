<?php

namespace BitWaspNew\Bitcoin\Crypto\EcAdapter\Serializer\Key;

use BitWaspNew\Bitcoin\Crypto\EcAdapter\Key\PrivateKeyInterface;
use BitWasp\Buffertools\BufferInterface;

interface PrivateKeySerializerInterface
{
    /**
     * @param PrivateKeyInterface $privateKey
     * @return BufferInterface
     */
    public function serialize(PrivateKeyInterface $privateKey);

    /**
     * @return $this
     */
    public function setNextCompressed();

    /**
     * @param string|BufferInterface $data
     * @return PrivateKeyInterface
     */
    public function parse($data);
}
