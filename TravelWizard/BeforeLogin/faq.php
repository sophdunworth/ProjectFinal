<?php
include 'templates/header1.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ's</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/Body.css">
</head>
<body>

    <h1>🧭 Frequently Asked Questions</h1>
<div class="faq-container">
    <?php
    //Array for questions and answers 
    $faqs = [
        [
            "question" => "What is TravelWizard?",
            "answer" => "TravelWizard is your ultimate travel companion! We help you discover, plan, and book your dream trips with ease, offering detailed destination guides and user-friendly tools."
        ],
        [
            "question" => "How do I search for destinations or travel packages?",
            "answer" => "Use the search bar at the top of the homepage. You can search by country and continent."
        ],
        [
            "question" => "Do I need an account to book a trip?",
            "answer" => "Yes, creating an account allows you to save preferences, track bookings, and receive personalized recommendations."
        ],
        [
            "question" => "Is it free to use TravelWizard?",
            "answer" => "Yes! Browsing destinations, guides, and using our planning tools is completely free. Charges apply only for bookings."
        ],
        [
            "question" => "Can I cancel or change my booking?",
            "answer" => "Yes, but policies depend on the provider. Check your booking under 'My Trips' for specific info."
        ],
        [
            "question" => "How do I contact TravelWizard support?",
            "answer" => "Use the Contact Us page or email support@travelwizard.com. We reply within 24 hours."
        ],
        [
            "question" => "Are there any discounts or promotions?",
            "answer" => "Yes! Subscribe to our newsletter or follow us for the latest deals and seasonal promotions."
        ],
        [
            "question" => "Is my payment information secure?",
            "answer" => "Yes. We use SSL encryption to protect your personal and payment data."
        ],
        [
            "question" => "Can I leave reviews for places I've visited?",
            "answer" => "Yes! After your trip, you can leave a review and share your experience with other travelers."
        ]
    ];

    foreach ($faqs as $faq) {
        echo "<div class='faq-item'>";
        echo "<h3>" . htmlspecialchars($faq['question']) . "</h3>";
        echo "<p>" . htmlspecialchars($faq['answer']) . "</p>";
        echo "</div>";
    }
    ?>
    </div>

</body>
</html>

<?php
include 'templates/footer.php'; 
?>
