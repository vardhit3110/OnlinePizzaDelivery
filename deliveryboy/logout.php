<?php
session_start();
session_destroy();
header("location: /OnlinePizzaDelivery/index.php");
exit();
?>
