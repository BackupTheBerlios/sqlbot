#############################################################################################
# 	module name 	pubcommands.pm
#
#	Author		Nutter, Axllent
#
#	Summary		Public command handlers
#
#	Description	This module contains all the subs that are called by anyone in the main
#			hub chat. Add a sub in here, then add the trigger in odch.pm
#
#
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org:8080/			
#
##############################################################################################
## Channel Stats Request ##
sub showFakers()
{
	my($user)=@_;
	my $sth = $dbh->prepare("SELECT * FROM fakers");
	$sth->execute();
	$log = "";
	while (my $ref = $sth->fetchrow_hashref()) 
		{$log .= "$ref->{'date'}($ref->{'time'}) $ref->{'name'} - $ref->{'ip'} $ref->{'country'} $ref->{'shared_bytes'} \r"; 
	}
	$sth->finish();
	&msgAll("Fakers detected are\n\r $log");
	&debug("$user - fakers built");
}


# User requested his own info
sub myInfo()
{
	my($user)=@_;

	my($totalconnect) = $dbh->selectrow_array("SELECT total_logins FROM user_stats where name = '$user'");
	my($average_gigs) = $dbh->selectrow_array("SELECT average_shared_gigs FROM user_stats where name = '$user'");
	my($first_login_date) = $dbh->selectrow_array("SELECT first_date FROM user_stats where name = '$user'");
	my($first_login_time) = $dbh->selectrow_array("SELECT first_time FROM user_stats where name = '$user'");

	my $sth = $dbh->prepare("SELECT * FROM online where name = '$user'");
	$sth->execute();
	my $ref = $sth->fetchrow_hashref();
	&msgUser("$user","Your Info:\r
Name : $ref->{'name'}\r
User Type : $ref->{'user_type'}\r
Total_Logins : $totalconnect\r
First Login : $first_login_date at $first_login_time\r
Client : $ref->{'client'}\r
Client Vers : $ref->{'client_version'}\r
Connection Mode : $ref->{'connection_mode'}\r
IP : $ref->{'ip'}\r
Country : $ref->{'country'}\r
Online Since : $ref->{'date'} at $ref->{'time'}\r
Sharing (Gigs) : $ref->{'shared_gigs'}G\r
Average Share : $average_gigs G\r
Email_Address : $ref->{'email'}");
        $sth->finish();
	&debug("$user - info sent");
}

sub seen()
{
	my($userseen)=@_;
	$seenresult = "";
	my($match) = 0;
	$seensearch = $userseen;
	$userseen  =~ s/\*/\%/g;
	$seenresult= "\r";
	
	my($user_count) = $dbh->selectrow_array("SELECT COUNT(*) FROM user_stats where name like '$userseen'");
	if ($user_count ne 0)	
		{my($sth) = $dbh->prepare("SELECT * FROM user_stats where name like '$userseen'");
		$sth->execute();
		while(my $ref = $sth->fetchrow_hashref()){
			my($last_date) = $ref->{'last_date'};
			my($last_time) = $ref->{'last_time'};
			my($name) = $ref->{'name'};
			my($user_online) = $dbh->selectrow_array("SELECT COUNT(*) FROM online where name = '$name'");
			if (($user_online) ne 1)
				{$seenresult .= "$name was last online on $last_date at $last_time\n\r";}
			else
				{$seenresult .= "$name is on online now\n\r";}
			$match++;
		}
		$sth->finish();
		$seenresult .= "Matches found for \'$seensearch\': $match\n\r";
	}
	else
		{my($user_online) = $dbh->selectrow_array("SELECT COUNT(*) FROM online where name = '$userseen'");
		if (($user_online) eq 0)
				{$seenresult .= "No Matches found for \'$seensearch\'\r";}
			else
				{$seenresult .= "\'$seensearch\' is on online now\r";}
	}
	&debug("$user - seen built");
}

#############################################################################################
sub buildRules {
	my($user)=@_;
	&parseClient($user);
	$rules = "";
	
	if (&getClientExists($dcClient)) 
		{my ($sth) = $dbh->prepare("SELECT * FROM client_rules WHERE client='$dcClient'");
		$sth->execute();
		my $ref = $sth->fetchrow_hashref();
		my($minShare) = $ref->{'min_share'};
		my($minVersion) = $ref->{'min_version'};
		my($minSlots) = $ref->{'min_slots'};
		my($maxSlots) = $ref->{'max_slots'};
		my($slotRatio) = $ref->{'slot_ratio'};
		my($maxHubs) = $ref->{'max_hubs'};
		my($dcClientname) = $ref->{'client_name'};
		my($minconnection) = &getConnection($ref->{'min_connection'});
		$sth->finish();
		$uconn = odch::get_connection($user);
		$uconnection = &getConnection($uconn);


		$rules = "$user .. The Rules for your client ($dcClientname) are.....\r
- Minimum share of $minShare GB.\r
- $dcClientname must be at least version $minVersion.\n\r";
if ($minSlots ne 0){$rules .= "- You must NOT have less than $minSlots slots available.\n\r";}
else	{my($minslots) = &getConnectionSlots($uconn,1);
	$rules .= "- Minimum Required slots are currently based on connection, For your connection($uconnection) you need at least $minslots Slots\n\r";}
if ($maxSlots ne 0){$rules .= "- You must NOT have more than $maxSlots slots available.\n\r";}
else	{my($maxslots) = &getConnectionSlots($uconn,2);
	$rules .= "- Maximum Required slots are currently based on connection, For your connection($uconnection) you can have a Maximum of $maxslots Slots\n\r";}

$rules .= "- You may connect to a maximum of $maxHubs hubs. \r
- maintain a Hubs/Slots ratio of at least $slotRatio.\r
- have a minimum connection of $minconnection.\n\r";
		
	}
	else 
		{if ((lc($dcClientname) eq lc(""))  )
			{if (&getConfigOption("kick_notags")) 
				{$rules = "$user.. Your client is NOT displaying a Tag, and Clients NOT displaying TAGS will be Auto-kicked. Sort it out !";
				&debug("$user - Rules noTag Warning");}
			else
				{my ($sth) = $dbh->prepare("SELECT * FROM client_rules WHERE client='++'");
				$sth->execute();
				my $ref = $sth->fetchrow_hashref();
				my($minshare) = $ref->{'min_share'};
				$sth->finish();
				$rules = "$user.. Your client is not displaying a Tag, and Tag checking is currently disabled.\r The only rules u must meet are the minimum share of $minshare GB\n\r";
				&debug("$user - Rules noTag ok");
			}
		}
	}
# Add static hub rules
	$rth = $dbh->prepare("SELECT * FROM hub_rules");
	$rth->execute();
	my($tmp_rules) = "";
	while (my $ref = $rth->fetchrow_hashref()) {
		$tmp_rules .=  "- $ref->{'rule'} \n\r";}
	$rth->finish();
	$rules .= "$tmp_rules";
	&debug("$user - Rules built");
}
#############################################################################################
# Help commands in PM for all user types
sub buildHelp(){
	my($user) = @_;
	my($type) = odch::get_type($user);
	
	$helpmsg = "";
	$helpmsg = "Hi, Im $botname. I maintain Law and order in here!\r
A number of public commands are available to you. Type these in the main chat \r
- +help = Shows these commands in a PM\r
- +time = Shows the current Data & Time of the server\r
- +version = Shows which version the bot is running\r
- +showops = List of Ops Online\r
- +fakers = List share fakers detected\r
- +rules = rules for your client,\r
- +stats = Channel statistics\r
- +myinfo = Show your information \r
- +seen \'username\' = show when the specified user was last online\n\r
The follwing commands can be used in a PM to me\r
- !help = Shows these commands in a PM\r
- !seen \'username\' = show when the specified user was last online\r 
- !stats = Shows the hub statistics\r
- !rules = Shows the rules\r";

	if(($type eq 32) or ($type eq 16))
	{
		my($defaultLogEntries) = &getHubVar("nr_log_entries");
		$helpmsg .="\n\r\n\rYou user type shows that Op commands are available to you\r
Private Msg Me (Type in here) the following\r
- !recheck = force bot to re check all clients,and update online table\r
- !info \'username\' = get the info of another user\r
- !log = show the last $defaultLogEntries Entries from the hublog\r
- !kicklog = show the last $defaultLogEntries kicks log\r
- !banlog = show the last $defaultLogEntries bans log\r
- !history \'username\' = show the last $defaultLogEntries log entries of the user\r
- !addfaker \'username\' = Add the user to the fakers log, then Nuke\r
Any command NOT recognised is sent to ALL OPs as OP CHAT \r";
	}
	if($type eq 32)
	{
#		$helpmsg .="\n\r\n\rOp Admin commands available to you\r
# - ##################################### \r";
	}
	&debug("$user - Help built");
}

sub info()
{
	my($user,$infoon)=@_;
	&debug("$user - info $infoon");

	if (&usrOnline($user) ne 1)	#Online now ?
	{
		&msgUser("$user","$infoon is not online");}
	else{
		my($totalkicks) = $dbh->selectrow_array("SELECT COUNT(*) FROM log where name='$infoon' && action='Kicked'");
		$type = odch::get_type($subpm);
		if($type eq 32 or $type eq 16 or $type eq 8){$QUERY_LOGON = "LogOn";}
		else {$QUERY_LOGON = "Connect";}
			my($totalconnect) = $dbh->selectrow_array("SELECT total_logins FROM user_stats where name = '$infoon'");
		my($average_gigs) = $dbh->selectrow_array("SELECT average_shared_gigs FROM user_stats where name = '$infoon'");
		my($first_login_date) = $dbh->selectrow_array("SELECT first_date FROM user_stats where name = '$infoon'");
		my($first_login_time) = $dbh->selectrow_array("SELECT first_time FROM user_stats where name = '$infoon'");
  			my $sth = $dbh->prepare("SELECT * FROM online where name = '$infoon'");
		$sth->execute();
		my $ref = $sth->fetchrow_hashref();
		&msgUser("$user","$ref->{'name'}'s Info (MySQL)\r
Name : $ref->{'name'}\r
User Type : $ref->{'user_type'}\r
Total_Logins : $totalconnect\r
First Login : $first_login_date at $first_login_time\r
Total_Kicks : $totalkicks\r
Client : $ref->{'client'}\r
Client Vers : $ref->{'client_version'}\r
Connection Mode : $ref->{'connection_mode'}\r
IP : $ref->{'ip'}\r
Country : $ref->{'country'}\r
Online Since : $ref->{'date'} at $ref->{'time'}\r
Sharing (Gigs) : $ref->{'shared_gigs'}G\r
Average Share : $average_gigs G\r
Email_Address : $ref->{'email'}");
	        $sth->finish();
	}
	&debug("$user - Info sent");
}

sub log()
{
	my($user)=@_;
	&setTime();
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my $sth = $dbh->prepare("SELECT * FROM log where date = '$date' ORDER by rowID DESC  LIMIT 0,$defaultLogEntries");
	$sth->execute();
	$log = "";
	while (my $ref = $sth->fetchrow_hashref()) {
			$log .= "\r\n$ref->{'date'}($ref->{'time'}) [$ref->{'action'} - $ref->{'reason'}] $ref->{'name'}"; }
	$sth->finish();
	&msgUser("$user","$log");
	&debug("$user - log sent");
}

sub kickLog()
{
	my($user)=@_;
	&setTime();
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my $sth = $dbh->prepare("SELECT * FROM log where date = '$date' AND (action = 'Kicked' or action = 'Banned') ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$sth->execute();
	$log = "";
	while (my $ref = $sth->fetchrow_hashref()) {
		$log .= "\r\n$ref->{'date'}($ref->{'time'}) [$ref->{'action'} - $ref->{'reason'}] $ref->{'name'}"; }
	$sth->finish();
	&msgUser("$user","$log");
	&debug("$user - Kick log sent");
}

sub banLog()
{
	my($user,$data)=@_;
	&setTime();
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my $sth = $dbh->prepare("SELECT * FROM log where date = '$date' AND action = 'Banned' ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$sth->execute();
	$log = "";
	while (my $ref = $sth->fetchrow_hashref()) {
		$log .= "\r\n$ref->{'date'}($ref->{'time'}) [$ref->{'action'} - $ref->{'reason'}] $ref->{'name'}"; }
	$sth->finish();
	&msgUser("$user","$log");
	&debug("$user - Ban log sent");
}

sub fakersLog()
{
	my($user)=@_;
	&setTime();
	my $sth = $dbh->prepare("SELECT * FROM fakers");
	$sth->execute();
	$log = "";
	while (my $ref = $sth->fetchrow_hashref()) {
		$log .= "\r\n$ref->{'date'}($ref->{'time'}) $ref->{'name'} ($ref->{'ip'}) [$ref->{'shared_bytes'} bytes]"; }
	$sth->finish();
	&msgUser("$user","$log");
	&debug("$user - Fakers sent");
}

sub history()
{
	my($user,$history)=@_;
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my($if_exist) = $dbh->selectrow_array("SELECT COUNT(*) FROM log WHERE name = '$history'");
	my $sth = $dbh->prepare("SELECT * FROM log WHERE name= '$history' ORDER by rowID DESC  LIMIT 0,$defaultLogEntries");
	$sth->execute();
	$log = "";
	while (my $ref = $sth->fetchrow_hashref()) {
		$log .= "\r\n$ref->{'date'}($ref->{'time'}) [$ref->{'action'} - $ref->{'reason'}] $ref->{'name'}"; }
	$sth->finish();
	&msgUser("$user","$log");
}
sub addFaker(){
	my($faker)=@_;
	&splitDescription($faker);
	$REASON = "FAKER";
	$ACTION = "Nuked";
	&msgAll("$faker is $REASON gonna $ACTION him");
	&processEvent($faker);
}

sub showOps(){
	$result = "";
	my $sth = $dbh->prepare("SELECT * FROM online WHERE user_type='Operator' OR user_type='Op-Admin'");
	$sth->execute();
	while (my $ref = $sth->fetchrow_hashref()){
		$result .= " $ref->{'name'},";}
	$sth->finish();
}
## Required in every module ##
1;
