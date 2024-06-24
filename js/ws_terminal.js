let ws;
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
		let ip = getCookie("cs3ip");
        const wsUrl = 'ws://'+ip+':8080/socket.io/?EIO=3&transport=websocket'; // Adjust the endpoint as needed

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
                    setTimeout(connect, 100); // Attempt to reconnect after 3 seconds
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
		function sendws(data) {
            const message = data;
            if (message && ws.readyState === WebSocket.OPEN) {
                ws.send(message);
                log('Sent: ' + message);
                document.getElementById('message').value = '';
            } else {
                log('WebSocket is not open.');
            }
        };

        function log(message) {
            const logDiv = document.getElementById('log');
            const newMessage = document.createElement('div');
            newMessage.textContent = message;
            logDiv.appendChild(newMessage);
            logDiv.scrollTop = logDiv.scrollHeight; // Auto scroll to bottom
        }

        // Initialize WebSocket connection
        connect();