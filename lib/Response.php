<?php
/**
 * Accumulates most methods, using with db
 * @param  string $template an url address
 * @param  array $variables an associative array
 * @return string all formed html is returned as a string. In the case of a failure a warning string is returned.
 */
function view($template, $variables = [])
{
    // var_dump($template);
    // extract(&$array) treats an associative array keys as variable names and values as variable values.
    // Returns the number of variables successfully imported into the symbol table.
    extract($variables);
    // Checks whether a file or directory, under given path to it, exists.
    if (file_exists($template)) {
        // This function will turn output buffering on.
        ob_start();
        include_once $template;
        // Gets the current buffer contents and delete current output buffer.
        return ob_get_clean();
    }
    return 'Template not found.';
}

function redirect($location = '', $redirectId = 302)
{
    header('Location: ' . $location, true, $redirectId);
    exit();
}

function fileView($file, $mime)
{
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mime);
    header('Content-Transfer-Encoding: Binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit();
}

function fileDownload($file)
{
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'. basename($file).'"');
    header('Content-Transfer-Encoding: Binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit();
}
