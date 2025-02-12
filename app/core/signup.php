<?php

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = (int)$_POST["phone"];
    $password = $_POST["password"];

    try{

        require_once 'dbh.inc.php';
        require_once 'signup_model.inc.php';
        require_once 'signup_contr.inc.php';

        // ERROR HANDLERS
        $errors = [];

        if(is_input_empty($username, $email, $phone, $password)){
            $errors["empty_input"] = "Fil all the Fields!";
        }
        if(is_email_invalid($email)){
            $errors["invalid_email"] = "Inavlid email used!";
        }
        if(is_username_taken($pdo, $username)){
            $errors["username_taken"] = "Username already taken!";
        }
        if(is_email_registered($pdo, $email)){
            $errors["email_taken"] = "Email already registered!";
        }

        require_once 'config_session.inc.php';

        if($errors){
            $_SESSION["errors_signup"] = $errors;

            header("location: ../User pages/signup.php");
            die();
        }

        create_user($pdo, $username, $email, $phone, $password);

        header("location: ../User pages/signup.php?signup=success");

        $pdo = null;
        $stmt = null;

        die();

    } catch (PDOException $e) {

        die("Query failed: " . $e->getMessage());
    }
} else{
    header("location: ../User pages/signup.php");
    die();
}

?>