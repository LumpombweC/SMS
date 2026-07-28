<?php

class Student {
    private $studentnumber;
    private $fullname;
    private $programme;
    private $year;

    public function __construct($studentnumber, $fullname, $programme, $year) {
        $this->studentnumber = $studentnumber   ;
        $this->fullname = $fullname;
        $this->programme = $programme;
        $this->year = $year;
    }

    public function getStudentNumber() {
        return $this->studentnumber;
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
?>