document.addEventListener('DOMContentLoaded', () => {
    const slider = document.getElementById('mySlider');
    const sliderValueDisplay = document.getElementById('sliderValue');

    // Load the stored value from local storage if it exists
    const storedValue = localStorage.getItem('sliderValue');
    if (storedValue !== null) {
        slider.value = storedValue;
        sliderValueDisplay.textContent = storedValue;
    }

    // Event listener for when the slider value changes
    slider.addEventListener('input', () => {
        sliderValueDisplay.textContent = slider.value;
    });

    // Event listener for when the slider is clicked
    slider.addEventListener('click', () => {
        const value = slider.value;
        alert(`Slider value: ${value}`); // Show popup with the value
    });

    // Event listener for when the slider value changes (for better UX, handle release)
    slider.addEventListener('change', () => {
        const value = slider.value;
        localStorage.setItem('sliderValue', value); // Store the value in local storage
    });
});
