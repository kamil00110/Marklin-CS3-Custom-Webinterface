<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="webs.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Client</title>
    <style>
     
        #log {
            width: 50vw;
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            margin-bottom: 0px;
            padding: 0px;
            background-color: #f9f9f9;
			display:none;
        }
        #message {
            width: 50%;
            padding: 10px;
            margin-right: 10px;
			display:none;
        }
        #send {
            padding: 10px 20px;
			display:none;
        }
    </style>
</head>
<body>
    <?php 
if(!isset($_COOKIE["cs3ip"])){
	header("Location: index.php");
}
?>  
    
	<div style='z-index: 10; display:flex; align-items:center; padding-left: 70px; padding-right: 70px; background-color: #e8e8e8; border-radius: 0px 0px 20px 20px; margin-left: 1vw; margin-right: 1vw; position: absolute; top: 0; width:90vw; height: 90px;'>
	<button class='button' style='width=200px;' onclick='loadWindow("ws_terminal.php")'>  
		  <div style=' margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="60" height="60">
  <rect x="10" y="10" width="80" height="80" rx="10" stroke="#666" stroke-width="4" fill="none" />

  <line x1="20" y1="50" x2="80" y2="50" stroke="#333" stroke-width="6" />
  <line x1="20" y1="70" x2="80" y2="70" stroke="#333" stroke-width="6" />

  <rect x="22" y="40" width="6" height="40" fill="#333" />
  <rect x="34" y="40" width="6" height="40" fill="#333" />
  <rect x="46" y="40" width="6" height="40" fill="#333" />
  <rect x="58" y="40" width="6" height="40" fill="#333" />
  <rect x="70" y="40" width="6" height="40" fill="#333" />


  <circle cx="20" cy="50" r="3" fill="#333" />
  <circle cx="20" cy="70" r="3" fill="#333" />
  <circle cx="80" cy="50" r="3" fill="#333" />
  <circle cx="80" cy="70" r="3" fill="#333" />
</svg>
</div>
</button>
	<button class='button' style='width=200px;' onclick='loadWindow("cmd.php")'>  
		  <div style=' margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	          <svg
	              style='margin: 12px 12px 5px 5px;'
                  width='83%'
                  viewBox='0 0 50 40'
                  fill='none'
                  xmlns='http://www.w3.org/2000/svg'>
                  <rect x='5' y='4' width='41' height='32' rx='8' fill='#f7f7f7' stroke='#4f4f4f' stroke-width='1.5'/>
                  <path d='M14 15L22 20L14 25' stroke='#4f4f4f' stroke-width='1.7' stroke-linecap='round' stroke-linejoin='round'/>
                  <rect x='25' y='26' width='12' height='2' rx='0.5' fill='#4f4f4f'/>
              </svg>
	     </div>
    </button>
	<div class='help' style='font-size:0px; margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	<p>Every action on the CS3 renturns data</p>
	<p>set s88 kontakt 42["event_data","{\"s88\":{\"oldstate\":\"2\",\"s88kontakt\":\"1\",\"s88kennung\":\"1\",\"state\":\"1 or 0\"}}"] </p>
    <p>set mag artikel 42["event_data","{\"mag\":{\"id\":\"8\",\"state\":\"1 or 128\"}}"]</p>
    <p>set lok speed 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"speed\":\"0 - 1000\"}}"]</p>
    <p>set lok direction 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"dir\":\"1 or 0\"}}"]</p>
    <p>set lok funktion 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"func\":\"1\",\"state\":\"1 or 0\"}}"]</p>
    <p>stop CS3 42["event_data","{\"cs3\":{\"state\":\"0 or 1\"}}"]</p>
	<p>state info and other data at http://CS3 IP/app/api/loks, mag, mags, devs, prefs, gbs, info, filemanager, automatics, helps, system or</p>
	<p>http://CS3 IP/config/geraet.vrs, lokomotive.cs2, fahrstrassen.cs2, gleisbild.cs2, magnetartikel.cs2 
	/app/assets/fct/</p>
	<svg
	style='margin: 6px 6px 5px 5px; position: absolute'
    width="83%"
    viewBox="0 0 72 72"
    fill="none"
    xmlns="http://www.w3.org/2000/svg">
        <rect x="6" y="6" width="60" height="60" rx="12" fill="#f7f7f7" stroke="#4f4f4f" stroke-width="2"/>
        <line x1="40" y1="24" x2="52" y2="24" stroke="#4f4f4f" stroke-width="4" stroke-linecap="round"/>
        <line x1="40" y1="36" x2="52" y2="36" stroke="#4f4f4f" stroke-width="4" stroke-linecap="round"/>
        <line x1="20" y1="48" x2="52" y2="48" stroke="#4f4f4f" stroke-width="4" stroke-linecap="round"/>
        <text  font-weight="bold" x="15" y="17" fill="#4f4f4f" font-family="Arial" font-size="32" text-anchor="start" dominant-baseline="hanging">?</text>
	</svg>
	
    </div>
    <div class="state"></div>
	<div class="mag_but"></div>
	<div class="s88_but"></div>
	<div class="scripts_but"></div>
	<div class="settings_but"></div>
    </div>
	    <div class='mags'></div>
		<div id='con'></div>

    <div><div id="log"></div>
    <input type="text" id="message" placeholder="Input Commands">
    <button id="send"></button>
	</div>

    
<script>
    function getCookie(cname) {
          let name = cname + "=";
          let ca = document.cookie.split(';');
          for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
              c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
            }
          }
          return "";
        }
    </script>
    <script src="js/load.js"></script>
	<script>
		function myFunction(internname) {
			const input = document.getElementById('mySlider');
			const inputValue = input.value;
			let test = internname+":"+inputValue;
  	    	//alert(test);
			sendWs("lok",internname,"speed", inputValue, "0");
    }
    </script>
    <script src="js/ws_send.js"></script>
	<script src="js/loadWindow.js"></script>
    <script src="js/ws_terminal.js">
	
	</script>
    <script src="js/update_func.js"></script>
    <script src="js/slider.js"></script>
	<div id="" style='margin-top: 9vh; margin-bottom: 0vh; position: absolute; top:10vh; right:0; border-radius: 20px 0px 0px 10px; width:20vw; height:81vh; background-color: #e8e8e8; display:flex; align-items:center; overflow-x: scroll;'>
	<div style="position:absolute; z-index:9999;" class='lokcontroll'></div>
	<?php
     // URL of the API to fetch data from
     $cs3ip = $_COOKIE["cs3ip"];
$url2 = 'http://'.$cs3ip.'/app/api/loks';
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
// Fetch data from the URL
$data2 = fetchData($url2);

// Log the fetched data
if ($data2) {
    $jsonData2 = json_decode($data2, true);
    foreach ($jsonData2 as $part2) {
        $name = htmlspecialchars($part2['name'] ?? 'None');
		$internname = htmlspecialchars($part2['internname'] ?? 'None');
		//$GLOBALS["intern"] = $internname;
        $icon = htmlspecialchars($part2['icon'] ?? 'None');
        $speed = htmlspecialchars($part2['speed'] ?? 'None');
        $recent = htmlspecialchars($part2['recent'] ?? 'None');
        $vmax = htmlspecialchars($part2['tachomax'] ?? 'None');
		$dir = htmlspecialchars($part2['dir'] ?? 'None');
        $vmin = 'None'; // Assuming vmin is not available in the provided JSON; set a default value.
        $icon = str_replace("/home/cs3/cs3data/lokicons", "/app/assets/lok", $icon);
		$icon = str_replace("/usr/local/cs3/lokicons", "/app/assets/lok", $icon);
		$icon = "http://".$cs3ip.$icon.".png";
        echo "
		    
		    <div class='controll' id='$internname' style='box-shadow: inset 0px 0px 5px grey; position: absolute; padding-left: 7px; padding-right: 7px; margin: 10px; border-radius: 5px; background-color: #e8e8e8; overflow: visable;'>
		    <div class='controll' style='text-align: center; white-space: nowrap; text-overflow: ellipsis;'>$name</div>
			<div style='position: absolute; padding-bottom: 20px; bottom: 340px;'>
			
			<img class='show' src='$icon' style='vertical-align: text-bottom;'></img>
		    </div>";        

        if (!empty($part2['funktionen'])) {
            foreach ($part2['funktionen'] as $funktion) {
                $nr = htmlspecialchars($funktion['nr'] ?? 'None');
                $ficon = htmlspecialchars($funktion['icon'] ?? 'None');
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
		echo "<div id='' style='width: 0px; height: 300px; position: absolute; right: 0px;'></div></div>";
    }
} else {
    echo "Failed to fetch data.";
}
?>
	</div>
	<div class="loks" style='margin-left: 9vw; margin-right: 9vw; position: absolute; bottom:0; border-radius: 10px 10px 0px 0px; width:80vw; height:150px; background-color: #e8e8e8; display:flex; align-items:center; overflow-x: scroll;'></div>
	<div style='left: 9.45vw; width: 50px; height: 120px; position: absolute; bottom: 23px; background: linear-gradient(to right, #e8e8e8 50%, #e8e8e8, transparent);'></div>
	<div style='right: 10.55vw; width: 50px; height: 120px; position: absolute; bottom: 23px; background: linear-gradient(to left, #e8e8e8 50%, #e8e8e8, transparent);'></div>
</body>
</html>


