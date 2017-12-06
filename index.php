<?php
// Load classes
require_once('src/Database.php');
require_once('src/GitData.php');

// Store url segments in array
$url_segments = array();
if (!empty($_GET['qs'])) {
    $url_segments = explode('/', $_GET['qs']);
}

$ctrl = '';

if (!empty($url_segments[0])) {
    $filepath = 'ctrl/'.$url_segments[0].'.php';
    if (is_file($filepath)) {
        $ctrl = $url_segments[0];
    } else {
        $ctrl = '404';
    }
} else {
    header('Location: /list');
    exit();
}

$filepath = 'ctrl/'.$ctrl.'.php';
if (is_file($filepath)) {
    include($filepath);
}
include('template.php');