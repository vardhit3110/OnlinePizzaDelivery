<?php
require 'partials/_dbconnect.php';
@session_start();
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style type="text/css">
    .admin-card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .admin-card {
        width: 48%;
        text-align: center;
        background-color: #4723d9;
        color: #ffffff;
        padding: 20px;
        display: flex;
        align-items: center;
    }

    .admin-card-icon {
        margin-right: 20px;
        width: 35px;
        height: 35px;
    }

    .admin-card-icon .nav__icon {
        font-size: 35px;
    }

    .admin-card-text-container {
        text-align: left;
    }

    .chart-container {
        margin-top: 40px;
        text-align: center;
    }
</style>
<br>
<div class="container" style="margin-top:70px;max-width: 900px;">
    <h1 style="margin-bottom: 30px;">Welcome,
        <b>
            <?php
            echo isset($_SESSION['adminName']) ? $_SESSION['adminName'] : 'Admin';
            ?>!
        </b>
    </h1>
    <div class="admin-card-container">
        <?php
        $counts = [];
        $tables = ['users', 'categories', 'item', 'orders', 'delivery_boys', 'likes'];
        foreach ($tables as $table) {
            $existSql = "SELECT * FROM $table";
            $result = mysqli_query($conn, $existSql);
            $numExistRows = mysqli_num_rows($result);
            $counts[$table] = $numExistRows;
        }
        ?>
        <!-- Admin Cards -->
        
        <div class="admin-card">
            <div class="admin-card-icon">
                <i class="bx bx-user nav__icon"></i>
            </div>
            <div class="admin-card-text-container">
                <div class="admin-card-title">Total Users</div>
                <div class="admin-card-count"><?php echo $counts['users']; ?></div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-icon">
                <i class="bx bx-folder nav__icon"></i>
            </div>
            <div class="admin-card-text-container">
                <div class="admin-card-title">Total Categories</div>
                <div class="admin-card-count"><?php echo $counts['categories']; ?></div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-icon">
                <i class="bx bx-message-square-detail nav__icon"></i>
            </div>
            <div class="admin-card-text-container">
                <div class="admin-card-title">Total Items</div>
                <div class="admin-card-count"><?php echo $counts['item']; ?></div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-icon">
                <i class="bx bx-bar-chart-alt-2 nav__icon"></i>
            </div>
            <div class="admin-card-text-container">
                <div class="admin-card-title">Total Orders</div>
                <div class="admin-card-count"><?php echo $counts['orders']; ?></div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-icon">
                <i class="bx bx-run nav__icon"></i>
            </div>
            <div class="admin-card-text-container">
                <div class="admin-card-title">Total Delivery Boys</div>
                <div class="admin-card-count"><?php echo $counts['delivery_boys']; ?></div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-icon">
                <i class='bx bxr  bx-like nav__icon'></i>
            </div>
            <div class="admin-card-text-container">
                <div class="admin-card-title">Total Likes</div>
                <div class="admin-card-count"><?php echo $counts['likes']; ?></div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="chart-container">
        <canvas id="dashboardChart" width="400" height="200"></canvas>
    </div>

    <script>
        <?php
        // Find top 5 most sold items
        $topSoldQuery = "
    SELECT i.itemName, SUM(o.itemQuantity) AS totalSold 
    FROM orderitems o
    JOIN item i ON o.itemId = i.itemId
    GROUP BY o.itemId    ORDER BY totalSold DESC 
    LIMIT 5";
        $topSoldResult = mysqli_query($conn, $topSoldQuery);

        $itemNames = [];
        $itemCounts = [];

        while ($row = mysqli_fetch_assoc($topSoldResult)) {
            $itemNames[] = $row['itemName'];
            $itemCounts[] = $row['totalSold'];
        }

        // If there are less than 5 items sold, fill the remaining with zeros
        while (count($itemNames) < 5) {
            $itemNames[] = "N/A"; // Placeholder for empty items
            $itemCounts[] = 0; // Placeholder for count
        }
        ?>
        const ctx = document.getElementById('dashboardChart').getContext('2d');

        // Create a gradient color for the bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(255, 99, 132, 1)');
        gradient.addColorStop(0.5, 'rgba(54, 162, 235, 1)');
        gradient.addColorStop(1, 'rgba(75, 192, 192, 1)');

        const dashboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($itemNames); ?>, // Include top 5 items
                datasets: [{
                    label: 'Total Sold',
                    data: <?php echo json_encode($itemCounts); ?>, // Include counts for top 5 items
                    backgroundColor: gradient, // Use the gradient color
                    borderColor: gradient, // Black borders
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</div>