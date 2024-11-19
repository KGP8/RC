<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // reCAPTCHA Secret Key
    $secret_key = "6LcCqnUqAAAAALjH8oYqertD2bu0Jmc3HPWEm_1L"; // Replace with your actual secret key

    // Verify reCAPTCHA response
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha_response");
    $response_keys = json_decode($response, true);

    if (intval($response_keys["success"]) !== 1) {
        echo "CAPTCHA verification failed. Please try again.";
        exit;
    }

    // Proceed with form processing if CAPTCHA is successful
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = trim($_POST["message"]);

    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please complete the form and try again.";
        exit;
    }

    // Email recipient
    $recipient = "hr@rcsquaretech.com"; // Replace with your email

    // Email subject
    $email_subject = "New contact from $name";

    // Email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Subject: $subject\n\n";
    $email_content .= "Message:\n$message\n";

    // Email headers
    $email_headers = "From: $name <$email>";

    if (mail($recipient, $email_subject, $email_content, $email_headers)) {
        echo "Thank You! Your message has been sent.";
        echo "<script>
                setTimeout(function(){
                    window.location.href = 'index.html';
                }, 3000); // Redirect after 3 seconds
              </script>";
        exit;
    } else {
        http_response_code(500);
        echo "Oops! Something went wrong, and we couldn't send your message.";
    }
} else {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>
