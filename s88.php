<?php
// URL of the API to fetch data from
$cs3ip = $_COOKIE["cs3ip"];
$url = 'http://'.$cs3ip.'/app/api/mags';

// Path to the log file
$logFile = 'address_log.json';

// Function to fetch data from the URL
function fetchData($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        return false;
    }

    curl_close($ch);

    return $output;
}

// Function to log data into a file
function logData($logFile, $data) {
    $currentDateTime = date('Y-m-d H:i:s');
    $logEntry = [
        'timestamp' => $currentDateTime,
        'data' => json_decode($data, true)
    ];

    $existingData = [];

    // Check if log file exists and has content
    if (file_exists($logFile)) {
        $existingData = json_decode(file_get_contents($logFile), true);
        if (!is_array($existingData)) {
            $existingData = [];
        }
    }

    // Add new log entry to the existing data
    $existingData[] = $logEntry;

    // Save the updated log data back to the file
    file_put_contents($logFile, json_encode($existingData, JSON_PRETTY_PRINT));
}

// Fetch data from the URL
$data = fetchData($url);

// Log the fetched data
if ($data) {
    $jsonData = json_decode($data, true);
    echo "	
	<div class='mag' style='overflow; visable; margin-left: 1vw; margin-right: 1vw; position: absolute; top:0; border-radius: 0px 0px 20px 20px; width: 97.3vw; height:180px; background-color: #e8e8e8; display:flex; align-items:center;'>
    <div class='scrollmag' style='box-shadow: inset 0px 0px 10px lightgrey; margin: 0.2%; border-radius: 18px; z-index: 50; position: absolute; bottom:0px; background-color: #f2f2f2; width: 99.5%; height: 50%; display:flex; align-items:center; overflow-x: scroll; overflow-y: hidden; overflow-x: overlay;' >";
    foreach ($jsonData as $part) {
        $name = htmlspecialchars($part['name'] ?? 'None');
		$internname = htmlspecialchars($part['internname'] ?? 'None');
        $iconFile = htmlspecialchars($part['iconFile'] ?? 'None');
		$state = htmlspecialchars($part['state'] ?? 'None');
		$isInMagList = htmlspecialchars($part['isInMagList'] ?? 'None');
		$isS88Contact = htmlspecialchars($part['isS88Contact'] ?? 'None');
		$id = htmlspecialchars($part['id'] ?? 'None');
		$states2 = htmlspecialchars($part['states'] ?? 'None');
		$s88kontakt = htmlspecialchars($part['s88kontakt'] ?? 'None');
		$s88kennung = htmlspecialchars($part['s88kennung'] ?? 'None');
		$iconFile = $iconFile = "http://".$cs3ip."/app/assets/mag/".$iconFile;
						if($iconFile == "http://".$cs3ip."/app/assets/mag/None"){
							$iconFile = "/app/assets/mag/none.svg";
						}
		if($isInMagList == true and $isS88Contact == true and $states2 == "2"){
			if($state == "0"){
                echo "	    
				<button class='button' style='width=200px;' onclick='sendWs(\"s88\",\"$s88kennung\",\"none\",\"$s88kontakt\",\"1\")''>
		    	<div style='box-shadow: inset 0px 0px 5px grey; position: relative; padding-left: 7px; padding-right: 7px; margin: 10px; border-radius: 5px; width:50px; height:65px; background-color: #d1b0b0; overflow: hide;'>
		    	<div style='text-align: center; font-size: 11px; padding-top:6px; width: 40px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>$name</div>
		    	<img src='$iconFile' style='vertical-align: text-bottom;'; max-height='40px' width='40px'>
				</button>";
			}
			elseif($state == "1"){
				echo "	  
                <button class='button' style='width=200px;' onclick='sendWs(\"s88\",\"$s88kennung\",\"none\",\"$s88kontakt\",\"0\")''>				
		    	<div style='box-shadow: inset 0px 0px 5px grey; position: relative; padding-left: 7px; padding-right: 7px; margin: 10px; border-radius: 5px; width:50px; height:65px; background-color: #b0d1b9; overflow: hide;'>
		    	<div style='text-align: center; font-size: 11px; padding-top:6px; width: 40px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>$name</div>
		    	<img src='$iconFile' style='vertical-align: text-bottom;'; max-height='40px' width='40px'>
				</button>";      		
			}
			else{
				echo "	  
                <button class='button' style='width=200px;' onclick='sendWs(\"s88\",\"$s88kennung\",\"none\",\"$s88kontakt\",\"0\")''>				
		    	<div style='box-shadow: inset 0px 0px 5px grey; position: relative; padding-left: 7px; padding-right: 7px; margin: 10px; border-radius: 5px; width:50px; height:65px; background-color: #cccccc; overflow: hide;'>
		    	<div style='text-align: center; font-size: 11px; padding-top:6px; width: 40px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>$name</div>
		    	<img src='$iconFile' style='vertical-align: text-bottom;'; max-height='40px' width='40px'>
				</button>";      		
			}
   		}
	}
	echo "</div>
	</div>";
}
?>