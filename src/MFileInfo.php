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
use mtoolkit\core\device\FilePermission;

/**
 * The MFileInfo class provides system-independent file information.
 * 
 * MFileInfo provides information about a file's name and position (path) in the 
 * file system, its access rights and whether it is a directory or symbolic 
 * link, etc. The file's size and last modified/read times are also available. <br />
 * <br />
 * A MFileInfo can point to a file with either a relative or an absolute file 
 * path. Absolute file paths begin with the directory separator "<i>/</i>" (or with a 
 * drive specification on Windows). Relative file names begin with a directory 
 * name or a file name and specify a path relative to the current working 
 * directory. An example of an absolute path is the string "<i>/tmp/quartz</i>". A 
 * relative path might look like "<i>src/fatlib</i>". You can use the function 
 * {@link isRelative()} to check whether a MFileInfo is using a relative or an absolute 
 * file path. You can call the function makeAbsolute() to convert a relative 
 * MFileInfo's path to an absolute path.<br />
 * <br />
 * The file that the MFileInfo works on is set in the constructor or later with 
 * {@link setFile()}. Use {@link exists()} to see if the file exists and 
 * {@link size()} to get its size.
 */
class MFileInfo
{
    /**
     * @var string
     */
    private $file = null;

    /**
     * @var boolean
     */
    private $caching = true;

    /**
     * @var int
     */
    private $size = null;

    /**
     * @var \DateTime
     */
    private $lastModified = null;

    /**
     * @var boolean
     */
    private $isReadable = null;

    /**
     * @var boolean
     */
    private $isWritable = null;

    /**
     * @var boolean
     */
    private $exists = null;

    /**
     * @var \DateTime
     */
    private $created = null;

    /**
     * @var \DateTime
     */
    private $lastRead = null;

    /**
     * @var boolean
     */
    private $isSymLink = null;

    /**
     * @var boolean
     */
    private $isDir = null;

    /**
     * @var boolean
     */
    private $isFile = null;

    /**
     * @var boolean
     */
    private $isExecutable = null;

    /**
     * @var int
     */
    private $ownerId = null;

    /**
     * @var string
     */
    private $owner = null;

    /**
     * @var int
     */
    private $groupId = null;

    /**
     * @var string
     */
    private $group = null;

    /**
     * Constructs a new MFileInfo that gives information about the given file. 
     * The file can also include an absolute or relative path.
     * 
     * @param string $file
     */
    public function __construct(string $file)
    {
        $this->file = $file;
    }

//    QDir	absoluteDir() const

    /**
     * Returns an absolute path including the file name.<br />
     * <br />
     * The absolute path name consists of the full path and the file name. On 
     * Unix this will always begin with the root, '/', directory. On Windows 
     * this will always begin 'D:/' where D is a drive letter, except for 
     * network shares that are not mapped to a drive letter, in which case the 
     * path will begin '//sharename/'. MFileInfo will uppercase drive letters.<br />
     *  <br />
     * Note that MDir does not do this. The code snippet below shows this.
     * 
     * @return string
     */
    public function getAbsoluteFilePath()
    {
        if ($this->isAbsolute() === true)
        {
            return $this->file;
        }

        return realpath($this->file);
    }

    /**
     * Returns a file's path absolute path. This doesn't include the file name.
     * 
     * @return string
     */
    public function getAbsolutePath()
    {
        return dirname($this->getAbsoluteFilePath());
    }

//    QString	baseName() const
//    QString	bundleName() const
//    QString	canonicalFilePath() const
//    QString	canonicalPath() const
//    QString	completeBaseName() const
//    QString	completeSuffix() const

    /**
     * Returns the date and time when the file was created.<br />
     * <br />
     * On most Unix systems, this function returns the time of the last status 
     * change. A status change occurs when the file is created, but it also 
     * occurs whenever the user writes or sets inode information (for example, 
     * changing the file permissions).<br />
     * <br />
     * If neither creation time nor "last status change" time are not available, 
     * returns the same as lastModified().
     * 
     * @return \DateTime
     */
    public function created()
    {
        if ($this->caching == false)
        {
            return \DateTime::createFromFormat("F d Y H:i:s.", date("F d Y H:i:s.", filectime($this->getAbsoluteFilePath())));
        }

        if ($this->created == null)
        {
            $this->created = \DateTime::createFromFormat("F d Y H:i:s.", date("F d Y H:i:s.", filectime($this->getAbsoluteFilePath())));
        }

        return $this->created;
    }

//    QDir	dir() const

    /**
     * Returns true if the file exists; otherwise returns false.
     * @return boolean
     */
    public function exists()
    {
        if ($this->caching == false)
        {
            return file_exists($this->getAbsoluteFilePath());
        }

        if ($this->exists == null)
        {
            $this->exists = file_exists($this->getAbsoluteFilePath());
        }

        return $this->exists;
    }

    /**
     * Returns the name of the file, excluding the path.
     * 
     * @return string
     */
    public function getFileName()
    {
        return basename($this->getAbsoluteFilePath());
    }

    /**
     * Returns the file name, including the path (which may be absolute or relative).
     * 
     * @return string
     */
    public function getFilePath()
    {
        return $this->file;
    }

    /**
     * Returns the group of the file. On Windows, on systems where files do not 
     * have groups, or if an error occurs, an empty string is returned.
     * 
     * @return string
     */
    public function getGroup()
    {
        if ($this->caching == false)
        {
            $groupId = $this->getGroupId();

            if ($groupId == -2)
            {
                return "";
            }

            $group = posix_getgrgid($groupId);

            return $group["name"];
        }

        if ($this->group == null)
        {
            $groupId = $this->getOwnerId();

            if ($groupId == -2)
            {
                $this->group = "";
            }

            $group = posix_getpwuid($groupId);

            $this->group = $group["name"];
        }

        return $this->group;
    }

    /**
     * Returns the id of the group the file belongs to.<br />
     * <br />
     * On Windows and on systems where files do not have groups this function 
     * always returns (uint) -2.
     * 
     * @return int
     */
    public function getGroupId()
    {
        if ($this->caching == false)
        {
            $groupId = filegroup($this->getAbsoluteFilePath());
            if ($groupId === false)
            {
                return -2;
            }

            return $groupId;
        }

        if ($this->groupId == null)
        {
            $groupId = filegroup($this->getAbsoluteFilePath());
            if ($groupId === false)
            {
                $this->groupId = -2;
            }

            $this->groupId = $groupId;
        }

        return $this->groupId;
    }

    /**
     * Returns true if the file path name is absolute, otherwise returns false 
     * if the path is relative.
     * 
     * @return boolean
     */
    public function isAbsolute()
    {
        return ( preg_match("/^(?:\/|\\\|\w\:\\\).$/", $this->file) ==1 );
    }

//    bool	isBundle() const

    /**
     * Returns true if this object points to a directory or to a symbolic link 
     * to a directory; otherwise returns false.
     * 
     * @return boolean
     */
    public function isDir()
    {
        if ($this->caching == false)
        {
            return is_dir($this->getAbsoluteFilePath());
        }

        if ($this->isDir == null)
        {
            $this->isDir = is_dir($this->getAbsoluteFilePath());
        }

        return $this->isDir;
    }

    public function isExecutable()
    {
        if ($this->caching == false)
        {
            return is_executable($this->getAbsoluteFilePath());
        }

        if ($this->isExecutable == null)
        {
            $this->isExecutable = is_executable($this->getAbsoluteFilePath());
        }

        return $this->isExecutable;
    }

    /**
     * Returns true if this object points to a file or to a symbolic link to a 
     * file. Returns false if the object points to something which isn't a file, 
     * such as a directory.
     * 
     * @return boolean
     */
    public function isFile()
    {
        if ($this->caching == false)
        {
            return is_file($this->getAbsoluteFilePath());
        }

        if ($this->isFile == null)
        {
            $this->isFile = is_file($this->getAbsoluteFilePath());
        }

        return $this->isFile;
    }

//    bool	isHidden() const
//    bool	isNativePath() const

    /**
     * Returns true if the user can read the file; otherwise returns false.
     * 
     * @return boolean
     */
    public function isReadable()
    {
        if ($this->caching == false)
        {
            return is_readable($this->getAbsoluteFilePath());
        }

        if ($this->isReadable == null)
        {
            $this->isReadable = is_readable($this->getAbsoluteFilePath());
        }

        return $this->isReadable;
    }

    /**
     * Returns true if the file path name is relative, otherwise returns false 
     * if the path is absolute (e.g. under Unix a path is absolute if it begins 
     * with a "/").
     * 
     * @return boolean
     */
    public function isRelative()
    {
        return (!$this->isAbsolute());
    }

    /**
     * Returns true if the object points to a directory or to a symbolic link to 
     * a directory, and that directory is the root directory; otherwise returns 
     * false.
     * 
     * @return boolean
     */
    public function isRoot()
    {
        return ( dirname($this->getAbsoluteFilePath()) == $this->getAbsoluteFilePath() );
    }

    /**
     * Returns true if this object points to a symbolic link (or to a shortcut 
     * on Windows); otherwise returns false.<br />
     * <br />
     * On Unix (including Mac OS X), opening a symlink effectively opens the 
     * link's target. On Windows, it opens the .lnk file itself.
     * 
     * @return boolean
     */
    public function isSymLink()
    {
        if ($this->caching == false)
        {
            return is_link($this->getAbsoluteFilePath());
        }

        if ($this->isSymLink == null)
        {
            $this->isSymLink = is_link($this->getAbsoluteFilePath());
        }

        return $this->isSymLink;
    }

    /**
     * Returns true if the user can write to the file; otherwise returns false.
     * 
     * @return boolean
     */
    public function isWritable()
    {
        if ($this->caching == false)
        {
            return is_writable($this->getAbsoluteFilePath());
        }

        if ($this->isWritable == null)
        {
            $this->isWritable = is_writable($this->getAbsoluteFilePath());
        }

        return $this->isWritable;
    }

    /**
     * Returns the date and time when the file was last modified.
     * 
     * @return \DateTime
     */
    public function getLastModified()
    {
        if ($this->caching == false)
        {
            return \DateTime::createFromFormat("F d Y H:i:s.", date("F d Y H:i:s.", filemtime($this->getAbsoluteFilePath())));
        }

        if ($this->lastModified == null)
        {
            $this->lastModified = \DateTime::createFromFormat("F d Y H:i:s.", date("F d Y H:i:s.", filemtime($this->getAbsoluteFilePath())));
        }

        return $this->lastModified;
    }

    /**
     * Returns the date and time when the file was last read (accessed).<br />
     * <br />
     * On platforms where this information is not available, returns the same as 
     * {@link lastModified()}.
     * 
     * @return \DateTime
     */
    public function getLastRead()
    {
        if ($this->caching == false)
        {
            return \DateTime::createFromFormat("F d Y H:i:s.", date("F d Y H:i:s.", fileatime($this->getAbsoluteFilePath())));
        }

        if ($this->lastRead == null)
        {
            $this->lastRead = \DateTime::createFromFormat("F d Y H:i:s.", date("F d Y H:i:s.", fileatime($this->getAbsoluteFilePath())));
        }

        return $this->lastRead;
    }

    /**
     * Converts the file's path to an absolute path if it is not already in that 
     * form. Returns true to indicate that the path was converted; otherwise 
     * returns false to indicate that the path was already absolute.
     * 
     * @return boolean
     */
    public function makeAbsolute()
    {
        $this->file = $this->getAbsoluteFilePath();
        return true;
    }

    /**
     * Returns the owner of the file. On systems where files do not have 
     * owners, or if an error occurs, an empty string is returned.
     * 
     * @return string
     */
    public function getOwner()
    {
        if ($this->caching == false)
        {
            $ownerId = $this->getOwnerId();

            if ($ownerId == -2)
            {
                return "";
            }

            $owner = posix_getpwuid($ownerId);

            return $owner["name"];
        }

        if ($this->owner == null)
        {
            $ownerId = $this->getOwnerId();

            if ($ownerId == -2)
            {
                $this->owner = "";
            }

            $owner = posix_getpwuid($ownerId);

            $this->owner = $owner["name"];
        }

        return $this->owner;
    }

    /**
     * Returns the id of the owner of the file.<br />
     * <br />
     * On Windows and on systems where files do not have owners this function 
     * returns ((uint) -2).
     * 
     * @return int
     */
    public function getOwnerId()
    {
        if ($this->caching == false)
        {
            $ownerId = fileowner($this->getAbsoluteFilePath());
            if ($ownerId === false)
            {
                return -2;
            }

            return $ownerId;
        }

        if ($this->ownerId == null)
        {
            $ownerId = fileowner($this->getAbsoluteFilePath());
            if ($ownerId === false)
            {
                $this->ownerId = -2;
            }

            $this->ownerId = $ownerId;
        }

        return $this->ownerId;
    }

    /**
     * Returns the file's path. This doesn't include the file name.
     * 
     * @return string
     */
    public function getPath()
    {
        return dirname($this->file);
    }

    /**
     * Tests for file permissions. The permissions argument can be several flags 
     * of type FilePermission.<br />
     * <br />
     * On systems where files do not have permissions this function always returns true.
     * 
     * @param FilePermission $permission
     * @return boolean
     */
    public function permission($permission)
    {
        $perms = fileperms($this->getAbsoluteFilePath());

        switch ($permission)
        {
            case FilePermission::READ_OWNER:
                return (($perms & 0x0100) ? true : false);

            case FilePermission::WRITE_OWNER:
                return (($perms & 0x0080) ? true : false);

            case FilePermission::EXE_OWNER:
                return (($perms & 0x0040) ?
                                (($perms & 0x0800) ? null /* 's' */ : true ) :
                                (($perms & 0x0800) ? null /* 'S' */ : false));

            case FilePermission::READ_USER:
                return $this->isReadable();

            case FilePermission::WRITE_USER:
                return $this->isWritable();

            case FilePermission::EXE_USER:
                return $this->isExecutable();

            case FilePermission::READ_GROUP:
                return (($perms & 0x0020) ? true : false);

            case FilePermission::WRITE_GROUP:
                return (($perms & 0x0010) ? true : false);

            case FilePermission::EXE_GROUP:
                return (($perms & 0x0008) ?
                                (($perms & 0x0400) ? null /* 's' */ : true ) :
                                (($perms & 0x0400) ? null /* 'S' */ : false));

            case FilePermission::READ_OTHER:
                return (($perms & 0x0004) ? true : false);

            case FilePermission::WRITE_OTHER:
                return (($perms & 0x0002) ? true : false);

            case FilePermission::EXE_OTHER:
                return (($perms & 0x0001) ?
                                (($perms & 0x0200) ? null /* 't' */ : true ) :
                                (($perms & 0x0200) ? null /* 'T' */ : false));
        }

        return true;
    }

    /**
     * Returns the complete MList<FilePermission> for the file.
     * 
     * @return \MToolkit\Core\MList
     */
    public function permissions()
    {
        $perms = fileperms($this->getAbsoluteFilePath());
        $permissions = new MList();

        // Owner
        if ($perms & 0x0100)
        {
            $permissions->append(FilePermission::READ_OWNER);
        }

        if ($perms & 0x0080)
        {
            $permissions->append(FilePermission::WRITE_OWNER);
        }

        if (($perms & 0x0040) & !($perms & 0x0800))
        {
            $permissions->append(FilePermission::EXE_OWNER);
        }

        // Group
        if ($perms & 0x0020)
        {
            $permissions->append(FilePermission::READ_GROUP);
        }
        
        if ($perms & 0x0010)
        {
            $permissions->append(FilePermission::WRITE_GROUP);
        }
        
        if (($perms & 0x0008) && !($perms & 0x0400))
        {
            $permissions->append(FilePermission::EXE_GROUP);
        }

        // World
        if ($perms & 0x0004)
        {
            $permissions->append(FilePermission::READ_OTHER);
        }
        
        if ($perms & 0x0002) 
        {
            $permissions->append(FilePermission::WRITE_OTHER);
        }
        
        if (($perms & 0x0001) && !($perms & 0x0200))
        {
            $permissions->append(FilePermission::EXE_OTHER);
        }

        if( $this->isWritable() )
        {
            $permissions->append(FilePermission::READ_USER);
        }
        
        if( $this->isReadable() )
        {
            $permissions->append(FilePermission::WRITE_USER);
        }
        
        if( $this->isExecutable() )
        {
            $permissions->append(FilePermission::EXE_USER);
        }
        
        return $permissions;
    }

    /**
     * Refreshes the information about the file, i.e. reads in information from 
     * the file system the next time a cached property is fetched.
     */
    public function refresh()
    {
        $this->size = null;
        $this->lastModified = null;
        $this->isReadable = null;
        $this->isWritable = null;
        $this->exists = null;
        $this->created = null;
        $this->isSymLink = null;
        $this->isDir = null;
        $this->isFile = null;
        $this->isExecutable = null;
        $this->ownerId = null;
        $this->owner = null;
        $this->group = null;
        $this->groupId = null;
        $this->lastRead = null;
    }

    /**
     * Sets the file that the QFileInfo provides information about to file.<br />
     * <br />
     * The file can also include an absolute or relative file path. Absolute 
     * paths begin with the directory separator (e.g. "/" under Unix) or a drive 
     * specification (under Windows). Relative file names begin with a directory 
     * name or a file name and specify a path relative to the current directory.
     * 
     * @param string $file
     */
    public function setFile(string $file)
    {
        $this->file = $file;
        $this->refresh();
    }

    /**
     * Returns the file size in bytes. If the file does not exist or cannot be 
     * fetched, 0 is returned.
     * 
     * @return int
     */
    public function getSize()
    {
        if ($this->caching == false)
        {
            return filesize($this->getAbsoluteFilePath());
        }

        if ($this->size == null)
        {
            $this->size = filesize($this->getAbsoluteFilePath());
        }

        return $this->size;
    }

    /**
     * Returns the suffix of the file.<br />
     * <br />
     * The suffix consists of all characters in the file after (but not 
     * including) the last '.'.
     * 
     * @return string
     */
    public function getSuffix()
    {
        $info = pathinfo($this->getAbsoluteFilePath());
        return $info['extension'];
    }

    public function getCaching()
    {
        return $this->caching;
    }

    /**
     * If enable is true, enables caching of file information. If enable is 
     * false caching is disabled.<br />
     * <br />
     * When caching is enabled, MFileInfo reads the file information from the 
     * file system the first time it's needed, but generally not later.<br />
     * <br />
     * Caching is enabled by default.
     * 
     * @param boolean $caching
     * @return \MToolkit\Core\MFileInfo
     */
    public function setCaching(bool $caching): MFileInfo
    {
        $this->caching = $caching;

        return $this;
    }

//    void	swap(QFileInfo & other)
    
    /**
     * Returns the absolute path to the file or directory a symlink (or shortcut 
     * on Windows) points to, or a an empty string if the object isn't a 
     * symbolic link.<br />
     * <br />
     * This name may not represent an existing file; it is only a string. 
     * MFileInfo::exists() returns true if the symlink points to an existing file.
     * 
     * @return string
     */
    public function symLinkTarget()
    {
        if( $this->isSymLink()===false )
        {
            return "";
        }
        
        return readlink( $this->getAbsoluteFilePath() );
    }
}
