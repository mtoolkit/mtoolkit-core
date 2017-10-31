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
 * The {@link MTime} class provides clock time functions.<br>
 * <br>
 * A {@link MTime} object contains a clock time, i.e. the number of hours, minutes,
 * seconds, and milliseconds since midnight. It can read the current time
 * from the system clock and measure a span of elapsed time. It provides
 * functions for comparing times and for manipulating a time by adding a
 * number of milliseconds.
 *
 * @package mtoolkit\core
 */
class MTime
{
    /**
     * @var int
     */
    private $mseconds = 0;

    /**
     * Constructs a time with hour <i>$h</i>, minute <i>$m</i>, seconds <i>$s</i> and milliseconds <i>ms</i>.
     *
     * @param int $h
     * @param int $m
     * @param int $s
     * @param int $ms
     */
    public function __construct(int $h = 0, int $m = 0, int $s = 0, int $ms = 0)
    {
        $this->setHMS($h, $m, $s, $ms);
    }

    /**
     * Returns a {@link MTime} object containing a time ms milliseconds later
     * than the time of this object (or earlier if ms is negative).
     *
     * @param int $ms
     * @return MTime 
     */
    public function addMSecs($ms)
    {
        if (is_int($ms) === false) {
            throw new MWrongTypeException("\$ms", "int", gettype($ms));
        }

        $mseconds = $this->mseconds;
        $mseconds += $ms;

        $return = new MTime(0, 0, 0, $mseconds);

        return $return;
    }

    /**
     * Returns a {@link MTime} object containing a time s seconds later than
     * the time of this object (or earlier if s is negative).
     *
     * @param $s
     * @return MTime
     */
    public function addSecs($s)
    {
        if (is_int($s) === false) {
            throw new MWrongTypeException("\$s", "int", gettype($s));
        }

        $mseconds = $this->mseconds;
        $mseconds += ($s * 1000);

        $return = new MTime(0, 0, 0, $mseconds);

        return $return;
    }

    /**
     * Returns the number of milliseconds that have elapsed since the last time
     * {@link start()} or {@link restart()} was called.
     *
     * @return int
     */
    public function elapsed()
    {
        $now = microtime();

        return ($now - $this->mseconds);
    }

    /**
     * Returns the hour part (0 to 23) of the time.
     *
     * @return int
     */
    public function hour()
    {
        return (int)($this->mseconds / 1000 / 60 / 60);
    }

    /**
     * Returns <i>true</i> if the time is valid; otherwise returns <i>false</i>.
     * For example, the time 23:30:55.746 is valid, but 24:12:30 is invalid.
     *
     * @return bool
     */
    public function isValid()
    {
        return ($this->mseconds >= 0);
    }

    /**
     * Returns the minute part (0 to 59) of the time.
     *
     * @return int
     */
    public function minute()
    {
        return (int)($this->mseconds / 1000 / 60);
    }

    /**
     * Returns the millisecond part (0 to 999) of the time.
     *
     * @return int
     */
    public function msec()
    {
        return $this->mseconds;
    }

    /**
     * Returns the number of milliseconds from this time to <i>$t</i>.
     * If <i>$t</i> is earlier than this time, the number of milliseconds returned is negative.
     *
     * @param MTime $t
     * @return int
     */
    public function msecsTo(MTime $t)
    {
        return ($t->msec() - $this->mseconds);
    }

    /**
     * Sets this time to the current time and returns the number of milliseconds
     * that have elapsed since the last time {@link start()} or {@link restart()} was called.
     */
    public function restart()
    {
        $this->mseconds = microtime();
    }

    /**
     * Returns the second part (0 to 59) of the time.
     *
     * @return int
     */
    public function second()
    {
        return (int)($this->mseconds / 1000);
    }

    /**
     * Returns the number of seconds from this time to <i>$t</i>. If <i>$t</i> is earlier than this time,
     * the number of seconds returned is negative.
     *
     * @param MTime $t
     * @return int
     */
    public function secsTo(MTime $t)
    {
        $return = $t->msec() - $this->mseconds;

        return (int)($return / 1000);
    }

    /**
     * Sets the time to hour <i>$h</i>, minute <i>$m</i>, seconds <i>$s</i> and milliseconds <i>$ms</i>.
     *
     * @param int $h
     * @param int $m
     * @param int $s
     * @param int $ms
     */
    public function setHMS($h, $m, $s, $ms = 0)
    {
        if (is_int($h) === false) {
            throw new MWrongTypeException("\$h", "int", gettype($h));
        }

        if (is_int($m) === false) {
            throw new MWrongTypeException("\$m", "int", gettype($m));
        }

        if (is_int($s) === false) {
            throw new MWrongTypeException("\$s", "int", gettype($s));
        }

        if (is_int($ms) === false) {
            throw new MWrongTypeException("\$ms", "int", gettype($ms));
        }

        $this->mseconds = $ms + ($s * 1000) + ($m * 60 * 1000) + ($h * 60 * 60 * 1000);
    }

    /**
     * Sets this time to the current time. This is practical for timing:
     * <code>
     * MTime t;
     * t.start();
     * some_lengthy_task();
     * printf("Time elapsed: %d ms", t.elapsed());
     * </code>
     */
    public function start()
    {
        $this->mseconds = microtime();
    }

    /**
     * Returns the current time as reported by the system clock.
     *
     * @return MTime
     */
    public static function currentTime()
    {
        $t = new MTime();
        $t->start();

        return $t;
    }

    /**
     * This is an overloaded function.<br>
     * <br>
     * Returns <i>true</i> if the specified time is valid; otherwise returns <i>false</i>.
     *
     * @param int $h
     * @param int $m
     * @param int $s
     * @param int $ms
     * @return bool
     */
    public static function staticIsValid($h, $m, $s, $ms = 0)
    {
        $t = new MTime($h, $m, $s, $ms);
        return $t->isValid();
    }
}

