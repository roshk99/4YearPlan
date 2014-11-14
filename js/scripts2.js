totalcredits = 0;
upperlevel = 0;
dist1 = 0;
dist2 = 0;
dist3 = 0;
fwis = 0;
lpap = 0;

function update()
{
	var selectedclasses = getselectedvalues();
	populatetables(selectedclasses); 
	updatecounters();
	conditionalformatting();  
	
}

function getselectedvalues()
{
	selected = [];
	for (i=0; i<9; i++)
	{
		selected[i] = $('.selectpicker[id=semester' + i + ']').val();
	} 
	return selected;
}

function populatetables(classes)
{
	//AP classes
	var apclasses = document.getElementById("apclasseslist");
	apclasses.innerHTML = "";
	var my_str = ""; 
	for (counter0 = 0, maxclasses = classes[0].length; counter0<maxclasses; counter0++)
	{
		var classinfo = parseclass(classes[0][counter0]); 
		my_str = my_str + classinfo["classname"] + " - " + classinfo["credits"];
		if (counter0 != classes[0].length - 1)
		{
			my_str = my_str + ", ";
		}
		updatevars(classinfo);
	}
	apclasses.innerHTML = my_str;
 
	for (counter1 = 1; counter1 < 9; counter1++)
	{	
		semester_count = 0; 
		if (classes[counter1] != null)
		{ 
			for (counter2 = 1; counter2 < 8; counter2++)
			{
				var list_item = document.getElementById("semester" + (counter1) + "-" + counter2); 
				if (classes[counter1][counter2-1] != null && classes[counter1][counter2-1] != undefined)
				{
					var classinfo = parseclass(classes[counter1][counter2-1]);
					var my_str = classinfo["classname"] + " - " + classinfo["credits"]; 
					list_item.innerHTML = my_str;
					list_item.style.color = "black"; 
					semester_count = semester_count + classinfo["credits"]; 
					updatevars(classinfo);

					//Check prereqs
					if (classinfo["prereqs"] != undefined)
					{
						if (check_prereqs(classes, classinfo, classes[counter1][counter2-1]) == "Not met")
						{
							list_item.style.color = "blue";
						}
					}
				}
				else
				{
					list_item.innerHTML = "";
				} 
			}
		}
		else
		{
			for (counter3 = 1; counter3 < 8; counter3++)
			{
				var list_item = document.getElementById("semester" + (counter1) + "-" + counter3); 
				list_item.innerHTML = "";
			}	
		}
		
		var count_item = document.getElementById("semester" + (counter1) + "num"); 
		count_item.innerHTML = semester_count; 
	}
}

function parseclass(str)
{
	var classinfo = [];
	classinfo['credits'] = parseInt(str.substring(str.length - 1, str.length));
	var distindexleft = str.indexOf("(");
	if (distindexleft != -1)
	{
		var distindexright = str.indexOf(")");
		classinfo["dist"] = str.substring(distindexleft + 1, distindexright);
	}

	classinfo["classname"] = str.substring(0, 8);
	
	var classspaceindex = classinfo["classname"].indexOf(" ");

	classinfo["classnumber"] = parseInt(classinfo["classname"].substring(4, 8));
	classinfo["classdepart"] = classinfo["classname"].substring(0, 4);

	var prereqleft = str.indexOf("{");
	if (prereqleft != -1)
	{
		var prereqright = str.indexOf("}");
		var prereqstr = str.substring(prereqleft + 1, prereqright);
		classinfo["prereqs"] = prereqstr.split(",");
	}

	return classinfo;
}

function updatevars(classinfo)
{
	totalcredits = totalcredits + classinfo["credits"];

	if (classinfo["classnumber"] > 299)
	{
		upperlevel = upperlevel + classinfo["credits"];
	}

	if (classinfo["dist"] == "I")
	{
		dist1 = dist1 + classinfo["credits"];
	}
	else if (classinfo["dist"] == "II")
	{
		dist2 = dist2 + classinfo["credits"];
	}
	else if (classinfo["dist"] == "III")
	{
		dist3 = dist3 + classinfo["credits"];
	}

	if (classinfo["classdepart"] == "FWIS")
	{
		fwis = fwis + classinfo["credits"];
	}
	else if (classinfo["classdepart"] == "LPAP")
	{
		lpap = lpap + classinfo["credits"];
	}
}

function updatecounters()
{
	document.getElementById("totalcreditsnum").innerHTML = totalcredits;
	document.getElementById("upperlevelnum").innerHTML = upperlevel;
	document.getElementById("dist1num").innerHTML = dist1;
	document.getElementById("dist2num").innerHTML = dist2;
	document.getElementById("dist3num").innerHTML = dist3;
	document.getElementById("fwisnum").innerHTML = fwis;
	document.getElementById("lpapnum").innerHTML = lpap;

	totalcredits = 0;
	upperlevel = 0;
	dist1 = 0;
	dist2 = 0;
	dist3 = 0;
	fwis = 0;
	lpap = 0;

}

function conditionalformatting()
{	
	var my_array = ["totalcredits", "upperlevel", "dist1", "dist2", "dist3", "fwis", "lpap"];	
	for (i=0, n=my_array.length; i<n; i++)
	{
		if (parseInt(document.getElementById(my_array[i] + "num").innerHTML) >= parseInt(document.getElementById(my_array[i] + "den").innerHTML))
		{
			document.getElementById(my_array[i]).style.color = "green";
		}
		else
		{
			document.getElementById(my_array[i]).style.color = "red";
		}
	}
}

function check_prereqs(classes, classinfo, str)
{
	for (k=0, n=classinfo["prereqs"].length; k<n; k++)
	{
		if (get_semester_numer(classes,str) < get_semester_numer(classes, classinfo["prereqs"][k]))
		{
			return "Not met";
		} 
	}

	return "Met";
}

function get_semester_numer(classes, str)
{
	my_class = str.substring(0,8); 
	for (semester=0; semester<9; semester++)
	{	
		if (classes[semester] != null)
		{
			for (classid=0; classid<classes[semester].length; classid++)
			{
				if (my_class == classes[semester][classid].substring(0,8))
				{
					return semester;
				}
			}
		}
	}
	return 9;
}
