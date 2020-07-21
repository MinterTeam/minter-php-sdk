<?php

namespace Minter\SDK;

use Elliptic\EC\Signature;

/**
 * Class MinterSignature
 * @package Minter\SDK
 */
class MinterSignature
{
    private $signatures;
    private $multisigAddress;

    /**
     * MinterSignature constructor.
     * @param int $type
     * @param     $data
     */
    public function __construct(int $type, $data)
    {
        if ($type === MinterTx::SIGNATURE_SINGLE_TYPE) {
            $this->addSingleSignature(...$data);
            return;
        }

        $this->addMultiSignature(...$data);
    }

    /**
     * Add single signature
     *
     * @param string $v
     * @param string $r
     * @param string $s
     */
    public function addSingleSignature(string $v, string $r, string $s)
    {
        $this->signatures[] = new Signature([
            "r"             => $r,
            "s"             => $s,
            "recoveryParam" => hexdec($v)
        ]);
    }

    /**
     * Handle multi-signature
     *
     * @param string $sender
     * @param array  $signatures
     */
    public function addMultiSignature(string $sender, $signatures)
    {
        $this->multisigAddress = MinterPrefix::ADDRESS . $sender;
        foreach ($signatures as $signature) {
            $this->addSingleSignature(...$signature);
        }
    }

    public function getSignatures(): array
    {
        return $this->signatures;
    }

    public function getMultisigAddress(): string
    {
        return $this->multisigAddress;
    }
}