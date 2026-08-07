<?php
/**
 * TAYO-TECH — About page
 */
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About TAYO-TECH | Tanzania Youth-Tech Forum</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a href="index.php" class="brand" aria-label="TAYO-TECH home">
        <img src="assets/img/logo.png" alt="TAYO-TECH logo" class="brand-logo">
      </a>
      <nav class="main-nav">
        <a href="index.php">Home</a>
        <a href="about.php" class="active">About Us</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
      </nav>
      <div class="header-actions">
        <a href="login.php" class="btn btn-outline">Login</a>
        <a href="register.php" class="btn btn-primary">Register</a>
      </div>
    </div>
  </header>

  <main class="about-page">
    <section class="about-hero">
      <div class="container">
        <div class="about-hero-copy">
          <span class="hero-pill">About TAYO-TECH</span>
          <h1>We empower Tanzania’s youth through technology, training, and opportunity.</h1>
          <p>As a national digital platform, TAYO-TECH connects young Tanzanians with career pathways, entrepreneurship support, skills development, and a community of innovators.</p>
        </div>
      </div>
    </section>

    <section class="about-content">
      <div class="container about-grid">
        <div class="about-text">
          <h2>Our mission</h2>
          <p>TAYO-TECH exists to build a stronger future for Tanzanian youth by creating access to digital skills, employment pathways, and entrepreneurial resources.</p>
          <p>We work with private sector partners, educators, and civic organizations to deliver practical programs that connect talent with opportunity.</p>

          <h2>Our vision</h2>
          <p>We envision a thriving youth-led technology ecosystem where learners become job creators, innovators, and community leaders across Tanzania.</p>
          <p>Our goal is to make digital opportunity inclusive, professional, and aligned to the needs of the local economy.</p>

          <h2>What we do</h2>
          <ul>
            <li>Deliver training and mentorship for youth in digital skills, entrepreneurship, and career readiness.</li>
            <li>Provide access to job opportunities, internships, and startup funding.</li>
            <li>Connect young people with networks, events, and community programs that accelerate growth.</li>
          </ul>

          <a href="contact.php" class="btn btn-primary">Contact Us</a>
        </div>

        <div class="about-images">
          <figure class="about-figure about-figure-large">
            <img src="assets/img/about-festo.jpeg" alt="Founder Festo John Mchodo">
            <figcaption>
              <h3>Founder</h3>
              <p>Festo John Mchodo leads TAYO-TECH’s vision and strategic direction for youth empowerment across Tanzania.</p>
            </figcaption>
          </figure>
          <div class="about-image-row">
            <figure class="about-figure">
              <img src="assets/img/about-edson.jpeg" alt="General Manager Edson">
              <figcaption>
                <h3>Manager</h3>
                <p>Edson coordinates operations and delivers the programs that support training, mentorship, and community engagement.</p>
              </figcaption>
            </figure>
            <figure class="about-figure">
              <img src="assets/img/about-ashura.jpeg" alt="General Secretary Ashura">
              <figcaption>
                <h3>General Secretary</h3>
                <p>Ashura oversees administration, partnerships, and communications to maintain strong stakeholder collaboration.</p>
              </figcaption>
            </figure>
          </div>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
