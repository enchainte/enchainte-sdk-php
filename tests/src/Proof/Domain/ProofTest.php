<?php

namespace Enchainte\Tests\src\Proof\Domain;

use Enchainte\Proof\Domain\Proof;
use PHPUnit\Framework\TestCase;

final class ProofTest extends TestCase
{
    /** @test */
    public function it_should_successfully_create_a_Proof_instance(): void
    {
        $proof = new Proof([], [], "depth", "bitmap");

        $this->assertInstanceOf(Proof::class, $proof);
    }

    /** @test */
    public function it_should_return_all_the_properties_values(): void
    {
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

        $proof = new Proof($leaves, $nodes, $depth, $bitmap);

        $this->assertEquals($proof->leaves(), $leaves);
        $this->assertEquals($proof->nodes(), $nodes);
        $this->assertEquals($proof->depth(), $depth);
        $this->assertEquals($proof->bitmap(), $bitmap);
    }

    /** @test */
    public function it_should_return_all_the_properties_values_in_bytes_format(): void
    {
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

        $proof = new Proof($leaves, $nodes, $depth, $bitmap);

        $this->assertEquals($proof->leavesBytes(), $leavesBytes);
        $this->assertEquals($proof->nodesBytes(), $nodesBytes);
        $this->assertEquals($proof->depthBytes(), $depthBytes);
        $this->assertEquals($proof->bitmapBytes(), $bitmapBytes);
    }

    /** @test */
    public function it_should_merge_two_leaves_and_create_a_hash_of_them_and_return_it_in_bytes_format(): void
    {
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

        $proof = new Proof($leaves, $nodes, $depth, $bitmap);

        $leave1 = [
            130, 170, 231, 232, 110, 181, 31, 97, 166, 32, 131, 19, 32, 71, 93, 157, 97, 203, 213, 39, 73, 219, 241, 143, 169, 66, 177, 185, 127, 80, 174, 233,
        ];
        $leave2 = [
            143, 1, 148, 176, 152, 110, 14, 162, 214, 226, 77, 245, 47, 31, 179, 212, 78, 66, 27, 206, 34, 67, 131, 247, 128, 95, 56, 220, 119, 43, 52, 137,
        ];


        $expectedResult = [
            189, 247, 172, 176, 237, 201, 10, 44, 233, 13, 178, 24, 9, 60, 242, 83, 26, 26, 103, 71, 96, 3, 172, 5, 129, 27, 178, 71, 212, 228, 11, 46,
        ];

        $result = $proof->mergeLeavesAndHash($leave1, $leave2);
        $this->assertEquals($expectedResult, $result);
    }

}