<?php
    session_start();
    include "path.php";
    require ROOT_PATH . "/app/database/connection.php";

    // Retrives the user id
    $user_id = $_SESSION['user_id'];

    //Prepares SQL query
    $sql = "SELECT * 
            FROM reports r
            JOIN issue_types it ON r.issue_type_id = it.id
            WHERE userid = ?
            ORDER BY date_reported DESC";
    
            
    $stmt = $conn->prepare($sql);

    $reports = []; // Where reports will be stored

    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute(); //Executes query
        $result = $stmt->get_result(); //Gets result

        // if ($result->num_rows > 0) {
        //     echo "Number of reports found: " . $result->num_rows . "<br>";
        // } else {
        //     echo "No reports found for user ID: $user_id<br>";
        // }

        while ($report = $result->fetch_assoc()) {
            $reports[] = $report;
        }

        $stmt->close();

    } else {
        $error = "Failed to prepare SQL statement.";
    }

    $conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eco Alert</title>
    <link rel="stylesheet" href="src/css/view_profile.css">
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <link rel="stylesheet" href="src/css/style.css">
    <script src="src/js/modal.js"></script>
</head>

<body id='report-body'>
    <?php include(ROOT_PATH . "/app/messages/header.php"); ?>

    <div class="events-container" id="my-report-header">
        <div style='text-align: right;'>
            <a id='rsvp-link' href="<?php echo BASE_URL; ?>/my-rsvps.php">My RSVPS</a>
        </div>

        <h2>My Reports</h2>



        <section class="event-cards" id="my-report-group">   
        <!-- <div style="display:flex; flex-direction: row; background-color: #FFFFFF; gap: 10px;"> -->
                <?php if (!empty($reports)): ?>
                    
                    <?php foreach ($reports as $report): ?>
                        <!-- Wrap the entire report card inside an <a> tag -->
                        <div class="event-card" id="my-report">
                        <a href="<?php echo BASE_URL; ?>/view-reports.php?reportid=<?php echo $report['reportid']; ?>"
                            class="">
                            <h3 class="event-name"><?php echo htmlspecialchars($report['issue_name']); ?> Issue</h3>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars($report['location']); ?></p>
                                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($report['date_reported'])); ?></p>
                                <p><strong>Upvotes:</strong> <?php echo $report['upvote_count']; ?></p>

                                <!-- Display the status with corresponding color -->
                                <p><strong>Status:</strong>
                                    <?php
                                    $status = htmlspecialchars($report['status']);
                                    // Style based on the status value
                                    $status_class = '';
                                    switch ($status) {
                                        case 'Pending':
                                            $status_class = 'status-pending';
                                            break;
                                        case 'In Progress':
                                            $status_class = 'status-in-progress';
                                            break;
                                        case 'Resolved':
                                            $status_class = 'status-resolved';
                                            break;
                                        default:
                                            $status_class = 'status-default';
                                    }
                                    ?>
                                    <span class="<?php echo $status_class; ?>"><?php echo $status; ?></span>
                                </p>

                        </a>
                        <div className='report-buttons'>
                            <a href="edit-report.php?reportid=<?= $report['reportid'] ?>" class="edit-button">Edit</a>
                            <button class='delete-button' data-reportid="<?= $report['reportid'] ?>">Delete</button>
                        </div>

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reports found.</p>
                <?php endif; ?>

                <div id="deleteModal" class="modal">
                    <div class="modal-content">
                        <h3>Are you sure you want to delete this report?</h2>
                        <p>This action cannot be undone.</p>
                        <div class="modal-buttons">
                            <button id="confirmDelete" class="confirm">Delete</button>
                            <button id="cancelDelete" class="cancel">Cancel</button>
                        </div>
                    </div>
                </div>
        </section>
        </div>
</body>

</html>