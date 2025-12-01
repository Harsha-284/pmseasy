////////////////////////////////  ADD FIELDS //////////////////////////////////////
	function addcontactfield(div)
	{
		var t1 = document.createElement("input");
		t1.setAttribute("type", "text");
		t1.setAttribute("class","person");
		t1.setAttribute("name",div+"person[]");
		
		var t2 = document.createElement("input");
		t2.setAttribute("type", "text");
		t2.setAttribute("class","email");
		t2.setAttribute("name",div+"email[]");
		
		var t3 = document.createElement("input");
		t3.setAttribute("type", "text");
		t3.setAttribute("class","contact");
		t3.setAttribute("name",div+"contact[]");
		
		document.getElementById(div+"person").appendChild(t1);
		document.getElementById(div+"email").appendChild(t2);
		document.getElementById(div+"contact").appendChild(t3);
	}

	function addhotel()
	{
		var newid = parseInt(document.getElementById('lastid').value)+1;
		
		var d1 = document.createElement("div");
		d1.setAttribute("class", "col-xs-2");
		d1.setAttribute("style", "padding-right:0px;");
			var d11 = document.createElement("div");
			d11.setAttribute("class", "form-group");
				var t11 = document.createElement("input");
				t11.setAttribute("class", "form-control");
				t11.setAttribute("name", "hotel[]");
			d11.appendChild(t11);
		d1.appendChild(d11);

		var d2 = document.createElement("div");
		d2.setAttribute("class", "col-xs-2");
		d2.setAttribute("style", "padding-right:0px;");
			var d21 = document.createElement("div");
			d21.setAttribute("class", "form-group");
				var t21 = document.createElement("input");
				t21.setAttribute("class", "form-control");
				t21.setAttribute("name", "conper[]");
			d21.appendChild(t21);
		d2.appendChild(d21);

		var d3 = document.createElement("div");
		d3.setAttribute("class", "col-xs-2");
		d3.setAttribute("style", "padding-right:0px;");
			var d31 = document.createElement("div");
			d31.setAttribute("class", "form-group");
				var t31 = document.createElement("input");
				t31.setAttribute("class", "form-control");
				t31.setAttribute("name", "email[]");
				t31.setAttribute("type", "text");
			d31.appendChild(t31);
		d3.appendChild(d31);
		
		var d4 = document.createElement("div");
		d4.setAttribute("class", "col-xs-2");
		d4.setAttribute("style", "padding-right:0px;");
			var d41 = document.createElement("div");
			d41.setAttribute("class", "form-group");
				var t41 = document.createElement("input");
				t41.setAttribute("class", "form-control");
				t41.setAttribute("name", "password[]");
			d41.appendChild(t41);
		d4.appendChild(d41);
		
		var d5 = document.createElement("div");
		d5.setAttribute("class", "col-xs-2");
		d5.setAttribute("style", "padding-right:0px;");
			var d51 = document.createElement("div");
			d51.setAttribute("class", "form-group");
				var t51 = document.createElement("input");
				t51.setAttribute("class", "form-control");
				t51.setAttribute("name", "contact[]");
			d51.appendChild(t51);
		d5.appendChild(d51);
		
		var d6 = document.createElement("div");
		d6.setAttribute("class", "col-xs-2");
			var d61 = document.createElement("div");
			d61.setAttribute("class", "form-group");
				var d62 = document.createElement("div");
				d62.setAttribute("style", "margin-left:-10px");
					var d63 = document.createElement("div");
					d63.setAttribute("class", "radio-6");
						var t61 = document.createElement("input");
						t61.setAttribute("type", "radio");
						t61.setAttribute("class", "form-control");
						t61.setAttribute("name", "bulkbooking"+newid);
						t61.setAttribute("value", "1");
						t61.setAttribute("checked", "checked");
					d63.appendChild(t61);
					d63.innerHTML += "Yes";
					var d64 = document.createElement("div");
					d64.setAttribute("class", "radio-6");
						var t62 = document.createElement("input");
						t62.setAttribute("type", "radio");
						t62.setAttribute("class", "form-control");
						t62.setAttribute("name", "bulkbooking"+newid);
						t62.setAttribute("value", "0");
					d64.appendChild(t62);
					d64.innerHTML += "No";
				d62.appendChild(d63);
				d62.appendChild(d64);
				var d65 = document.createElement("div");
				d65.setAttribute("style", "clear:both");
			d61.appendChild(d62);
			d61.appendChild(d65);
		d6.appendChild(d61);

		document.getElementById("newhotels").appendChild(d1);
		document.getElementById("newhotels").appendChild(d2);
		document.getElementById("newhotels").appendChild(d3);
		document.getElementById("newhotels").appendChild(d4);
		document.getElementById("newhotels").appendChild(d5);
		document.getElementById("newhotels").appendChild(d6);

		document.getElementById('lastid').value = newid;
	}

	function setroomtypes(nr)
	{
		if(nr != "")
		{
			z = parseInt(nr);
			document.getElementById("roomtypeblock").innerHTML = "<div class='row'><div class='col-xs-9'><label>Room Type</label></div><div class='col-xs-3'><label>No. of Rooms</label></div></div>";
			for(i=1; i<=z; i++)
			{
				document.getElementById("roomtypeblock").innerHTML += "<div class='row'><div class='col-xs-9'><div class='form-group'><input type='text' class='form-control' name='roomtypes[]' title='Enter the room type e.g. Deluxe' placeholder='E.g. Deluxe'></div></div><div class='col-xs-3'><div class='form-group'><input type='text' class='form-control' name='numofrooms[]' title='Enter the number of rooms in this type e.g. 5' placeholder='E.g. 5'></div></div></div>";
			}
			document.getElementById("roomtypeblock").innerHTML += "<div class='row'><div class='col-xs-12'><input type='submit' class='btn btn-primary' value='Confirm'></div></div>";
		}
	}

	function addroomnumber()
	{
		i = 0;
		while(document.getElementById("rn"+i))
			i++;
		
		var d1 = document.createElement("div");
		d1.setAttribute("id","rn"+i);
			var d2 = document.createElement("div");
			d2.setAttribute("class","col-xs-10");
				var d3 = document.createElement("div");
				d3.setAttribute("class","form-group");
					var t1 = document.createElement("input");
					t1.setAttribute("type","text");
					t1.setAttribute("name","newroomnumbers[]");
					t1.setAttribute("class","form-control");
					t1.setAttribute("placeholder","E.g 01");
				d3.appendChild(t1);
			d2.appendChild(d3);
			var d4 = document.createElement("div");
			d4.setAttribute("class","col-xs-2");
				var a1 = document.createElement("a");
				a1.setAttribute("href","javascript:void(0)");
				a1.setAttribute("title","Delete this room number");
				a1.setAttribute("onClick","deleteroom("+i+")");
					var s1 = document.createElement("span");
					s1.setAttribute("class","label label-danger");
						var i1 = document.createElement("i");
						i1.setAttribute("class","fa fa-times");
					s1.appendChild(i1);
				a1.appendChild(s1);
			d4.appendChild(a1);
		d1.appendChild(d2);
		d1.appendChild(d4);
		
		document.getElementById("newroomnumbers").appendChild(d1);
	}
	
	function deleteroom(id)
	{
		rn				= document.getElementById("rn"+id);
		rnform			= document.getElementById("rnform");
		newroomnumbers	= document.getElementById("newroomnumbers");
		
		if(newroomnumbers.contains(rn))
			newroomnumbers.removeChild(rn);
		else
			rnform.removeChild(rn);
	}

	function addluxuryslots()
	{
		var newid = 1;
		while(document.getElementById("rrf"+newid))
			newid++;
		
		var lastid = newid-1;
		
		if(document.getElementById("rrf"+lastid).value!="" && document.getElementById("rtf"+lastid).value!="" && document.getElementById("pv"+lastid).value!="")
		{
			var d0 = document.createElement("div");
			d0.setAttribute("id","slot"+newid);
				var d1 = document.createElement("div");
				d1.setAttribute("class","col-xs-4");
					var d2 = document.createElement("div");
					d2.setAttribute("class","form-group");
						var t1 = document.createElement("input");
						t1.setAttribute("type","number");
						t1.setAttribute("name","rrf[]");
						t1.setAttribute("id","rrf"+newid);
						t1.setAttribute("min",parseInt(document.getElementById("rtf"+lastid).value)+1);
						t1.setAttribute("max",parseInt(document.getElementById("rtf"+lastid).value)+1);
						t1.setAttribute("value",parseInt(document.getElementById("rtf"+lastid).value)+1);
						t1.setAttribute("readOnly","readOnly");
						t1.setAttribute("class","form-control");
					d2.appendChild(t1);
				d1.appendChild(d2);
				
				var d3 = document.createElement("div");
				d3.setAttribute("class","col-xs-4");
					var d4 = document.createElement("div");
					d4.setAttribute("class","form-group");
						var t2 = document.createElement("input");
						t2.setAttribute("type","number");
						t2.setAttribute("name","rrt[]");
						t2.setAttribute("id","rtf"+newid);
						t2.setAttribute("min","0");
						t2.setAttribute("class","form-control");
						t2.setAttribute("onChange","destroynext("+newid+")");
					d4.appendChild(t2);
				d3.appendChild(d4);
			
				var d5 = document.createElement("div");
				d5.setAttribute("class","col-xs-4");
					var d6 = document.createElement("div");
					d6.setAttribute("class","form-group");
						var t3 = document.createElement("input");
						t3.setAttribute("type","number");
						t3.setAttribute("name","pv[]");
						t3.setAttribute("id","pv"+newid);
						t3.setAttribute("min","0");
						t3.setAttribute("step","0.1");
						t3.setAttribute("class","form-control");
					d6.appendChild(t3);
				d5.appendChild(d6);
			
				d0.appendChild(d1);
				d0.appendChild(d3);
				d0.appendChild(d5);
			document.getElementById("slotblock").appendChild(d0);

			document.getElementById("errortext").style.display = "none";
		}
		else
		{
			document.getElementById("errortext").style.display = "block";
		}
	}
////////////////////////////////  ADD FIELDS //////////////////////////////////////

////////////////////////////////  GET DATA ////////////////////////////////////////
	function getstates(id,caller='adminpanel')
	{
		var ajaxRequest;
		
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		ajaxRequest.onreadystatechange = function()
		{
			if(ajaxRequest.readyState == 4)
			{
				var str = ajaxRequest.responseText;
				if(str != "")
					document.getElementById("state").innerHTML = str;
				else
					document.getElementById("state").innerHTML = "<option value=''>Select State</option>";
			}
		}
		if(caller == 'adminpanel')
			ajaxRequest.open("GET","ajaxproc.php?Pg=getstates&id="+id, true);
		else if(caller == 'app')
			ajaxRequest.open("GET","../ajaxproc.php?Pg=getstates&id="+id, true);

		ajaxRequest.send(null);
	}
	
	function getcities(id)
	{
		var ajaxRequest;
		
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		ajaxRequest.onreadystatechange = function()
		{
			if(ajaxRequest.readyState == 4)
			{
				var str = ajaxRequest.responseText;
				if(str != "")
					document.getElementById("city").innerHTML = str;
				else
					document.getElementById("city").innerHTML = "<option value=''>Select City</option>";
				document.getElementById("locality").innerHTML = "<option value=''>Select Locations</option>";
			}
		}
		ajaxRequest.open("GET","ajaxproc.php?Pg=getcities&id="+id, true);
		ajaxRequest.send(null);
	}

	function getlocations(id)
	{
		var ajaxRequest;
		
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		ajaxRequest.onreadystatechange = function()
		{
			if(ajaxRequest.readyState == 4)
			{
				var str = ajaxRequest.responseText;
				if(str != "")
					document.getElementById("locality").innerHTML = str;
				else
					document.getElementById("locality").innerHTML = "<option value=''>Select Location</option>";
			}
		}
		ajaxRequest.open("GET","ajaxproc.php?Pg=getlocations&id="+id, true);
		ajaxRequest.send(null);
	}
////////////////////////////////  GET DATA ////////////////////////////////////////

////////////////////////////////   GENERAL   //////////////////////////////////////
	function changepwd()
	{
		if(document.getElementById("cp").value==""){alert("You must enter the current password"); return false;}
		else if(document.getElementById("np1").value==""){alert("You must enter the new password"); return false;}
		else if(document.getElementById("np1").value!=document.getElementById("np2").value){alert("Retyped password mismatched"); return false;}
		else{return true;}
	}

	function checklocality(loc)
	{
		if(loc == 0)
		{
			document.getElementById('localitydiv').style.display='block';
			document.getElementById('newlocality').required = 1;
		}
		else
		{
			document.getElementById('localitydiv').style.display='none';
			document.getElementById('newlocality').required = 0;
		}
	}

	function distancefrom(id)
	{
		if(document.getElementById("landmark"+id).checked){
			document.getElementById("landmarkname"+id).disabled = 0;
			document.getElementById("distance"+id).disabled = 0;
		}
		else{
			document.getElementById("landmarkname"+id).value = "";
			document.getElementById("distance"+id).value = "";
			document.getElementById("landmarkname"+id).disabled = 1;
			document.getElementById("distance"+id).disabled = 1;
		}
	}

	function toggleroomtype(id,i)
	{
		var ajaxRequest;
		
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		ajaxRequest.onreadystatechange = function()
		{
			if(ajaxRequest.readyState == 4)
			{
				var str = ajaxRequest.responseText;
				//document.getElementById("toggleswitch"+i).checked = str;
			}
		}
		ajaxRequest.open("GET","ajaxproc.php?Pg=toggleroomswitch&id="+id, true);
		ajaxRequest.send(null);
	}

	function toggleswitch(flag)
	{
		var ajaxRequest;
		
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		ajaxRequest.onreadystatechange = function()
		{
			if(ajaxRequest.readyState == 4)
			{
				var str = ajaxRequest.responseText;
				//document.getElementById("toggleswitch"+i).checked = str;
			}
		}
		ajaxRequest.open("GET","ajaxproc.php?Pg=toggleswitch&flag="+flag, true);
		ajaxRequest.send(null);
	}

	function copyrates(id)
	{
		var fdt = parseInt(document.getElementById("fdt"+id).value);
		var wel = parseInt(document.getElementById("weekend"+id).value);
		if(!isNaN(fdt))
		{
			document.getElementById("mon"+id).value = parseInt(Math.round(fdt*100)/100);
			document.getElementById("tue"+id).value = parseInt(Math.round(fdt*100)/100);
			document.getElementById("wed"+id).value = parseInt(Math.round(fdt*100)/100);
			document.getElementById("thu"+id).value = parseInt(Math.round(fdt*100)/100);
			document.getElementById("fri"+id).value = parseInt(Math.round(fdt*100)/100);
			document.getElementById("sat"+id).value = parseInt(Math.round(fdt*(100+wel))/100);
			document.getElementById("sun"+id).value = parseInt(Math.round(fdt*(100+wel))/100);
		}
		else
			document.getElementById("fdt"+id).value = fdt.substring(0,(fdt.length-1));
	}

	function validatehotelrates(id)
	{
		if(isNaN(document.getElementById(id).value))
		{
			document.getElementById(id).value = document.getElementById(id).value.substring(0,(document.getElementById(id).value.length-1));
			validatehotelrates(id);
		}
	}

	function validatenumbers(id)
	{
		if(isNaN(document.getElementById(id).value))
		{
			document.getElementById(id).value = document.getElementById(id).value.substring(0,(document.getElementById(id).value.length-1));
			validatehotelrates(id);
		}
	}

	function wdcalc(hr,fdt)
	{
		if(document.getElementById("whrd"+hr).value!="")
			var wdrd = parseInt(document.getElementById("whrd"+hr).value);
		else
			var wdrd = 0;

		if(document.getElementById("whrdnl"+hr).value!="")
			var wdrdnl = parseInt(document.getElementById("whrdnl"+hr).value);
		else
			var wdrdnl = 0;

		drate = parseInt((wdrd*0.01)*fdt);
		nrate = parseInt(((wdrd+wdrdnl)*0.01)*fdt);

		document.getElementById("monday"+hr).value = drate;
		document.getElementById("tueday"+hr).value = drate;
		document.getElementById("wedday"+hr).value = drate;
		document.getElementById("thuday"+hr).value = drate;
		document.getElementById("friday"+hr).value = drate;

		document.getElementById("monnight"+hr).value = nrate;
		document.getElementById("tuenight"+hr).value = nrate;
		document.getElementById("wednight"+hr).value = nrate;
		document.getElementById("thunight"+hr).value = nrate;
		document.getElementById("frinight"+hr).value = nrate;
	}

	function wecalc(hr,fdt)
	{
		if(document.getElementById("wehrd"+hr).value!="")
			var wehrd = parseInt(document.getElementById("wehrd"+hr).value);
		else
			var wehrd = 0;

		if(document.getElementById("wehrdnl"+hr).value!="")
			var wehrdnl = parseInt(document.getElementById("wehrdnl"+hr).value);
		else
			var wehrdnl = 0;

		drate = parseInt((wehrd*0.01)*fdt);
		nrate = parseInt(((wehrd+wehrdnl)*0.01)*fdt);

		document.getElementById("satday"+hr).value = drate;
		document.getElementById("sunday"+hr).value = drate;

		document.getElementById("satnight"+hr).value = nrate;
		document.getElementById("sunnight"+hr).value = nrate;
	}

	function applyfdt()
	{
		for(i=2; i<=10; i++)
		{
			var fdt		= parseInt(document.getElementById('fdt').value);
			var whrd	= parseInt(document.getElementById('whrd'+i).value);
			var whrdnl	= parseInt(document.getElementById('whrdnl'+i).value);
			var wehrd	= parseInt(document.getElementById('wehrd'+i).value);
			var wehrdnl	= parseInt(document.getElementById('wehrdnl'+i).value);

			if(document.getElementById('fdt').value!="")
			{
				document.getElementById('monday'+i).value = parseInt((whrd/100)*fdt);
				document.getElementById('tueday'+i).value = parseInt((whrd/100)*fdt);
				document.getElementById('wedday'+i).value = parseInt((whrd/100)*fdt);
				document.getElementById('thuday'+i).value = parseInt((whrd/100)*fdt);
				document.getElementById('friday'+i).value = parseInt((whrd/100)*fdt);

				document.getElementById('monnight'+i).value = parseInt(((whrd+whrdnl)/100)*fdt);
				document.getElementById('tuenight'+i).value = parseInt(((whrd+whrdnl)/100)*fdt);
				document.getElementById('wednight'+i).value = parseInt(((whrd+whrdnl)/100)*fdt);
				document.getElementById('thunight'+i).value = parseInt(((whrd+whrdnl)/100)*fdt);
				document.getElementById('frinight'+i).value = parseInt(((whrd+whrdnl)/100)*fdt);

				document.getElementById('satday'+i).value = parseInt((wehrd/100)*fdt);
				document.getElementById('sunday'+i).value = parseInt((wehrd/100)*fdt);
				
				document.getElementById('satnight'+i).value = parseInt(((wehrd+wehrdnl)/100)*fdt);
				document.getElementById('sunnight'+i).value = parseInt(((wehrd+wehrdnl)/100)*fdt);
			}
		}
	}

	function showtargetlist(x)
	{
		if(x == "general")
		{
			document.getElementById("cities").style.display = "none";
			document.getElementById("users").style.display = "none";
			document.getElementById("hotels").style.display = "none";
		}
		else if(x == "hotel")
		{
			document.getElementById("cities").style.display = "none";
			document.getElementById("users").style.display = "none";
			document.getElementById("hotels").style.display = "block";
		}
		else if(x == "user")
		{
			document.getElementById("cities").style.display = "none";
			document.getElementById("users").style.display = "block";
			document.getElementById("hotels").style.display = "none";
		}
		else if(x == "city")
		{
			document.getElementById("cities").style.display = "block";
			document.getElementById("users").style.display = "none";
			document.getElementById("hotels").style.display = "none";
		}
	}
	function hideall()
	{
		document.getElementById("cities").style.display = "none";
		document.getElementById("users").style.display = "none";
		document.getElementById("hotels").style.display = "none";
	}

	function makerequired1(x,y)
	{
		if(x == 0)
		{
			document.getElementById('stblock').style.display='none';
			document.getElementById('stnumber').required = 0;
			document.getElementById('st').required = 0;
			document.getElementById('sbc').required = 0;
			document.getElementById('kkc').required = 0;
			document.getElementById('stcopy').required = 0;
			document.getElementById('threshold').required = 0;
		}
		else
		{
			document.getElementById('stblock').style.display = 'block';
			document.getElementById('stnumber').required = 1;
			document.getElementById('st').required = 1;
			document.getElementById('sbc').required = 1;
			document.getElementById('kkc').required = 1;
			if(y=="")
				document.getElementById('stcopy').required = 1;
			document.getElementById('threshold').required = 1;
		}
	}

	function makerequired2(x)
	{
		if(x == 0)
		{
			document.getElementById('abetmentblock').style.display='none';
			document.getElementById('ast').required = 0;
			document.getElementById('asbc').required = 0;
			document.getElementById('akkc').required = 0;
		}
		else
		{
			document.getElementById('abetmentblock').style.display = 'block';
			document.getElementById('ast').required = 1;
			document.getElementById('asbc').required = 1;
			document.getElementById('akkc').required = 1;
		}
	}

	function makerequired3(x)
	{
		if(x == 0)
		{
			document.getElementById('scblock').style.display='none';
			document.getElementById('servicecharge').required = 0;
		}
		else
		{
			document.getElementById('scblock').style.display = 'block';
			document.getElementById('servicecharge').required = 1;
		}
	}

	function makerequired4(x,y)
	{
		if(x == 0)
		{
			document.getElementById('ltblock').style.display='none';
			document.getElementById('ltnumber').required = 0;
			document.getElementById('state').required = 0;
			document.getElementById('rrf').required = 0;
			document.getElementById('rtf').required = 0;
			document.getElementById('pv').required = 0;
		}
		else
		{
			document.getElementById('ltblock').style.display = 'block';
			document.getElementById('ltnumber').required = 1;
			document.getElementById('state').required = 1;
			document.getElementById('rrf').required = 1;
			document.getElementById('rtf').required = 1;
			document.getElementById('pv').required = 1;
			if(y=="")
				document.getElementById('ltcopy').required = 1;
		}
	}

	function codegeneration(x)
	{
		if(x == "dynamic")
		{
			document.getElementById("dynamic").style.display="block";
			document.getElementById("manual").style.display="none";
		}
		else
		{
			document.getElementById("dynamic").style.display="none";
			document.getElementById("manual").style.display="block";
		}
	}

	function labelpreference(x)
	{
		if(x == "Yes")
			document.getElementById("labelpref").style.display="block";
		else
			document.getElementById("labelpref").style.display="none";
	}
	
	function destroynext(id)
	{
		x = parseInt(id)+1;
		while(document.getElementById('slot'+x))
		{
			document.getElementById('slotblock').removeChild(document.getElementById('slot'+x));
			x++;
		}
	}

	function validate_facilities()
	{
		if(document.getElementById("diningico").checked || document.getElementById("wifiico").checked || document.getElementById("traveldeskico").checked || document.getElementById("gymico").checked || document.getElementById("parkingico").checked || document.getElementById("tvico").checked)
			document.myform.submit();
		else
			alert("You must select at least 1 facility");
	}

	function auto_select(id)
	{
		if(id=="Gym")
		{
			if(document.getElementById("Gym").checked)
				document.getElementById("gymico").checked = true;
			else
				document.getElementById("gymico").checked = false;
		}
		else if(id=="Free_Wi-Fi_in_lobby")
		{
			if(document.getElementById("Free_Wi-Fi_in_lobby").checked)
				document.getElementById("wifiico").checked = true;
			else
				document.getElementById("wifiico").checked = false;
		}
		else if(id=="Restaurant")
		{
			if(document.getElementById("Restaurant").checked)
				document.getElementById("diningico").checked = true;
			else
				document.getElementById("diningico").checked = false;
		}
		else if(id=="Car_Parking")
		{
			if(document.getElementById("Car_Parking").checked)
				document.getElementById("parkingico").checked = true;
			else
				document.getElementById("parkingico").checked = false;
		}
		else if(id=="Travel_Desk_to_book_rental_cars")
		{
			if(document.getElementById("Travel_Desk_to_book_rental_cars").checked)
				document.getElementById("traveldeskico").checked = true;
			else
				document.getElementById("traveldeskico").checked = false;
		}
		else if(id=="gymico")
		{
			if(document.getElementById("gymico").checked)
				document.getElementById("Gym").checked = true;
			else
				document.getElementById("Gym").checked = false;
		}
		else if(id=="wifiico")
		{
			if(document.getElementById("wifiico").checked)
				document.getElementById("Free_Wi-Fi_in_lobby").checked = true;
			else
				document.getElementById("Free_Wi-Fi_in_lobby").checked = false;
		}
		else if(id=="diningico")
		{
			if(document.getElementById("diningico").checked)
				document.getElementById("Restaurant").checked = true;
			else
				document.getElementById("Restaurant").checked = false;
		}
		else if(id=="parkingico")
		{
			if(document.getElementById("parkingico").checked)
				document.getElementById("Car_Parking").checked = true;
			else
				document.getElementById("Car_Parking").checked = false;
		}
		else if(id=="traveldeskico")
		{
			if(document.getElementById("traveldeskico").checked)
				document.getElementById("Travel_Desk_to_book_rental_cars").checked = true;
			else
				document.getElementById("Travel_Desk_to_book_rental_cars").checked = false;
		}
	}

	function update_roomtype_name(id,roomtypename)
	{
		document.getElementById(id).innerHTML = "<input type='text' id='r"+id+"' class='form-control' value='"+roomtypename+"' onKeydown=\"if(event.keyCode == 13)save_roomtype_name('"+id+"',this.value)\">";
	}

	function save_roomtype_name(id,roomtypename)
	{
		var ajaxRequest;
		
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		document.getElementById("r"+id).readOnly = true;
		document.getElementById("r"+id).style.color = "red";
		document.getElementById("r"+id).value = "Please wait...";
		$.ajax({
			type : "POST",
			url  : "ajaxproc.php?Pg=save_roomtype_name",
			data:{
					roomtype_id	  : '"'+id+'"',
					roomtype_name : '"'+roomtypename+'"'
			},
		}).done(function(data){
				$("#"+id).html(data);
		   })
		
		ajaxRequest.send(null);
	}

	function update_fullday_tariff(id,fulldaytariff)
	{
		document.getElementById("fdt"+id).innerHTML = "<input type='text' id='t"+id+"' class='form-control' value='"+fulldaytariff+"' onKeydown=\"if(event.keyCode == 13)save_fullday_tariff('"+id+"',this.value)\">";
	}

	function save_fullday_tariff(id,fulldaytariff)
	{
		var ajaxRequest;
		
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		document.getElementById("t"+id).readOnly = true;
		document.getElementById("t"+id).style.color = "red";
		document.getElementById("t"+id).value = "Please wait...";
		$.ajax({
			type : "POST",
			url  : "ajaxproc.php?Pg=save_fulldaytariff",
			data:{
					roomtype_id	  : '"'+id+'"',
					fullday_tariff: '"'+fulldaytariff+'"'
			},
		}).done(function(data){
				$("#fdt"+id).html(data);
		   })
		
		ajaxRequest.send(null);
	}

	function save_rate_ondate(id,tariff,roomtype)
	{
		var ajaxRequest;
		
		try
		{
			ajaxRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			try
			{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				try
				{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				}
				catch (e)
				{
					alert("Your browser broke!");
					return false;
				}
			}
		}
		
		//document.getElementById("t"+id).readOnly = true;
		//document.getElementById("t"+id).style.color = "red";
		//document.getElementById("t"+id).value = "Please wait...";
		
		$.ajax({
			type : "POST",
			url  : "ajaxproc.php?Pg=save_rate_ondate",
			data:{
					rdate		  : '"'+id+'"',
					fullday_tariff: '"'+tariff+'"',
					roomtype_id	  : '"'+roomtype+'"'
			},
		}).done(function(data){
				$("#"+id).html(data);
		   })
		
		ajaxRequest.send(null);
	}

////////////////////////////////  //GENERAL   /////////////////////////////////////