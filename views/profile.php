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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    <title>My Profile | RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />
    
</head>

<body class="flex flex-col min-h-screen ">
    <?php
    require_once __DIR__ . '/../helpers/auth.php';
    require_once __DIR__ . '/../models/UserModel.php';
    require_once __DIR__ . '/../models/ProfilePicture.php';
    checkAuth();

    $userId = $_SESSION['user_id'];
    $userModel = new UserModel();
    $profile = $userModel->getProfileById($userId);
    // Get email using UserModel::getUserById
    $userData = $userModel->getUserById($userId);
    $email = $userData['email'] ?? '';
    // Fetch 4 random avatars for each gender using ProfilePicture model
    $profilePictureModel = new ProfilePicture();
    $allAvatars = $profilePictureModel->getProfilePictures();
    $maleAvatars = array_filter($allAvatars, function ($pic) {
        return strtolower($pic['gender']) === 'men';
    });
    $femaleAvatars = array_filter($allAvatars, function ($pic) {
        return strtolower($pic['gender']) === 'women';
    });
    require_once __DIR__ . '/../controllers/UserController.php';
    $maleAvatars = UserController::getRandomAvatars(array_values($maleAvatars), 4);
    $femaleAvatars = UserController::getRandomAvatars(array_values($femaleAvatars), 4);

    // Handle update
    $successMsg = $errorMsg = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $newEmail = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $fieldsToUpdate = [];
        if ($name !== ($profile['name'] ?? '')) {
            $fieldsToUpdate['name'] = $name;
        }
        if ($phone !== ($profile['phone'] ?? '')) {
            $fieldsToUpdate['phone'] = $phone;
        }
        if ($gender !== ($profile['gender'] ?? '')) {
            $fieldsToUpdate['gender'] = $gender;
        }
        // Handle avatar selection or image upload
        $avatar = $_POST['avatar'] ?? '';
        $hasAvatar = !empty($avatar);
        $hasUpload = isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK;
        if ($hasAvatar) {
            // Get avatar_id from profile_pictures table
            $stmt = $userModel->getConnection()->prepare("SELECT id FROM profile_pictures WHERE image = ? LIMIT 1");
            $stmt->bind_param('s', $avatar);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $fieldsToUpdate['avatar_id'] = $row['id'];
                $fieldsToUpdate['uploaded_picture'] = null;
            }
            $stmt->close();
        } elseif ($hasUpload) {
            // Save uploaded image and update uploaded_picture, set avatar_id to null
            $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $monthYear = date('F_Y');
            $dir = __DIR__ . '/../assets/profile/profilepictures/' . $monthYear;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $filename = $userId . '_' . date('YmdHis') . '.' . $ext;
            $target = $dir . '/' . $filename;
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target);
            $fieldsToUpdate['uploaded_picture'] = 'assets/profile/profilepictures/' . $monthYear . '/' . $filename;
            $fieldsToUpdate['avatar_id'] = null;
        }
        $result = true;
        if (!empty($fieldsToUpdate)) {
            $result = $userModel->updateProfileFields($userId, $fieldsToUpdate);
        }
        // Email update (user table)
        if ($newEmail && $newEmail !== $email) {
            $resultEmail = $userModel->updateUserEmail($userId, $newEmail);
            if ($resultEmail !== true) {
                $errorMsg = $resultEmail;
            } else {
                $email = $newEmail;
            }
        }
        // Password update (user table)
        if ($password !== '' && $password === $confirmPassword) {
            $resultPwd = $userModel->updateUserPassword($userId, $password);
            if ($resultPwd !== true) {
                $errorMsg = $resultPwd;
            }
        }
        if ($result === true && !$errorMsg) {
            $successMsg = 'Profile updated successfully!';
            // Refresh profile data
            $profile = $userModel->getProfileById($userId);
        } elseif ($result !== true && !$errorMsg) {
            $errorMsg = $result;
        }
    }
    ?>
    <?php include 'partials/header.php' ?>
    <div class="flex-grow flex items-center justify-center px-4 ">
        <!-- Profile Start -->
        <form method="POST" enctype="multipart/form-data"
            class="w-full max-w-5xl mx-auto bg-white rounded-2xl shadow-2xl shadow-blue-300/60 p-4 sm:p-10 mt-10 mb-12 flex flex-col space-y-6 relative before:content-[''] before:absolute before:top-0 before:left-0 before:w-full before:h-6 before:rounded-t-2xl before:shadow-2xl before:shadow-blue-300/60 before:z-10" id="profileForm">
            <h2 class="text-3xl font-semibold text-blue-600 text-start">My Profile</h2>
            <hr class="border-gray-300 mb-3">
            <?php if ($successMsg): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-2"> <?= htmlspecialchars($successMsg) ?> </div>
            <?php endif; ?>
            <?php if ($errorMsg): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-2"> <?= htmlspecialchars($errorMsg) ?> </div>
            <?php endif; ?>

            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1">
                    <label class="block text-gray-700 font-semibold mb-1" for="first_name">Name</label>
                    <input id="first_name" type="text" value="<?= htmlspecialchars($profile['name'] ?? '') ?>"
                        name="name"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 font-semibold mb-1" for="phone">Phone Number</label>
                    <input id="phone" type="text" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>" name="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1">
                    <label class="block text-gray-700 font-semibold mb-1" for="email">Email</label>
                    <input id="email" type="email" value="<?= htmlspecialchars($email) ?>" name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 font-semibold mb-1" for="gender">Gender</label>
                    <div class="flex gap-4 mt-2">
                        <?php
                        $genderValRaw = $profile['gender'] ?? 'Male';
                        $genderVal = ucfirst(strtolower($genderValRaw));
                        ?>
                        <button type="button" id="gender-male"
                            class="gender-btn px-4 py-2 rounded border border-gray-300 text-gray-700 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            data-value="Male">Male</button>
                        <button type="button" id="gender-female"
                            class="gender-btn px-4 py-2 rounded border border-gray-300 text-gray-700 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            data-value="Female">Female</button>
                        <button type="button" id="gender-other"
                            class="gender-btn px-4 py-2 rounded border border-gray-300 text-gray-700 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400"
                            data-value="Other">Other</button>
                    </div>
                    <input type="hidden" name="gender" id="gender-input" value="<?= htmlspecialchars($genderVal) ?>" />
                </div>
            </div>
            <!-- Profile Picture Upload/Avatar Selection and Change Password in one row -->
            <div class="flex flex-col md:flex-row items-start md:items-center gap-8">
                <!-- Upload/Avatar Section -->
                <div class="flex-1 w-full">
                    <label class="font-bold text-gray-700 mb-2 md:mb-0 md:w-64 w-full block text-center md:text-left" for="profile_picture">Upload
                        Image/Select Avatar</label>
                    <div id="profile_image_preview_container"
                        class="relative flex justify-center items-center my-4 <?= ($profile['uploaded_picture'] || $profile['avatar_id']) ? '' : 'hidden' ?> w-full">
                        <?php
                        $profilePicUrl = '';
                        if (!empty($profile['uploaded_picture'])) {
                            $profilePicUrl = htmlspecialchars($profile['uploaded_picture']);
                        } elseif (!empty($profile['avatar_id'])) {
                            // Fetch avatar image using model method
                            $avatarId = (int) $profile['avatar_id'];
                            $avatarPic = $userModel->getAvatarImageById($avatarId);
                            $profilePicUrl = htmlspecialchars($avatarPic);
                        }
                        ?>
                        <img id="profile_image_preview" class="w-32 h-32 rounded-full object-cover mx-auto"
                            src="<?= $profilePicUrl ?>" alt="Profile Preview" />
                        <button type="button" id="remove_profile_image"
                            class="absolute top-0 right-1 bg-gray-800 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs focus:outline-none">&times;</button>
                    </div>
                    <div id="upload_box"
                        class="border-2 border-gray-300 rounded-lg flex flex-col items-center justify-center p-6 my-4 bg-white cursor-pointer hover:border-blue-400 transition w-full max-w-xs mx-auto<?= !empty($profilePicUrl) ? ' hidden' : '' ?>"
                        onclick="document.getElementById('profile_picture_input').click()">
                        <i class="fas fa-image text-3xl text-gray-400 mb-2"></i>
                        <span class="text-gray-500 text-center">Click or Drop to upload profile image (jpg or
                            png)</span>
                        <input type="file" id="profile_picture_input" name="profile_picture" accept="image/*"
                            class="hidden" />
                    </div>
                    <div id="or_div"
                        class="text-center text-gray-400 my-2<?= !empty($profilePicUrl) ? ' hidden' : '' ?>">-- OR --
                    </div>
                    <div id="avatar_suggestions"
                        class="flex justify-center gap-4 mt-2 w-full<?= !empty($profilePicUrl) ? ' hidden' : '' ?>">
                        <?php
                        if (!isset($maleAvatars) || !is_array($maleAvatars))
                            $maleAvatars = [];
                        if (!isset($femaleAvatars) || !is_array($femaleAvatars))
                            $femaleAvatars = [];
                        ?>
                        <div id="male_avatars" class="flex gap-4">
                            <?php foreach ($maleAvatars as $avatar): ?>
                                <img src="<?= htmlspecialchars($avatar['image']) ?>"
                                    data-value="<?= htmlspecialchars($avatar['image']) ?>" alt="Male Avatar"
                                    class="avatar-img w-16 h-16 rounded-full border-2 border-transparent hover:border-blue-400 cursor-pointer transition" />
                            <?php endforeach; ?>
                        </div>
                        <div id="female_avatars" class="flex gap-4">
                            <?php foreach ($femaleAvatars as $avatar): ?>
                                <img src="<?= htmlspecialchars($avatar['image']) ?>"
                                    data-value="<?= htmlspecialchars($avatar['image']) ?>" alt="Female Avatar"
                                    class="avatar-img w-16 h-16 rounded-full border-2 border-transparent hover:border-blue-400 cursor-pointer transition" />
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input type="hidden" name="avatar" id="avatar_input" />
                </div>

                <!-- Change Password Section -->
                <div class="flex-1 w-full md:ml-8 mt-8 md:mt-0">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-blue-600 mb-4">Change Password</h3>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-semibold mb-1">New Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" autocomplete="new-password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 pr-10" />
                                <button type="button" tabindex="-1" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 focus:outline-none" onclick="togglePasswordVisibility('password', this)">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="confirm_password" class="block text-gray-700 font-semibold mb-1">Confirm Password</label>
                            <div class="relative">
                                <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 pr-10" />
                                <button type="button" tabindex="-1" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 focus:outline-none" onclick="togglePasswordVisibility('confirm_password', this)">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div id="password_error" class="text-red-600 text-sm mt-1 hidden">Passwords do not match.</div>
                    </div>
                </div>
            </div>
            <!-- End Profile Picture Upload/Avatar Selection -->
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-200 mt-4">Update
                Changes</button>
            <div class="mt-8 w-full">
                <hr class="border-gray-300 mb-3">
                <div class="text-left mb-2 text-sm text-gray-500">If you no longer need your RoomLinks account, you can
                    delete your profile permanently. This action cannot be undone.</div>
                <div class="flex justify-start">
                    <button type="button" id="deleteProfileBtn"
                        class="flex items-center gap-2 text-red-600 py-2 px-4 rounded transition duration-200 mt-0">
                        <i class="fas fa-trash-alt"></i>
                        Delete Profile
                    </button>
                </div>
            </div>
        </form>
        <script>
        // Password match validation
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            var pwd = document.getElementById('password').value;
            var cpwd = document.getElementById('confirm_password').value;
            var errorDiv = document.getElementById('password_error');
            // Password requirements: min 6 chars, 1 number, 1 uppercase, 1 lowercase, 1 symbol
            var pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{6,}$/;
            if (pwd !== '' || cpwd !== '') {
                if (!pwdRegex.test(pwd)) {
                    errorDiv.classList.remove('hidden');
                    errorDiv.textContent = 'Password must be at least 6 characters and include a number, uppercase, lowercase, and symbol.';
                    e.preventDefault();
                    return false;
                } else if (pwd !== cpwd) {
                    errorDiv.classList.remove('hidden');
                    errorDiv.textContent = 'Passwords do not match.';
                    e.preventDefault();
                    return false;
                } else {
                    errorDiv.classList.add('hidden');
                }
            } else {
                errorDiv.classList.add('hidden');
            }
        });
        // Toggle password visibility for password fields
        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                btn.querySelector('i').classList.remove('fa-eye');
                btn.querySelector('i').classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                btn.querySelector('i').classList.remove('fa-eye-slash');
                btn.querySelector('i').classList.add('fa-eye');
            }
        }
        </script>

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

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden p-2 sm:p-0 m-0">
            <div class="bg-white rounded-lg shadow-2xl p-4 sm:p-8 w-full max-w-md m-0">
                <h3 class="text-2xl font-bold mb-4 text-center text-[--primary-color]">Are you sure?</h3>
                <p class="mb-6 text-gray-700 text-center">Your data will be deleted from the server and this action
                    cannot be undone.</p>
                <div class="flex justify-center gap-4">
                    <button id="confirmDelete" type="button"
                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                    <button id="cancelDelete" type="button"
                        class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">Cancel</button>
                </div>
            </div>
        </div>


        <!-- Loading Modal (shows while deleting) -->
        <div id="deleteLoadingModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white rounded-lg shadow-2xl p-8 flex flex-col items-center justify-center">
                <svg class="w-16 h-16 text-blue-500 mb-4 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                </svg>
                <h3 class="text-xl font-semibold text-center text-blue-600">Deleting profile...</h3>
            </div>
        </div>

        <!-- Success Animation Modal (not nested) -->
        <div id="deleteSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
            <div class="bg-white rounded-lg shadow-2xl p-8 flex flex-col items-center justify-center">
                <svg class="w-20 h-20 text-green-500 mb-4 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/>
                    <path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 class="text-2xl font-bold mb-2 text-center text-green-600">Profile Deleted!</h3>
                <p class="mb-2 text-gray-700 text-center">Your profile was deleted successfully.</p>
            </div>
        </div>

        <script>
            // Modal logic
            document.addEventListener('DOMContentLoaded', function () {
                const deleteBtn = document.getElementById('deleteProfileBtn');
                const modal = document.getElementById('deleteModal');
                const cancelBtn = document.getElementById('cancelDelete');
                const confirmBtn = document.getElementById('confirmDelete');
                if (deleteBtn && modal) {
                    deleteBtn.onclick = function () {
                        modal.classList.remove('hidden');
                    };
                }
                if (cancelBtn && modal) {
                    cancelBtn.onclick = function () {
                        modal.classList.add('hidden');
                    };
                }
                if (confirmBtn && modal) {
                    confirmBtn.onclick = function () {
                        modal.classList.add('hidden');
                        // Show loading modal
                        const loadingModal = document.getElementById('deleteLoadingModal');
                        if (loadingModal) loadingModal.classList.remove('hidden');
                        // AJAX call to delete user and all related data
                        fetch('<?= APP_URL ?>/controllers/UserController.php?action=deleteUser', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Network error');
                            return res.json();
                        })
                        .then(data => {
                            if (loadingModal) loadingModal.classList.add('hidden');
                            if (data.success) {
                                // Show success animation, then redirect
                                const successModal = document.getElementById('deleteSuccessModal');
                                if (successModal) {
                                    successModal.classList.remove('hidden');
                                    setTimeout(function () {
                                        window.location.href = '<?= APP_URL ?>/';
                                    }, 1800);
                                } else {
                                    window.location.href = '<?= APP_URL ?>/';
                                }
                            } else {
                                alert(data.error || 'Failed to delete profile.');
                            }
                        })
                        .catch((err) => {
                            if (loadingModal) loadingModal.classList.add('hidden');
                            alert('Failed to delete profile.');
                        });
                    };
                }
            });
            // Gender button logic
            const genderBtns = document.querySelectorAll('.gender-btn');
            const genderInput = document.getElementById('gender-input');
            function setGenderHighlight(selected) {
                genderBtns.forEach(btn => {
                    if (btn.getAttribute('data-value') === selected) {
                        btn.classList.add('bg-blue-600', 'text-white');
                        btn.classList.remove('bg-gray-100', 'text-gray-700');
                    } else {
                        btn.classList.remove('bg-blue-600', 'text-white');
                        btn.classList.add('bg-gray-100', 'text-gray-700');
                    }
                });
            }
            // Initial highlight from profile table value
            setGenderHighlight(genderInput.value);
            genderBtns.forEach(btn => {
                btn.onclick = function () {
                    genderInput.value = this.getAttribute('data-value');
                    setGenderHighlight(genderInput.value);
                    // Show/hide avatar sections based on gender selection
                    showGenderAvatars(genderInput.value);
                };
            });

            
            showGenderAvatars(genderInput.value);
            const profileInput = document.getElementById('profile_picture_input');
            const previewContainer = document.getElementById('profile_image_preview_container');
            const previewImg = document.getElementById('profile_image_preview');
            const removeBtn = document.getElementById('remove_profile_image');
            const avatarImgs = document.querySelectorAll('.avatar-img');
            const avatarInput = document.getElementById('avatar_input');
            // Show correct avatar section based on gender
            function showGenderAvatars(gender) {
                const maleAvatars = document.getElementById('male_avatars');
                const femaleAvatars = document.getElementById('female_avatars');
                // Normalize gender value for comparison
                const genderNorm = gender.trim().toLowerCase();
                // Show/hide avatar sections based on gender
                if (genderNorm === 'male' || genderNorm === 'men') {
                    if (maleAvatars) maleAvatars.style.display = 'flex';
                    if (femaleAvatars) femaleAvatars.style.display = 'none';
                    document.getElementById('avatar_suggestions').style.display = '';
                } else if (genderNorm === 'female' || genderNorm === 'women') {
                    if (maleAvatars) maleAvatars.style.display = 'none';
                    if (femaleAvatars) femaleAvatars.style.display = 'flex';
                    document.getElementById('avatar_suggestions').style.display = '';
                } else {
                    // Hide both for 'Other'
                    if (maleAvatars) maleAvatars.style.display = 'none';
                    if (femaleAvatars) femaleAvatars.style.display = 'none';
                    document.getElementById('avatar_suggestions').style.display = 'none';
                }
            }
            // Show preview when file selected
            if (profileInput) {
                profileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (ev) {
                            previewImg.src = ev.target.result;
                            previewContainer.classList.remove('hidden');
                            document.getElementById('upload_box').classList.add('hidden');
                            document.getElementById('or_div').classList.add('hidden');
                            document.getElementById('avatar_suggestions').classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            // On page load, show avatar suggestions if no image/avatar selected
            window.addEventListener('DOMContentLoaded', function () {
                const profilePicUrl = "<?= $profilePicUrl ?>";
                if (!profilePicUrl) {
                    document.getElementById('avatar_suggestions').classList.remove('hidden');
                    document.getElementById('upload_box').classList.remove('hidden');
                    document.getElementById('or_div').classList.remove('hidden');
                    showGenderAvatars(genderInput.value);
                } else {
                    document.getElementById('avatar_suggestions').classList.add('hidden');
                }
            });
            // Remove profile image
            if (removeBtn) {
                removeBtn.onclick = function () {
                    previewImg.src = '#';
                    previewContainer.classList.add('hidden');
                    document.getElementById('upload_box').classList.remove('hidden');
                    document.getElementById('or_div').classList.remove('hidden');
                    document.getElementById('avatar_suggestions').classList.remove('hidden');
                    if (profileInput) profileInput.value = '';
                    avatarInput.value = '';
                };
            }
            // Avatar selection
            avatarImgs.forEach(img => {
                img.onclick = function () {
                    avatarImgs.forEach(i => i.classList.remove('border-blue-400'));
                    this.classList.add('border-blue-400');
                    avatarInput.value = this.getAttribute('data-value');
                    previewImg.src = this.getAttribute('data-value');
                    previewContainer.classList.remove('hidden');
                    document.getElementById('upload_box').classList.add('hidden');
                    document.getElementById('or_div').classList.add('hidden');
                    document.getElementById('avatar_suggestions').classList.add('hidden');
                };
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
        </script>
        <!-- Profile End -->
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <?php include 'partials/footer.php' ?>

</body>

</html>