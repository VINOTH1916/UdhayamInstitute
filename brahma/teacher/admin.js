// Get all slide elements
const slides = document.querySelectorAll('.slide');

// Function to load content from PHP file and show the slide
function loadSlideContent(url, index) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            slides[index].innerHTML = data;
            showSlide(index);
        })
        .catch(error => console.error('Error:', error));
}

// Function to show a specific slide based on its index
function showSlide(index) {
    // Hide all slides
    slides.forEach(slide => slide.classList.remove('show'));

    // Show the selected slide
    slides[index].classList.add('show');
}

// Add event listeners to navigation links
document.querySelectorAll('nav a').forEach((link, index) => {
    link.addEventListener('click', function (event) {
        // Prevent the default behavior of the link
        event.preventDefault();

        // Get the href attribute (URL of the PHP file)
        const targetUrl = this.getAttribute('href');

        // Load content from PHP file and show the slide
        loadSlideContent(targetUrl, index);
    });
});

// Show the first slide by default
showSlide(0);
