<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="webs.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #log {
            width: 50vw;
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        #message {
            width: 50%;
            padding: 10px;
            margin-right: 10px;
        }
        #send {
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    
	<div style='display:flex; align-items:center; padding-left: 70px; padding-right: 70px; background-color: #e8e8e8; border-radius: 0px 0px 20px 20px; margin-left: 1vw; margin-right: 1vw; position: absolute; top: 0; width:90vw; height: 90px;'>
	<div style=' margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	<svg
	   style='margin: 12px 12px 5px 5px;'
       width="83%"
       viewBox="0 0 50 40"
       fill="none"
       xmlns="http://www.w3.org/2000/svg">
       <rect x="5" y="4" width="41" height="32" rx="8" fill="#f7f7f7" stroke="#4f4f4f" stroke-width="1.5"/>
       <path d="M14 15L22 20L14 25" stroke="#4f4f4f" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
       <rect x="25" y="26" width="12" height="2" rx="0.5" fill="#4f4f4f"/>
    </svg>
	</div>
	<div class='help' style='font-size:0px; margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	<p>Every action on the CS3 renturns data</p>
	<p>set s88 kontakt 42["event_data","{\"s88\":{\"oldstate\":\"2\",\"s88kontakt\":\"1\",\"s88kennung\":\"1\",\"state\":\"1 or 0\"}}"] </p>
    <p>set mag artikel 42["event_data","{\"mag\":{\"id\":\"8\",\"state\":\"1 or 128\"}}"]</p>
    <p>set lok speed 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"speed\":\"0 - 1000\"}}"]</p>
    <p>set lok direction 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"dir\":\"1 or 0\"}}"]</p>
    <p>set lok funktion 42["event_data","{\"lok\":{\"name\":\"132#20439-1\",\"func\":\"1\",\"state\":\"1 or 0\"}}"]</p>
    <p>stop CS3 42["event_data","{\"cs3\":{\"state\":\"0 or 1\"}}"]</p>
	<p>state info and other data at http://CS3 IP/app/api/loks, mag, mags, devs, prefs, gbs, info, filemanager, automatics, helps, system or</p>
	<p>http://CS3 IP/config/geraet.vrs, lokomotive.cs2, fahrstrassen.cs2, gleisbild.cs2, magnetartikel.cs2</p>
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
    </div>
    <div style="position: relative; top:200px;"><h1>WebSocket Client</h1><div id="log"></div>
    <input type="text" id="message" placeholder="Input Commands">
    <button id="send">Send</button>
	</div>
    <script>
      async function loadContent() {
        try {
          // Fetch the new content from your server or an API endpoint
          const response = await fetch('loks.php'); // Replace with your URL
          if (response.ok) {
            const newContent = await response.text();
            document.getElementById('loks').innerHTML = newContent;
          } else {
            console.error('Error fetching content:', response.statusText);
          }
        } catch (error) {
          console.error('Error fetching content:', error);
        }
      }

      // Reload the content every 100 milliseconds
      setInterval(loadContent, 100);
    </script>

    <script>
        let ws;
        const wsUrl = 'ws://192.168.2.125:8080/socket.io/?EIO=3&transport=websocket'; // Adjust the endpoint as needed

        function connect() {
            log('Attempting to connect to WebSocket...');
            ws = new WebSocket(wsUrl);

            ws.onopen = function(event) {
                log('Connected to WebSocket');
            };

            ws.onmessage = function(event) {
                log('Received: ' + event.data);
            };

            ws.onerror = function(event) {
                log('WebSocket error: ' + event);
            };

            ws.onclose = function(event) {
                log(`WebSocket closed. Code: ${event.code}, Reason: ${event.reason}`);
                if (event.code !== 1000) { // 1000 means normal closure
                    log('Reconnecting in 3 seconds...');
                    setTimeout(connect, 3000); // Attempt to reconnect after 3 seconds
                }
            };
        }

        document.getElementById('send').addEventListener('click', function() {
            const message = document.getElementById('message').value
            if (message && ws.readyState === WebSocket.OPEN) {
                ws.send(message);
                log('Sent: ' + message);
                document.getElementById('message').value = '';
            } else {
                log('WebSocket is not open.');
            }
        });

        function log(message) {
            const logDiv = document.getElementById('log');
            const newMessage = document.createElement('div');
            newMessage.textContent = message;
            logDiv.appendChild(newMessage);
            logDiv.scrollTop = logDiv.scrollHeight; // Auto scroll to bottom
        }

        // Initialize WebSocket connection
        connect();
    </script>
	<div id="loks" style='margin-left: 9vw; margin-right: 9vw; position: absolute; bottom:0; border-radius: 10px 10px 0px 0px; width:80vw; height:150px; background-color: #e8e8e8; display:flex; align-items:center; overflow-x: scroll;'></div>
	<div style='left: 9.45vw; width: 50px; height: 120px; position: absolute; bottom: 23px; background: linear-gradient(to right, #e8e8e8 50%, #e8e8e8, transparent);'></div>
	<div style='right: 10.55vw; width: 50px; height: 120px; position: absolute; bottom: 23px; background: linear-gradient(to left, #e8e8e8 50%, #e8e8e8, transparent);'></div>
</body>
</html>

