<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Minter\SDK\MinterConverter;

/**
 * Class for testing MinterConverter
 */
final class MinterConverterTest extends TestCase
{
    /**
     * Predefined values
     */
    const VALUES = [
        ['1', '1000000000000000000'],
        ['0.1', '100000000000000000'],
        ['10', '10000000000000000000'],
        ['0.0000000000002', '200000'],
        ['0', '0']
    ];

    /**
     * Test converting value from bip to pip.
     */
    public function testConvertValueToPIP()
    {
        foreach (self::VALUES as $data) {
            $this->assertEquals($data[1], MinterConverter::convertValue($data[0], 'pip'));
        }
    }

    /**
     * Test converting value from pip to bip.
     */
    public function testConvertValueToBIP()
    {
        foreach (self::VALUES as $data) {
            $this->assertEquals($data[0], MinterConverter::convertValue($data[1], 'bip'));
        }
    }

    /**
     * Test converting coin name.
     */
    public function testConvertCoinName()
    {
        $this->assertEquals(
            'BIP' . str_repeat(chr(0), 10 - strlen('BIP')),
            MinterConverter::convertCoinName('BIP')
        );

        $this->assertEquals(
            'BIPBIPBIPP',
            MinterConverter::convertCoinName('BIPBIPBIPP')
        );
    }
}
