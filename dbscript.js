document.addEventListener("DOMContentLoaded", function() {
  const buttons = document.querySelectorAll('.filter-button');
  const contents = document.querySelectorAll('.content');

  buttons.forEach(button => {
    button.addEventListener('click', function() {
      const category = this.getAttribute('data-category');
      // Remove 'active' class from all buttons
      buttons.forEach(btn => {
        btn.classList.remove('active');
      });
      // Add 'active' class to the clicked button
      this.classList.add('active');
      // Hide all content sections
      contents.forEach(content => {
        content.style.display = 'none';
      });
      // Show content for the selected category
      document.getElementById(category.toLowerCase() + '-content').style.display = 'block';
    });
  });

  // Add event listener for profile icon
  document.getElementById("profile-icon").addEventListener("click", function() {
    var menu = document.getElementById("dropdown-menu");
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
  });

  // Close dropdown menu when clicking outside of it
  window.addEventListener("click", function(event) {
    var menu = document.getElementById("dropdown-menu");
    var icon = document.getElementById("profile-icon");
    if (event.target !== menu && event.target !== icon) {
      menu.style.display = "none";
    }
  });
});
