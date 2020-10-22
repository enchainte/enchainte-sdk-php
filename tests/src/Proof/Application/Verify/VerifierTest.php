<?php

namespace Enchainte\Tests\src\Proof\Application\Verify;

use Enchainte\Proof\Application\Verify\Verifier;
use Enchainte\Proof\Domain\Proof;
use Enchainte\Shared\Domain\HashAlgorithm;
use Enchainte\Shared\Infrastructure\Hashing\Blake2b;
use Enchainte\Tests\src\Shared\Infrastructure\Web3\Web3SuccessfulValidationStub;
use Enchainte\Tests\src\Shared\Infrastructure\Web3\Web3UnsuccessfulValidationStub;
use PHPUnit\Framework\TestCase;

class VerifierTest extends TestCase
{
    const LEAVES_VALUES = [
        '0f387971e8ec99cbfaef7a716343b24545750123dd65815050cef723001d4b0a',
    ];
    const NODES_VALUES = [
        "b077f57d8c949d15e48dd6b98450379435fd4341f671cbc59ae153f0ff5e031f",
        "ffa4fe16ffbadd45b44c4ef1b9e0f5e707a90966f3831473917161f924edd280",
        "9953763d1ed0a109baaa56c7f5b47530d99a067ca951bf61c11a9e9ac3f9e67d",
        "ea16177faa795b9e111a8b8eebe8a8292f46172df185e5110c07fc49769ddefb",
        "bdc292b0c82e7507fd9bed223e51a63b0062e9356494ba587003c431720c1775",
        "2c1523e2446540630377968f1db612c1a9b9f57f53a9d7afeff159cfcbcfcd3b",
        "edcfb5d7eb2b27850276a6debb9bf786d26159ef14edc0ec53cada809405ca53",
        "0f38554070a43ce2516325891d0c61b9aa0b66976d409433feac448cf723a09d",
        "0f39981d0e3b8e6097f9d281a442054d8cde1c50a6b743b84ff53504e172b8bb",
        "7b9c5cce627c862aee985c81cbbd468b44bb695a43755fca5d62739e3f9a1233",
        "bcad2f3f7936fa5b94663a1ff1e58b8743c47ed54a11f5895895b1bb172ad912",
        "233eb5dd4d165b580bf846910dbad821147c173385414f994347c251240af747",
        "4f55a7cfc5f92e4ac6bbfe0353330fc337e9ddfe3378ce2555daf8129c321baf",
        "d07f1f698105c2971202dd1eb6ccf355d849e0cbd7e6e3475143b1236b8fabd6",
        "3b28341e46b4eda3889f312c6c32f177e941d0b0d47d29178830dc6cb09536ff",
        "25e2936aa6c11da7faf800f68a7660afedf433dbdc8f8670c38f9052bfa28ae2",
    ];
    const DEPTH_VALUE = "050607080b0c0d10100f0e0a0904030201";
    const BITMAP_VALUE = "ff7f80";

    /** @test
     * @group unit
     */
    public function it_should_return_true_when_the_Proof_root_is_correct(): void
    {
        $blockchainClient = new Web3SuccessfulValidationStub();
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $verifier = new Verifier($blockchainClient, $hashAlgorithm);
        $result = $verifier->verifyProof(
            self::LEAVES_VALUES,
            self::NODES_VALUES,
            self::DEPTH_VALUE,
            self::BITMAP_VALUE
        );

        $this->assertTrue($result);
    }

    /** @test
     * @group unit
     */
    public function it_should_return_false_when_the_Proof_root_is_not_correct(): void
    {
        $blockchainClient = new Web3UnsuccessfulValidationStub();
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $verifier = new Verifier($blockchainClient, $hashAlgorithm);
        $result = $verifier->verifyProof(
            self::LEAVES_VALUES,
            self::NODES_VALUES,
            self::DEPTH_VALUE,
            self::BITMAP_VALUE
        );

        $this->assertFalse($result);
    }

    /** @test
     * @group unit
     */
    public function it_should_calculate_return_the_expected_root_value(): void
    {
        $blockchainClient = new Web3UnsuccessfulValidationStub();
        $hashAlgorithm = new Blake2b();

        $verifier = new Verifier($blockchainClient, $hashAlgorithm);

        $proof = new Proof(
            self::LEAVES_VALUES,
            self::NODES_VALUES,
            self::DEPTH_VALUE,
            self::BITMAP_VALUE,
            $hashAlgorithm
        );

        $this->assertEquals("165936bc3aa9d9e96de230741e287a68d1e5b104fb49ccd23c25178c6f1e7bca", $verifier->calculateRoot($proof));
    }


}