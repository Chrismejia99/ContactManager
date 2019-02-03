
var urlBase = 'http://COP4331-4.com/LAMPAPI';
var extension = "php";

var userId = 0;

function doLogin()
{
	userId = 0;

	var login = document.getElementById("loginName").value;
	var password = document.getElementById("loginPassword").value;

	// document.getElementById("loginResult").innerHTML = "";

	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';
	var url = urlBase + '/Login.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.send(jsonPayload);

		var jsonObject = JSON.parse( xhr.responseText );

		userId = jsonObject.id;

		if( userId < 1 )
		{
			// document.getElementById("loginResult").innerHTML = "User/Password combination incorrect";
			return;
		}

		document.getElementById("loginName").value = "";
		document.getElementById("loginPassword").value = "";


    // will have to load contacts, set contact id attributes
		hideOrShow( "loggedInDiv", true);
		hideOrShow( "loginDiv", false);
		loadContacts();
	}
	catch(err)
	{
		// document.getElementById("loginResult").innerHTML = err.message;
		return;
	}

}

function doLogout()
{
	userId = 0;

	hideOrShow( "loggedInDiv", false);
	hideOrShow( "loginDiv", true);
}

function createUser()
{
  var login = document.getElementById("loginName").value;
  var password = document.getElementById("loginPassword").value;

	var jsonPayload = '{"login" : "' + login + '", "password" : "' + password + '"}';
	var url = urlBase + '/Signup.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				document.getElementById("addUser").innerHTML = "User has been created";
			}
		};

		xhr.send(jsonPayload);

		doLogin();
	}
	catch(err)
	{
		document.getElementById("addUser").innerHTML = err.message;
	}
}

function hideOrShow( elementId, showState )
{
	var vis = "visible";
	var dis = "block";
	if( !showState )
	{
		vis = "hidden";
		dis = "none";
	}

	document.getElementById( elementId ).style.visibility = vis;
	document.getElementById( elementId ).style.display = dis;
}
function loadContacts()
{
	var url = urlBase + '/Search.' + extension;
	var jsonPayload = '{"userID" : "' + userId + '","name": "","phone": "","email": ""}';
	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.send(jsonPayload);

		var jsonObject = JSON.parse( xhr.responseText );

		if(jsonObject.id == 0)
			return;

		var i = 0;
		var contactIndex;

		for(contactIndex = 1; contactIndex <= jsonObject.results.length/3; contactIndex++)
		{
			var nameID = "name" + contactIndex;
			var phoneID = "phone" + contactIndex;
			var emailID = "email" + contactIndex;
			var contactDivID = "contact" + contactIndex;
			document.getElementById(nameID).innerHTML = jsonObject.results[i++];
			document.getElementById(phoneID).innerHTML = jsonObject.results[i++];
			document.getElementById(emailID).innerHTML = jsonObject.results[i++];
			document.getElementById( contactDivID ).style.display = "block";
		}

	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}

}
function addContact()
{
	var newName = document.getElementById("name").value;
  var newPhone = document.getElementById("phone").value;
  var newEmail = document.getElementById("email").value;

	document.getElementById("name").value = "";
  document.getElementById("phone").value = "";
  document.getElementById("email").value = "";

	// document.getElementById("colorAddResult").innerHTML = "";

	var jsonPayload = '{"name" : "' + newName + '", "phone" : "' + newPhone + '", "email" : "' + newEmail + '", "userID" : ' + userId + '}';
	var url = urlBase + '/Add.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				// change this to add contact HTML and display all the contacts
				// document.getElementById("colorAddResult").innerHTML = "Color has been added";
				hideOrShow("addContactDiv", false);
				hideOrShow("loggedInDiv", true);
				loadContacts();
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("colorAddResult").innerHTML = err.message;
	}

}
function deleteContact(contactId)
{
  // will probably unique ID from contact HTML


  // var jsonPayload = '{"userId" : "' + userId + '", "Name" : "' + contactName + '"}';
	var url = urlBase + '/Delete.' + extension;
	var jsonPayload = '{"userId" : "' + userId + '",  "contactId" : "' + contactId + '"}';

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, false);
}
function searchContact()
{
  // might have to make all html in one page to preserve userId, otherwise new document will reset it's id to 0
	var srch = document.getElementById("searchText").value;
	document.getElementById("colorSearchResult").innerHTML = "";

	var colorList = document.getElementById("colorList");
	colorList.innerHTML = "";

	var jsonPayload = '{"search" : "' + srch + '"}';
	var url = urlBase + '/SearchColors.' + extension;

	var xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				hideOrShow( "colorList", true );

				document.getElementById("colorSearchResult").innerHTML = "Color(s) has been retrieved";
				var jsonObject = JSON.parse( xhr.responseText );

				var i;
				for( i=0; i<jsonObject.results.length; i++ )
				{
					var opt = document.createElement("option");
					opt.text = jsonObject.results[i];
					opt.value = "";
					colorList.options.add(opt);
				}
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("colorSearchResult").innerHTML = err.message;
	}

}
