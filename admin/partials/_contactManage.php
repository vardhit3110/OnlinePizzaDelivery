<?php
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Handling Contact Reply and Status Update
    if (isset($_POST['contactReply'])) {
        $contactId = $_POST['contactId'];
        $message = $_POST['message'];
        $userId = $_POST['userId'];

        // Insert reply into contactreply table
        $sqlReply = "INSERT INTO `contactreply` (`contactId`, `userId`, `message`, `datetime`) 
                     VALUES ('$contactId', '$userId', '$message', current_timestamp())";
        $resultReply = mysqli_query($conn, $sqlReply);

        if ($resultReply) {
            // Update status to 'resolved' after reply is sent
            $sqlUpdateStatus = "UPDATE `contact` SET `status` = 'resolved' WHERE `contactId` = '$contactId'";
            $resultStatus = mysqli_query($conn, $sqlUpdateStatus);

            if ($resultStatus) {
                echo "<script>alert('Reply sent successfully and status updated.');
                        window.location=document.referrer;
                    </script>";
            } else {
                echo "<script>alert('Reply sent, but failed to update status.');
                        window.location=document.referrer;
                    </script>";
            }
        } else {
            echo "<script>alert('Failed to send reply.');
                    window.location=document.referrer;
                </script>";
        }
    }

    // Handling Status Update Manually (if needed)
    if (isset($_POST['status']) && isset($_POST['contactId'])) {
        $contactId = $_POST['contactId'];
        $status = $_POST['status'];

        $sql = "UPDATE `contact` SET `status` = '$status' WHERE `contactId` = '$contactId'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Status updated successfully.');
                    window.location=document.referrer;
                </script>";
        } else {
            echo "<script>alert('Failed to update status.');
                    window.location=document.referrer;
                </script>";
        }
    }
}
?>