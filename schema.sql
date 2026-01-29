CREATE TABLE participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ndis_id VARCHAR(32) NOT NULL,
    plan_start_date DATE NOT NULL,
    plan_end_date DATE NOT NULL,
    region_code VARCHAR(16)
);

CREATE TABLE budgets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    participant_id INT NOT NULL,
    category_id ENUM('Core', 'Capital', 'Capacity') NOT NULL,
    total_allocated DECIMAL(12,2) NOT NULL DEFAULT 0,
    spent DECIMAL(12,2) NOT NULL DEFAULT 0,
    committed DECIMAL(12,2) NOT NULL DEFAULT 0,
    FOREIGN KEY (participant_id) REFERENCES participants(id)
);

CREATE TABLE providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    abn VARCHAR(32) NOT NULL,
    is_registered TINYINT(1) NOT NULL DEFAULT 1,
    contact_info VARCHAR(255)
);

CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL,
    participant_id INT NOT NULL,
    invoice_no VARCHAR(64) NOT NULL,
    status ENUM('Pending', 'Paid', 'Rejected') NOT NULL DEFAULT 'Pending',
    total_gst DECIMAL(12,2) NOT NULL DEFAULT 0,
    ocr_data_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provider_id) REFERENCES providers(id),
    FOREIGN KEY (participant_id) REFERENCES participants(id)
);

CREATE TABLE alerts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(64) NOT NULL,
    message VARCHAR(255) NOT NULL,
    status ENUM('warning', 'info') NOT NULL DEFAULT 'info',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
