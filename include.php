<?php

// show all errors (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// more memory and more execution time for the script
ini_set('memory_limit','1000M');
ini_set('max_execution_time', '800');

// define the MPDF main folder
define('_MPDF_URI', 'library/mpdf/');
require_once _MPDF_URI. 'mpdf.php';

// pdf site orientation
define("C_ORIENTATION_LANDSCAPE", 0);
define("C_ORIENTATION_PORTRAIT", 1);

// pdf site format
define("C_FORMAT_A0", 0);
define("C_FORMAT_A1", 1);
define("C_FORMAT_A2", 2);
define("C_FORMAT_A3", 3);
define("C_FORMAT_A4", 4); // default
define("C_FORMAT_A5", 5);
define("C_FORMAT_A6", 6);
define("C_FORMAT_A7", 7);
define("C_FORMAT_A8", 8);
define("C_FORMAT_A9", 9);
define("C_FORMAT_A10", 10);
define("C_FORMAT_B0", 11);
define("C_FORMAT_B1", 12);
define("C_FORMAT_B2", 13);
define("C_FORMAT_B3", 14);
define("C_FORMAT_B4", 15);
define("C_FORMAT_B5", 16);
define("C_FORMAT_B6", 17);
define("C_FORMAT_B7", 18);
define("C_FORMAT_B8", 19);
define("C_FORMAT_B9", 20);
define("C_FORMAT_B10", 21);
define("C_FORMAT_C0", 22);
define("C_FORMAT_C1", 23);
define("C_FORMAT_C2", 24);
define("C_FORMAT_C3", 25);
define("C_FORMAT_C4", 26);
define("C_FORMAT_C5", 27);
define("C_FORMAT_C6", 28);
define("C_FORMAT_C7", 29);
define("C_FORMAT_C8", 30);
define("C_FORMAT_C9", 31);
define("C_FORMAT_C10", 32);
define("C_FORMAT_4A0", 33);
define("C_FORMAT_2A0", 34);
define("C_FORMAT_RA0", 35);
define("C_FORMAT_RA1", 36);
define("C_FORMAT_RA2", 37);
define("C_FORMAT_RA3", 38);
define("C_FORMAT_RA4", 39);
define("C_FORMAT_SRA0", 40);
define("C_FORMAT_SRA1", 41);
define("C_FORMAT_SRA2", 42);
define("C_FORMAT_SRA3", 43);
define("C_FORMAT_SRA4", 44);
define("C_FORMAT_LETTER", 45);
define("C_FORMAT_LEGAL", 46);
define("C_FORMAT_EXECUTIVE", 47);
define("C_FORMAT_FOLIO", 48);
define("C_FORMAT_DEMY", 49);
define("C_FORMAT_ROYAL", 50);