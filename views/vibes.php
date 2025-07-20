<?php require_once __DIR__ . '/../config/constants.php'; ?>
<?php
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../controllers/UserController.php';
checkUserAuth();
$userController = new UserController();
$vibes = $userController->getVibes();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles/tailwind.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/hide-scrollbar.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />

    <title>RoomLinks | Offical Website</title>

    <meta name="description"
        content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta name="author" content="RoomLinks" />
    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="RoomLinks | Official Website" />
    <meta property="og:description"
        content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta property="og:image" content="assets/hero-section/description.png" />
    <meta property="og:url" content="https://roomlinks.in" />
    <meta property="og:type" content="website" />
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="RoomLinks | Official Website" />
    <meta name="twitter:description"
        content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta name="twitter:image" content="assets/hero-section/description.png" />
    <!-- Canonical URL -->
    <link rel="canonical" href="https://roomlinks.in" />

</head>

<body class="flex flex-col min-h-screen">
    <?php include 'partials/logo.php' ?>
    <div class="flex-grow flex items-center justify-center px-4 ">

        <!-- Vibes Start -->
        <div class="w-full max-w-5xl mx-auto bg-white rounded-xl shadow-lg p-4 sm:p-8 my-20 md:my-20 flex flex-col items-center mx-2 sm:mx-0">
            <h2 class="text-3xl font-bold mb-2 text-gray-800">Your Vibes</h2>
            <p class="mb-6 text-gray-500 text-center">Select at least 5 vibes that describe what kind of roommates you
                prefer. These will help us match you with your ideal vibeMates!</p>
            <form id="vibesForm" class="w-full" method="POST" action="<?php echo APP_URL; ?>/vibes">
                <input type="hidden" name="update_vibes" value="1" />
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-6 mb-8 justify-items-center">
                    <!-- Vibe Images Start -->
                    <?php foreach ($vibes as $vibe): ?>
                    <label class="flex flex-col items-center cursor-pointer group">
                        <input type="checkbox" name="vibes[]" value="<?= htmlspecialchars($vibe['id']) ?>" class="hidden peer" />
                        <span class="w-20 h-20 md:w-28 md:h-28 rounded-full bg-white shadow-md flex items-center justify-center mb-2 border-2 border-transparent peer-checked:border-blue-500 transition">
                            <img src="<?= htmlspecialchars($vibe['images']) ?>" alt="<?= htmlspecialchars($vibe['name']) ?>"
                                class="w-14 h-14 md:w-20 md:h-20 object-contain rounded-full" /> 
                        </span>
                        <span class="text-xs md:text-sm text-gray-700 group-hover:text-blue-600"><?= htmlspecialchars($vibe['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
               
                <div class="flex justify-center">
                    <div class="w-full flex flex-col items-center">
                        <div id="vibesSuccess" style="display:none;" class="w-full mb-2 p-3 rounded-lg font-semibold text-center bg-teal-100 text-teal-700 border border-teal-400"></div>
                        <div id="vibesError" style="display:none;" class="w-full mb-2 p-3 rounded-lg font-semibold text-center bg-red-100 text-red-700 border border-red-400"></div>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition disabled:opacity-50"
                            id="updateVibesBtn" name="update_vibes">Update Vibes</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Vibes End -->
    </div>

    <script>
    document.getElementById('vibesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = e.target;
        var formData = new FormData(form);
        var btn = document.getElementById('updateVibesBtn');
        btn.disabled = true;
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            var successDiv = document.getElementById('vibesSuccess');
            var errorDiv = document.getElementById('vibesError');
            successDiv.style.display = 'none';
            errorDiv.style.display = 'none';
            if (data.success) {
                successDiv.textContent = data.success;
                successDiv.style.display = '';
            } else if (data.error) {
                errorDiv.textContent = data.error;
                errorDiv.style.display = '';
            }
            if (data.redirect) {
                setTimeout(function() {
                    successDiv.style.display = 'none';
                    errorDiv.style.display = 'none';
                    window.location.href = data.redirect;
                }, 1500);
            }
        })
        .catch(() => {
            btn.disabled = false;
            var errorDiv = document.getElementById('vibesError');
            errorDiv.textContent = 'Failed to update vibes. Please try again.';
            errorDiv.style.display = '';
        });
    });
    </script>

    <?php include 'partials/footer.php' ?>
</body>

</html>