<?php declare(strict_types=1);

namespace XoopsModules\News;

/**
 * Copyright (C) 2002 Jason Sheets <jsheets@shadonet.com>.
 * All rights reserved.
 *
 * THIS SOFTWARE IS PROVIDED BY THE PROJECT AND CONTRIBUTORS ``AS IS'' AND
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 * notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in the
 * documentation and/or other materials provided with the distribution.
 *
 * 3. Neither the name of the project nor the names of its contributors
 * may be used to endorse or promote products derived from this software
 * without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE PROJECT AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE PROJECT OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 **/

/**
 * Name: PHP MimeType Class
 *
 * Version: 1.0
 * Date Released: 10/20/02
 *
 * Description: This class allows a PHP script to determine the mime type
 * a file based on the file extension.  This class is handy for PHP versions
 * without the benefit of other tools to determine the mime type.  The class
 * defaults to octet-stream so if the file type is not recognized the user
 * is presented with a file download prompt.
 *
 * NOTES: The mime types for this version are based on Apache 1.3.27
 * mime types may change or need to be added in the future, the mime types
 * are stored in an array so that an awk or other script can automatically
 * generate the code to make the array.
 *
 * Usage:
 *
 * First an instance of the mimetype class must be created, then the
 * getType method should be called with the filename.  It will return
 * the mime type, an example follows.
 *
 * Example:
 *
 * $mimetype = new mimetype();
 * print $mimetype->getType('acrobat.pdf');
 *
 * Author: Jason Sheets <jsheets@shadonet.com>
 *
 * License: This script is distributed under the BSD License, you are free
 * to use, or modify it however you like.  If you find this script useful please
 * e-mail me.
 **/

/**
 * Class Mimetype
 */
class Mimetype
{
    /**
     * @param string $filename
     *
     * @return string
     */
    public function getType(string $filename): string
    {
        // get base name of the filename provided by user
        $filename = \basename($filename);

        // break file into parts seperated by .
        $filename = \explode('.', $filename);

        // take the last part of the file to get the file extension
        $filename = $filename[\count($filename) - 1];

        // find mime type
        return $this->privFindType($filename);
    }

    /**
     * @param string $ext
     *
     * @return string
     */
    public function privFindType(string $ext): string
    {
        // create mimetypes array
        $mimetypes = $this->privBuildMimeArray();

        // return mime type for extension
        return $mimetypes[$ext] ?? 'unknown';
    }

    /**
     * @return array
     */
    public function privBuildMimeArray(): array
    {
        return [
            'ez'      => 'application/andrew-inset',
            'hqx'     => 'application/mac-binhex40',
            'cpt'     => 'application/mac-compactpro',
            'doc'     => 'application/msword',
            'DOC'     => 'application/msword',
            'Doc'     => 'application/msword',
            'xls'     => 'application/vnd.ms-excel',
            'Xls'     => 'application/vnd.ms-excel',
            'XLS'     => 'application/vnd.ms-excel',
            'bin'     => 'application/octet-stream',
            'dms'     => 'application/octet-stream',
            'lha'     => 'application/octet-stream',
            'lzh'     => 'application/octet-stream',
            'exe'     => 'application/octet-stream',
            'class'   => 'application/octet-stream',
            'so'      => 'application/octet-stream',
            'dll'     => 'application/octet-stream',
            'oda'     => 'application/oda',
            'pdf'     => 'application/pdf',
            'PDF'     => 'application/pdf',
            'Pdf'     => 'application/pdf',
            'ai'      => 'application/postscript',
            'eps'     => 'application/postscript',
            'ps'      => 'application/postscript',
            'smi'     => 'application/smil',
            'smil'    => 'application/smil',
            'wbxml'   => 'application/vnd.wap.wbxml',
            'wmlc'    => 'application/vnd.wap.wmlc',
            'wmlsc'   => 'application/vnd.wap.wmlscriptc',
            'bcpio'   => 'application/x-bcpio',
            'vcd'     => 'application/x-cdlink',
            'pgn'     => 'application/x-chess-pgn',
            'cpio'    => 'application/x-cpio',
            'csh'     => 'application/x-csh',
            'dcr'     => 'application/x-director',
            'dir'     => 'application/x-director',
            'dxr'     => 'application/x-director',
            'dvi'     => 'application/x-dvi',
            'spl'     => 'application/x-futuresplash',
            'gtar'    => 'application/x-gtar',
            'hdf'     => 'application/x-hdf',
            'js'      => 'application/x-javascript',
            'skp'     => 'application/x-koan',
            'skd'     => 'application/x-koan',
            'skt'     => 'application/x-koan',
            'skm'     => 'application/x-koan',
            'latex'   => 'application/x-latex',
            'nc'      => 'application/x-netcdf',
            'cdf'     => 'application/x-netcdf',
            'sh'      => 'application/x-sh',
            'shar'    => 'application/x-shar',
            'swf'     => 'application/x-shockwave-flash',
            'sit'     => 'application/x-stuffit',
            'sv4cpio' => 'application/x-sv4cpio',
            'sv4crc'  => 'application/x-sv4crc',
            'tar'     => 'application/x-tar',
            'tcl'     => 'application/x-tcl',
            'tex'     => 'application/x-tex',
            'texinfo' => 'application/x-texinfo',
            'texi'    => 'application/x-texinfo',
            't'       => 'application/x-troff',
            'tr'      => 'application/x-troff',
            'roff'    => 'application/x-troff',
            'man'     => 'application/x-troff-man',
            'me'      => 'application/x-troff-me',
            'ms'      => 'application/x-troff-ms',
            'ustar'   => 'application/x-ustar',
            'src'     => 'application/x-wais-source',
            'xhtml'   => 'application/xhtml+xml',
            'xht'     => 'application/xhtml+xml',
            'zip'     => 'application/x-zip-compressed',
            'Zip'     => 'application/x-zip-compressed',
            'ZIP'     => 'application/x-zip-compressed',
            'au'      => 'audio/basic',
            'XM'      => 'audio/fasttracker',
            'snd'     => 'audio/basic',
            'mid'     => 'audio/midi',
            'midi'    => 'audio/midi',
            'kar'     => 'audio/midi',
            'mpga'    => 'audio/mpeg',
            'mp2'     => 'audio/mpeg',
            'mp3'     => 'audio/mpeg',
            'aif'     => 'audio/x-aiff',
            'aiff'    => 'audio/x-aiff',
            'aifc'    => 'audio/x-aiff',
            'm3u'     => 'audio/x-mpegurl',
            'ram'     => 'audio/x-pn-realaudio',
            'rm'      => 'audio/x-pn-realaudio',
            'rpm'     => 'audio/x-pn-realaudio-plugin',
            'ra'      => 'audio/x-realaudio',
            'wav'     => 'audio/x-wav',
            'wax'     => 'audio/x-windows-media',
            'pdb'     => 'chemical/x-pdb',
            'xyz'     => 'chemical/x-xyz',
            'bmp'     => 'image/bmp',
            'gif'     => 'image/gif',
            'ief'     => 'image/ief',
            //            'jpeg'    => 'image/pjpeg',
            'jpeg'    => 'image/jpeg',
            'jpg'     => 'image/jpeg',
            'jpe'     => 'image/jpeg',
            'png'     => 'image/x-png',
            'tiff'    => 'image/tiff',
            'tif'     => 'image/tif',
            'ico'     => 'image/icon',
            'djvu'    => 'image/vnd.djvu',
            'djv'     => 'image/vnd.djvu',
            'wbmp'    => 'image/vnd.wap.wbmp',
            'ras'     => 'image/x-cmu-raster',
            'pnm'     => 'image/x-portable-anymap',
            'pbm'     => 'image/x-portable-bitmap',
            'pgm'     => 'image/x-portable-graymap',
            'ppm'     => 'image/x-portable-pixmap',
            'rgb'     => 'image/x-rgb',
            'xbm'     => 'image/x-xbitmap',
            'xpm'     => 'image/x-xpixmap',
            'xwd'     => 'image/x-windowdump',
            'igs'     => 'model/iges',
            'iges'    => 'model/iges',
            'msh'     => 'model/mesh',
            'mesh'    => 'model/mesh',
            'silo'    => 'model/mesh',
            'wrl'     => 'model/vrml',
            'vrml'    => 'model/vrml',
            'css'     => 'text/css',
            'html'    => 'text/html',
            'htm'     => 'text/html',
            'asc'     => 'text/plain',
            'txt'     => 'text/plain',
            'Log'     => 'text/plain',
            'log'     => 'text/plain',
            'rtx'     => 'text/richtext',
            'rtf'     => 'text/rtf',
            'sgml'    => 'text/sgml',
            'sgm'     => 'text/sgml',
            'tsv'     => 'text/tab-seperated-values',
            'wml'     => 'text/vnd.wap.wml',
            'wmls'    => 'text/vnd.wap.wmlscript',
            'etx'     => 'text/x-setext',
            'xml'     => 'text/xml',
            'xsl'     => 'text/xml',
            'mpeg'    => 'video/mpeg',
            'mpg'     => 'video/mpeg',
            'mpe'     => 'video/mpeg',
            'qt'      => 'video/quicktime',
            'mov'     => 'video/quicktime',
            'mxu'     => 'video/vnd.mpegurl',
            'avi'     => 'video/x-msvideo',
            'movie'   => 'video/x-sgi-movie',
            'php'     => 'text/php',
            'php3'    => 'text/php3',
            'ice'     => 'x-conference-xcooltalk',
            'unknown' => 'application/octet-stream',
        ];
    }
}
