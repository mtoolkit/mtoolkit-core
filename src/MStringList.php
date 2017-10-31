<?php

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

namespace mtoolkit\core;

use mtoolkit\core\exception\MWrongTypeException;

class MStringList extends MList
{
    public function __construct(array $list = array())
    {
        parent::__construct($list, null);
    }
    
    /**
     * Inserts <i>$value</i> at the end of the list.
     * 
     * @param MString $value
     * @throws MWrongTypeException
     */
    public function append( MString $value )
    {
        parent::append($value);
    }

    /**
     * Inserts MList <i>$value</i> at the end of the list.
     * 
     * @param MStringList $value
     */
    public function appendList( MStringList $value )
    {
        parent::appendList($value);
    }

    /**
     * Returns the item at index position <i>$i</i> in the list. <i>$i</i> must 
     * be a valid index position in the list (i.e., 0 <= <i>$i</i> < size()).
     * 
     * @param int $i
     * @return MString
     * @throws MWrongTypeException
     * @throws \OutOfBoundsException
     */
    public function &at( int $i )
    {
        return parent::at($i);
    }

    /**
     * This function is provided for STL compatibility. 
     * It is equivalent to last(). 
     * The list must not be empty. 
     * If the list can be empty, call isEmpty() before calling this function.
     * 
     * @return MString
     * @throws \OutOfBoundsException
     */
    public function back()
    {
        return parent::back();
    }

    /**
     * Returns true if this list is not empty and its last item is equal to 
     * <i>$value</i>; otherwise returns false.
     * 
     * @param MString $value
     * @return boolean
     */
    public function endsWith( MString $value ):bool
    {
        return parent::endsWith($value);
    }

    /**
     * Returns the value of the first item in the list. The list must not be 
     * empty. If the list can be empty, call isEmpty() before calling this 
     * function.
     * 
     * @return MString
     */
    public function first()
    {
        return parent::first();
    }

    /**
     * This function is provided for STL compatibility. It is equivalent to 
     * first(). The list must not be empty. If the list can be empty, call 
     * isEmpty() before calling this function.
     * 
     * @return MString
     */
    public function front()
    {
        return parent::front();
    }

    /**
     * Returns the index position of the first occurrence of <i>$value</i> in 
     * the list, searching forward from index position <i>$from</i>. Returns -1 
     * if no item matched.
     * 
     * @param MString $value
     * @param int $from
     * @return int
     * @throws MWrongTypeException
     */
    public function /* int */ indexOf( MString $value, $from = 0 ):int
    {
        return parent::indexOf($value, $from);
    }

    /**
     * Inserts value at index position <i>$i</i> in the list. If i is 0, the 
     * <i>$value</i> is prepended to the list. If i is size(), the value is appended to 
     * the list.
     * 
     * @param int $i
     * @param MString $value
     * @throws MWrongTypeException
     */
    public function insert( int $i, MString $value )
    {        
        parent::insert($i, $value);
    }

    /**
     * Returns a reference to the last item in the list. The list must not be 
     * empty. If the list can be empty, call isEmpty() before calling this 
     * function.
     * 
     * @return MString
     */
    public function last()
    {
        return parent::last();
    }

    /**
     * @param MString $value
     * @param int $from
     * @return int
     */
    public function lastIndexOf( MString $value, $from = -1 ):int
    {
        return parent::lastIndexOf($value, $from);
    }

    /**
     * @param MString $value
     */
    public function prepend( MString $value )
    {
        parent::prepend($value);
    }

    /**
     * @param MString $value
     * @return bool
     */
    public function removeOne( MString $value ):bool
    {
        return parent::removeOne($value);
    }

    /**
     * @param int $i
     * @param MString $value
     */
    public function replace( int $i, MString $value )
    {
        parent::replace($i, $value);
    }

    /**
     * @param MString $value
     * @return bool
     */
    public function startsWith( MString $value ):bool
    {
        return false;
    }
    
    /**
     * @param int $i
     * @return MString
     */
    public function takeAt( int $i )
    {
        return parent::takeAt($i);
    }

    /**
     * @return MString
     */
    public function takeFirst()
    {
        return parent::takeFirst();
    }

    /**
     * @return MString
     */
    public function takeLast()
    {
        return parent::takeLast();
    }

    /**
     * @param int $i
     * @param MString $defaultValue
     * @return MString
     */
    public function getValue( int $i, MString $defaultValue = null ):MString
    {
        return parent::getValue($i, $defaultValue);
    }

    /**
     * @return MString
     */
    public function &current()
    {
        return parent::current();
    }
}
