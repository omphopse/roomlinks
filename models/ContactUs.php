<?php
// models/ContactUs.php
class ContactUs
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function save($data)
    {
        $stmt = $this->db->prepare("INSERT INTO contact_us (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssss",
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['subject'],
            $data['message']
        );
        return $stmt->execute();
    }
}
