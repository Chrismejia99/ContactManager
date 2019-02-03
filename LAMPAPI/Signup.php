<?php

	$inData = getRequestInfo();

	$s1 = $inData["login"];
	$s2 = $inData["password"];
	$hash = hash("md5", $s2);
	
	$id = 0;

	$conn = new mysqli("localhost", "copcom_admin", "Paul4Change!", "copcom_Contacts");
	if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	} 
	else
	{
		$sql = "SELECT ID FROM Users WHERE Username='" . $s1 . "'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			$id = $row["ID"];

			returnWithError("Username already exists");
			
		}
		else
		{
			$sql = "INSERT INTO Users (Username, Password) Values ('" . $inData["login"] . "' , '" . $hash . "')";

			$conn->query($sql);
			$sql = "SELECT ID FROM Users WHERE Username='" . $s1 . "'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$id = $row["ID"];

			returnWithInfo($id);

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