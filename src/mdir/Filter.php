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

namespace mtoolkit\core\mdir;

/**
 * @const DIRS                      List directories that match the filters.
 * @const ALL_DIRS                  List all directories; i.e. don't apply the filters to directory names.
 * @const FILES                     List files.
 * @const DRIVES                    List disk drives (ignored under Unix).
 * @const NO_SYM_LINKS              Do not list symbolic links (ignored by operating systems that don't support symbolic links).
 *
 * @const NO_DOT                    Do not list the special entry ".".
 * @const NO_DOT_DOT                Do not list the special entry "..".
 *
 * @const READABLE                  List files for which the application has read access. The Readable value needs to be combined with Dirs or Files.
 * @const WRITABLE                  List files for which the application has write access. The Writable value needs to be combined with Dirs or Files.
 * @const EXECUTABLE                List files for which the application has execute access. The Executable value needs to be combined with Dirs or Files.
 * @const MODIFIED                  Only list files that have been modified (ignored on Unix).
 * @const HIDDEN                    List hidden files (on Unix, files starting with a ".").
 * @const SYSTEM                    List system files (on Unix, FIFOs, sockets and device files are included; on Windows, .lnk files are included)
 */
final class Filter
{
    const DIRS = 0x001;
    const ALL_DIRS = 0x400;
    const FILES = 0x002;
    const DRIVES = 0x004;
    const NO_SYM_LINKS = 0x008;
    const NO_DOT_AND_DOT_DOT = 0x6000;
    const NO_DOT = 0x2000;
    const NO_DOT_DOT = 0x4000;
    const ALL_ENTRIES = 0x007;
    const READABLE = 0x010;
    const WRITABLE = 0x020;
    const EXECUTABLE = 0x040;
    const MODIFIED = 0x080;
    const HIDDEN = 0x100;
    const SYSTEM = 0x200;
    const NO_FILTER = 0;
}
