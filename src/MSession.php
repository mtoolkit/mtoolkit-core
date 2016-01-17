<?php

namespace mtoolkit\core;

if (session_id() == '')
{
    @session_start();
}

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

class MSession
{

    /**
     * Return the value saved in <i>$_SESSION</i> with <i>$key</i>.
     * 
     * @param string $key
     * @return mixed
     */
    public static function get( $key )
    {
        if (isset( $_SESSION[$key] ) === false)
        {
            return null;
        }

        return unserialize( $_SESSION[$key] );
    }

    /**
     * Save the <i>$value</i> in <i>$_SESSION</i> with <i>$key</i>.
     * 
     * @param string $key
     * @param mixed $value
     */
    public static function set( $key, $value )
    {
        $_SESSION[$key] = serialize( $value );
    }

    /**
     * Delete the value in <i>$_SESSION</i> with <i>$key</i>.
     * 
     * @param string $key
     */
    public static function delete( $key )
    {
        if (isset( $_SESSION[$key] ) === false)
        {
            return;
        }

        unset( $_SESSION[$key] );
    }

    /**
     * Remove all stored session values.
     */
    public static function deleteAll()
    {
        session_destroy();
    }

}