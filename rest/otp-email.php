<?php
require "../utils/otp.php";

header("Content-Type: application/json; charset=utf-8");

$POST = json_decode(file_get_contents("php://input"), true);

switch($_SERVER["REQUEST_METHOD"]) {
    case "GET": {
        $key = key_exists("key", $_GET) ? $_GET["key"] : "";
        $value = key_exists("value", $_GET) ? $_GET["value"] : "";
        $key = strtolower($key);
        $value = strtolower($value);
        $output = null;

        if ($key && $value) {
            $output = getOtpUser($key, $value);
        }

        echo json_encode($output);
        break;
    }
    case "POST": {
        $email = key_exists("email", $POST) ? $POST["email"] : "";
        $otp = key_exists("otp", $POST) ? $POST["otp"] : "";
        $expire = key_exists("expire", $POST) ? $POST["expire"] : "";
        $date = key_exists("date", $POST) ? $POST["date"] : "";

        $emailErrors = getOtpEmailErrors($email);
        $output = [
            "success" => !count($emailErrors),
            "error" => count($emailErrors),
            "errorMessages" => $emailErrors
        ];

        if ($output["success"]) {
            saveOtpUser($email, $otp, $expire, $date);
        }

        echo json_encode($output);
        break;
    }
    default: {
        echo json_encode(["error" => true]);
    }
}
?>