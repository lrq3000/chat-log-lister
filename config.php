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
 * \description     Main configuration file for the whole application
 */

// Debug options: uncomment the following lines if you want to get more verbose outputs on your test server (AVOID on production server)
// ini_set('display_errors', 1);
// ini_set('error_reporting', E_ALL);

// Init the $conf array (will be set global and included in all other scripts)
$conf = array();

// Debug mode: add more verbose output, only enable this on test servers because this can show some critical informations to hackers
$conf['debug'] = false;

// Database connection informations
$conf['mysql_host'] = 'localhost:3306';
$conf['mysql_user'] = 'root';
$conf['mysql_password'] = '';
$conf['mysql_database'] = 'b3';
$conf['mysql_table'] = 'chatlog_all';

// Blacklist for messages contents (you can use wildcard %)
$conf['blacklist_msg'] = array(  '!%',
                                                            'follownext',
                                                            );

// Whitelist for messages types (either ALL, TEAM or PM)
$conf['whitelist_msg_type'] = array('ALL');

// Delay before showing the log of that day (which means that people won't see the log instantaneously, but rather see the log beginning from x seconds before - this is to avoid stalkers and bullies)
$conf['delay'] = 86400;

// Page limits
$conf['limitbypage'] = 30; // number of chat lines to show per page
$conf['maxpages'] = 500; // maximum number of pages (this is to avoid an overflow on your database if someone tries to open the page 99999999999999999999 for example)

?>