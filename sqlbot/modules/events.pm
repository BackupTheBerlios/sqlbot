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
sub logDisconnect{
	my($user)=@_;
  	$REASON = "";
	if($type eq 32 or $type eq 16 or $type eq 8)
		{$ACTION = "LogOff";
	      	if(&getLogOption("log_logoffs"))
        		{&addToLog($user,$ACTION,$REASON);}}
	else
		{$ACTION = "Disconnect";
	      	if(&getLogOption("log_disconnects"))
	        	{&addToLog($user,$ACTION,$REASON);}}

	&delFromOnline($user);
	&debug("$user - logOff.done");
}

sub logLogon{
	my($user)=@_;
	$REASON = "";

	if($type eq 32 or $type eq 16 or $type eq 8)
		{$ACTION = "LogOn";
	      	if(&getLogOption("log_logons"))
	        	{&addToLog($user,$ACTION,$REASON);}}
	else
		{$ACTION = "Connect";
	      	if(&getLogOption("log_connects"))
	        	{&addToLog($user,$ACTION,$REASON);}}

	&updateInStats($user);
	if (&usrOnline($user)){&delFromOnline($user);}
	&addToOnline($user);
	&debug("$user - logON.done");	
}

######################################################################################
# Write an ACTION & REASON entry to the hublog
sub processEvent(){
	my($user)=@_;
	&debug("$user - processEvent");

	# Select the appropriate action
	$kicked = "Kicked";
	$banned = "Banned";
	$nuked = "Nuked";
	$notags = "NoTags";
		
	if (lc($REASON) eq lc("FAKER"))
	{
		&msgAll("$user is a FAKER, sharing $shared Bytes.IP was $ip");
		&msgUser("$user","Dont FAKE your share ... Your IP has been [Banned]");
		&addToFakers($user);
	}
	elsif (lc($REASON) eq lc("MLDonkey"))
	{
		&msgUser("$user","No MLDonkey... Your IP has been [Banned]");
	}
	
	if (lc($ACTION) eq lc($banned)){
    		if(&getLogOption("log_bans"))
    			{&addToLog($user,$ACTION,$REASON);}
		&delFromOnline($user);
		odch::add_ban_entry($ip);
		&addToKick($user,$REASON); # GoodBye..
		if (&getVerboseOption("verbose_banned")){
			&msgAll("$ACTION $user for : $REASON");
		}
	}
	elsif (lc($ACTION) eq lc($kicked)){
		if (&getConfigOption("client_check")){
			if (lc($REASON) eq lc($notags))	{
				if(&getLogOption("log_no_tags_kicks")){
					&addToLog($user,$ACTION,$REASON);}}
			else{
        			if(&getLogOption("log_kicks"))
        				{&addToLog($user,$ACTION,$REASON);}}
			&addToKick($user,$REASON);
			&buildRules($user);
			$delayedKickTime = &getHubVar("delayed_kick_time");
			&msgUser("$user","Your client does meet the required rules $rules.");
			&msgUser("$user","Please get within these rules. You will be autokicked in $delayedKickTime Seconds");
		}
	}
	elsif (lc($ACTION) eq lc($nuked)){
		if(&getLogOption("log_nukes"))
      			{&addToLog($user,$ACTION,$REASON);}
		&delFromOnline($user);
		odch::add_ban_entry($ip);
		&addToKick($user,$REASON); # GoodBye..
		if (&getVerboseOption("verbose_nukes"))
			{&msgAll("$ACTION $user for : $REASON");}
	}
	&debug("$user - $ACTION $user $REASON");
	&debug("$user - processEvent.done");
}

# Read the kick table and kick all marked users
sub kickKickTable()
{
	my $sth = $dbh->prepare("SELECT * FROM kick");
	$sth->execute();
	while (my $ref = $sth->fetchrow_hashref()) {
		$kickuser = "$ref->{'user'}";
		$reason = "$ref->{'reason'}";
		if (&getVerboseOption("verbose_kicks")){
			if (lc($REASON) eq lc($notags))	{
				if (&getVerboseOption("verbose_notagkicks"))	{
					&msgAll("Kicking $ref->{'user'} for breaking the $ref->{'reason'} rule");}}
			else{&msgAll("Kicking $ref->{'user'} for breaking the $ref->{'reason'} rule");}}
		odch::kick_user($kickuser); # GoodBye..
		&debug("Kicked - $kickuser - $reason");
		&buildRules($user);
		&msgUser("$kickuser","You have been kicked for breaking the Rules Reason: $reason .Check the current rules\r $rules.");
	}
	$sth->finish();
	&delFromKick();
	$alarmSet = 0; #Enable kick timer to be restarted

}
## Required in every module ##
1;
