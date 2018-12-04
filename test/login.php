<?php

session_start();
 
// Proverava da li je korisnik ulogovan, ako jeste ide na user stranicu
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: user.php");
    exit;
}
 

require_once "connect.php";
 
// Definiše varijable
$email = $password = "";
$email_err = $password_err = "";
 
// slanje na server
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    /*
    if(empty(trim($_POST["username"]))){
        $username_err = "Unesite korisničko ime.";
    } else{
        $username = trim($_POST["username"]);
    }
    */

    //Validacija emaila
  if (empty($_POST["email"])) {
    $email_err = "Unesite e-mail adresu";
  } else {
    $email = test_input($_POST["email"]);
  }
    
    // Proverava da li je prazno polje
    if(empty(trim($_POST["password"]))){
        $password_err = "Unesite šifru.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Provera greški
    if(empty($email_err) && empty($password_err)){
        // Selektuje izjave
        $sql = "SELECT id, email, password FROM users WHERE email = :email";
        
        if($stmt = $pdo->prepare($sql)){
            // Vezuje promenljive za selektovane izjave
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            
            
            $param_email = trim($_POST["email"]);
            
            
            if($stmt->execute()){
                // Porvera da li korisničko ima postoji, ukoliko postoji verifikuje šifru
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            
                            session_start();
                            
                            // čuva podatke o logovanju
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;                            
                            
                            
                            header("location: user.php");
                        } else{
                            
                            $password_err = "Neispravna šifra.";
                        }
                    }
                } else{
                    
                    $username_err = "Ne postoji nalog.";
                }
            } else{
                echo "Neuspešno, pokušajte ponovo.";
            }
        }
        
        
        unset($stmt);
    }
    
    
    unset($pdo);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel = "stylesheet" type="text/css" href="stilizacija.css">
</head>
<body class="stil">
    <div class="wrapper">
        <h2 style="text-align: center; width: 200px; margin: auto;">Uloguj se</h2><br>
        <form style="text-align: center; width: 200px; margin: auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            
                <label style="float: left;">E-mail</label>
                <input type="text" name="email" style="float: right;" value="<?php echo $email;?>">
                <span class="error"><?php echo $email_err;?></span>
                <br><br>
            
                <label style="float: left;">Šifra</label>
                <input type="password" name="password" style="float: right;" class="form-control">
                <span class="error"><?php echo $password_err; ?></span>
                <br><br>
            
                <input type="submit" class="btn btn-primary" value="Uloguj se">
            
        </form>
    </div>
</body>
</html>