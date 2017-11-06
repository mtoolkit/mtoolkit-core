<?php

namespace mtoolkit\core\test;

use mtoolkit\core\exception\MOutOfBoundsException;
use mtoolkit\core\exception\MWrongTypeException;
use mtoolkit\core\MList;
use mtoolkit\core\test\resources\Person;
use mtoolkit\core\test\resources\RandomPersonGenerator;

class MListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MList<Person>
     */
    private $list;

    /**
     * @var MList
     */
    private $untypedList;

    public function testAppend()
    {
        try {
            $this->list->append("ciao");
            $this->assertTrue(false);
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(true);
        }

        try {
            $countPreAppend = $this->list->count();

            $this->list->append(RandomPersonGenerator::builder());
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + 1, $this->list->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }
    }

    public function testUntypeAppend()
    {
        try {
            $countPreAppend = $this->untypedList->count();

            $this->untypedList->append("ciao");
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + 1, $this->untypedList->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }

        try {
            $countPreAppend = $this->untypedList->count();

            $this->untypedList->append(RandomPersonGenerator::builder());
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + 1, $this->untypedList->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }
    }

    public function testAppendArray()
    {
        try {
            $countPreAppend = $this->list->count();
            $newPerson = RandomPersonGenerator::builderALot(40);

            $this->list->appendArray($newPerson);
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + count($newPerson), $this->list->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }

        try {
            $this->list->appendArray(array(RandomPersonGenerator::builder(), "ciao"));
            $this->assertTrue(false);
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(true);
        }
    }

    public function testUntypeAppendArray()
    {
        try {
            $countPreAppend = $this->untypedList->count();
            $newPerson = RandomPersonGenerator::builderALot(40);

            $this->untypedList->appendArray($newPerson);
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + count($newPerson), $this->untypedList->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }

        try {
            $countPreAppend = $this->untypedList->count();

            $this->untypedList->appendArray(array(RandomPersonGenerator::builder(), "ciao"));
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + 2, $this->untypedList->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }
    }

    public function testAppendList()
    {
        try {
            $countPreAppend = $this->list->count();
            $new = RandomPersonGenerator::builderALot(40);

            $this->list->appendList(new MList($new, Person::class));
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + count($new), $this->list->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }

        try {
            $this->list->appendList(new MList(array(RandomPersonGenerator::builder(), "ciao")));
            $this->assertTrue(false);
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(true);
        }
    }

    public function testUntypeAppendList()
    {
        try {
            $countPreAppend = $this->untypedList->count();
            $new = RandomPersonGenerator::builderALot(40);

            $this->untypedList->appendList(new MList($new));
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + count($new), $this->untypedList->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }

        try {
            $countPreAppend = $this->untypedList->count();

            $this->untypedList->appendList(new MList(array(RandomPersonGenerator::builder(), "ciao")));
            $this->assertTrue(true);

            $this->assertEquals($countPreAppend + 2, $this->untypedList->count());
        } catch (MWrongTypeException $ex) {
            $this->assertTrue(false);
        }
    }

    public function testAt()
    {
        $this->untypedList->clear();
        $new = RandomPersonGenerator::builderALot(40);
        $this->untypedList->appendArray($new);

        for ($k = 0; $k < 10; $k++) {
            $pos = rand(0, count($new) - 1);
            $this->assertEquals($new[$pos], $this->untypedList->at($pos));
        }

        try {
            $this->untypedList->at(count($new));
            $this->assertTrue(false);
        } catch (MOutOfBoundsException $ex) {
            $this->assertTrue(true);
        }
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->list = new MList(RandomPersonGenerator::builderALot(50), Person::class);
        $this->untypedList = new MList(array("ciao"));
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
