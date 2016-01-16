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

/**
 * Class MVector is one of MToolkit's generic container classes.
 * It stores its item in a PHP array and provides fast index-based access.
 *
 * @package MToolkit\Core
 */
class MVector extends MAbstractTemplate implements \ArrayAccess
{
    /**
     * @var array
     */
    private $vector = array();

    public function __construct(array $array=array(), $type = null)
    {
        parent::__construct($type);
        $this->vector=array_values($array);
    }

    /**
     * Inserts value at the end of the vector.
     * @param mixed $value 
     */
    public function append( $value )
    {
        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        $this->vector[] = $value;
    }

    /**
     * Inserts all the items in <i>$value</i> at the and of the vector and returns a reference to this vector.
     *
     * @param MVector $value
     * @return MVector $this
     */
    public function appendAll( MVector $value )
    {
        foreach( $value as $item ){
            $this->append($item);
        }

        return $this;
    }

    /**
     * Returns the item at index position <i>i</i> in the vector.
     * <i>i</i> must be a valid index position in the vector (i.e., 0 <= <i>i</i> < size()).
     * @param int $i
     * @return mixed
     * @throws MWrongTypeException If <i>i</i> is not a int.
     */
    public function at( $i )
    {
        MDataType::mustBe(array(MDataType::INT));

        if ($i >= $this->count())
        {
            return null;
        }

        return $this->vector[$i];
    }

    /**
     * Removes all the elements from the vector and releases the memory used by the vector. 
     */
    public function clear()
    {
        $this->vector = array();
    }

    /**
     * Returns true if the vector contains an occurrence of <i>value</i>; otherwise returns false.
     *
     * @param mixed $value
     * @return bool 
     */
    public function contains( $value )
    {
        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        return in_array( $value, $this->vector );
    }

    /**
     * If <i>value</i> is not null: returns the number of occurrences of value in the vector.
     * If <i>value</i> is null: same as size().
     * 
     * @param mixed $value
     * @return int 
     */
    public function count( $value = null )
    {
        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        if (is_null( $value ))
        {
            return count( $this->vector );
        }

        $occurrences = 0;
        foreach ( $this->vector as $item )
        {
            if ($item == $value)
            {
                $occurrences++;
            }
        }

        return $occurrences;
    }

    /**
     * Returns true if the vector has size 0; otherwise returns false.
     * @return bool 
     */
    public function isEmpty()
    {
        return ( count( $this->vector ) == 0 );
    }

    /**
     * Returns true if this vector is not empty and its last item is equal to <i>$value</i>, otherwise returnss false.
     * @param mixed $value
     * @return bool
     */
    public function endsWith( $value )
    {
        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        if( $this->isEmpty() ){
            return false;
        }

        return ( $this->vector[$this->size() - 1] == $value );
    }

    /**
     * Removes all item from <i>$start</i> to <i>$end</i> position.
     * If the <i>$end</i> is equal to -1, the only removed item will be the one at position <i>$start</i>.
     *
     * @param int $start
     * @param int $end
     */
    public function erase($start, $end = -1){

        MDataType::mustBe(array(MDataType::INT, MDataType::INT));

        if($end==-1){
            $end=$start;
        }

        if( $start<$this->count() && $start>=0 ){
            return;
        }

        if( $end<$this->count() && $end>=0 ){
            return;
        }

        if($start>$end){
            return;
        }

        for($k=$start; $k<$end; $k++){
            unset($this->vector[$k]);
        }
    }

    /**
     * Assigns <i>$value</i> to all items in the vector. If <i>$size</i> is different from -1 (the default), the vector
     * is resized to <i>$size</i> beforehand.
     *
     * @param mixed $value
     * @param int $size
     * @return MVector $this
     */
    public function fill($value, $size=-1){

        MDataType::mustBe(array(MDataType::MIXED, MDataType::INT));

        $realSize=$size;
        if($realSize==-1){
            $realSize=$this->count();
        }

        $this->vector=array();
        for($k=0;$k<$realSize;$k++){
            $this->vector[$k]=$value;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        if (0 >= $this->count())
        {
            throw new \OutOfBoundsException();
        }

        return $this->vector[0];
    }

    public function front()
    {
        return $this->first();
    }

    /**
     * Returns the index position of the first occurrence of <i>$value</i> in the vector, searching forward from index position
     * <i>$from</i>. Returns -1 if no item matched.
     *
     * @param mixed $value
     * @param int $from
     * @return int
     */
    public function indexOf( $value, $from = 0 )
    {
        MDataType::mustBe(array(MDataType::MIXED, MDataType::INT));

        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        $to = $this->count() - 1;

        if ($from == -1)
        {
            $to = $from;
        }

        for ( $i = 0; $i == $to; $i++ )
        {
            if ($this->vector[$i] == $value)
            {
                return $i;
            }
        }

        return -1;
    }

    /**
     * Inserts count copies of value at index position <i>$i</i> in the vector.
     *
     * @param int $i
     * @param mixed $value
     * @param int $count
     */
    public function insert( $i, $value, $count = 1 )
    {
        MDataType::mustBe(array(MDataType::INT, MDataType::MIXED, MDataType::INT));

        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        for ( $j = 1; $j <= $count; $j++ )
        {
            array_splice( $this->vector, $i, 0, $value );
        }
    }

    //iterator insert ( iterator before,  T & value ) 
    public function last()
    {
        if ($this->count() <= 0)
        {
            throw new \OutOfBoundsException();
        }

        return $this->vector[$this->count() - 1];
    }

    /**
     * @param mixed $value
     * @param int $from
     * @return int
     */
    public function lastIndexOf( $value, $from = 0 )
    {
        MDataType::mustBe(array(MDataType::MIXED, MDataType::INT));

        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        $index=-1;
        for( $k=$from; $k<$this->size(); $k++ ){
            if( MObject::areEquals($this->at($k), $value) ){
                $index=$k;
            }
        }

        return $index;
    }

    /**
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function pop_back()
    {
        if ($this->count() <= 0)
        {
            throw new \OutOfBoundsException();
        }

        $item = $this->vector[$this->count() - 1];

        $this->removeLast();

        return $item;
    }

    /**
     * Removes the last item in the vector. Calling this function is equivalent to calling remove(size() - 1).
     * The vector must not be empty. If the vector can be empty, call isEmpty() before calling this function.
     */
    public function removeLast(){
        if( count($this->vector)<=0 ){
            return;
        }

        unset( $this->vector[count($this->vector)-1] );
    }

    /**
     * @return mixed
     * @throws \OutOfBoundsException
     */
    public function pop_front()
    {
        if ($this->count() <= 0)
        {
            throw new \OutOfBoundsException();
        }

        $item = $this->vector[0];

        $this->removeFirst();

        return $item;
    }

    /**
     * Removes the first item in the vector. Calling this function is equivalent to calling remove(0). The vector must
     * not be empty. If the vector can be empty, call isEmpty() before calling this function.
     */
    public function removeFirst(){
        $this->remove(0);
    }

    /**
     * @param mixed $value
     */
    public function prepend( $value )
    {
        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        array_unshift( $this->vector, $value );
    }

    /**
     * @param mixed $value
     */
    public function push_back( $value )
    {
        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        $this->vector[] = $value;
    }

    /**
     * @param mixed $value
     */
    public function push_front( $value )
    {
        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        $this->prepend( $value );
    }

    /**
     * @param int $i
     * @param int $count
     * @throws \OutOfBoundsException
     */
    public function remove( $i, $count = 1 )
    {
        MDataType::mustBe(array(MDataType::INT, MDataType::INT));

        for ( $j = $i; $j < $this->count() && $j < ($i + $count); $j++ )
        {
            unset( $this->vector[$j] );
        }
    }

    /**
     * Replaces the item at index position i with value.<br>
     * <i>$i</i> must be a valid index position in the vector (i.e., 0 <= <i>$i</i> < size()).
     *
     * @param int $i
     * @param mixed $value
     * @throws \OutOfBoundsException
     */
    public function replace( $i, $value )
    {
        MDataType::mustBe(array(MDataType::INT, MDataType::MIXED));

        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        if (count( $this->vector ) >= $i)
        {
            throw new \OutOfBoundsException();
        }

        $this->vector[$i] = $value;
    }

    /**
     * Returns the number of items in the vector.
     *
     * @return int
     */
    public function size()
    {
        return count( $this->vector );
    }

    /**
     * Returns true if this vector is not empty and its first item is equal to <i>$value<i>; otherwise returns false.
     *
     * @param mixed $value
     * @return bool
     */
    public function startsWith( $value )
    {
        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        return ( $this->vector[0] == $value );
    }

    /**
     * @param int $i
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getValue( $i, $defaultValue = null )
    {
        MDataType::mustBe(array(MDataType::INT, MDataType::MIXED));

        if ($this->isValidType( $defaultValue ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $defaultValue );
        }

        $value = $this->vector[$i];

        if (is_null( $value ) === true && is_null( $defaultValue ) === false)
        {
            $value = $defaultValue;
        }

        return $value;
    }

    /**
     * Return if a key exists.
     * 
     * @param int $offset
     * @return bool
     */
    public function offsetExists( $offset )
    {
        MDataType::mustBe(array(MDataType::INT));

        return (array_key_exists( $offset, $this->vector ) === true);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet( $offset )
    {
        MDataType::mustBe(array(MDataType::INT));

        if ($this->offsetExists( $offset ))
        {
            return $this->vector[$offset];
        }

        return null;
    }

    /**
     * @param int $offset
     * @param mixed $value
     */
    public function offsetSet( $offset, $value )
    {
        MDataType::mustBe(array(MDataType::INT, MDataType::MIXED));

        if ($this->isValidType( $value ) === false)
        {
            throw new MWrongTypeException( "\$value", $this->getType(), $value );
        }

        if ($offset == null)
        {
            $this->vector[] = $value;
        }
        else
        {
            $this->vector[$offset] = $value;
        }
    }

    /**
     * @param int $offset
     */
    public function offsetUnset( $offset )
    {
        MDataType::mustBe(array(MDataType::INT));

        if ($this->offsetExists( $offset ))
        {
            unset( $this->vector[$offset] );
        }
    }

    public function equals(MVector $other){
        if( $this->size()!= $other->size() ){
            return false;
        }

        for($k=0; $k<$this->count(); $k++){
            if( MObject::areEquals($this->at($k), $other->at($k))===false){
                return false;
            }
        }

        return true;
    }

    //QVector<T> operator+ (  QVector<T> & other ) 
    //QVector<T> & operator+= (  QVector<T> & other )
    //QVector<T> & operator+= (  T & value )
    //QVector<T> & operator<< (  T & value )
    //QVector<T> & operator<< (  QVector<T> & other )
    //QVector<T> & operator= (  QVector<T> & other )
}

