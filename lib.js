/*
 * Copyright (C) 2013 Larroque Stephen
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the Affero GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

/**
 * \description     Library of the functions for client's interactions
 */

// Return the value of the html select with id="selectId"
function getSelectValue(selectId)
{
	/**Fetch the html <select> from id=selectID*/
	var selectElmt = document.getElementById(selectId);
	/**
	selectElmt.options corresponds to the list of tags <option> of the <select>
	selectElmt.selectedIndex corresponds to the index of the list of options that is currently selected
	*/
	return selectElmt.options[selectElmt.selectedIndex].value;
	//return selectElmt.options[selectElmt.selectedIndex].text;

	// or :
	// var selectValue = document.getElementById('identifiantDeMonSelect').options[document.getElementById('identifiantDeMonSelect').selectedIndex].value;
}

// Set selected the option with the specified index in a HTML select
function setSelectIndex(selectId, newindex)
{
	/**Fetch the html <select> from id=selectID*/
	var selectElmt = document.getElementById(selectId);
	/**
	selectElmt.options corresponds to the list of tags <option> of the <select>
	selectElmt.selectedIndex corresponds to the index of the list of options that is currently selected
	*/
	selectElmt.selectedIndex = newindex;
	//return selectElmt.options[selectElmt.selectedIndex].text;

	// or :
	// var selectValue = document.getElementById('identifiantDeMonSelect').options[document.getElementById('identifiantDeMonSelect').selectedIndex].value;
}

// Fetch GET parameters from URL
function param_url() {
    // Remove the ?
    param = window.location.search.slice(1,window.location.search.length);

    // Separating the parameters...
    // first[0] is in the following format: param=valeur

    params = param.split("&");

	/* example of use
	var nom=new Array();
    var valeur=new Array();

	for(i=0;i<params.length;i++){
        oneparam = params[i].split("=");
        name[i] = oneparam[0];
        value[i] = oneparam[1];
    }
	*/

	return params;
}

// Refresh the calendar with the new settings (game server, timezone, start_date, etc)
function refresh_calendar(){
	var newloc = "?";

	// Get server name
	var servername = getSelectValue("server_name");
	if (servername != ""){
	  newloc += newloc + "&server_name="+servername;
	}

	// Get currently selected start_date
	var params;
	var param;
	params = param_url();

	// Refresh the page with these parameters (passed to the php script as GET params)
	location.href = newloc;
}
