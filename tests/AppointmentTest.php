<?php
use PHPUnit\Framework\TestCase;

include '../init.php';

class AppointmentTest extends TestCase {
    
    /**
     * @dataProvider checkAttrProvider
     */
    public function testPrepareVal($a, $b, $expected)
    {
        $this->assertEquals($expected, Appointment::prepare_val($a, $b));
    }

    public function checkAttrProvider()
    {
        return [
            [5, 'int', 5],
            [12, 'char', "'12'"],
            ['2017-02-28', 'datetime', "'2017-02-28 00:00:00'"],
            ['pasta','string',"'pasta'"]
        ];
    }        
    
}