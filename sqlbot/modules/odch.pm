#############################################################################################
# 	module name 	odch.pm
#
#	Author		Nutter, Axllent
#
#	Summary		Interface to ODCH calls
#
#	Description	All functions called directly by the odch hub.Any additional odch 
#			functions should be added here, simple functions can be included
#			directly, more complex functions, subs should be added into  
#			another module and handled there.
#
#
#	
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org:8080/
#
##############################################################################################

# Fires when a user is kicked
sub kicked_user()
{
	my($user,$kickedby) = @_;
	&userOffline($user);
	&addToLog($user,"Kicked",$kickedby);

	
	
}

# Fires when a normal User has connected
sub new_user_connected(){
	my($user) = @_;
	&splitDescription($user);
	
	my($userInDB) = &userInDB($user,$ip);
	
	if($userInDB eq 1)
		{&parseClient($user);
		&updateUserRecord($user);
		&userConnect($user);	
		&checkClones($user);
		&processEvent($user);			
	}
	elsif($userInDB eq 0)
		{&parseClient($user);
		&createNewUserRecord($user);
		&userConnect($user);	
		&checkClones($user);
		&processEvent($user);
	}
	elsif($userInDB eq 2)
		{&updateUserRecord($user);
		&userConnect($user);
		&userOnline($user);
	}
	
	&userOnline($user);
}

# Fires when a registered user has connected
sub reg_user_connected(){
	my($user) = @_;
	
	&splitDescription($user);

	my($userInDB) = &userInDB($user,$ip);
	if($userInDB eq 1)
		{&parseClient($user);
		&updateUserRecord($user);
		&userConnect($user);	
		if (&getConfigOption("check_reg")) 
		{	&checkClones($user);
			&processEvent($user);}
	}
	elsif($userInDB eq 0)
		{&parseClient($user);
		&createNewUserRecord($user);
		&userConnect($user);	
		if (&getConfigOption("check_reg")) 
		{	&checkClones($user);
			&processEvent($user);}
	}
	elsif($userInDB eq 2)
		{&updateUserRecord($user);
		&userConnect($user);
		&userOnline($user);
	}
	
	if (&getVerboseOption("verbose_op_connect"))
		{&msgAll("Reg User $user just connected");}

}

# Fires when an op has connected
sub op_connected(){
	my($user) = @_;
	
	&splitDescription($user);

	my($userInDB) = &userInDB($user,$ip);
	if($userInDB eq 1)
		{&parseClient($user);
		&updateUserRecord($user);
		&userConnect($user);	
		if (&getConfigOption("check_op")) 
		{	&checkClones($user);
			&processEvent($user);}
	}
	elsif($userInDB eq 0)
		{&parseClient($user);
		&createNewUserRecord($user);
		&userConnect($user);	
		if (&getConfigOption("check_op")) 
		{	&checkClones($user);
			&processEvent($user);}
	}
	elsif($userInDB eq 2)
		{&updateUserRecord($user);
		&userConnect($user);
		&userOnline($user);
	}
	
	&userOnline($user);

	if (&getVerboseOption("verbose_op_connect"))
		{&msgAll("Op $user just connected");
		&msgOPs("$botname","Op $user just connected");}
}

#Fires when an Op Admin has connected
sub op_admin_connected() 
{
	my($user) = @_;
	
	&splitDescription($user);
	my($userInDB) = &userInDB($user,$ip);
	if($userInDB eq 1)
		{&parseClient($user);
		&updateUserRecord($user);
		&userConnect($user);	
		if (&getConfigOption("check_opadmin"))
		{	&checkClones($user);
			&processEvent($user);}	
	}
	elsif($userInDB eq 0)
		{&parseClient($user);
		&createNewUserRecord($user);
		&userConnect($user);	
		if (&getConfigOption("check_opadmin"))
		{	&checkClones($user);
			&processEvent($user);}	
	}
	elsif($userInDB eq 2)
		{&updateUserRecord($user);
		&userConnect($user);
		&userOnline($user);
	}
	&userOnline($user);
	
	if (&getVerboseOption("verbose_op_connect"))
		{&msgAll("OpAdmin $user just connected");
		&msgOPs("$botname","OpAdmin $user just connected");}

}

# Fires when a User disconnects
sub user_disconnected(){
	my($user) = @_;
	&userDisconnect($user);
	&userOffline($user);
}

# Fires every 900 seconds. Used for regular things
sub hub_timer() {
	if (&getConfigOption("post_client_check")){	
		&clientRecheck();}	#Check all clients
	&checkRecords();#Any new records, Max Share, users ?
	if (&getVerboseOption("hub_timer")){
		&totalUptime();
		my($webAddress) = &getHubVar("hub_website_address");
		&msgAll("Hub has been running for $days days,$hours hours and $mins mins. Visit $webAddress");
	}
}

#Data has received
sub data_arrival(){
	my($user,$data)=@_;
	if($data =~ /GetNickList/)
	{
		odch::data_to_user($user,"\$MyINFO \$ALL $botname $botDescription\$ \$botConnection\$\$$botShare\$|");
	} 

	if($data =~ /\$To: $botname From: (.*)\|/)
	{
		my($pm )= $1;
		
		@params = split(/\ /, $pm);
		$param1 = $params[2];$param2 = $params[3];$param3 = $params[4];$param4 = $params[5];

		# Only specific types may msg the bot
		my($type) = odch::get_type($user);
		#Public PM commands
		if($param1 =~ /^[\!+-]seen/i)
			{if($param1 =~ /^[\!+-]seen\|/i)
				{&msgUser("$user","usage: +seen username");}
			else 
				{&seen($param2);
				&msgUser("$user","$seenresult");}}
		elsif($param1 =~ /^[\!+-]stats/i)
			{&buildStats();
			&msgUser("$user","$statsmsg");}
		elsif($param1 =~ /^[\!+-]rules/i)
			{&buildRules($user);
			&msgUser("$user","$rules");}
		elsif($param1 =~ /^[\!+-]help/i)
			{&buildHelp($user);
			&msgUser("$user","$helpmsg");}
		elsif($param1 =~ /^[\!+-]myinfo/i)
			{&myInfo($user);}
		#Op commands
		elsif($type eq 8)
			{if ($param1 =~ /^[\!+-]pass/){
				if($param1 =~ /^[\!+-]pass\|/i)
					{&msgUser("$user","+pass oldpassword newpass");}
				else{&chPassUser($user,$param2,$param3,$param4);}}}
		elsif($type eq 32 or $type eq 16)
			{if ($param1 =~ /^[\!+-]info/){
				if($param1 =~ /^[\!+-]info\|/i)
					{&msgUser("$user","+info username");}
				else
					{&info($user,$param2);}}
			elsif($param1 =~ /^[\!+-]pass/i){
				if($param1 =~ /^[\!+-]pass\|/i)
					{&msgUser("$user","+pass oldpass newpass");}
				else
					{&chPassUser($user,$param2,$param3);}}
			elsif ($param1 =~ /^[\!+-]recheck/i){
				&msgUser("$user","Rechecking Online clients");	
				&clientRecheck();
				&msgUser("$user","Done");}
			elsif ($param1 =~ /^[\!+-]log/i)
				{&log($user);}
			elsif ($param1 =~ /^[\!+-]kicklog/i)
				{&kickLog($user);}
			elsif ($param1 =~ /^[\!+-]banlog/i)
				{&banLog($user);}
			elsif ($param1 =~ /^[\!+-]kick/i)
				{if($param1 =~ /^[\!+-]kick\|/i)
					{&msgUser("$user","usage: +kick 'username' 'reason'");}
				else 
					{&splitDescription($user);
					@reason = split(/\"/, $pm);
					$reason = $reason[1];$reason =~ s/^(")//;$reason =~ s/(")$//;
					&kickUser($param2,$reason);}}
			elsif ($param1 =~ /^[\!+-]tban/i)
				{if($param1 =~ /^[\!+-]tban\|/i)
					{&msgUser("$user","usage: +tban 'username' 'reason'");}
				else 
					{@reason = split(/\"/, $pm);
					$reason = $reason[1];$reason =~ s/^(")//;$reason =~ s/(")$//;
					my($userip) = &getUserIp($param2);
					&banUser($param2,$reason,$userip,"tban");
					&kickUser($param2,$reason);}}
			elsif ($param1 =~ /^[\!+-]pban/i)
				{if($param1 =~ /^[\!+-]pban\|/i)
					{&msgUser("$user","usage: +pban 'username' 'reason'");}
				else 
					{@reason = split(/\"/, $pm);
					$reason = $reason[1];$reason =~ s/^(")//;$reason =~ s/(")$//;
					my($userip) = &getUserIp($param2);
					&banUser($param2,$reason,$userip,"pban");
					&kickUser($param2,$reason);}}
			elsif ($param1 =~ /^[\!+-]uban/i)
				{if($param1 =~ /^[\!+-]uban\|/i)
					{&msgUser("$user","usage: +uban username reason");}
				else 
					{@reason = split(/\"/, $pm);
					$reason = $reason[1];$reason =~ s/^(")//;$reason =~ s/(")$//;
					my($userip) = &getUserIp($param2);
					&banUser($param2,$reason,$userip,"uban");
					&kickUser($param2,$reason);}}
			elsif ($param1 =~ /^[\!+-]fakerslog/i)
				{&fakersLog($user);}
			elsif ($param1 =~ /^[\!+-]history/i)
				{if($param1 =~ /^[\!+-]history\|/i)
					{&msgUser("$user","usage: +history username");}
				else 
					{&history("$user","$param2");}}
			elsif ($param1 =~ /^[\!+-]addfaker/i)
				{if($param1 =~ /^[\!+-]addfaker\|/)
					{&msgUser("$user","usage: +addfaker username");}
				else 
					{my($ip) = odch::get_ip($param2);
					&banUser($param2,"Faker",$ip,"pban");}}
			# Add new op commands here
			elsif($param1 =~ /^[\!+-]auser/i)
			{
				if($param1 =~ /^[\!+-]auser\|/)
					{&msgAll("usage: !auser nick pass level");}
				else
					{&setRegUser($user,$param2,$param3,$param4);}

			}
			elsif($param1 =~ /^[\!+-]duser/i)
			{
				if($param1 =~ /^[\!+-]duser\|/)
					{&msgAll("usage: +auser nick");}
				else
					{&delRegUser($user,$param2);}
			}
			else
				{#Send to OPChat
				$pos1 = rindex($pm,"\$"); #Get to end of data
				$opmsg = substr($pm, $pos1+1);
				&msgOPs("$user","$opmsg");
				&incLineCount($user);}
		}
	}
	# Public main chat commands
	else
	{
		if($data =~ /^<.*> [\+-]fakers\|/i)
			{&fakersLog($user);}
		elsif($data =~ /^<.*> [\+-]time\|/i)
			{&setTime();
			&msgAll("Server Time = $date $time");}
		elsif($data =~ /^<.*> [\+-]version\|/i)
			{&version($user);}
		elsif($data =~ /^<.*> [\+-]myinfo\|/i)
			{&myInfo($user);}
		elsif($data =~ /^<.*> [\+-]uptime\|/i)
			{&totalUptime();
			my($webAddress) = &getHubVar("hub_website_address");
			&msgAll("The Hub has been running for $days days,$hours hours and $mins mins.");}
		elsif($data =~ /^<.*> [\+-]help\|/i)
			{&buildHelp($user);
			&msgUser("$user","$helpmsg");}
		elsif($data =~ /^<.*> [\+-]stats\|/i)
			{&buildStats();
			&msgUser("$user","$statsmsg");}
		elsif($data =~ /^<.*> [\+-]rules\|/i)
			{&buildRules($user);
			&msgUser("$user","$rules|");}
		elsif($data =~ /^<.*> [\+-]showOps\|/i)
			{&showOps();
			&msgAll("Ops online: $result|");}
		elsif($data =~ /^<.*> [\+-]records/i)
			{if(&checkRecords())
				{&msgAll("No New record has been set. Use +stats to view current records.");}	}
		elsif($data =~ /^<.*> [\+-]seen/i)
			{if($data =~ /^<.*> [\+-]seen\|/)
				{&msgAll("usage: +seen username");}
			elsif($data =~ /^<.*> \s?(\S*) (.*)\|/)
				{&seen($2);
				&msgUser("$user","$seenresult");}}
		elsif($data =~ /^<.*> [\+-]dcgui\|/i)
			{&msgAll("DCGUI - Homepage = http://dc.ketelhot.de/|");}
		elsif($data =~ /^<.*> [\+-]sqlbot\|/i)
			{&msgAll("sqlBot - Project Homepage = http://sqlbot.berlios.de/|");}
		elsif($data =~ /^<.*> [\+-]topchat\|/i)
			{&topChat();&msgAll("$msg");}		
		elsif($data =~ /^<.*> [\+-]away/i)
			{&userAway("$user","$data");}
		elsif($data =~ /^<.*> [\+-]back/i)
			{&userBack("$user");}
		elsif($data =~ /^<.*> [\+-]/i)
			{&msgAll("RTFM!  Try +help");}
		elsif($data =~ /^<.*>(.*)/)
			{&incLineCount($user);
#			&printolog($data);
#NEW FEATURE CHAT LOGGING			
#			logfile_name
#
#
#
			}

		#OpAdmin ONLY public commands

		my($type) = odch::get_type($user);
		if($type eq 32)
			{if($data =~ /^<.*>\+test/i)
			{&msgUser("$user","Got it");}}
	}
	&botWorker();
} # end sub data arrival

## Required in every module ##
1;
