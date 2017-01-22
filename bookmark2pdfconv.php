<?php

/*
 * With this php-cli script you can save all your firefox bookmarks as pdf.
 * Export your bookmarks to a json file and pass it as argument to the script.
 *
 * Example: php bookmark2pdfconv.php bookmarks.json
 *
 * Copyright (c) reachC 2017
 * https://reachcoding.eu
 *
 * This file is part of bookmark2pdfconv.
 *
 * bookmark2pdfconv is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * bookmark2pdfconv is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with bookmark2pdfconv. If not, see <http://www.gnu.org/licenses/>.
 */

// show no errors
error_reporting(0);
ini_set('display_errors', 0);

// more memory and unlimited execution time for the script
ini_set('memory_limit','600M');
ini_set('max_execution_time', 0);

// define the MPDF main folder
define('_MPDF_URI', 'mpdf/');
require_once _MPDF_URI. 'mpdf.php';

// get the json file from arguments
$file = $argv[1];

// show usage lines
if (trim($file) == "/?" || !isset($argv[1])) {
    echo "\nbookmark2pdfconv - Save your firefox bookmarks into pdf files\n\n";
    echo "Usage: php bookmark2pdfconv.php [json-file]";
    echo "\n\n";
    exit();
}

// define the array where all working urls are saved
$urls = array();

// define a array to save all failed urls
$urlerrors = array();

// number of processed pdfs
$pdfcount = 0;

// function to parse the json items
function GetUriFromJson($jsonarray, &$urlarray, &$errorarray) {
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
            } else {
		$errorarray[] = $url;
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

    // count processed pdfs
    $pdfcount++;

    // get site content
    $html = @file_get_contents($url, NULL, NULL, 0, 100000000); // read max. 100MB

    // check if the response is ok
    if (!$html) {
	echo "[" . str_pad($pdfcount, strlen($urlcount), ' ', STR_PAD_LEFT) . "/" . $urlcount . "]" . " Error (" . $http_response_header[0] . ", " . $url . ")\n";
	$urlerrors[] = $url;
	unset($mpdf);
	continue;
    }

    // convert website to utf-8 encoding
    $html = iconv(mb_detect_encoding($html, mb_detect_order(), true), "UTF-8", $html);

    // remove images from html because some gif images cause a out of memory error in mpdf!?!
    $html = preg_replace("/<img[^>]+\>/i", "", $html);

    // set mpdf properties
    $mpdf->setBasePath($urlitem);
    $mpdf->WriteHTML($html);

    // create filename
    $filename = preg_replace("/[^a-zA-Z0-9]+/", "", $url);
    $filename2 = str_replace('https', '', $filename);
    $filename3 = str_replace('http', '' , $filename2);
    $filename4 = str_replace('www', '', $filename3);
    $filename5 = substr(trim($filename4), 0, 70);
    $filename5 .= '.pdf';

    // save pdf file
    echo "[" . str_pad($pdfcount, strlen($urlcount), ' ', STR_PAD_LEFT) . "/" . $urlcount . "] " . $filename5 . "\n";
    $mpdf->Output('output/' . $filename5, 'F');

    // destroy mpdf object
    unset($mpdf);
}

// show the number of failed urls
echo "\nFailed: " . sizeof($urlerrors) . "\n";
