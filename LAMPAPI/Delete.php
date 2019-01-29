<?php

	// Expects input as JSON in the format {"userID":"", "Name":"", "ID":}

	$inData = getRequestInfo();
	
	$name = "";

	$conn = new mysqli("localhost", "copcom_admin", "Paul4Change!", "copcom_Contacts");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql = "SELECT * FROM Contacts WHERE userID=" . $inData["userID"] . " AND Name='" . $inData["Name"] . "' AND ID=" . $inData["ID"];
		$result = $conn->query($sql);
		if ($result->num_rows == 0)
		{
			returnWithError( "Contact Not Found" );
		}
		else
		{
			$sql = "DELETE FROM Contacts WHERE userID=" . $inData["userID"] . " AND Name='" . $inData["Name"] . "' AND ID=" . $inData["ID"];
			$result = $conn->query($sql);
			returnWithInfo( "Successful Deletion" );
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
		$retValue = '{"name":0,"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $name )
	{
		$retValue = '{"name":' . $name . ',"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>