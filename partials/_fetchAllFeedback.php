<?php
include '_dbconnect.php';

// Fetch only approved feedback
$sql = "SELECT f.*, u.email 
        FROM feedback f
        JOIN users u ON f.user_id = u.user_id 
        WHERE f.status = 'approved'
        ORDER BY f.submission_date DESC";

$result = mysqli_query($conn, $sql);
$output = "";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<div class="col-md-12 mb-3">
                        <div class="card feedback-card p-3">
                            <h5>Rating: ' . str_repeat('⭐', intval($row['rating'])) . '</h5>
                            <p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>
                            <p>"' . htmlspecialchars($row['comment']) . '"</p>
                            <small class="text-muted">Posted on ' . $row['submission_date'] . '</small>
                        </div>
                    </div>';
    }
} else {
    $output .= '<p class="text-center text-muted">No feedback available.</p>';
}

echo $output;
?>