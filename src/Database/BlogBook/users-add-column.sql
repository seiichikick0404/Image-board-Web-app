ALTER TABLE users
ADD COLUMN subscription VARCHAR(255),
ADD COLUMN subscription_status VARCHAR(255),
ADD COLUMN subscription_created_at DATETIME,
ADD COLUMN subscription_end_at DATETIME;