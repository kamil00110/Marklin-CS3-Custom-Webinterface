<?php
$sudopsw = "64128";
$messageFile = "../tmp/messages.txt";

function copyPrecompiledImage($filename) {
    $precompiledImage = '../Update.btrfs';
    if (!copy($precompiledImage, $filename)) {
        die("Failed to copy precompiled image.");
    }
}



$os = php_uname('s');

if (stripos($os, 'Windows') !== false) {
	$output = shell_exec('wsl -l 2>&1');
	$output = iconv(mb_detect_encoding($output, mb_detect_order(), true), 'UTF-8//IGNORE//TRANSLIT', $output);
	$output = str_replace('ü', 'u', $output);
	$outputCleaned = preg_replace('/[^A-Za-z0-9]/', '', $output);

    if (str_contains($outputCleaned, 'Subsystem') !== false) {
        // Execute Linux commands through WSL
		file_put_contents($messageFile ,"Windows with valid WSL detected".PHP_EOL ,FILE_APPEND);
		file_put_contents($messageFile ,"Removing old Image..".PHP_EOL ,FILE_APPEND);
		shell_exec("wsl rm -rf ../tmp/Update.btrfs");
		file_put_contents($messageFile ,"Creating Update.btrfs in tmp...".PHP_EOL ,FILE_APPEND);
		shell_exec("wsl truncate --size=1M ../tmp/Update.btrfs");
		sleep(1);
		file_put_contents($messageFile ,"Formating Update.btrfs...".PHP_EOL ,FILE_APPEND);
		shell_exec("wsl mkfs.ext4 -F ../tmp/Update.btrfs");
		sleep(1);
		file_put_contents($messageFile ,"Removing old mointing dirrectory...".PHP_EOL ,FILE_APPEND);
		shell_exec("wsl rm -rf /tmp/image");
		file_put_contents($messageFile ,"Creating new mounting directory...".PHP_EOL ,FILE_APPEND);
		shell_exec("wsl mkdir /tmp/image");
		file_put_contents($messageFile ,"Mounting Ubtadte.btrfs in WSL in /tmp/image...".PHP_EOL ,FILE_APPEND);
		shell_exec("wsl echo $sudopsw | wsl sudo -S mount ../tmp/Update.btrfs /tmp/image");
		sleep(1);
		file_put_contents($messageFile ,"Copying code into /tmp/image...".PHP_EOL ,FILE_APPEND);
		shell_exec("wsl echo $sudopsw | wsl sudo -S cp -R ../payload/* /tmp/image");
		shell_exec("wsl echo $sudopsw | wsl sudo -S rm -rf /tmp/image/lost+found/");
		file_put_contents($messageFile ,"Creating Update.btrfs in tmp...".PHP_EOL ,FILE_APPEND);
		sleep(1);
		file_put_contents($messageFile ,"Unmountig Update.btrfs...".PHP_EOL ,FILE_APPEND);
		shell_exec("wsl echo $sudopsw | wsl sudo -S umount /tmp/image");
		file_put_contents($messageFile ,"Finished making Update.btrfs...".PHP_EOL ,FILE_APPEND);
    } else {
		file_put_contents($messageFile ,"No WSL in Windows detected".PHP_EOL ,FILE_APPEND);
		copy("../Update.btrfs","../tmp/Update.btrfs");
		file_put_contents($messageFile ,"Precompiled Update.btrfs copied to /tmp".PHP_EOL ,FILE_APPEND);
    }
} else if (stripos($os, 'Linux') !== false) {
       // Execute Linux command
		echo "Linux detected"."<br>"."<br>";
		flush();
		ob_flush();
		echo "Removing old Image..."."<br>";
		flush();
		shell_exec("rm -rf ../tmp/Update.btrfs");
		echo "Creating Update.btrfs in tmp..."."<br>";
		flush();
		ob_flush();
		shell_exec("truncate --size=1M ../tmp/Update.btrfs");
		sleep(1);
		echo "Formating Update.btrfs..."."<br>";
		flush();
		ob_flush();
		shell_exec("mkfs.ext4 -F ../tmp/Update.btrfs");
		sleep(1);
		echo "Removing old mointing dirrectory..."."<br>";
		flush();
		ob_flush();
		shell_exec("rm -rf /tmp/image");
		echo "Creating new mounting directory..."."<br>";
		flush();
		ob_flush();
		shell_exec("mkdir /tmp/image");
		echo "Mounting Ubtadte.btrfs in WSL in /tmp/image..."."<br>";
		flush();
		ob_flush();
		shell_exec("echo $sudopsw | wsl sudo -S mount ../tmp/Update.btrfs /tmp/image");
		sleep(1);
		echo "Copying code into /tmp/image..."."<br>";
		flush();
		ob_flush();
		shell_exec("echo $sudopsw | wsl sudo -S cp -R ../payload/* /tmp/image");
		sleep(1);
		echo "Unmountig Update.btrfs..."."<br>";
		flush();
		ob_flush();
		shell_exec("echo $sudopsw | wsl sudo -S umount /tmp/image");
		echo "Finished making Update.btrfs...";
}
?>
<script>
alert("test");
</script>
