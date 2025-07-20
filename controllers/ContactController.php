<?php
require_once __DIR__ . '/../models/ContactUs.php';
require_once __DIR__ . '/../config/database.php';

class ContactController
{
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name'    => trim($_POST['name']),
                'email'   => trim($_POST['email']),
                'phone'   => trim($_POST['number']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
            ];

            $db = getDatabaseConnection();
            $contactModel = new ContactUs($db);
            session_start();
            if ($contactModel->save($data)) {
                $_SESSION['success'] = 'Your message has been sent successfully.';
                $db->close();
                header('Location:'. APP_URL .'/contact');
                exit;
            } else {
                $_SESSION['error'] = 'There was an error sending your message. Please try again.';
                $db->close();
                header('Location:'. APP_URL .'/contact');
                exit;
            }
        }
    }
}
