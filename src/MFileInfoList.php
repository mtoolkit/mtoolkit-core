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

use mtoolkit\core\exception\MWrongTypeException;

class MFileInfoList extends MList
{
    public function __construct(array $list = array())
    {
        parent::__construct($list, null);
    }
    
    /**
     * Inserts <i>$value</i> at the end of the list.
     * 
     * @param MFileInfo $value
     * @throws MWrongTypeException
     */
    public function append( MFileInfo $value )
    {
        parent::append($value);
    }

    /**
     * Inserts MList <i>$value</i> at the end of the list.
     * 
     * @param MFileInfoList $value
     */
    public function appendList( MFileInfoList $value )
    {
        parent::appendList($value);
    }

    /**
     * Returns the item at index position <i>$i</i> in the list. <i>$i</i> must 
     * be a valid index position in the list (i.e., 0 <= <i>$i</i> < size()).
     * 
     * @param int $i
     * @return MFileInfo
     * @throws MWrongTypeException
     * @throws \OutOfBoundsException
     */
    public function &at( $i )
    {
        return parent::at($i);
    }

    /**
     * This function is provided for STL compatibility. 
     * It is equivalent to last(). 
     * The list must not be empty. 
     * If the list can be empty, call isEmpty() before calling this function.
     * 
     * @return MFileInfo
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
     * @param MFileInfo $value
     * @return boolean
     */
    public function endsWith( MFileInfo $value )
    {
        return parent::endsWith($value);
    }

    /**
     * Returns the value of the first item in the list. The list must not be 
     * empty. If the list can be empty, call isEmpty() before calling this 
     * function.
     * 
     * @return MFileInfo
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
     * @return MFileInfo
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
     * @param MFileInfo $value
     * @param int $from
     * @return int
     * @throws MWrongTypeException
     */
    public function /* int */ indexOf( MFileInfo $value, $from = 0 )
    {
        return parent::indexOf($value, $from);
    }

    /**
     * Inserts value at index position <i>$i</i> in the list. If i is 0, the 
     * <i>$value</i> is prepended to the list. If i is size(), the value is appended to 
     * the list.
     * 
     * @param int $i
     * @param MFileInfo $value
     * @throws MWrongTypeException
     */
    public function insert( $i, MFileInfo $value )
    {        
        parent::insert($i, $value);
    }

    /**
     * Returns a reference to the last item in the list. The list must not be 
     * empty. If the list can be empty, call isEmpty() before calling this 
     * function.
     * 
     * @return MFileInfo
     */
    public function last()
    {
        return parent::last();
    }

    /**
     * @param MFileInfo $value
     * @param int $from
     * @return MFileInfo
     */
    public function lastIndexOf( MFileInfo $value, $from = -1 )
    {
        return parent::lastIndexOf($value, $from);
    }

    /**
     * @param MFileInfo $value
     */
    public function prepend( MFileInfo $value )
    {
        parent::prepend($value);
    }

    /**
     * @param MFileInfo $value
     * @return MFileInfo
     */
    public function removeOne( MFileInfo $value )
    {
        return parent::removeOne($value);
    }

    /**
     * @param int $i
     * @param MFileInfo $value
     */
    public function replace( $i, MFileInfo $value )
    {
        parent::replace($i, $value);
    }

    /**
     * @param MFileInfo $value
     * @return MFileInfo
     */
    public function startsWith( MFileInfo $value )
    {
        return null;
    }
    
    /**
     * @param int $i
     * @return MFileInfo
     */
    public function takeAt( $i )
    {
        return parent::takeAt($i);
    }

    /**
     * @return MFileInfo
     */
    public function takeFirst()
    {
        return parent::takeFirst();
    }

    /**
     * @return MFileInfo
     */
    public function takeLast()
    {
        return parent::takeLast();
    }

    /**
     * @param int $i
     * @param MFileInfo $defaultValue
     * @return MFileInfo
     */
    public function getValue( $i, MFileInfo $defaultValue = null )
    {
        return parent::getValue($i, $defaultValue);
    }

    /**
     * @return MFileInfo
     */
    public function &current()
    {
        return parent::current();
    }
}
