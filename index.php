<?php

/** @noinspection ALL */

require_once './vendor/autoload.php';

// Respuesta del servicios
$response = array (
  'code' => 200,
  'data' => ''
);

if ($_FILES && $_FILES['file']) {
  $uniqId = uniqid();
  $randStart = rand(1, 5);
  $fileName = substr($uniqId, $randStart, 8);
  
  // Mueve el archivo al directorio
  if (move_uploaded_file($_FILES["file"]["tmp_name"], 'files/'.$fileName.'.pdf')) {
    try {
      // Ruta del archivo cargado
      $filePath = __DIR__.'/uploaded-files/'.$fileName.'.pdf';
      
      // Directorio de las imagenes
      $imagesPath = __DIR__.'/images/'.$fileName;
      
      // Crea el directorio donde se guardaran las imagenes
      mkdir($imagesPath, 0777, true);
      
      // Convierte el PDF en imagenes
      $pdf = new Spatie\PdfToImage\Pdf($filePath);
      $pdf->setOutputFormat('jpg')->saveAllPagesAsImages($imagesPath, $fileName.'-');
      
      // Obtiene el contenido del directorio
      $directoryFiles = array_diff(scandir($imagesPath), array ('..', '.'));
      
      foreach ($directoryFiles as $key => $item) {
        $directoryFiles[$key] = $imagesPath.'/'.$directoryFiles[$key];
      }
      
      // Convierte el contenido del directorio en un PDF
      $pdf = new Imagick($directoryFiles);
      $pdf->setImageFormat('pdf');
      $pdf->writeImages('final-files/'.$fileName.'.pdf', true);
      
      // Modifica la respuesta del servicio
      $response['data'] = 'final-files/'.$fileName.'.pdf';
    } catch (\Spatie\PdfToImage\Exceptions\PdfDoesNotExist $error) {
      // Modifica la respuesta del servicio
      $response['data'] = 'Se produjo un error protegiendo el archivo';
    }
  } else {
    // Modifica la respuesta del servicio
    $response['data'] = 'Se produjo un error cargando el archivo';
  }
} else {
  // Modifica la respuesta del servicio
  $response['data'] = 'No se cargo ningún archivo';
}

// Muestra la respuesta
echo json_encode($response);
