#!/usr/bin/perl
			## INSTALLER SCRIPT for sql bot ##
use DBI;
print "\n";
print "This is NOT a complete installer for sqlbot it is provided to assist with upgrading from release to release or to latest CVS only\n";
print "\n";
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
	print "1 - Upgrade from 0.2.0 to CVS\n";
	print "2 - Upgrade from 0.2.1 to CVS\n";
	print "3 - Exit\n";
	
	my($option) = &promptUser("option :","1");
	if ($option eq "1")
		{&installTables();}
	elsif ($option eq "2")
		{&installTables021();}
	elsif ($option eq "3")
		{&exit();}
	else
		{print "Invalid entry\n";
		&main();}
		

}
sub installTables021 {
	print "Trying to log into the MySQL database \"$sqldbname\" on server \"$sql_server\" using the
login name and password you gave in this script.\n\n";
	$dbh = DBI->connect("DBI:mysql:$sqldbname:$sql_server","$sql_username","$sql_password",{ RaiseError => 1, AutoCommit => 0 });
	$dbh->do("SET OPTION SQL_BIG_TABLES = 1");

eval { $dbh->do("ALTER TABLE client_rules ADD min_limit TINYINT(4)");
			print "ALTER TABLE client_rules ADD min_limit TINYINT(4)\n";
		}; print "ALTER TABLE client_rules ADD min_limit TINYINT(4) : failed $@\n" if $@;


# Disconnect from the database.
         $dbh->disconnect();
}


sub installTables {
	print "Trying to log into the MySQL database \"$sqldbname\" on server \"$sql_server\" using the
login name and password you gave in this script.\n\n";
	$dbh = DBI->connect("DBI:mysql:$sqldbname:$sql_server","$sql_username","$sql_password",{ RaiseError => 1, AutoCommit => 0 });
	$dbh->do("SET OPTION SQL_BIG_TABLES = 1");

eval { $dbh->do("ALTER TABLE userDB MODIFY nick VARCHAR(50)");
					print "ALTER TABLE userDB MODFIY (nick varchar(50)\n";
		}; print "ALTER TABLE userDB MODFIY nick varchar(50)  : failed $@\n" if $@;
		
eval { $dbh->do("ALTER TABLE userDB MODIFY onlineTime BIGINT(20)");
					print "ALTER TABLE userDB MODIFY onlineTime BIGINT(20)\n";
		}; print "ALTER TABLE userDB MODIFY onlineTime BIGINT(20)  : failed $@\n" if $@;

eval { $dbh->do("INSERT INTO hub_variables VALUES (	'',
							'hub_country',
							'',
							'Country location of the Hub')");
			print "INSERT INTO hub_variables hub_country \n";
		}; print "INSERT INTO hub_variables VALUES hub_country : failed $@\n" if $@;

eval { $dbh->do("INSERT INTO hub_variables VALUES (	'',
						'logfile_name',
						'',
						'Name for the Chat logFile, Leave blank for no logging')");
			print "INSERT INTO hub_variables logfile_name \n";
		}; print "INSERT INTO hub_variables logfile_name\n" if $@;

						
	
eval { $dbh->do("ALTER TABLE hubLog MODIFY nick VARCHAR(50)");
				print "ALTER TABLE userDB MODFIY (nick varchar(50)\n";
		}; print "ALTER TABLE hubLog MODFIY nick varchar(50) : failed $@\n" if $@;

eval { $dbh->do("CREATE TABLE ipFiltering (rowID 	INT(7) NOT NULL AUTO_INCREMENT PRIMARY KEY,
					ipStart		VARCHAR(15),
					ipEnd		VARCHAR(15),
					ipMask		VARCHAR(15),
					function	VARCHAR(10),
					information	VARCHAR(50),
					log		char(3))");
					print "CREATE TABLE ipFiltering\n";
		}; print "CREATE TABLE ipFiltering failed: $@\n" if $@;


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
print "\n\n\n		## Upgrade ##
You have hopefully now upgraded the required tables for the sqlbot.

1] Ensure that you PHP and perl for sqlbot is now at this latest release level.";
print "Script exiting...\n";
exit;
}


