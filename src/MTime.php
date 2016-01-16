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

class MTime
{
    private $mseconds=0;
        
    public function __construct( $h=0, $m=0, $s = 0, $ms = 0 )
    {
        // I controlli sui parametri non sono stati fatti qui,
        // Perchè vengono effettuati dal metodo seguente
        $this->setHMS( $h, $m, $s, $ms );
    }
    
    public function addMSecs ( $ms )
    {
        if( is_int( $ms )===false )
        {
            throw new MWrongTypeException( "\$ms", "int", gettype($ms) );
        }
        
        $mseconds=$this->mseconds;
        $mseconds+=$ms;
        
        $return=new MTime( 0, 0, 0, $mseconds );
        
        return $return;
    }
    
    public function addSecs ( $s )
    {
        if( is_int( $s )===false )
        {
            throw new MWrongTypeException( "\$s", "int", gettype($s) );
        }
        
        $mseconds=$this->mseconds;
        $mseconds+=($s * 1000);
        
        $return=new MTime( 0, 0, 0, $mseconds );
        
        return $return;
    }
    
    public function elapsed ()
    {
        $now=microtime();
        
        return ( $now - $this->mseconds );
    }
    
    public function hour () 
    {
        return (int)( $this->mseconds/1000/60/60 );
    }
    
    //public function isNull (){}
    
    public function isValid ()
    {
        return ( $this->mseconds>=0 );
    }
    
    public function minute () 
    {
        return (int)( $this->mseconds/1000/60 );
    }
    
    public function msec () 
    {
        return $this->mseconds;
    }
    
    public function msecsTo ( MTime $t ) 
    {
        return ( $t->msec() - $this->mseconds );
    }
    
    public function restart ()
    {
        $this->mseconds=microtime();
    }
    
    public function second () 
    {
        return (int)( $this->mseconds/1000 );
    }
    
    public function secsTo ( MTime $t ) 
    {
        $return= $t->msec() - $this->mseconds ;
        
        return (int)( $return /1000 );
    }
    
    public function setHMS ( $h, $m, $s, $ms = 0 )
    {
        if( is_int( $h )===false )
        {
            throw new MWrongTypeException( "\$h", "int", gettype($h) );
        }
        
        if( is_int( $m )===false )
        {
            throw new MWrongTypeException( "\$m", "int", gettype($m) );
        }
        
        if( is_int( $s )===false )
        {
            throw new MWrongTypeException( "\$s", "int", gettype($s) );
        }
        
        if( is_int( $ms )===false )
        {
            throw new MWrongTypeException( "\$ms", "int", gettype($ms) );
        }
        
        $this->mseconds=$ms + ( $s*1000 ) + ( $m*60*1000 ) + ( $h*60*60*1000 );
    }
        
    public function start ()
    {
        $this->mseconds=microtime();
    }
        
    public function toString ( /* string */ $format ) 
    {
        
    }
     
    // Static Methods
    
    public static function currentTime ()
    {
        $t=new MTime();
        $t->start();
        
        return $t;
    }
            
//QTime	fromString ( const QString & string, Qt::DateFormat format = Qt::TextDate )
//QTime	fromString ( const QString & string, const QString & format )
        
    public static function staticIsValid( $h, $m, $s, $ms = 0 )
    {
        // I controlli sui parametri non sono stati fatti qui,
        // Perchè vengono effettuati dal metodo seguente
        $t=new MTime( $h, $m, $s, $ms );
        return $t->isValid();
    }
}

