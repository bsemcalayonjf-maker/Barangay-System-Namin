-- ========================================
-- SAMPLE DATA
-- ========================================

USE barangay_system;

-- ========================================
-- INSERT ADMIN ACCOUNTS
-- ========================================
-- Default Password: admin123 (hashed using password_hash())
INSERT INTO admins (username, password, fullname) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Dela Cruz'),
('staff1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria Garcia'),
('staff2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro Santos');

-- ========================================
-- INSERT DOCUMENT TYPES
-- ========================================
INSERT INTO document_types (document_name, requirements, processing_days) VALUES 
('Barangay Clearance', 'Valid ID, Barangay ID or Cedula, 1x1 Picture', 3),
('Certificate of Indigency', 'Valid ID, Proof of Residency, Income Statement', 2),
('Barangay ID', '1x1 Picture, Valid ID, Proof of Residency', 5),
('Business Permit', 'DTI Registration, Valid ID, Barangay Clearance', 7),
('Certificate of Residency', 'Valid ID, Proof of Address (Utility Bill)', 2),
('Good Moral Certificate', 'Valid ID, Barangay Clearance', 3),
('First Time Job Seeker Certificate', 'Valid ID, Birth Certificate, Barangay Clearance', 3);

-- ========================================
-- INSERT SAMPLE USERS
-- ========================================
-- Default Password: password123 (hashed using password_hash())
INSERT INTO users (firstname, lastname, email, password, address, contact_number) VALUES 
('Maria', 'Santos', 'maria@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 5 Lot 10 Phase 3, Quezon City', '09123456789'),
('Pedro', 'Reyes', 'pedro@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 2 Lot 15 Phase 1, Quezon City', '09187654321'),
('Ana', 'Cruz', 'ana@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 8 Lot 5 Phase 2, Quezon City', '09171234567'),
('Jose', 'Mendoza', 'jose@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 3 Lot 20 Phase 4, Quezon City', '09198765432');

-- ========================================
-- INSERT SAMPLE REQUESTS
-- ========================================
INSERT INTO document_requests (user_id, document_type_id, purpose, status, remarks) VALUES 
(1, 1, 'For employment requirement at ABC Company', 'approved', 'Document ready for pickup at Barangay Hall'),
(1, 2, 'For scholarship application at XYZ University', 'approved', 'Approved - Please claim within 7 days'),
(2, 1, 'For passport application', 'processing', 'Under review - Documents being verified'),
(2, 4, 'For small business registration', 'pending', NULL),
(3, 5, 'For bank account opening', 'approved', 'Ready for pickup'),
(3, 1, 'For visa application', 'processing', 'Additional documents required'),
(4, 3, 'For valid ID purposes', 'pending', NULL),
(4, 6, 'For job application', 'rejected', 'Incomplete requirements - please resubmit with complete documents');

-- ========================================
-- UPDATE SOME REQUESTS WITH PROCESSED INFO
-- ========================================
UPDATE document_requests 
SET processed_by = 1, processed_date = NOW() 
WHERE status IN ('approved', 'rejected', 'processing');
