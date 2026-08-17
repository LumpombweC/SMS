CREATE DATABASE IF NOT EXISTS students_records;
USE students_records;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_number VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    programme VARCHAR(100),
    year_of_study INT
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    credit_hours INT NOT NULL
);

CREATE TABLE enrolments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    grade VARCHAR(2),

    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);
INSERT INTO students (student_number, first_name, last_name, programme,Year_of_Study)
VALUES
('2026001','Lewis','Chingwamari','Computer Science',2),
('2026002','Kieth','Tim','Computer Science',3);

INSERT INTO courses (course_code, course_name, credit_hours)
VALUES
('CSC101','Programming Fundamentals',3),
('CSC205','Database Systems',3);

INSERT INTO enrolments (student_id, course_id, grade)
VALUES
(1,1,'A'),
(1,2,'B+'),
(2,1,'A-');
