#!/usr/bin/perl
			## INSTALLER SCRIPT for sql bot ##
use DBI;

print "In order to install the neccessary SQL tables you should have SQL running, and already have a username and password, that SQLbot will use. If you have not yet done so you should Quit this install and setup SQL first.\n";
$sql_server = &promptUser("Enter the SQL server Hostname ","localhost");

print "You should of already created a username in SQL for the bot to use.\n";
$sql_username = &promptUser("Enter the username the bot will use to access SQL ","sqlbot");

print "You should of already created a password for $sql_username, in SQL for the bot to use.\n";
$sql_password = &promptUser("Enter the password ","sqlbot");

# print "Where have you located your modules ?\n";
# $moduledir  = &promptUser("Enter the module directory  ", "~/.opendchub/scripts/modules");
$sqldbname = "odch";
&main();

sub main (){
	print "1 - Install new tables and fields\n";
	print "2 - Delete all existing tables and fields (If new version requires it)\n";
	print "3 - Exit\n";
	
	my($option) = &promptUser("option :","1");
	if ($option eq "1")
		{&installTables();}
	elsif ($option eq "2")
		{&deleteTables();}
	elsif ($option eq "3")
		{&exit();}
	else
		{print "Invalid entry\n";
		&main();}
		

}

sub deleteTables(){
	$dbh = DBI->connect("DBI:mysql:$sqldbname:$sql_server","$sql_username","$sql_password",{ RaiseError => 1, AutoCommit => 0 });
	$dbh->do("SET OPTION SQL_BIG_TABLES = 1");

eval { $dbh->do("DROP TABLE fakers");
			}; print "DROP \"fakers\" failed: $@\n" if $@;
eval { $dbh->do("DROP TABLE log");
			}; print "DROP \"log\" failed: $@\n" if $@;
eval { $dbh->do("DROP TABLE online");
			}; print "DROP \"online\" failed: $@\n" if $@;
eval { $dbh->do("DROP TABLE user_stats");
			}; print "DROP \"user_stats\" failed: $@\n" if $@;
eval { $dbh->do("DROP TABLE userDB");
			}; print "DROP \"userDB\" failed: $@\n" if $@;
eval { $dbh->do("DROP TABLE hubLog");
			}; print "DROP \"userDB\" failed: $@\n" if $@;
	# Disconnect from the database.
         $dbh->disconnect();
}

sub installTables {
	print "Trying to log into the MySQL database \"$sqldbname\" on server \"$sql_server\" using the
login name and password you gave in this script.\n\n";
	$dbh = DBI->connect("DBI:mysql:$sqldbname:$sql_server","$sql_username","$sql_password",{ RaiseError => 1, AutoCommit => 0 });
	$dbh->do("SET OPTION SQL_BIG_TABLES = 1");

eval { $dbh->do("CREATE TABLE hubLog (	rowID 		INT(7) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					nick		VARCHAR(30),
					logTime		DATETIME,
					action		VARCHAR(20),
					reason		VARCHAR(20)
					)");
					print "Created table \"hubLog\"\n";
			}; print "CREATE \"hubLog\" failed: $@\n" if $@;


eval { $dbh->do("CREATE TABLE userDB (	rowID 		INT(7) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					nick		VARCHAR(50),
					passwd		VARCHAR(15),
					status		VARCHAR(10),
					utype		VARCHAR(10),
					type		INT(5),
					allowStatus	VARCHAR(8),
					awayStatus	VARCHAR(8),
					awayMsg		VARCHAR(40),
					fullDescription	VARCHAR(100),
					dcClient	VARCHAR(10),
					dcVersion	VARCHAR(25),
					slots		INT(5) UNSIGNED,
					hubs		INT(5) UNSIGNED,
					limiter		INT(5) UNSIGNED,
					connection	VARCHAR(8),
					connectionMode	VARCHAR(8),
					country		VARCHAR(3),
					IP		VARCHAR(15),
					hostname	VARCHAR(50),
					firstTime	DATETIME,
					outTime		DATETIME,
					inTime		DATETIME,
					onlineTime	DATETIME,
					loginCount	INT(10) UNSIGNED,
					kickCount	INT(10) UNSIGNED,
					kickCountTot    INT(10) UNSIGNED,
					tBanCount	INT(10) UNSIGNED,
					tBanCountTot	INT(10) UNSIGNED,
					pBanCount	INT(10) UNSIGNED,
					pBanCountTot	INT(10) UNSIGNED,
					lineCount	INT(20) UNSIGNED,
					avShareBytes	BIGINT(20) UNSIGNED,
					shareByte	BIGINT(20) UNSIGNED,
					lastAction	VARCHAR(8),
					lastReason	VARCHAR(12)
					)");
					print "Created table \"userDB\"\n";
			}; print "CREATE \"userDB\" failed: $@\n" if $@;

eval { $dbh->do("CREATE TABLE botWorker (	rowID 		TINYINT(7) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					function	int(3),
					nick		VARCHAR(30),
					IP		VARCHAR(15),
					information	VARCHAR(50)
					)");
					print "Created table \"botWorker\"\n"
			}; print "CREATE \"botWorker\" failed: $@\n" if $@;

eval { $dbh->do("CREATE TABLE client_rules (rowID INT(7) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
					client VARCHAR(10), 
					min_version VARCHAR(10), 
					allowed VARCHAR(3), 
					min_slots tinyint(2) UNSIGNED,
					max_slots tinyint(2) UNSIGNED,
					slot_ratio VARCHAR(4) , 
					max_hubs tinyint(3) UNSIGNED, 
					min_share tinyint(4) UNSIGNED, 
					min_connection tinyint(1) UNSIGNED NOT NULL,
					client_name VARCHAR(10) NOT NULL)");
					print "Created table \"client_rules\"\n"
			}; print "CREATE \"client_rules\" failed: $@\n" if $@;

eval { $dbh->do("CREATE TABLE hub_config (rowID INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					rule VARCHAR(20),
					value VARCHAR(4),
					description VARCHAR(50))");
					print "Created table \"hub_config\"\n";
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'check_opadmin',
						'off',
						'Client Checks on OP ADMINs')");
						print "	data \"check_opadmin\" inserted\n";
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'check_op',
						'off',
						'Client Checks on OPs')");
						print "	data \"check_op\" inserted\n";
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'check_reg',
						'off',
						'Client Checks on Registered Users')");
						print "	data \"check_reg\" inserted\n";
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'check_kicks',
						'on',
						'Enable Ban after 10 kicks per 24Hrs')");
						print "	data \"check_kicks\" inserted\n";		
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'check_mldonkey',
						'on',
						'Kick mlDonkey users')");
						print "	data \"check_mldonkey\" inserted\n";
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'kick_notags',
						'on',
						'Kick all UNTAGGED clients')");
						print "	data \"kick_notags\" inserted\n";
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'post_client_check',
						'on',
						'Re-check clients AFTER initial connect')");
						print "	data \"post_client_check\" inserted\n";
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'clone_check',
						'on',
						'Check for clones.(2 Users one ip)')");
						print "	data \"post_client_check\" inserted\n";
$dbh->do("INSERT INTO hub_config VALUES (	'',
						'client_check',
						'off',
						'Kick Clients who fail Regular Client Checks')");
						print "	data \"client_check\" inserted\n";		
				}; print "CREATE \"hub_config\" failed: $@\n" if $@;
eval { $dbh->do("CREATE TABLE verbosity (rowID INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					rule VARCHAR(20),
					value VARCHAR(20),
					description VARCHAR(40))");
					print "Created table \"verbosity\"\n";
$dbh->do("INSERT INTO verbosity VALUES (	'',
						'verbose_kicks',
						'on',
						'Show kicks in Main Chat')");
						print "	data \"verbose_kicks\" inserted\n";
$dbh->do("INSERT INTO verbosity VALUES (	'',
						'verbose_notagkicks',
						'off',
						'Announce Kick of an UnTagged Client')");
						print "	data \"verbose_notagkicks\" inserted\n";
$dbh->do("INSERT INTO verbosity VALUES (	'',
						'verbose_banned',
						'on',
						'Announe Bans on main Chat')");
						print "	data \"verbose_banned\" inserted\n";
$dbh->do("INSERT INTO verbosity VALUES (	'',
						'verbose_nukes',
						'on',
						'Announe Nukes on main Chat')");
						print "	data \"verbose_nukes\" inserted\n";
$dbh->do("INSERT INTO verbosity VALUES (	'',
						'verbose_op_connect',
						'on',
						'Announce OP & OP Admin connects')");
						print "	data \"verbose_op_connect\" inserted\n";
$dbh->do("INSERT INTO verbosity VALUES (	'',
						'hub_timer',
						'off',
						'Hub timer Hub Advert.........(900 secs)')");
						print "	data \"hub_timer\" inserted\n";
$dbh->do("INSERT INTO verbosity VALUES (	'',
						'verbose_records',
						'on',
						'Main Chat Record notification')");
						print "	data \"verbose_records\" inserted\n";
$dbh->do("INSERT INTO verbosity VALUES (	'',
						'verbose_botjoin',
						'off',
						'Announce the bot on !reloadscripts')");
						print "	data \"verbose_botjoin\" inserted\n";
				}; print "CREATE \"verbosity\" failed: $@\n" if $@;
eval { $dbh->do("CREATE TABLE records (	rowID INT(7) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					recordName VARCHAR(20),
					recordValue VARCHAR(10),
					date DATE,
					time TIME)");
					print "Created table \"records\"\n";

$dbh->do("INSERT INTO records VALUES ('','share','0','','')"); print "	data \"share\" inserted\n";
$dbh->do("INSERT INTO records VALUES ('','users','0','','')"); print "	data \"users\" inserted\n";
	}; print "CREATE \"log_config\" failed: $@\n" if $@;

eval { $dbh->do("CREATE TABLE log_config (rowID INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					rule VARCHAR(20),
					value VARCHAR(20),
					description VARCHAR(50))");
					print "Created table \"log_config\"\n";
$dbh->do("INSERT INTO log_config VALUES (	'',
						'log_kicks',
						'on',
						'Log kicks (Required for 10x bans)')");
						print "	data \"log_kicks\" inserted\n";
$dbh->do("INSERT INTO log_config VALUES (	'',
						'log_no_tags_kicks',
						'on',
						'Log No-Tags kicks')");
						print "	data \"log_no_tags_kicks\" inserted\n";
$dbh->do("INSERT INTO log_config VALUES (	'',
						'log_bans',
						'on',
						'Log Bans')");
						print "	data \"log_bans\" inserted\n";
$dbh->do("INSERT INTO log_config VALUES (	'',
						'log_nukes',
						'on',
						'Log Nukes')");
						print "	data \"log_nukes\" inserted\n";		
$dbh->do("INSERT INTO log_config VALUES (	'',
						'log_connects',
						'on',
						'Log all successful Connects')");
						print "	data \"log_connects\" inserted\n";
$dbh->do("INSERT INTO log_config VALUES (	'',
						'log_disconnects',
						'on',
						'Log all disconnects')");
						print "	data \"log_disconnects\" inserted\n";
$dbh->do("INSERT INTO log_config VALUES (	'',
						'log_logons',
						'on',
						'Log OP(-Admin)s and RegUsers Connects')");
						print "	data \"log_logons\" inserted\n";
$dbh->do("INSERT INTO log_config VALUES (	'',
						'log_logoffs',
						'on',
						'Log OP(-Admin)s and RegUsers disconnects')");
						print "	data \"log_logoffs\" inserted\n";		
				}; print "CREATE \"log_config\" failed: $@\n" if $@;

eval { $dbh->do("CREATE TABLE hub_variables (rowID INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					rule VARCHAR(20),
					value VARCHAR(40),
					description VARCHAR(50))");
					print "Created table \"hub_variables\"\n";
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'hub_website_address',
						'',
						'URL of Hub')");
						print "	data \"hub_web_address\" inserted\n";
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'external_ip',
						'',
						'External IP of user(if on a LAN')");
						print "	data \"external_ip\" inserted\n";
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'debug_user',
						'',
						'Name of User where debug trace should be sent')");
						print "	data \"debug_user\" inserted\n";
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'use_debug',
						'0',
						'Output debug (0 = off, 1 = on)')");
						print "	data \"use_debug\" inserted\n";
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'nr_log_entries',
						'20',
						'Nr. of lines to be returned for !log events')");
						print "	data \"nr_log_entries\" inserted\n";		
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'delayed_kick_time',
						'10',
						'Delay kicks by X seconds')");
						print "	data \"delayed_kick_time\" inserted\n";
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'kick_before_tban',
						'10',
						'Number of kicks before a kick becomes a Temp Ban')");
						print "	data \"kick_before_ban\" inserted\n";
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'temp_ban_time',
						'1',
						'Minutes to temp-ban people')");
						print "	data \"temp_ban_time\" inserted\n";
$dbh->do("INSERT INTO hub_variables VALUES (	'',
						'tban_before_pban',
						'10',
						'Nr. of Temp bans before Perm ban (in 24 hours)')");
						print "	data \"tban_before_pban\" inserted\n";
				}; print "CREATE \"hub_variables\" failed: $@\n" if $@;

eval { $dbh->do("CREATE TABLE connection_slots (rowID INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					connection INT(3),
					min_slots INT(4),
					max_slots INT(4),
					description VARCHAR(15))");
					print "Created table \"connection_slots\"\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'1',
						'1',
						'1',
						'28.8Kbps')");
						print "	data \"connection 28.8Kbps\" inserted\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'2',
						'1',
						'1',
						'33.6Kbps')");
						print "	data \"connection 33.6Kbps\" inserted\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'3',
						'1',
						'2',
						'56Kbps')");
						print "	data \"connection 56Kbps\" inserted\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'4',
						'2',
						'4',
						'Satellite')");
						print "	data \"connection Satellite\" inserted\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'5',
						'2',
						'6',
						'ISDN')");
						print "	data \"connection ISDN\" inserted\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'6',
						'3',
						'10',
						'DSL')");
						print "	data \"connection DSL\" inserted\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'7',
						'3',
						'10',
						'Cable')");
						print "	data \"connection Cable\" inserted\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'8',
						'5',
						'20',
						'LAN(T1)')");
						print "	data \"connection LAN(T1)\" inserted\n";
$dbh->do("INSERT INTO connection_slots VALUES (	'',
						'9',
						'5',
						'25',
						'LAN(T3)')");
						print "	data \"connection LAN(T3)\" inserted\n";
		}; print "CREATE \"connection_slots\" failed: $@\n" if $@;

eval { $dbh->do("CREATE TABLE hub_rules (rowID INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					rule VARCHAR(100))");
					print "Created table \"hub_rules\"\n";
		}; print "CREATE \"hub_rules\" failed: $@\n" if $@;


# Disconnect from the database.
         $dbh->disconnect();
}

sub promptUser {
	local($promptString,$defaultValue) = @_;
	if ($defaultValue) 
		{print $promptString, "[", $defaultValue, "]: ";}
	else 
		{print $promptString, ": ";}
	$| = 1;	$_ = <STDIN>;chomp;
	if ("$defaultValue") 
		{return $_ ? $_ : $defaultValue; }   # return $_ if it has a value
	else 
		{return $_;}
}

sub exit()
{
print "\n\n\n		## INSTALATION NOTES ##
You have hopefully now installed the required tables for the sqlbot.

If you got warnings above during the install, it is probably because you
still have old configs in there. This script will not add to current
tables! It will only insert new ones (if any). To \"reinstall\" you
first need to delete the old ones.

1] Configure your bot (sqlbot.pl) to use the login host=$sql_server username=$sql_username password=$sql_password.
2] Change the module path in (sqlbot.pl) to $moduledir
2] Configure the dbinfo.inc.php for your admin control to use host=$sql_server username=$sql_username password=$sql_password.
3] Log into your webserver into the directory where you have the configure php files, and configure your bot.
4] !reloadscripts in your opendchub server.\n\n";
print "Script exiting...\n";
exit;
}


