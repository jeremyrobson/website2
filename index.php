<?php
$config = parse_ini_file("config.ini", true);

if ($config["site"]["debug"]) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

define("BASE_URL", $config["site"]["base_url"]);
define("ROOT_DIR", __DIR__);
define("BLOG_DIR", ROOT_DIR . "/" . $config["site"]["blog_dir"]);
define("TEMPLATE_DIR", ROOT_DIR . "/" . $config["site"]["template_dir"]);
define("RECAPTCHA_SITE_KEY", $config["site"]["recaptcha_site_key"]);
define("RECAPTCHA_SECRET_KEY", $config["site"]["recaptcha_secret_key"]);

require_once("src/classes/Blog.php");

session_start();

?>

<!doctype html>
<html lang="en">

<head>
    <title><?=$config["site"]["title"]?></title>
    <meta name="description" content="<?=$config["site"]["description"]?>">
    <meta name="author" content="<?=$config["site"]["author"]?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
    <link href="https://fonts.googleapis.com/css?family=Audiowide&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Inconsolata&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet" type="text/css">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>

<body>

<?php require_once("src/blocks/navbar.php"); ?>

<div class="container mt-5">

<?php require_once("src/blocks/alert.php"); ?>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page = $_POST["page"];
}
else {
    $page = !empty($_GET["p"]) ? $_GET["p"] : "home";
}

if (!empty($page)) {
    $path = ROOT_DIR . "/src/pages/$page.php";
    $post_path = ROOT_DIR . "/src/posts/$page.php";
    if (file_exists($path)) {
        include_once($path);
    }
    else if (file_exists($post_path)) {
        include_once(ROOT_DIR . "/src/pages/blog.php");
    }
    else {
        header('HTTP/1.0 404 Not Found');
        header("Location: /error");
        die;
    }
}

?>

<?php require_once("src/blocks/blog_links.php"); ?>

</div>

<?php require_once("src/blocks/footer.php"); ?>

</body>

</html>