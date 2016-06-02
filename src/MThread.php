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

use mtoolkit\core\exception\MThreadException;

abstract class MThread extends MObject
{
    const FINISHED = 'MToolkit\Core\MThread\FINISHED';
    const STARTED = 'MToolkit\Core\MThread\STARTED';

    private $pid = -1;
    private $isRunning = false;
    private $isFinished = false;

    public function __construct( MObject $parent = null )
    {
        parent::__construct( $parent );

        if( function_exists( 'pcntl_fork' ) === false )
        {
            throw new \Exception( 'You have to compile the CGI or CLI version of PHP with --enable-pcntl configuration option when compiling PHP to use MThread' );
        }
    }

    /**
     * Returns true if the thread is running; otherwise returns false.
     *
     * @return boolean
     */
    public function isRunning()
    {
        return $this->isRunning;
    }

    /**
     * Returns true if the thread is finished; otherwise returns false.
     *
     * @return boolean
     */
    public function isFinished()
    {
        return $this->isFinished;
    }

    /**
     * The starting point for the thread.
     * After calling start(), the newly created thread calls this function.
     * You must reimplement this function to advanced thread management.
     * Returning from this method will end the execution of the thread.
     */
    public abstract function run();

    /**
     * Begins execution of the thread by calling run().
     * The operating system will schedule the thread according to the priority parameter.
     * If the thread is already running, this function does nothing.
     * The effect of the priority parameter is dependent on the operating system's scheduling policy.
     * In particular, the priority will be ignored on systems that do not support thread priorities (such as on Linux,
     * see http://linux.die.net/man/2/sched_setscheduler for more details).
     *
     * @throws MThreadException
     */
    public function start()
    {
        $this->pid = \pcntl_fork();

        if( $this->pid == -1 )
        {
            throw new MThreadException( 'Impossible to start thread', 1 );
        }

        if( $this->pid )
        {
            //\pcntl_wait( $status );
        }
        else
        {
            $this->isRunning = true;

            $this->emit( MThread::STARTED );

            $this->run();

            $this->isRunning = false;
            $this->isFinished = true;

            $this->emit( MThread::FINISHED );
            exit();
        }
    }

    public function wait()
    {
        \pcntl_wait( $status );
    }

    /**
     * Forces the current thread to sleep for secs seconds.
     *
     * @param int $seconds
     */
    public static function sleep( $seconds )
    {
        \sleep( $seconds );
    }
}