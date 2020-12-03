<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

if (isset($_GET['file'])) {
  header('Content-Type: application/pdf');
  header('Content-Disposition: attachment; filename=securedFile.pdf');
  header('Pragma: no-cache');

  readfile(__DIR__ . '/' . str_replace('\/', '/', $_GET['file']));

  exit;
}
