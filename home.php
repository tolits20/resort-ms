<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Resort Paradise</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body, html {
      font-family: Arial, sans-serif;
      scroll-behavior: smooth;
    }
    header {
      background: rgba(0, 0, 0, 0.7);
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
      color: #fff;
      padding: 20px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    header .logo {
      font-size: 1.5em;
      font-weight: bold;
    }
    header nav {
      display: flex;
      align-items: center;
    }
    header nav a {
      color: #fff;
      text-decoration: none;
      margin: 0 15px;
      font-weight: bold;
    }
    header nav a:hover {
      text-decoration: underline;
    }
    header .profile {
      margin-left: 20px;
    }
    header .profile img {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #fff;
      display:block;

      /* width: 40px;
            height: 40px;
            border-radius: 50%;
            background: url('profile.jpg') center/cover;
            border: 2px solid white;
            display: block; */
    }
    .hero {
      background: url('images/resort-hero.jpg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-align: center;
      padding: 0 20px;
    }
    .hero h1 {
      font-size: 3em;
      margin-bottom: 20px;
    }
    .hero p {
      font-size: 1.2em;
      max-width: 600px;
    }
    .content {
      padding: 100px 40px 60px;
      background: #f8f8f8;
    }
    .section {
      margin-bottom: 40px;
    }
    .section h2 {
      margin-bottom: 20px;
      color: #333;
      text-align: center;
    }
    .section p {
      font-size: 1em;
      color: #555;
      line-height: 1.6;
      max-width: 800px;
      margin: 0 auto;
    }
    footer {
      background: #333;
      color: #fff;
      padding: 20px 40px;
      text-align: center;
    }
    @media (max-width: 768px) {
      header {
        flex-direction: column;
      }
      .hero h1 {
        font-size: 2.5em;
      }
      .hero p {
        font-size: 1em;
      }
      header nav {
        margin-top: 10px;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="logo">
      Resort Paradise
    </div>
    <nav>
      <a href="#home">Home</a>
      <a href="#accommodations">Accommodations</a>
      <a href="#amenities">Amenities</a>
      <a href="#gallery">Gallery</a>
      <a href="#contact">Contact</a>
      <div class="profile">
        <!-- Replace 'images/profile.jpg' with the path to your profile picture -->
        <img src="images/profile.jpg" alt="Profile Picture">
      </div>
    </nav>
  </header>

  <section class="hero" id="home">
    <div>
      <h1>Welcome to Resort Paradise</h1>
      <p>Experience the luxury and serenity of our exclusive resort, where every moment is designed for relaxation and rejuvenation.</p>
    </div>
  </section>

  <section class="content">
    <div class="section" id="accommodations">
      <h2>Accommodations</h2>
      <p>Our resort offers a wide range of luxurious accommodations to suit your needs—from cozy suites to spacious villas, all with stunning views and world-class amenities.</p>
    </div>
    <div class="section" id="amenities">
      <h2>Amenities</h2>
      <p>Enjoy our award-winning spa, gourmet dining, infinity pools, and a variety of recreational activities. Every detail has been carefully crafted to ensure your ultimate comfort and enjoyment.</p>
    </div>
    <div class="section" id="gallery">
      <h2>Gallery</h2>
      <p>Take a look at our resort through our photo gallery, featuring breathtaking landscapes, elegant interiors, and memorable moments captured during your stay.</p>
    </div>
    <div class="section" id="contact">
      <h2>Contact Us</h2>
      <p>If you have any questions or would like to book your stay, please get in touch with us. Our friendly staff is here to help make your dream vacation a reality.</p>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 Resort Paradise. All Rights Reserved.</p>
  </footer>
</body>
</html>
