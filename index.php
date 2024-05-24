<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WebSocket Server Scanner</title>
<style>
.loader {
  border: 36px solid #f3f3f3; /* Light grey */
  border-top: 36px solid red;
  border-radius: 50%;
  width: 90px;
  height: 90px;
  animation: spin 2s linear infinite;
  position: relative;
  top: -10%;
  left: 20%;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
body{
	font-family: Arial;
	font-size: 30px;
}
</style>
</head>
<body>
<?php 
if(isset($_COOKIE["cs3ip"])){
	echo $_COOKIE["cs3ip"];
	header("Location: webs.php");
}
?>
<div style="display:none">
  <label for="port">Port:</label>
  <input type="number" id="port" value="8080">
</div>
<button style="display:none" id="scanButton">Scan</button>
<div style="position: fixed; top: 50%; left: 50%; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%);text-align: center;justify-content: center; width: 300px; height: 300px;">
<div class="loader"></div>
<div id="resultContainer"></div>
</div>

<script>
setTimeout(click, 100)
function click(){
  document.getElementById('scanButton').click();
}

</script>
<script>
document.getElementById('scanButton').addEventListener('click', async () => {
  const startIp = '<?php $localIp = getHostByName(getHostName()); echo substr($localIp, 0, strrpos($localIp, '.')).".2";?>';
  const endIp = '<?php $localIp = getHostByName(getHostName()); echo substr($localIp, 0, strrpos($localIp, '.')).".254";?>';
  const port = parseInt(document.getElementById('port').value, 10);

  const resultContainer = document.getElementById('resultContainer');
  resultContainer.innerHTML = 'Scanning network for CS3...';

  const scanNetwork = async () => {
    const ipList = [];
    const start = startIp.split('.').map(Number);
    const end = endIp.split('.').map(Number);
    for (let i = start[0]; i <= end[0]; i++) {
      for (let j = start[1]; j <= end[1]; j++) {
        for (let k = start[2]; k <= end[2]; k++) {
          for (let l = start[3]; l <= end[3]; l++) {
            ipList.push(`${i}.${j}.${k}.${l}`);
          }
        }
      }
    }

    const results = await Promise.all(ipList.map(ip => checkWebSocketServer(ip, port)));
    const openServers = results.filter(result => result.status === 'open').map(result => result.ip);
    return openServers;
  };

  const checkWebSocketServer = async (ip, port) => {
    return new Promise(resolve => {
      const ws = new WebSocket(`ws://${ip}:8080/socket.io/?EIO=3&transport=websocket`);

      ws.onopen = () => {
        ws.close();
        resolve({ ip, status: 'open' });
      };

      ws.onerror = () => {
        resolve({ ip, status: 'closed' });
      };

      ws.onclose = () => {
        resolve({ ip, status: 'closed' });
      };
    });
  };

  const servers = await scanNetwork();
  const resultText = servers.length > 0 ? `Found CS3 at:<br>${servers.join('<br>')}` : 'No CS3 found.';
  servers.length > 0 ? document.write("cs3 found"):alert("no cs3 found");
  resultContainer.innerHTML = resultText;
  if(servers.length < 1){
      servers = prompt('enter ip here:');
	  document.cookie = "cs3ip="+servers+"; expires=Thu, 18 Dec 2033 12:00:00 UTC; path=/";
	  window.location.replace("webs.php");
  }
  else{
	  document.cookie = "cs3ip="+servers+"; expires=Thu, 18 Dec 2033 12:00:00 UTC; path=/";
	  window.location.replace("webs.php");
  }
  
});
</script>
</body>
</html>
