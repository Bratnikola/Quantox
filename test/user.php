<?php

session_start();
 
// Provera da li je korisnik ulogvan u suprotnom ide na login stranicu
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}



//require_once "connect.php";

//NISAM USPEO DA IZVEDEM USPOMOĆ PDO. Ovo je bar delimično.

$search_value=$_POST["search"];

$con=new mysqli("localhost","root","","quantox");
if($con->connect_error){
    echo 'Neuspešna konekcija: '.$con->connect_error;
    }else{
        $sql="select * from users where name like '%$search_value%'";

        $res=$con->query($sql);

        while($row=$res->fetch_assoc()){
            echo 'Rezultat:  '.$row["name"];


            }       

        }




?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <div class="page-header">
        <h1>Zdravo <b><?php echo htmlspecialchars($_SESSION["email"]); ?></b>!</h1>
    </div>

    <div class="search-box">
        <form action="" method="post">
            <input type="text" name="search">
            <input type="submit" name="submit" value="Pretraži">
        </form>
    </div>

</body>
</html>