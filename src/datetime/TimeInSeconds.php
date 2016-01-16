<?php
namespace mtoolkit\core\datetime;

/**
 * In <i>TimeInSeconds</i> enum are defined some constatns indicating 
 * how many seconds there are in a:
 * <ul>
 *  <li>millisecond</li>
 *  <li>second</li>
 *  <li>minute</li>
 *  <li>hour</li>
 *  <li>day</li>
 *  <li>week</li>
 * </ul>
 */
final class TimeInSeconds
{
    const MILLISECOND=0.001;
    const SECOND=1;
    const MINUTE=60;
    const HOUR=3600;
    const DAY=86400;
    const WEEK=604800;
}
