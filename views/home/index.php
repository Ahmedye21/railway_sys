<?php
// Set variables for the base template
$pageTitle = "RailConnect - Home";
$currentPage = 'home';
ob_start(); // Start output buffering
?>

<!-- Your page-specific content here -->
<section class="hero-section text-center">
    <!-- Hero content -->
</section>

<?php
// Get the buffered content and pass to base template
$content = ob_get_clean();
// Include the base template
include __DIR__ . '/../base/index.php';
?>