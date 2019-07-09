<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$input = array();

if (empty($_SESSION["input"])) {
    $input = array(
        "email" => filter_var(@$_POST["email"], FILTER_SANITIZE_EMAIL) ?? "",
        "message" => filter_var(@$_POST["message"], FILTER_SANITIZE_SPECIAL_CHARS) ?? "",
    );
}
else {
    $input = $_SESSION["input"];
}

if (isset($_POST["message_submitted"])) {
    $filename = "https://www.google.com/recaptcha/api/siteverify?secret=".RECAPTCHA_SECRET_KEY."&response=".$_POST["g-recaptcha-response"];
    $verifyResponse = file_get_contents($filename);
    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        $error = implode(", ", $responseData->{"error-codes"});
        $alert = array(
            "type" => "danger",
            "title" => "Nice try robot dude!",
            "message" => "Recaptcha Error: $error"
        );
        $_SESSION["alerts"][] = $alert;
        $_SESSION["input"] = $input;
        $redirect = BASE_URL . "/contact";
    }
    else {
        $email_from = $input["email"];
        $subject = "New message submitted on jeremyrobson.com";
        $message = filter_var($_POST["message"], FILTER_SANITIZE_SPECIAL_CHARS);
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = $config["mail"]["debug"];
        $mail->isSMTP();
        $mail->Host = $config["mail"]["host"];
        $mail->SMTPAuth = $config["mail"]["auth"];
        $mail->Username = $config["mail"]["username"];
        $mail->Password = $config["mail"]["password"];
        $mail->SMTPSecure = $config["mail"]["secure"];
        $mail->Port = $config["mail"]["port"];
        $mail->setFrom($config["site"]["contact_email"], $email_from);
        //$mail->setFrom($email_from, $email_from);
        $mail->addAddress($config["site"]["admin_email"], 'Jeremy Robson');
        $mail->addReplyTo($email_from, 'noreply');
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $message;

        try {
            $mail->send();
            $alert = array(
                "type" => "success",
                "title" => "Success!",
                "message" => "Thanks for contacting me. I will get back to you shortly!"
            );
            $redirect = BASE_URL . "/home";
        } catch (Exception $e) {
            $alert = array(
                "type" => "danger",
                "title" => "Message could not be sent!",
                "message" => 'Mailer Error: ' . $mail->ErrorInfo
            );
            $redirect = BASE_URL . "/contact";
        }

        $_SESSION["alerts"][] = $alert;
        $_SESSION["input"] = $input;
    }

    header("Location: $redirect");
    die();

}
?>

<h1>Contact Me</h1>

<form class="pt-5" action="contact" method="post" onsubmit="submit_button.disabled = true; return true;">
    <input type="hidden" name="page" value="contact" />
    <input type="hidden" name="message_submitted" value="1" />
    
    <div class="form-group">
        <label for="exampleInputEmail1">Your email address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" maxlength="255" required value="<?=$input["email"]?>"/>
    </div>
    <div class="form-group">
        <label for="message">Your message to me</label>
        <textarea class="form-control" id="message" name="message" cols="80" rows="8" maxlength="1000" required><?=$input["message"]?></textarea>
    </div>

    <div style="text-align: right;">
        <div>
            <div class="g-recaptcha pb-3" style="display: inline-block;" data-theme="dark" data-sitekey="<?=RECAPTCHA_SITE_KEY?>"></div>
        </div>
    </div>

    <input type="submit" id="submit_button" class="btn btn-primary float-right" value="Send 💌" />
    <div class="clearfix"></div>
</form>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>