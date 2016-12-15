<?php

namespace BitWaspNew\Bitcoin\Serializer\Block;

use BitWasp\Buffertools\Exceptions\ParserOutOfRange;
use BitWasp\Buffertools\Parser;
use BitWaspNew\Bitcoin\Block\BlockHeader;
use BitWaspNew\Bitcoin\Block\BlockHeaderInterface;
use BitWasp\Buffertools\TemplateFactory;

class BlockHeaderSerializer
{
    /**
     * @var \BitWasp\Buffertools\Template
     */
    private $template;

    public function __construct()
    {
        $this->template = $this->getTemplate();
    }

    /**
     * @param \BitWasp\Buffertools\BufferInterface|string $string
     * @return BlockHeader
     * @throws ParserOutOfRange
     */
    public function parse($string)
    {
        return $this->fromParser(new Parser($string));
    }

    /**
     * @return \BitWasp\Buffertools\Template
     */
    public function getTemplate()
    {
        return (new TemplateFactory())
            ->int32le()
            ->bytestringle(32)
            ->bytestringle(32)
            ->uint32le()
            ->uint32le()
            ->uint32le()
            ->getTemplate();
    }

    /**
     * @param Parser $parser
     * @return BlockHeader
     * @throws ParserOutOfRange
     */
    public function fromParser(Parser $parser)
    {

        try {
            list ($version, $prevHash, $merkleHash, $time, $nBits, $nonce) = $this->template->parse($parser);

            return new BlockHeader(
                $version,
                $prevHash,
                $merkleHash,
                $time,
                (int) $nBits,
                $nonce
            );
        } catch (ParserOutOfRange $e) {
            throw new ParserOutOfRange('Failed to extract full block header from parser');
        }
    }

    /**
     * @param BlockHeaderInterface $header
     * @return \BitWasp\Buffertools\BufferInterface
     */
    public function serialize(BlockHeaderInterface $header)
    {
        return $this->template->write([
            $header->getVersion(),
            $header->getPrevBlock(),
            $header->getMerkleRoot(),
            $header->getTimestamp(),
            $header->getBits(),
            $header->getNonce()
        ]);
    }
}
