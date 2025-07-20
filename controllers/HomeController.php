<?php

require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../config/database.php';

class HomeController
{

    public function index()
    {
        include __DIR__ . '/../views/index.php';
    }

    public function submitMessage()
    {
        session_start();

        $username = trim($_POST['username'] ?? '');
        $messageText = trim($_POST['message'] ?? '');

        if (empty($username) || empty($messageText)) {
            $_SESSION['error'] = "Username and message cannot be empty.";
            header("Location:" . APP_URL . "/home");
            exit();
        }

        

        $date = date('Y-m-d H:i:s');

        $message = new Message($username, $messageText, $date);

        if ($message->save()) {
            $_SESSION['success'] = "Message submitted successfully.";
        } else {
            $_SESSION['error'] = "Failed to submit message.";
        }

        header("Location: " . APP_URL . "/view");
        exit();
    }
}
