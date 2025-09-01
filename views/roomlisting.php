<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/ProfilePicture.php';

// Get listing ID from query
$listingId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$listingId) {
    echo '<div style="color:red; padding:20px;">Invalid listing ID.</div>';
    exit;
}

$userModel = new UserModel();
$listing = $userModel->getListingById($listingId);
if (!$listing) {
    echo '<div style="color:red; padding:20px;">Listing not found.</div>';
    exit;
}


$userId = $listing['user_id'];
$profile = null;
if (method_exists($userModel, 'getProfileById')) {
    $profile = $userModel->getProfileById($userId);
}

$profileImg = UserModel::getProfileImageByUserId($userId);
$profileImg = str_replace(['\\', '//'], '/', $profileImg);
if (strpos($profileImg, 'assets/') === 0) {
    $localPath = str_replace('/', DIRECTORY_SEPARATOR, $profileImg);
    if (!file_exists(__DIR__ . '/../' . $localPath)) {
        $profileImg = 'assets/images/profile/user.png';
    }
}

$vibes = isset($profile['vibes']) ? $profile['vibes'] : '';

// Prepare amenities, highlights, images
$amenities = isset($listing['amenities']) ? (is_array($listing['amenities']) ? $listing['amenities'] : explode(' | ', $listing['amenities'])) : [];
$highlights = isset($listing['highlights']) ? (is_array($listing['highlights']) ? $listing['highlights'] : explode(' | ', $listing['highlights'])) : [];
$images = [];
for ($i = 1; $i <= 5; $i++) {
    if (!empty($listing['image' . $i])) {
        $images[] = $listing['image' . $i];
    }
}
if (empty($images) && !empty($listing['image'])) {
    $images[] = $listing['image'];
}
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
    <style>
        /* 1. General hide below 950px */
        @media (max-width: 950px) {
            #mobileBottomNav {
                display: none !important;
            }
        }

        /* 2. Override and show again below 768px */
        @media (max-width: 768px) {
            #mobileBottomNav {
                display: flex !important;
            }
        }


        /* Custom breakpoint for mobile view at 950px and below */
        @media (max-width: 950px) {


            .md\:flex {
                display: none !important;
            }

            .md\:hidden {
                display: block !important;
            }

            .md\:w-1\/4 {
                width: 100% !important;
            }

            .md\:w-3\/4 {
                width: 100% !important;
            }

            .md\:px-8 {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .md\:block {
                display: none !important;
            }

            .md\:grid-cols-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }

            .max-w-6xl {
                max-width: 100% !important;
            }

            body,
            html {
                background: #f8fafc;
            }

            header.header-main,
            .top-bar-main,
            .nav-bar {
                border-radius: 0 !important;
                margin: 0 !important;
            }

            /* Do NOT override .flex globally here! */
        }

        @media (min-width: 951px) {
            .md\:hidden {
                display: none !important;
            }

            .md\:flex {
                display: flex !important;
            }

            .md\:block {
                display: block !important;
            }
        }
    </style>
    <title> Roomlisting | RoomLinks | Listing Details</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />
</head>

<body class="flex flex-col min-h-screen ">
    <?php include 'partials/header.php' ?>
    <!-- Single Listing Start -->
    <div class="w-full flex justify-center bg-white">
        <div class="w-full max-w-6xl py-8 px-2 md:px-8 flex md:flex-row flex-col gap-6">
            <!-- Left Sidebar (Desktop Only) -->
            <div class="hidden md:flex md:w-1/4 w-full flex-col gap-4">
                <div class="bg-white rounded-xl shadow-xl shadow-gray-300 p-6 flex flex-col items-center">
                    <img src="<?php echo $profileImg; ?>" alt="User Avatar"
                        class="w-24 h-24 rounded-full mb-2 border-4 border-gray-200">
                    <h2 class="font-semibold text-lg mb-2">
                        <?php echo htmlspecialchars( $listing['profile_name'] ?? 'User'); ?>
                    </h2>
                    <div class="flex gap-2 w-full justify-center mt-2">
                        <?php
                        require_once __DIR__ . '/../controllers/UserController.php';
                        $userController = new UserController();
                        $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
                        if ($isLoggedIn) {
                            echo $userController->getContactButtons($listingId);
                        } else {
                            echo '<div class="w-full flex justify-center items-center py-2"><span class="text-gray-500 text-sm font-medium">Please <a href="#" class=" text-blue-600 underline" onclick="showAuthModal(\'login\'); return false;">login</a> to contact the user.</span></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-xl shadow-xl shadow-gray-300 p-6 mb-6">
                    <!-- Tablet/Mobile Profile Top -->
                    <div class="flex flex-col items-center mb-6 md:hidden text-center w-full">
                        <img src="<?php echo $profileImg; ?>" alt="User Avatar"
                            class="w-24 h-24 rounded-full mb-2 border-4 border-gray-200 mx-auto">
                        <h2 class="font-semibold text-xl mb-2 text-center">
                            <?php echo htmlspecialchars($listing['profile_name'] ?? 'User'); ?>
                        </h2>
                        <div class="flex gap-2 justify-center mt-2 mb-2 flex-wrap w-full">
                            <?php
                            require_once __DIR__ . '/../controllers/UserController.php';
                            $userController = new UserController();
                            $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
                            if ($isLoggedIn) {
                                echo $userController->getContactButtons($listingId);
                            } else {
                                echo '<div class="w-full flex justify-center items-center py-2"><span class="text-gray-500 text-sm font-medium">Please <a href="#" class="text-blue-600 underline" onclick="showAuthModal(\'login\'); return false;">login</a> to contact the user.</span></div>';
                            }
                            ?>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 justify-center w-full"><i
                                class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($listing['location']); ?></div>
                    </div>
                    <!-- Desktop Location Row -->
                    <div class="hidden md:block pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-1">Location</h3>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($listing['location']); ?>
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Basic Info</h3>
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div>
                                <div class="text-xs text-gray-400">Gender</div>
                                <?php
                                $gender = isset($profile['gender']) ? strtolower($profile['gender']) : 'any';
                                $genderIcon = 'fa-mars';
                                $genderLabel = ucfirst($gender);
                                if ($gender === 'female') {
                                    $genderIcon = 'fa-venus';
                                } elseif ($gender === 'any') {
                                    $genderIcon = 'fa-venus-mars';
                                    $genderLabel = 'Any';
                                }
                                ?>
                                <div class="font-medium flex items-center gap-1"><i
                                        class="fas <?php echo $genderIcon; ?>"></i> <?php echo $genderLabel; ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Approx Rent</div>
                                <div class="font-medium">₹
                                    <?php echo htmlspecialchars($listing['price'] ?? $listing['rent']); ?>
                                </div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Occupancy</div>
                                <div class="font-medium flex items-center gap-1"><i class="fas fa-bed"></i>
                                    <?php echo htmlspecialchars($listing['occupancy'] ?? 'Shared'); ?></div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Looking For</div>
                                <?php
                                $lookingForGender = isset($listing['gender']) ? strtolower($listing['gender']) : 'any';
                                $lookingForIcon = 'fa-mars';
                                $lookingForLabel = ucfirst($lookingForGender);
                                if ($lookingForGender === 'female') {
                                    $lookingForIcon = 'fa-venus';
                                } elseif ($lookingForGender === 'any') {
                                    $lookingForIcon = 'fa-venus-mars';
                                    $lookingForLabel = 'Any';
                                }
                                ?>
                                <div class="font-medium flex items-center gap-1"><i
                                        class="fas <?php echo $lookingForIcon; ?>"></i> <?php echo $lookingForLabel; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($listing['role']) && strtolower($listing['role']) === 'roommate') { ?>
                        <!-- Pictures section hidden for roommate role -->
                    <?php } else { ?>
                        <div class=" border-t pt-8 pb-8">
                            <h3 class="font-semibold text-lg mb-2">Pictures</h3>
                            <div class="w-full flex justify-center">
                                <div id="galleryContainer"
                                    class="relative w-full max-w-full flex flex-col items-center select-none">
                                    <button id="galleryPrev"
                                        class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10 text-gray-500 hover:bg-gray-100"
                                        aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                                    <div id="gallerySlider"
                                        class="w-full h-72 overflow-hidden relative bg-gray-100 flex items-center justify-center"
                                        style="touch-action: pan-y;">
                                        <img id="galleryImage" src="assets/room-listings/home.png" alt="Room Photo"
                                            class="rounded-lg w-full h-72 object-contain transition-transform duration-300 ease-in-out cursor-pointer"
                                            style="max-width: 100%; max-height: 18rem; min-height: 18rem;" />
                                    </div>
                                    <button id="galleryNext"
                                        class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10 text-gray-500 hover:bg-gray-100"
                                        aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                                    <div class="flex gap-1 mt-3">
                                        <?php if (!empty($images)) {
                                            foreach ($images as $imgIdx => $_) {
                                                echo '<span class="gallery-dot w-2 h-2 rounded-full bg-gray-300 inline-block"></span>';
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Gallery Modal -->
                            <div id="galleryModal"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 hidden">
                                <button id="closeGalleryModal"
                                    class="absolute top-4 right-4 text-white hover:text-gray-300 text-4xl leading-none">&times;</button>
                                <button id="galleryModalPrev"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-60 text-black rounded-full p-2 text-2xl z-10"><i
                                        class="fas fa-chevron-left"></i></button>
                                <img id="galleryModalImage" src="" alt="Gallery Large"
                                    class="max-h-[80vh] max-w-[90vw] rounded-xl shadow-2xl object-contain" />
                                <button id="galleryModalNext"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-60 text-black rounded-full p-2 text-2xl z-10"><i
                                        class="fas fa-chevron-right"></i></button>
                                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2">
                                    <?php if (!empty($images)) {
                                        foreach ($images as $imgIdx => $_) {
                                            echo '<span class="gallery-modal-dot w-3 h-3 rounded-full bg-gray-400 inline-block"></span>';
                                        }
                                    } ?>
                                </div>
                            </div>
                            <script>
                                const galleryImages = <?php echo json_encode($images); ?>;
                                let galleryIndex = 0;
                                const galleryImage = document.getElementById('galleryImage');
                                const galleryDots = document.querySelectorAll('.gallery-dot');
                                const galleryContainer = document.getElementById('galleryContainer');
                                const gallerySlider = document.getElementById('gallerySlider');
                                let isTransitioning = false;

                                function updateGallery(transition = true) {
                                    if (transition) {
                                        galleryImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                    } else {
                                        galleryImage.style.transition = 'none';
                                    }
                                    galleryImage.src = galleryImages[galleryIndex];
                                    galleryImage.style.transform = 'translateX(0)';
                                    galleryDots.forEach((dot, idx) => {
                                        dot.classList.toggle('bg-blue-500', idx === galleryIndex);
                                        dot.classList.toggle('bg-gray-300', idx !== galleryIndex);
                                    });
                                }

                                document.getElementById('galleryPrev').onclick = function () {
                                    if (isTransitioning) return;
                                    slideTo(galleryIndex - 1, -1);
                                };
                                document.getElementById('galleryNext').onclick = function () {
                                    if (isTransitioning) return;
                                    slideTo(galleryIndex + 1, 1);
                                };
                                // Dots click
                                galleryDots.forEach((dot, idx) => {
                                    dot.onclick = function () {
                                        if (isTransitioning || idx === galleryIndex) return;
                                        slideTo(idx, idx > galleryIndex ? 1 : -1);
                                    };
                                });

                                // Slide animation logic
                                function slideTo(newIndex, direction) {
                                    if (isTransitioning) return;
                                    isTransitioning = true;
                                    // Slide out current image
                                    galleryImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                    galleryImage.style.transform = `translateX(${-100 * direction}%)`;
                                    setTimeout(() => {
                                        galleryImage.style.transition = 'none';
                                        galleryIndex = (newIndex + galleryImages.length) % galleryImages.length;
                                        galleryImage.src = galleryImages[galleryIndex];
                                        galleryImage.style.transform = `translateX(${100 * direction}%)`;
                                        setTimeout(() => {
                                            galleryImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                            galleryImage.style.transform = 'translateX(0)';
                                            galleryDots.forEach((dot, idx) => {
                                                dot.classList.toggle('bg-blue-500', idx === galleryIndex);
                                                dot.classList.toggle('bg-gray-300', idx !== galleryIndex);
                                            });
                                            setTimeout(() => { isTransitioning = false; }, 300);
                                        }, 20);
                                    }, 300);
                                }

                                // Touch and mouse drag support
                                let startX = 0;
                                let currentX = 0;
                                let dragging = false;
                                let dragDirection = 0;

                                function onDragStart(x) {
                                    if (isTransitioning) return;
                                    dragging = true;
                                    startX = x;
                                    currentX = x;
                                    galleryImage.style.transition = 'none';
                                }
                                function onDragMove(x) {
                                    if (!dragging) return;
                                    currentX = x;
                                    const deltaX = currentX - startX;
                                    galleryImage.style.transform = `translateX(${deltaX}px)`;
                                }
                                function onDragEnd() {
                                    if (!dragging) return;
                                    const deltaX = currentX - startX;
                                    const threshold = 60;
                                    dragging = false;
                                    if (deltaX < -threshold) {
                                        slideTo(galleryIndex + 1, 1);
                                    } else if (deltaX > threshold) {
                                        slideTo(galleryIndex - 1, -1);
                                    } else {
                                        galleryImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                        galleryImage.style.transform = 'translateX(0)';
                                    }
                                }
                                // Touch events
                                let touchId = null;
                                gallerySlider.addEventListener('touchstart', function (e) {
                                    if (e.touches.length === 1) {
                                        touchId = e.touches[0].identifier;
                                        onDragStart(e.touches[0].clientX);
                                    }
                                });
                                gallerySlider.addEventListener('touchmove', function (e) {
                                    if (touchId !== null) {
                                        for (let i = 0; i < e.touches.length; i++) {
                                            if (e.touches[i].identifier === touchId) {
                                                onDragMove(e.touches[i].clientX);
                                                break;
                                            }
                                        }
                                    }
                                });
                                gallerySlider.addEventListener('touchend', function (e) {
                                    onDragEnd();
                                    touchId = null;
                                });
                                // Mouse events
                                let mouseDown = false;
                                gallerySlider.addEventListener('mousedown', function (e) {
                                    mouseDown = true;
                                    onDragStart(e.clientX);
                                });
                                gallerySlider.addEventListener('mousemove', function (e) {
                                    if (mouseDown) {
                                        onDragMove(e.clientX);
                                    }
                                });
                                gallerySlider.addEventListener('mouseup', function (e) {
                                    if (mouseDown) {
                                        onDragEnd();
                                        mouseDown = false;
                                    }
                                });
                                gallerySlider.addEventListener('mouseleave', function (e) {
                                    if (mouseDown) {
                                        onDragEnd();
                                        mouseDown = false;
                                    }
                                });
                                // Prevent image dragging default behavior
                                if (galleryImage) {
                                    galleryImage.ondragstart = function () { return false; };
                                }
                                updateGallery();

                                // Gallery Modal logic
                                const galleryModal = document.getElementById('galleryModal');
                                const galleryModalImage = document.getElementById('galleryModalImage');
                                const galleryModalPrev = document.getElementById('galleryModalPrev');
                                const galleryModalNext = document.getElementById('galleryModalNext');
                                const closeGalleryModal = document.getElementById('closeGalleryModal');
                                const galleryModalDots = document.querySelectorAll('.gallery-modal-dot');
                                let modalIndex = 0;

                                function openGalleryModal(index) {
                                    modalIndex = index;
                                    galleryModal.classList.remove('hidden');
                                    updateGalleryModal(modalIndex);
                                }
                                function closeGallery() {
                                    galleryModal.classList.add('hidden');
                                }
                                function updateGalleryModal(index) {
                                    galleryModalImage.src = galleryImages[index];
                                    galleryModalDots.forEach((dot, idx) => {
                                        dot.classList.toggle('bg-blue-500', idx === index);
                                        dot.classList.toggle('bg-gray-400', idx !== index);
                                    });
                                }
                                // Open modal on image click
                                if (galleryImage) {
                                    galleryImage.style.cursor = 'pointer';
                                    galleryImage.onclick = function () {
                                        openGalleryModal(galleryIndex);
                                    };
                                }
                                // Modal navigation with buttons
                                function modalPrev() {
                                    modalIndex = (modalIndex - 1 + galleryImages.length) % galleryImages.length;
                                    galleryIndex = modalIndex;
                                    updateGalleryModal(modalIndex);
                                }
                                function modalNext() {
                                    modalIndex = (modalIndex + 1) % galleryImages.length;
                                    galleryIndex = modalIndex;
                                    updateGalleryModal(modalIndex);
                                }
                                galleryModalPrev.onclick = function (e) {
                                    e.stopPropagation();
                                    modalPrev();
                                };
                                galleryModalNext.onclick = function (e) {
                                    e.stopPropagation();
                                    modalNext();
                                };
                                closeGalleryModal.onclick = closeGallery;
                                galleryModal.onclick = function (e) {
                                    if (e.target === galleryModal) closeGallery();
                                };
                                // Modal dots click
                                const modalDots = document.querySelectorAll('.gallery-modal-dot');
                                modalDots.forEach((dot, idx) => {
                                    dot.onclick = function (e) {
                                        e.stopPropagation();
                                        modalIndex = idx;
                                        galleryIndex = idx;
                                        updateGalleryModal(idx);
                                    };
                                });
                                // Swipe support for modal
                                let modalStartX = 0;
                                let modalCurrentX = 0;
                                let modalDragging = false;

                                function onModalDragStart(x) {
                                    modalDragging = true;
                                    modalStartX = x;
                                    modalCurrentX = x;
                                    galleryModalImage.style.transition = 'none';
                                }
                                function onModalDragMove(x) {
                                    if (!modalDragging) return;
                                    modalCurrentX = x;
                                    const deltaX = modalCurrentX - modalStartX;
                                    galleryModalImage.style.transform = `translateX(${deltaX}px)`;
                                }
                                function onModalDragEnd() {
                                    if (!modalDragging) return;
                                    const deltaX = modalCurrentX - modalStartX;
                                    const threshold = 60;
                                    modalDragging = false;
                                    if (deltaX < -threshold) {
                                        galleryModalImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                        galleryModalImage.style.transform = 'translateX(-100%)';
                                        setTimeout(() => {
                                            galleryModalImage.style.transition = 'none';
                                            galleryModalImage.style.transform = 'translateX(100%)';
                                            modalNext();
                                            setTimeout(() => {
                                                galleryModalImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                                galleryModalImage.style.transform = 'translateX(0)';
                                            }, 20);
                                        }, 300);
                                    } else if (deltaX > threshold) {
                                        galleryModalImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                        galleryModalImage.style.transform = 'translateX(100%)';
                                        setTimeout(() => {
                                            galleryModalImage.style.transition = 'none';
                                            galleryModalImage.style.transform = 'translateX(-100%)';
                                            modalPrev();
                                            setTimeout(() => {
                                                galleryModalImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                                galleryModalImage.style.transform = 'translateX(0)';
                                            }, 20);
                                        }, 300);
                                    } else {
                                        galleryModalImage.style.transition = 'transform 0.3s cubic-bezier(0.4,0,0.2,1)';
                                        galleryModalImage.style.transform = 'translateX(0)';
                                    }
                                }
                                // Touch events for modal
                                let modalTouchId = null;
                                galleryModalImage.addEventListener('touchstart', function (e) {
                                    if (e.touches.length === 1) {
                                        modalTouchId = e.touches[0].identifier;
                                        onModalDragStart(e.touches[0].clientX);
                                    }
                                });
                                galleryModalImage.addEventListener('touchmove', function (e) {
                                    if (modalTouchId !== null) {
                                        for (let i = 0; i < e.touches.length; i++) {
                                            if (e.touches[i].identifier === modalTouchId) {
                                                onModalDragMove(e.touches[i].clientX);
                                                break;
                                            }
                                        }
                                    }
                                });
                                galleryModalImage.addEventListener('touchend', function (e) {
                                    onModalDragEnd();
                                    modalTouchId = null;
                                });
                                // Mouse events for modal (optional for desktop)
                                let modalMouseDown = false;
                                galleryModalImage.addEventListener('mousedown', function (e) {
                                    modalMouseDown = true;
                                    onModalDragStart(e.clientX);
                                });
                                galleryModalImage.addEventListener('mousemove', function (e) {
                                    if (modalMouseDown) {
                                        onModalDragMove(e.clientX);
                                    }
                                });
                                galleryModalImage.addEventListener('mouseup', function (e) {
                                    if (modalMouseDown) {
                                        onModalDragEnd();
                                        modalMouseDown = false;
                                    }
                                });
                                galleryModalImage.addEventListener('mouseleave', function (e) {
                                    if (modalMouseDown) {
                                        onModalDragEnd();
                                        modalMouseDown = false;
                                    }
                                });
                                // Prevent image dragging default behavior in modal
                                galleryModalImage.ondragstart = function () { return false; };
                            </script>
                        </div>
                    <?php } ?>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Vibes</h3>
                        <div class="flex flex-wrap gap-6">
                            <?php
                            // Get vibes of the user who posted the listing
                            $vibesList = [];
                            require_once __DIR__ . '/../controllers/UserController.php';
                            $userController = new UserController();
                            // Fetch the profile of the listing owner
                            $listingUserId = isset($listing['user_id']) ? $listing['user_id'] : null;
                            $listingUserProfile = $listingUserId ? $userController->getProfileById($listingUserId) : null;
                            if (!empty($listingUserProfile) && !empty($listingUserProfile['vibes'])) {
                                $vibesIds = array_filter(array_map('trim', preg_split('/\s*\|\s*/', $listingUserProfile['vibes'])));
                                if (!empty($vibesIds)) {
                                    $vibesList = $userController->getVibesByIds($vibesIds);
                                }
                            }
                            if (!empty($vibesList) && is_array($vibesList)) {
                                foreach ($vibesList as $vibe) {
                                    $img = !empty($vibe['image']) ? $vibe['image'] : 'assets/vibes/default.png';
                                    $name = htmlspecialchars($vibe['name'] ?? '');
                                    echo '<div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="' . $img . '" alt="' . $name . '" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">' . $name . '</span></div>';
                                }
                            } else {
                                echo '<span class="text-gray-400 text-sm">No vibes set</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Highlights</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $highlightIds = [];
                            $highlightNames = [];
                            $highlightField = '';
                            if (!empty($listing['property_highlights'])) {
                                $highlightField = $listing['property_highlights'];
                            } elseif (!empty($listing['highlights'])) {
                                $highlightField = is_array($listing['highlights']) ? implode(' | ', $listing['highlights']) : $listing['highlights'];
                            }
                            if (!empty($highlightField)) {
                                $highlightIds = array_filter(array_map('trim', preg_split('/\s*\|\s*|\s*,\s*/', $highlightField)), 'is_numeric');
                            }
                            if (!empty($highlightIds)) {
                                $highlightNames = $userModel->getPropertyHighlightsByIds($highlightIds);
                            }
                            if (!empty($highlightNames)) {
                                foreach ($highlightNames as $highlightName) {
                                    echo '<span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> ' . htmlspecialchars($highlightName) . '</span>';
                                }
                            } else {
                                echo '<span class="text-gray-400 text-sm">No highlights set</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php if (isset($listing['role']) && strtolower($listing['role']) === 'roommate') { ?>
                        <!-- Amenities section hidden for roommate role -->
                    <?php } else { ?>
                        <div class=" border-t pt-8 pb-8">
                            <h3 class="font-semibold text-lg mb-2">Amenities</h3>
                            <div class="flex flex-wrap gap-6">
                                <?php
                                $amenityIds = [];
                                $amenityField = '';
                                if (!empty($listing['amenities'])) {
                                    $amenityField = is_array($listing['amenities']) ? implode(' | ', $listing['amenities']) : $listing['amenities'];
                                }
                                if (!empty($amenityField)) {
                                    $amenityIds = array_filter(array_map('trim', preg_split('/\s*\|\s*|\s*,\s*/', $amenityField)), 'is_numeric');
                                }
                                $amenitiesList = [];
                                if (!empty($amenityIds)) {
                                    $amenitiesList = $userModel->getAmenitiesByIds($amenityIds);
                                }
                                if (!empty($amenitiesList)) {
                                    foreach ($amenitiesList as $amenity) {
                                        $img = !empty($amenity['images']) ? $amenity['images'] : 'assets/amenities/default.png';
                                        $name = htmlspecialchars($amenity['name'] ?? '');
                                        echo '<div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="' . $img . '" alt="' . $name . '" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">' . $name . '</span></div>';
                                    }
                                } else {
                                    echo '<span class="text-gray-400 text-sm">No amenities set</span>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Description</h3>
                        <div class="bg-gray-50 rounded-lg p-4 flex flex-col ">
                            <?php
                            // Check if user is logged in
                            $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
                            if ($isLoggedIn) {
                                echo '<span>' . nl2br(htmlspecialchars($listing['description'] ?? '')) . '</span>';
                            } else {
                                // Blurred/fake description
                                echo '<span style="filter: blur(4px); color: #888;">' . str_repeat('This listing is presented by roomlinks. Please log in to view the full description. ', 3) . '</span>';
                                echo '<span class="flex items-center gap-2 mt-0 ml-4" style="margin-top:0; margin-left:24px;">'
                                    . '<i class="fas fa-exclamation-circle" style="color: #FFD600;"></i>'
                                    . '<span class="text-gray-900 font-medium">Please log in to view the full description.</span>'
                                    . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php if ($isLoggedIn): ?>
                        <div class="flex justify-start items-center gap-2 border-t pt-8 pb-8" id="reportArea">
                            <?php
                            $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
                            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
                            $isOwner = $isLoggedIn && ($_SESSION['user_id'] == $listing['user_id']);
                            $isAdmin = $isLoggedIn && ($userRole === 'admin');
                            if ($isOwner || $isAdmin) {
                                ?>
                                <?php
                                $editUrl = '';
                                if (isset($listing['role']) && strtolower($listing['role']) === 'roommate') {
                                    $editUrl = APP_URL . '/room?id=' . $listingId;
                                } else {
                                    $editUrl = APP_URL . '/roommate?id=' . $listingId;
                                }
                                // Redirect if user is not the owner and tries to access edit link directly
                                $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
                                $isOwner = $isLoggedIn && ($_SESSION['user_id'] == $listing['user_id']);
                                if (isset($_GET['edit']) && $_GET['edit'] == '1' && !$isOwner) {
                                    header('Location: ' . APP_URL . '/listings');
                                    exit;
                                }
                                ?>
                                <a href="<?php echo $editUrl; ?>"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mr-2">Edit</a>
                                <form id="deleteListingForm" method="POST" action="<?php echo APP_URL; ?>/deletelisting"
                                    style="display:inline;">
                                    <input type="hidden" name="listing_id" value="<?php echo $listingId; ?>">
                                    <button type="button" id="deleteListingBtn"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded mr-2">Delete</button>
                                </form>
                                <!-- Delete Confirmation Modal -->
                                <div id="deleteModal"
                                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                                    <div
                                        class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md relative flex flex-col items-center">
                                        <button id="closeDeleteModal"
                                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-3xl leading-none">&times;</button>
                                        <img src="assets/banner/report.png" alt="Alert"
                                            class="w-32 h-32 object-contain mb-2 mt-2" />
                                        <h3 class="font-bold text-xl text-center mb-2 mt-2">Are you sure you want to delete your
                                            listing?</h3>
                                        <p class="text-gray-500 text-center mb-6">This action cannot be undone.</p>
                                        <div class="flex gap-4 mb-2 w-full justify-center">
                                            <button id="confirmDeleteBtn"
                                                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-semibold text-base flex-1">Yes,
                                                Delete</button>
                                            <button id="cancelDeleteBtn"
                                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold text-base flex-1">Cancel</button>
                                        </div>
                                        <div id="deleteLoading" class="hidden flex flex-col items-center justify-center mt-4">
                                            <svg class="animate-spin h-8 w-8 text-red-500 mb-2"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                            <span class="text-red-500 font-medium">Deleting your listing...</span>
                                        </div>
                                        <div id="deleteSuccess" class="hidden flex flex-col items-center justify-center mt-4">
                                            <svg class="w-12 h-12 text-green-500 mb-2" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                                                    fill="none" />
                                                <path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span class="text-green-600 font-semibold text-lg">Listing deleted
                                                successfully!</span>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    // Delete confirmation modal logic
                                    document.getElementById('deleteListingBtn').onclick = function () {
                                        document.getElementById('deleteModal').classList.remove('hidden');
                                    };
                                    document.getElementById('closeDeleteModal').onclick = function () {
                                        document.getElementById('deleteModal').classList.add('hidden');
                                    };
                                    document.getElementById('cancelDeleteBtn').onclick = function () {
                                        document.getElementById('deleteModal').classList.add('hidden');
                                    };
                                    document.getElementById('deleteModal').onclick = function (e) {
                                        if (e.target === this) this.classList.add('hidden');
                                    };
                                    document.getElementById('confirmDeleteBtn').onclick = function () {
                                        // Show loading
                                        document.getElementById('deleteLoading').classList.remove('hidden');
                                        document.getElementById('confirmDeleteBtn').disabled = true;
                                        document.getElementById('cancelDeleteBtn').disabled = true;
                                        // Submit via AJAX
                                        var form = document.getElementById('deleteListingForm');
                                        var formData = new FormData(form);
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST', form.action, true);
                                        xhr.onreadystatechange = function () {
                                            if (xhr.readyState === 4) {
                                                document.getElementById('deleteLoading').classList.add('hidden');
                                                if (xhr.status === 200) {
                                                    // Show success animation
                                                    document.getElementById('deleteSuccess').classList.remove('hidden');
                                                    setTimeout(function () {
                                                        window.location.href = '<?php echo APP_URL; ?>/listings';
                                                    }, 1500);
                                                } else {
                                                    alert('Failed to delete listing.');
                                                    document.getElementById('deleteModal').classList.add('hidden');
                                                }
                                                document.getElementById('confirmDeleteBtn').disabled = false;
                                                document.getElementById('cancelDeleteBtn').disabled = false;
                                            }
                                        };
                                        xhr.send(formData);
                                    };
                                </script>
                            <?php } ?>
                            <button id="reportBtn"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">Report
                                Listing</button>
                        </div>
                    <?php endif; ?>
                    <!-- Report Listing Modal -->
                    <div id="reportModal"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                        <div
                            class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-xl relative flex flex-col items-center">
                            <button id="closeReportModal"
                                class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-3xl leading-none">&times;</button>
                            <img src="assets/banner/report.png" alt="Alert"
                                class="w-48 h-48 object-contain mb-2 mt-2" />
                            <h3 class="font-bold text-2xl text-center mb-2 mt-2">Found Wrong Information?</h3>
                            <p class="text-gray-500 text-center mb-6">Please choose a option from below</p>
                            <div class="flex gap-4 mb-6 w-full justify-center">
                                <?php if (isset($listing['role']) && strtolower($listing['role']) === 'roommate'): ?>
                                    <button id="occupiedBtn"
                                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold text-base flex-1 max-w-xs">Not
                                        Looking Now</button>
                                <?php else: ?>
                                    <button id="occupiedBtn"
                                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold text-base flex-1 max-w-xs">It
                                        is Occupied</button>
                                <?php endif; ?>
                                <button id="wrongInfoBtn"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-6 py-2 rounded-lg font-semibold text-base flex-1 max-w-xs">Wrong
                                    Information</button>
                            </div>
                            <div id="reportLoading"
                                class="hidden w-full flex flex-col items-center justify-center mb-2">
                                <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span class="text-blue-500 font-medium">Submitting your request...</span>
                            </div>
                            <p class="text-xs text-gray-400 text-center mt-2">Note: We'll review this listing and
                                proceed with its removal once we've confirmed the decision with the user who posted it.
                            </p>
                        </div>
                    </div>
                    <script>
                        // Modal open/close logic
                        document.getElementById('reportBtn').onclick = function () {
                            document.getElementById('reportModal').classList.remove('hidden');
                        };
                        document.getElementById('closeReportModal').onclick = function () {
                            document.getElementById('reportModal').classList.add('hidden');
                        };
                        document.getElementById('reportModal').onclick = function (e) {
                            if (e.target === this) this.classList.add('hidden');
                        };

                        function sendReport(queryType) {
                            var loading = document.getElementById('reportLoading');
                            loading.classList.remove('hidden');
                            document.getElementById('occupiedBtn').disabled = true;
                            document.getElementById('wrongInfoBtn').disabled = true;
                            var xhr = new XMLHttpRequest();
                            xhr.open('POST', 'controllers/ReportController.php', true);
                            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4) {
                                    var resp = {};
                                    try { resp = JSON.parse(xhr.responseText); } catch (e) { }
                                    if (xhr.status === 200 && resp.success) {
                                        // Show loading spinner for at least 1 second
                                        setTimeout(function () {
                                            loading.classList.add('hidden');
                                            document.getElementById('reportModal').classList.add('hidden');
                                            document.getElementById('reportArea').innerHTML = `
                                                <div class="w-full flex items-center gap-2 p-4 mt-4">
                                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                    <span class="text-green-600 font-semibold text-lg">Your request has been successfully submitted.</span>
                                                </div>
                                            `;
                                        }, 1000);
                                    } else {
                                        loading.classList.add('hidden');
                                        alert(resp.message || 'Failed to submit report.');
                                        document.getElementById('occupiedBtn').disabled = false;
                                        document.getElementById('wrongInfoBtn').disabled = false;
                                    }
                                }
                            };
                            xhr.send('listing_id=' + encodeURIComponent(<?php echo $listingId; ?>) + '&query=' + encodeURIComponent(queryType));
                        }
                        document.getElementById('occupiedBtn').onclick = function () {
                            <?php if (isset($listing['role']) && strtolower($listing['role']) === 'roommate'): ?>
                                sendReport('NLN');
                            <?php else: ?>
                                sendReport('occupied');
                            <?php endif; ?>
                        };
                        document.getElementById('wrongInfoBtn').onclick = function () { sendReport('wrong info'); };
                    </script>
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- Single Lisiting End -->
    <?php include 'partials/footer.php' ?>
</body>

</html>