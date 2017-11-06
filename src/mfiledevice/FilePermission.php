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

namespace mtoolkit\core\device;

/**
 * This enum is used by the permission() function to report the permissions and
 * ownership of a file. The values may be OR-ed together to test multiple
 * permissions and ownership values.
 *
 * @const READ_OWNER    The file is readable by the owner of the file.
 * @const WRITE_OWNER    The file is writable by the owner of the file.
 * @const EXE_OWNER      The file is executable by the owner of the file.
 * @const READ_USER      The file is readable by the user.
 * @const WRITE_USER    The file is writable by the user.
 * @const EXE_USER       The file is executable by the user.
 * @const READ_GROUP    The file is readable by the group.
 * @const WRITE_GROUP    The file is writable by the group.
 * @const EXE_GROUP      The file is executable by the group.
 * @const READ_OTHER    The file is readable by anyone.
 * @const WRITE_OTHER    The file is writable by anyone.
 * @const EXE_OTHER      The file is executable by anyone.
 */
final class FilePermission
{
    const READ_OWNER = 0x4000;
    const WRITE_OWNER = 0x2000;
    const EXE_OWNER = 0x1000;
    const READ_USER = 0x0400;
    const WRITE_USER = 0x0200;
    const EXE_USER = 0x0100;
    const READ_GROUP = 0x0040;
    const WRITE_GROUP = 0x0020;
    const EXE_GROUP = 0x0010;
    const READ_OTHER = 0x0004;
    const WRITE_OTHER = 0x0002;
    const EXE_OTHER = 0x0001;

}
