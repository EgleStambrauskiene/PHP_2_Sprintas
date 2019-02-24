<?php
function view($template, $variables = [])
{
    extract($variables);
    if (file_exists($template)) {
        ob_start();
        include_once $template;
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
