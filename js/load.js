  async function loadContentForClass(className, url, childClass, version) {
	if (version == 1){
    try {
      const response = await fetch(url);
      if (response.ok) {
        const newContent = await response.text();
        const elements = document.getElementsByClassName(className);

        // Save the scroll positions of the specific children
        const scrollPositions = [];
        for (let element of elements) {
          const child = element.querySelector(`.${childClass}`);
          if (child) {
            scrollPositions.push({
              scrollTop: child.scrollTop,
              scrollLeft: child.scrollLeft
            });
          } else {
            scrollPositions.push({ scrollTop: 0, scrollLeft: 0 }); // Default scroll positions to 0
          }
        }

        // Update the content of each element
        for (let element of elements) {
          element.innerHTML = newContent;
        }

        // Restore the scroll positions of the specific children
        for (let i = 0; i < elements.length; i++) {
          const child = elements[i].querySelector(`.${childClass}`);
          if (child) {
            child.scrollTop = scrollPositions[i].scrollTop;
            child.scrollLeft = scrollPositions[i].scrollLeft;
          }
        }
      } else {
        console.error(`Error fetching content for ${className}:`, response.statusText);
      }
    } catch (error) {
      console.error(`Error fetching content for ${className}:`, error);
    }
	}
  }

  // Example usage: Adjust the childClass to the specific child you want to track
  setInterval(() => loadContentForClass('loks', 'loks.php', 'specificChildClass', '1'), 1000);
  setInterval(() => loadContentForClass('state', 'scripts/state_but.php', 'specificChildClass', '1'), 1000);
  setInterval(() => loadContentForClass('mag_but', 'scripts/mag_but.php', 'specificChildClass', '1'), 1000);
  setInterval(() => loadContentForClass('s88_but', 'scripts/s88_but.php', 'specificChildClass', '1'), 1000);
  setInterval(() => loadContentForClass('lokcontroll', 'lokcontroll.php', 'specificChildClass', '1'), 1000);
 let Int1 = 0;
  function change(file){
	      clearInterval(Int1);
	      Int1 = setInterval(() => loadContentForClass('mags', file, 'scrollmag', '1'), 1000); 
  }