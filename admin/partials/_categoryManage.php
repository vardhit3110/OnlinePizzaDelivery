<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Create Category
    if (isset($_POST['createCategory'])) {
        $name = isset($_POST["name"]) ? mysqli_real_escape_string($conn, $_POST["name"]) : '';
        $desc = isset($_POST["desc"]) ? mysqli_real_escape_string($conn, $_POST["desc"]) : '';
        $status = isset($_POST["status"]) ? mysqli_real_escape_string($conn, $_POST["status"]) : 'active';
        $imagePath = NULL; // Default NULL if no image uploaded

        if (!empty($_FILES["image"]["tmp_name"])) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $newfilename = "card-" . time() . ".jpg"; // Unique filename using timestamp
                $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/OnlinePizzaDelivery/img/';
                $uploadfile = $uploaddir . $newfilename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                    $imagePath = "img/" . $newfilename; // Store relative path
                }
            }
        }

        // Insert into database
        $sql = "INSERT INTO `categories` (`categorieName`, `categorieImage`, `categorieDesc`, `status`, `categorieCreateDate`) 
                VALUES ('$name', '$imagePath', '$desc', '$status', current_timestamp())";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Category created successfully.');
                    window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Error creating category.');
                    window.location=document.referrer;</script>";
        }
    }

    // Remove Category
    if (isset($_POST['removeCategory'])) {
        $catId = intval($_POST["catId"]);

        // Get the image path
        $sql = "SELECT `categorieImage` FROM `categories` WHERE `categorieId` = $catId";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        
        if ($row && !empty($row['categorieImage'])) {
            $filename = $_SERVER['DOCUMENT_ROOT'] . "/OnlinePizzaDelivery/" . $row['categorieImage'];
            if (file_exists($filename)) {
                unlink($filename);
            }
        }

        // Delete the category
        $sql = "DELETE FROM `categories` WHERE `categorieId` = $catId";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Category removed successfully.');
                window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Failed to remove category.');
                window.location=document.referrer;</script>";
        }
    }

    // Update Category
    if (isset($_POST['updateCategory'])) {
        $catId = intval($_POST["catId"]);
        $catName = isset($_POST["name"]) ? mysqli_real_escape_string($conn, $_POST["name"]) : '';
        $catDesc = isset($_POST["desc"]) ? mysqli_real_escape_string($conn, $_POST["desc"]) : '';
        $status = isset($_POST["status"]) ? mysqli_real_escape_string($conn, $_POST["status"]) : 'active';

        $sql = "UPDATE `categories` SET `categorieName` = '$catName', 
                                        `categorieDesc` = '$catDesc', 
                                        `status` = '$status' 
                WHERE `categorieId` = $catId";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Category updated successfully.');
                window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Failed to update category.');
                window.location=document.referrer;</script>";
        }
    }

    // Update Category Photo
    if (isset($_POST['updateCatPhoto'])) {
        $catId = intval($_POST["catId"]);

        if (!empty($_FILES["catimage"]["tmp_name"])) {
            $check = getimagesize($_FILES["catimage"]["tmp_name"]);
            if ($check !== false) {
                // Get the old image path
                $sql = "SELECT `categorieImage` FROM `categories` WHERE `categorieId` = $catId";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);

                if ($row && !empty($row['categorieImage'])) {
                    $oldImage = $_SERVER['DOCUMENT_ROOT'] . "/OnlinePizzaDelivery/" . $row['categorieImage'];
                    if (file_exists($oldImage)) {
                        unlink($oldImage);
                    }
                }

                // Upload new image
                $newfilename = "card-" . time() . ".jpg";
                $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/OnlinePizzaDelivery/img/';
                $uploadfile = $uploaddir . $newfilename;

                if (move_uploaded_file($_FILES['catimage']['tmp_name'], $uploadfile)) {
                    $imagePath = "img/" . $newfilename;

                    // Update the image path in the database
                    $sql = "UPDATE `categories` SET `categorieImage`='$imagePath' WHERE `categorieId`=$catId";
                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        echo "<script>alert('Image updated successfully.');
                                window.location=document.referrer;</script>";
                    } else {
                        echo "<script>alert('Failed to update image in database.');
                                window.location=document.referrer;</script>";
                    }
                } else {
                    echo "<script>alert('Failed to upload image.');
                            window.location=document.referrer;</script>";
                }
            } else {
                echo "<script>alert('Please select a valid image file to upload.');
                        window.location=document.referrer;</script>";
            }
        } else {
            echo "<script>alert('No image selected.');
                    window.location=document.referrer;</script>";
        }
    }

    // Update Category Status
    if (isset($_POST['status']) && isset($_POST['catId'])) {
        $catId = intval($_POST['catId']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        
        $sql = "UPDATE `categories` SET `status`='$status' WHERE `categorieId`=$catId";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>
                alert('Category status updated successfully.');
                window.location=document.referrer;
            </script>";
        } else {
            echo "<script>
                alert('Error updating status: " . mysqli_error($conn) . "');
                window.location=document.referrer;
            </script>";
        }
    }
}
?>
