<?php
    $alerts = &$_SESSION["alerts"] ?? array();

    while (!empty($alerts) && $alert = array_shift($alerts)) {
        require(TEMPLATE_DIR . "/alert.php");
    }
?>