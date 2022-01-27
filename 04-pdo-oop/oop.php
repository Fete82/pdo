<?php

require('includes/config.php');
require('includes/db.php');
require('Models/User.php');
require('Models/Employee.php');
require('Models/Student.php');

// get a new db handle
$dbh = db_init();

// prepare and execute a query for students
$student_query = $dbh->prepare("SELECT * FROM students");
$student_query->execute();

// get result as instances of the class Student
$students = $student_query->fetchAll(PDO::FETCH_CLASS, Student::class);

// prepare and execute a query for employees
$employee_query = $dbh->prepare("SELECT * FROM employees");
$employee_query->execute();

// get result as instances of the class Employee
$employees = $employee_query->fetchAll(PDO::FETCH_CLASS, Employee::class);

// 💥
$people = array_merge($students, $employees);

// show result
echo "<pre>";
echo "<h2>People</h2>";
var_dump($people);
echo "</pre>";

echo "<ul>";
foreach ($people as $person) {
	$class = get_class($person); // get class (as a string) that the object is an instance of

	// is user an Employee?
	if ($person instanceof Employee) {
		if ($person->isExternal()) {
			$class = "external {$class}";
		}
	}

	echo "<li>";
	echo "{$person->getFullName()} ({$person->getEmail()}) is a {$class} with ID {$person->getId()}";
	echo "</li>";
}
echo "</ul>";
