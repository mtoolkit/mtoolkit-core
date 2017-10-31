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

class MObject
{
    const SIGNALS = 'MToolkit\Core\MObject\Signals';

    /**
     * @var MSlot[]
     */
    private $signals = array();

    /**
     * @var array
     */
    private $properties = array();

    /**
     * @var bool
     */
    private $signalsBlocked = false;

    /**
     * @var MPost
     */
    private static $post = null;

    /**
     * @var MGet
     */
    private static $get = null;

    /**
     * @var MObject
     */
    private $parent = null;

    /**
     * @var string
     */
    private $toString = null;

    /**
     * Constructs an object with parent object <i>$parent</i>.
     *
     * @param \MToolkit\Core\MObject $parent
     */
    public function __construct( MObject $parent = null )
    {
        $this->parent = $parent;
        $reflect = new \ReflectionClass( $this );
        $this->toString = uniqid( $reflect->getShortName() . "_", true );
    }

    /**
     * Returns true if signals are blocked; otherwise returns false.<br />
     * Signals are not blocked by default.
     *
     * @return bool
     */
    public function getSignalsBlocked()
    {
        return $this->signalsBlocked;
    }

    /**
     * If <i>$signalsBlocked</i> is true, signals emitted by this object are
     * blocked (i.e., emitting a signal will not invoke anything connected to
     * it). If block is false, no such blocking will occur.
     *
     * @param bool $signalsBlocked
     * @return \MToolkit\Core\MObject
     */
    public function setSignalsBlocked( $signalsBlocked )
    {
        $this->signalsBlocked = $signalsBlocked;
        return $this;
    }

    /**
     * @param \MToolkit\Core\MObject $sender
     * @param string $signal
     * @param \MToolkit\Core\MObject $receiver
     * @param string $method
     */
    public function connect( MObject $sender, $signal, MObject $receiver, $method )
    {
        if( $sender !== $this )
        {
            $sender->connect( $sender, $signal, $receiver, $method );
            return;
        }

        $slot = new MSlot();
        $slot->setMethod( $method )
            ->setReceiver( $receiver );

        $this->signals[$signal][] = $slot;
    }

    public function connectClosure( MObject $sender, $signal, \Closure $callback )
    {
        if( $sender !== $this )
        {
            $sender->connectClosure( $sender, $signal, $callback );
            return;
        }

        $slot = new MSlot();
        $slot->setMethod( $callback )
            ->setReceiver( null );

        $this->signals[$signal][] = $slot;
    }

    /**
     * Disconnects <i>$signal</i> in object <i>$sender</i> from method in object
     * <i>$receiver</i>. Returns true if the connection is successfully broken;
     * otherwise returns false.
     *
     * @param MObject $sender
     * @param $signal
     * @param MObject $receiver
     * @param string $method
     * @return bool
     */
    public function disconnect( MObject $sender, $signal, MObject $receiver, $method )
    {
        if( $this !== $sender )
        {
            $sender->disconnect( $sender, $signal, $receiver, $method );
            return false;
        }

        if( !isset($this->signals[$signal]) )
        {
            return false;
        }

        unset($this->signals[$signal]);

        return true;
    }

    /**
     * Call every slots connected with the <i>$signal</i>.
     *
     * @param string $signal
     * @param mixed $args
     */
    public function emit( $signal, $args = null )
    {
        if( $this->signalsBlocked )
        {
            return;
        }

        if( isset($this->signals[$signal]) === false )
        {
            return;
        }

        /* @var $slots MSlot[] */
        $slots = $this->signals[$signal];

        foreach( $slots as /* @var $slot MSlot */
                 $slot )
        {
            $method = $slot->getMethod();
            $object = $slot->getReceiver();

            if( $args == null )
            {
                if( $object == null )
                {
                    $method();
                }
                else
                {
                    $object->$method();
                }
            }
            else
            {
                if( $object == null )
                {
                    $method( $args );
                }
                else
                {
                    $object->$method( $args );
                }
            }
        }
    }

    /**
     * Remove all signals
     */
    public function disconnectSignals()
    {
        $this->signals = array();
    }

    /**
     * Returns the parent object.
     *
     * @return MObject
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function setParent( MObject $parent )
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @deprecated
     * @param string $key
     * @return string|null
     */
    public function post( $key )
    {
        if( isset($_POST[$key]) === false )
        {
            return null;
        }

        return $_POST[$key];
    }

    /**
     * @deprecated Use
     * @param string $key
     * @return string|null
     */
    public function get( $key )
    {
        if( isset($_GET[$key]) === false )
        {
            return null;
        }

        return $_GET[$key];
    }

    /**
     * Return a MMap with the data in <i>$_POST</i>
     *
     * @return MPost
     */
    public static function getPost()
    {
        if( MObject::$post == null )
        {
            MObject::$post = new MPost();
        }

        return MObject::$post;
    }

    /**
     * Return a MMap with the data in <i>$_GET</i>
     *
     * @return MGet
     */
    public static function getGet()
    {
        if( MObject::$get == null )
        {
            MObject::$get = new MGet();
        }

        return MObject::$get;
    }

    /**
     * Overload of MObject::getProperty( $name ).<br />
     * Returns the value of the object's <i>$name</i> property.
     *
     * @param string $name
     * @return mixed
     */
    public function __get( $name )
    {
        return $this->getProperty( $name );
    }

    /**
     * Overload of MObject::setProperty( $name, $value ).<br />
     * Sets the value of the object's <i>$name</i> property to <i>$value</i>.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set( $name, $value )
    {
        $this->setProperty( $name, $value );
    }

    /**
     * Returns the value of the object's <i>$name</i> property.
     *
     * @param string $name
     * @return mixed
     */
    public function getProperty( $name )
    {
        if( property_exists( $this, $name ) )
        {
            return $this->$name;
        }

        return $this->properties[$name];
    }

    /**
     * Sets the value of the object's <i>$name</i> property to <i>$value</i>.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setProperty( $name, $value )
    {
        if( property_exists( $this, $name ) )
        {
            $this->$name = $value;
            return;
        }

        $this->properties[$name] = $value;
    }

    /**
     * @param bool $obj
     * @return boolean
     */
    public function equals( $obj )
    {
        return MObject::areEquals( $this, $obj );
    }

    /**
     * @param mixed $obj1
     * @param mixed $obj2
     * @return boolean
     * @throws \Exception
     */
    public static function areEquals( $obj1, $obj2 )
    {
        if( gettype( $obj1 ) != gettype( $obj2 ) )
        {
            return false;
        }

        switch( gettype( $obj1 ) )
        {
            case "boolean":
                return ($obj1 === $obj2);
                break;
            case "integer":
            case "double":
            case "string":
                return ($obj1 == $obj2);
                break;
            case "array":
                return (count( array_diff( $obj1, $obj2 ) ) == 0);
                break;
            case "object":
                // Do nothing
                break;
            case "NULL":
                return true;
                break;
            default:
                throw new \Exception( 'Types like "resource" and "unknown type" are incomparable.' );
                break;
        }

        if( get_class( $obj1 ) != get_class( $obj2 ) )
        {
            return false;
        }

        $reflectObj1 = new \ReflectionClass( $obj1 );
        $reflectObj2 = new \ReflectionClass( $obj2 );

        /* @var $propertiesThis \ReflectionProperty[] */
        $propertiesObj1 = $reflectObj1->getProperties();
        /* @var $propertiesObj \ReflectionProperty[] */
        $propertiesObj2 = $reflectObj2->getProperties();

        if( count( $propertiesObj1 ) != count( $propertiesObj2 ) )
        {
            return false;
        }

        for( $i = 0; $i < count( $obj1 ); $i++ )
        {
            /* @var $propertyObj1 \ReflectionProperty */
            $propertyObj1 = $propertiesObj1[$i];
            /* @var $propertyObj2 \ReflectionProperty */
            $propertyObj2 = $propertiesObj2[$i];

            $propertyObj1->setAccessible( true );
            $propertyObj2->setAccessible( true );

            $areEquals = MObject::areEquals( $propertyObj1->getValue( $obj1 ), $propertyObj2->getValue( $obj2 ) );
            if( $areEquals === false )
            {
                return false;
            }
        }

        return true;
    }

    public function __toString()
    {
        return $this->toString;
    }

}

/**
 * @ignore
 */
final class MSlot
{
    /**
     * @var MObject|null
     */
    private $receiver = null;

    /**
     * @var string
     */
    private $method = null;

    /**
     * @return MObject
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param \MToolkit\Core\MObject $receiver
     * @return \MToolkit\Core\MSlot
     */
    public function setReceiver( $receiver )
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod( $method )
    {
        $this->method = $method;
        return $this;
    }

}
