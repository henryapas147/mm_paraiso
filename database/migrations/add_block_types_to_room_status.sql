-- Add block_type values to rooms.room_status ENUM
-- This allows room_status to match block_type values from room_availability_blocks

ALTER TABLE rooms
MODIFY COLUMN room_status ENUM(
    'available',
    'occupied',
    'reserved',
    'cleaning',
    'maintenance',
    'out_of_order',
    'blocked',
    'owner_use',
    'event_hold',
    'deep_cleaning',
    'renovation',
    'other'
) NOT NULL DEFAULT 'available' COMMENT 'Real-time operational status - includes block types';

SELECT 'add_block_types_to_room_status.sql — OK' AS migration_status;
