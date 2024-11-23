let slideIndex = 0;
showSlides(slideIndex);

// Function to change slide
function changeSlide(n) {
    showSlides(slideIndex += n);
}

// Function to display the correct slide
function showSlides(n) {
    let slides = document.getElementsByClassName("slide");

    if (n >= slides.length) {
        slideIndex = 0;
    } 
    if (n < 0) {
        slideIndex = slides.length - 1;
    }

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slides[slideIndex].style.display = "block";
}

// Automatic slideshow
setInterval(() => {
    changeSlide(1);
}, 10000); // Change image every 10 seconds
