<?php

namespace mtoolkit\core\test\resources;

class RandomPersonGenerator
{
    /**
     * @return Person
     */
    public static function builder()
    {
        $person = new Person();
        $person->setName(uniqid("name_"))
            ->setAddress(uniqid("address_"))
            ->setSurname(uniqid("surname_"));

        return $person;
    }

    /**
     * @param int $number
     * @return Person[]
     */
    public static function builderALot($number)
    {
        $list = array();

        for ($i = 0; $i < $number; $i++) {
            $list[] = self::builder();
        }

        return $list;
    }
}