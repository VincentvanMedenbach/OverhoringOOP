<?php

class Logger
{
    public function __construct($location, $severity, $format)
    {
        $this->severity = $severity;
        $this->location = $location;
        $this->format = $format;
    }

    public $severity;
    public $location;
    public $format;
    public $date;

    public function logFile($message)
    {
        $date = date("[Y-m-d h:m:s]", time());
        $CommentSave = fopen($this->location, "w");
        if ($this->format == 'compact') {
            $totalMessage = $this->severity . ' ' . $message;
        } else {
            $totalMessage = $date . ' ' . $this->severity . ' ' . $message;
        }
        fwrite($CommentSave, $totalMessage);
    }

    public function logDataBase($message, $table)
    {
        $servername = "localhost";
        $username = "root";
        $password = "mysql";
        $date = date("[Y-m-d h:m:s]", time());


        // Create connection
        $conn = new mysqli($servername, $username, $password, $this->location);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        $sql = "INSERT INTO `$table` (severity, message, date)
VALUES ('$this->severity', '$message', '$date')";
        if ($conn->query($sql) === TRUE) {
            echo "error logged succesfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            die;
        }
        $conn->close();
    }
    public function doubleLog($message, $table, $location, $db){
        $this->location = $db;
        $this->logDataBase($message, $table);
        $this->location = $location;
        $this->logFile($message);
    }
}


$logAll = new Logger('log-all.txt', 'all', 'compact');
$errorsOnly = new Logger('log-errors.txt', 'only-errors', 'long');
$logDatabase = new Logger('errors', 'severe', 'long');
$logAll->logFile("info");
$logAll->logFile("info");
$errorsOnly->logFile("error");
$errorsOnly->logFile("error");
$logDatabase->logDataBase('hoi','errorLogs');
$logDatabase->doubleLog('Double trouble', 'errorLogs', 'logging.txt', 'errors');
