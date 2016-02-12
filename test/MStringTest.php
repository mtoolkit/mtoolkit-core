<?php

namespace mtoolkit\core\test;

use mtoolkit\core\MString;

class MStringTest extends \PHPUnit_Framework_TestCase
{
    public function testAppend()
    {
        $string = 'Hello world %s';
        $username = 'user';

        // Test 1
        $result1 = 'Hello world user';

        $helloWorld1 = new MString();
        $helloWorld1->append( $string, $username );
        $this->assertEquals( $result1, (string)$helloWorld1 );

        // Test 2
        $result2 = 'Hello world ';

        $helloWorld2 = new MString();
        $helloWorld2->append( $string );
        $this->assertEquals( $result2, (string)$helloWorld2 );

        // Test 3
        $helloWorld3 = new MString();
        $this->assertEquals( $result1, (string)$helloWorld3->append( $string, $username ) );
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

}
