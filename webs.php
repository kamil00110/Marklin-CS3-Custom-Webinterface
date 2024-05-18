<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocket Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #log {
            width: 100%;
            height: 300px;
            border: 1px solid #ccc;
            overflow-y: scroll;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        #message {
            width: 80%;
            padding: 10px;
            margin-right: 10px;
        }
        #send {
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <h1>WebSocket Client</h1>
    <div id="log"></div>
    <input type="text" id="message" placeholder="Type your message here">
    <button id="send">Send</button>

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
            const message = document.getElementById('message').value;
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
</body>
</html>
