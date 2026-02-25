-- Migration: Change listing.location from INT to VARCHAR
-- This allows storing both location IDs (for existing locations) and custom location strings

-- Step 1: Drop the foreign key constraint
ALTER TABLE `listing` 
DROP FOREIGN KEY `listing_ibfk_location`;

-- Step 2: Modify the location column to VARCHAR
ALTER TABLE `listing` 
MODIFY `location` VARCHAR(255) NOT NULL;

-- Step 3: (Optional) Add a comment to document the change
ALTER TABLE `listing` 
MODIFY `location` VARCHAR(255) NOT NULL COMMENT 'Can be location ID (numeric string) or custom location name';
