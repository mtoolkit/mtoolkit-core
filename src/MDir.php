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

use mtoolkit\core\mdir\Filter;
use mtoolkit\core\mdir\SortFlag;


/**
 * The MDir class provides access to directory structures and their contents.<br />
 * <br />
 * A MDir is used to manipulate path names, access information regarding paths
 * and files, and manipulate the underlying file system. It can also be used to
 * access MToolkit's resource system.<br />
 * <br />
 * MToolkit uses "/" as a universal directory separator in the same way that "/"
 * is used as a path separator in URLs. If you always use "/" as a directory
 * separator, MToolkit will translate your paths to conform to the underlying operating
 * system.<br />
 * <br />
 * A MDir can point to a file using either a relative or an absolute path.
 * Absolute paths begin with the directory separator (optionally preceded by a
 * drive specification under Windows). Relative file names begin with a
 * directory name or a file name and specify a path relative to the current
 * directory.
 */
class MDir
{
    /**
     * @var MFileInfo
     */
    private $fileInfo;
    private $count = null;

    /**
     * @var MStringList|null
     */
    private $nameFilters = null;

    private $filters = Filter::NO_FILTER;
    private $sorting = SortFlag::NO_SORT;

    public function __construct($path = '.')
    {
        $this->setPath($path);
    }

    /**
     * Returns the absolute path name of a file in the directory. Does not check
     * if the file actually exists in the directory; but see exists(). Redundant
     * multiple separators or "." and ".." directories in fileName are not
     * removed (see cleanPath()).
     *
     * @param string $fileName
     * @return string
     */
    public function getAbsoluteFilePath(string $fileName): string
    {
        return $this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $fileName;
    }

    /**
     * Returns the absolute path (a path that starts with "/" or with a drive
     * specification), which may contain symbolic links, but never contains
     * redundant ".", ".." or multiple separators.
     *
     * @return string
     */
    public function getAbsolutePath(): string
    {
        return $this->fileInfo->getAbsoluteFilePath();
    }

//    QString	canonicalPath() const

    /**
     * Changes the QDir's directory to dirName.<br />
     * <br />
     * Returns true if the new directory exists; otherwise returns false. Note
     * that the logical cd() operation is not performed if the new directory
     * does not exist.<br />
     * <br />
     * Calling cd("..") is equivalent to calling cdUp().
     *
     * @param string $dirName
     * @return boolean
     */
    public function cd(string $dirName): bool
    {
        $fileInfo = new MFileInfo($this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $dirName);
        if ($fileInfo->exists() === true) {
            $this->fileInfo = $fileInfo;
            return true;
        }

        return false;
    }

    /**
     * Changes directory by moving one directory up from the QDir's current directory.<br />
     * <br />
     * Returns true if the new directory exists; otherwise returns false.
     * Note that the logical cdUp() operation is not performed if the new directory does not exist.
     *
     * @return boolean
     */
    public function cdUp(): bool
    {
        if ($this->fileInfo->isRoot()) {
            return false;
        }

        $cdUp = $this->fileInfo->getAbsolutePath();
        $this->fileInfo = new MFileInfo($cdUp);
        return true;
    }

    /**
     * Returns the total number of directories and files in the directory.
     *
     * @return int
     */
    public function count(): int
    {
        if ($this->count == null) {
            $this->count = 0;

            if ($handle = opendir($this->fileInfo->getAbsoluteFilePath())) {
                while (($file = readdir($handle)) !== false) {
                    if (!in_array($file, array('.', '..'))) {
                        $this->count++;
                    }
                }

                closedir($handle);
            }
        }

        return $this->count;
    }

    /**
     * Returns the name of the directory; this is not the same as the path, e.g.
     * a directory with the name "mail", might have the path "/var/spool/mail".
     * If the directory has no name (e.g. it is the root directory) an empty
     * string is returned.<br />
     * <br />
     * No check is made to ensure that a directory with this name actually
     * exists; but see exists().
     *
     * @return string
     */
    public function getDirName(): string
    {
        return $this->fileInfo->getFileName();
    }

    /**
     * Returns a list of MFileInfo objects for all the files and directories in
     * the directory, ordered according to the name and attribute filters
     * previously set with setNameFilters() and setFilter(), and sorted
     * according to the flags set with setSorting().<br />
     * <br />
     * The name filter, file attribute filter, and sorting specification can be
     * overridden using the nameFilters, filters, and sort arguments.<br />
     * <br />
     * Returns an empty list if the directory is unreadable, does not exist, or
     * if nothing matches the specification.
     *
     * @param MStringList|null $nameFilters
     * @param Filter|int $filters
     * @param SortFlag|int $sort
     * @return \MToolkit\Core\MFileInfoList
     * @throws \Exception
     */
    public function getEntryInfoList(MStringList $nameFilters = null, $filters = Filter::NO_FILTER, $sort = SortFlag::NO_SORT)
    {
        if ($this->fileInfo->exists() === false || $this->fileInfo->isReadable() === false) {
            return new MFileInfoList();
        }

        $files = array();
        if ($handle = opendir($this->fileInfo->getAbsoluteFilePath())) {
            while (false !== ($entry = readdir($handle))) {
                $files[] = $entry;
            }
            closedir($handle);
        }

//        var_dump($files);

        // Apply the name filters
        $nameFilteredFiles = array();

        if ($nameFilters === null) {
            $nameFilters = $this->nameFilters;
        }

        if ($nameFilters != null && $nameFilters->size() > 0) {
            foreach ($nameFilters as $nameFilter) {
                foreach ($files as $file) {
                    if (fnmatch($nameFilter, $this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $file)) {
                        $nameFilteredFiles[] = $file;
                    }
                }
            }
        } else {
            $nameFilteredFiles = $files;
        }

        // Apply the filters        
        $filteredFiles = array();

        foreach ($nameFilteredFiles as $nameFilteredFile) {
            $fileInfo = new MFileInfo($this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $nameFilteredFile);
            $toAdd = true;

            // Filter::DIRS
            if ($fileInfo->isDir() === false && ($filters & Filter::DIRS)) {
                $toAdd = $toAdd && false;
            }

            // Filter::FILES
            if ($fileInfo->isFile() === false && ($filters & Filter::FILES)) {
                $toAdd = $toAdd && false;
            }

            // Filter::NO_SYM_LINKS
            if ($fileInfo->isSymLink() === false && ($filters & Filter::NO_SYM_LINKS)) {
                $toAdd = $toAdd && false;
            }

            // Filter::NO_DOT_AND_DOT_DOT
            if (($nameFilteredFile == "." || $nameFilteredFile == "..") && ($filters & Filter::NO_DOT_AND_DOT_DOT)) {
                $toAdd = $toAdd && false;
            }

            // Filter::NO_DOT
            if (($nameFilteredFile == ".") && ($filters & Filter::NO_DOT)) {
                $toAdd = $toAdd && false;
            }

            // Filter::NO_DOT_DOT
            if (($nameFilteredFile == "..") && ($filters & Filter::NO_DOT_DOT)) {
                $toAdd = $toAdd && false;
            }

            // Filter::ALL_ENTRIES
//            if ($filters & Filter::ALL_ENTRIES)
//            {
//                $toAdd = $toAdd || true;
//            }
            // Filter::READABLE
            if ($fileInfo->isReadable() === false && ($filters & Filter::READABLE)) {
                $toAdd = $toAdd && false;
            }

            // Filter::WRITABLE
            if ($fileInfo->isWritable() === false && ($filters & Filter::WRITABLE)) {
                $toAdd = $toAdd && false;
            }

            // Filter::EXECUTABLE
            if ($fileInfo->isExecutable() === false && ($filters & Filter::EXECUTABLE)) {
                $toAdd = $toAdd && false;
            }

            // Unsupported filter
            if (
                ($filters & Filter::MODIFIED) || ($filters & Filter::HIDDEN) || ($filters & Filter::SYSTEM) || ($filters & Filter::DRIVES)
            ) {
                throw new \Exception("Filter " . $filters . " not yet supported.");
            }

            if ($toAdd === true) {
                $filteredFiles[] = $nameFilteredFile;
            }
        }

        if (($filters & Filter::ALL_DIRS)) {
            $dirs = array_filter(glob($this->getAbsolutePath() . MDir::getSeparator() . '*'), 'is_dir');
            $filteredFiles = array_merge($filteredFiles, $dirs);
            $filteredFiles = array_unique($filteredFiles);
        }

        $filesInfos = array();

        foreach ($filteredFiles as $filteredFile) {
            $filesInfos[] = new MFileInfo($this->getAbsolutePath() . MDir::getSeparator() . $filteredFile);
        }

        // Sort
        if ($sort != SortFlag::NO_SORT) {
            if (($sort & SortFlag::NAME)) {
                usort($filesInfos, array($this, "sortByName"));
            }

            if (($sort & SortFlag::TIME)) {
                usort($filesInfos, array($this, "sortByTime"));
            }

            if (($sort & SortFlag::SIZE)) {
                usort($filesInfos, array($this, "sortBySize"));
            }

            if (($sort & SortFlag::TYPE)) {
                usort($filesInfos, array($this, "sortByType"));
            }

            if (($sort & SortFlag::DIRS_FIRST)) {
                usort($filesInfos, array($this, "sortByDirsFirst"));
            }

            if (($sort & SortFlag::DIRS_LAST)) {
                usort($filesInfos, array($this, "sortByDirsLast"));
            }

            if (($sort & SortFlag::REVERSED)) {
                usort($filesInfos, array($this, "sortReversed"));
            }

            if (($sort & SortFlag::IGNORE_CASE)) {
                usort($filesInfos, array($this, "sortIgnoreCase"));
            }
        }

        $toReturn = new MFileInfoList();
        $toReturn->appendArray($filesInfos);

        return $toReturn;
    }

    private function sortByName(MFileInfo $a, MFileInfo $b): int
    {
        return strcmp($a->getFileName(), $b->getFileName());
    }

    private function sortByTime(MFileInfo $a, MFileInfo $b): int
    {
        return strcmp($a->getLastModified()->format("YmdHis"), $b->getLastModified()->format("YmdHis"));
    }

    private function sortBySize(MFileInfo $a, MFileInfo $b): int
    {
        if ($a->getSize() == $b->getSize()) {
            return 0;
        }
        return ($a->getSize() < $b->getSize()) ? -1 : 1;
    }

    private function sortByType(MFileInfo $a, MFileInfo $b): int
    {
        return strcmp($a->getSuffix(), $b->getSuffix());
    }

    private function sortByDirsFirst(MFileInfo $a, MFileInfo $b): int
    {
        if ($a->isDir() == $b->isDir()) {
            return $this->sortByName($a, $b);
        }

        return strcmp(($a->isDir() ? "a" : "b"), ($b->isDir() ? "a" : "b"));
    }

    private function sortByDirsLast(MFileInfo $a, MFileInfo $b): int
    {
        if ($b->isDir() == $a->isDir()) {
            return $this->sortByName($a, $b);
        }

        return strcmp(($b->isDir() ? "a" : "b"), ($a->isDir() ? "a" : "b"));
    }

    private function sortReversed(MFileInfo $a, MFileInfo $b): int
    {
        return strcmp($b->getFileName(), $a->getFileName());
    }

    private function sortIgnoreCase(MFileInfo $a, MFileInfo $b): int
    {
        return strcmp(strtolower($a->getFileName()), strtolower($b->getFileName()));
    }

    /**
     * Returns a list of the names of all the files and directories in the
     * directory, ordered according to the name and attribute filters previously
     * set with setNameFilters() and setFilter(), and sorted according to the
     * flags set with setSorting().<br />
     * <br />
     * The name filter, file attribute filter, and sorting specification can be
     * overridden using the nameFilters, filters, and sort arguments.<br />
     * <br />
     * Returns an empty list if the directory is unreadable, does not exist, or
     * if nothing matches the specification.
     *
     * @param MStringList|null $nameFilters
     * @param Filter|int $filters
     * @param SortFlag|int $sort
     * @return \MToolkit\Core\MStringList
     */
    public function getEntryList(MStringList $nameFilters = null, $filters = Filter::NO_FILTER, $sort = SortFlag::NO_SORT)
    {
        $fileInfoList = $this->getEntryInfoList($nameFilters, $filters, $sort);

        $toReturn = new MStringList();

        foreach ($fileInfoList as /* @var $fileInfo MFileInfo */
                 $fileInfo) {
            $toAppend = new MString($fileInfo->getAbsoluteFilePath());
            $toReturn->append($toAppend);
        }

        return $toReturn;
    }

    /**
     * Returns true if the file called <i>$name</i> exists; otherwise returns false.<br />
     * If <i>$name</i> is null the <i>$path</i> passed to the construct will be used.
     *
     * @param string|null $name
     * @return boolean
     */
    public function exists(?string $name = null): bool
    {
        if ($name != null) {
            $fileInfo = new MFileInfo($name);
            return $fileInfo->exists();
        }

        return $this->fileInfo->exists();
    }

//    QString	filePath(const QString & fileName) const

    /**
     * Returns true if the directory's path is absolute; otherwise returns false.
     *
     * @return boolean
     */
    public function isAbsolute(): bool
    {
        return $this->fileInfo->isAbsolute();
    }

    /**
     * Returns true if the directory is readable and we can open files by name;
     * otherwise returns false.
     *
     * @return boolean
     */
    public function isReadable(): bool
    {
        return $this->fileInfo->isReadable();
    }

    /**
     * Returns true if the directory path is relative; otherwise returns false.
     * (Under Unix a path is relative if it does not start with a "/").
     *
     * @return boolean
     */
    public function isRelative(): bool
    {
        return $this->fileInfo->isRelative();
    }

    /**
     * Returns true if the directory is the root directory; otherwise returns false.
     *
     * @return boolean
     */
    public function isRoot(): bool
    {
        return $this->fileInfo->isRoot();
    }

    /**
     * Converts the directory path to an absolute path. If it is already
     * absolute nothing happens. Returns true if the conversion succeeded;
     * otherwise returns false.
     *
     * @return boolean
     */
    public function makeAbsolute(): bool
    {
        return $this->fileInfo->makeAbsolute();
    }

    /**
     * Creates a sub-directory called dirName.<br />
     * <br />
     * Returns true on success; otherwise returns false.<br />
     * <br />
     * If the directory already exists when this function is called, it will return false.
     *
     * @param string $dirName
     * @return boolean
     */
    public function mkdir(string $dirName): bool
    {
        return mkdir($this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $dirName);
    }

    /**
     * Creates the directory path dirPath.<br />
     * <br />
     * The function will create all parent directories necessary to create the directory.<br />
     * <br />
     * Returns true if successful; otherwise returns false.<br />
     * <br />
     * If the path already exists when this function is called, it will return true.
     *
     * @param string $path
     * @return boolean
     */
    public function mkpath(string $path): bool
    {
        $dirs = explode(MDir::getSeparator(), $path);
        $count = count($dirs);
        $path = '.';
        for ($i = 0; $i < $count; ++$i) {
            $path .= MDir::getSeparator() . $dirs[$i];
            if (!is_dir($path) && !mkdir($path)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns the path. This may contain symbolic links, but never contains
     * redundant ".", ".." or multiple separators.<br />
     * <br />
     * The returned path can be either absolute or relative (see setPath()).
     *
     * @return boolean
     */
    public function getPath(): bool
    {
        return $this->fileInfo->getPath();
    }

    /**
     * Refreshes the directory information.
     */
    public function refresh(): void
    {
        $this->fileInfo->refresh();
        $this->count = null;
    }

//    QString	relativeFilePath(const QString & fileName) const

    /**
     * Removes the file, <i>$fileName</i>.<br />
     * <br />
     * Returns true if the file is removed successfully; otherwise returns false.
     *
     * @param string $fileName
     * @return boolean
     */
    public function remove($fileName): bool
    {
        return unlink($this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $fileName);
    }

    /**
     * Removes the directory, including all its contents.<br />
     * <br />
     * Returns true if successful, otherwise false.<br />
     * <br />
     * If a file or directory cannot be removed, removeRecursively() keeps going
     * and attempts to delete as many files and sub-directories as possible,
     * then returns false.<br />
     * <br />
     * If the directory was already removed, the method returns true (expected
     * result already reached).<br />
     * <br />
     * Note: this function is meant for removing a small application-internal
     * directory (such as a temporary directory), but not user-visible
     * directories. For user-visible operations, it is rather recommended to
     * report errors more precisely to the user, to offer solutions in case of
     * errors, to show progress during the deletion since it could take several
     * minutes, etc.
     *
     * @return boolean
     */
    public function removeRecursively(): bool
    {
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->fileInfo->getAbsoluteFilePath(), \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $path) {
            $path->isDir() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        }
        rmdir($this->fileInfo->getAbsoluteFilePath());

        return true;
    }

    /**
     * Renames a file or directory from <i>$oldName</i> to <i>$newName</i>, and
     * returns true if successful; otherwise returns false.<br />
     * <br />
     * On most file systems, rename() fails only if oldName does not exist, or
     * if a file with the new name already exists. However, there are also other
     * reasons why rename() can fail. For example, on at least one file system
     * rename() fails if newName points to an open file..
     *
     * @param string $oldName
     * @param string $newName
     * @return boolean
     */
    public function rename(string $oldName, string $newName): bool
    {
        return rename($this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $oldName, $this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $newName);
    }

    /**
     * Removes the directory specified by <i>$dirName</i>.<br />
     * <br />
     * The directory must be empty for rmdir() to succeed.<br />
     * <br />
     * Returns true if successful; otherwise returns false.
     *
     * @param string $dirName
     * @return boolean
     */
    public function rmdir(string $dirName): bool
    {
        return unlink($this->fileInfo->getAbsoluteFilePath() . MDir::getSeparator() . $dirName);
    }

//    bool	rmpath(const QString & dirPath) const

    /**
     * Sets the path of the directory to <i>$path</i>. The path is cleaned of redundant
     * "<i>.</i>", "<i>..</i>" and of multiple separators. No check is made to see whether a
     * directory with this path actually exists; but you can check for yourself
     * using exists().<br />
     * <br />
     * The path can be either absolute or relative. Absolute paths begin with
     * the directory separator "/" (optionally preceded by a drive specification
     * under Windows). Relative file names begin with a directory name or a file
     * name and specify a path relative to the current directory. An example of
     * an absolute path is the string "/tmp/quartz", a relative path might look
     * like "<i>src/fatlib</i>".
     *
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->fileInfo = new MFileInfo($path);
        $this->nameFilters = new MStringList();
    }

    /**
     * Swaps this MDir instance with <i>$other</i>. This function is very fast and never fails.
     *
     * @param \MToolkit\Core\MDir $other
     */
    public function swap(MDir $other): void
    {
        $this->count = $other->count;
        $this->fileInfo = $other->fileInfo;
        $this->filters = $other->filters;
        $this->nameFilters = $other->nameFilters;
        $this->sorting = $other->sorting;
    }

// STATIC 
//    void	addSearchPath(const QString & prefix, const QString & path)
//    QString	cleanPath(const QString & path)
//    QDir	current()
//    QString	currentPath()
//    QFileInfoList	drives()
//    QString	fromNativeSeparators(const QString & pathName)

    /**
     * Returns the user's home directory.
     *
     * @return \MToolkit\Core\MDir
     */
    public static function getHome(): MDir
    {
        return new MDir(MDir::getHomePath());
    }

    /**
     * Returns the absolute path of the user's home directory.
     *
     * @return string
     */
    public static function getHomePath(): string
    {
        return getenv("HOME");
    }

    /**
     * Returns true if <i>$path</i> is absolute; returns false if it is relative.
     *
     * @param string $path
     * @return boolean
     */
    public function isAbsolutePath(string $path): bool
    {
        $fileInfo = new MFileInfo($path);
        return $fileInfo->isAbsolute();
    }

    /**
     * Returns true if the directory <i>$path</i> is relative; otherwise returns
     * false. (Under Unix a path is relative if it does not start with a "/").
     *
     * @param string $path
     * @return boolean
     */
    public function isRelativePath(string $path): bool
    {
        $fileInfo = new MFileInfo($path);
        return $fileInfo->isAbsolute() === false;
    }

//    bool	match(const QString & filter, const QString & fileName)
//    bool	match(const QStringList & filters, const QString & fileName)
//    QDir	root()
//    QString	rootPath()
//    QStringList	searchPaths(const QString & prefix)

    /**
     * Returns the native directory separator: "/" under Unix (including Mac OS
     * X) and "\" under Windows.
     *
     * @return string
     */
    public static function getSeparator(): string
    {
        return DIRECTORY_SEPARATOR;
    }

//    bool	setCurrent(const QString & path)
//    void	setSearchPaths(const QString & prefix, const QStringList & searchPaths)

    /**
     * Returns the system's temporary directory.
     *
     * @return \MToolkit\Core\MDir
     */
    public static function getTemp(): MDir
    {
        return new MDir(MDir::getTempPath());
    }

    /**
     * Returns the absolute path of the system's temporary directory.
     *
     * @return boolean
     */
    public static function getTempPath(): bool
    {
        return sys_get_temp_dir();
    }

    /**
     * Returns the string list set by setNameFilters().
     *
     * @return MStringList
     */
    public function getNameFilters(): MStringList
    {
        return $this->nameFilters;
    }

    /**
     * Sets the name filters used by entryList() and entryInfoList() to the list
     * of filters specified by <i>$nameFilters</i>.
     *
     * @param \MToolkit\Core\MStringList $nameFilters
     * @return \MToolkit\Core\MDir
     */
    public function setNameFilters(MStringList $nameFilters): MDir
    {
        $this->nameFilters = $nameFilters;
        return $this;
    }

    /**
     * Returns the value set by setFilter()
     *
     * @return Filter|int
     */
    public function getFilters(): int
    {
        return $this->filters;
    }

    /**
     * Sets the filter used by entryList() and entryInfoList() to filters. The
     * filter is used to specify the kind of files that should be returned by
     * entryList() and entryInfoList().
     *
     * @param Filter|int $filters
     * @return \MToolkit\Core\MDir
     */
    public function setFilters($filters): MDir
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * Returns the value set by setSorting()
     *
     * @return SortFlag|int
     */
    public function getSorting(): int
    {
        return $this->sorting;
    }

    /**
     * Sets the sort order used by entryList() and entryInfoList().
     *
     * @param SortFlag|int $sorting
     * @return \MToolkit\Core\MDir
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;
        return $this;
    }

//    QString	toNativeSeparators(const QString & pathName)
}
