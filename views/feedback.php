<?php require_once __DIR__ . '/../config/constants.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles/tailwind.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/hide-scrollbar.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />

    <title>Feedback | RoomLinks | Official Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />
</head>

<body class="flex flex-col min-h-screen ">

    <?php include 'partials/header.php' ?>

    <div class="flex-grow flex items-center justify-center px-4 ">

        <!-- Feedback Form Start -->
        <form class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 flex flex-col gap-6" method="post" action="">
            <h2 class="text-2xl font-bold text-center mb-2">Feedback</h2>

            <?php
            if (session_status() === PHP_SESSION_NONE)
                session_start();
            if (!empty($_SESSION['success'])) {
                echo '<div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-center">' . htmlspecialchars($_SESSION['success']) . '</div>';
                unset($_SESSION['success']);
            }
            if (!empty($_SESSION['error'])) {
                echo '<div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-center">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <div>
                <label for="subject" class="block text-sm font-medium mb-1">Subject</label>
                <select id="subject" name="subject"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="" disabled selected>Select a subject</option>
                    <option value="General">General</option>
                    <option value="Website Issue">Website Issue</option>
                    <option value="Roommate Experience">Roommate Experience</option>
                    <option value="Suggestions">Suggestions</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div>
                <label for="feedback" class="block text-sm font-medium mb-1">Feedback (max 200 words)</label>
                <textarea id="feedback" name="feedback" rows="6" maxlength="2000"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    placeholder="Enter your feedback here..." required oninput="limitWords(this, 200, 20)"></textarea>
                <div class="text-xs text-gray-500 mt-1" id="wordCount">0 / 200 words</div>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded hover:bg-blue-700 transition">
                Submit Feedback
            </button>
        </form>
        <!-- Feedback Form End -->
    </div>

    <script>
        function limitWords(textarea, maxWords, minWords = 0) {
            const words = textarea.value.trim().split(/\s+/).filter(Boolean);
            if (words.length > maxWords) {
                textarea.value = words.slice(0, maxWords).join(" ");
            }
            const wordCount = textarea.value.trim().split(/\s+/).filter(Boolean).length;
            document.getElementById('wordCount').textContent = `${wordCount} / ${maxWords} words`;
            if (minWords && wordCount > 0 && wordCount < minWords) {
                document.getElementById('wordCount').classList.add('text-red-500');
            } else {
                document.getElementById('wordCount').classList.remove('text-red-500');
            }
        }
        // Optional: Prevent submit if less than 20 words
        document.querySelector('form').addEventListener('submit', function (e) {
            const textarea = document.getElementById('feedback');
            const words = textarea.value.trim().split(/\s+/).filter(Boolean);
            if (words.length < 20) {
                alert('Feedback must be at least 20 words.');
                textarea.focus();
                e.preventDefault();
            }
        });
    </script>

    <?php include 'partials/footer.php' ?>

</body>

</html>