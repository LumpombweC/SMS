<?php

class Student {
    private $studentnumber;
    private $fullname;
    private $programme;
    private $year;

    public function __construct($studentnumber, $fullname, $programme, $year) {
        $this->studentnumber = self::generateStudentNumber();
        $this->fullname = $fullname;
        $this->programme = $programme;
        $this->year = $year;
    }

     // Implementation for generating student number
    private static $counter = 0000;
    public function generateStudentNumber():string
    {
        self::$counter++;
        $year = date('Y');
        return sprintf('LGU-'.$year.'-%03d', self::$counter);
    }
    
    public function getFullName() {
        return $this->fullname;
    }

    public function getProgramme() {
        return $this->programme;
    }

    public function getYear() {
        return $this->year;
    }
}   
    // Enrol student in a course
    public function enrol(Course $course): void {
        $this->enrolments[] = new Enrolment($this, $course);
    }

    // Record a mark for a specific course with validation[cite: 1]
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

    // Return all courses with marks and grades[cite: 1]
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

    // Getters and Setters[cite: 1]
    public function getStudentNumber(): string {
        return $this->studentNumber;
    }

    public function getFullName(): string {
        return $this->fullName;
    }

    public function setFullName(string $fullName): void {
        $this->fullName = $fullName;
    }

    public function getProgramme(): string {
        return $this->programme;
    }

    public function setProgramme(string $programme): void {
        $this->programme = $programme;
    }

    public function getYear(): int {
        return $this->year;
    }

    public function setYear(int $year): void {
        $this->year = $year;
    }

    public function getEnrolments(): array {
        return $this->enrolments;
    }
}
?>