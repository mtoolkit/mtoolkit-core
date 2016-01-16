<?php

namespace mtoolkit\core\mdir;

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
 * @const NAME Sort by name.
 * @const TIME Sort by time (modification time).
 * @const SIZE Sort by file size.
 * @const TYPE Sort by file type (extension).
 * @const UNSORTED Do not sort.
 * @const NO_SORT Not sorted by default.
 * @const DIRS_FIRST Put the directories first, then the files.
 * @const DIRS_LAST Put the files first, then the directories.
 * @const REVERSED Reverse the sort order.
 * @const IGNORE_CASE Sort case-insensitively.
 * @const LOCALE_AWARE Sort items appropriately using the current locale settings.
 */
final class SortFlag
{
    const NAME = 0x00;
    const TIME = 0x01;
    const SIZE = 0x02;
    const TYPE = 0x80;
    const UNSORTED = 0x03;
    const NO_SORT = -1;
    const DIRS_FIRST = 0x04;
    const DIRS_LAST = 0x20;
    const REVERSED = 0x08;
    const IGNORE_CASE = 0x10;
    const LOCALE_AWARE = 0x40;

}
