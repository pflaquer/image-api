<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if any files were uploaded
  if (isset($_FILES['images'])) {
    $imageUrls = [];

    // Loop through each uploaded file
    foreach ($_FILES['images'] as $key => $value) {
      // Get the file information
      $filename = $value['name'];
      $tmpName = $value['tmp_name'];
      $error = $value['error'];
      $size = $value['size'];
      $type = $value['type'];

      // Check for upload errors
      if ($error === UPLOAD_ERR_OK) {
        // Check if the file type is an image
        if (in_array($type, ['image/jpeg', 'image/png', 'image/gif'])) {
          // Generate a unique filename
          $newFilename = uniqid() . '.' . pathinfo($filename, PATHINFO_EXTENSION);

          // Upload the file to the uploads directory
          move_uploaded_file($tmpName, 'uploads/' . $newFilename);

          // Construct the image URL
          $imageUrl = 'http://localhost:5000/uploads/' . $newFilename;

          // Add the image URL to the array
          $imageUrls[] = $imageUrl;
        } else {
          echo 'Invalid file type: ' . $type;
        }
      } else {
        echo 'Upload error: ' . $error;
      }
    }

    // Encode the array of image URLs to JSON
    $json = json_encode($imageUrls);

    // Echo the JSON response
    echo $json;
  } else {
    echo 'No images found in request';
