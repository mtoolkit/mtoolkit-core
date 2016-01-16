<?php
namespace mtoolkit\core\json;

class MJsonObject
{

    /**
     * Returns an array rappresenting the object properties and their values.
     * 
     * @return array 
     */
    public function toArray()
    {
        $reflect = new \ReflectionClass( $this );
        $propertyList = $reflect->getProperties();
        $toReturn = array();

        foreach ( $propertyList as &$property )
        {
            $reflectionProperty = $reflect->getProperty( $property->getName() );
            $reflectionProperty->setAccessible( true );
            
            $propertyValue=$reflectionProperty->getValue($this);
            
            if ( is_object( $propertyValue ) )
            {
                if( method_exists( $propertyValue, 'toArray' ) )
                {
                    $toReturn[$property->getName()] = $propertyValue->toArray();
                }
                else
                {
                    $toReturn[$property->getName()] = null;
                }
            }
            else
            {
                $toReturn[$property->getName()] = $propertyValue;
            }
        }
        return $toReturn;
    }

    /**
     * Sets the properties of the class, using the <i>$json</i>.<br>
     * The default implementation returns null.
     *
     * @param array $json
     * @return null
     */
    public static function fromArray( array $json )
    {
        return null;
    }

    /**
     * Returns a string rappresenting the object properties and their values.
     * 
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
    
    /**
     * Sets the properties of the class, using the <i>$json</i>.
     * The default implementation returns null.
     * 
     * @param string $json
     * @return MJsonObject
     */
    public static function fromJson( $json )
    {
        return MJsonObject::fromArray( json_decode($json) );
    }
}

