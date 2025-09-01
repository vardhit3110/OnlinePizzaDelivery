<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function validateInput($input) {
        return isset($input) && trim($input) !== '';
    }

    if (isset($_POST['createItem'])) {
        $name = $_POST["name"];
        $description = $_POST["description"];
        $categoryId = $_POST["categoryId"];
        $price = $_POST["price"];

        if (!validateInput($name) || !validateInput($description)) {
            echo "<script>alert('Name and description cannot be empty or contain only spaces.');
                  window.location=document.referrer;
              </script>";
            exit();
        }

        $sql = "INSERT INTO `item` (`itemName`, `itemPrice`, `itemDesc`, `itemCategorieId`, `itemPubDate`) VALUES ('$name', '$price', '$description', '$categoryId', current_timestamp())";   
        $result = mysqli_query($conn, $sql);
        $itemId = $conn->insert_id; 
        if ($result) {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                $newName = 'item-' . $itemId; 
                $newfilename = $newName . ".jpg";

                $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/OnlinePizzaDelivery/img/';
                $uploadfile = $uploaddir . $newfilename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                    echo "<script>alert('Item created successfully.');
                            window.location=document.referrer;
                        </script>";
                } else {
                    echo "<script>alert('Image upload failed.');
                            window.location=document.referrer;
                        </script>";
                }
            } else {
                echo '<script>alert("Please select a valid image file to upload.");
                        window.location=document.referrer;
                    </script>';
            }
        } else {
            echo "<script>alert('Failed to create item.');
                    window.location=document.referrer;
                </script>";
        }
    }

    if (isset($_POST['removeItem'])) {
        $itemId = $_POST["itemId"]; 
        $sql = "DELETE FROM `item` WHERE `itemId`='$itemId'";   
        $result = mysqli_query($conn, $sql);
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/OnlinePizzaDelivery/img/item-" . $itemId . ".jpg"; 
        if ($result) {
            if (file_exists($filename)) {
                unlink($filename);
            }
            echo "<script>alert('Item removed successfully.');
                window.location=document.referrer;
            </script>";
        } else {
            echo "<script>alert('Failed to remove item.');
            window.location=document.referrer;
            </script>";
        }
    }

    if (isset($_POST['updateItem'])) {
        $itemId = $_POST["itemId"]; 
        $itemName = $_POST["name"]; 
        $itemDesc = $_POST["desc"]; 
        $itemPrice = $_POST["price"]; 
        $itemCategorieId = $_POST["catId"]; 

        if (!validateInput($itemName) || !validateInput($itemDesc)) {
            echo "<script>alert('Name and description cannot be empty or contain only spaces.');
                  window.location=document.referrer;
              </script>";
            exit();
        }

        $sql = "UPDATE `item` SET `itemName`='$itemName', `itemPrice`='$itemPrice', `itemDesc`='$itemDesc', `itemCategorieId`='$itemCategorieId' WHERE `itemId`='$itemId'";   
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('Item updated successfully.');
                window.location=document.referrer;
                </script>";
        } else {
            echo "<script>alert('Failed to update item.');
                window.location=document.referrer;
                </script>";
        }
    }

    if (isset($_POST['updateItemPhoto'])) {
        $itemId = $_POST["itemId"]; 
        $check = getimagesize($_FILES["itemimage"]["tmp_name"]);
        if ($check !== false) {
            $newName = 'item-' . $itemId; 
            $newfilename = $newName . ".jpg";

            $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/OnlinePizzaDelivery/img/';
            $uploadfile = $uploaddir . $newfilename;

            if (move_uploaded_file($_FILES['itemimage']['tmp_name'], $uploadfile)) {
                echo "<script>alert('Image updated successfully.');
                        window.location=document.referrer;
                    </script>";
            } else {
                echo "<script>alert('Image upload failed.');
                        window.location=document.referrer;
                    </script>";
            }
        } else {
            echo '<script>alert("Please select a valid image file to upload.");
            window.location=document.referrer;
                </script>';
        }
    }

    // Handle Item Status Update
    if (isset($_POST['status']) && isset($_POST['itemId'])) { 
        $itemId = $_POST['itemId']; 
        $status = $_POST['status'];

        $sql = "UPDATE `item` SET `status`='$status' WHERE `itemId`='$itemId'"; 
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>
                alert('Item status updated successfully.');
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