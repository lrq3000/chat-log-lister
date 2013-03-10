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
 * \description     List the chatlog using a big brother bot's (b3) database (using chatlogger plugin)
 */

// Include the required files
require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

// Connect to the database
$conn = dbconnect($conf['mysql_host'], $conf['mysql_user'], $conf['mysql_password'], $conf['mysql_database']);

// Set the page number
$page = 1;
if (isset($_GET['page']) && $_GET['page'] >= 1 && $_GET['page'] <= $conf['maxpages']) $page = $_GET['page'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <title>Chat Log Lister</title>
        <script type="text/javascript" src="lib.js"></script>
        <style type="text/css">@import url(style/chatloglister.css);</style>
    </head>
    <body>

        <h1>Chat Log Lister</h1>
        <?php if ($conf['delay'] > 0) echo '<p>To limit abuses and stalking, logs are shown with a delay, thus you see the log starting from '.printTime(-$conf['delay']).'.</p>'; // message to notify users that there's a delay if one is set ?>
        Chat logs for server:
	<select name="server_name" id="server_name" onchange="refresh_calendar();">
            <option value=""></option>
            <?php
            // List the servers for which logs are available
            $servers = fetchAny($conn, 'DISTINCT server_name', $conf['mysql_table'], null, 'server_name ASC'); // Fetch the list of unique server_name from the db
            $servers = flatten_array($servers); // flatten the multidimensional array/object into a 1D array
            print_options($servers, $_GET['server_name']); // print the array as html options
            ?>
	</select><br />
        <?php
        // Print the messages if at least a server_name is selected
        if ( isset($_GET['server_name']) && isset($servers[$_GET['server_name']]) ) {
            $server_name = $servers[$_GET['server_name']];
        ?>
        <h2>Page <?php echo $page; ?></h2>
        <table cellspacing="3" cellpadding="2" id="chatloglister_table">
            <tr>
                <th>Date and time</th>
                <?php if (count($conf['whitelist_msg_type']) > 1) echo '<th>Type</th>'; ?>
                <th>Name</th>
                <th>Message</th>
            </tr>
            <?php
            // Init variables
            $whitelist_msg_type = array();
            foreach($conf['whitelist_msg_type'] as $v) {
                $whitelist_msg_type[] = "msg_type='".$v."'";
            }

            $blacklist_msg = array();
            foreach($conf['blacklist_msg'] as $v) {
                $blacklist_msg[] = "msg NOT LIKE '".$v."'";
            }

            $limitstart = ($page-1)*$conf['limitbypage'];
            $limitend = ($page-1)*$conf['limitbypage'] + $conf['limitbypage'];

            // Fetch all messages
            $messages = fetchAny($conn, 'DISTINCT msg_time, msg_type, client_name, msg', $conf['mysql_table'], "server_name='".$server_name."' and msg_time <= '".(time()-$conf['delay'])."' and (".implode(' or ', $whitelist_msg_type).") and ".implode(' and ', $blacklist_msg), 'msg_time DESC', $limitstart.','.$limitend, $conf['debug']);
            if (!empty($messages)) {
                $count = 1;
                foreach($messages as $msg) {
                    print "<tr class=\"".((++$count % 2 == 0) ? 'alt' : '')."\">";
                    print '<td>'.date('Y-m-d h:i:s',$msg->msg_time).'</td>';
                    if (count($conf['whitelist_msg_type']) > 1) print '<td>'.$msg->msg_type.'</td>';
                    print '<td>'.$msg->client_name.'</td>';
                    print '<td>'.$msg->msg.'</td>';
                    print '</tr>';
                }
            }
            ?>
        </table>

        <?php
            // Previous/Next page links
            if ($page >= 2) {
            ?>
                <a href="?server_name=<?php echo $_GET['server_name'] ?>&page=<?php echo $page-1 ?>">Previous page</a>
            <?php
            }
        ?>
                <a href="?server_name=<?php echo $_GET['server_name'] ?>&page=<?php if ($page >= 2) echo $page+1; else echo '2'; ?>">Next page</a>

        <?php
        }
        ?>

        <br /><br />
        <a href="http://www.gnu.org/licenses/agpl.html" target="_blank"><img src="agplv3.png" alt="This application is licensed under Affero GPL v3+" title="This application is licensed under Affero GPL v3+" /></a>

    </body>
</html>