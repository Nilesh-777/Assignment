
<html>
<head>
<style type="text/css">
 /*  div,h1,table
   {
   	display: block;
   }*/
   body{
   	background-color: #FFF6CC;
   }
	#list{

		margin:50px auto;
		width:100%;
		background-color: white;
		box-shadow: 5px 5px 25px grey;
		text-align: center;
		background-color: lightblue;
	}
	#top{
		text-align: center;
		text-shadow: 0px 0px 5px red;
		
	}
	table{
		border-collapse: collapse;
		width:100%;
	}
	td,th{
		text-align: left;
		border:1px solid #dddddd;
		padding:10px;
	}
	tr:nth-child(even){
		background-color: #dddddd;
	}
</style>
</head>
<body>

<div id="list">

	<table>
	<caption><h1 id="top">REGISTERED USERS</h1></caption>
		<tr>
			<th>FIRST NAME</th>
			<TH>LAST NAME</TH>
			<TH>E-MAIL</TH>
			<TH>GENDER</TH>
			<TH>EDUCATION</TH>
			<TH>SKILLS</TH>
		</tr>
          <?php
          $servername="localhost";
$username="root";
$password="nilesh";
$dbName="user_data";
$tbName="data";
//connection
$conn=new mysqli($servername,$username,$password,$dbName);

if($conn->connect_error)
die("connection error ".$conn->connect_error);
else
//echo "<br/>connected successfully";

//select query
$sq="select*from ".$dbName.".".$tbName;
$result=$conn->query($sq);

//display result
if($result->num_rows>0)
{

    while($rowdata = $result->fetch_assoc())
    {
    	echo "<tr>
    	      <td>".$rowdata["FIRST_NAME"]."</td>
    	       <td>".$rowdata["LAST_NAME"]."</td>
    	       <td>".$rowdata["EMAIL"]."</td>
    	       <td>".$rowdata["GENDER"]."</td>
    	       <td>".$rowdata["EDUCATION"]."</td>
               <td>".$rowdata["SKILL"]."</td>
             </tr>
    	";
    }

}
else
echo "<tr>no result</tr>";

          ?>
	</table>
	</div>
</body>
</html>