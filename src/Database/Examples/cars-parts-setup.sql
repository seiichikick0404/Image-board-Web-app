 CREATE TABLE IF NOT EXISTS cars_parts (
    car_id INT,
    part_id INT,
    quantity INT,
    PRIMARY KEY (car_id, part_id),
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (part_id) REFERENCES parts(id) ON DELETE CASCADE ON UPDATE CASCADE
);