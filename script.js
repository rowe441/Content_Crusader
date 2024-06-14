document.getElementById("showPassword").addEventListener("change", function() {
    var pswInput = document.getElementById("psw");
    if (pswInput.type === "password") {
        pswInput.type = "text";
    } else {
        pswInput.type = "password";
    }
});

// Function to create animated dots
function createDots() {
    const dotContainer = document.getElementById("animated-dots");

    // Create dots at random positions
    for (let i = 0; i < 10; i++) {
        const dot = document.createElement("div");
        dot.classList.add("dot");
        dot.style.left = Math.random() * 100 + "%";
        dot.style.top = Math.random() * 100 + "%";
        dotContainer.appendChild(dot);
    }
}

// Call the function when the page loads
window.onload = function() {
    createDots();
};
