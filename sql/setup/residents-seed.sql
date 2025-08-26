INSERT INTO users (email, password, role)
VALUES
('juan.cruz@example.com', 'hashed_password1', 'resident'),
('maria.lopez@example.com', 'hashed_password2', 'resident'),
('pedro.garcia@example.com', 'hashed_password3', 'resident');

-- SAMPLE SEED DATA FOR RESIDENTS
INSERT INTO residents (user_id, first_name, middle_name, last_name, date_of_birth, place_of_birth_province, gender, civil_status, photo_filename)
VALUES
(1, 'Juan', 'Dela', 'Cruz', '1990-05-12', 'Metro Manila', 'Male', 'Single', 'juan_cruz.jpg'),
(2, 'Maria', 'Santos', 'Lopez', '1985-09-20', 'Laguna', 'Female', 'Married', 'maria_lopez.jpg'),
(3, 'Pedro', 'Reyes', 'Garcia', '2000-01-30', 'Cebu', 'Male', 'Single', 'pedro_garcia.jpg')