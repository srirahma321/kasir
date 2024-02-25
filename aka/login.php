<?php
session_start();
include "db_conn.php";

header("refresh:2;url=index.php");

if (isset($_POST['uname']) && isset($_POST['password'])){
 
    function validate($data){
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']); 

    if (empty($uname)){
        header("location: index.php?error=User Name is required");
        exit();
    }else if(empty($pass)){
        header("location: index.php?error=Password is required");
        exit();
    }else{
        $sql = "SELECT * FROM users WHERE user_name='$uname' AND password='$pass'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1){
            $row = mysqli_fetch_assoc($result);
            if ($row['user_name'] === $uname && $row['password'] === $pass) {
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                header("location: tam/index.php");
                exit();
            }else{
                header("location: index.php?error=Incorect User name or password");
                exit();
            }
        }
    }

}else{
    header("location: index.php");
    exit();
}
?>