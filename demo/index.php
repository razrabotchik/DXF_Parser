<?php

header('Content-type: text/html; charset=Windows-1251');

require_once '../lib/DXFParser.php';

$dxf = new DXFParser();

if ($dxf->load('pattern.dxf')) {
    ?><pre><?php print_r($dxf->getNames()) ?></pre><?php
    exit();
}



