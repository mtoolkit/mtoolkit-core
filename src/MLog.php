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

/**
 * @const SIGNAL_INFORMATION_ENTERED This signal is emitted when an information message is added.
 * @const SIGNAL_ERROR_ENTERED This signal is emitted when an error message is added.
 * @const SIGNAL_WARNING_ENTERED This signal is emitted when an warning message is added.
 */
final class MLog
{
    const MESSAGES = 'MToolkit\Core\MLog\Messages';
    const INFO = "INFO";
    const WARNING = "WARNING";
    const ERROR = "ERROR";

    /**
     * Add a information message.
     * 
     * @param string $tag
     * @param string $text
     */
    public static function i( $tag, $text )
    {
        self::addMessage( MLog::INFO, $tag, $text );
    }

    /**
     * Add a warning message.
     * 
     * @param string $tag
     * @param string $text
     */
    public static function w( $tag, $text )
    {
        self::addMessage( MLog::WARNING, $tag, $text );
    }

    /**
     * Add a error message.
     * 
     * @param string $tag
     * @param string $text
     */
    public static function e( $tag, $text )
    {
        self::addMessage( MLog::ERROR, $tag, $text );
    }

    /**
     * @param string $type
     * @param string $tag
     * @param string $text
     */
    private static function addMessage( $type, $tag, $text )
    {
        if( !MCoreApplication::isDebug() )
        {
            return;
        }

        if( $text instanceof MList || $text instanceof MMap )
        {
            $text = json_encode( $text->__toArray() );
        }
        
        if( is_array( $text ) )
        {
            $text = json_encode( $text );
        }

        /* @var $messages MList */
        $messages = self::getMessages();

        $message = new MLogMessage();
        $message->setType( $type )
                ->setTag( $tag )
                ->setText( $text );

        $messages->append( $message );

        $_SESSION[self::MESSAGES] = serialize( $messages );
    }

    /**
     * Return the message at position <i>$i</i>.
     * 
     * @return MList<MLogMessage>
     */
    public static function getMessages()
    {
        /* @var $messages MList */
        $messages = new MList();
        if( isset( $_SESSION[self::MESSAGES] ) )
        {
            $messages = unserialize( $_SESSION[self::MESSAGES] );
        }

        return $messages;
    }

    /**
     * Remove all stored messages.
     */
    public static function clearAllMessages()
    {
        $_SESSION[self::MESSAGES] = serialize( new MList() );
    }

}

/**
 * <b>Don't istantiate an object of type MLogMessage.</b>
 * It is used only from <i>MLog</i> class to store a MLogMessage
 */
final class MLogMessage
{
    /**
     * @var string
     */
    private $type = null;

    /**
     * @var string
     */
    private $tag = null;

    /**
     * @var string
     */
    private $text = null;

    /**
     * @var \DateTime
     */
    private $time = null;

    public function __construct()
    {
        $this->time = new \DateTime();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return \MToolkit\Core\MLogMessage
     */
    public function setType( $type )
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     * @return \MToolkit\Core\MLogMessage
     */
    public function setTag( $tag )
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return \MToolkit\Core\MLogMessage
     */
    public function setText( $text )
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

}
