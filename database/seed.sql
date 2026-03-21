USE sarkari;

-- SSC Exams
INSERT INTO exams (name, category, description, icon, sort_order, syllabus_json) VALUES
('SSC CGL', 'SSC', 'Combined Graduate Level exam for Group B & C posts in government ministries', 'building', 1,
 '{"sections":["Quantitative Aptitude","English Language","General Intelligence & Reasoning","General Awareness"]}'),
('SSC CHSL', 'SSC', 'Combined Higher Secondary Level for LDC, DEO, PA/SA posts', 'file-text', 2,
 '{"sections":["Quantitative Aptitude","English Language","General Intelligence","General Awareness"]}'),
('SSC MTS', 'SSC', 'Multi Tasking Staff for Group C non-technical posts', 'users', 3,
 '{"sections":["Numerical Aptitude","English Language","General Intelligence","General Awareness"]}'),
('SSC CPO', 'SSC', 'Central Police Organisation for SI in Delhi Police & CAPFs', 'shield', 4,
 '{"sections":["Quantitative Aptitude","English Language","General Intelligence & Reasoning","General Awareness"]}'),
('SSC Stenographer', 'SSC', 'Stenographer Grade C & D examination', 'mic', 5,
 '{"sections":["General Intelligence & Reasoning","General Awareness","English Language & Comprehension"]}');

-- Banking Exams
INSERT INTO exams (name, category, description, icon, sort_order, syllabus_json) VALUES
('IBPS PO', 'Banking', 'Institute of Banking Personnel Selection - Probationary Officer', 'landmark', 10,
 '{"sections":["Quantitative Aptitude","Reasoning Ability","English Language","General Awareness","Computer Aptitude"]}'),
('IBPS Clerk', 'Banking', 'Clerical cadre posts in public sector banks', 'credit-card', 11,
 '{"sections":["Quantitative Aptitude","Reasoning Ability","English Language","General/Financial Awareness","Computer Aptitude"]}'),
('SBI PO', 'Banking', 'State Bank of India Probationary Officer', 'banknote', 12,
 '{"sections":["Quantitative Aptitude","Reasoning & Computer Aptitude","English Language","General/Economy/Banking Awareness","Data Analysis & Interpretation"]}'),
('SBI Clerk', 'Banking', 'State Bank of India Junior Associate (Clerk)', 'wallet', 13,
 '{"sections":["Quantitative Aptitude","Reasoning Ability","English Language","General/Financial Awareness","Computer Aptitude"]}'),
('RBI Grade B', 'Banking', 'Reserve Bank of India Officer Grade B', 'building-2', 14,
 '{"sections":["General Awareness","Quantitative Aptitude","English Language","Reasoning","Economic & Social Issues","Finance & Management"]}');

-- Railway Exams
INSERT INTO exams (name, category, description, icon, sort_order, syllabus_json) VALUES
('RRB NTPC', 'Railway', 'Non-Technical Popular Categories for various railway posts', 'train', 20,
 '{"sections":["Mathematics","General Intelligence & Reasoning","General Awareness","General Science"]}'),
('RRB Group D', 'Railway', 'Level 1 posts in Indian Railways', 'wrench', 21,
 '{"sections":["Mathematics","General Intelligence & Reasoning","General Awareness","General Science"]}'),
('RRB JE', 'Railway', 'Junior Engineer posts in Indian Railways', 'settings', 22,
 '{"sections":["Mathematics","General Intelligence & Reasoning","General Awareness","General Science","Technical Ability"]}'),
('RRB ALP', 'Railway', 'Assistant Loco Pilot and Technician posts', 'cog', 23,
 '{"sections":["Mathematics","General Intelligence & Reasoning","General Science","Technical Ability"]}');

-- UPSC Exams
INSERT INTO exams (name, category, description, icon, sort_order, syllabus_json) VALUES
('UPSC CSE Prelims', 'UPSC', 'Civil Services Examination Preliminary Test', 'award', 30,
 '{"sections":["General Studies Paper I","CSAT Paper II"]}'),
('UPSC CDS', 'UPSC', 'Combined Defence Services for Army, Navy & Air Force', 'swords', 31,
 '{"sections":["English","General Knowledge","Elementary Mathematics"]}'),
('UPSC NDA', 'UPSC', 'National Defence Academy for Army, Navy & Air Force wings', 'target', 32,
 '{"sections":["Mathematics","General Ability Test"]}'),
('UPSC CAPF', 'UPSC', 'Central Armed Police Forces Assistant Commandant', 'shield-check', 33,
 '{"sections":["General Ability & Intelligence","General Studies, Essay & Comprehension"]}');

-- State PSC Exams
INSERT INTO exams (name, category, description, icon, sort_order, syllabus_json) VALUES
('UPPSC PCS', 'State PSC', 'Uttar Pradesh Public Service Commission PCS exam', 'map-pin', 40,
 '{"sections":["General Studies I","General Studies II","General Hindi","Essay"]}'),
('MPPSC', 'State PSC', 'Madhya Pradesh Public Service Commission State Service exam', 'map-pin', 41,
 '{"sections":["General Studies","General Aptitude Test","Hindi"]}'),
('BPSC', 'State PSC', 'Bihar Public Service Commission Combined Competitive exam', 'map-pin', 42,
 '{"sections":["General Studies","Qualifying Hindi","Optional Subject"]}'),
('RPSC RAS', 'State PSC', 'Rajasthan Public Service Commission RAS/RTS exam', 'map-pin', 43,
 '{"sections":["General Knowledge & General Science","General Studies I, II, III"]}');

-- Exam Subjects (for weak-subject picker)
-- SSC CGL
INSERT INTO exam_subjects (exam_id, name) VALUES
(1, 'Number System & Simplification'), (1, 'Percentage & Ratio'), (1, 'Profit, Loss & Discount'),
(1, 'Time, Speed & Distance'), (1, 'Time & Work'), (1, 'Algebra & Geometry'),
(1, 'Trigonometry & Mensuration'), (1, 'Data Interpretation'),
(1, 'Reading Comprehension'), (1, 'Grammar & Vocabulary'), (1, 'Sentence Correction'),
(1, 'Coding-Decoding & Analogy'), (1, 'Blood Relations & Direction'), (1, 'Syllogism & Statement'),
(1, 'History & Culture'), (1, 'Geography & Environment'), (1, 'Polity & Economics'), (1, 'Current Affairs');

-- SSC CHSL
INSERT INTO exam_subjects (exam_id, name) VALUES
(2, 'Arithmetic'), (2, 'Data Interpretation'), (2, 'Algebra & Geometry'),
(2, 'English Grammar'), (2, 'Reading Comprehension'), (2, 'Vocabulary'),
(2, 'Reasoning & Logic'), (2, 'General Awareness & Current Affairs');

-- SSC MTS
INSERT INTO exam_subjects (exam_id, name) VALUES
(3, 'Basic Arithmetic'), (3, 'English Language'), (3, 'Reasoning Ability'), (3, 'General Awareness');

-- SSC CPO
INSERT INTO exam_subjects (exam_id, name) VALUES
(4, 'Quantitative Aptitude'), (4, 'English Language'), (4, 'General Intelligence'), (4, 'General Awareness');

-- SSC Steno
INSERT INTO exam_subjects (exam_id, name) VALUES
(5, 'Reasoning'), (5, 'General Awareness'), (5, 'English Comprehension');

-- IBPS PO
INSERT INTO exam_subjects (exam_id, name) VALUES
(6, 'Number Series & Simplification'), (6, 'Data Interpretation'), (6, 'Quadratic Equations'),
(6, 'Coding-Decoding & Puzzles'), (6, 'Seating Arrangement'), (6, 'Syllogism & Inequality'),
(6, 'Reading Comprehension'), (6, 'Cloze Test & Error Spotting'),
(6, 'Banking & Financial Awareness'), (6, 'Computer Awareness');

-- IBPS Clerk
INSERT INTO exam_subjects (exam_id, name) VALUES
(7, 'Quantitative Aptitude'), (7, 'Reasoning Ability'), (7, 'English Language'),
(7, 'General Awareness'), (7, 'Computer Knowledge');

-- SBI PO
INSERT INTO exam_subjects (exam_id, name) VALUES
(8, 'Data Analysis & Interpretation'), (8, 'Reasoning & Computer Aptitude'),
(8, 'English Language'), (8, 'General/Banking Awareness'), (8, 'Quantitative Aptitude');

-- SBI Clerk
INSERT INTO exam_subjects (exam_id, name) VALUES
(9, 'Quantitative Aptitude'), (9, 'Reasoning'), (9, 'English Language'),
(9, 'General Awareness'), (9, 'Computer Aptitude');

-- RBI Grade B
INSERT INTO exam_subjects (exam_id, name) VALUES
(10, 'General Awareness'), (10, 'Quantitative Aptitude'), (10, 'English Language'),
(10, 'Reasoning'), (10, 'Economic & Social Issues'), (10, 'Finance & Management');

-- RRB NTPC
INSERT INTO exam_subjects (exam_id, name) VALUES
(11, 'Mathematics'), (11, 'General Intelligence'), (11, 'General Awareness'), (11, 'General Science');

-- RRB Group D
INSERT INTO exam_subjects (exam_id, name) VALUES
(12, 'Mathematics'), (12, 'General Intelligence'), (12, 'General Awareness'), (12, 'General Science');

-- RRB JE
INSERT INTO exam_subjects (exam_id, name) VALUES
(13, 'Mathematics'), (13, 'General Intelligence'), (13, 'General Awareness'),
(13, 'General Science'), (13, 'Technical Ability');

-- RRB ALP
INSERT INTO exam_subjects (exam_id, name) VALUES
(14, 'Mathematics'), (14, 'General Intelligence'), (14, 'General Science'), (14, 'Technical Ability');

-- UPSC CSE Prelims
INSERT INTO exam_subjects (exam_id, name) VALUES
(15, 'History & Art/Culture'), (15, 'Geography'), (15, 'Indian Polity & Governance'),
(15, 'Economy'), (15, 'Environment & Ecology'), (15, 'Science & Technology'),
(15, 'Current Affairs'), (15, 'CSAT - Comprehension'), (15, 'CSAT - Logical Reasoning'),
(15, 'CSAT - Quantitative Aptitude');

-- UPSC CDS
INSERT INTO exam_subjects (exam_id, name) VALUES
(16, 'English'), (16, 'General Knowledge'), (16, 'Elementary Mathematics');

-- UPSC NDA
INSERT INTO exam_subjects (exam_id, name) VALUES
(17, 'Mathematics'), (17, 'General Ability - English'), (17, 'General Ability - GK'),
(17, 'General Ability - Science'), (17, 'General Ability - Current Affairs');

-- UPSC CAPF
INSERT INTO exam_subjects (exam_id, name) VALUES
(18, 'General Ability'), (18, 'General Studies'), (18, 'Essay Writing'), (18, 'Comprehension');

-- State PSC - UPPSC
INSERT INTO exam_subjects (exam_id, name) VALUES
(19, 'General Studies'), (19, 'Indian History'), (19, 'Geography'), (19, 'Polity'),
(19, 'Economy'), (19, 'General Hindi'), (19, 'Current Affairs');

-- MPPSC
INSERT INTO exam_subjects (exam_id, name) VALUES
(20, 'General Studies'), (20, 'General Aptitude'), (20, 'Hindi'), (20, 'Current Affairs');

-- BPSC
INSERT INTO exam_subjects (exam_id, name) VALUES
(21, 'General Studies'), (21, 'Hindi Qualifying'), (21, 'Optional Subject'), (21, 'Current Affairs');

-- RPSC RAS
INSERT INTO exam_subjects (exam_id, name) VALUES
(22, 'General Knowledge'), (22, 'General Science'), (22, 'General Studies'), (22, 'Current Affairs');

-- Create default admin user (password: admin123)
INSERT INTO users (name, email, password_hash, role, email_verified) VALUES
('Admin', 'admin@sarkari.in', '$2y$12$V9jHKBBDW2PakDg8hJGALuHB4ZqbnfnHb7QkHtE1LqutMwCxGtR5m', 'admin', 1);
