<?php

namespace mtoolkit\core;

/*
 * This file is part of MToolkit.
 *
 * MToolkit is free software: you can redistribute it and/or modify
 * it under the terms of the LGNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * MToolkit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * LGNU Lesser General Public License for more details.
 *
 * You should have received a copy of the LGNU Lesser General Public License
 * along with MToolkit.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author  Michele Pagnin
 */

abstract class MAbstractTemplate
{
    /**
     * @var string
     */
    private $type = null;

    /**
     * @param int|null|string $type
     */
    public function __construct($type = null)
    {
        $this->type = $type;
    }

    /**
     * Returns true if the <i>$object</i> has the right data type assigned in
     * the construct, otherwise false.
     *
     * @param mixed $object
     * @return boolean
     */
    public function isValidType($object): bool
    {
        if ($this->type == null || $object == null) {
            return true;
        }

        if (is_object($object) && get_class($object) == $this->type) {
            return true;
        }

        return (MDataType::getType($object) == $this->type);
    }

    /**
     * Returns MDataType constants, or the name of the class, or null.
     *
     * @return null|int|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param null|int|string $type MDataType constants, or the name of the class, or null.
     * @return \MToolkit\Core\MAbstractTemplate
     */
    public function setType($type): MAbstractTemplate
    {
        $this->type = $type;
        return $this;
    }
}