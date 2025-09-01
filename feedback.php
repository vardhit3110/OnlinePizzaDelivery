<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" crossorigin="anonymous">
    <!-- Latest Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <title>Feedback</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">

    <style>
        .feedback-container {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .feedback-display {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-height: 500px;
            overflow-y: auto;
        }
        .star-rating {
            display: flex;
            justify-content: center;
            direction: rtl;
            font-size: 2rem;
        }
        .star-rating input { display: none; }
        .star-rating label {
            cursor: pointer;
            color: #ccc;
            transition: color 0.3s ease-in-out;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: gold;
            transform: scale(1.1);
        }
        .feedback-card {
            border-radius: 10px;
            border: 1px solid #ddd;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .feedback-card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .feedback-card h5 { color: #ffcc00; }
    </style>
</head>
<body>      

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php?error=Please login to access feedback.");
    exit;
}

include 'partials/_dbconnect.php';
include 'partials/_nav.php';

$user_email = "";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user email
    $userSql = "SELECT email FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $userSql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $user_email = $row['email'];
        } else {
            echo "No user found with ID: $user_id <br>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "SQL Error: " . mysqli_error($conn) . "<br>";
    }
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <div class="feedback-container">
                <h2 class="text-center">Submit Your Feedback</h2>
                <form action="partials/_handleFeedback.php" method="POST">
                <?php
                  $passSql = "SELECT * FROM users WHERE id='$userId'";
                  $passResult = mysqli_query($conn, $passSql);
                  $passRow = mysqli_fetch_assoc($passResult);
                  $email = $passRow['email'];
                  $phone = $passRow['phone'];
                  ?>
                        <div class="form-group mt-3">
                          <b><label for="email">Email:</label></b>
                          <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter Your Email" required value="<?php echo $email ?>" readonly>
</div>

                    <div class="form-group">
                        <label for="rating">Rating (Click a Star)</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" required><label for="star5">&#9733;</label>
                            <input type="radio" id="star4" name="rating" value="4"><label for="star4">&#9733;</label>
                            <input type="radio" id="star3" name="rating" value="3"><label for="star3">&#9733;</label>
                            <input type="radio" id="star2" name="rating" value="2"><label for="star2">&#9733;</label>
                            <input type="radio" id="star1" name="rating" value="1"><label for="star1">&#9733;</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment">Your Feedback</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Submit Feedback</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="feedback-display">
                <h2 class="text-center">Recent Feedback</h2>
                <div class="row" id="feedback-container">
                <?php 
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Define user_id

$sql = "SELECT f.*, u.email 
        FROM feedback f 
        JOIN users u ON f.user_id = u.id 
        ORDER BY f.submission_date DESC LIMIT 3"; 

$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    $is_admin = isset($_SESSION['user_type']) && $_SESSION['user_type'] == '1';
    $feedback_id = $row['feedback_id'];

    echo '<div class="col-md-12 mb-3">
            <div class="card feedback-card p-3">
                <p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>
                <h5>Rating: ' . str_repeat('⭐', $row['rating']) . '</h5>
                <p>"' . htmlspecialchars($row['comment']) . '"</p>
                <small class="text-muted">Posted on ' . $row['submission_date'] . '</small>';

    $is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;

    if ($is_logged_in && ($row['user_id'] == $user_id || $is_admin)) {
        echo '<form action="partials/_handleFeedback.php" method="POST" class="mt-2">
                <input type="hidden" name="feedback_id" value="' . $feedback_id . '">
                <div class="form-group">
                    <label for="rating">Update Rating</label>
                    <select class="form-control" name="rating">
                        <option value="5" ' . ($row['rating'] == '5' ? 'selected' : '') . '>5 Stars</option>
                        <option value="4" ' . ($row['rating'] == '4' ? 'selected' : '') . '>4 Stars</option>
                        <option value="3" ' . ($row['rating'] == '3' ? 'selected' : '') . '>3 Stars</option>
                        <option value="2" ' . ($row['rating'] == '2' ? 'selected' : '') . '>2 Stars</option>
                        <option value="1" ' . ($row['rating'] == '1' ? 'selected' : '') . '>1 Star</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comment">Update Your Feedback</label>
                    <textarea class="form-control" name="comment" rows="3" required>' . htmlspecialchars($row['comment']) . '</textarea>
                </div>
                <button type="submit" name="update_feedback" class="btn btn-warning btn-block">Update Feedback</button>
            </form>';
    }   
    echo '</div></div>';
}
?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'partials/_footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#viewAllFeedback").click(function() {
            $.ajax({
                url: "partials/_fetchAllFeedback.php",
                method: "GET",
                success: function(response) {
                    console.log(response); // Debugging step
                    if (response.trim() !== "") {
                        $("#feedback-container").html(response);
                        $("#viewAllFeedback").hide();
                    } else {
                        $("#feedback-container").html('<p class="text-center text-muted">No feedback available.</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert("Error fetching feedback. Please try again.");
                }
            });
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>         
<script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>

</body>
</html>
