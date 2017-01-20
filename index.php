<?php

/*
 * This file is part of "bookmark2pdfconv" licensed under GPLv3 (https://www.gnu.org/licenses/gpl-3.0.de.html)
 *
 * Copyright (c) reachC 2017
 * http://reachcoding.eu
 */

// show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// more memory and more execution time for the script
ini_set('memory_limit','1000M');
ini_set('max_execution_time', '800');

// define the MPDF main folder
define('_MPDF_URI', 'library/mpdf/');
require_once _MPDF_URI. 'mpdf.php';

// define the array where all urls are saved
$urls = array();

// function to parse the json items
function GetUriFromJson($jsonarray, &$urlarray) {
    // get the child items of the json root
    $childs = $jsonarray->children;

    foreach ($childs as $item) {
        if (isset($item->children)) {
            GetUriFromJson($item, $urlarray);
        } elseif (isset($item->uri)) {
            $url = filter_var($item->uri, FILTER_SANITIZE_URL);

            // check if the url is valid
            if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
                $urlarray[] = $url;
            }
        }
    }
}

// read and parse bookmarks file from webbrowser
$bookmarks = file_get_contents('temp/bookmarks.json');
$bookmarks = utf8_encode($bookmarks);
$bookmarks = json_decode($bookmarks);

// save all urls from the json file in an array
GetUriFromJson($bookmarks, $urls);

foreach($urls as $urlitem) {
    // create mpdf object
    $mpdf = new mPDF();

    // get site content
    $html = file_get_contents($urlitem);

    // set mpdf properties
    $mpdf->setBasePath($urlitem);
    $mpdf->WriteHTML($html);

    // create filename
    $filename = str_replace('/', '_', $urlitem);
    $filename2 = str_replace(':', '_', $filename);
    $filename3 = trim($filename2);
    $filename3 .= '.pdf';

    // save pdf file
    $mpdf->Output('temp/' . $filename3, 'F');

    unset($mpdf);
}
