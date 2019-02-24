<?php
/**
 * Directory separator adaptable to OS
 * @param  string $string path given
 * @return string         string with valid directory separator
 */
function sanitizeSlash($string)
{
    return strtr($string, '/\\', DIRECTORY_SEPARATOR);
}
