<?php

namespace Enchainte\Tests\src\Proof\Application\Verify;

use Enchainte\Proof\Application\Verify\Verifier;
use Enchainte\Tests\src\Shared\Infrastructure\Web3\Web3SuccessfulValidationStub;
use Enchainte\Tests\src\Shared\Infrastructure\Web3\Web3UnsuccessfulValidationStub;
use PHPUnit\Framework\TestCase;

class VerifierTest extends TestCase
{
//    const LEAVES_VALUES = [
//        '72aae7e86eb51f61a620831320475d9d61cbd52749dbf18fa942b1b97f50aee9',
//    ];
//    const NODES_VALUES = [
//        '359b5206452a4ca5058129727fb48f0860a36c0afee0ec62baa874927e9d4b99',
//        '707cb86e449cd3990c85fb3ae9ec967ee12b82f21eae9e6ea35180e6c331c3e8',
//        '23950edeb3ca719e814d8b04d63d90d39327b49b7df5baf2f72305c1f2b260b7',
//        '72aae7e86eb50f61a620831320475d9d61cbd52749dbf18fa942b1b97f50aee9',
//        '517e320992fb35553575750153992d6360268d04a1e4d9e2cae7e5c3736ac627',
//    ];
//    const DEPTH_VALUE = "020304050501";
//    const BITMAP_VALUE = "f4";
    const LEAVES_VALUES = [
        '82aae7e86eb51f61a620831320475d9d61cbd52749dbf18fa942b1b97f50aee9',
        '92aae7e86eb51f61a620831320475d9d61cbd52749dbf18fa942b1b97f50aee9',
    ];
    const NODES_VALUES = [
        '285f570a90100fb94d5608b25d9e2b74bb58f068d495190f469aac5ef7ecf3c5',
        '8f0194b0986e0ea2d6e24df52f1fb3d44e421bce224383f7805f38dc772b3489',
    ];
    const DEPTH_VALUE = "01030302";
    const BITMAP_VALUE = "a0";

    /** @test */
    public function it_should_return_true_when_the_Proof_root_is_correct(): void
    {
        $blockchainClient = new Web3SuccessfulValidationStub();
        $verifier = new Verifier($blockchainClient);
        $result = $verifier->verifyProof(
            self::LEAVES_VALUES,
            self::NODES_VALUES,
            self::DEPTH_VALUE,
            self::BITMAP_VALUE
        );

        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_return_false_when_the_Proof_root_is_not_correct(): void
    {
        $blockchainClient = new Web3UnsuccessfulValidationStub();
        $verifier = new Verifier($blockchainClient);
        $result = $verifier->verifyProof(
            self::LEAVES_VALUES,
            self::NODES_VALUES,
            self::DEPTH_VALUE,
            self::BITMAP_VALUE
        );

        $this->assertFalse($result);
    }
}