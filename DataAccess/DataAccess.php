<?php
require("../Entities/YearTop.php");
require("../Entities/PieChartData.php");
 if (isset($_POST['GetPieChartData'])) {
        echo GetPieChartData($_POST['GetPieChartData']);
    }


if (isset($_POST['GetYearData'])) {
        echo GetYearData();
    }

function GetPieChartData($year)
{
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "wiki_data";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
    //return $year;

	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "SELECT 'Vandal Users' AS uType, COUNT(DISTINCT username) AS val FROM  vandalusers 
			where year(revtime) = " . $year . 
			" UNION 
			SELECT 'Benign Users' AS uType, COUNT(DISTINCT username) AS val FROM  benignusers 
			where year(revtime) = ". $year;
    //return $sql;
    $array = array("#F44336","#009688");
    
	
	//echo json_encode($array);
	$result = $conn->query($sql);
	$itemsData = array();
	if ($result->num_rows > 0) {
		$i =  0;
	    while($row = $result->fetch_assoc()) {
	    		
	    		$toAdd = new PieChartData();
	    		$toAdd->StatusID = $row["uType"];
	    		$toAdd->Name = $row["uType"]. " count ";
				$toAdd->Val = $row["val"]*1;
				$toAdd->Color = $array[$i];
				$toAdd->ExtraParams = "No Extra in this";
				$itemsData[] = $toAdd;
				$i++;
				
	    }

	}

return json_encode($itemsData);
//echo $itemsData;

$conn->close();
}





function GetYearData()
{
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "wiki_data";
	$conn = mysqli_connect($servername, $username, $password, $dbname);


	if (!$conn) {
	    die("Connection failed: " . mysqli_connect_error());
	}

	$sql = "select 
	  YearAndMonth,MainCategoty, SubCatrgory,CountOfUniqueHits
 from topicsByTime where YearAndMonth != 2013
 and YearAndMonth != 2014 order by ID";
    
  
    
	
	//echo json_encode($array);
	$result = $conn->query($sql);
	$itemsData = array();
	if ($result->num_rows > 0) {
		$i =1;
	    while($row = $result->fetch_assoc()) {
	    		
	    		$toAdd = new YearTop();
	    		$toAdd->x = $i;
	    		$i++;
	    		$toAdd->MainCategory = $row["MainCategoty"];
				$toAdd->SubCategory = $row["SubCatrgory"];
				$toAdd->y = ($row["CountOfUniqueHits"]*1);
				$toAdd->size = ($row["CountOfUniqueHits"]*1);
				
				$itemsData[] = $toAdd;
				
				
	    }

	}

return json_encode($itemsData);
//echo $itemsData;

$conn->close();
}


?>