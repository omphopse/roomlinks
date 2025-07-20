<?php
// models/Message.php
require_once __DIR__ . '/../config/database.php';

class Message
{
    private $username;
    private $message;
    private $date;

    public function __construct($username, $message, $date)
    {
        $this->username = $username;
        $this->message = $message;
        $this->date = $date;
    }


    public function save()
    {
        $conn = getDatabaseConnection();

        $stmt = $conn->prepare("INSERT INTO massage (uname, massage, created_at) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sss", $this->username, $this->message, $this->date);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return true;
        } else {
            $error = $stmt->error;
            $stmt->close();
            $conn->close();
            die("Execute failed: " . $error);
        }
    }

    public static function getAll()
    {
        $conn = getDatabaseConnection();
        $sql = "SELECT uname, massage, created_at FROM massage ORDER BY created_at DESC";
        $result = $conn->query($sql);

        $messages = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $messages[] = $row;
            }
        }
        $conn->close();
        return $messages;
    }

    public static function getMessagesByPage($limit, $offset)
    {
        $conn = getDatabaseConnection();
        $sql = "SELECT uname, massage, created_at FROM massage ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $messages;
    }

    public static function getMessagesByPageWithOrder($limit, $offset, $order)
    {
        $conn = getDatabaseConnection();

        $orderBy = "created_at DESC";
        if ($order === 'a-z') {
            $orderBy = "uname ASC";
        } elseif ($order === 'z-a') {
            $orderBy = "uname DESC";
        } elseif ($order === 'latest') {
            $orderBy = "created_at DESC";
        } elseif ($order === 'earliest') {
            $orderBy = "created_at ASC";
        }

        $sql = "SELECT uname, massage, created_at FROM massage ORDER BY $orderBy LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $messages;
    }

    public static function getFilteredMessages($limit, $offset, $order, $search)
    {
        $conn = getDatabaseConnection();

        $orderBy = "created_at DESC";
        if ($order === 'a-z') {
            $orderBy = "uname ASC";
        } elseif ($order === 'z-a') {
            $orderBy = "uname DESC";
        } elseif ($order === 'latest') {
            $orderBy = "created_at DESC";
        } elseif ($order === 'earliest') {
            $orderBy = "created_at ASC";
        }

        $searchParam = '%' . $conn->real_escape_string($search) . '%';

        $sql = "SELECT uname, massage, created_at FROM massage WHERE uname LIKE ? OR massage LIKE ? ORDER BY $orderBy LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssii", $searchParam, $searchParam, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
        $conn->close();
        return $messages;
    }

    public static function getFilteredCount($search)
    {
        $conn = getDatabaseConnection();

        $searchParam = '%' . $conn->real_escape_string($search) . '%';

        $sql = "SELECT COUNT(*) as total FROM massage WHERE uname LIKE ? OR massage LIKE ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ss", $searchParam, $searchParam);
        $stmt->execute();
        $result = $stmt->get_result();

        $total = 0;
        if ($result) {
            $row = $result->fetch_assoc();
            $total = (int)$row['total'];
        }
        $stmt->close();
        $conn->close();
        return $total;
    }

    public static function getTotalCount()
    {
        $conn = getDatabaseConnection();
        $sql = "SELECT COUNT(*) as total FROM massage";
        $result = $conn->query($sql);
        $total = 0;
        if ($result) {
            $row = $result->fetch_assoc();
            $total = (int)$row['total'];
        }
        $conn->close();
        return $total;
    }
}
