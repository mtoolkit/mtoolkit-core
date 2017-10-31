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
use mtoolkit\core\enum\HashAlgorithm;
use mtoolkit\core\exception\InvalidHashAlgorithmException;
use mtoolkit\core\exception\MWrongTypeException;

/**
 * With MHash class it is possible to create an hash from a string.<br>
 * The supported algorithms are the same supported from the PHP, use <i>hash_algos()</i> to have a complete list.<br>
 * <br>
 * Example:
 * <code>
 *      $hash = new MHash( HashAlgorithm::SHA512 );
 *      $plaintext = "Hello world!";
 *      $hashed = $hash->getHash( $plaintext );
 * <code>
 * Or:
 * <code>
 *      $hashed = MHash::generate( HashAlgorithm::SHA512 )->of( "Hello world!" );
 * </code>
 * <i>$hashed</i> value will be "f6cde2a0f819314cdde55fc227d8d7dae3d28cc556222a0a8ad66d91ccad4aad6094f517a2182360c9aacf6a3dc323162cb6fd8cdffedb0fe038f55e85ffb5b6".
 *
 * @package MToolkit\Core
 */
class MHash
{
    private $hashAlgorithm;

    /**
     * <i>$hashAlgorithm</i> is the algorithm used to generate the hash when you will call the <i>getHash</i> method.
     *
     * @param HashAlgorithm|string $hashAlgorithm
     * @throws InvalidHashAlgorithmException
     * @throws MWrongTypeException
     */
    public function __construct(string $hashAlgorithm)
    {
        if (in_array($hashAlgorithm, hash_algos()) == false)
        {
            throw new InvalidHashAlgorithmException($hashAlgorithm);
        }

        $this->hashAlgorithm = $hashAlgorithm;
    }

    /**
     * @param string $text
     * @return string
     */
    public function getHash(string $text):string
    {
        return hash($this->hashAlgorithm, $text);
    }

    /**
     * @param HashAlgorithm|string $hashAlgorithm
     * @return MHash
     */
    public static function generate($hashAlgorithm):MHash{
        return new MHash($hashAlgorithm);
    }

    /**
     * @param string $text
     * @return string
     */
    public function of($text){
        return $this->getHash($text);
    }
}
