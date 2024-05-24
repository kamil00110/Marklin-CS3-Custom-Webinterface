	<?php
     // URL of the API to fetch data from
     $cs3ip = $_COOKIE["cs3ip"];
$url2 = 'http://'.$cs3ip.'/app/api/loks';

// Path to the log file
$logFile2 = 'address_log.json';

// Function to fetch data from the URL
function fetchData($url2) {
    $ch2 = curl_init();

    curl_setopt($ch2, CURLOPT_URL, $url2);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

    $output2 = curl_exec($ch2);

    if (curl_errno($ch2)) {
        echo 'Error:' . curl_error($ch2);
        return false;
    }

    curl_close($ch2);

    return $output2;
}

// Function to log data into a file
function logData($logFile2, $data2) {
    $currentDateTime2 = date('Y-m-d H:i:s');
    $logEntry2 = [
        'timestamp' => $currentDateTime2,
        'data' => json_decode($data2, true)
    ];

    $existingData2 = [];

    // Check if log file exists and has content
    if (file_exists($logFile2)) {
        $existingData2 = json_decode(file_get_contents($logFile2), true);
        if (!is_array($existingData2)) {
            $existingData2 = [];
        }
    }

    // Add new log entry to the existing data
    $existingData2[] = $logEntry2;

    // Save the updated log data back to the file
    file_put_contents($logFile2, json_encode($existingData2, JSON_PRETTY_PRINT));
}

// Fetch data from the URL
$data2 = fetchData($url2);

// Log the fetched data
if ($data2) {
    $jsonData2 = json_decode($data2, true);
    foreach ($jsonData2 as $part2) {	
		$info = file_get_contents("data.txt");
        $speed = htmlspecialchars($part2['speed'] ?? 'None');
		$name = htmlspecialchars($part2['name'] ?? 'None');
		$dir = htmlspecialchars($part2['dir'] ?? 'None');
		$tachomax = htmlspecialchars($part2['tachomax'] ?? 'None');
		$internname = htmlspecialchars($part2['internname'] ?? 'None');
		$kmh = htmlspecialchars($part2['tachoLabels']['kmh'] ?? 'None');
		$ratio = $tachomax / 1000;
		$tacho = $ratio * $speed;
		$tacho = round($tacho);
		if ($tacho <= 9 ){
			$tacho = "00".$tacho;
		}
		if ($tacho <= 99 and $tacho >= 9){
			$tacho = "0".$tacho;
		}	
		if($internname === $info){
			//print_r($part2);
		    //echo $internname;
			//echo $tacho."  ";
		    //echo $info." + ";
		    //echo $internname."<br><br>";
		    if($dir==="0"){
				$anzfu = count($part2['funktionen']);
						if($anzfu > 16){
							$size = "44px";
							$rows = "auto auto auto auto auto auto";
							$margin = "0px";
						}
						else{
							$size = "64px";
							$rows = "auto auto auto auto";
							$margin = "3px";
						}
				echo "<div style='width: 310px ;margin: 40px; top: -430px; position: absolute;'>";
		    	echo "<div style='fontsize 20px; font-size: 20px;'>
				      <p style='font-size: 30px; font-weight: 600;'>$name </p>
				      <p style='color:#00a30b; font-size: 30px; font-weight: 800;'>$tacho 
					      <span style='color:#00a30b; font-size: 20px; font-weight: 600;'>$kmh</span>
					  </p>
					  </div>
					  <button class='button2' style='width=200px; position: absolute; top:253px' onclick='myFunction(\"$internname\")'>
					  <input  style='width=100%' type='range' min='0' max='1000' class='slider' id='mySlider'>
					  </button>
		    	      <progress style='position: absolute; top:255px' class='bar4' id='file' value='$speed' max='1000'></progress>
					  <button style='left: 0px; position: absolute; top: 275px;' class='button' onclick='sendWs(\"lok\",\"$internname\",\"dir\",\"1\",\"none\")'>
					  <svg width='30' height='30' viewBox='0 0 500 670' xmlns='http://www.w3.org/2000/svg' xmlns:bx='https://boxy-svg.com'>
                      <path d='M 1275.402 1857.307 Q 1353.277 1768.554 1431.151 1857.307 L 1644.477 2100.429 Q 1722.352 2189.182 1566.602 2189.182 L 1139.951 2189.182 Q 984.201 2189.182 1062.076 2100.429 Z' style='fill: #c90000; transform-origin: 1353.28px 2001.06px'; transform='matrix(0, 1, 1, 0, -1105.722045898439, -1663.257934570312)' bx:shape='triangle 984.201 1768.554 738.151 420.628 0.5 0.211 1@bcd9c0da'/>
                      </svg>
					  </button>
					  <button style='right: 0px; position: absolute; top: 275px;' class='button' onclick='sendWs(\"lok\",\"$internname\",\"dir\",\"0\",\"none\")'>
					  <svg width='30' height='30' viewBox='0 0 500 670' xmlns='http://www.w3.org/2000/svg' xmlns:bx='https://boxy-svg.com'>
                      <path d='M 1275.402 1857.307 Q 1353.277 1768.554 1431.151 1857.307 L 1644.477 2100.429 Q 1722.352 2189.182 1566.602 2189.182 L 1139.951 2189.182 Q 984.201 2189.182 1062.076 2100.429 Z' style='fill: #00a30b; transform-origin: 1353.28px 2001.06px'; transform='matrix(0, 1, -1, 0, -1105.722045898439, -1663.257934570312)' bx:shape='triangle 984.201 1768.554 738.151 420.628 0.5 0.211 1@bcd9c0da'/>
                      </svg>
					  </button>
					  <div style='padding-left: 7px; grid-template-columns: $rows; top:313px; position: absolute; overflow-y: scroll; overflow-x: hide; display: grid; width: 330px; height: 290px;'>";
					  
			    if (!empty($part2['funktionen'])) {
                    foreach ($part2['funktionen'] as $funktion) {
                        $nr = htmlspecialchars($funktion['nr'] ?? 'None');
                        $fIcon = htmlspecialchars($funktion['icon'] ?? 'None');
                        $state = htmlspecialchars($funktion['state'] ?? 'None');
						$fIcon = $fIcon = "http://".$cs3ip."/app/assets/fct/".$fIcon.".svg";
						if($fIcon == "http://".$cs3ip."/app/assets/fct/None.svg"){
							$fIcon = "none.svg";
						}
					if($state==1){
					    echo "<button class='button' onclick='sendWs(\"lok\",\"$internname\",\"func\",\"$nr\",\"0\")'>
						      <div style='box-shadow: inset 0px 0px 10px gray; margin: $margin; width:$size; height: $size; background-color: #f5eb69; border-radius: 10px;'>
					                  <img src='$fIcon'>
								      </img>	  
					          </div></button>";
					}
					if($state==0){
					    echo "<button class='button' onclick='sendWs(\"lok\",\"$internname\",\"func\",\"$nr\",\"1\")'>
						      <div style='box-shadow: inset 0px 0px 10px gray; margin: $margin; width:$size; height: $size; background-color: lightgray; border-radius: 10px;'>				               
					                  <img src='$fIcon'>
								      </img>	  
					          </div></button>";
					}
                    }
                }
				echo "</div></div>";
		    } 
			
		    else{
			    $anzfu = count($part2['funktionen']);
						if($anzfu > 16){
							$size = "44px";
							$rows = "auto auto auto auto auto auto";
							$margin = "0px";
						}
						else{
							$size = "64px";
							$rows = "auto auto auto auto";
							$margin = "3px";
						}
				echo "<div style='width: 310px ;margin: 40px; top: -430px; position: absolute;'>";
		    	echo "<div style='fontsize 20px; font-size: 20px;'>
				      <p style='font-size: 30px; font-weight: 600;'>$name </p>
				      <p style='color:#c90000; font-size: 30px; font-weight: 800;'>$tacho 
					      <span style='color:#c90000; font-size: 20px; font-weight: 600;'>$kmh</span>
					  </p>
					  </div>
					  <button class='button2' style='width=200px; position: absolute; top:253px' onclick='myFunction(\"$internname\")'>
					  <input  style='width=100%' type='range' min='0' max='1000' class='slider' id='mySlider'>
					  </button>
		    	      <progress style='position: absolute; top:255px' class='bar3' id='file' value='$speed' max='1000'></progress>
					  <button style='left: 0px; position: absolute; top: 275px;' class='button' onclick='sendWs(\"lok\",\"$internname\",\"dir\",\"1\",\"none\")'>
					  <svg width='30' height='30' viewBox='0 0 500 670' xmlns='http://www.w3.org/2000/svg' xmlns:bx='https://boxy-svg.com'>
                      <path d='M 1275.402 1857.307 Q 1353.277 1768.554 1431.151 1857.307 L 1644.477 2100.429 Q 1722.352 2189.182 1566.602 2189.182 L 1139.951 2189.182 Q 984.201 2189.182 1062.076 2100.429 Z' style='fill: #c90000; transform-origin: 1353.28px 2001.06px'; transform='matrix(0, 1, 1, 0, -1105.722045898439, -1663.257934570312)' bx:shape='triangle 984.201 1768.554 738.151 420.628 0.5 0.211 1@bcd9c0da'/>
                      </svg>
					  </button>
					  <button style='right: 0px; position: absolute; top: 275px;' class='button' onclick='sendWs(\"lok\",\"$internname\",\"dir\",\"0\",\"none\")'>
					  <svg width='30' height='30' viewBox='0 0 500 670' xmlns='http://www.w3.org/2000/svg' xmlns:bx='https://boxy-svg.com'>
                      <path d='M 1275.402 1857.307 Q 1353.277 1768.554 1431.151 1857.307 L 1644.477 2100.429 Q 1722.352 2189.182 1566.602 2189.182 L 1139.951 2189.182 Q 984.201 2189.182 1062.076 2100.429 Z' style='fill: #00a30b; transform-origin: 1353.28px 2001.06px'; transform='matrix(0, 1, -1, 0, -1105.722045898439, -1663.257934570312)' bx:shape='triangle 984.201 1768.554 738.151 420.628 0.5 0.211 1@bcd9c0da'/>
                      </svg>
					  </button>
					  <div style='padding-left: 7px; grid-template-columns: $rows; top:313px; position: absolute; overflow-y: scroll; overflow-x: hide; display: grid; width: 330px; height: 290px;'>";
					  
			    if (!empty($part2['funktionen'])) {
                    foreach ($part2['funktionen'] as $funktion) {
                        $nr = htmlspecialchars($funktion['nr'] ?? 'None');
                        $fIcon = htmlspecialchars($funktion['icon'] ?? 'None');
                        $state = htmlspecialchars($funktion['state'] ?? 'None');
						$fIcon = $fIcon = "http://".$cs3ip."/app/assets/fct/".$fIcon.".svg";
						if($fIcon == "http://".$cs3ip."/app/assets/fct/None.svg"){
							$fIcon = "none.svg";
						}
					if($state==1){
					    echo "<button class='button' onclick='sendWs(\"lok\",\"$internname\",\"func\",\"$nr\",\"0\")'>
						      <div style='box-shadow: inset 0px 0px 10px gray; margin: $margin; width:$size; height: $size; background-color: #f5eb69; border-radius: 10px;'>
					                  <img src='$fIcon'>
								      </img>	  
					          </div></button>";
					}
					if($state==0){
					    echo "<button class='button' onclick='sendWs(\"lok\",\"$internname\",\"func\",\"$nr\",\"1\")'>
						      <div style='box-shadow: inset 0px 0px 10px gray; margin: $margin; width:$size; height: $size; background-color: lightgray; border-radius: 10px;'>				               
					                  <img src='$fIcon'>
								      </img>	  
					          </div></button>";
					}
                    }
                }
				echo "</div></div>";
		    }

                

            
        }
        else{
			//echo "1";
		}		
		
    }
}
?>