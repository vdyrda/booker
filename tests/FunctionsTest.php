<?php
use PHPUnit\Framework\TestCase;

include_once '../config.php';
include_once '../functions.php';

class FunctionsTest extends TestCase {
    
    /**
     * @dataProvider zeroProvider
     */
    public function testZero($a, $expected)
    {
        $this->assertEquals($expected, zero($a));
    }

    public function zeroProvider()
    {
        return [
            [0, '0'],
            [12, '12'],
            [8, '08'],
            [-2, '']
        ];
    }        
}

