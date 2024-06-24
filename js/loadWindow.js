var currentIframe = null;

        function loadWindow(fileName) {
            // Remove currently loaded iframe if exists
            if (currentIframe) {
                currentIframe.style.display = 'none';
            }

            // Create new iframe element
            var iframe = document.createElement('iframe');
            iframe.src = fileName;
            iframe.onload = function() {
                iframe.style.display = 'block';  // Show the iframe once it's loaded
				iframe.style.width = '98vw'; 
				iframe.style.height = '98vh'; 
				iframe.style.border = 'none'; 
            };

            // Replace current iframe with new one
            var iframeContainer = document.getElementById('con');
            iframeContainer.innerHTML = '';  // Clear container
            iframeContainer.appendChild(iframe);

            // Update currentIframe reference
            currentIframe = iframe;
        }