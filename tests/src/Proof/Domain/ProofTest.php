<?php

namespace Enchainte\Tests\src\Proof\Domain;

use Enchainte\Proof\Domain\Exception\InvalidProofArgumentException;
use Enchainte\Proof\Domain\Proof;
use Enchainte\Shared\Domain\HashAlgorithm;
use Enchainte\Shared\Infrastructure\Hashing\Blake2b;
use PHPUnit\Framework\TestCase;

final class ProofTest extends TestCase
{
    /** @test
     * @group unit
     */
    public function it_should_successfully_create_a_Proof_instance(): void
    {
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $proof = new Proof(
            ['5ac706bdef87529b22c08646b74cb98baf310a46bd21ee420814b04c71fa42b1'],
            ['95be11f4984e0b6e15f100e4eb4476d54a716f47bfdbd606f85367f3867e9836'],
            "02080c0d1012141413110f0e0b0a0907060504030100",
            "020000",
            $hashAlgorithm
        );

        $this->assertInstanceOf(Proof::class, $proof);
    }

    /** @test
     * @group unit
     */
    public function it_should_return_all_the_properties_values(): void
    {
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $leaves = [
            '5ac706bdef87529b22c08646b74cb98baf310a46bd21ee420814b04c71fa42b1',
            '5cd53f8367e1892c4f25dc9b5ddf28c7a1a27f489336a9537a43555819e4f434',
        ];
        $nodes = [
            '95be11f4984e0b6e15f100e4eb4476d54a716f47bfdbd606f85367f3867e9836',
            '9b6c0696bc6c51d6f99b8fdc949c06eaaa0b6af7bee2564d04708e5dc2e262d8',
            '5ac607f2a9ee295d8401e913478b8e04a729ddcca14a3a84c76fc2ae9105e6cf',
        ];
        $depth = "02080c0d1012141413110f0e0b0a0907060504030100";
        $bitmap = "020000";

        $proof = new Proof($leaves, $nodes, $depth, $bitmap, $hashAlgorithm);

        $this->assertEquals($proof->leaves(), $leaves);
        $this->assertEquals($proof->nodes(), $nodes);
        $this->assertEquals($proof->depth(), $depth);
        $this->assertEquals($proof->bitmap(), $bitmap);
    }

    /** @test
     * @group unit
     */
    public function it_should_return_aan_exception_when_an_invalid_argument_is_provided(): void
    {
        $this->expectException(InvalidProofArgumentException::class);
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $leaves = [
            '5ac706bdef87529b22c08646b74cb98baf310a46bd21ee420814b04c71fa4zzz',
            '5cd53f8367e1892c4f25dc9b5ddf28c7a1a27f489336a9537a43555819e4f434',
        ];
        $nodes = [
            '95be11f4984e0b6e15f100e4eb4476d54a716f47bfdbd606f85367f3867e9836',
            '9b6c0696bc6c51d6f99b8fdc949c06eaaa0b6af7bee2564d04708e5dc2e262d8',
            '5ac607f2a9ee295d8401e913478b8e04a729ddcca14a3a84c76fc2ae9105e6cf',
        ];
        $depth = "02080c0d1012141413110f0e0b0a0907060504030100";
        $bitmap = "020000";

        $proof = new Proof($leaves, $nodes, $depth, $bitmap, $hashAlgorithm);

    }

    /** @test
     * @group unit
     */
    public function it_should_return_all_the_properties_values_in_bytes_format(): void
    {
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $leaves = [
            '5ac706bdef87529b22c08646b74cb98baf310a46bd21ee420814b04c71fa42b1',
            '5cd53f8367e1892c4f25dc9b5ddf28c7a1a27f489336a9537a43555819e4f434',
        ];
        $nodes = [
            '95be11f4984e0b6e15f100e4eb4476d54a716f47bfdbd606f85367f3867e9836',
            '9b6c0696bc6c51d6f99b8fdc949c06eaaa0b6af7bee2564d04708e5dc2e262d8',
            '5ac607f2a9ee295d8401e913478b8e04a729ddcca14a3a84c76fc2ae9105e6cf',
        ];
        $depth = "02080c0d1012141413110f0e0b0a0907060504030100";
        $bitmap = "020000";


        $leavesBytes = [
            [
                90, 199, 6, 189, 239, 135, 82, 155, 34, 192, 134, 70, 183, 76, 185, 139, 175, 49, 10, 70, 189, 33, 238, 66, 8, 20, 176, 76, 113, 250, 66, 177,
            ],
            [
                92, 213, 63, 131, 103, 225, 137, 44, 79, 37, 220, 155, 93, 223, 40, 199, 161, 162, 127, 72, 147, 54, 169, 83, 122, 67, 85, 88, 25, 228, 244, 52,
            ]
        ];
        $nodesBytes = [
            [
                149, 190, 17, 244, 152, 78, 11, 110, 21, 241, 0, 228, 235, 68, 118, 213, 74, 113, 111, 71, 191, 219, 214, 6, 248, 83, 103, 243, 134, 126, 152, 54,
            ],
            [
                155, 108, 6, 150, 188, 108, 81, 214, 249, 155, 143, 220, 148, 156, 6, 234, 170, 11, 106, 247, 190, 226, 86, 77, 4, 112, 142, 93, 194, 226, 98, 216
            ],
            [
                90, 198, 7, 242, 169, 238, 41, 93, 132, 1, 233, 19, 71, 139, 142, 4, 167, 41, 221, 204, 161, 74, 58, 132, 199, 111, 194, 174, 145, 5, 230, 207,
            ]
        ];
        $depthBytes = [2, 8, 12, 13, 16, 18, 20, 20, 19, 17, 15, 14, 11, 10, 9, 7, 6, 5, 4, 3, 1, 0];
        $bitmapBytes = [2, 0, 0];

        $proof = new Proof($leaves, $nodes, $depth, $bitmap, $hashAlgorithm);

        $this->assertEquals($proof->leavesBytes(), $leavesBytes);
        $this->assertEquals($proof->nodesBytes(), $nodesBytes);
        $this->assertEquals($proof->depthBytes(), $depthBytes);
        $this->assertEquals($proof->bitmapBytes(), $bitmapBytes);
    }

    /** @test
     * @group unit
     */
    public function it_should_merge_two_leaves_and_create_a_hash_of_them_and_return_it_in_bytes_format(): void
    {
        $leaves = [
            '0f387971e8ec99cbfaef7a716343b24545750123dd65815050cef723001d4b0a',
        ];
        $nodes = [
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
        $depth = "050607080b0c0d10100f0e0a0904030201";
        $bitmap = "ff7f80";

        $hashAlgorithm = new Blake2b();
        $proof = new Proof($leaves, $nodes, $depth, $bitmap, $hashAlgorithm);

        $leave1 = [
            143, 1, 148, 176, 152, 110, 14, 162, 214, 226, 77, 245, 47, 31, 179, 212, 78, 66, 27, 206, 34, 67, 131, 247, 128, 95, 56, 220, 119, 43, 52, 137,
        ];
        $leave2 = [
            143, 1, 148, 176, 152, 110, 14, 162, 214, 226, 77, 245, 47, 31, 179, 212, 78, 66, 27, 206, 34, 67, 131, 247, 128, 95, 56, 220, 119, 43, 52, 137,
        ];

        $expectedResult = [
            41, 169, 88, 61, 47, 184, 166, 183, 158, 226, 117, 103, 161, 148, 180, 169, 169, 229, 139, 130, 200, 82, 155, 40, 222, 223, 90, 82, 250, 44, 187, 25,
        ];

        $result = $proof->mergeAndHash($leave1, $leave2);

        $this->assertEquals($expectedResult, $result);
    }

}