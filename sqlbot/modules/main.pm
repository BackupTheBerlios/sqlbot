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
#	http://axljab.homelinux.org:8080/			
#
##############################################################################################

$botVersion = "0.2.0(devel)";


use DBI;
use IP::Country::Fast;
use Date::Simple ('date', 'today');

$dbh = DBI->connect("DBI:mysql:odch:$sql_server","$sql_username","$sql_password",{ RaiseError => 1, AutoCommit => 0 });
$dbh->do("SET OPTION SQL_BIG_TABLES = 1");

# Import the other modules,

require "$modules_path/common.pm";
require "$modules_path/kickban.pm";
require "$modules_path/events.pm";
require "$modules_path/mysql.pm";
require "$modules_path/clientchecks.pm";
require "$modules_path/statistics.pm";
require "$modules_path/commands.pm";
require "$modules_path/userManagement.pm";
require "$modules_path/odch.pm";
# Register the script and announce a presence
sub main(){
        odch::register_script_name($botname);

        if (&getVerboseOption("verbose_botjoin")){
		&version();
        }
	&addToLog($botname,"Restart","Reloadscripts");
}






## Required in every module ##
1;
