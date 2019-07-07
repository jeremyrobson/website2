<?php
    $alerts = &$_SESSION["alerts"] ?? array();

    while ($alert = array_shift($alerts)) {
        require(TEMPLATE_DIR . "/alert.php");
    }
?>