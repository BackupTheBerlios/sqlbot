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

# Fires when a ban is added
sub added_perm_ban()
{
    	my($entry) = @_;
    	&msgAll("$entry has been BANNED");
}

# Fires when a user is kicked
sub kicked_user()
{
	my($user,$REASON) = @_; #$REASON = the kicker
	&parseClient($user);
	&delFromOnline(@_);
	&addToLog($user,"Kicked",$REASON);
}

# Fires when a normal User has connected
sub new_user_connected(){
	my($user) = @_;
	&parseClient($user);
	&checkKicks($user);
	&checkClones($user);
	&processEvent($user);
	&logLogon($user);
	&buildHelp($user);
	&msgUser("$user","$helpmsg");
}

# Fires when a registered user has connected
sub reg_user_connected(){
	my($user) = @_;
	&parseClient($user);
	if (&getConfigOption("check_reg")) 
	{	&checkKicks($user);
		&checkClones($user);
		&processEvent($user);
		&logLogon($user);}
	else 
		{&logLogon($user);}

	if (&getVerboseOption("verbose_op_connect"))
		{&msgAll("Reg User $user just connected");}

	&buildHelp($user);
	&msgUser("$user","$helpmsg");
}

# Fires when an op has connected
sub op_connected(){
	my($user) = @_;
	&parseClient($user);
	if (&getConfigOption("check_op")) 
	{	&checkKicks($user);
		&checkClones($user);
		&processEvent($user);
		&logLogon($user);}
	else 
		{&logLogon($user);}

	if (&getVerboseOption("verbose_op_connect"))
		{&msgAll("Op $user just connected");}

	&buildHelp($user);
	&msgUser("$user","$helpmsg");
}

#Fires when an Op Admin has connected
sub op_admin_connected() 
{
	my($user) = @_;
	&parseClient($user);
	if (&getConfigOption("check_opadmin")) 
	{	&checkClones($user);
		&checkKicks($user);
		&processEvent($user);
		&logLogon($user);}
	else 
		{&logLogon($user);}


	if (&getVerboseOption("verbose_op_connect"))
		{&msgAll("OpAdmin $user just connected");}

	&buildHelp($user);
	&msgUser("$user","$helpmsg");

}

# Fires when a User disconnects
sub user_disconnected(){
	my($user) = @_;
	&parseClient($user);
	&logDisconnect($user);
	&updateInStats($user);
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
	if($data =~ /\$To: $botname From: $user \$\<$user\> (.*)\|/)
	{
		my $pm = $1;
		# Only specific types may msg the bot
		my($type) = odch::get_type($user);

		#Op commands
		if($type eq 32 or $type eq 16)
		{
			&debug("$pm");
			if ($pm =~ /!info/){
				if($data =~ /\$To: $botname From: $user \$\<$user\> !info\|/)
					{&msgUser("$user","!info username");}
				elsif($data =~ /\$To: $botname From: $user \$\<$user\> \s?(\S*) (.*)\|/) 
					{&info($user);}
			}
			elsif ($pm =~ /!recheck/){
				&msgAll("$user has forced all clients to be rechecked");	
				&clientRecheck();}
			elsif ($pm =~ /!log/){
				&log($user);}
			elsif ($pm =~ /!kicklog/){
				&kickLog($user);}
			elsif ($pm =~ /!banlog/){
				&banLog($user);}
			elsif ($pm =~ /!fakerslog/){
				&fakerslog($user);}
			elsif ($pm =~ /!history/)
			{
				if($data =~ /\$To: $botname From: $user \$\<$user\> !history\|/)
					{&msgUser("$user","usage: !history username");}
				elsif($data =~ /\$To: $botname From: $user \$\<$user\> \s?(\S*) (.*)\|/) 
					{&history(@_);}
			}
		}
		#Public PM commands
		if($pm =~ /!seen/)
			{if($data =~ /\$To: $botname From: $user \$\<$user\> !seen\|/)
				{&msgUser("$user","usage: !seen username");}
			elsif($data =~ /\$To: $botname From: $user \$\<$user\> \s?(\S*) (.*)\|/) 
				{&seen(@_);
				&msgUser("$user","$seenresult");}			
		}
		elsif($pm =~ /!stats/)
			{&buildStats();
			&msgUser("$user","$statsmsg");}
		elsif($pm =~ /!rules/)
			{&buildRules($user);
			&msgUser("$user","$rules");}
		elsif($pm =~ /!help/)
			{&buildHelp(@_);
			&msgUser("$user","$helpmsg");}
		elsif($pm =~ /!myinfo/)
			{&myinfo(@_);}
	}
	# Public main chat commands
	else
	{
		if($data =~ /^<.*> \+fakers/i)
			{&showFakers($user);}
		elsif($data =~ /^<.*> \+time/i)
			{&setTime();
			&msgAll("Server Time = $date $long_time");}
		elsif($data =~ /^<.*> \+version/i)
			{&version($user);}
		elsif($data =~ /^<.*> \+myinfo/i)
			{&myInfo($user);}
		elsif($data =~ /^<.*> \+help/i)
			{&buildHelp($user);
			&msgUser("$user","$helpmsg");}
		elsif($data =~ /^<.*> \+stats/i)
			{&buildStats();
			&msgUser("$user","$statsmsg");}
		elsif($data =~ /^<.*> \+rules/i)
			{&buildRules($user);
			&msgUser("$user","$rules|");}
		elsif($data =~ /^<.*> \+records/i)
			{if(&checkRecords())
				{&msgAll("No New record has been set. Use +stats to view current records.");}	}
		elsif($data =~ /^<.*> \+seen/i)
			{if($data =~ /^<.*> \+seen\|/)
				{&msgAll("usage: +seen username");}
			elsif($data =~ /^<.*> \s?(\S*) (.*)\|/)
				{&seen(@_);
				&msgAll("$seenresult");}
		}
		elsif($data =~ /^<.*> \+dcgui/i)
			{&msgAll("DCGUI - Homepage = http://dc.ketelhot.de/|");}
		elsif($data =~ /^<.*> \+sqlbot/i)
			{&msgAll("sqlBot - Project Homepage = http://sqlbot.berlios.de/|");}		
		elsif($data =~ /^<.*> \+/i)
			{&msgAll("Command not recognised. Try +help");}

		#OpAdmin ONLY public commands
		my($type) = odch::get_type($user);
		if($type eq 32)
		{
			if($data =~ /^<.*> \+vartest/i)
				{&msgAll();}
		}
	}
} # end sub data arrival

## Required in every module ##
1;
