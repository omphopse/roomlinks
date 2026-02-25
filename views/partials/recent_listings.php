<?php
require_once __DIR__ . '/../../controllers/UserController.php';
require_once __DIR__ . '/../../models/UserModel.php';
$userController = new UserController();
$userModel = new UserModel();
$recentListings = $userController->getListings(0, 6);
if (empty($recentListings)) {
    echo '<div class="col-span-3 text-center text-gray-500 text-xl py-16">No recent listings found.</div>';
} else {
    foreach ($recentListings as $listing): ?>
        <a href="<?php echo APP_URL; ?>/roomlisting?id=<?php echo $listing['id']; ?>">
            <div class="custom-listing-card" style="text-decoration:none;color:inherit;">
                <div class="relative flex-shrink-0">
                    <?php
                    $profileImg = UserModel::getProfileImageByUserId($listing['user_id']);
                    $profileImg = str_replace(['\\', '//'], '/', $profileImg);
                    if (strpos($profileImg, 'assets/') === 0) {
                        $localPath = str_replace('/', DIRECTORY_SEPARATOR, $profileImg);
                        if (!file_exists(__DIR__ . '/../../' . $localPath)) {
                            $profileImg = 'assets/images/profile/user.png';
                        }
                    }
                    $mainImg = ($listing['role'] === 'roommate') ? $profileImg : $listing['image'];
                    ?>
                    <img src="<?php echo $mainImg; ?>" alt="Room Thumbnail"
                        class="w-full h-56 max-h-56 object-cover object-center">
                    <span
                        class="absolute top-3 left-3 bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold shadow"><?php echo $listing['price']; ?></span>
                </div>
                <div class="custom-listing-card-content">
                    <div class="custom-listing-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="block max-w-[190px] truncate align-middle"><?php 
                            $locationValue = $listing['location'];
                            if (is_numeric($locationValue)) {
                                $locationName = $userModel->getLocationNameById((int)$locationValue);
                                echo htmlspecialchars($locationName ?? 'Location not found');
                            } else {
                                echo htmlspecialchars($locationValue ?: 'Location not found');
                            }
                        ?></span>
                    </div>
                    <div class="custom-listing-amenities hide-scrollbar">
                        <div class="custom-listing-amenity" title="Gender">
                            <i class="fas fa-venus-mars text-pink-500"></i>
                            Looking for : <?php echo ucfirst($listing['gender']); ?>
                        </div>

                        <?php
                        // Show vibe match percentage if logged in and not self, else show 'Set Your Vibes' button
                        $vibePercent = null;
                        $showSetVibes = false;
                        if (session_status() === PHP_SESSION_NONE)
                            session_start();
                        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $listing['user_id']) {
                            // Check if logged-in user has set their vibes
                            $userVibes = $userController->hasUserSetVibes($_SESSION['user_id']);
                            if ($userVibes) {
                                $vibePercent = $userController->getVibeMatchPercentage($_SESSION['user_id'], $listing['user_id']);
                            } else {
                                $showSetVibes = true;
                            }
                        }
                        if ($vibePercent !== null) {
                            echo '<div class="flex justify-end mt-1">'
                                . '<button class="vibe-match-percentage px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-300 hover:bg-blue-200 transition-all">'
                                . '<i class="fas fa-bolt mr-1 text-yellow-400"></i>Vibe Match: ' . $vibePercent . '%'
                                . '</button></div>';
                        } elseif ($showSetVibes) {
                            echo '<div class="flex justify-end mt-1">'
                                . '<button type="button" onclick="event.stopPropagation(); event.preventDefault(); window.location=\'' . APP_URL . '/vibes\'" onmousedown="event.stopPropagation(); event.preventDefault();" class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300 hover:bg-yellow-200 transition-all">'
                                . '<i class="fas fa-bolt mr-1 text-yellow-400"></i>Set Your Vibes'
                                . '</button></div>';
                        }
                        ?>
                    </div>
                    <div class="custom-listing-profile">
                        <?php
                        $profileImg = UserModel::getProfileImageByUserId($listing['user_id']);
                        $profileImg = str_replace(['\\', '//'], '/', $profileImg);
                        if (strpos($profileImg, 'assets/') === 0) {
                            $localPath = str_replace('/', DIRECTORY_SEPARATOR, $profileImg);
                            if (!file_exists(__DIR__ . '/../../' . $localPath)) {
                                $profileImg = 'assets/images/profile/user.png';
                            }
                        }
                        ?>
                        <img src="<?php echo $profileImg; ?>" alt="User Profile" class="custom-listing-profile-img">
                        <div class="custom-listing-profile-info">
                            <span class="custom-listing-profile-name"><?php echo $listing['profile_name']; ?></span>
                            <span class="custom-listing-profile-status">
                                <?php
                                if (isset($listing['role'])) {
                                    if ($listing['role'] === 'room') {
                                        echo 'Looking for Roommate';
                                    } elseif ($listing['role'] === 'roommate') {
                                        echo 'Looking for Room';
                                    } elseif ($listing['role'] === 'pg') {
                                        echo 'Looking for Paying Guest';
                                    } elseif ($listing['role'] === 'tenant') {
                                        echo 'Looking for Tenant';
                                    } else {
                                        echo ucfirst($listing['role']);
                                    }
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    <?php endforeach;
}
