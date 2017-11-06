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

if (class_exists("MToolkit\Core\MString") === false) {
    require_once __DIR__ . '/MString.php';
}

if (class_exists("MToolkit\Core\MCoreApplication") === false) {
    require_once __DIR__ . '/MCoreApplication.php';
}

/**
 * Autoload re-implementation following PSR-0 Standard.
 * No namespaces are defined here, otherwise this method is not
 * called from PHP engine.
 */
spl_autoload_register(function ($rawName) {
    // Don't load an already defined class.
    if (class_exists($rawName) === true) {
        return;
    }

    $name = $rawName;
    $applicationDir = MToolkit\Core\MCoreApplication::getApplicationDirPath();
    $classCompleteName = new \MToolkit\Core\MString($rawName);

    if ($classCompleteName->startsWith($applicationDir->getNamespace())) {
        $name = str_replace($applicationDir->getNamespace(), "", $rawName);
    }

    $classPath = sprintf("%s%s%s%s",
        $applicationDir->getPath(),
        DIRECTORY_SEPARATOR,
        str_replace("\\", DIRECTORY_SEPARATOR, $name),
        '.php'
    );

    // If the file exists and id the class is not declared
    if (file_exists($classPath) === true) {
        require_once $classPath;
    }
});

/**
 * Prints a warning message containing the source code file name and line number
 * if <i>$test</i> is false.<br />
 * M_ASSERT() is useful for testing pre- and post-conditions during development.
 *
 * @param boolean $test
 */
function M_ASSERT($test)
{
    if ($test !== true) {
        $trace = debug_backtrace();
        $lastTrace = $trace[0];

        $output = sprintf(
            'ASSERT FAIL: "%b" in file %s, line %s.<br />'
            , $lastTrace['args'][0]
            , $lastTrace['file']
            , $lastTrace['line']
        );

        trigger_error($output, E_USER_WARNING);
    }
}
