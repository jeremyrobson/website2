<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
if (isset($_POST["message_submitted"])) {
    $email_from = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
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
    $mail->setFrom($config["site"]["contact_email"], "Contact");
    $mail->addAddress($config["site"]["admin_email"], 'Jeremy Robson');
    $mail->addReplyTo($email_from, 'noreply');
    $mail->isHTML(false);
    $mail->Subject = $subject;
    $mail->Body = $message;
print_r($mail); die;
    try {
        $mail->send();
        $alert = array(
            "type" => "success",
            "title" => "Success!",
            "message" => "Your message has been sent successfully"
        );
    } catch (Exception $e) {
        $alert = array(
            "type" => "danger",
            "title" => "Message could not be sent!",
            "message" => 'Mailer Error: ' . $mail->ErrorInfo
        );
    }
    $_SESSION["alerts"][] = $alert;
    $url = $config["site"]["base_url"] . "/contact";
    header("Location: $url");
    die();
}
?>

<?php if (isset($alert)): include(TEMPLATE_DIR . "/alert.php"); endif; ?>

<h1>Contact Me</h1>

<form class="py-5" action="contact" method="post">
    <input type="hidden" name="page" value="contact" />
    <input type="hidden" name="message_submitted" value="1" />
    <div class="form-group">
        <label for="exampleInputEmail1">Your email address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" maxlength="255" required value="test@test.com"/>
    </div>
    <div class="form-group">
        <label for="message">Your message to me</label>
        <textarea class="form-control" id="message" name="message" cols="80" rows="8" maxlength="1000" required>test</textarea>
    </div>

    <input type="submit" class="btn btn-primary float-right" value="Send 💌" />
    <div class="clearfix"></div>
</form>