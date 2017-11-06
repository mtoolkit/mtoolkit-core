<?php

namespace mtoolkit\core\datetime;

/**
 * In <i>TimeInMilliseconds</i> enum are defined some constatns indicating
 * how many milliseconds there are in a:
 * <ul>
 *  <li>millisecond</li>
 *  <li>second</li>
 *  <li>minute</li>
 *  <li>hour</li>
 *  <li>day</li>
 *  <li>week</li>
 * </ul>
 */
final class TimeInMilliseconds
{
    const MILLISECOND = 1;
    const SECOND = 1000;
    const MINUTE = 60000;
    const HOUR = 3600000;
    const DAY = 86400000;
    const WEEK = 604800000;
}
