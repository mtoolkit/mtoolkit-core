<?php
namespace mtoolkit\core\test\json;

use mtoolkit\core\json\MJsonObject;

class MJsonObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testToJson()
    {
        $expectedJson01 = json_encode( array(
            'property01' => array(
                'property01' => 'property01',
                'property02' => 2,
                'property03' => 3.3,
                'property04' => true,
            ),
            'property02' => 2
        ) );

        $obj = new A2();
        $json = $obj->toJson();
        $this->assertEquals( $expectedJson01, $json );
    }
}

class A1
{
    private $property01 = 'property01';
    public $property02 = 2;
    protected $property03 = 3.3;
    private $property04 = true;

    /**
     * @return string
     */
    public function getProperty01()
    {
        return $this->property01;
    }

    /**
     * @return boolean
     */
    public function isProperty04()
    {
        return $this->property04;
    }


}

class A2 extends MJsonObject
{
    protected $property01;
    private $property02 = 2;

    /**
     * @return int
     */
    public function getProperty02()
    {
        return $this->property02;
    }


    /**
     * A2 constructor.
     */
    public function __construct()
    {
        $this->property01 = new A1();
    }
}