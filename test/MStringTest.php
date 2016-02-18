<?php

namespace mtoolkit\core\test;

use mtoolkit\core\enum\CaseSensitivity;
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

    public function testToString()
    {
        $str = "Hello world!";
        $helloWorld1 = new MString( $str );
        $this->assertEquals( $str, (string)$helloWorld1 );

        $helloWorld2 = new MString();
        $this->assertEquals( MString::EMPTY_STRING, (string)$helloWorld2 );
    }

    public function testAt()
    {
        $str = "Hello world!";
        $helloWorld1 = new MString( $str );
        $this->assertEquals( "H", $helloWorld1->at( 0 ) );

        $helloWorld2 = new MString( $str );
        $this->assertEquals( null, $helloWorld2->at( 100 ) );
    }

    public function testChop()
    {
        $str = "Hello world!";
        $helloWorld1 = new MString( $str );
        $helloWorld1->chop( 7 );
        $this->assertEquals( "Hello", (string)$helloWorld1 );

        $helloWorld2 = new MString( $str );
        $helloWorld2->chop( 70 );
        $this->assertEquals( MString::EMPTY_STRING, (string)$helloWorld2 );
    }

    public function testClear()
    {
        $str = "Hello world!";
        $helloWorld1 = new MString( $str );
        $helloWorld1->clear();
        $this->assertEquals( MString::EMPTY_STRING, (string)$helloWorld1 );
    }

    public function testCompare()
    {
        $str1 = "Hello world!";
        $str2 = "hello world!";
        $helloWorld1 = new MString( $str1 );
        $helloWorld2 = new MString( $str2 );

        $this->assertEquals( 0, $helloWorld1->compare( $str1 ) );
        $this->assertEquals( 0, $helloWorld1->compare( $str2, CaseSensitivity::CASE_INSENSITIVE ) );
        $this->assertEquals( 0, $helloWorld1->compare( $helloWorld2, CaseSensitivity::CASE_INSENSITIVE ) );
    }

    public function testContains()
    {
        $str1 = "Hello";
        $str2 = "hello";
        $helloWorld1 = new MString( "Hello world!" );
        $helloWorld2 = new MString( "hello world!" );

        $this->assertTrue( $helloWorld1->contains( $str1 ) );
        $this->assertTrue( $helloWorld1->contains( $str2, CaseSensitivity::CASE_INSENSITIVE ) );
        $this->assertTrue( $helloWorld1->contains( $helloWorld2, CaseSensitivity::CASE_INSENSITIVE ) );
        $this->assertFalse( $helloWorld1->contains( $helloWorld2, CaseSensitivity::CASE_SENSITIVE ) );
    }

    public function testSize()
    {
        $str1 = "Hello world!";
        $helloWorld1 = new MString( $str1 );

        $this->assertEquals( strlen( $str1 ), $helloWorld1->size() );
        $this->assertNotEquals( 0, $helloWorld1->size() );
    }

    public function testCount()
    {
        $str1 = "Hello world!";
        $helloWorld1 = new MString( $str1 );

        $this->assertEquals( strlen( $str1 ), $helloWorld1->count() );
        $this->assertNotEquals( 0, $helloWorld1->size() );
    }

    public function testIndexOf()
    {
        $str1 = "Hello world!";
        $helloWorld1 = new MString( $str1 );
        $subString1 = "world";
        $subString2 = "World";

        $this->assertEquals( 6, $helloWorld1->indexOf( $subString1 ) );
        $this->assertEquals( 6, $helloWorld1->indexOf( $subString2, CaseSensitivity::CASE_INSENSITIVE ) );

        $this->assertNotEquals( 5, $helloWorld1->indexOf( $subString1 ) );
        $this->assertNotEquals( 6, $helloWorld1->indexOf( $subString2, 0, CaseSensitivity::CASE_SENSITIVE ) );
        $this->assertNotEquals( 6, $helloWorld1->indexOf( $subString2, 7, CaseSensitivity::CASE_SENSITIVE ) );
    }

    public function testLastIndexOf()
    {
        $str1 = "Hello world!";
        $helloWorld1 = new MString( $str1 );
        $subString1 = "o";
        $subString2 = "O";

        $this->assertEquals( 7, $helloWorld1->lastIndexOf( $subString1 ) );
        $this->assertEquals( 7, $helloWorld1->lastIndexOf( $subString2, CaseSensitivity::CASE_INSENSITIVE ) );

        $this->assertNotEquals( 5, $helloWorld1->lastIndexOf( $subString1 ) );
        $this->assertNotEquals( 6, $helloWorld1->lastIndexOf( $subString2, 0, CaseSensitivity::CASE_SENSITIVE ) );
        $this->assertNotEquals( 6, $helloWorld1->lastIndexOf( $subString2, 7, CaseSensitivity::CASE_SENSITIVE ) );
    }

    public function testInsert(){
        $str1 = "Hello world";
        $str2 = " creative";
        $helloWorld1 = new MString( $str1 );

        $this->assertEquals( $str1.$str2, $helloWorld1->insert( $helloWorld1->size(), $str2 ) );
        $this->assertEquals( "Hello creative world", $helloWorld1->insert( 5, $str2 ) );
        $this->assertNotEquals( "Hellocreative  world", $helloWorld1->insert( 5, $str2 ) );
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
