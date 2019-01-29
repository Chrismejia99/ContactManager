<?php

	// Expects input as JSON in the format {"Name":"", "phone":"", "email":"", "userID":""}

	$inData = getRequestInfo();
	
	$id = 0;

	$conn = new mysqli("localhost", "copcom_admin", "Paul4Change!", "copcom_Contacts");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql = "SELECT * FROM Contacts WHERE Name='" . $inData["Name"] . "' AND phone='" . $inData["phone"] . "' AND email='" . $inData["email"] . "' AND userID=" . $inData["userID"];
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$id = $row["ID"];
			
			returnWithError( "Contact Already Present" );
		}
		else
		{
			$sql = "INSERT INTO Contacts (Name, phone, email, userID) Values ('" . $inData["Name"] . "', '" . $inData["phone"] . "', '" . $inData["email"] . "', '" . $inData["userID"] . "')";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
     		$id = $row["ID"];
	    	returnWithInfo($id );
		}
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
	
	function returnWithInfo( $id )
	{
		$retValue = '{"id":' . $id . ',"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>