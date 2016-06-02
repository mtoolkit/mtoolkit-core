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
        return $this->apply( $this );
    }

    /**
     * Returns a string rappresenting the object properties and their values.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode( $this->toArray() );
    }

    private function apply( $object )
    {
        $toReturn = array();
        $reflect = new \ReflectionClass( $object );
        $props = $reflect->getProperties(
            \ReflectionProperty::IS_PRIVATE |
            \ReflectionProperty::IS_PUBLIC |
            \ReflectionProperty::IS_PROTECTED
        );
        
        foreach( $props as $prop )
        {
            $propertyName = $prop->getName();
            $reflectionProperty = $reflect->getProperty( $prop->getName() );
            $reflectionProperty->setAccessible( true );
            $propertyValue = $reflectionProperty->getValue( $object );

            if( is_object( $propertyValue ) )
            {
                $toReturn[$propertyName] = $this->apply( $propertyValue );
            }
            else
            {
                $toReturn[$propertyName] = $propertyValue;
            }
        }

        return $toReturn;
    }
}

