-- SMS Logs Table Migration
-- Run this to ensure sms_logs table has all required columns

-- Create table if not exists
CREATE TABLE IF NOT EXISTS sms_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NULL,
    phone VARCHAR(20) NOT NULL,
    message LONGTEXT NOT NULL,
    direction ENUM('IN', 'OUT') NOT NULL DEFAULT 'OUT',
    status VARCHAR(50),
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    INDEX idx_order (order_id),
    INDEX idx_phone (phone),
    INDEX idx_created (created_at)
);

-- Verify orders table has contact field
-- ALTER TABLE orders ADD COLUMN contact VARCHAR(20) IF NOT EXISTS; 
-- (Note: MySQL doesn't support IF NOT EXISTS for columns in ALTER, so do this manually if needed)

-- If you already have sms_logs with different columns, add missing ones:
-- ALTER TABLE sms_logs ADD COLUMN status VARCHAR(50) AFTER direction;
-- ALTER TABLE sms_logs ADD COLUMN error_message TEXT AFTER status;

-- Optional: Add indexes if they don't exist
-- ALTER TABLE sms_logs ADD INDEX idx_order (order_id);
-- ALTER TABLE sms_logs ADD INDEX idx_phone (phone);
-- ALTER TABLE sms_logs ADD INDEX idx_created (created_at);
