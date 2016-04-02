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
 * In the MDataType class there is a collection of static method to check the
 * type of the data.<br />
 * The data type supported are:
 * <ul>
 *      <li>int</li>
 *      <li>long</li>
 *      <li>boolean</li>
 *      <li>float</li>
 *      <li>double</li>
 *      <li>string</li>
 *      <li>nullable int</li>
 *      <li>nullable long</li>
 *      <li>nullable boolean</li>
 *      <li>nullable float</li>
 *      <li>nullable double</li>
 *      <li>nullable string</li>
 *      <li>nullable null</li>
 * </ul>
 * <br />
 * If the data type is not corrected a <i>MWrongTypeException</i> will be
 * throwed.
 */
class MDataType
{
    const INT = 1;
    const LONG = 2;
    const BOOLEAN = 4;
    const FLOAT = 8;
    const DOUBLE = 16;
    const STRING = 32;
    const NULL = 64;
    const __ARRAY = 128;
    const OBJECT = 256;
    const RESOURCE = 512;
    const MIXED = 2048;
    const UNKNOWN = 1024;

    /**
     * Checks the type of the arguments of the method caller with the type passed
     * in <i>$dataTypes</i>.<br>
     * If the number of elements in <i>$dataTypes</i> isn't the same of the number
     * of the arguments of the caller, it will throw an exception.<br>
     * If the type mismatch then it will be throw a {@link MWrongTypeException MWrongTypeException}.
     *
     * @param array|int $dataTypes,... unlimited OPTIONAL The possible values of the elements of the array are: case
     *                                 MDataType::INT, MDataType::LONG, MDataType::BOOLEAN, MDataType::FLOAT: return
     *                                 "MDataType::FLOAT, MDataType::DOUBLE, MDataType::STRING, MDataType::NULL,
     *                                 MDataType::__ARRAY, MDataType::OBJECT, MDataType::RESOURCE, MDataType::MIXED
     * @throws \Exception|MWrongTypeException
     */
    public static function mustBe( $dataTypes )
    {
        $params = func_get_args();
        if( isset($params[0]) && is_array( $params[0] ) )
        {
            $dataTypeArray = $params[0];
        }
        else
        {
            $dataTypeArray = $params;
        }

        $trace = debug_backtrace();
        $caller = $trace[1];
        $args = $caller["args"];

        for( $i = 0; $i < count( $args ); $i++ )
        {
            $dataType = $dataTypeArray[$i];

            if( $dataType === MDataType::MIXED )
            {
                continue;
            }

            if( ($dataType) & MDataType::getType( $args[$i] ) )
            {
                continue;
            }

            $callerName = (isset($caller["class"]) ? $caller["class"] . "::" . $caller["function"] : $caller["function"]);
            $mustBe = MDataType::getTypeName( $dataType );
            $itIs = MDataType::getTypeName( MDataType::getType( $args[$i] ) );

            $message = "Argument " . ($i + 1) . " passed to " . $callerName . " must be of the type " . $mustBe . ", " . $itIs
                . " given, called in " . $caller["file"] . " on line " . $caller["line"] . " and defined";

            throw new MWrongTypeException( $message );
        }
    }

    public static function getTypeName( $value )
    {
        $types = array();
        if( MDataType::INT & $value )
        {
            $types[] = "MDataType::INT";
        }
        if( MDataType::LONG & $value )
        {
            $types[] = "MDataType::LONG";
        }
        if( MDataType::BOOLEAN & $value )
        {
            $types[] = "MDataType::BOOLEAN";
        }
        if( MDataType::FLOAT & $value )
        {
            $types[] = "MDataType::FLOAT";
        }
        if( MDataType::DOUBLE & $value )
        {
            $types[] = "MDataType::DOUBLE";
        }
        if( MDataType::STRING & $value )
        {
            $types[] = "MDataType::STRING";
        }
        if( MDataType::NULL & $value )
        {
            $types[] = "MDataType::NULL";
        }
        if( MDataType::__ARRAY & $value )
        {
            $types[] = "MDataType::__ARRAY";
        }
        if( MDataType::OBJECT & $value )
        {
            $types[] = "MDataType::OBJECT";
        }
        if( MDataType::RESOURCE & $value )
        {
            $types[] = "MDataType::RESOURCE";
        }

        if( count( $types ) <= 0 )
        {
            return "MDataType::UNKNOWN";
        }

        return implode( " or ", $types );
    }

    /**
     * Returns the type of <i>$value</i>.
     *
     * @param mixed $value
     * @return int MDataType::INT, MDataType::LONG, MDataType::BOOLEAN, etc
     */
    public static function getType( $value )
    {
        if( is_int( $value ) )
        {
            return MDataType::INT;
        }

        if( is_long( $value ) )
        {
            return MDataType::LONG;
        }

        if( is_bool( $value ) )
        {
            return MDataType::BOOLEAN;
        }

        if( is_float( $value ) )
        {
            return MDataType::FLOAT;
        }

        if( is_double( $value ) )
        {
            return MDataType::DOUBLE;
        }

        if( is_string( $value ) )
        {
            return MDataType::STRING;
        }

        if( $value == null )
        {
            return MDataType::NULL;
        }

        if( is_array( $value ) )
        {
            return MDataType::__ARRAY;
        }

        if( is_object( $value ) )
        {
            return MDataType::OBJECT;
        }

        if( is_resource( $value ) )
        {
            return MDataType::RESOURCE;
        }

        return MDataType::UNKNOWN;
    }

    /**
     * Throw an exception if <i>$value</i> is not an int.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeInt( $value )
    {
        if( is_int( $value ) === false )
        {
            throw new MWrongTypeException( '\$value', 'int', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a long.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeLong( $value )
    {
        if( is_long( $value ) === false )
        {
            throw new MWrongTypeException( '\$value', 'long', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a boolean.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeBoolean( $value )
    {
        if( is_bool( $value ) === false )
        {
            throw new MWrongTypeException( '\$value', 'boolean', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a float.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeFloat( $value )
    {
        if( is_float( $value ) === false )
        {
            throw new MWrongTypeException( '\$value', 'float', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a double.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeDouble( $value )
    {
        if( is_double( $value ) === false )
        {
            throw new MWrongTypeException( '\$value', 'double', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a string.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeString( $value )
    {
        if( is_string( $value ) === false )
        {
            throw new MWrongTypeException( '\$value', 'string', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a null.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeNull( $value )
    {
        if( $value != null )
        {
            throw new MWrongTypeException( '\$value', 'null', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not an int or null.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeNullableInt( $value )
    {
        if( is_int( $value ) === false && $value != null )
        {
            throw new MWrongTypeException( '\$value', 'int|null', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a long or null.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeNullableLong( $value )
    {
        if( is_long( $value ) === false && $value != null )
        {
            throw new MWrongTypeException( '\$value', 'long|null', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a boolean or null.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeNullableBoolean( $value )
    {
        if( is_bool( $value ) === false && $value != null )
        {
            throw new MWrongTypeException( '\$value', 'boolean|null', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a float or null.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeNullableFloat( $value )
    {
        if( is_float( $value ) === false && $value != null )
        {
            throw new MWrongTypeException( '\$value', 'float|null', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a double or null.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeNullableDouble( $value )
    {
        if( is_double( $value ) === false && $value != null )
        {
            throw new MWrongTypeException( '\$value', 'double|null', gettype( $value ) );
        }
    }

    /**
     * Throw an exception if <i>$value</i> is not a string or null.
     *
     * @param mixed $value
     * @throws MWrongTypeException
     */
    public static function mustBeNullableString( $value )
    {
        if( is_string( $value ) === false && $value != null )
        {
            throw new MWrongTypeException( '\$value', 'string|null', gettype( $value ) );
        }
    }

    public static function convert( $value, $type )
    {
        switch( $type )
        {
            case MDataType::BOOLEAN:
                return settype( $value, "boolean" );
            case MDataType::DOUBLE:
                return settype( $value, "float" );
            case MDataType::FLOAT:
                return settype( $value, "float" );
            case MDataType::INT:
                return settype( $value, "integer" );
            case MDataType::LONG:
                return settype( $value, "integer" );
            case MDataType::NULL:
                return null;
            case MDataType::STRING:
                return settype( $value, "string" );
        }

        return $value;
    }
}
