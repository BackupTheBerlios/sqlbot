#############################################################################################
# 	module name 	main.pm
#
#	Author		Nutter, Axllent
#
#	Summary		The bot startup entry point
#
#	Description	This module should be only used to connect to hub, and to 
#			import other modules
#
#
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org/			
#
##############################################################################################

$botVersion = "0.2.2";

use DBI;
use IP::Country::Fast;
use Date::Simple ('date', 'today');

$dbh = DBI->connect("DBI:mysql:odch:$sql_server","$sql_username","$sql_password",{ RaiseError => 1, AutoCommit => 0 });
$dbh->do("SET OPTION SQL_BIG_TABLES = 1");

$botDescription = "sqlBOT (http://sqlbot.berlios.de)";
$botConnection = "botConnection";
$botShare = 0; #715112000000; #666
# Import the other modules,

require "$modules_path/common.pm";
require "$modules_path/kickban.pm";
require "$modules_path/clientchecks.pm";
require "$modules_path/userManagement.pm";
require "$modules_path/events.pm";
require "$modules_path/mysql.pm";
require "$modules_path/statistics.pm";
require "$modules_path/commands.pm";
require "$modules_path/odch.pm";
# Register the script and announce a presence
sub main(){
        odch::register_script_name($botname);

        if (&getVerboseOption("verbose_botjoin")){
		&version();
        }
	
	# When the Hub fires up again since creation or when killed or restarted set everyone Offline.
	# If we do not do this it will lead to false positives in clone checking.

        my($sth) = $dbh->prepare("UPDATE userDB set status='Offline' ");
        $sth->execute();
        $sth->finish();

	# odch::data_to_all("\$MyINFO \$ALL $botname $botDescription\$ \$$botConnection\$\$$botShare\$|");
	odch::data_to_all("\$MyINFO \$ALL $botname $botDescription\$ \$$botConnection\x01\$\$$botShare\$|")
	&addToLog($botname,"Restart","Reloadscripts");
}






## Required in every module ##
1;
