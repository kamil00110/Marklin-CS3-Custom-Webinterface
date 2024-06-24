async function uploadFile(file) {
	// Function to generate a random number between min and max (inclusive)
function getRandomNumber(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Generate two random numbers for the calculation
const num1 = getRandomNumber(1, 10);
const num2 = getRandomNumber(1, 10);

// Calculate the correct answer
const correctAnswer = num1 + num2;

// Prompt the user with a warning and the calculation to solve
const userAnswer = prompt(`Warning! Rooting your CS§ can be dangerous continue:\n\nWhat is ${num1} + ${num2}?`);

// Check if the user's answer is correct
if (parseInt(userAnswer) === correctAnswer) {
    console.log("Code execution continues...");
} else {
    alert("Incorect. CS3 not rooted");
}
    try {
		let ip = getCookie("cs3ip");
        const formData = new FormData();
        formData.append('file', file);
       //const response3 = await fetch('http://192.168.2.125/app/api/system/reboot', {
         //   method: 'PUT',
           // params: { http://192.168.2.125/app/api/update/update http://192.168.2.125/app/api/update/source http://192.168.2.125/app/api/system/test?type=
             //  id: "2",
			   //update: "true"
            //}
        //});   
        
        try {
    const response3 = await fetch('http://'+ip+'/app/api/filemanager/startUploadProcess', {
        method: 'PUT',
        body: formData
    });
} catch (error) {
    // Log or handle the error if needed, or simply ignore it
}

try {
    const response2 = await fetch('http://'+ip+'/app/api/filemanager/upload?mountpoint=cs3&type=backup&name=../../../../media/usb0/test.btrfs', {
        method: 'POST',
		body: formData
    });
} catch (error) {
    // Log or handle the error if needed, or simply ignore it
}
await new Promise(r => setTimeout(r, 5000));
try {
    const response = await fetch('http://'+ip+'/app/api/update/update', {
        method: 'GET'
    });
} catch (error) {
    // Log or handle the error if needed, or simply ignore it
}

        if (response.ok) {
            console.log('File uploaded successfully');
        } else {
            console.error('Failed to upload file:', response.statusText);
        }
    } catch (error) {
        console.error('Error uploading file:', error.message);
    }
}

