#!/usr/bin/perl
#
#       Required DBI::MySql
#                IP::Country::Fast
#
#       Install the script in ~/.opendchub/scripts
#-----------------------------------------------------------------
#
# PERL MODULES USED
# DBI::MySql            (Included with all distros)
# IP::Country::Fast     http://nutter.kicks-ass.net:35600/software/odchscripts/sqlbot/Required Modules/IP-Country-2.11/
#                       http://nutter.kicks-ass.net:35600/software/odchscripts/sqlbot/Required Modules/Geography-Countries-1.4/
# Date:Simple		http://nutter.kicks-ass.net:35600/software/odchscripts/sqlbot/Required Modules/
#-------------------------------------------------------------------------------------
# Recommended
#       Local WWW server
#       PHP
#       php-mysql
#
## SCRIPT CONFIG ##

$botname = "sqlbot";            # Botname

$sql_server = "localhost";                  # Server to connect to
$sql_username = "sqlusername";              # Username required to access sql database
$sql_password = "sqlpassword";              # User password required to access sql database
$modules_path = "/path/to/sqlbot/modules";  # Path to the sqlbot modules

## END SCRIPT CONFIG ##

## OK, Let's rip ##
require "$modules_path/main.pm";
