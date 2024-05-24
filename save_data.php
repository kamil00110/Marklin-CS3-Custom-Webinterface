<?php
if (isset($_POST['data'])) {
    $data = $_POST['data'];
    $filename = 'data.txt'; // Specify the file name
    $file = fopen($filename, 'w'); // Open the file in write mode

    if ($file) {
        fwrite($file, $data); // Write the data to the file
        fclose($file); // Close the file
        echo "Data saved to file.";
    } else {
        echo "Failed to open the file.";
    }
} else {
    echo "No data received.";
}
?>
