<?php
set_time_limit(0);
$commandFile = '../tmp/commands.txt'; // File to store commands
$messageFile = '../tmp/messages.txt';
$socket = stream_socket_server("tcp://0.0.0.0:4440", $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN);
stream_set_blocking($socket, 0);

$connections = [];
$read = [];
$write = null;
$except = null;
flush();
ob_flush();
while (1) {
	flush();
	ob_flush();
    // look for new connections
    if ($c = @stream_socket_accept($socket, empty($connections) ? -1 : 0, $peer)) {
		file_put_contents($messageFile ,$peer.' connected'.PHP_EOL ,FILE_APPEND);
        echo $peer.' connected'.'<br>';
        $connections[$peer] = $c;
		$d = $c;
    }
    $command = file_get_contents($commandFile);
    if(!empty($command)){	
	    if (substr($command, -1) !== "\n") {
            $command .= "\n";
        }
	    fwrite($d, $command);
		file_put_contents($messageFile ,'$ '.$command,FILE_APPEND);
	    echo $command;
        file_put_contents($commandFile, "");
    }
	
    // wait for any stream data
    $read = $connections;
    if (stream_select($read, $write, $except, 1)) {
        foreach ($read as $c) {
			    
			    $contents = fread($c, 1024);
				file_put_contents($messageFile ,$contents.PHP_EOL ,FILE_APPEND);
                echo $contents.PHP_EOL;
				flush();
				ob_flush();
            }
        }

    }

?>