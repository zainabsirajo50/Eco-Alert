<?php
include "../../path.php";
include ROOT_PATH . "/app/controllers/events.php";
include ROOT_PATH . "/app/controllers/reports.php";
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="../../src/css/style.css">
    <link rel="stylesheet" href="../../src/css/LoginForm.css">
</head>

<body>

    <!-- Header Section with Buttons and Profile Dropdown -->
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

    <!-- Navbar for filters and search -->
    <nav class="navbar">
        <ul class="navbar-list">
            <li><a href="index.php">All</a></li>
            <li><a href="index.php?filter=recent">Recents</a></li>
        </ul>
        <div class="navbar-search">
            <form method="GET" action="my-rsvps.php">
                <input type="text" name="search_query" placeholder="Search by location and event..."
                    value="<?php echo isset($_GET['search_query']) ? htmlspecialchars($_GET['search_query']) : ''; ?>">
                <button type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- Events Section -->
    <div class="events-container">
        <h2>Upcoming Events</h2>

        <?php if (!empty($events)): ?>
            <div class="events-cards">
                <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <h3 class="event-name"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                        <p class="event-date"><?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                        <p class="event-location"><?php echo htmlspecialchars($event['event_location']); ?></p>
                        <p class="event-description"><?php echo htmlspecialchars($event['event_description']); ?></p>
                        <!-- Example button for further interaction -->
                        <form action="index.php" method="POST">
                            <input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>">
                            <button type="submit" name="rsvp">RSVP</button>
                        </form>

                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No upcoming events available.</p>
        <?php endif; ?>
    </div>

</body>

</html>