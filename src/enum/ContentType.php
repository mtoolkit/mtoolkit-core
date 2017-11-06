<?php

namespace mtoolkit\core\enum;

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
 * The <i>ContentType</i> defines a lot of content type.
 * The content type could be used as following:
 * <code>
 * <?php
 *  header(ContentType::IMAGE_PNG);
 * ?>
 * </code>
 */
final class ContentType
{
    const APPLICATION_JSON = 'Content-type: application/json';
    const IMAGE_PNG = 'Content-Type: image/png';
    const IMAGE_GIF = 'Content-Type: image/gif';
    const IMAGE_JPEG = 'Content-Type: image/jpeg';
    const TEXT_PLAIN = 'Content-Type: text/plain';
    const TEXT_HTML = 'Content-Type: text/html';
    const APPLICATION_PDF = 'Content-Type: application/pdf';
    const APPLICATION_VDM_MS_EXCEL = 'Content-Type: application/vnd.ms-excel';
    const AUDIO_MPEG = 'Content-Type: audio/mpeg';
    const APPLICATION_MSWORD = 'Content-Type: application/msword';
    const APPLICATION_OCTET_STREAM = 'Content-Type: application/octet-stream';
    const HTTP_UNIX_DIRECTORY = 'Content-Type: httpd/unix-directory';
    const AUDIO_X_PN_REALAUDIO = 'Content-Type: audio/x-pn-realaudio';
    const APPLICATION_XML = 'Content-Type: application/xml';
    const APPLICATION_ZIP = 'Content-Type: application/zip';
    const VIDEO_X_MS_WMV = 'Content-Type: video/x-ms-wmv';
    const VIDEO_MPEG = 'Content-Type: video/mpeg';
    const APPLICATION_POSTSCRIPT = 'Content-Type: application/postscript';
    const APPLICATION_VND_MS_POWERPOINT = 'Content-Type: application/vnd.ms-powerpoint';
    const VIDEO_QUICKTIME = 'Content-Type: video/quicktime';
    const APPLICATION_X_SHOCKWAVE_FLASH = 'Content-Type: application/x-shockwave-flash';
    const AUDIO_BASIC = 'Content-Type: audio/basic';
    const APPLICATION_OGG = 'Content-Type: application/ogg';
    const APPLICATION_XHTML_XML = 'Content-Type: application/xhtml+xml';
    const APPLICATION_RDF_XML = 'Content-Type: application/rdf+xml';
    const AUDIO_X_WAV = 'Content-Type: audio/x-wav';
    const TEXT_XML = 'Content-Type: text/xml';
    const TEXT_RTF = 'Content-Type: text/rtf';
    const AUDIO_MIDI = 'Content-Type: audio/midi';
    const APPLICATION_X_HTTPD_PHP = 'Content-Type: application/x-httpd-php';
    const VIDEO_X_MS_ASF = 'Content-Type: video/x-ms-asf';
    const APPLICATION_X_ZIP_COMPRESSED = 'Content-Type: application/x-zip-compressed';
    const AUDIO_X_MPEG = 'Content-Type: audio/x-mpeg';
    const APPLICATION_X_PDF = 'Content-Type: application/x-pdf';
    const AUDIO_WAV = 'Content-Type: audio/wav';
    const APPLICATION_VND_MOZILLA_XUL_XML = 'Content-Type: application/vnd.mozilla.xul+xml';
    const APPLICATION_X_MSDOS_PROGRAM = 'Content-Type: application/x-msdos-program';
}


