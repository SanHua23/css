<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title id="contactContent">
      <?php
          // Get Current File Name and Display
          $url = pathinfo(basename($_SERVER['PHP_SELF']), PATHINFO_FILENAME);

          // Remove the prefix
          $removePrefix = str_replace('http://localhost/smartpark/', '', $url);

          // Get the file name without the ".php" extension
          $fileNameWithoutPhp = pathinfo(basename($removePrefix), PATHINFO_FILENAME);
          $cleanedString = preg_replace('/[^a-zA-Z0-9]/', ' ', $fileNameWithoutPhp);
          $sentencecase = ucwords($cleanedString);

          // Output the result
          echo $sentencecase;
      ?>
    </title>
    <!-- BOOTSTRAP 5 CSS -->
    <link rel="stylesheet" type="text/css" href="../plugins/bootstrap5/bootstrap.min.css">
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
