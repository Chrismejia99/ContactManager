<?php

	// Query All, This page should give all the contacts that are connected to the current user 
	// this is currently garbage below and is still in progress
	
	$inData = getRequestInfo();
	
	$name = "";

	//This is how we use MySQL to connect to the database with the appropriate credentials 
	$conn = new mysqli("localhost", "copcom_admin", "Paul4Change!", "copcom_Contacts");
	//If we couldn't connect, it gives error saying so
	if ($conn->connect_error)
	{
		returnWithError( $conn->connect_error );
	} 
	//Otherwise
	//Read in the appopriate ID for the current User
	else
	{
		//I want the ID from the current user
		$sql = "SELECT Username FROM Users where Username='" . $inData["login"] . "' and Password='" . $inData["password"] . "'";
		$result = $conn->query($sql);
		//$sql = "SELECT ID FROM Users where Username='" . $inData["login"] . "' and Password='" . $inData["password"] . "'"; 
		
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			//$name = $row["Username"];
			//echo $id;

			
			//returnWithInfo($name );
			returnWithInfo($row[]);
		}
		else
		{
			returnWithError( "No Records Found" );
		}
		
		//$sql = "SELECT (Name, phone, email) FROM Contacts where userID='" . $inData["login"] . "' and Password='" . $inData["password"] . "'";
		$conn->close();
	}
	
	function getRequestInfo()
	{
		return json_decode(file_get_contents('php://input'), true);
	}

	function sendResultInfoAsJson( $obj )
	{
		header('Content-type: application/json');
		echo $obj;
	}
	
	function returnWithError( $err )
	{
		$retValue = '{"id":0,"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $row )
	{
		$retValue = '{"row": "' . $row . '","error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>