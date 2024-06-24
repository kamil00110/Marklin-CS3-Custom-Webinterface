	<link rel="stylesheet" href="webs.css">
	<div id="messageContainer" onscroll="handleScroll()" style="margin-left: 180px; margin-top: 200px; width: 50vw; border-radius: 20px; background-color: black; color: white; height: 250px; overflow-y: auto; padding: 10px;">
        <pre id="messageContent"></pre>
    <div style="font-family: monospace;" id="commandInput">
        <span style="color: lime;">$</span>
		<span class="blink">|</span>
		<input class="prompt" type="text" id="command" placeholder="">
        <button  class="prompt" id="Btn" onclick="sendCommand()">Send</button>
    </div>
	</div>
	<div id="messageContainer" onscroll="handleScroll()" style="margin-left: 180px; margin-top: 30px; width: 50vw; border-radius: 20px; background-color: black; color: white; height: 250px; overflow-y: auto; padding: 10px;">
        <pre id="log"></pre>
    <div style="font-family: monospace;" id="commandInput">
        <span>ws></span>
		<span class="blink"></span>
		<input class="prompt" type="text" id="message" placeholder="">
        <button  class="prompt" id="send" onclick="sendCommand()">Send</button>
    </div>
	</div>
	<div style="position: absolute; left: 50px; top: 200px; width: 100px; height: 570px; background-color: #e8e8e8; border-radius: 10px;">
	<button class='button' style='width=200px;' onclick='startPhpScript("scripts/clear.php")'>  
		  <div style=' margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	          <svg
	              style='margin: 12px 12px 5px 5px;'
                  width='83%'
                  viewBox='0 0 50 40'
                  fill='none'
                  xmlns='http://www.w3.org/2000/svg'>
                  
				  <text  font-weight='bold' x='9' y='15' fill='gray' font-family='Arial' font-size='15' text-anchor='start' dominant-baseline='hanging'>clear</text>
              </svg>
	     </div>
    </button>
	<button class='button' style='width=200px;' onclick='startPhpScript("scripts/img.php")'>  
		  <div style=' margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	          <svg
	              style='margin: 12px 12px 5px 5px;'
                  width='83%'
                  viewBox='0 0 50 40'
                  fill='none'
                  xmlns='http://www.w3.org/2000/svg'>
                  
				  <text  font-weight='bold' x='0' y='15' fill='gray' font-family='Arial' font-size='13' text-anchor='start' dominant-baseline='hanging'>compile</text>
              </svg>
	     </div>
    </button>
	<button class='button' style='width=200px;' onclick='uploadFile("tmp/Update.btrfs")'>  
		  <div style=' margin: 10px; border-radius: 10px; position: relative; background-color: #ededed; width: 72px; height: 72px;'>
	          <svg
	              style='margin: 12px 12px 5px 5px;'
                  width='83%'
                  viewBox='0 0 50 40'
                  fill='none'
                  xmlns='http://www.w3.org/2000/svg'>
                  
				  <text  font-weight='bold' x='10' y='8' fill='gray' font-family='Arial' font-size='15' text-anchor='root' dominant-baseline='hanging'>root</text>
				  <text  font-weight='bold' x='13' y='23' fill='gray' font-family='Arial' font-size='15' text-anchor='root' dominant-baseline='hanging'>cs3</text>
              </svg>
	     </div>
    </button>
	</div>
	<script src="js/upload.js"></script>
		<script> 
// JavaScript code to start a PHP script with a specified path
function startPhpScript(phpScriptPath) {
    fetch(phpScriptPath)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            console.log('PHP script executed successfully');
            console.log(data); // The response from the PHP script
        })
        .catch(error => console.error('Error:', error));
}

</script>
<script>startPhpScript("scripts/send.php")</script>
	<script>
var input = document.getElementById("command");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter" && document.activeElement === input) {
    event.preventDefault();
    document.getElementById("Btn").click();
  }
});
</script>

<script>
var input = document.getElementById("message");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") && document.activeElement === input) {
    event.preventDefault();
    document.getElementById("send").click();
  }
});
</script>
<script src="js/ws_terminal.js"></script>
    <script>
    let autoScroll = true; // Flag to indicate if auto-scrolling is enabled

        // Function to fetch messages from the PHP script
        function fetchMessages() {
            fetch('scripts/cmd.php')
                .then(response => response.text())
                .then(data => {
                    // Replace $ with green $ using a span with class green-dollar
                    const formattedData = data.replace(/\$/g, '<span style="color: lime;">$</span>');
                    document.getElementById('messageContent').innerHTML = formattedData;

                    // Auto-scroll if enabled and at the bottom
                    if (autoScroll) {
                        scrollToBottom();
                    }
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Function to send command to the PHP script
        function sendCommand() {
            const command = document.getElementById('command').value;
            if (command) {
                const formData = new FormData();
                formData.append('command', command);

                fetch('scripts/cmd.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                 
                    document.getElementById('command').value = '';
                    fetchMessages();  // Refresh messages after sending a command
                })
                .catch(error => console.error('Error sending command:', error));
            } else {
                
            }
        }

        // Function to scroll to the bottom of the message container
        function scrollToBottom() {
            const messageContainer = document.getElementById('messageContainer');
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        // Function to handle scroll events in the message container
        function handleScroll() {
            const messageContainer = document.getElementById('messageContainer');
            // Check if user is at the bottom of the container
            const isAtBottom = messageContainer.scrollHeight - messageContainer.clientHeight <= messageContainer.scrollTop + 1;

            // Update autoScroll flag based on scroll position
            autoScroll = isAtBottom;
        }

        // Fetch messages initially when the page loads
        window.onload = () => {
            fetchMessages();
            // Set an interval to fetch messages every 5 seconds (5000 milliseconds)
            setInterval(fetchMessages, 100);
        };
	</script>
