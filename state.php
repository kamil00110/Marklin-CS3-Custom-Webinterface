<?php
// IP address of the device
$cs3ip = "192.168.2.125";

// URL of the API to fetch data from
$url = 'http://' . $cs3ip . '/app/api/system';

// Function to fetch data from the URL
function fetchData3($url) {
    $ch3 = curl_init();

    curl_setopt($ch3, CURLOPT_URL, $url);
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);

    $output3 = curl_exec($ch3);

    if (curl_errno($ch3)) {
        echo 'Error:' . curl_error($ch3);
        return false;
    }

    curl_close($ch3);

    return $output3;
}

// Fetch data from the URL
$data3 = fetchData3($url);

if ($data3) {
    // Decode JSON data
    $jsonData3 = json_decode($data3, true);
    
    // Check if decoding was successful
    if (json_last_error() === JSON_ERROR_NONE) {
        // Extract values with fallback to "None"
        $state = htmlspecialchars($jsonData3['state'] ?? 'None');
    } 
	else {
        echo "Failed to decode JSON data.";
    }
	if ($state==0){
		echo "<button class='button' style='width=200px;' onclick='sendWs(\"cs3\",\"none\",\"state\",\"none\",\"1\")''>  
		      <div style='box-shadow: 0px 0px 15px #c90000, inset 0px 0px 35px #c90000; font-size:0px; margin: 10px; border-radius: 10px; position: relative; background-color: #c90000; width: 72px; height: 72px;'>
	          <svg
	          style='margin: 6px 6px 5px 5px;'
              width='83%'
              viewBox='0 0 72 72'
              fill='none'
              xmlns='http://www.w3.org/2000/svg'>
              <rect x='6' y='6' width='60' height='60' rx='12' fill='#f7f7f7' stroke='#4f4f4f' stroke-width='2'/>
              <text  font-weight='bold' x='12' y='30' fill='red' font-family='Arial' font-size='18' text-anchor='start' dominant-baseline='hanging'>STOP</text>
              </svg>
	          </div>
			  </button>";
	}
	else{
		echo "<button class='button' style='width=200px;' onclick='sendWs(\"cs3\",\"none\",\"state\",\"none\",\"0\")''>  
		      <div style='font-size:0px; margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	          <svg
	          style='margin: 6px 6px 5px 5px;'
              width='83%'
              viewBox='0 0 72 72'
              fill='none'
              xmlns='http://www.w3.org/2000/svg'>
              <rect x='6' y='6' width='60' height='60' rx='12' fill='#f7f7f7' stroke='#4f4f4f' stroke-width='2'/>
              <text  font-weight='bold' x='12' y='30' fill='red' font-family='Arial' font-size='18' text-anchor='start' dominant-baseline='hanging'>STOP</text>
              </svg>
	          </div>
			  </button>";
		
	}
} else {
    echo "Failed to fetch data.";
}
?>