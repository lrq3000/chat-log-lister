CHAT-LOG-LISTER README v1.0
=======================
Chat-Log-Lister is a simple PHP5+MySQL web application to securely give a public web access to the log files of your game servers using Big-Brother-Bot and the ChatLogger plugin.

DESCRIPTION
-----------

This is a simple PHP5+MySQL web application to pubicly show your game servers' logs.

This application gives you the choice on what you want to give access to: you can blacklist some kinds of messages, and filter out private or team messages to only show public messages. You can quickly configure the behavior of this application by editing the config.php file.

This application does NOT save the log files nor process them, for this you need Big-Brother-Bot and the ChatLogger plugin, which will process your log files and save the chat lines into a MySQL database.

AUTHOR
------

This software was developped by Stephen Larroque.

You can contact the author at <lrq3000 at gmail dot com>

LICENSE
-------

This software is licensed under the Affero GNU General Public License v3 and above (AGPLv3+).

INSTALL
-------

Just copy every files inside the archive (along this README) to any folder you want on your web server. Your server must run PHP > 5 (it may work below but this wasn't tested).

Then, open config.php and edit the MySQL database connection informations, and any other parameters to your liking.

Additionally, you also need Big-Brother-Bot and the Chatlogger plugin, and also a MySQL database running with your game servers.
