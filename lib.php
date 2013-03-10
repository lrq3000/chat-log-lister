<?php
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
 * \description     Library of auxiliary functions
 */

// Function to print the options of a <select> form element, and reselect automatically the right value if specified in $currentvalue
// $array : contains all the possible options (key being the index/html value of the options and value the text shown)
// $currentvalue : currently selected value (will automatically select the option that contains this key)
// $stringcompare : compare strings instead of number?
function print_options($array, $currentvalue = null, $stringcompare = false) {
	if (count($array) <= 0) {
		echo '<option value=""></option>';
	} else {
		foreach ($array as $value=>$text) {
			// Reselect the same option if form is uncomplete
			$selected = "";
			if (isset($currentvalue)) {
				if ($stringcompare) {
					if (strcmp($value,$currentvalue) == 0) $selected='selected="true"';
				} else {
					if ($value == $currentvalue) $selected='selected="true"';
				}
			}
			// Output the option
			echo '<option value="'.$value.'" '.$selected.'>'.$text.'</option>';
		}
	}
}

function dbconnect($mysql_host, $mysql_username, $mysql_password, $mysql_database) {
    // Connect to MySQL server
    $conn = mysql_pconnect($mysql_host, $mysql_username, $mysql_password) or die('Error connecting to MySQL server: ' . mysql_error());
    if (is_resource($conn)) {
        // Select database
        mysql_select_db($mysql_database) or die('Error selecting MySQL database: ' . mysql_error());

        return $conn;
    } else {
        return false;
    }
}

/**
*      Fetch any record in the database from any table and automatically manage errors and return a nice object
*      @param        conn               database connection identifier
*      @param	columns		one or several columns (separated by commas) to fetch
*      @param	table		table where to fetch from
*      @param	where		where clause (format: column='value'). Can put several where clause separated by AND or OR
*      @param	orderby	order by clause
*      @param        printsql           debug variable to print the sql that is issued to the database (helps you to pinpoint the bugs)
*      @return     int or array of objects         	<0 if KO, array of objects if one or several records found (for more consistency, even if only one record is found, an array is returned)
*/
function fetchAny($conn, $columns, $table, $where='', $orderby='', $limitby='', $printsql=false)
{

       $sql = "SELECT ".$columns." FROM ".$table;
       if (!empty($where)) {
               $sql.= " WHERE ".$where;
       }
       if (!empty($orderby)) {
               $sql.= " ORDER BY ".$orderby;
       }
       if (!empty($limitby)) {
               $sql.= " LIMIT ".$limitby;
       }

       if ($printsql) print($sql);

       // Executing the SQL statement
       $resql = mysql_query($sql, $conn);

       // Filling the record object
       if ($resql === FALSE) { // if there's no error
               return $resql; // we return the error code
       } else { // else we fill the record
               $num = mysql_num_rows($resql); // number of results returned (number of records)
               // Several records returned = array() of objects
               if ($num >= 1) {
                       $record = array();
                       for ($i=0;$i < $num;$i++) {
                               $record[] = mysql_fetch_object($resql);
                       }
               // Only one record returned = one object
               /*
               } elseif ($num == 1) {
                       $record = $this->fetch_object($resql);
               */
               // No record returned = null
               } else {
                       $record = null;
               }
               mysql_free_result($resql);

               // Return the record(s) or null
               return $record;
       }

}

// Return a 1D array from a multidimensional array/object
function flatten_array($array) {
    $arr = array();
    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
    foreach($it as $v) {
        $arr[] = $v;
    }
    return $arr;
}

// Print a time interval in a human-readable format
// Thank's to salladin from php.net manual comments for the base function, this one is an enhanced version
function printTime($secs, $nosingleterm=false){
    // Negative values = past in time
    $positive = true;
    if ($secs < 0) {
        $positive = false;
        $secs = -$secs; // we just compute the time with the opposite (a positive value)
    }

    // Breaking seconds into parts (years, weeks, days, etc..)
    $bit = array(
        'year'        => $secs / 31556926 % 12,
        'week'        => $secs / 604800 % 52,
        'day'        => $secs / 86400 % 7,
        'hour'        => $secs / 3600 % 24,
        'minute'    => $secs / 60 % 60,
        'second'    => $secs % 60
        );

    // Fix linguistic issues
    foreach($bit as $k => $v){
        if($v > 1)$ret[] = $v . ' ' . $k . 's'; // if multiple items, append a 's'
        // If only a single item, we don't add a 's', but we can remove the '1'
        if($v == 1) {
            // We can choose to remove the ' 1 ' with this optional parameter (so that you can print something like 'this resets every day' instead of 'this resets every 1 day')
            $nosingleterm ? $ret[] = $k : $ret[] = $v . ' ' . $k;
        }
    }

    // Beautify a bit more: add comma between n-1 terms, and the last gets separated by ' and '
    if (count($ret) > 1) {
        $ret1 = array();
        $ret1[] = implode(', ', array_slice($ret, 0, count($ret)-1));
        $ret1[] = $ret[count($ret)-1];
        $ret = implode(' and ', $ret1);
    // If there's only one element in the list, we just convert it to a string
    } else {
        $ret = $ret[0];
    }

    // Negative value = past in time, so we add ' ago' at the end
    if (!$positive) $ret .= ' ago';

    // Return the final result, a human-readable time
    return $ret;
}

?>