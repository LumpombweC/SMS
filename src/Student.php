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
?>