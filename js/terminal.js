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
                    alert(data);
                    document.getElementById('command').value = '';
                    fetchMessages();  // Refresh messages after sending a command
                })
                .catch(error => console.error('Error sending command:', error));
            } else {
                alert('Please enter a command.');
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