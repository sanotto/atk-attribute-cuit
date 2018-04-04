<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Sintattica\Atk\Attributes\CUITAttribute as Cuit;

final class CUITAttributeTest extends TestCase
{
  
    public function testNormalizeCUITWorks()
    {
        $this->assertEquals(Cuit::normalizeCUIT('20-22264382-2'), '20222643822',
            'CUIT was not normalized properly'
        );
    }
    
    public function testUnnormalizableCUITreturnsFalse()
    {
        $this->assertEquals(Cuit::normalizeCUIT('20-22264382-'), false,
            'Unnormalizable CUIT was deemed valid'
        );
    }
    
    public function testCalcVerifyDigit()
    {
        $this->assertEquals(Cuit::calcVerifyDigit('20222643822'),2,
            'Calculation of verify digit gave incorrect value'
        );
    }


    public function testValidCUILisValid()
    {
        $this->assertTrue(Cuit::isValidCUIT('20222643822'),
            'Valid CUIL deemed invalid'
        );
    }

    public function testValidCUITisValid()
    {
        $this->assertTrue(Cuit::isValidCUIT('30563638385'),
            'Valid CUIT deemed invalid'
        );
    }

    public function testInvalidCUILisInvalid()
    {
        $this->assertFalse(Cuit::isValidCUIT('20222643824'),
            'Invalid CUIL deemed valid'
        );
    }

    public function testInvalidCUITisInvalid()
    {
        $this->assertFalse(Cuit::isValidCUIT('30563638386'),
            'Invalid CUIT deemed valid'
        );
    }

    public function testCalculateCUIL()
    {
        $this->assertEquals(Cuit::CalculateCUIL('22264382', 'M'),'20222643822',
            'CalculateCUIL returned incorrect CUIL'
        );
    }
    public function testCalculateCUIT()
    {
        $this->assertEquals(Cuit::CalculateCUIL('56363838', 'J'),'30563638385',
            'CalculateCUIL returned incorrect CUIT'
        );
    }
 
    public function testValidCUILWith23PrefixIsValid()
    {
        $this->assertTrue(Cuit::isValidCUIT('23289726004'),
            'Valid CUIT deemed invalid'
        );
    }

    public function testCalculateCUILWith23Prefix()
    {
        $this->assertEquals(Cuit::CalculateCUIL('28972600', 'F'),'23289726004',
            'CalculateCUIL returned incorrect CUIL'
        );

    }

}

