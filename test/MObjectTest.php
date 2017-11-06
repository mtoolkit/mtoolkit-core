<?php


namespace mtoolkit\core\test;


use mtoolkit\core\MObject;

class MObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $obj = new MObject();
        $this->assertNotEmpty($obj->__toString());
    }
}