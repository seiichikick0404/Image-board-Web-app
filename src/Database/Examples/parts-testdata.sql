CREATE TABLE computer_parts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    type VARCHAR(255),
    brand VARCHAR(255),
    model_number VARCHAR(255),
    release_date DATE,
    performance_score INT,
    market_price FLOAT,
    rsm FLOAT,
    power_consumptionw FLOAT,
    lengthm FLOAT,
    widthm FLOAT,
    heightm FLOAT,
    lifespan INT,
    quantityInStock INT,
    price FLOAT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);




INSERT INTO computer_parts (name, description, type, brand, model_number, release_date, performance_score, market_price, rsm, power_consumptionw, lengthm, widthm, heightm, lifespan, quantityInStock, price) VALUES
('Hard Drive', '1TB HDD Storage', 'Storage', 'Seagate', 'ST1000DM010', '2020-01-01', 80, 50.00, 1.2, 10, 0.15, 0.10, 0.04, 5, 20, 50.00),
('SSD', '256GB Solid State Drive', 'Storage', 'Samsung', 'MZ-76E256B', '2021-02-15', 95, 70.00, 1.5, 5, 0.10, 0.07, 0.02, 5, 15, 70.00),
('Graphics Card', 'GeForce GTX 1660 Super', 'GPU', 'NVIDIA', 'GTX1660Super', '2020-03-03', 120, 229.99, 2.0, 120, 0.26, 0.12, 0.05, 3, 10, 229.99),
('Processor', 'Intel Core i9-9900K', 'CPU', 'Intel', 'BX80684I99900K', '2019-10-18', 150, 499.99, 2.5, 95, 0.04, 0.04, 0.04, 5, 5, 499.99);


