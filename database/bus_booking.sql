CREATE DATABASE IF NOT EXISTS bus_booking;
USE bus_booking;

DROP TABLE IF EXISTS booking_passengers;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS buses;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE buses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bus_code VARCHAR(20) NOT NULL,
    bus_name VARCHAR(100) NOT NULL,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    journey_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    bus_type VARCHAR(50) NOT NULL,
    total_rows INT NOT NULL DEFAULT 5,
    total_cols INT NOT NULL DEFAULT 4,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(30) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    bus_id INT NOT NULL,
    journey_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'booked',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (bus_id) REFERENCES buses(id)
);

CREATE TABLE booking_passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    passenger_name VARCHAR(120) NOT NULL,
    seat_row INT NOT NULL,
    seat_col INT NOT NULL,
    ticket_code VARCHAR(30) NOT NULL UNIQUE,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

DROP TABLE IF EXISTS bus_templates;
CREATE TABLE bus_templates (
    bus_code VARCHAR(20) NOT NULL,
    bus_name VARCHAR(100) NOT NULL,
    source VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    bus_type VARCHAR(50) NOT NULL,
    total_rows INT NOT NULL,
    total_cols INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
);

INSERT INTO bus_templates
(bus_code, bus_name, source, destination, departure_time, arrival_time, bus_type, total_rows, total_cols, price, image)
VALUES
('101A', 'Horizon Express', 'Pune', 'Mumbai', '07:00:00', '11:00:00', 'Sleeper AC', 5, 4, 1000.00, 'greenbus.jpg'),
('102B', 'Skyline Travels', 'Pune', 'Nashik', '08:00:00', '12:00:00', 'Sleeper Non-AC', 5, 4, 1500.00, 'busimg.jpg'),
('103C', 'Royal Route', 'Pune', 'Mumbai', '16:00:00', '20:30:00', 'Seating', 5, 4, 1200.00, 'nightbus.jpg'),
('104D', 'Comfort Ride', 'Hyderabad', 'Gujarat', '09:00:00', '18:00:00', 'Seating', 5, 4, 2000.00, 'roadbus.jpg'),
('105E', 'Night Cruiser', 'Pune', 'Mumbai', '21:00:00', '02:00:00', 'Sleeper AC', 5, 4, 1300.00, 'nightbus.jpg'),
('106F', 'Green Line', 'Bangalore', 'Pune', '10:00:00', '20:00:00', 'Sleeper AC', 5, 4, 1800.00, 'greenbus.jpg'),
('107G', 'City Connect', 'Mumbai', 'Pune', '06:30:00', '10:30:00', 'Seating', 5, 4, 950.00, 'roadbus.jpg'),
('108H', 'Western Star', 'Nashik', 'Pune', '14:00:00', '18:00:00', 'Seating', 5, 4, 1100.00, 'busimg.jpg'),
('109I', 'Deccan Sleeper', 'Pune', 'Bangalore', '20:00:00', '07:00:00', 'Sleeper AC', 5, 4, 2200.00, 'nightbus.jpg'),
('110J', 'Morning Ride', 'Gujarat', 'Hyderabad', '07:30:00', '16:00:00', 'Seating', 5, 4, 1950.00, 'roadbus.jpg');

DROP PROCEDURE IF EXISTS seed_buses;

DELIMITER $$

CREATE PROCEDURE seed_buses()
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE total_days INT DEFAULT 365;

    WHILE i < total_days DO
        INSERT INTO buses
        (bus_code, bus_name, source, destination, journey_date, departure_time, arrival_time, bus_type, total_rows, total_cols, price, image)
        SELECT
            bus_code,
            bus_name,
            source,
            destination,
            DATE_ADD(CURDATE(), INTERVAL i DAY),
            departure_time,
            arrival_time,
            bus_type,
            total_rows,
            total_cols,
            price,
            image
        FROM bus_templates;

        SET i = i + 1;
    END WHILE;
END $$

DELIMITER ;

CALL seed_buses();

DROP PROCEDURE IF EXISTS seed_buses;
DROP TABLE IF EXISTS bus_templates;