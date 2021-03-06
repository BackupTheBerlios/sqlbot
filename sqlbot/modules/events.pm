#############################################################################################
# 	module name 	events.pm
#
#	Author		Nutter, Axllent
#
#	Summary		Unpredictable events handling
#
#	Description	Functions such as logons,user kicks, user bans, etc. This module does
#			handles the logging of the event and takes the action.
#
#
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org:8080/			
#
##############################################################################################
sub userDisconnect(){
	my($user)=@_;
	my($type) = odch::get_type($user);
	if($type eq 32 or $type eq 16 or $type eq 8)
		{if(&getLogOption("log_logoffs"))
        		{&addToLog($user,"LogOff","");}}
	else
		{if(&getLogOption("log_disconnects"))
	        	{&addToLog($user,"Disconnect","");}}
}

sub userConnect(){
	my($user)=@_;
	my($type) = odch::get_type($user);
	if($type eq 32 or $type eq 16 or $type eq 8)
		{if(&getLogOption("log_logons"))
	        	{&addToLog($user,"LogOn","");}}
	else
		{if(&getLogOption("log_connects"))
	        	{&addToLog($user,"Connect","");}}
}

######################################################################################
# Write an ACTION & REASON entry to the hublog
sub processEvent(){
	my($user)=@_;

	if (lc($ACTION) ne lc("")){
		&checkKicks($user);}

	# Select the appropriate action
	if (lc($REASON) eq lc("Fake(Share)"))
		{&banUser($user,"Fake(Share)",$ip,"pban");}
	elsif (lc($ACTION) eq lc("P-Banned"))
		{&banUser($user,"$REASON",$ip,"pban");}
	elsif (lc($ACTION) eq lc("Kicked")){
		if (&getConfigOption("client_check")){
			&kickUser($user,$REASON);}}
	elsif (lc($ACTION) eq lc("T-Banned")){
		&banUser($user,"$REASON",$ip,"tban");}
}

sub botWorker(){
	# Check for Kick Events

	my($WKicks) = $dbh->selectrow_array("SELECT COUNT(*) FROM botWorker WHERE function LIKE '1%'");
	if($WKicks ne 0)
		{
		&kickWorker();}
	#Check for pban Events
	my($WBans) = $dbh->selectrow_array("SELECT COUNT(*) FROM botWorker WHERE function LIKE '2%'"); # Or 22 or 23
	if($WBans ne 0)
		{
		&banWorker();}
	#Check for User List events
	my($WUsers) = $dbh->selectrow_array("SELECT COUNT(*) FROM botWorker WHERE function LIKE '3%'"); # Or 31 or 32 or 33
	if($WUsers ne 0)
		{
		&userWorker();}
	#Check for User List events # 40 - add  41 - Delete  42 - Allow Status
	my($WIpFilter) = $dbh->selectrow_array("SELECT COUNT(*) FROM botWorker WHERE function LIKE '4%'"); # Or 40 or 41
	if($WIpFilter ne 0)
		{
		&ipFilterWorker();}
	#User is marked as allowed	
	my($WAUser) = $dbh->selectrow_array("SELECT COUNT(*) FROM botWorker WHERE function LIKE '5%'"); # Or 50
	if($WAUser ne 0)
		{&aUserWorker();}
}

sub ipFilterWorker(){
	my($bwth) = $dbh->prepare("SELECT function,nick,information,IP FROM botWorker WHERE function LIKE '4%'");
	$bwth->execute();
	while ($ref = $bwth->fetchrow_hashref())
	{	my($function) = "$ref->{'function'}";
		my($mask) = "$ref->{'nick'}";
		my($ip) = "$ref->{'IP'}";
		my($information) = "$ref->{'information'}";
		my($banMask) = "$ip/$mask";		

		if ($function=='40')
		{ # Add this IP range ban
			odch::add_ban_entry($banMask);
			if (&getVerboseOption("verbose_banned"))
				{&msgAll("Banned IP Ban Range ($banMask) $information");}
		}
		elsif($function=='41')
		{ # Delete this IP range ban
			odch::remove_ban_entry($banMask);
			if (&getVerboseOption("verbose_banned"))
				{&msgAll("Removed IP Ban Range ($banMask) $information");}
		}
		elsif($function=='42')
		{ # Any user with IP in range is set to allowed
			# Do a scan of all users and mark any states in range as allowed
		}
		$dbh->do("DELETE FROM botWorker WHERE function LIKE '4%' AND IP='$ip'");
	}
	$bwth->finish();
}


## Required in every module ##
1;
