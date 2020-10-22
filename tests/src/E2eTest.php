<?php


namespace Enchainte\Tests\src;

use EnchainteClient;
use Exception;
use PHPUnit\Framework\TestCase;


require_once(dirname(__DIR__, 2) . "/EnchainteClient.php");

final class E2eTest extends TestCase
{
    const TOKEN = 'JB1lKPZIdUKXhrpBVTGwsXSEFs2JT2jmp2dlmYS0nvBBsGBQ2g4hRFYOUNmneBdN';

    /** @test
     * @group e2e
     */
    public function end_to_end_sdk_test()
    {
        try {
            $sdk = new EnchainteClient(self::TOKEN);

            $bytes = $this->generateRandomBytes();

            $ok = $sdk->sendMessage($bytes);

            if (!$ok) {
                $this->fail("Failed to write message");
            }
            $sdk->waitMessageReceipt([$bytes]);

            $proof = $sdk->getProof([$bytes]);

            $valid = false;

            while (!$valid) {
                $valid = $sdk->verifyProof($proof["leaves"], $proof["nodes"], $proof["depth"], $proof["bitmap"]);
                usleep(500);
            }
            $this->assertTrue(true);
        }catch (Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    private function generateRandomBytes() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $length = rand(10, 40);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return unpack('C*', $randomString);
    }

}