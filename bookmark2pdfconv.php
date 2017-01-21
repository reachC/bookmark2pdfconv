<?php

/*
 * This file is part of "bookmark2pdfconv" licensed under GPLv3 (https://www.gnu.org/licenses/gpl-3.0.de.html)
 *
 * Copyright (c) reachC 2017
 * https://reachcoding.eu
 */

// show all errors
error_reporting(0); //E_ALL);
ini_set('display_errors', 0); // 1);

// more memory and more execution time for the script
ini_set('memory_limit','1000M');
ini_set('max_execution_time', '800');

// define the MPDF main folder
define('_MPDF_URI', 'mpdf/');
require_once _MPDF_URI. 'mpdf.php';

// get json file from arguments
$file = $argv[1];

if (trim($file) == "/?" || !isset($argv[1])) {
    echo "\nbookmark2pdfconv - Save your firefox bookmarks as pdf file\n";
    echo "licensed under GPLv3\n\n";
    echo "Usage: php bookmark2pdfconv.php [json-file]";
    echo "\n\n";
    exit();
}

// define the array where all urls are saved
$urls = array();

// number of processed pdfs
$pdfcount = 0;

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

// read and parse bookmarks file
$bookmarks = file_get_contents($file);
$bookmarks = utf8_encode($bookmarks);
$bookmarks = json_decode($bookmarks);

// save all urls from the json file in an array
GetUriFromJson($bookmarks, $urls);

// output number of urls
$urlcount = sizeof($urls);
echo "Found " . $urlcount . " bookmarks\n";

foreach($urls as $url) {
    // create mpdf object
    $mpdf = new mPDF('utf-8', 'A3-L');

    $pdfcount++; 

    // get site content
    $html = @file_get_contents($url, NULL, NULL, 0, 100000); // change to higher value than 100000 if html file is not complete

    // check if we get the content
    if (!$html) {
	echo "[" . str_pad($pdfcount, strlen($urlcount), ' ', STR_PAD_LEFT) . "/" . $urlcount . "]" . " Error (" . $http_response_header[0] . ", " . $url . ")\n";
	unset($mpdf);
	continue;
    }

    // get rid of invalid utf-8 character errors
    $html = iconv(mb_detect_encoding($html, mb_detect_order(), true), "UTF-8", $html); //iconv("UTF-8","UTF-8//IGNORE",$html);

    // set mpdf properties
    $mpdf->setBasePath($urlitem);
    $mpdf->WriteHTML($html);

    // create filename
    $filename = preg_replace("/[^a-zA-Z0-9]+/", "", $url);
    $filename2 = str_replace('https', '', $filename);
    $filename3 = str_replace('http', '' , $filename2);
    $filename4 = str_replace('www', '', $filename3);
    $filename5 = trim($filename4);
    $filename5 .= '.pdf';

    // save pdf file
    echo "[" . str_pad($pdfcount, strlen($urlcount), ' ', STR_PAD_LEFT) . "/" . $urlcount . "]" . " Write to file: " . $filename5 . "\n";
    $mpdf->Output('temp/' . $filename5, 'F');

    // destroy mpdf object
    unset($mpdf);
}
