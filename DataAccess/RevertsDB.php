<?php
require("../Entities/reverts.php");

 if (isset($_POST['GetReverts'])) {
        echo GetReverts($_POST['GetReverts'],$_POST['GetYearReverts']);
    }


    if (isset($_POST['GetRevertsLimitPages'])) {
        echo GetRevertsLimitPages($_POST['status'],$_POST['year'],$_POST['min'],$_POST['max']);
    }



function GetRevertsLimitPages($status,$year,$min,$max)
{
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "wiki_data";
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	//return $status . " " . $year . " " . $min . " " . $max ;
	
	if($status == "Vandal Users")
	{
		$status = " vandalusers ";
	}
	else
	{
		$status = " benignusers ";
	}

	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	//return $status; 
 // -- where year(revtime) = 2014
	$sql = "SELECT UserName, count(username) AS Pages,
			COUNT(CASE WHEN IsReverted = 'true' then 1 ELSE NULL END) as Reverts
            ,count(distinct pagetitle) as d
			FROM "  .  $status  . " where year(revtime) = " . $year . " 
			 GROUP BY UserName 
             having count(distinct pagetitle)  >= ".  $min . " AND  count(distinct pagetitle)  <= " . $max .
            " ORDER BY  count(distinct pagetitle) DESC
            
            ";
   
   // return $sql;
	$itemsData = array();

	//echo json_encode($array);
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	
	    while($row = $result->fetch_assoc()) {
	    		
	    		$toAdd = new Reverts();
	    		$toAdd->label = $row["UserName"];
	    		$toAdd->id = $row["UserName"];
	    		$toAdd->x = $row["Pages"];
				$toAdd->y = $row["Reverts"];
				$itemsData[] = $toAdd;
				
	    }

	}

return json_encode($itemsData);
//echo $itemsData;

$conn->close();
}


function GetReverts($status,$year)
{

	//return $year;
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "wiki_data";
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	//return $status;
	
	if($status == "Vandal Users")
	{
		$status = " vandalusers ";
	}
	else
	{
		$status = " benignusers ";
	}

	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	//return $status; 
 // -- where year(revtime) = 2014
	$sql = "SELECT UserName, count(username) AS Pages,
			COUNT(CASE WHEN IsReverted = 'true' then 1 ELSE NULL END) as Reverts
            ,count(distinct pagetitle) as d
			FROM "  .  $status  . " where year(revtime) = " . $year . " 
			 GROUP BY UserName 
            -- having count(distinct pagetitle)  > 10
            ORDER BY  count(distinct pagetitle) DESC
            
            ";
   
   // return $sql;
	$itemsData = array();

	//echo json_encode($array);
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	
	    while($row = $result->fetch_assoc()) {
	    		
	    		$toAdd = new Reverts();
	    		$toAdd->label = $row["UserName"];
	    		$toAdd->id = $row["UserName"];
	    		$toAdd->x = $row["Pages"];
				$toAdd->y = $row["Reverts"];
				$itemsData[] = $toAdd;
				
	    }

	}

return json_encode($itemsData);
//echo $itemsData;

$conn->close();
}

?>