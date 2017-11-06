<?php

namespace mtoolkit\core;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

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
final class MLog extends MObject
{
    public const SIGNAL_INFORMATION_ENTERED = 'SIGNAL_INFORMATION_ENTERED';
    public const SIGNAL_ERROR_ENTERED = 'SIGNAL_ERROR_ENTERED';
    public const SIGNAL_WARNING_ENTERED = 'SIGNAL_WARNING_ENTERED';

    /**
     * @var Logger
     */
    private $log;

    /**
     * MLog constructor.
     * @param string $path
     * @param int $logLevel
     */
    public function __construct(string $path, int $logLevel)
    {
        parent::__construct();
        $this->log = new Logger(MCoreApplication::getApplicationName());
        $this->log->pushHandler(new StreamHandler($path, $logLevel));
    }


    /**
     * Add a information message.
     *
     * @param $text
     * @param array $context
     */
    public function i($text, $context = array())
    {
        $this->log->info($text, $context);
        $this->emit(self::SIGNAL_INFORMATION_ENTERED);
    }

    /**
     * Add a warning message.
     *
     * @param $text
     * @param array $context
     */
    public function w($text, $context = array())
    {
        $this->log->warn($text, $context);
        $this->emit(self::SIGNAL_WARNING_ENTERED);
    }

    /**
     * Add a error message.
     *
     * @param $text
     * @param array $context
     */
    public function e($text, $context = array())
    {
        $this->log->error($text, $context);
        $this->emit(self::SIGNAL_ERROR_ENTERED);
    }
}
