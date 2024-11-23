<?php
// report_submission.php
include "path.php";
require "app/controllers/reports.php"; // Ensure this path is correct

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Report</title>
    <link rel="stylesheet" href="src/css/LoginForm.css">
</head>

<body>

    <!-- Header Section with Buttons and Search Bar -->
    <header>
        <div class="header-container">
            <div class="header-buttons">
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/user-homepage.php'">Home</button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                    Report</button>
                <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                    Events</button>
            </div>

            <div class="header-search">
                <form method="GET" action="search_results.php">
                    <input type="text" name="search_query" placeholder="Search reports or events..." required>
                    <button type="submit">Search</button>
                </form>
            </div>

        </div>
    </header>

    <!-- Report Submission Form -->

    <div class="report-form">
        <h2>Reports Near Me</h2>

        <!-- Display Reports -->
        <section class="reports-section">
            <ul>
                <?php if (!empty($reports)): ?>
                    <?php foreach ($reports as $report): ?>
                        <li class="report-item">
                            <h3><?php echo htmlspecialchars($report['issue_type']); ?></h3>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
                            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($report['date_reported'])); ?></p>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reports found.</p>
                <?php endif; ?>
            </ul>
        </section>


    </div>

</body>

</html>