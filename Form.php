<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername="localhost";
    $username="root";
    $password="nilesh";
    $dbName="USER_DATA";
    $tbName="DATA";
$fname=$lname=$email=$gender=$education=$skill="";
$fnameErr=$lnameErr=$emailErr=$genderErr=$educationErr=$skillErr="";
$var=true;

function validateData()
{
    global $fname,$lname,$email,$gender,$education,$skill;
   global $fnameErr,$lnameErr,$emailErr,$genderErr,$educationErr,$skillErr,$var;
        //check fix name
if(!empty($_POST["f_name"]))
{
$fname=test($_POST["f_name"]);
if(!preg_match('/[a-zA-Z\s*]*/', $fname))
{$fnameErr="Only alphabets and white spaces are allowed";$var=false;}

}
else
{$fnameErr="First Name is required";  $var=false;}

//check fix last name
if(!empty($_POST["l_name"]))
{
$lname=test($_POST["l_name"]);
if(!preg_match('/[a-zA-Z\s*]*/', $lname))
{$lnameErr="Only alphabets and white spaces are allowed"; $var=false;}

}
else
{$lnameErr="Last Name is required";  $var=false;}

//check email
if(!empty($_POST["email"]))
{
$email=test($_POST["email"]);
if(!filter_var($email,FILTER_VALIDATE_EMAIL))
{
    $var=false;
    $emailErr="Invalid Email";
}
else{
if(!validateEmail($email))
{$emailErr="email already in use";  $var=false;}

}
}
else
{$emailErr="Email is required";  $var=false;  }

//check gender

if(empty($_POST["gender"])||!isset($_POST["gender"]))
{
$genderErr="Gender should be selected";   $var=false;
}else
{ $gender=$_POST["gender"];   }

//educatiom
$education=$_POST["education"];

//$skill=htmlspecialchars($_POST["sk"]);
if(!empty($_POST["sk"]))
{
   $skill=$_POST["sk"]; 
}
else
{
    $var=false;
    $skillErr="skill should be mentioned";
}


return $var;
}

function test($name)
{
    $name=trim($name);
    $name=stripslashes($name);
    return $name;
}

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $choice=validateData();
    if($choice)
        addData();
    else
        echo "submit form properly";
}

function addData()
{
/*
header('HTTP/1.1 307 Temporary Redirect');
header('Location: /Basic Php/testdelete.php');*/

global $fname,$lname,$email,$gender,$education,$skill,$servername,$username,$password,$dbName,$tbName;

    
    $conn=new mysqli($servername,$username,$password);
   
    //check connection
    if($conn->connect_error)
    {
          die("error while connection".$conn->connect_error);
    }
    else
      { //echo "connected successfully !!"; 
    }

    //create database
    $crdb="CREATE DATABASE IF NOT EXISTS ".$dbName;
    if($conn->query($crdb))
     {   //echo "<br/>database created"; 
    }
    else
      {  //echo "<br/>error during database creation";  
    }

    //create table
    $crtble="CREATE TABLE IF NOT EXISTS ".$dbName.".".$tbName."(
     ID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     FIRST_NAME VARCHAR(30) NOT NULL,
     LAST_NAME VARCHAR(40)  NOT NULL,
     EMAIL VARCHAR(40) NOT NULL,
     GENDER VARCHAR(5) NOT NULL,
     EDUCATION VARCHAR(30) NOT NULL,
     SKILL VARCHAR(100)
     )";
     if($conn->query($crtble))
      { // echo "<br/> table created";
      }
    else
       { //echo "<br/>error during table crearion"; 
       }

    //insert data
    $instr=
    $st=$conn->prepare("INSERT INTO ".$dbName.".".$tbName."(FIRST_NAME,LAST_NAME,EMAIL,GENDER,EDUCATION,SKILL) VALUES(?,?,?,?,?,?)");
    $st->bind_param("ssssss",$fname,$lname,$email,$gender,$education,$skill);
    $st->execute();
    echo "<script>
      alert('user data added to database');
    </script>";
    $fname=$lname=$email=$gender=$education=$skill="";
    $st->close();
    $conn->close();
  
}

function validateEmail($email)
{
  global $servername,$username,$password,$dbName,$tbName;
     $n=true;
    $conn=new mysqli($servername,$username,$password);
   
    //check connection
    if($conn->connect_error)
    {
          die("error while connection".$conn->connect_error);
    }
    else
      { //echo "connected successfully !!";   
    }
    
    //result 
    $sq="SELECT EMAIL FROM ".$dbName.".".$tbName;
    $result=$conn->query($sq);
    if($result->num_rows>0)
    {
         while($rows=$result->fetch_assoc())
         {
            if($rows["EMAIL"]==$email)
            {
                $n=false; break;
            }
         }
    }
    $conn->close();
     return $n;
}

?>



<html>
<head>
<title>User Entry</title>
<link rel="stylesheet" type="text/css" href="Form.css">

</head>
<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<div id="form">
	<div id="top"><h1>ADD USER</h1></div>
	<p>* fields are mandatory</p><br/>
	<div id="f_name">
		<label>First Name:</label><br/>
		<input type="text" name="f_name" value="<?php echo $fname; ?>"><label class="msg"><?php echo "*".$fnameErr; ?></label>
		<br/>
	</div>
	<div id="l_name">
	<label>Last Name:</label><br/>
    <input type="text" name="l_name" value="<?php echo $lname; ?>"><label class="msg"><?php echo "*".$lnameErr; ?></label>
    <br/>
	</div>
	<div id="email">
	<label>Email:</label><br/>
    <input type="text" name="email" value="<?php echo $email; ?>"><label class="msg"><?php echo "*".$emailErr; ?></label>
    <br/>
	</div>
	<div id="gender">
	<label>Gender:</label><br/>
    <input type="radio" name="gender" value="M" <?php if($_SERVER["REQUEST_METHOD"]=="POST"){if($gender=="M"&&isset($gender))echo "checked"; } ?> ><label>Male</label>&nbsp; &nbsp;
    <input type="radio" name="gender" value="F" <?php if($_SERVER["REQUEST_METHOD"]=="POST"){if($gender=="F"&&isset($gender))echo "checked";} ?>><label>Female
    </label><label class="msg"><?php echo "  *".$genderErr; ?></label>
    <br/>
    </div>
    <div id="education">
    <label>Education:</label><br/>
    <select name="education">
       <option value="BE">BE</option>
       <option value="B-Tech">B-Tech</option>
       <option value="M-Tech">M-Tech</option>
       <option value="B-Sc">BSc</option>
       <option value="BCA">BCA</option>
    </select>
    </div>
    <div id="skill">
    	<label>Skill:</label><br/>
    	<textarea name="sk" rows="5" cols="30"><?php if($_SERVER["REQUEST_METHOD"]=="POST")echo $skill;?></textarea>
        <label class="msg"><?php if($_SERVER["REQUEST_METHOD"]=="POST")echo "*".$skillErr; ?></label>
    	<br/><br/>
    </div>
	<button type="submit" id="button" class="but">SUBMIT</button>
    <a  id="button1" class="but" target="_blank" href="listUser.php">List Users</button>
	</div>
	</form>
</body>
</html>

