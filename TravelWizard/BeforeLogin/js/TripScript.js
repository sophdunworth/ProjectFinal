document.addEventListener("DOMContentLoaded", function () {
    const carousel = document.querySelector(".carousel");
    const images = document.querySelectorAll(".carousel img");
    const prevBtn = document.querySelector(".prev");
    const nextBtn = document.querySelector(".next");
    let index = 1; // Start at 1 due to clone at beginning
    let interval;
    const totalImages = images.length;

    // Clone the first and last image to allow looping
    const firstClone = images[0].cloneNode(true);
    const lastClone = images[totalImages - 1].cloneNode(true);
    firstClone.classList.add("clone");
    lastClone.classList.add("clone");

    //Add the cloned image to the carousel 
    //Add the clone of the first image to the end 
    //Add the clone of the last image to the start
    carousel.appendChild(firstClone); 
    carousel.insertBefore(lastClone, images[0]);

    //Get the new list of images originals plus the clones
    const allImages = document.querySelectorAll(".carousel img");
    const totalSlides = allImages.length;

    //Moves the carousel to the current index 
    function updateCarousel() {
        carousel.style.transition = "transform 0.5s ease-in-out";
        carousel.style.transform = `translateX(-${index * 100}%)`;
    }

    //Go to the next image
    function nextImage() {
        //Stop if we're at the end
        if (index >= totalSlides - 1) return;
        index++;
        updateCarousel();
    }

    //Go to the previous image
    function prevImage() {
        //Stop if were at the start 
        if (index <= 0) return;
        index--;
        updateCarousel();
    }

    //Start the carousel auto-sliding every 5 seconds
    function startAutoSlide() {
        interval = setInterval(nextImage, 5000);
    }

    //Reset the timer if the user clicks next or prev
    function resetInterval() {
        clearInterval(interval);
        startAutoSlide();
    }

    carousel.addEventListener("transitionend", () => {
        //If on a cloned image jump to the real one
        if (allImages[index].classList.contains("clone")) {
            carousel.style.transition = "none";
            if (index === totalSlides - 1) {
                // Reset to first real image
                index = 1; 
            } else if (index === 0) {
                // Reset to last real image
                index = totalSlides - 2; 
            }
            carousel.style.transform = `translateX(-${index * 100}%)`;
        }
    });

    //When previous button is clicked 
    prevBtn.addEventListener("click", () => {
        prevImage();
        //Restart auto slide
        resetInterval();
    });

    //When next buttom is clicked
    nextBtn.addEventListener("click", () => {
        nextImage();
        //Restart auto slide 
        resetInterval();
    });

    // Set the starting position and start the auto slide
    carousel.style.transform = `translateX(-${index * 100}%)`;
    startAutoSlide();
});











