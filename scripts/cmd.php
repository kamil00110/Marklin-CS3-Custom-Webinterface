<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Read from messages.txt
    $messages = file_get_contents('../tmp/messages.txt');
    echo $messages;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
	
    // Write to commands.txt
    $command = $_POST['command'];
    file_put_contents('../tmp/commands.txt', $command);
    echo "Command written to commands.txt";
}
?>
