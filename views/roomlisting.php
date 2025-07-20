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

    <title>RoomLinks | Offical Website</title>

    <meta name="description"
        content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta name="author" content="RoomLinks" />
    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="RoomLinks | Official Website" />
    <meta property="og:description" content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta property="og:image" content="assets/hero-section/description.png" />
    <meta property="og:url" content="https://roomlinks.in" />
    <meta property="og:type" content="website" />
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="RoomLinks | Official Website" />
    <meta name="twitter:description" content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta name="twitter:image" content="assets/hero-section/description.png" />
    <!-- Canonical URL -->
    <link rel="canonical" href="https://roomlinks.in" />

</head>

<body class="flex flex-col min-h-screen ">
    <?php include 'partials/header.php' ?>
    <!-- Single Listing Start -->
    <div class="w-full flex justify-center">
        <div class="w-full max-w-6xl py-8 px-2 md:px-8 flex md:flex-row flex-col gap-6">
            <!-- Left Sidebar (Desktop Only) -->
            <div class="hidden md:flex md:w-1/4 w-full flex-col gap-4">
                <div class="bg-white rounded-xl shadow-xl shadow-gray-300 p-6 flex flex-col items-center">
                    <img src="assets/profile/user.png" alt="User Avatar" class="w-24 h-24 rounded-full mb-2 border-4 border-gray-200">
                    <h2 class="font-semibold text-lg mb-2">Abhishek Sharma</h2>
                    <div class="flex gap-2 w-full justify-center mt-2">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2"><i class="fab fa-whatsapp"></i> Chat</button>
                        <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded flex items-center gap-2"><i class="fas fa-phone"></i> Call</button>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-xl shadow-xl shadow-gray-300 p-6 mb-6">
                    <div class="mb-4 text-sm text-gray-400">Home / Looking for Roommate / <span class="text-gray-700 font-medium">Abhishek Sharma</span></div>
                    <!-- Tablet/Mobile Profile Top -->
                    <div class="flex flex-col items-center mb-6 md:hidden">
                        <img src="assets/profile/user.png" alt="User Avatar" class="w-24 h-24 rounded-full mb-2 border-4 border-gray-200">
                        <h2 class="font-semibold text-xl mb-2">Abhishek Sharma</h2>
                        <div class="flex gap-2 w-full justify-center mt-2 mb-2 flex-wrap">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded flex items-center gap-2"><i class="fab fa-whatsapp"></i> Chat</button>
                            <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-6 py-2 rounded flex items-center gap-2"><i class="fas fa-phone"></i> Call</button>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600"><i class="fas fa-map-marker-alt"></i> Pune, Maharashtra, India</div>
                    </div>
                    <!-- Desktop Location Row -->
                    <div class="hidden md:block border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-1">Location</h3>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-map-marker-alt"></i> Pune, Maharashtra, India
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Basic Info</h3>
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div>
                                <div class="text-xs text-gray-400">Gender</div>
                                <div class="font-medium flex items-center gap-1"><i class="fas fa-mars"></i> Male</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Approx Rent</div>
                                <div class="font-medium">₹ 7900</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Occupancy</div>
                                <div class="font-medium flex items-center gap-1"><i class="fas fa-bed"></i> Shared</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Looking For</div>
                                <div class="font-medium flex items-center gap-1"><i class="fas fa-mars"></i> Male</div>
                            </div>
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Pictures</h3>
                        <div class="w-full flex justify-center">
                            <div id="galleryContainer" class="relative w-full max-w-full flex flex-col items-center select-none">
                                <button id="galleryPrev" class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10 text-gray-500 hover:bg-gray-100" aria-label="Previous"><i class="fas fa-chevron-left"></i></button>
                                <div id="gallerySlider" class="w-full h-72 overflow-hidden relative bg-gray-100 flex items-center justify-center" style="touch-action: pan-y;">
                                    <img id="galleryImage" src="assets/room-listings/home.png" alt="Room Photo" class="rounded-lg w-full h-72 object-contain transition-transform duration-300 ease-in-out cursor-pointer" style="max-width: 100%; max-height: 18rem; min-height: 18rem;" />
                                </div>
                                <button id="galleryNext" class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10 text-gray-500 hover:bg-gray-100" aria-label="Next"><i class="fas fa-chevron-right"></i></button>
                                <div class="flex gap-1 mt-3">
                                    <span class="gallery-dot w-2 h-2 rounded-full bg-gray-300 inline-block"></span>
                                    <span class="gallery-dot w-2 h-2 rounded-full bg-gray-300 inline-block"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Gallery Modal -->
                        <div id="galleryModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 hidden">
                            <button id="closeGalleryModal" class="absolute top-4 right-4 text-white hover:text-gray-300 text-4xl leading-none">&times;</button>
                            <button id="galleryModalPrev" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-60 text-black rounded-full p-2 text-2xl z-10"><i class="fas fa-chevron-left"></i></button>
                            <img id="galleryModalImage" src="" alt="Gallery Large" class="max-h-[80vh] max-w-[90vw] rounded-xl shadow-2xl object-contain" />
                            <button id="galleryModalNext" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-60 text-black rounded-full p-2 text-2xl z-10"><i class="fas fa-chevron-right"></i></button>
                            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2">
                                <span class="gallery-modal-dot w-3 h-3 rounded-full bg-gray-400 inline-block"></span>
                                <span class="gallery-modal-dot w-3 h-3 rounded-full bg-gray-400 inline-block"></span>
                            </div>
                        </div>
                        <script>
                            const galleryImages = [
                                'assets/room-listings/home.png',
                                'assets/room-listings/home2.png'
                            ];
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

                            document.getElementById('galleryPrev').onclick = function() {
                                if (isTransitioning) return;
                                slideTo(galleryIndex - 1, -1);
                            };
                            document.getElementById('galleryNext').onclick = function() {
                                if (isTransitioning) return;
                                slideTo(galleryIndex + 1, 1);
                            };
                            // Dots click
                            galleryDots.forEach((dot, idx) => {
                                dot.onclick = function() {
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
                            gallerySlider.addEventListener('touchstart', function(e) {
                                if (e.touches.length === 1) {
                                    touchId = e.touches[0].identifier;
                                    onDragStart(e.touches[0].clientX);
                                }
                            });
                            gallerySlider.addEventListener('touchmove', function(e) {
                                if (touchId !== null) {
                                    for (let i = 0; i < e.touches.length; i++) {
                                        if (e.touches[i].identifier === touchId) {
                                            onDragMove(e.touches[i].clientX);
                                            break;
                                        }
                                    }
                                }
                            });
                            gallerySlider.addEventListener('touchend', function(e) {
                                onDragEnd();
                                touchId = null;
                            });
                            // Mouse events
                            let mouseDown = false;
                            gallerySlider.addEventListener('mousedown', function(e) {
                                mouseDown = true;
                                onDragStart(e.clientX);
                            });
                            gallerySlider.addEventListener('mousemove', function(e) {
                                if (mouseDown) {
                                    onDragMove(e.clientX);
                                }
                            });
                            gallerySlider.addEventListener('mouseup', function(e) {
                                if (mouseDown) {
                                    onDragEnd();
                                    mouseDown = false;
                                }
                            });
                            gallerySlider.addEventListener('mouseleave', function(e) {
                                if (mouseDown) {
                                    onDragEnd();
                                    mouseDown = false;
                                }
                            });
                            // Prevent image dragging default behavior
                            if (galleryImage) {
                                galleryImage.ondragstart = function() { return false; };
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
                                galleryImage.onclick = function() {
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
                            galleryModalPrev.onclick = function(e) {
                                e.stopPropagation();
                                modalPrev();
                            };
                            galleryModalNext.onclick = function(e) {
                                e.stopPropagation();
                                modalNext();
                            };
                            closeGalleryModal.onclick = closeGallery;
                            galleryModal.onclick = function(e) {
                                if (e.target === galleryModal) closeGallery();
                            };
                            // Modal dots click
                            const modalDots = document.querySelectorAll('.gallery-modal-dot');
                            modalDots.forEach((dot, idx) => {
                                dot.onclick = function(e) {
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
                            galleryModalImage.addEventListener('touchstart', function(e) {
                                if (e.touches.length === 1) {
                                    modalTouchId = e.touches[0].identifier;
                                    onModalDragStart(e.touches[0].clientX);
                                }
                            });
                            galleryModalImage.addEventListener('touchmove', function(e) {
                                if (modalTouchId !== null) {
                                    for (let i = 0; i < e.touches.length; i++) {
                                        if (e.touches[i].identifier === modalTouchId) {
                                            onModalDragMove(e.touches[i].clientX);
                                            break;
                                        }
                                    }
                                }
                            });
                            galleryModalImage.addEventListener('touchend', function(e) {
                                onModalDragEnd();
                                modalTouchId = null;
                            });
                            // Mouse events for modal (optional for desktop)
                            let modalMouseDown = false;
                            galleryModalImage.addEventListener('mousedown', function(e) {
                                modalMouseDown = true;
                                onModalDragStart(e.clientX);
                            });
                            galleryModalImage.addEventListener('mousemove', function(e) {
                                if (modalMouseDown) {
                                    onModalDragMove(e.clientX);
                                }
                            });
                            galleryModalImage.addEventListener('mouseup', function(e) {
                                if (modalMouseDown) {
                                    onModalDragEnd();
                                    modalMouseDown = false;
                                }
                            });
                            galleryModalImage.addEventListener('mouseleave', function(e) {
                                if (modalMouseDown) {
                                    onModalDragEnd();
                                    modalMouseDown = false;
                                }
                            });
                            // Prevent image dragging default behavior in modal
                            galleryModalImage.ondragstart = function() { return false; };
                        </script>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Vibes</h3>
                        <div class="flex flex-wrap gap-6">
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/early-bird.png" alt="Early Bird" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Early Bird</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/reader.png" alt="Studious" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Studious</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/gym.png" alt="Fitness Freak" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Fitness Freak</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/party-mode.png" alt="Sporty" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Sporty</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/explorer.png" alt="Wanderer" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Wanderer</span></div>
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Highlights</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Attached washroom</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Market nearby</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Attached balcony</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Gated society</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> No Restriction</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> House keeping</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Gym nearby</span>
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Amenities</h3>
                        <div class="flex flex-wrap gap-6">
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/amenities/refrigerator.png" alt="Fridge" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Fridge</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/amenities/cooking-stove.png" alt="Kitchen" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Kitchen</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/amenities/wifi.png" alt="Wifi" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Wifi</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/amenities/washing-machine.png" alt="Machine" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Machine</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/amenities/pet-friendly.png" alt="Cook" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Cook</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/amenities/parking.png" alt="Parking" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Parking</span></div>
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Description</h3>
                        <div class="bg-gray-50 rounded-lg p-4 text-gray-400 flex items-center gap-2">
                            <span>I need a roommate.</span>
                        </div>
                    </div>
                    <div class="flex justify-start border-t pt-8 pb-8" id="reportArea">
                        <button id="reportBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">Report Listing</button>
                    </div>
                    <!-- Report Listing Modal -->
                    <div id="reportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-xl relative flex flex-col items-center">
                            <button id="closeReportModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-3xl leading-none">&times;</button>
                            <img src="assets/banner/report.png" alt="Alert" class="w-48 h-48 object-contain mb-2 mt-2" />
                            <h3 class="font-bold text-2xl text-center mb-2 mt-2">Found Wrong Information?</h3>
                            <p class="text-gray-500 text-center mb-6">Please choose a option from below</p>
                            <div class="flex gap-4 mb-6 w-full justify-center">
                                <button id="occupiedBtn" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold text-base flex-1 max-w-xs">It is Occupied</button>
                                <button id="wrongInfoBtn" class="bg-yellow-400 hover:bg-yellow-500 text-white px-6 py-2 rounded-lg font-semibold text-base flex-1 max-w-xs">Wrong Information</button>
                            </div>
                            <div id="reportLoading" class="hidden w-full flex flex-col items-center justify-center mb-2">
                                <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                <span class="text-blue-500 font-medium">Submitting your request...</span>
                            </div>
                            <p class="text-xs text-gray-400 text-center mt-2">Note: We'll review this listing and proceed with its removal once we've confirmed the decision with the user who posted it.</p>
                        </div>
                    </div>
                    <script>
                        // Modal open/close logic
                        document.getElementById('reportBtn').onclick = function() {
                            document.getElementById('reportModal').classList.remove('hidden');
                        };
                        document.getElementById('closeReportModal').onclick = function() {
                            document.getElementById('reportModal').classList.add('hidden');
                        };
                        document.getElementById('reportModal').onclick = function(e) {
                            if (e.target === this) this.classList.add('hidden');
                        };
                        // Success message logic with loading
                        function showReportSuccess() {
                            // Show loading spinner
                            document.getElementById('reportLoading').classList.remove('hidden');
                            // Disable buttons
                            document.getElementById('occupiedBtn').disabled = true;
                            document.getElementById('wrongInfoBtn').disabled = true;
                            setTimeout(function() {
                                document.getElementById('reportModal').classList.add('hidden');
                                document.getElementById('reportLoading').classList.add('hidden');
                                document.getElementById('reportArea').innerHTML = `
                                    <div class="w-full flex items-center gap-2 p-4 mt-4">
                                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <span class="text-green-600 font-semibold text-lg">Your request has been successfully submitted.</span>
                                    </div>
                                `;
                            }, 1000);
                        }
                        document.getElementById('occupiedBtn').onclick = showReportSuccess;
                        document.getElementById('wrongInfoBtn').onclick = showReportSuccess;
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- Single Lisiting End -->
    <?php include 'partials/footer.php' ?>
</body>
</html>