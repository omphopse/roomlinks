<?php

class ViewController
{
    public function show()
    {
        require_once __DIR__ . '/../models/Message.php';

        $limit = 20;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        $order = isset($_GET['order']) ? $_GET['order'] : 'latest';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        if ($search !== '') {
            $messages = Message::getFilteredMessages($limit, $offset, $order, $search);
            $totalMessages = Message::getFilteredCount($search);
        } else {
            $messages = Message::getMessagesByPageWithOrder($limit, $offset, $order);
            $totalMessages = Message::getTotalCount();
        }

        $totalPages = ceil($totalMessages / $limit);

        require_once __DIR__ . '/../views/views.php';
    }
}

?>
