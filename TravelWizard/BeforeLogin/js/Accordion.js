 // Accordion Functionality
    document.querySelectorAll(".accordion-header").forEach(button => {
        //Click event listener to each accordian header
        button.addEventListener("click", function () {
            const content = this.nextElementSibling;
            const icon = this.querySelector(".toggle-icon");

            // Check if clicked section is already open 
            const isActive = content.classList.contains("show");

            // Close all other accordion sections
            document.querySelectorAll(".accordion-content").forEach(item => {
                item.classList.remove("show");
            });
            //Reset all toggle icons
            document.querySelectorAll(".toggle-icon").forEach(i => {
                i.textContent = "+";
            });

            // If the clicked section is not already open, open it and change the icon changes
            if (!isActive) {
                content.classList.add("show");
                icon.textContent = "−";
            }
        });
    });