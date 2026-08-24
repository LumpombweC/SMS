<?php

class Student {
    private string $studentNumber;
    private string $fullName;
    private string $programme;
    private int $yearOfStudy;
    private array $enrolments = [];

    // Static counter for generating unique student numbers
    private static int $counter = 0;

    public function __construct(string $fullName, string $programme, int $yearOfStudy) {
        $this->studentNumber = self::generateStudentNumber();
        $this->setFullName($fullName);
        $this->setProgramme($programme);
        $this->setYearOfStudy($yearOfStudy);
    }

    // Auto-generate student number (e.g., LGU-2026-001)
    public static function generateStudentNumber(): string {
        self::$counter++;
        $year = date('Y');
        return sprintf('LGU-%s-%03d', $year, self::$counter);
    }

    // Enrol student in a course
    public function enrol(Course $course): void {
        $this->enrolments[] = new Enrolment($this, $course);
    }

    // Record a mark for a specific course with validation
    public function recordMark(string $courseCode, float $mark): void {
        if ($mark < 0 || $mark > 100) {
            throw new InvalidArgumentException("Mark for {$courseCode} must be between 0 and 100.");
        }

        foreach ($this->enrolments as $enrolment) {
            if ($enrolment->getCourse()->getCourseCode() === $courseCode) {
                $enrolment->setMark($mark);
                return;
            }
        }

        throw new Exception("Student {$this->studentNumber} is not enrolled in course {$courseCode}.");
    }

    // Return transcript data
    public function getTranscript(): array {
        $transcript = [];
        foreach ($this->enrolments as $enrolment) {
            $transcript[] = [
                'courseCode'  => $enrolment->getCourse()->getCourseCode(),
                'courseName'  => $enrolment->getCourse()->getCourseName(),
                'creditHours' => $enrolment->getCourse()->getCreditHours(),
                'mark'        => $enrolment->getMark(),
                'grade'       => $enrolment->getGrade()
            ];
        }
        return $transcript;
    }

    // Getters
    public function getStudentNumber(): string {
        return $this->studentNumber;
    }

    public function getFullName(): string {
        return $this->fullName;
    }

    public function getProgramme(): string {
        return $this->programme;
    }

    public function getYearOfStudy(): int {
        return $this->yearOfStudy;
    }

    public function getEnrolments(): array {
        return $this->enrolments;
    }

    // Setters with validation
    public function setFullName(string $fullName): void {
        $fullName = trim($fullName);
        if ($fullName === '') {
            throw new InvalidArgumentException('Full name cannot be empty.');
        }
        $this->fullName = $fullName;
    }

    public function setProgramme(string $programme): void {
        $programme = trim($programme);
        if ($programme === '') {
            throw new InvalidArgumentException('Programme cannot be empty.');
        }
        $this->programme = $programme;
    }

    public function setYearOfStudy(int $yearOfStudy): void {
        if ($yearOfStudy < 1 || $yearOfStudy > 6) {
            throw new InvalidArgumentException('Year of study must be between 1 and 6.');
        }
        $this->yearOfStudy = $yearOfStudy;
    }
}