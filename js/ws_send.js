function sendWs(type, name, func, addres, state){
        let ws;
		let ip = getCookie("cs3ip");
        const wsUrl = 'ws://'+ip+':8080/socket.io/?EIO=3&transport=websocket'; // Adjust the endpoint as needed
		ws = new WebSocket(wsUrl);
		
		if (type == "lok"){
			
			if (func == "speed"){
				let message = '42["event_data","{ \\"lok\\":{\\"name\\":\\"'+name+'\\",\\"speed\\":\\"'+addres+'\\"}}"]';
				sendws(message);
			}
			if (func == "dir"){
				let message = '42["event_data","{ \\"lok\\":{\\"name\\":\\"'+name+'\\",\\"dir\\":\\"'+addres+'\\"}}"]';
				sendws(message);
			}
			if (func == "func"){
				let message = '42["event_data","{ \\"lok\\":{\\"name\\":\\"'+name+'\\",\\"func\\":\\"'+addres+'\\",\\"state\\":\\"'+state+'\\"}}"]';
				sendws(message);
			}
		}
		if (type == "mag"){
			let message = '42["event_data","{ \\"mag\\":{\\"id\\":\\"'+addres+'\\",\\"state\\":\\"'+state+'\\"}}"]';
				sendws(message);
		}
		if (type == "s88"){
			let message = '42["event_data","{ \\"s88\\":{\\"oldstate\\":\\"2\\",\\"s88kontakt\\":\\"'+addres+'\\",\\"s88kennung\\":\\"'+name+'\\",\\"state\\":\\"'+state+'\\"}}"]';
			sendws(message);
		}
		if (type == "cs3"){
			let message = '42["event_data","{ \\"cs3\\":{\\"state\\":\\"'+state+'\\"}}"]';
		    sendws(message);
		}
        ws.send(message);
		log('Sent: ' + message);

}