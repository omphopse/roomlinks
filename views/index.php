<?php require_once __DIR__ . '/../config/constants.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="styles/tailwind.css" />
  <link rel="stylesheet" href="styles/style.css" />
  <link rel="stylesheet" href="styles/hide-scrollbar.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />
  

  <title>RoomLinks | Offical Website</title>

  <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />
  
  <meta name="description"
    content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
  <meta name="author" content="RoomLinks" />
  <!-- Open Graph / Facebook -->
  <meta property="og:title" content="RoomLinks | Official Website" />
  <meta property="og:description"
    content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
  <meta property="og:image" content="assets/hero-section/description.png" />
  <meta property="og:url" content="https://roomlinks.in" />
  <meta property="og:type" content="website" />
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="RoomLinks | Official Website" />
  <meta name="twitter:description"
    content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
  <meta name="twitter:image" content="assets/hero-section/description.png" />
  <!-- Canonical URL -->
  <link rel="canonical" href="https://roomlinks.in" />

</head>

<body class="flex flex-col min-h-screen">
  <?php include 'partials/header.php' ?>
  <div class="flex-grow">

    <div class="hero-content">
      <div class="hero-header">
        <p class="hero-tagline">More than just a roommate find your vibeMate.</p>
        <h1 class="hero-title">Better <span class="hero-title-highlight">Rooms</span>,<br> Better<span
            class="hero-title-highlight"> Roommates</span>,
          <br> Find Yours <span class="hero-title-highlight">Today</span>!
        </h1>
      </div>

      <div class="input text-center mt-10">
        <div class="hero-input-wrap">
          <!-- Font Awesome icon -->
          <i class="fas fa-search hero-search-icon "></i>
          <!-- Input -->
          <input type="text" id="searchInput" class="hero-input" placeholder="Search your home here..." value="" />
          <script>
            // Redirect to listings with search value on Enter
            document.addEventListener('DOMContentLoaded', function () {
              var searchInput = document.getElementById('searchInput');
              if (searchInput) {
                searchInput.addEventListener('keydown', function (e) {
                  if (e.key === 'Enter') {
                    var val = searchInput.value.trim();
                    if (val !== '') {
                      window.location.href = '<?php echo APP_URL; ?>/listings?search=' + encodeURIComponent(val);
                    }
                  }
                });
              }
            });
          </script>
        </div>

        <div class="hero-img-wrap">
          <img src="assets/hero-section/hero.png" class="hero-img" alt="roomlinks" />
        </div>

      </div>
    </div>
    <!-- end of hero section -->

    <!-- start of listing section -->
    <section class="custom-listing-section">
      <div class="custom-listing-header">
        <h2 class="custom-listing-title">Recent Listings</h2>
      </div>
      <div class="custom-listing-cards">
        <?php include __DIR__ . '/partials/recent_listings.php'; ?>
      </div>
      <div class="flex justify-center mt-8">
        <a href="<?php echo APP_URL; ?>/listings" class="btn-view-all-listings">View All Listings</a>
      </div>
    </section>
    <!-- end of listing section -->

    <!-- start of connect with us section -->
    <section class="whyus-section md:px-8">
      <div class="whyus-section-bg">
        <svg viewBox="0 0 1440 320" fill="none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <linearGradient id="whyus-bg-gradient" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0%" stop-color="#1976d2" />
              <stop offset="100%" stop-color="#42a5f5" />
            </linearGradient>
          </defs>
          <ellipse cx="300" cy="120" rx="320" ry="80" fill="url(#whyus-bg-gradient)" />
          <ellipse cx="1200" cy="200" rx="240" ry="60" fill="url(#whyus-bg-gradient)" />
        </svg>
      </div>
      <div class="whyus-inner">
        <h2 class="whyus-section-title">Why Choose <span class="whyus-title-highlight">RoomLinks?</span></h2>
        <p class="whyus-section-desc">
          At RoomLinks, we believe finding a roommate shouldn’t feel like a gamble. That’s why we make it easy to
          discover people who share your vibe and values. Listing your property? You can do it here without any
          brokerage or hidden fees. From browsing to moving in, everything is designed to be simple, transparent, and
          driven by a sense of community. Welcome to a better way to find your next space and the people you’ll share it
          with.
        </p>
        <div class="whyus-features">
          <div class="whyus-feature-card whyus-feature-animate">
            <div class="whyus-feature-icon">
              <i class="fas fa-users"></i>
            </div>
            <h3 class="whyus-feature-title">Find Your VibeMate</h3>
            <p class="whyus-feature-desc">Connect with roommates who match your lifestyle, interests, and vibe.</p>
          </div>
          <div class="whyus-feature-card whyus-feature-animate" style="animation-delay:0.15s;">
            <div class="whyus-feature-icon">
              <i class="fas fa-home"></i>
            </div>
            <h3 class="whyus-feature-title">No Brokerage</h3>
            <p class="whyus-feature-desc">List your property for free no hidden fees or middlemen involved.</p>
          </div>
          <div class="whyus-feature-card whyus-feature-animate" style="animation-delay:0.3s;">
            <div class="whyus-feature-icon">
              <i class="fas fa-handshake"></i>
            </div>
            <h3 class="whyus-feature-title">Community Driven</h3>
            <p class="whyus-feature-desc">A safe, supportive platform built for genuine connections and trust.</p>
          </div>
        </div>
        <div class="whyus-brand-cta mt-8">
          <span class="whyus-section-desc font-semibold text-[--primary-color] text-center block">RoomLinks: Where new
            journeys begin, and real connections are made.</span>
        </div>
      </div>
    </section>
    <!-- end of connect with us section -->

    <!-- Connect with us section -->
    <a href="<?php echo APP_URL; ?>/contact">
      <section class="connect-section md:px-8">
        <div class="z-10 mb-8 md:mb-0 md:mr-8 flex-1">
          <h2 class="connect-title">Connect with Us</h2>
          <p class="connect-desc">Ready to bring your vision to life? Reach out and let's make it
            happen!</p>
        </div>
        <a href="<?php echo APP_URL; ?>/contact" class="connect-btn">
          <i class="fas fa-phone-alt mr-3"></i>
          Contact Us
        </a>
        <!-- Decorative background shapes using root colors -->
        <div class="absolute inset-0 pointer-events-none">
          <svg class="absolute right-0 bottom-0 w-80 h-40 opacity-5" viewBox="0 0 420 180" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="340" cy="150" rx="80" ry="35" fill="#2563eb" />
            <ellipse cx="230" cy="110" rx="50" ry="20" fill="#3b82f6" />
            <ellipse cx="390" cy="170" rx="25" ry="10" fill="#60a5fa" />
          </svg>
          <svg class="absolute left-0 top-0 w-44 h-24 opacity-5" viewBox="0 0 220 120" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <ellipse cx="70" cy="50" rx="50" ry="20" fill="#2563eb" />
            <ellipse cx="150" cy="30" rx="30" ry="12" fill="#3b82f6" />
          </svg>
        </div>
      </section>
    </a>

  </div>
  <?php include 'partials/footer.php' ?>
</body>

</html>