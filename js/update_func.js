// Function to get the URL fragment
function getFragment() {
    var fragment = window.location.hash.substring(1);
    return fragment || "No fragment found";
}

// Function to send the JavaScript variable to the server
function sendToServer(data) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/save_data.php", true); // Point to the PHP script
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log("Data sent to server and saved successfully: " + data);
            document.getElementById("status").innerText = "Data sent to server: " + data;
        }
    };
    xhr.send("data=" + encodeURIComponent(data));
}

// Function to repeatedly get the fragment and send data to the server
function repeatSendToServer(interval) {
    setInterval(function() {
        var jsVar = getFragment();
        sendToServer(jsVar);
    }, interval);
}

// Send the fragment to the server every 3 seconds
repeatSendToServer(1000); // 3000 milliseconds = 3 seconds