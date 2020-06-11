CREATE TABLE IF NOT EXISTS products (
    `SKU` varchar(16) UNIQUE NOT NULL,
    `description` text NOT NULL,
    `normal_price` float NOT NULL,
    `special_price` float NULL
)
