<?php
// Include connect fajl
require_once "connect.php";
 
// Definisanje promenljivih
$name = $email = $username = $password = $confirm_password = "";
$name_err = $email_err = $username_err = $password_err = $confirm_password_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
//Validacija Imena
    if (empty($_POST["name"])) {
    $name_err = "Unesite ime";
  } else {
    $name = test_input($_POST["name"]);
    // dalja provera da li sadrži slova i razmak _
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $name_err = "Samo slova i razmak"; 
    }
  }
  
  //Validacija emaila
  if (empty($_POST["email"])) {
    $email_err = "Unesite e-mail adresu";
  } else {
    $email = test_input($_POST["email"]);
    // 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Neispravan e-mail"; 
    }
  }
 
    // Validacija korisničkog imena
    if(empty(trim($_POST["username"]))){
        $username_err = "Unesite korisničko ime";
    } else{
        
        $sql = "SELECT id FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            // Vezivanje varijabli za parametre
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            
            
            $param_username = trim($_POST["username"]);
            
            //Izvršava i provera da li korisničko ime već postoji
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "Izaberite drugo korisničko ime";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Pokušajte ponovo!";
            }
        }
         
        
        unset($stmt);
    }
    
    // Vallidacija šifre
    if(empty(trim($_POST["password"]))){
        $password_err = "Unesite šifru";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Minimum 6 karaktera!";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validacija potvrde šifre
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Potvrdite šifru";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Niste lepo popunili!";
        }
    }
    
    // Provera greški
    if(empty($name_err) && empty($email_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Ubacivanje u bazu
        $sql = "INSERT INTO users (name, email, username, password) VALUES (:name, :email, :username, :password)";
         
        if($stmt = $pdo->prepare($sql)){
            // Vezivanje varijabli
            $stmt->bindParam(":name", $param_name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            
            $param_name = $name;
            $param_email = $email;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            
            //Pokušaj da se sprovede 
            if($stmt->execute()){
                
                header("location: login.php");
            } else{
                echo "Neuspešno, pokušajte ponovo!";
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
    <title>Registracija</title>
    <link rel = "stylesheet" type="text/css" href="stilizacija.css">
</head>
<body class="stil">
    <div class="wrapper">
        <h2 style="text-align: center; width: 230px; margin: auto;">Registruj se</h2><br>
        <form style="text-align: center; width: 250px; margin: auto;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                
                <label style="float: left; ">Ime</label>
                <input type="text" name="name" style="float: right; " value="<?php echo $name;?>">
                <span class="error"><?php echo $name_err;?></span>
                <br><br>

                <label style="float: left; ">E-mail</label>
                <input type="text" name="email" style="float: right; " value="<?php echo $email;?>">
                <span class="error"><?php echo $email_err;?></span>
                <br><br>

                <label style="float: left; ">Korisničko ime</label>
                <input type="text" name="username" style="float: right; " class="form-control" value="<?php echo $username; ?>">
                <span class="error"><?php echo $username_err; ?></span>
                <br><br>
            
                <label style="float: left; ">Šifra</label>
                <input type="password" name="password" style="float: right; " class="form-control" value="<?php echo $password; ?>">
                <span class="error"><?php echo $password_err; ?></span>
                <br><br>
            
                <label style="float: left; ">Potvrdi šifru</label>
                <input type="password" name="confirm_password" style="float: right; " class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="error"><?php echo $confirm_password_err; ?></span>
                <br><br>
            
                <input type="submit" class="btn btn-primary" value="Kreiraj">
            
        </form>
    </div>    
</body>
</html>