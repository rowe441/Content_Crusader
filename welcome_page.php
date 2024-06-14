<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome - Content Crusader Guide</title>
  <link rel="icon" type="image/x-icon" href="image/fav.png">
  <link rel="stylesheet" href="styles.css">
  <style>
    .container {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      margin-top: 180px; /* Move content closer to the header */
    }
    .login-button {
      padding: 15px 30px;
      font-size: 18px;
      background-color: silver;
      color: black;
      border: none;
      cursor: pointer;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s, transform 0.3s;
      margin-top: 20px; /* Add space between tagline and button */
    }
    .login-button:hover {
      background-color: yellow;
      transform: scale(1.05);
    }
    .nav-toggle {
      display: none;
      background-color: #007BFF;
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 5px;
    }
    .nav-toggle:hover {
      background-color: #0056b3;
    }
    @media (max-width: 600px) {
      .nav-toggle {
        display: block;
      }
      .nav-center ul {
        flex-direction: column;
      }
    }
    .nav-open {
      display: block;
    }
    .carousel {
      font-size: 6em; /* Make the carousel text larger */
      font-weight: bold;
      color: yellow;
      animation: slide 5s infinite;
      margin: 0 5%; /* Adjust margin for better fit */
    }
    .tagline {
      font-size: 2em; /* Make the tagline text larger */
      color: deeppink;
      margin-top: 20px; /* Add some space below the carousel */
    }
    @keyframes slide {
      0% { opacity: 0; transform: translateY(-50px); }
      20% { opacity: 1; transform: translateY(0); }
      80% { opacity: 1; transform: translateY(0); }
      100% { opacity: 0; transform: translateY(50px); }
    }
    .about-us {
      max-width: 1500px;
      width: 100%;
      background-color: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      color: #ffffff;
      padding: 20px;
      margin: 50px auto; /* Center the about section with margin */
    }
    .about-us h1, .about-us h2, .about-us h3, .about-us h4 {
      color: yellow;
      font-size: 2em; /* Increase font size */
      text-align: center;
      margin-bottom: 20px; /* Add some space below headings */
    }
    .about-us p {
      line-height: 1.6;
      text-align: center;
      justify-content: center;
      font-size: 1.2em; /* Increase font size */
    }
    .about-us img {
      width: 150px; /* Decrease image size */
      height: 150px; /* Decrease image size */
      border-radius: 50%;
      margin-bottom: 20px; /* Add some space below images */
    }
    .team-member {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      text-align: center;
    }
    .contact {
      margin-top: 40px;
      text-align: center;
    }
    .contact ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
      text-align: left;
    }
    .contact ul li {
      margin-bottom: 10px;
      text-align: center;
    }
    .footer {
      width: 100%;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      color: yellow;
      text-align: center;
      padding: 10px;
      position: fixed;
      bottom: 0;
    }
  </style>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap" rel="stylesheet">
</head>
<body>
  <header class="header">
    <div class="glass-container">
      <nav>
        <a href="" class="logo"><img src="image/Logo.png" alt="Logo"></a>
        <div class="nav-center">
          <button class="nav-toggle">Menu</button>
          <ul>

          </ul>
        </div>
      </nav>
    </div>
  </header>

  <div class="container">
    <div class="carousel">Welcome to Content Crusader!!!</div>
    <p class="tagline">Your ultimate guide to conquering content creation.</p>
    <a href="login.php" class="login-button">Log in</a>
  </div>

  <div class="about-us" id="about">
    <section class="intro">
      <h1>About Us</h1>
      <p>Welcome to Marketing Trends, your go-to source for the latest insights and strategies in the marketing world. We are dedicated to helping you stay ahead of the curve with up-to-date information on the most effective marketing techniques and trends.</p>
    </section>

    <section class="our-mission">
      <h2>Our Mission</h2>
      <p>Our mission is to empower marketers with the knowledge and tools they need to succeed in a rapidly evolving landscape. We believe in the power of information and aim to provide comprehensive resources that cover all aspects of marketing.</p>
    </section>

    <section class="team">
      <h2>Meet the Team</h2>
      <div class="team-member">
        <div>
          <img src="image/vince.jpg" alt="Team Member">
          <h3>Vince John Batuigas</h3>
          <h4>Trend Setter</h4>
          <p>Vince is a marketing expert with over 10 years of experience in the industry. He is passionate about discovering new trends and sharing his insights with others.</p>
        </div>
        <div>
          <img src="image/paul.jpg" alt="Team Member">
          <h3>John Paul Togonon</h3>
          <h4>Marketing Specialist</h4>
          <p>Paul has a keen eye for market analysis and is always on the lookout for new marketing strategies.</p>
        </div>
        <div>
          <img src="image/rowe.jpg" alt="Team Member">
          <h3>Rowe Sabanal</h3>
          <h4>Insights Manager</h4>
          <p>Rowe specializes in creating engaging content that captures the audience's attention.</p>
        </div>
      </div>
    </section>

    <section class="contact">
      <h2>Contact Us</h2>
      <p>If you have any questions or would like to learn more about what we do, feel free to get in touch with us.</p>
      <ul>
        <li>Vince Email: batuigasvincejohn@gmail.com</li>
        <li>John Paul Email: johnpaultogonon123@gmail.com</li>
        <li>Rowe Email: rowesabaan@gmail.com</li>
        <li>Phone: (123) 456-7890</li>
        <li>Address: Poblacion, Claveria, Misamis Oriental, Philippines</li>
      </ul>
    </section>
  </div>

  <footer class="footer">
    <p>&copy; 2024 Content Crusader Guide. All rights reserved. Disclaimer this website is for School Purposes only!</p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const nav = document.querySelector('.nav-center ul');
      const navToggle = document.querySelector('.nav-toggle');

      navToggle.addEventListener('click', function() {
        nav.classList.toggle('nav-open');
      });

      const navLinks = document.querySelectorAll('.nav-link');
      navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          document.querySelector(link.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
          });
        });
      });
    });

    function toggleDropdownMenu() {
      const dropdownMenu = document.getElementById('dropdown-menu');
      dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'flex' : 'none';
    }

    function openUpload() {
      document.getElementById('upload-container').style.display = 'block';
    }

    function closeUpload() {
      document.getElementById('upload-container').style.display = 'none';
    }

    function performSearch() {
      // Implement search functionality
    }

    function loadDynamicContent() {
      // Example dynamic content loading
      fetch('dynamic-content.html')
        .then(response => response.text())
        .then(data => {
          document.querySelector('.container').innerHTML = data;
        })
        .catch(error => console.error('Error loading content:', error));
    }

    // Call the dynamic content loading function (if needed)
    // loadDynamicContent();
  </script>
</body>
</html>
