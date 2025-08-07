-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2025 at 09:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rooms`
--

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `images` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `images`, `created_at`, `updated_at`, `name`) VALUES
(11, 'assets/amenities/AC_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'AC'),
(12, 'assets/amenities/Bed_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Bed'),
(13, 'assets/amenities/Cooking_Stove_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Cooking Stove'),
(14, 'assets/amenities/Elevator_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Elevator'),
(15, 'assets/amenities/Geyser_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Geyser'),
(16, 'assets/amenities/Mattres_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Mattres'),
(17, 'assets/amenities/Microwave_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Microwave'),
(18, 'assets/amenities/Praking_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Praking'),
(19, 'assets/amenities/Pet_Friendly_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Pet Friendly'),
(20, 'assets/amenities/Power_Backup_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Power Backup'),
(21, 'assets/amenities/Refrigerator_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Refrigerator'),
(22, 'assets/amenities/Guard_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Guard'),
(23, 'assets/amenities/Study_Desk_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Study Desk'),
(24, 'assets/amenities/wifi_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'wifi'),
(25, 'assets/amenities/Washing_Machine_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Washing Machine'),
(26, 'assets/amenities/Vacuum_Cleanner_20250731224037.png', '2025-07-31 20:40:37', '2025-07-31 20:40:37', 'Vacuum Cleanner');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_role` varchar(50) NOT NULL,
  `operation` varchar(10) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `user_role`, `operation`, `table_name`, `record_id`, `details`, `ip_address`, `created_at`) VALUES
(3, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #1\"}}', '::1', '2025-08-01 01:15:11'),
(4, 1, 'admin', 'CREATE', 'locations', NULL, '{\"action\":\"addLocations\",\"locations\":[\"pune\",\"ahilyanagar\"]}', '::1', '2025-08-01 01:27:20'),
(5, 1, 'admin', 'CREATE', 'locations', NULL, '{\"action\":\"addLocations\",\"locations\":[\"pune\"]}', '::1', '2025-08-01 01:28:47'),
(6, 1, 'admin', 'CREATE', 'locations', NULL, '{\"action\":\"addLocations\",\"locations\":[\"om\"]}', '::1', '2025-08-01 01:32:25'),
(7, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"om\"}', '::1', '2025-08-01 01:33:28'),
(8, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"om1\"}', '::1', '2025-08-01 01:33:28'),
(9, 1, 'admin', 'DELETE', 'locations', 5, '{\"action\":\"deleteLocation\",\"locationId\":5}', '::1', '2025-08-01 01:39:59'),
(10, 1, 'admin', 'DELETE', 'property_highlights', 9, '{\"action\":\"deleteHighlight\",\"highlightId\":9}', '::1', '2025-08-01 01:42:10'),
(11, 1, 'admin', 'DELETE', 'property_highlights', 10, '{\"action\":\"deleteHighlight\",\"highlightId\":10}', '::1', '2025-08-01 01:42:18'),
(12, 1, 'admin', 'DELETE', 'locations', 3, '{\"action\":\"deleteLocation\",\"locationId\":3}', '::1', '2025-08-01 01:47:17'),
(13, 1, 'admin', 'CREATE', 'locations', NULL, '{\"action\":\"addLocations\",\"locations\":[\"om\"]}', '::1', '2025-08-01 01:59:44'),
(14, 1, 'admin', 'DELETE', 'locations', 6, '{\"action\":\"deleteLocation\",\"locationId\":6}', '::1', '2025-08-01 02:00:46'),
(15, 1, 'admin', 'CREATE', 'locations', NULL, '{\"action\":\"addLocations\",\"locations\":[\"ahilyanagar\"]}', '::1', '2025-08-01 02:01:06'),
(16, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Adventurous: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(17, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Ambitious: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(18, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Chai Lover: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(19, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Chill: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(20, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Cinephile: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(21, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Cleanliness: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(22, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Coffee Preson: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(23, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Creative: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(24, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Early Bird: Vibe added successfully!\"}}', '::1', '2025-08-01 02:04:10'),
(25, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Explorer: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(26, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Foodie: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(27, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Gamer: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(28, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Friendly: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(29, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Gym: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(30, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Night Owl: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(31, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Party Mode: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(32, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Pet Friendly: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(33, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Books: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(34, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Student: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(35, 1, 'admin', 'CREATE', 'vibes', NULL, '{\"action\":\"addVibes\",\"vibeData\":{\"success\":true,\"message\":\"Introvert: Vibe added successfully!\"}}', '::1', '2025-08-01 02:06:54'),
(36, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"AC: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(37, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Bed: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(38, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Cooking Stove: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(39, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Elevator: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(40, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Geyser: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(41, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Mattres: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(42, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Microwave: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(43, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Praking: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(44, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Pet Friendly: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(45, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Power Backup: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(46, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Refrigerator: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(47, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Guard: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(48, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Study Desk: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(49, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"wifi: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(50, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Washing Machine: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(51, 1, 'admin', 'CREATE', 'amenities', NULL, '{\"action\":\"addNewAmenities\",\"amenityData\":{\"success\":true,\"message\":\"Vacuum Cleanner: Amenity added successfully!\"}}', '::1', '2025-08-01 02:10:37'),
(52, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Fully Furnished\"}', '::1', '2025-08-01 02:17:01'),
(53, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Housekeeping Included\"}', '::1', '2025-08-01 02:17:01'),
(54, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"24\\/7 Water Supply\"}', '::1', '2025-08-01 02:17:01'),
(55, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Prime Location\"}', '::1', '2025-08-01 02:17:01'),
(56, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Access to Gym\"}', '::1', '2025-08-01 02:17:01'),
(57, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Roommate Friendly\"}', '::1', '2025-08-01 02:17:01'),
(58, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Private Entry\"}', '::1', '2025-08-01 02:17:01'),
(59, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Well-Connected Transport\"}', '::1', '2025-08-01 02:17:01'),
(60, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Cooking Allowed\"}', '::1', '2025-08-01 02:17:01'),
(61, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"West Facing Unit\"}', '::1', '2025-08-01 02:17:01'),
(62, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"East Facing Unit\"}', '::1', '2025-08-01 02:17:01'),
(63, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"No Brokerage\"}', '::1', '2025-08-01 02:17:01'),
(64, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Walking Distance to Market\"}', '::1', '2025-08-01 02:17:01'),
(65, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Verified Tenants Only\"}', '::1', '2025-08-01 02:17:01'),
(66, 1, 'admin', 'CREATE', 'property_highlights', NULL, '{\"action\":\"addPropertyHighlight\",\"highlightName\":\"Quiet Hours Policy\"}', '::1', '2025-08-01 02:17:01'),
(67, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #1\"}}', '::1', '2025-08-01 03:06:29'),
(68, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #2\"}}', '::1', '2025-08-01 03:06:29'),
(69, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #3\"}}', '::1', '2025-08-01 03:06:29'),
(70, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #4\"}}', '::1', '2025-08-01 03:06:29'),
(71, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #5\"}}', '::1', '2025-08-01 03:06:29'),
(72, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #6\"}}', '::1', '2025-08-01 03:06:29'),
(73, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #7\"}}', '::1', '2025-08-01 03:06:29'),
(74, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #8\"}}', '::1', '2025-08-01 03:06:29'),
(75, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #9\"}}', '::1', '2025-08-01 03:06:29'),
(76, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #10\"}}', '::1', '2025-08-01 03:06:29'),
(77, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #11\"}}', '::1', '2025-08-01 03:06:29'),
(78, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #12\"}}', '::1', '2025-08-01 03:06:29'),
(79, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #13\"}}', '::1', '2025-08-01 03:06:29'),
(80, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #14\"}}', '::1', '2025-08-01 03:06:29'),
(81, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #15\"}}', '::1', '2025-08-01 03:06:29'),
(82, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #16\"}}', '::1', '2025-08-01 03:06:29'),
(83, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #17\"}}', '::1', '2025-08-01 03:06:29'),
(84, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #18\"}}', '::1', '2025-08-01 03:06:29'),
(85, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #19\"}}', '::1', '2025-08-01 03:06:29'),
(86, 1, 'admin', 'CREATE', 'profile_pictures', NULL, '{\"action\":\"addProfilePicture\",\"profilePictureData\":{\"success\":true,\"message\":\"Profile picture added successfully for entry #20\"}}', '::1', '2025-08-01 03:06:29'),
(87, 1, 'admin', 'DELETE', 'profile_pictures', 39, '{\"action\":\"deleteProfilePicture\",\"profilePictureId\":\"39\"}', '::1', '2025-08-01 03:06:50'),
(88, 71, 'user', 'DELETE', 'listings', 50, '{\"action\":\"deleteListing\",\"listingId\":\"50\"}', '::1', '2025-08-01 03:22:22'),
(89, 71, 'user', 'DELETE', 'listings', 49, '{\"action\":\"deleteListing\",\"listingId\":\"49\"}', '::1', '2025-08-01 03:22:28'),
(90, 71, 'user', 'CREATE', 'roommatelisting', 52, '{\"action\":\"addroommatelisting\",\"roommateId\":52,\"location\":\"wagholi, Pune\",\"rent\":\"5000\",\"gender\":\"female\",\"occupancy\":\"single\",\"highlights\":\"13 | 19 | 21 | 12 | 14\",\"contact\":\"call | whatsapp\",\"description\":\"Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional \\/ MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\\r\\n\\r\\nMy budget is around \\u20b95000, and I\'m ideally looking for a place that\\u2019s close to public transport, grocery stores, and basic amenities. Open to both shared flats or private rooms, preferably with like-minded and friendly roommates.\"}', '::1', '2025-08-01 03:24:03'),
(91, 72, 'user', 'CREATE', 'roommatelisting', 54, '{\"action\":\"addroommatelisting\",\"roommateId\":54,\"location\":\"Banner\",\"rent\":\"7000\",\"gender\":\"male\",\"occupancy\":\"single\",\"highlights\":\"13 | 15 | 19 | 12 | 22 | 18\",\"contact\":\"call | whatsapp\",\"description\":\"Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional \\/ MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\\r\\n\\r\\nMy budget is around \\u20b9[your budget], and I\'m ideally looking for a place that\\u2019s close to public transport, grocery stores, and basic amenities. Open to both shared flats or private rooms, preferably with like-minded and friendly roommates.\"}', '::1', '2025-08-01 03:28:45'),
(92, 76, 'user', 'CREATE', 'roommatelisting', 58, '{\"action\":\"addroommatelisting\",\"roommateId\":58,\"location\":\"Viman Nagar, Pune\",\"rent\":\"20000\",\"gender\":\"female\",\"occupancy\":\"any\",\"highlights\":\"19 | 21 | 12 | 22 | 14 | 25 | 16\",\"contact\":\"call | email\",\"description\":\"Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional \\/ MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\\r\\n\\r\\nMy budget is around \\u20b9[your budget], and I\'m ideally looking for a place that\\u2019s close to public transport, grocery stores, and basic amenities. Open to both shared flats or private rooms, preferably with like-minded and friendly roommates.\"}', '::1', '2025-08-01 03:39:05'),
(93, 78, 'user', 'CREATE', 'roommatelisting', 60, '{\"action\":\"addroommatelisting\",\"roommateId\":60,\"location\":\"Hadapsar, Pune\",\"rent\":\"8000\",\"gender\":\"female\",\"occupancy\":\"any\",\"highlights\":\"12 | 22 | 24 | 18 | 20\",\"contact\":\"call | whatsapp\",\"description\":\"Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional \\/ MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\"}', '::1', '2025-08-01 03:43:18'),
(94, 82, 'user', 'CREATE', 'roommatelisting', 64, '{\"action\":\"addroommatelisting\",\"roommateId\":64,\"location\":\"MIDC Area, Ahilyanagar\",\"rent\":\"7000\",\"gender\":\"male\",\"occupancy\":\"any\",\"highlights\":\"19 | 11 | 16\",\"contact\":\"call | email\",\"description\":\"Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional \\/ MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\"}', '::1', '2025-08-01 12:08:10'),
(95, 83, 'user', 'CREATE', 'roommatelisting', 66, '{\"action\":\"addroommatelisting\",\"roommateId\":66,\"location\":\"Bhingar (Bhingar Camp), Ahilyanagar\",\"rent\":\"7000\",\"gender\":\"any\",\"occupancy\":\"single\",\"highlights\":\"21 | 12 | 25 | 16\",\"contact\":\"call | whatsapp\",\"description\":\"Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional \\/ MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\"}', '::1', '2025-08-01 12:11:48'),
(96, 90, 'user', 'DELETE', 'user', 90, '{\"action\":\"deleteUser\",\"userId\":\"90\"}', '::1', '2025-08-01 12:22:53'),
(97, 93, '', 'DELETE', 'user', 93, '{\"action\":\"deleteUser\",\"userId\":\"93\"}', '::1', '2025-08-01 12:36:28');

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` varchar(1200) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'riya sharma', 'riya.sharma@email.com', '6554654665', 'feature', 'Hi, I’m trying to list a room in Kothrud but facing issues uploading pictures. Can someone from the team help me complete the listing?', '2025-08-01 12:38:56'),
(2, 'Arjun Mehta', 'arjun.mehta@email.com', '3511321345', 'feature', 'RoomLinks is great! It would be awesome if you could add a chat feature so we can talk to potential roommates directly on the platform.', '2025-08-01 12:39:26'),
(3, 'Snehal Patil', 'snehal.patil@email.com', '2434341346', 'problem', 'I submitted my ID for profile verification two days ago but haven’t received any update yet. Please let me know the status.', '2025-08-01 12:39:48');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('reported','proccessing','resolved') NOT NULL DEFAULT 'reported',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `subject`, `feedback`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(4, 'General', 'I recently used RoomLinks to search for a roommate in Pune, and the experience was smooth and hassle-free. The interface is user-friendly, and I loved the filters that helped narrow down my preferences.\r\n\r\nOne small suggestion — it would be great to add a verification badge for profiles to build more trust. Overall, I found a roommate within a week, and I’m very happy with the service!', 86, 'reported', '2025-08-01 06:47:00', '2025-08-01 06:47:00'),
(5, 'Roommate Experience', 'I moved to Pune for work and was struggling to find a decent room in my budget. RoomLinks made it super easy — I found a place in Kothrud with great amenities and a friendly flatmate.\r\n\r\nThe listing details were accurate, and the contact process was smooth. Definitely better than searching randomly on WhatsApp groups!', 87, 'reported', '2025-08-01 06:48:28', '2025-08-01 06:48:28'),
(6, 'Suggestions', 'The platform is really helpful, but it would be even better if you could add more detailed filters — like preferences for vegetarian/non-vegetarian roommates, working professionals vs. students, or even quiet vs. social flatmates.\r\n\r\nThis would help users find more compatible living partners and avoid future conflicts. Looking forward to seeing this in a future update!', 88, 'reported', '2025-08-01 06:49:36', '2025-08-01 06:49:36'),
(7, 'Other', 'I really liked using RoomLinks — it’s simple and does what it promises. I was able to shortlist a few rooms in Viman Nagar and got quick responses.\r\n\r\nOne thing I noticed: sometimes the map doesn’t load properly on mobile. If that gets fixed, it’ll be perfect. Great initiative overall!', 89, 'reported', '2025-08-01 06:50:42', '2025-08-01 06:50:42');

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

CREATE TABLE `listing` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `rent` decimal(10,2) NOT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `occupancy` varchar(250) DEFAULT NULL,
  `propertyhighlights` text DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `contact` text DEFAULT NULL,
  `role` enum('room','roommate','pg','tenant') NOT NULL DEFAULT 'room',
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `image5` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`id`, `user_id`, `location`, `rent`, `gender`, `occupancy`, `propertyhighlights`, `amenities`, `description`, `contact`, `role`, `image1`, `image2`, `image3`, `image4`, `image5`, `created_at`, `updated_at`) VALUES
(51, 71, 'Sus, Pune', 9000.00, 'any', 'single', '13 | 12 | 22 | 14 | 25 | 16 | 24 | 20', '12 | 13 | 14 | 16 | 17 | 19 | 25', 'Spacious Private Room Available in 2BHK Flat – Ideal for Working Professionals or Students\r\n\r\nLooking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.', 'call | whatsapp', 'room', 'assets/listings/71__202507/688be55b3225a_img4.jpeg', 'assets/listings/71__202507/688be55b32390_img3.jpeg', 'assets/listings/71__202507/688be55b32470_img2.jpeg', 'assets/listings/71__202507/688be55b34062_img1.jpeg', '', '2025-07-31 21:51:23', '2025-07-31 21:51:23'),
(52, 71, 'wagholi, Pune', 5000.00, 'female', 'single', '13 | 19 | 21 | 12 | 14', NULL, 'Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional / MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\r\n\r\nMy budget is around ₹5000, and I\'m ideally looking for a place that’s close to public transport, grocery stores, and basic amenities. Open to both shared flats or private rooms, preferably with like-minded and friendly roommates.', 'call | whatsapp', 'roommate', NULL, NULL, NULL, NULL, NULL, '2025-07-31 21:54:03', '2025-07-31 21:54:03'),
(53, 72, 'Kothrud, Pune', 15000.00, 'male', 'shared', '15 | 12 | 22 | 25 | 23 | 18', '12 | 13 | 14 | 18 | 21 | 23 | 26 | 25', 'Spacious Private Room Available in 2BHK Flat – Ideal for Working Professionals or Students\r\n\r\nLooking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | whatsapp', 'room', 'assets/listings/72__202507/688be6e8cbc31_img3.jpg', 'assets/listings/72__202507/688be6e8cbd1d_img2.jpg', 'assets/listings/72__202507/688be6e8cbdfc_img1.jpg', '', '', '2025-07-31 21:58:00', '2025-07-31 21:58:00'),
(54, 72, 'Banner', 7000.00, 'male', 'single', '13 | 15 | 19 | 12 | 22 | 18', NULL, 'Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional / MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\r\n\r\nMy budget is around ₹[your budget], and I\'m ideally looking for a place that’s close to public transport, grocery stores, and basic amenities. Open to both shared flats or private rooms, preferably with like-minded and friendly roommates.', 'call | whatsapp', 'roommate', NULL, NULL, NULL, NULL, NULL, '2025-07-31 21:58:45', '2025-07-31 21:58:45'),
(55, 73, 'Baner, Pune', 18000.00, 'any', 'single', '13 | 15 | 12 | 22 | 17 | 16 | 23 | 18', '12 | 14 | 22 | 18 | 21 | 26 | 25 | 24', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | whatsapp', 'tenant', 'assets/listings/73__202508/688be7cc1f9f5_img6.jpg', 'assets/listings/73__202508/688be7cc1fb2d_img5.jpg', 'assets/listings/73__202508/688be7cc1fca0_img4.jpg', '', '', '2025-07-31 22:01:48', '2025-07-31 22:01:48'),
(56, 74, 'Wakad', 9000.00, 'female', 'shared', '13 | 15 | 19 | 11 | 12 | 22 | 14 | 25 | 16', '12 | 13 | 14 | 15 | 22 | 16 | 17 | 23 | 26 | 24', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | email', 'pg', 'assets/listings/74__202508/688be87347231_img9.jpg', 'assets/listings/74__202508/688be873473d0_img8.jpg', 'assets/listings/74__202508/688be873474da_img7.jpg', '', '', '2025-07-31 22:04:35', '2025-07-31 22:04:35'),
(57, 75, 'Hinjewadi, Pune', 8000.00, 'male', 'shared', '13 | 15 | 12 | 16', '13 | 14 | 15 | 17 | 19 | 20 | 18 | 26 | 25', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | email', 'room', 'assets/listings/75__202508/688be911152b5_img12.jpg', 'assets/listings/75__202508/688be9111541e_img11.jpg', 'assets/listings/75__202508/688be9111561e_img10.jpg', '', '', '2025-07-31 22:07:13', '2025-07-31 22:07:13'),
(58, 76, 'Viman Nagar, Pune', 20000.00, 'female', 'any', '19 | 21 | 12 | 22 | 14 | 25 | 16', NULL, 'Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional / MCA student], easygoing, respectful, and responsible when it comes to shared spaces.\r\n\r\nMy budget is around ₹[your budget], and I\'m ideally looking for a place that’s close to public transport, grocery stores, and basic amenities. Open to both shared flats or private rooms, preferably with like-minded and friendly roommates.', 'call | email', 'roommate', NULL, NULL, NULL, NULL, NULL, '2025-07-31 22:09:05', '2025-07-31 22:09:05'),
(59, 77, 'Koregaon Park, Pune', 30000.00, 'female', 'shared', '13 | 15 | 21 | 14 | 16 | 24', '11 | 12 | 13 | 22 | 17 | 20 | 26 | 25 | 24', 'The apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | whatsapp', 'tenant', 'assets/listings/77__202508/688bea1953bb1_img15.jpg', 'assets/listings/77__202508/688bea1953d3f_img14.jpg', 'assets/listings/77__202508/688bea1953e70_img13.jpg', '', '', '2025-07-31 22:11:37', '2025-07-31 22:11:37'),
(60, 78, 'Hadapsar, Pune', 8000.00, 'female', 'any', '12 | 22 | 24 | 18 | 20', NULL, 'Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional / MCA student], easygoing, respectful, and responsible when it comes to shared spaces.', 'call | whatsapp', 'roommate', NULL, NULL, NULL, NULL, NULL, '2025-07-31 22:13:18', '2025-07-31 22:13:18'),
(61, 79, 'Camp (MG Road), Pune', 10000.00, 'any', 'any', '15 | 19 | 22 | 14 | 25 | 16 | 24', '13 | 14 | 15 | 17 | 19 | 20 | 18 | 23 | 26 | 24', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | email', 'tenant', 'assets/listings/79__202508/688beb317dc11_img18.jpg', 'assets/listings/79__202508/688beb317dea1_img17.jpg', 'assets/listings/79__202508/688beb317e0b9_img16.jpg', '', '', '2025-07-31 22:16:17', '2025-07-31 22:16:17'),
(62, 80, 'Savedi, Ahmednagar', 6000.00, 'male', 'shared', '13 | 15 | 12', '12 | 13 | 14 | 25 | 24', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | whatsapp', 'room', 'assets/listings/80__202508/688c5f6340733_img18.jpg', 'assets/listings/80__202508/688c5f6340a40_img17.jpg', 'assets/listings/80__202508/688c5f6340bc6_img16.jpg', '', '', '2025-08-01 06:32:03', '2025-08-01 06:32:03'),
(63, 81, 'Sarasnagar, Ahilyanagar', 8000.00, 'any', 'single', '15 | 11 | 22 | 25 | 16', '13 | 14 | 16 | 17 | 26', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | whatsapp', 'tenant', 'assets/listings/81__202508/688c605e7fc74_img15.jpg', 'assets/listings/81__202508/688c605e7fe71_img14.jpg', 'assets/listings/81__202508/688c605e80002_img13.jpg', '', '', '2025-08-01 06:36:14', '2025-08-01 06:36:14'),
(64, 82, 'MIDC Area, Ahilyanagar', 7000.00, 'male', 'any', '19 | 11 | 16', NULL, 'Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional / MCA student], easygoing, respectful, and responsible when it comes to shared spaces.', 'call | email', 'roommate', NULL, NULL, NULL, NULL, NULL, '2025-08-01 06:38:10', '2025-08-01 06:38:10'),
(65, 83, 'Pipeline Road, Ahilyanagar', 9000.00, 'any', 'any', '15 | 12 | 22', '13 | 22 | 16 | 20 | 26', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | whatsapp', 'room', 'assets/listings/83__202508/688c614fa5922_img14.jpg', 'assets/listings/83__202508/688c614fa5b3b_img13.jpg', 'assets/listings/83__202508/688c614fa5dfc_img12.jpg', '', '', '2025-08-01 06:40:15', '2025-08-01 06:40:15'),
(66, 83, 'Bhingar (Bhingar Camp), Ahilyanagar', 7000.00, 'any', 'single', '21 | 12 | 25 | 16', NULL, 'Hi! I\'m looking for a clean and comfortable room to rent in [Preferred Location, e.g., Kothrud, Pune]. I\'m a [your profession or student status, e.g., working professional / MCA student], easygoing, respectful, and responsible when it comes to shared spaces.', 'call | whatsapp', 'roommate', NULL, NULL, NULL, NULL, NULL, '2025-08-01 06:41:48', '2025-08-01 06:41:48'),
(67, 84, 'Tarakpur, Ahilyanagar', 9800.00, 'any', 'any', '12 | 25 | 16 | 23', '12 | 13 | 15 | 20 | 23', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | whatsapp', 'tenant', 'assets/listings/84__202508/688c621caea56_img13.jpg', 'assets/listings/84__202508/688c621caecc4_img3.jpg', 'assets/listings/84__202508/688c621caee8d_img2.jpg', '', '', '2025-08-01 06:43:40', '2025-08-01 06:43:40'),
(68, 85, 'Maliwada, Ahilyanagar', 7000.00, 'any', 'shared', '15 | 11 | 25 | 16 | 20', '12 | 13 | 15 | 22 | 18 | 21', 'Looking for a clean, responsible, and friendly roommate to share a well-maintained 2BHK apartment in [Location Name, e.g., Baner, Pune]. The room is fully furnished with a bed, wardrobe, and study table. You\'ll have access to a shared kitchen, living room, balcony, and two bathrooms.\r\n\r\nThe apartment is in a gated society with 24/7 security, lift, parking, and amenities like a gym and garden. It\'s located close to major IT parks, grocery stores, cafes, and public transport.', 'call | whatsapp', 'tenant', 'assets/listings/85__202508/688c628c062b5_img7.jpg', 'assets/listings/85__202508/688c628c064bf_img6.jpg', 'assets/listings/85__202508/688c628c0668b_img5.jpg', '', '', '2025-08-01 06:45:32', '2025-08-01 06:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location`, `created_at`, `updated_at`) VALUES
(2, 'pune', '2025-07-31 19:57:20', '2025-07-31 19:57:20'),
(7, 'ahilyanagar', '2025-07-31 20:31:06', '2025-07-31 20:31:06');

-- --------------------------------------------------------

--
-- Table structure for table `massage`
--

CREATE TABLE `massage` (
  `id` int(11) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `massage` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `who_are_you` varchar(100) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `avatar_id` int(11) DEFAULT NULL,
  `uploaded_picture` varchar(255) DEFAULT NULL,
  `vibes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `name`, `phone`, `who_are_you`, `gender`, `location`, `avatar_id`, `uploaded_picture`, `vibes`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '9623908967', 'flat_owner', 'male', NULL, 19, NULL, NULL, '2025-07-31 19:46:17', '2025-07-31 19:46:17'),
(71, 'Om Phopse', '9623908967', 'roommate', 'male', '2', 24, NULL, '30 | 15 | 16 | 21 | 22', '2025-07-31 21:38:35', '2025-08-01 07:27:01'),
(72, 'Rohit Sharma', '9623908967', 'roommate', 'male', '2', 25, NULL, '30 | 15 | 18 | 19 | 25 | 29 | 31', '2025-07-31 21:55:30', '2025-08-01 07:27:04'),
(73, 'Aman Verma', '9623908967', 'flat_owner', 'male', '2', NULL, 'assets/profile/profilepictures/August_2025/73_20250801000011.png', '30 | 15 | 20 | 21 | 26', '2025-07-31 22:00:11', '2025-08-01 07:27:06'),
(74, 'Karthik Reddy', '9623908967', 'pg_owner ', 'female', '2', 34, NULL, '30 | 15 | 16 | 19 | 20 | 22', '2025-07-31 22:02:31', '2025-08-01 07:27:08'),
(75, 'Vikram Joshi', '9623908967', 'roommate', 'male', '2', 23, NULL, '14 | 30 | 15 | 19 | 20 | 21', '2025-07-31 22:05:51', '2025-08-01 07:27:11'),
(76, 'Priya Mehta', '9623908967', 'roommate', 'female', '2', 31, NULL, '30 | 16 | 17 | 24 | 28 | 29', '2025-07-31 22:08:13', '2025-08-01 07:27:13'),
(77, 'Sneha Patil', '9623908967', 'flat_owner', 'female', '2', 29, NULL, '13 | 14 | 15 | 28 | 31', '2025-07-31 22:09:56', '2025-08-01 07:27:15'),
(78, 'Anjali Sharma', '9623908967', 'roommate', 'female', '2', 30, NULL, '15 | 16 | 20 | 21 | 22 | 26', '2025-07-31 22:12:27', '2025-08-01 07:27:17'),
(79, 'Neha Kulkarni', '9623908967', 'flat_owner', 'female', '2', 37, NULL, '30 | 15 | 16 | 22 | 23 | 28', '2025-07-31 22:14:12', '2025-08-01 07:27:21'),
(80, 'Aarav', '9623908967', 'roommate', 'male', '7', 27, NULL, '30 | 15 | 19 | 20 | 21 | 24', '2025-08-01 06:28:47', '2025-08-01 07:27:23'),
(81, 'Priya', '9623908967', 'flat_owner', 'female', '7', 32, NULL, '15 | 19 | 21 | 22 | 26 | 27', '2025-08-01 06:33:13', '2025-08-01 07:27:28'),
(82, 'Rahul ', '9623908967', 'roommate', 'male', '7', 28, NULL, '14 | 30 | 21 | 22 | 23', '2025-08-01 06:37:19', '2025-08-01 07:27:30'),
(83, 'Sneha', '9623908967', 'roommate', 'female', '7', 35, NULL, '13 | 14 | 15 | 16 | 19 | 24 | 32 | 27', '2025-08-01 06:39:04', '2025-08-01 07:27:33'),
(84, 'Vivaan', '9623908967', 'flat_owner', 'female', '7', 34, NULL, '16 | 17 | 18 | 23 | 24', '2025-08-01 06:42:31', '2025-08-01 07:27:36'),
(85, 'Ananya', '9623908967', 'flat_owner', 'female', '7', 38, NULL, '13 | 14 | 22 | 25 | 27', '2025-08-01 06:44:23', '2025-08-01 07:27:38'),
(86, 'Rohan', '9623908967', 'roommate', 'male', '2', 26, NULL, '13 | 30 | 15 | 19 | 20 | 21', '2025-08-01 06:46:07', '2025-08-01 07:27:41'),
(87, 'Isha@gmail.com', '9623908967', 'roommate', 'female', '7', 30, NULL, '15 | 16 | 20 | 22 | 31', '2025-08-01 06:47:49', '2025-08-01 07:27:44'),
(88, 'Karan', '9623908967', 'roommate', 'male', '2', 22, NULL, '13 | 20 | 21 | 24 | 32', '2025-08-01 06:49:01', '2025-08-01 07:27:46'),
(89, 'Meera', '9623908967', 'roommate', 'female', '7', 37, NULL, '13 | 14 | 15 | 19 | 21 | 22 | 32', '2025-08-01 06:50:10', '2025-08-01 07:27:50');

-- --------------------------------------------------------

--
-- Table structure for table `profile_pictures`
--

CREATE TABLE `profile_pictures` (
  `id` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_pictures`
--

INSERT INTO `profile_pictures` (`id`, `gender`, `image`, `created_at`) VALUES
(19, 'men', 'assets/profile/men/profile_688bc7c747ef58.53184066.png', '2025-07-31 19:45:11'),
(20, 'men', 'assets/profile/men/profile_688be1dd168019.98121510.jpg', '2025-07-31 21:36:29'),
(21, 'men', 'assets/profile/men/profile_688be1dd179851.95057746.jpg', '2025-07-31 21:36:29'),
(22, 'men', 'assets/profile/men/profile_688be1dd186066.26069939.jpg', '2025-07-31 21:36:29'),
(23, 'men', 'assets/profile/men/profile_688be1dd1921c5.08193532.jpg', '2025-07-31 21:36:29'),
(24, 'men', 'assets/profile/men/profile_688be1dd19dda0.94901546.jpg', '2025-07-31 21:36:29'),
(25, 'men', 'assets/profile/men/profile_688be1dd1aaf41.21772756.jpg', '2025-07-31 21:36:29'),
(26, 'men', 'assets/profile/men/profile_688be1dd1e7957.16667782.jpg', '2025-07-31 21:36:29'),
(27, 'men', 'assets/profile/men/profile_688be1dd204274.66519734.jpg', '2025-07-31 21:36:29'),
(28, 'men', 'assets/profile/men/profile_688be1dd2247a0.47101946.jpg', '2025-07-31 21:36:29'),
(29, 'women', 'assets/profile/women/profile_688be1dd243186.22439780.jpg', '2025-07-31 21:36:29'),
(30, 'women', 'assets/profile/women/profile_688be1dd25bf32.98798107.jpg', '2025-07-31 21:36:29'),
(31, 'women', 'assets/profile/women/profile_688be1dd275e07.08432101.jpg', '2025-07-31 21:36:29'),
(32, 'women', 'assets/profile/women/profile_688be1dd2dd457.71022881.jpg', '2025-07-31 21:36:29'),
(33, 'women', 'assets/profile/women/profile_688be1dd356772.37582904.jpg', '2025-07-31 21:36:29'),
(34, 'women', 'assets/profile/women/profile_688be1dd3722a1.77594076.jpg', '2025-07-31 21:36:29'),
(35, 'women', 'assets/profile/women/profile_688be1dd3cf4e4.56282866.jpg', '2025-07-31 21:36:29'),
(36, 'women', 'assets/profile/women/profile_688be1dd3eaf45.29912400.jpg', '2025-07-31 21:36:29'),
(37, 'women', 'assets/profile/women/profile_688be1dd4061a1.24034289.jpg', '2025-07-31 21:36:29'),
(38, 'women', 'assets/profile/women/profile_688be1dd422223.63292195.jpg', '2025-07-31 21:36:29');

-- --------------------------------------------------------

--
-- Table structure for table `property_highlights`
--

CREATE TABLE `property_highlights` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `property_highlights`
--

INSERT INTO `property_highlights` (`id`, `name`, `created_at`, `updated_at`) VALUES
(11, 'Fully Furnished', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(12, 'Housekeeping Included', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(13, '24/7 Water Supply', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(14, 'Prime Location', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(15, 'Access to Gym', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(16, 'Roommate Friendly', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(17, 'Private Entry', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(18, 'Well-Connected Transport', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(19, 'Cooking Allowed', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(20, 'West Facing Unit', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(21, 'East Facing Unit', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(22, 'No Brokerage', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(23, 'Walking Distance to Market', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(24, 'Verified Tenants Only', '2025-07-31 20:47:01', '2025-07-31 20:47:01'),
(25, 'Quiet Hours Policy', '2025-07-31 20:47:01', '2025-07-31 20:47:01');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `query` enum('occupied','NLN','wrong info') NOT NULL,
  `status` enum('reported','processing','resolved') NOT NULL DEFAULT 'reported',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `listing_id`, `user_id`, `query`, `status`, `created_at`, `updated_at`) VALUES
(6, 61, 80, 'wrong info', 'reported', '2025-08-01 06:32:15', '2025-08-01 06:32:15'),
(7, 55, 81, 'occupied', 'reported', '2025-08-01 06:36:24', '2025-08-01 06:36:24'),
(8, 54, 81, 'NLN', 'reported', '2025-08-01 06:36:31', '2025-08-01 06:36:31'),
(9, 58, 82, 'wrong info', 'reported', '2025-08-01 06:38:24', '2025-08-01 06:38:24'),
(10, 62, 83, 'wrong info', 'reported', '2025-08-01 06:40:24', '2025-08-01 06:40:24'),
(11, 66, 86, 'NLN', 'reported', '2025-08-01 06:46:26', '2025-08-01 06:46:26'),
(12, 68, 86, 'wrong info', 'reported', '2025-08-01 06:46:31', '2025-08-01 06:46:31');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `phone`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@roomlinks.in', '$2y$10$Aiup6SfiNnP1gMUx5exL7.fX9ZSMNr2ZoTEhGmXdncZRWHTULfBk.', '9623908967', 'admin', '2025-07-31 13:27:08', '2025-08-01 07:24:29'),
(71, 'omphopse@gmail.com', 'omphopse@gmail.com', '$2y$10$Oa5nnLECNsUQG6rtG.Ebb.cS3L8WNa8DvCbJNjByKVk8B/PlRKZ5m', '9623908967', 'user', '2025-07-31 21:38:35', '2025-08-01 07:24:48'),
(72, 'rohitsharma@gmail.com', 'rohitsharma@gmail.com', '$2y$10$EG00zi1TIYjp7hg7zJVTJe2DTvsJ0EbWRWeVDfi/fxxfAnVOBIuvG', '9623908967', 'user', '2025-07-31 21:55:30', '2025-08-01 07:24:53'),
(73, 'amanverma@gmail.com', 'amanverma@gmail.com', '$2y$10$/tb6hQKMufVCVJyT7v1IWuzL4Hr2V6EaeSnkyAI0z/ttZYPNzgM6e', '9623908967', 'user', '2025-07-31 22:00:11', '2025-08-01 07:24:55'),
(74, 'karthikreddy@gmail.com', 'karthikreddy@gmail.com', '$2y$10$Rx3MEWUsQsM5UxKHFcXGeu4gxalfl1xJYL2NQyBALc/qtejYnmI7m', '9623908967', 'user', '2025-07-31 22:02:31', '2025-08-01 07:24:58'),
(75, 'vikramjoshi@gmail.com', 'vikramjoshi@gmail.com', '$2y$10$0biQ1nyqXlD5Ku3R/OSQcelYUb2OUn0wwhYWIuM/bMBXEAXCWozSG', '9623908967', 'user', '2025-07-31 22:05:51', '2025-08-01 07:25:00'),
(76, 'priyamehta@gmail.com', 'priyamehta@gmail.com', '$2y$10$tXkrWj2fFNXnKuA.GfMFvuFtoi3Orom0ELLX4aukioytlIt3Gs7Ui', '9623908967', 'user', '2025-07-31 22:08:13', '2025-08-01 07:25:03'),
(77, 'snehapatil@gmail.com', 'snehapatil@gmail.com', '$2y$10$2xTAG3Rm71ixSG/S2gpHAOfuaFSqBN2Xz0KOIzqUODSF43CQkTwZC', '9623908967', 'user', '2025-07-31 22:09:56', '2025-08-01 07:25:05'),
(78, 'anjalisharma@gmail.com', 'anjalisharma@gmail.com', '$2y$10$qzSPMz5WXA/ahKoqzkYsOeHrcL8Kn4fERhtSm12VQBKJyyp6os2l2', '9623908967', 'user', '2025-07-31 22:12:27', '2025-08-01 07:25:08'),
(79, 'nehakulkarni@gmail.com', 'nehakulkarni@gmail.com', '$2y$10$1g96i4PPkrQDk9t4xOsX5uO1ClYfmCU2YqI9re9uj9S0dZF024j.a', '9623908967', 'user', '2025-07-31 22:14:12', '2025-08-01 07:25:10'),
(80, 'aarav@gmail.com', 'aarav@gmail.com', '$2y$10$biOB8IwV/XjeyTv/F0CVr.cujh5AGPlz0hjVDTLJPKAK53yiY1.0q', '9623908967', 'user', '2025-08-01 06:28:47', '2025-08-01 07:25:13'),
(81, 'priya@gmail.com', 'priya@gmail.com', '$2y$10$i4U.e83/E5v1no02WNS0J.0ry8iUOZmgesU33U6b5jzhuPn4YFqW2', '9623908967', 'user', '2025-08-01 06:33:13', '2025-08-01 07:25:15'),
(82, 'Rahul@gmail.com', 'Rahul@gmail.com', '$2y$10$/5LnvYxrz3qkeDRBrAvprOKSPlZA9x14JhLTjQC3490DAgnhbR2SC', '9623908967', 'user', '2025-08-01 06:37:19', '2025-08-01 07:25:19'),
(83, 'Sneha@gmail.com', 'Sneha@gmail.com', '$2y$10$lVGBDz4cy02FoeC64yzqaurctVq9dAk3Toj7PeIsp5l6sy9R7aYeO', '9623908967', 'user', '2025-08-01 06:39:04', '2025-08-01 07:25:25'),
(84, 'Vivaan@gmail.com', 'Vivaan@gmail.com', '$2y$10$GMZjSCcL0m5EUtsQ8he1se76PlBey7pc4RBFMgQZZ4cIQyyOWwwku', '9623908967', 'user', '2025-08-01 06:42:31', '2025-08-01 07:25:27'),
(85, 'Ananya@gmail.com', 'Ananya@gmail.com', '$2y$10$ukCnT3rNH2H65NZ1p05V6ueo444L2P2FY7HACNlwugbGXLaQk3pWu', '9623908967', 'user', '2025-08-01 06:44:23', '2025-08-01 07:25:30'),
(86, 'Rohan@gmail.com', 'Rohan@gmail.com', '$2y$10$weTUGGI55JTjaBWTRuwchug1R2bx3cQFTzEVO7cBjM/P5ZriZdhGa', '9623908967', 'user', '2025-08-01 06:46:07', '2025-08-01 07:26:41'),
(87, 'Isha@gmail.com', 'Isha@gmail.com', '$2y$10$OIxzyjNA1ml6X6VAzBmHw.sty8b4Nlc61zSTU/5FoVi8NbMQ9PJFK', '9623908967', 'user', '2025-08-01 06:47:49', '2025-08-01 07:26:43'),
(88, 'Karan@gmail.com', 'Karan@gmail.com', '$2y$10$xIy5bZz8pr.tzvbjXWEWt.VgBVjzKeTZUk41a1E1P6aMtdnhK19ky', '9623908967', 'user', '2025-08-01 06:49:01', '2025-08-01 07:26:46'),
(89, 'Meera@gmail.com', 'Meera@gmail.com', '$2y$10$EpmVnDDpBQU9op1ORe6t/.JNZzkQjFGYKhnYAa8A8hdyEmDjSnite', '9623908967', 'user', '2025-08-01 06:50:10', '2025-08-01 07:26:48');

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(33, 1, '9a0a6014968cecc1196c1a0a337313adc792cb058a2ae82ec4fb54c9cb0bd806', '2025-08-31 09:22:16', '2025-08-01 12:52:16');

-- --------------------------------------------------------

--
-- Table structure for table `vibes`
--

CREATE TABLE `vibes` (
  `id` int(11) NOT NULL,
  `images` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vibes`
--

INSERT INTO `vibes` (`id`, `images`, `created_at`, `updated_at`, `name`) VALUES
(13, 'assets/vibes/Adventurous_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Adventurous'),
(14, 'assets/vibes/Ambitious_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Ambitious'),
(15, 'assets/vibes/Chai_Lover_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Chai Lover'),
(16, 'assets/vibes/Chill_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Chill'),
(17, 'assets/vibes/Cinephile_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Cinephile'),
(18, 'assets/vibes/Cleanliness_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Cleanliness'),
(19, 'assets/vibes/Coffee_Preson_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Coffee Preson'),
(20, 'assets/vibes/Creative_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Creative'),
(21, 'assets/vibes/Early_Bird_20250731223410.png', '2025-07-31 20:34:10', '2025-07-31 20:34:10', 'Early Bird'),
(22, 'assets/vibes/Explorer_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Explorer'),
(23, 'assets/vibes/Foodie_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Foodie'),
(24, 'assets/vibes/Gamer_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Gamer'),
(25, 'assets/vibes/Friendly_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Friendly'),
(26, 'assets/vibes/Gym_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Gym'),
(27, 'assets/vibes/Night_Owl_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Night Owl'),
(28, 'assets/vibes/Party_Mode_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Party Mode'),
(29, 'assets/vibes/Pet_Friendly_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Pet Friendly'),
(30, 'assets/vibes/Books_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Books'),
(31, 'assets/vibes/Student_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Student'),
(32, 'assets/vibes/Introvert_20250731223654.png', '2025-07-31 20:36:54', '2025-07-31 20:36:54', 'Introvert');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `listing`
--
ALTER TABLE `listing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `massage`
--
ALTER TABLE `massage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `avatar_id` (`avatar_id`);

--
-- Indexes for table `profile_pictures`
--
ALTER TABLE `profile_pictures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property_highlights`
--
ALTER TABLE `property_highlights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vibes`
--
ALTER TABLE `vibes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `listing`
--
ALTER TABLE `listing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `massage`
--
ALTER TABLE `massage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile_pictures`
--
ALTER TABLE `profile_pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `property_highlights`
--
ALTER TABLE `property_highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `vibes`
--
ALTER TABLE `vibes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profile_ibfk_2` FOREIGN KEY (`avatar_id`) REFERENCES `profile_pictures` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD CONSTRAINT `user_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
