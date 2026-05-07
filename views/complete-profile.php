<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../models/City.php';
require_once __DIR__ . '/../models/ProfilePicture.php';
require_once __DIR__ . '/../config/database.php';


$pdo = getPDO();
$locationModel = new City($pdo);
$locations = $locationModel->getAllCities();
$profilePictureModel = new ProfilePicture();
$allAvatars = $profilePictureModel->getProfilePictures();
$maleAvatars = array_filter($allAvatars, function ($pic) {
    return $pic['gender'] === 'men';
});
$femaleAvatars = array_filter($allAvatars, function ($pic) {
    return $pic['gender'] === 'women';
});
// Randomly select up to 4 avatars for each gender
$maleAvatars = UserController::getRandomAvatars(array_values($maleAvatars), 4);
$femaleAvatars = UserController::getRandomAvatars(array_values($femaleAvatars), 4);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    <title>Complete Profile | RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />

    <style>
        #avatar_suggestions label {
            cursor: pointer;
            display: inline-block;
            outline: none;
        }

        #avatar_suggestions input[type="radio"] {
            position: absolute;
            left: -9999px;
            opacity: 0;
        }

        /* Debug: Ensure avatar selection border is visible */
        .avatar-img.border-blue-400 {
            border-color: #2563eb !important;
            /* Tailwind blue-400 */
            box-shadow: 0 0 0 2px #2563eb;
            z-index: 2;
        }

        .avatar-img {
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            /* Add shadow behind avatars */
        }
    </style>
</head>

<body class="flex flex-col min-h-screen ">
    <?php include 'partials/logo.php' ?>
    <div class="flex-grow flex items-center justify-center px-4 mt-8 mb-8">
        <!-- Profile Start -->
        <div class="max-w-2xl w-full bg-white rounded-lg shadow-lg p-10">
            <h2 class="text-2xl font-bold mb-2 text-center">You are almost done</h2>
            <p class="text-gray-600 mb-6 text-center">Please complete your profile to get the best experience and find
                your perfect match!</p>
            <form id="profileForm" enctype="multipart/form-data" method="post" class="space-y-6">
                <div id="profileSuccess" class="alert alert-success bg-green-100 text-green-800 px-4 py-2 rounded mb-2 mt-2"
                    style="display:none;"></div>
                <div id="profileError" class="alert alert-danger bg-red-100 text-red-800 px-4 py-2 rounded mb-2 mt-2"
                    style="display:none;"></div>
                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
                    <label class="font-bold text-gray-700 mb-1 md:mb-0 md:w-48" for="name">Enter your name</label>
                    <input type="text" id="name" name="name"
                        class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full"
                        placeholder="Enter your full name" required />
                </div>
                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
                    <label class="font-bold text-gray-700 mb-1 md:mb-0 md:w-48" for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" maxlength="10" minlength="10"
                        class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full"
                        placeholder="Enter your 10-digit phone number"
                        title="Please enter a 10-digit phone number (digits only)" required />
                </div>

                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
                    <label class="font-bold text-gray-700 mb-1 md:mb-0 md:w-48" for="who">Who are you?</label>
                    <select id="who" name="who"
                        class="flex-1 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full"
                        required>
                        <option value="">Select an option</option>
                        <option value="roommate">Looking for room/roommate/PG</option>
                        <option value="flat_owner">Flat Owner</option>
                        <option value="pg_owner">PG Owner</option>
                        <option value="broker">Broker</option>
                    </select>
                </div>
                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
                    <label class="font-bold text-gray-700 mb-1 md:mb-0 md:w-48">Enter your gender</label>
                    <div class="flex gap-2 flex-1 w-full">
                        <button type="button"
                            class="gender-btn px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white w-full md:w-auto"
                            data-value="male">Male</button>
                        <button type="button"
                            class="gender-btn px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white w-full md:w-auto"
                            data-value="female">Female</button>
                        <button type="button"
                            class="gender-btn px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-blue-500 hover:text-white w-full md:w-auto"
                            data-value="other">Other</button>
                    </div>
                    <input type="hidden" name="gender" id="gender_input" required />
                </div>
                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
                    <label class="font-bold text-gray-700 mb-1 md:mb-0 md:w-48" for="location">Please select your
                        location</label>
                    <div class="flex-1 w-full">
                        <select id="location" name="location"
                            class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full"
                            required>
                            <option value="">Select location</option>
                            <?php if (!empty($locations)): ?>
                                <?php foreach ($locations as $loc): ?>
                                    <option value="<?= htmlspecialchars($loc['id']) ?>"><?= htmlspecialchars($loc['location']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <option value="other">Other</option>
                        </select>
                        <input type="text" id="custom_location" name="custom_location" placeholder="Enter your location" class="mt-2 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 w-full hidden" />
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-start md:items-center gap-2 md:gap-4">
                    <label class="font-bold text-gray-700 mb-2 md:mb-0 md:w-64 w-full" for="profile_picture">Upload
                        Image/Select Avatar</label>
                    <div class="flex-1 w-full">
                        <div id="profile_image_preview_container"
                            class="relative flex justify-center items-center mb-4 hidden w-full">
                            <img id="profile_image_preview" class="w-32 h-32 rounded-full object-cover mx-auto" src="#"
                                alt="Profile Preview" />
                            <button type="button" id="remove_profile_image"
                                class="absolute top-0 right-1 bg-gray-800 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs focus:outline-none">&times;</button>
                        </div>
                        <div id="upload_box"
                            class="border-2 border-gray-300 rounded-lg flex flex-col items-center justify-center p-6 mb-4 bg-white cursor-pointer hover:border-blue-400 transition w-full max-w-xs mx-auto"
                            onclick="document.getElementById('profile_picture_input').click()">
                            <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                            <span class="text-gray-500 text-center">Click or Drop to upload profile image (jpg or
                                png)</span>
                            <input type="file" id="profile_picture_input" name="profile_picture" accept="image/*"
                                class="hidden" />
                        </div>
                        <div id="or_div" class="text-center text-gray-400 my-2">-- OR --</div>
                        <div id="avatar_suggestions" class="flex justify-center gap-4 mt-2 w-full">
                            <div id="male_avatars" class="flex gap-4">
                                <?php foreach ($maleAvatars as $avatar): ?>
                                    <img src="<?= htmlspecialchars($avatar['image']) ?>"
                                        data-value="<?= htmlspecialchars($avatar['image']) ?>" alt="Male Avatar"
                                        class="avatar-img w-16 h-16 rounded-full border-2 border-transparent hover:border-blue-400 cursor-pointer transition" />
                                <?php endforeach; ?>
                            </div>
                            <div id="female_avatars" class="flex gap-4" style="display:none;">
                                <?php foreach ($femaleAvatars as $avatar): ?>
                                    <img src="<?= htmlspecialchars($avatar['image']) ?>"
                                        data-value="<?= htmlspecialchars($avatar['image']) ?>" alt="Female Avatar"
                                        class="avatar-img w-16 h-16 rounded-full border-2 border-transparent hover:border-blue-400 cursor-pointer transition" />
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <input type="hidden" name="avatar" id="avatar_input" />
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Complete
                    Profile</button>
            </form>
        </div>
        <div id="cropperModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-xl relative mx-4 md:mx-0" style="margin: 32px;">
                <button id="closeCropperModal"
                    class="absolute top-2 right-2 text-2xl text-gray-400 hover:text-gray-700">&times;</button>
                <h2 class="text-xl font-bold text-center mb-4">Select Your Profile Picture</h2>
                <div class="flex justify-center items-center mb-4">
                    <div class="w-64 h-64 bg-gray-100 flex items-center justify-center overflow-hidden rounded-full">
                        <img id="cropperImage" src="#" class="max-w-full max-h-full" style="display:none;" />
                    </div>
                </div>
                <div class="flex flex-col items-center mb-4">
                    <label class="font-semibold mb-1">Zoom</label>
                    <input id="cropperZoom" type="range" min="0.1" max="3" step="0.01" value="1" class="w-2/3" />
                </div>
                <button id="setProfilePicture" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Set
                    as Profile Picture</button>
            </div>
        </div>

        <script>
            // Show custom location input if 'Other' is selected
            document.addEventListener('DOMContentLoaded', function () {
                var locationSelect = document.getElementById('location');
                var customLocation = document.getElementById('custom_location');
                if (locationSelect && customLocation) {
                    locationSelect.addEventListener('change', function () {
                        if (this.value === 'other') {
                            customLocation.classList.remove('hidden');
                            customLocation.required = true;
                        } else {
                            customLocation.classList.add('hidden');
                            customLocation.required = false;
                            customLocation.value = '';
                        }
                    });
                }
            });
            // Button selection logic for Gender
            document.querySelectorAll('.gender-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.gender-btn').forEach(b => {
                        b.classList.remove('bg-blue-600', 'text-white');
                        b.classList.add('bg-gray-200', 'text-gray-700');
                    });
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    this.classList.add('bg-blue-600', 'text-white');
                    document.getElementById('gender_input').value = this.dataset.value;
                    // Avatar logic
                    var avatarSuggestions = document.getElementById('avatar_suggestions');
                    var maleAvatars = document.getElementById('male_avatars');
                    var femaleAvatars = document.getElementById('female_avatars');
                    if (this.dataset.value === 'male') {
                        avatarSuggestions.style.display = '';
                        maleAvatars.style.display = '';
                        femaleAvatars.style.display = 'none';
                    } else if (this.dataset.value === 'female') {
                        avatarSuggestions.style.display = '';
                        maleAvatars.style.display = 'none';
                        femaleAvatars.style.display = '';
                    } else {
                        avatarSuggestions.style.display = 'none';
                    }
                });
            });
            // Set default gender selection on page load
            window.addEventListener('DOMContentLoaded', function () {
                var defaultGender = document.querySelector('.gender-btn[data-value="male"]');
                if (defaultGender) {
                    defaultGender.classList.remove('bg-gray-200', 'text-gray-700');
                    defaultGender.classList.add('bg-blue-600', 'text-white');
                    document.getElementById('gender_input').value = 'male';
                    // Show male avatars by default
                    var avatarSuggestions = document.getElementById('avatar_suggestions');
                    var maleAvatars = document.getElementById('male_avatars');
                    var femaleAvatars = document.getElementById('female_avatars');
                    avatarSuggestions.style.display = '';
                    maleAvatars.style.display = '';
                    femaleAvatars.style.display = 'none';
                }
            });
            // Avatar selection logic (no radio inputs)
            document.querySelectorAll('.avatar-img').forEach(img => {
                img.addEventListener('click', function () {
                    document.querySelectorAll('.avatar-img').forEach(i => i.classList.remove('border-blue-400'));
                    this.classList.add('border-blue-400');
                    document.getElementById('avatar_input').value = this.getAttribute('data-value');
                });
            });
            // Ensure avatar images are clickable
            document.querySelectorAll('#avatar_suggestions img').forEach(img => {
                img.style.pointerEvents = 'auto';
            });
            document.addEventListener('DOMContentLoaded', function () {
                // Profile image preview logic
                const profileInput = document.getElementById('profile_picture_input');
                const previewContainer = document.getElementById('profile_image_preview_container');
                const previewImg = document.getElementById('profile_image_preview');
                const removeBtn = document.getElementById('remove_profile_image');
                const uploadBox = document.getElementById('upload_box');
                let cropper = null;
                const cropperModal = document.getElementById('cropperModal');
                const cropperImage = document.getElementById('cropperImage');
                const cropperZoom = document.getElementById('cropperZoom');
                const setProfilePictureBtn = document.getElementById('setProfilePicture');
                const closeCropperModal = document.getElementById('closeCropperModal');

                if (profileInput && previewContainer && previewImg && removeBtn && uploadBox) {
                    profileInput.addEventListener('change', function (e) {
                        if (this.files && this.files[0]) {
                            // Hide avatar options and -- OR -- divider when a local image is selected
                            document.getElementById('avatar_suggestions').style.display = 'none';
                            document.getElementById('or_div').style.display = 'none';
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                cropperImage.src = e.target.result;
                                cropperImage.style.display = 'block';
                                cropperModal.classList.remove('hidden');
                                // Initialize cropper after image loads
                                cropperImage.onload = function () {
                                    if (cropper) cropper.destroy();
                                    cropper = new Cropper(cropperImage, {
                                        aspectRatio: 1,
                                        viewMode: 1,
                                        dragMode: 'move',
                                        background: false,
                                        autoCropArea: 1,
                                        responsive: true,
                                        zoomOnWheel: false,
                                    });
                                    cropperZoom.value = 1;
                                };
                            };
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                    cropperZoom.addEventListener('input', function () {
                        if (cropper) {
                            cropper.zoomTo(parseFloat(this.value));
                        }
                    });
                    setProfilePictureBtn.addEventListener('click', function () {
                        if (cropper) {
                            const canvas = cropper.getCroppedCanvas({ width: 256, height: 256, imageSmoothingQuality: 'high' });
                            previewImg.src = canvas.toDataURL('image/png');
                            previewContainer.classList.remove('hidden');
                            uploadBox.classList.add('hidden');
                            cropperModal.classList.add('hidden');
                            cropper.destroy();
                            cropper = null;
                        }
                    });
                    closeCropperModal.addEventListener('click', function () {
                        cropperModal.classList.add('hidden');
                        if (cropper) {
                            cropper.destroy();
                            cropper = null;
                        }
                        profileInput.value = '';
                    });
                    removeBtn.addEventListener('click', function () {
                        profileInput.value = '';
                        previewImg.src = '#';
                        previewContainer.classList.add('hidden');
                        uploadBox.classList.remove('hidden');
                        // Show avatar options and -- OR -- divider again when image is removed
                        document.getElementById('avatar_suggestions').style.display = '';
                        document.getElementById('or_div').style.display = '';
                    });
                }
            });
            // AJAX profile completion
            document.getElementById('profileForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                const form = e.target;
                const data = new FormData(form);
                const errorDiv = document.getElementById('profileError');
                const successDiv = document.getElementById('profileSuccess');
                errorDiv.style.display = 'none';
                successDiv.style.display = 'none';

                // Require either profile image or avatar selection
                const profileInput = document.getElementById('profile_picture_input');
                const avatarInput = document.getElementById('avatar_input');
                const hasProfileImage = profileInput && profileInput.files && profileInput.files.length > 0;
                const hasAvatar = avatarInput && avatarInput.value && avatarInput.value.trim() !== '';
                if (!hasProfileImage && !hasAvatar) {
                    errorDiv.textContent = 'Please upload a profile image or select an avatar.';
                    errorDiv.style.display = '';
                    return;
                }

                // If custom location is shown, set location value to custom_location for backend
                const locationSelect = document.getElementById('location');
                const customLocation = document.getElementById('custom_location');
                if (locationSelect && customLocation && locationSelect.value === 'other' && customLocation.value.trim() !== '') {
                    data.set('location', 'other');
                    data.set('custom_location', customLocation.value.trim());
                }

                try {
                    const res = await fetch(window.location.pathname, {
                        method: 'POST',
                        body: data,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const result = await res.json();
                    if (result.success) {
                        successDiv.textContent = result.success;
                        successDiv.style.display = '';
                        errorDiv.style.display = 'none';
                        setTimeout(() => {
                            successDiv.style.display = 'none';
                            if (result.redirect) {
                                window.location.href = result.redirect;
                            }
                        }, 1500);
                    } else if (result.error) {
                        errorDiv.textContent = result.error;
                        errorDiv.style.display = '';
                        successDiv.style.display = 'none';
                    }
                } catch (err) {
                    errorDiv.textContent = 'Profile update failed. Please try again.';
                    errorDiv.style.display = '';
                    successDiv.style.display = 'none';
                }
            });
        </script>
        <!-- Profile End -->
    </div>

    <?php include 'partials/footer.php' ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
</body>

</html>