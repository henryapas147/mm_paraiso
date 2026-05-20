-- ============================================================================
-- Room Availability Block Triggers
-- Automatically updates room status based on active availability blocks
-- ============================================================================

-- Trigger: After inserting a new block
DELIMITER $$

CREATE TRIGGER tr_rab_after_insert
AFTER INSERT ON room_availability_blocks
FOR EACH ROW
BEGIN
    DECLARE block_count INT;
    
    -- Check if there are any active blocks for this room
    SELECT COUNT(*) INTO block_count
    FROM room_availability_blocks
    WHERE room_id = NEW.room_id
      AND is_active = 1
      AND date_start <= CURDATE()
      AND date_end >= CURDATE();
    
    -- Update room status based on active blocks
    IF block_count > 0 THEN
        UPDATE rooms
        SET room_status = 'blocked',
            updated_at = NOW()
        WHERE room_id = NEW.room_id;
    ELSE
        -- Revert to available if no active blocks
        UPDATE rooms
        SET room_status = 'available',
            updated_at = NOW()
        WHERE room_id = NEW.room_id;
    END IF;
END$$

-- Trigger: After updating a block
CREATE TRIGGER tr_rab_after_update
AFTER UPDATE ON room_availability_blocks
FOR EACH ROW
BEGIN
    DECLARE block_count INT;
    
    -- Check if there are any active blocks for this room
    SELECT COUNT(*) INTO block_count
    FROM room_availability_blocks
    WHERE room_id = NEW.room_id
      AND is_active = 1
      AND date_start <= CURDATE()
      AND date_end >= CURDATE();
    
    -- Update room status based on active blocks
    IF block_count > 0 THEN
        UPDATE rooms
        SET room_status = 'blocked',
            updated_at = NOW()
        WHERE room_id = NEW.room_id;
    ELSE
        -- Revert to available if no active blocks
        UPDATE rooms
        SET room_status = 'available',
            updated_at = NOW()
        WHERE room_id = NEW.room_id;
    END IF;
END$$

-- Trigger: After deleting a block
CREATE TRIGGER tr_rab_after_delete
AFTER DELETE ON room_availability_blocks
FOR EACH ROW
BEGIN
    DECLARE block_count INT;
    
    -- Check if there are any remaining active blocks for this room
    SELECT COUNT(*) INTO block_count
    FROM room_availability_blocks
    WHERE room_id = OLD.room_id
      AND is_active = 1
      AND date_start <= CURDATE()
      AND date_end >= CURDATE();
    
    -- Update room status based on remaining active blocks
    IF block_count > 0 THEN
        UPDATE rooms
        SET room_status = 'blocked',
            updated_at = NOW()
        WHERE room_id = OLD.room_id;
    ELSE
        -- Revert to available if no active blocks
        UPDATE rooms
        SET room_status = 'available',
            updated_at = NOW()
        WHERE room_id = OLD.room_id;
    END IF;
END$$

DELIMITER ;

SELECT '03_room_blocks_triggers.sql — OK' AS migration_status;
