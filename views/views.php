<?php require_once __DIR__ . '/../config/constants.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles/tailwind.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <title title="rooms, roommates, find room, rent, RoomLinks, vibeMate, rooms in Pune, rooms in Mumbai, rooms in Delhi, rooms in Bangalore, rooms in Hyderabad, rooms in Chennai, rooms in Kolkata, rooms in Ahmedabad, rooms in Jaipur, rooms in Lucknow, rooms in Indore, rooms in Surat, rooms in Bhopal, rooms in Patna, rooms in Nagpur, rooms in Chandigarh, rooms in Noida, rooms in Gurugram, rooms in Thane, rooms in Nashik">RoomLinks | Offical Website</title>
    <meta name="description" content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta name="keywords" content="rooms, roommates, find room, rent, RoomLinks, vibeMate, rooms in Pune, rooms in Mumbai, rooms in Delhi, rooms in Bangalore, rooms in Hyderabad, rooms in Chennai, rooms in Kolkata, rooms in Ahmedabad, rooms in Jaipur, rooms in Lucknow, rooms in Indore, rooms in Surat, rooms in Bhopal, rooms in Patna, rooms in Nagpur, rooms in Chandigarh, rooms in Noida, rooms in Gurugram, rooms in Thane, rooms in Nashik, rooms in small cities, PG in Pune, PG in Mumbai, PG in Delhi, flatmates, shared accommodation, hostel, student rooms, working professionals, affordable rooms, single room, double sharing, triple sharing, girls hostel, boys hostel, co-living, rent a room, best rooms, cheap rooms, city rooms, local rooms, room search, room booking, room rental, roommate finder, Pune, Mumbai, Delhi, Bangalore, Hyderabad, Chennai, Kolkata, Ahmedabad, Jaipur, Lucknow, Indore, Surat, Bhopal, Patna, Nagpur, Chandigarh, Noida, Gurugram, Thane, Nashik" />
</head>
<body>
    <?php include 'partials/header.php'?>

    <main class="p-6 mt-5 flex flex-col items-center space-y-6">
        <form id="filterForm" method="GET" action="" class="hidden md:flex flex-col md:flex-row  max-w-9xl items-start space-x-4 mb-3 gap-5">
            <div class="flex items-center gap-3 mb-3">
                <label for="order" class="text-gray-700 font-semibold">Order by:</label>
                <select id="order" name="order" class="flex-grow px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="a-z" <?php echo (isset($_GET['order']) && $_GET['order'] === 'a-z') ? 'selected' : ''; ?>>A to Z</option>
                    <option value="z-a" <?php echo (isset($_GET['order']) && $_GET['order'] === 'z-a') ? 'selected' : ''; ?>>Z to A</option>
                    <option value="latest" <?php echo (!isset($_GET['order']) || $_GET['order'] === 'latest') ? 'selected' : ''; ?>>Latest</option>
                    <option value="earliest" <?php echo (isset($_GET['order']) && $_GET['order'] === 'earliest') ? 'selected' : ''; ?>>Earliest</option>
                </select>
            </div>
            <input type="text" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" placeholder="Search..." class="flex-grow px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <button type="submit" class="bg-[--primary-color] text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Filter</button>
        </form>
        <button id="showFilterBtn" class="md:hidden bg-[--primary-color] text-white px-4 py-2 rounded-md hover:bg-blue-700 transition mb-6">Show Filter</button>
        <div class="flex flex-wrap justify-center w-full md:max-w-6xl max-w-9xl gap-5">

            <?php if (!empty($messages)) : ?>
                <?php foreach ($messages as $message) : ?>
                    <div class="card w-full md:w-1/3 lg:w-[18%] p-2">
                        <div class="card-header flex items-center justify-between px-4">
                            <div class="flex space-x-2">
                                <div class="circle red"></div>
                                <div class="circle yellow"></div>
                                <div class="circle green"></div>
                            </div>
                            <div class="username font-thin text-white text-lg text-ellipsis">
                                @<?php echo htmlspecialchars($message['uname']); ?>
                            </div>
                        </div>
                        <div class="card-content px-4 pt-2 pb-6 overflow-y-auto">
                            <div class="message"><?php echo htmlspecialchars($message['massage']); ?></div>
                            <div class="date mt-4">
                                <?php echo htmlspecialchars($message['created_at']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="text-center text-gray-500">No messages found.</p>
            <?php endif; ?>
        </div>

        <script>
            const showFilterBtn = document.getElementById('showFilterBtn');
            const filterForm = document.getElementById('filterForm');

            showFilterBtn.addEventListener('click', () => {
                if (filterForm.classList.contains('hidden')) {
                    filterForm.classList.remove('hidden');
                } else {
                    filterForm.classList.add('hidden');
                }
            });
        </script>

        <?php if (isset($totalPages) && $totalPages > 1) : ?>
            <nav class="pagination flex justify-center mt-6 space-x-2">
                <?php
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($currentPage < 1) {
                    $currentPage = 1;
                }
                ?>

                <a href="?page=<?php echo max(1, $currentPage - 1); ?>"
                   class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-200 flex items-center justify-center w-8 h-8 <?php echo ($currentPage == 1) ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                    &#8592;
                </a>

                <?php
                $maxPagesToShow = 3;
                $startPage = max(1, $currentPage - 1);
                $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

                if ($endPage - $startPage + 1 < $maxPagesToShow) {
                    $startPage = max(1, $endPage - $maxPagesToShow + 1);
                }

                for ($i = $startPage; $i <= $endPage; $i++) : ?>
                    <a href="?page=<?php echo $i; ?>"
                       class="px-3 py-1 rounded border border-gray-300 hover:bg-blue-600 hover:text-white <?php echo ($i == $currentPage) ? 'bg-blue-600 text-white' : ''; ?> w-8 h-8 flex items-center justify-center">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>"
                   class="px-3 py-1 rounded border border-gray-300 hover:bg-gray-200 flex items-center justify-center w-8 h-8 <?php echo ($currentPage == $totalPages) ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                    &#8594;
                </a>
            </nav>
        <?php endif; ?>
    </main>

    <?php include 'partials/footer.php'?>
</body>
</html>
