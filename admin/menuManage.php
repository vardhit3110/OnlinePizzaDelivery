<style>
    /* Style for the dropdown text */
    .status-select {
        font-weight: bold;
    }

    /* Style for Available option */
    .status-select option[value="available"] {
        color: green;
        font-weight: bold;
    }

    /* Style for Unavailable option */
    .status-select option[value="unavailable"] {
        color: red;
        font-weight: bold;
    }
</style>
<!-- Admin Menubar Scrolling -->
<style>
    #scroll {
        position: -webkit-sticky;
        position: sticky;
        top: 1rem;
        display: block !important;
        height: calc(105vh - 6rem);
        padding-left: 0.25rem;
        margin-left: -0.25rem;
        overflow-y: scroll;
        /* still allows scrolling */

        /* Hide scrollbar but keep scroll functionality */
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* Internet Explorer 10+ */
    }

    #scroll::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Edge */
    }
</style>
<div class="container-fluid" style="margin-top:98px">
    <div class="col-lg-12">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-4">
                <form action="partials/_menuManage.php" method="post" enctype="multipart/form-data">
                    <div class="card mb-3">
                        <div class="card-header" style="background-color: rgb(111 202 203);">
                            Create New Item
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="control-label">Name: </label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Description: </label>
                                <textarea cols="30" rows="3" class="form-control" name="description"
                                    required></textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Price of Small Size</label>
                                <input type="number" class="form-control" name="price" required min="1">
                                <label class="control-label text-danger"><small>*The price will change if you choose
                                        medium and large pizza.</small></label>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Category: </label>
                                <select name="categoryId" id="categoryId" class="custom-select browser-default"
                                    required>
                                    <option hidden disabled selected value>None</option>
                                    <?php
                                    $catsql = "SELECT * FROM `categories`";
                                    $catresult = mysqli_query($conn, $catsql);
                                    while ($row = mysqli_fetch_assoc($catresult)) {
                                        $catId = $row['categorieId'];
                                        $catName = $row['categorieName'];
                                        echo '<option value="' . $catId . '">' . $catName . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="image" class="control-label">Image</label>
                                <input type="file" name="image" id="image" accept=".jpg" class="form-control" required
                                    style="border:none;">
                                <small id="Info" class="form-text text-muted mx-3">Please .jpg file upload.</small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="mx-auto">
                                    <button type="submit" name="createItem" class="btn btn-sm btn-primary"> Create
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Panel -->
            <div class="col-md-8" id="scroll">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover mb-0">
                            <thead style="background-color: rgb(111 202 203);">
                                <tr>
                                    <th class="text-center" style="width:7%;">Cat. Id</th>
                                    <th class="text-center">Img</th>
                                    <th class="text-center" style="width:40%;">Item Detail</th>
                                    <th class="text-center" style="width:15%;">Status</th>
                                    <th class="text-center" style="width:18%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM `item`";
                                $result = mysqli_query($conn, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $itemId = $row['itemId'];
                                    $itemName = $row['itemName'];
                                    $itemPrice = $row['itemPrice'];
                                    $itemDesc = $row['itemDesc'];
                                    $itemCategorieId = $row['itemCategorieId'];
                                    $itemStatus = $row['status'];

                                    
                                    echo '<tr>
                                            <td class="text-center">' . $itemCategorieId . '</td>
                                            <td class="text-center">
                                                <img src="/OnlinePizzaDelivery/img/pizza-' . $itemId . '.jpg" alt="Item Image" width="100px" height="100px">
                                            </td>
                                            <td>
                                                <p><strong>Name:</strong> ' . $itemName . '</p>
                                                <p><strong>Description:</strong> <span class="truncate">' . $itemDesc . '</span></p>
                                                <p><strong>Price:</strong> ₹' . $itemPrice . '</p>
                                            </td>
                                            <td class="text-center">
                                                <form method="POST" action="partials/_menuManage.php">
                                                    <input type="hidden" name="itemId" value="' . $itemId . '">
                                                    <select name="status" class="form-control status-select" onchange="this.form.submit();">
                                                        <option value="available" ' . ($itemStatus == 'available' ? 'selected' : '') . '>Available</option>
                                                        <option value="unavailable" ' . ($itemStatus == 'unavailable' ? 'selected' : '') . '>Unavailable</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <button class="btn btn-sm btn-primary mr-2" type="button" data-toggle="modal" data-target="#updateItem' . $itemId . '">Edit</button>
                                                    <form action="partials/_menuManage.php" method="POST">
                                                        <input type="hidden" name="itemId" value="' . $itemId . '">
                                                        <button name="removeItem" class="btn btn-sm btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>
</div>

<?php
$itemsql = "SELECT * FROM `item`";
$itemResult = mysqli_query($conn, $itemsql);
while ($itemRow = mysqli_fetch_assoc($itemResult)) {
    $itemId = $itemRow['itemId'];
    $itemName = $itemRow['itemName'];
    $itemPrice = $itemRow['itemPrice'];
    $itemCategorieId = $itemRow['itemCategorieId'];
    $itemDesc = $itemRow['itemDesc'];
    ?>

    <!-- Modal -->
     
    <div class="modal fade" id="updateItem<?php echo $itemId; ?>" tabindex="-1" role="dialog"
        aria-labelledby="updateItem<?php echo $itemId; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(111 202 203);">
                    <h5 class="modal-title" id="updateItem<?php echo $itemId; ?>">Item Id: <?php echo $itemId; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="partials/_menuManage.php" method="post" enctype="multipart/form-data">
                        <div class="text-left my-2 row" style="border-bottom: 2px solid #dee2e6;">
                            <div class="form-group col-md-8">
                                <b><label for="image">Image</label></b>
                                <input type="file" name="itemimage" id="itemimage" accept=".jpg" class="form-control"
                                    required style="border:none;"
                                    onchange="document.getElementById('itemPhoto').src = window.URL.createObjectURL(this.files[0])">
                                <small id="Info" class="form-text text-muted mx-3">Please .jpg file upload.</small>
                                <input type="hidden" id="itemId" name="itemId" value="<?php echo $itemId; ?>">
                                <button type="submit" class="btn btn-success my-1" name="updateItemPhoto">Update
                                    Img</button>
                            </div>
                            <div class="form-group col-md-4">
                                <img src="/OnlinePizzaDelivery/img/pizza-<?php echo $itemId; ?>.jpg" id="itemPhoto"
                                    name="itemPhoto" alt="item image" width="100" height="100">
                            </div>
                        </div>
                    </form>
                    <form action="partials/_menuManage.php" method="post">
                        <div class="text-left my-2">
                            <b><label for="name">Name</label></b>
                            <input class="form-control" id="name" name="name" value="<?php echo $itemName; ?>" type="text"
                                required>
                        </div>
                        <div class="text-left my-2 row">
                            <div class="form-group col-md-6">
                                <b><label for="price">Price</label></b>
                                <input class="form-control" id="price" name="price" value="<?php echo $itemPrice; ?>"
                                    type="number" min="1" required>
                            </div>
                            <div class="form-group col-md-6">
                                <b><label for="catId">Category Id</label></b>
                                <input class="form-control" id="catId" name="catId" value="<?php echo $itemCategorieId; ?>"
                                    type="number" min="1" required>
                            </div>
                        </div>
                        <div class="text-left my-2">
                            <b><label for="desc">Description</label></b>
                            <textarea class="form-control" id="desc" name="desc" rows="2" required
                                minlength="6"><?php echo $itemDesc; ?></textarea>
                        </div>
                        <input type="hidden" id="itemId" name="itemId" value="<?php echo $itemId; ?>">
                        <button type="submit" class="btn btn-success" name="updateItem">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    &nbsp;
    <?php
}
?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const statusSelects = document.querySelectorAll(".status-select");

        statusSelects.forEach(select => {
            changeStatusColor(select); // Set initial color

            select.addEventListener("change", function () {
                changeStatusColor(this);
            });
        });
    });

    function changeStatusColor(select) {
        if (select.value === "available") {
            select.style.color = "green";
        } else {
            select.style.color = "red";
        }
    }
</script>