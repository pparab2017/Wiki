<?php 

require("../Entities/NodeLink.php");

 if (isset($_POST['GetNodeLink'])) {
        echo GetNodeLink($_POST['GetNodeLink'],$_POST['status']);
    }



function GetNodeLink($string,$status)
{

	//return "year";
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "wiki_data";
	$conn = mysqli_connect($servername, $username, $password, $dbname);

	if($status == "Vandal Users")
	{
		$status = " vandalusers ";
	}
	else
	{
		$status = " benignusers ";
	}
	
	
	//return $status; 
 // -- where year(revtime) = 2014
	$sql = "select username,pagetitle,revtime,isReverted from " .$status. " where find_in_set(username,'". $string ."' ) > 0 ";
   
    //return $sql;
	$itemsData1 = array();
	$itemsData2 = array();

	$returnObj = array();
	//echo json_encode($array);
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	
	    while($row = $result->fetch_assoc()) {
	    		
	    		$toAdd1 = new obj1();
	    		$toAdd2 = new obj2();
	    		
	    		$toAdd1->user = $row["username"];
	    		$toAdd1->pages = $row["pagetitle"];
	    		$toAdd1->time = $row["revtime"];
	    		$toAdd1->rev = $row["isReverted"];
				$itemsData1[] = $toAdd1;
				
				$toAdd2->user = $row["username"];
	    		$toAdd2->pages = $row["pagetitle"];
	    		$toAdd2->rev = $row["isReverted"];
				$itemsData2[] = $toAdd2;
				
	    }
	    $returnObj[] = $itemsData1;
		$returnObj[] = $itemsData2;
	}

return json_encode($returnObj);
//echo $itemsData;

$conn->close();
}


?>