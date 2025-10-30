<?php
/**
 * Bihak Center - Home Page
 * Main landing page for the Bihak Center website
 */

require_once __DIR__ . '/../config/database.php';

// Fetch user stories from database
$userStories = [];
try {
    $conn = getDatabaseConnection();
    $result = $conn->query("SELECT * FROM usagers ORDER BY created_at DESC");

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $userStories[] = $row;
        }
    }

    closeDatabaseConnection($conn);
} catch (Exception $e) {
    error_log($e->getMessage());
    // Continue without user stories if database fails
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bihak Center - Empowering young people through development and education">
    <meta name="keywords" content="education, development, youth empowerment, Rwanda">
    <meta name="author" content="Bihak Center">

    <title>Bihak Center - Home</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/images/favimg.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/user-tiles.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/responsive.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;700&family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="../assets/images/logob.png" alt="Bihak Center Logo">
        </div>

        <!-- Language Switcher -->
        <div class="language-switcher">
            <a href="#" onclick="changeLanguage('fr'); return false;">Français</a> |
            <a href="#" onclick="changeLanguage('en'); return false;">English</a>
        </div>

        <!-- Navigation -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="work.html">Our Work</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="opportunities.html">Opportunities</a></li>
                <li><a href="login-join.html">My Account</a></li>
            </ul>
        </nav>
    </header>

    <!-- Google Translate Element (Hidden) -->
    <div id="google_translate_element" style="display: none;"></div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to Bihak Center</h1>
            <p>Empowering young people through development and education.</p>
            <a href="contact.html" class="cta-button">Get Involved</a>
        </div>
        <div class="hero-image">
            <img src="../assets/images/Designer.jpeg" alt="Bihak Center Activities">
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="articles">
        <h2 class="section-title">Success Stories</h2>

        <div class="article">
            <p>Sofia, an aspiring artist, wanted to attend an elite art school but couldn't afford tuition...</p>
            <img src="../assets/images/Designer_5.jpeg" alt="Sofia's Artwork">
            <a href="work.html#education" class="btn">Learn More</a>
        </div>

        <div class="article">
            <p>Sofia applied for multiple art scholarships and sold her work online to fund her education...</p>
            <img src="../assets/images/Designer_6.jpeg" alt="Sofia Painting">
            <a href="work.html#education" class="btn">Learn More</a>
        </div>

        <div class="article">
            <p>David, a high school senior passionate about computer science, faced financial hardships...</p>
            <img src="../assets/images/Designer_2.jpeg" alt="David's Work">
            <a href="work.html#technology" class="btn">Learn More</a>
        </div>

        <div class="article">
            <p>Through a crowdfunding campaign and mentorship, David raised enough to study software engineering...</p>
            <img src="../assets/images/Designer_7.jpeg" alt="David Coding">
            <a href="work.html#technology" class="btn">Learn More</a>
        </div>

        <div class="article">
            <p>Sofia's perseverance led to her work being featured in galleries worldwide...</p>
            <img src="../assets/images/Designer_4.jpeg" alt="Sofia's Gallery">
            <a href="work.html#education" class="btn">Learn More</a>
        </div>

        <div class="article">
            <p>David is now interning at a leading tech firm, applying his coding skills in real-world projects...</p>
            <img src="../assets/images/Designer_3.jpeg" alt="David's Internship">
            <a href="work.html#technology" class="btn">Learn More</a>
        </div>

        <div class="article">
            <p>Sofia is mentoring other young artists, helping them secure grants and scholarships...</p>
            <img src="../assets/images/Designer_1.jpeg" alt="Sofia Teaching">
            <a href="work.html#education" class="btn">Learn More</a>
        </div>

        <div class="article">
            <p>David is launching a startup to teach coding to underprivileged students...</p>
            <img src="../assets/images/Designer_8.jpeg" alt="David's Startup">
            <a href="work.html#technology" class="btn">Learn More</a>
        </div>

        <!-- Dynamic User Stories from Database -->
        <?php if (!empty($userStories)): ?>
            <div class="user-tiles">
                <?php foreach ($userStories as $story): ?>
                    <div class="tile">
                        <h3><?php echo htmlspecialchars($story['name']); ?></h3>
                        <p><?php echo substr(htmlspecialchars($story['description']), 0, 100); ?>...</p>
                        <a href="user_detail.php?id=<?php echo $story['id']; ?>" class="btn">Read More</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>Discover Our Programs</h3>
                <ul>
                    <li><a href="work.html#orientation">Academic & Professional Orientation</a></li>
                    <li><a href="work.html#coaching">Project Development Coaching</a></li>
                    <li><a href="work.html#financial">Financial Support</a></li>
                    <li><a href="work.html#technology">Technology for Development</a></li>
                </ul>
            </div>

            <div class="about-us">
                <h3>About Us</h3>
                <ul>
                    <li><a href="about.html#vision">Our Vision</a></li>
                    <li><a href="about.html#mission">Our Mission</a></li>
                    <li><a href="about.html#motivation">Our Motivation</a></li>
                </ul>
            </div>

            <div class="social-links">
                <h3>Follow Us</h3>
                <ul>
                    <li>
                        <a href="https://facebook.com/bihakcenter" target="_blank" rel="noopener noreferrer">
                            <img src="../assets/images/facebk.png" alt="Facebook">
                            <span>Bihak Center</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://instagram.com/bihakcenter" target="_blank" rel="noopener noreferrer">
                            <img src="../assets/images/image.png" alt="Instagram">
                            <span>Bihak Center</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://twitter.com/bihak_center" target="_blank" rel="noopener noreferrer">
                            <img src="../assets/images/xx.webp" alt="Twitter">
                            <span>@bihak_center</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Bihak Center | All Rights Reserved</p>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="myBtn" aria-label="Scroll to top">↑</button>

    <!-- JavaScript -->
    <script src="../assets/js/translate.js"></script>
    <script src="../assets/js/scroll-to-top.js"></script>
</body>
</html>
