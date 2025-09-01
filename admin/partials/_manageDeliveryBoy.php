<?php
include '_dbconnect.php'; // Ensure correct path

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($conn) {
        if ($action === 'approve') {
            $query = "UPDATE delivery_boys SET status=? WHERE id=?";
            $status = "approved";
        } elseif ($action === 'reject') {
            $query = "UPDATE delivery_boys SET status=? WHERE id=?";
            $status = "rejected";
        } elseif ($action === 'remove') {
            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("DELETE FROM delivery_boys WHERE id = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                // Redirect with success message
                header("Location: ../index.php?page=deliveryRequests&msg=removed");
            } else {
                // Redirect with error message
                header("Location: ../index.php?page=deliveryRequests&msg=error");
            }

            $stmt->close();
            exit(); // Exit after handling remove action
        } else {
            header("Location: ../index.php?page=deliveryRequests&msg=invalid_action");
            exit();
        }

        // Handle approve and reject actions
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../index.php?page=deliveryRequests&msg=$status");
            exit();
        } else {
            die("Error updating record: " . mysqli_error($conn));
        }
    } else {
        die("Database connection not established.");
    }
} else {
    header("Location: ../index.php?page=deliveryRequests&msg=invalid_request");
}
exit();
?>