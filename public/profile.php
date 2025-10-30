<?php
/**
 * Bihak Center - Profile Detail Page
 * Displays full profile information
 */

require_once __DIR__ . '/../config/database.php';

// Get profile ID
$profileId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($profileId <= 0) {
    header('Location: index_new.php');
    exit;
}

// Fetch profile
$profile = null;
try {
    $conn = getDatabaseConnection();

    $query = "SELECT * FROM profiles WHERE id = ? AND status = 'approved' AND is_published = TRUE";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $profileId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $profile = $result->fetch_assoc();

        // Increment view count
        $updateQuery = "UPDATE profiles SET view_count = view_count + 1 WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $profileId);
        $updateStmt->execute();
        $updateStmt->close();
    }

    $stmt->close();
    closeDatabaseConnection($conn);
} catch (Exception $e) {
    error_log('Profile Error: ' . $e->getMessage());
}

if (!$profile) {
    header('Location: index_new.php');
    exit;
}

// Calculate age
$dob = new DateTime($profile['date_of_birth']);
$now = new DateTime();
$age = $now->diff($dob)->y;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($profile['short_description']); ?>">
    <title><?php echo htmlspecialchars($profile['full_name']); ?> - Bihak Center</title>

    <link rel="icon" type="image/png" href="../assets/images/favimg.png">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/profile-detail.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/responsive.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;700&family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="../assets/images/logob.png" alt="Bihak Center Logo">
        </div>

        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="index_new.php">Home</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="work.html">Our Work</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="opportunities.html">Opportunities</a></li>
                <li><a href="signup.php" class="cta-nav">Share Your Story</a></li>
            </ul>
        </nav>
    </header>

    <!-- Profile Hero -->
    <section class="profile-hero">
        <div class="hero-background"></div>
        <div class="hero-content-wrapper">
            <div class="profile-image-container">
                <img src="<?php echo htmlspecialchars($profile['profile_image']); ?>" alt="<?php echo htmlspecialchars($profile['full_name']); ?>" class="profile-image-large">
            </div>
            <div class="profile-hero-info">
                <h1 class="profile-title-large"><?php echo htmlspecialchars($profile['full_name']); ?></h1>
                <p class="profile-subtitle"><?php echo htmlspecialchars($profile['title']); ?></p>
                <div class="profile-quick-info">
                    <span><?php echo $age; ?> years old</span>
                    <span>•</span>
                    <span><?php echo htmlspecialchars($profile['city'] . ', ' . $profile['district']); ?></span>
                    <?php if (!empty($profile['field_of_study'])): ?>
                        <span>•</span>
                        <span><?php echo htmlspecialchars($profile['field_of_study']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Content -->
    <main class="profile-main">
        <div class="profile-container">
            <!-- Main Story -->
            <div class="profile-content-section">
                <div class="story-section">
                    <h2>My Story</h2>
                    <div class="story-content">
                        <?php echo nl2br(htmlspecialchars($profile['full_story'])); ?>
                    </div>
                </div>

                <?php if (!empty($profile['goals'])): ?>
                    <div class="goals-section">
                        <h2>My Goals</h2>
                        <div class="goals-content">
                            <?php echo nl2br(htmlspecialchars($profile['goals'])); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($profile['achievements'])): ?>
                    <div class="achievements-section">
                        <h2>My Achievements</h2>
                        <div class="achievements-content">
                            <?php echo nl2br(htmlspecialchars($profile['achievements'])); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($profile['media_url'])): ?>
                    <div class="media-section">
                        <h2>Media</h2>
                        <?php if ($profile['media_type'] === 'video'): ?>
                            <video src="<?php echo htmlspecialchars($profile['media_url']); ?>" controls class="profile-video"></video>
                        <?php else: ?>
                            <img src="<?php echo htmlspecialchars($profile['media_url']); ?>" alt="Additional media" class="profile-additional-image">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="profile-sidebar">
                <!-- Info Card -->
                <div class="info-card">
                    <h3>About</h3>
                    <div class="info-item">
                        <strong>Location:</strong>
                        <span><?php echo htmlspecialchars($profile['city'] . ', ' . $profile['district']); ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Age:</strong>
                        <span><?php echo $age; ?> years</span>
                    </div>
                    <?php if (!empty($profile['education_level'])): ?>
                        <div class="info-item">
                            <strong>Education:</strong>
                            <span><?php echo htmlspecialchars($profile['education_level']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($profile['current_institution'])): ?>
                        <div class="info-item">
                            <strong>Institution:</strong>
                            <span><?php echo htmlspecialchars($profile['current_institution']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($profile['field_of_study'])): ?>
                        <div class="info-item">
                            <strong>Field:</strong>
                            <span><?php echo htmlspecialchars($profile['field_of_study']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Social Media -->
                <?php if (!empty($profile['facebook_url']) || !empty($profile['twitter_url']) || !empty($profile['instagram_url']) || !empty($profile['linkedin_url'])): ?>
                    <div class="social-card">
                        <h3>Connect</h3>
                        <div class="social-links-sidebar">
                            <?php if (!empty($profile['facebook_url'])): ?>
                                <a href="<?php echo htmlspecialchars($profile['facebook_url']); ?>" target="_blank" rel="noopener noreferrer" class="social-link facebook">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($profile['twitter_url'])): ?>
                                <a href="<?php echo htmlspecialchars($profile['twitter_url']); ?>" target="_blank" rel="noopener noreferrer" class="social-link twitter">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                    Twitter
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($profile['instagram_url'])): ?>
                                <a href="<?php echo htmlspecialchars($profile['instagram_url']); ?>" target="_blank" rel="noopener noreferrer" class="social-link instagram">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                        <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                    </svg>
                                    Instagram
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($profile['linkedin_url'])): ?>
                                <a href="<?php echo htmlspecialchars($profile['linkedin_url']); ?>" target="_blank" rel="noopener noreferrer" class="social-link linkedin">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                    LinkedIn
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Support CTA -->
                <div class="support-card">
                    <h3>Support This Story</h3>
                    <p>Want to help <?php echo htmlspecialchars(explode(' ', $profile['full_name'])[0]); ?> achieve their dreams?</p>
                    <a href="contact.html?ref=<?php echo $profile['id']; ?>" class="btn-support">Get in Touch</a>
                </div>

                <!-- Stats -->
                <div class="stats-card">
                    <div class="stat-item">
                        <strong><?php echo number_format($profile['view_count']); ?></strong>
                        <span>Views</span>
                    </div>
                    <div class="stat-item">
                        <strong><?php echo date('M d, Y', strtotime($profile['created_at'])); ?></strong>
                        <span>Joined</span>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Back to Stories -->
        <div class="back-link-container">
            <a href="index_new.php#stories" class="back-link">← Back to All Stories</a>
        </div>
    </main>

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
                    <li><a href="https://facebook.com/bihakcenter" target="_blank"><img src="../assets/images/facebk.png" alt="Facebook"><span>Bihak Center</span></a></li>
                    <li><a href="https://instagram.com/bihakcenter" target="_blank"><img src="../assets/images/image.png" alt="Instagram"><span>Bihak Center</span></a></li>
                    <li><a href="https://twitter.com/bihak_center" target="_blank"><img src="../assets/images/xx.webp" alt="Twitter"><span>@bihak_center</span></a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 Bihak Center | All Rights Reserved</p>
        </div>
    </footer>

    <!-- Scroll to Top -->
    <button id="myBtn" aria-label="Scroll to top">↑</button>

    <script src="../assets/js/scroll-to-top.js"></script>
</body>
</html>
