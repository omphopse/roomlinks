<?php
session_start();
require_once __DIR__ . '/../config/constants.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles/tailwind.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />

    <title>RoomLinks | Offical Website</title>
    <meta name="description" content="Contact RoomLinks for the best rooms and roommates in major and small cities across India. Find your vibeMate today!" />
    <meta name="keywords" content="contact, rooms, roommates, find room, rent, RoomLinks, vibeMate, rooms in Pune, rooms in Mumbai, rooms in Delhi, rooms in Bangalore, rooms in Hyderabad, rooms in Chennai, rooms in Kolkata, rooms in Ahmedabad, rooms in Jaipur, rooms in Lucknow, rooms in Indore, rooms in Surat, rooms in Bhopal, rooms in Patna, rooms in Nagpur, rooms in Chandigarh, rooms in Noida, rooms in Gurugram, rooms in Thane, rooms in Nashik, PG in Pune, PG in Mumbai, PG in Delhi, flatmates, shared accommodation, hostel, student rooms, working professionals, affordable rooms, single room, double sharing, triple sharing, girls hostel, boys hostel, co-living, rent a room, best rooms, cheap rooms, city rooms, local rooms, room search, room booking, room rental, roommate finder, Pune, Mumbai, Delhi, Bangalore, Hyderabad, Chennai, Kolkata, Ahmedabad, Jaipur, Lucknow, Indore, Surat, Bhopal, Patna, Nagpur, Chandigarh, Noida, Gurugram, Thane, Nashik" />
</head>

<body>
    <?php include 'partials/header.php' ?>

    <!-- Contact Section Wrapper -->
    <div class="max-w-full mx-8 my-10 lg:mx-auto py-8">
        <div class="flex flex-col lg:flex-row gap-8 items-stretch">
            <!-- Contact Cards -->
            <div class="flex flex-col gap-y-11 flex-1 h-full">
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center border-t-4 border-blue-500 h-full">
                    <span class="text-blue-600 text-3xl mb-2">
                        <!-- Sharp Email Icon using Font Awesome -->
                        <i class="fas fa-envelope"></i>
                    </span>
                    <h3 class="font-semibold text-lg mb-1">Email</h3>
                    <p class="text-gray-700 text-center text-sm"><a href="mailto:info@roomlinks.in"
                            target="_blank">info@roomlinks.in</a><br><a href="mailto:invest@roomlinks.in"
                            target="_blank">invest@roomlinks.in</a></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center border-t-4 border-blue-500 h-full">
                    <span class="text-blue-600 text-3xl mb-2">
                        <!-- Font Awesome Location Icon -->
                        <i class="fas fa-map-marker-alt"></i>
                    </span>
                    <h3 class="font-semibold text-lg mb-1">Address</h3>
                    <p class="text-gray-700 text-center text-sm"><a href="https://maps.app.goo.gl/AqTZEFTv1PqKkVcK9"
                            target="_blank">Ahilayanagar, Maharashtra</a></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center border-t-4 border-blue-500 h-full">
                    <span class="text-blue-600 text-3xl mb-2">
                        <!-- Font Awesome Cell Phone Icon -->
                        <i class="fas fa-mobile-alt"></i>
                    </span>
                    <h3 class="font-semibold text-lg mb-1">Phone</h3>
                    <p class="text-gray-700 text-center text-sm"><a href="tel:+919623908967" target="_blank">+91
                            9623908967</a></p>
                </div>
            </div>
            <!-- Form and Map -->
            <div class="flex flex-col gap-6 flex-[2] h-full">
                <!-- Contact Form -->
                <div class="bg-white rounded-lg shadow-md p-8 border-t-4 border-blue-500 flex flex-col justify-between h-full">
                    <?php
                    if (isset($_SESSION['success'])) {
                        echo '<p class="text-green-600 font-semibold mb-4">' . htmlspecialchars($_SESSION['success']) . '</p>';
                        unset($_SESSION['success']);
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<p class="text-red-600 font-semibold mb-4">' . htmlspecialchars($_SESSION['error']) . '</p>';
                        unset($_SESSION['error']);
                    }
                    ?>
                    <h2 class="text-2xl font-bold text-blue-600 mb-6">Contact Us</h2>

                    <form action="<?php echo APP_URL; ?>/contact/submit" method="POST"
                        class="space-y-5 flex flex-col h-full">
                        <div class="flex flex-col md:flex-row gap-5">
                            <div class="flex-1">
                                <label for="name" class="block text-gray-700 font-semibold mb-1">Name</label>
                                <input type="text" id="name" name="name" placeholder="Enter your full name" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                            </div>
                            <div class="flex-1">
                                <label for="number" class="block text-gray-700 font-semibold mb-1">Phone Number</label>
                                <input type="tel" id="number" name="number" placeholder="xxx-xxxx-xxx" required
                                    pattern="[0-9]{10}" maxlength="10"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-5">
                            <div class="flex-1">
                                <label for="email" class="block text-gray-700 font-semibold mb-1">Email</label>
                                <input type="email" id="email" name="email" required autocomplete="email"
                                    placeholder="Enter your email"
                                    pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+.[a-zA-Z]{2,}$"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                            </div>
                            <div class="flex-1">
                                <label for="subject" class="block text-gray-700 font-semibold mb-1">Subject</label>
                                <select id="subject" name="subject" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <option value="invest">Invest in us</option>
                                    <option value="feature">Feature suggestion</option>
                                    <option value="problem">Problem experienced</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col">
                            <label for="message" class="block text-gray-700 font-semibold mb-1">Message</label>
                            <textarea id="message" name="message" rows="5" maxlength="2000" required
                                placeholder="Write your message here..."
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 flex-1"
                                oninput="updateWordCount(this)"></textarea>
                            <div class="text-right text-xs text-gray-500 mt-1"><span id="wordCount">0</span>/200 words
                            </div>
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-200">Send
                            Message</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Map -->
        <div class="w-full h-56 md:h-64 lg:h-60 rounded-lg shadow-md border-2 border-blue-200 mt-10">
            <iframe src="https://www.google.com/maps?q=Ahilayanagar,+Maharashtra&output=embed" width="100%"
                height="100%" class="w-full h-full" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>



    <script>
        function updateWordCount(textarea) {
            const words = textarea.value.trim().split(/\s+/).filter(Boolean);
            const wordCount = words.length;
            document.getElementById('wordCount').textContent = wordCount;
            if (wordCount > 200) {
                textarea.value = words.slice(0, 200).join(' ');
                document.getElementById('wordCount').textContent = 200;
            }
        }
    </script>

    <?php include 'partials/footer.php' ?>
</body>

</html>