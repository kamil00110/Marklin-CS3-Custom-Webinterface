<?php
// URL of the API to fetch data from
$cs3ip = "192.168.2.125";
$url = 'http://'.$cs3ip.'/app/api/loks';

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
    logData($logFile, $data);
    $jsonData = json_decode($data, true);
    echo "<div style='background-color: transparen; position: relative; width: 150px; height: 50px;'>need pla</div>";
    foreach ($jsonData as $part) {
        $name = htmlspecialchars($part['name'] ?? 'None');
        $icon = htmlspecialchars($part['icon'] ?? 'None');
        $speed = htmlspecialchars($part['speed'] ?? 'None');
        $recent = htmlspecialchars($part['recent'] ?? 'None');
        $vmax = htmlspecialchars($part['tachomax'] ?? 'None');
		$dir = htmlspecialchars($part['dir'] ?? 'None');
        $vmin = 'None'; // Assuming vmin is not available in the provided JSON; set a default value.
        $icon = str_replace("/home/cs3/cs3data/lokicons", "/app/assets/lok", $icon);
		$icon = str_replace("/usr/local/cs3/lokicons", "/app/assets/lok", $icon);
		$icon = "http://".$cs3ip.$icon.".png";
        echo "
		    
		    <div style='box-shadow: 0px 0px 5px grey; position: relative; padding-left: 7px; padding-right: 7px; margin: 10px; border-radius: 5px; width:90px; height:90px; background-color: #f7f7f7; overflow: hide;'>
		    <div style='text-align: center; font-size: 11px; padding-top:10px; width: 80px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;'>$name</div>
		    <div style='box-shadow: inset 0px 0px 5px rgba(0,0,0,0.5); padding-left: 3px; padding-right: 3px; margin-top:5px; background-color: #dbdbdb; width: 80px; height: 45px; border-radius: 5px;'><div style='position: absolute; padding-bottom: 20px; bottom: 0; width: 80px;'><img src='$icon' style='vertical-align: text-bottom;'; max-height='40px' width='80px'></div></div>";
			if($dir=="0"){echo "<progress class='bar2' id='file' value='$speed' max='1000'></progress>";} 
			else{echo "<progress class='bar1' id='file' value='$speed' max='1000'></progress>";}
          

        if (!empty($part['funktionen'])) {
            foreach ($part['funktionen'] as $funktion) {
                $nr = htmlspecialchars($funktion['nr'] ?? 'None');
                $fIcon = htmlspecialchars($funktion['icon'] ?? 'None');
                $state = htmlspecialchars($funktion['state'] ?? 'None');

                //echo "<div class='funktion'>
                //    <p>Nr: $nr</p>
                 //   <p>Icon: $fIcon</p>
                 //   <p>State: $state</p>
                //</div>";
            }
        } else {
            //echo "<p>None</p>";
        }
        
        echo "</div>";
    }
echo "<div style='background-color: transparen; position: relative; width: 150px; height: 50px;'>need pla</div>";
    echo "";
} else {
    echo "Failed to fetch data.";
}
// state = ? = answer?
//set s88 kontakt 42["event_data","{\"s88\":{\"oldstate\":\"2\",\"s88kontakt\":\"1\",\"s88kennung\":\"1\",\"state\":\"1 or 0\"}}"]
// answer: 42["data","{\"s88\":{\"id\":73729,\"state\":0}}"]
//set mag artikel 42["event_data","{\"mag\":{\"id\":\"8\",\"state\":\"1 or 128\"}}"]
// answer: 42["data","{\"mag\":{\"id\":\"8\",\"state\":129}}"] Received: 42["data","{\"mag\":{\"id\":\"8\",\"state\":1}}"]
// set lok speed 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"speed\":\"0 - 1000\"}}"]
//answer: 42["data","{\"lok\":{\"name\":\"132 439-1\",\"speed\":\"690\"},\"status\":true}"]
// set lok direction 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"dir\":\"1 or 0\"}}"]
// set lok funktion 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"func\":\"1\",\"state\":\"1 or 0\"}}"]
// stop CS3 42["event_data","{\"cs3\":{\"state\":\"0 or 1\"}}"]
?>
