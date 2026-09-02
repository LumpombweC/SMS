<?php
/**
 * Enrolment class
 * Links a Student to a Course and stores the mark/grade.
 * Responsible for composition relationship and grade calculation.
 */
class Enrolment
{
    private Student $student;
    private Course $course;
    private ?float $mark = null;
    private ?Grade $grade = null;

    public function __construct(Student $student, Course $course, ?float $mark = null)
    {
        $this->student = $student;
        $this->course = $course;
        if ($mark !== null) {
            $this->setMark($mark);
        }
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function getMark(): ?float
    {
        return $this->mark;
    }

    /**
     * Returns the letter grade (or null if no mark recorded yet).
     */
    public function getGrade(): ?string
    {
        return $this->grade !== null ? $this->grade->getLetter() : null;
    }

    public function getGradeObject(): ?Grade
    {
        return $this->grade;
    }

    public function setMark(float $mark): void
    {
        // Validation lives inside Grade
        $this->grade = new Grade($mark);
        $this->mark = $mark;
    }
}
