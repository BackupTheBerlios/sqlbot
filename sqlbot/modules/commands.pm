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
	my $sfth = $dbh->prepare("SELECT outTime,IP,country,shareByte FROM userDB WHERE lastReason='Faker'");
	$sfth->execute();
	$log = "";
	while (my $ref = $sth->fetchrow_hashref()) 
		{$log .= "$ref->{'outTime'} - $ref->{'IP'} $ref->{'country'} $ref->{'shareByte'} \r"; 
	}
	$sfth->finish();
	&msgAll("Fakers detected are\n\r $log");
	&debug("$user - fakers built");
}


# User requested his own info
sub myInfo()
{
	my($user)=@_;

	my($loginCount) = $dbh->selectrow_array("SELECT loginCount FROM userDB WHERE nick='$user'");
	my($avShareBytes) = $dbh->selectrow_array("SELECT avShareBytes FROM userDB WHERE nick='$user'");
	my($shareByte) = $dbh->selectrow_array("SELECT shareByte FROM userDB WHERE nick='$user'");
	my($firstTime) = $dbh->selectrow_array("SELECT firstTime FROM userDB WHERE nick='$user'");

	my $mith = $dbh->prepare("SELECT * FROM userDB WHERE nick='$user'");
	$mith->execute();
	my $ref = $mith->fetchrow_hashref();
	&msgUser("$user","Your Info:\r
Name : $ref->{'nick'}\r
User Type : $ref->{'utype'}\r
Total_Logins : $loginCount\r
First Login : $firstTime\r
Client : $ref->{'dcClient'}\r
Client Vers : $ref->{'dcVersion'}\r
Connection Mode : $ref->{'connectionMode'}\r
IP : $ref->{'IP'}\r
Country : $ref->{'country'}\r
Online Since : $ref->{'inTime'}\r
Sharing  : $ref->{'shareByte'}\r
Average Share : $avShareBytes");
        $mith->finish();
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
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my($userCount) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB WHERE nick like '$userseen'");
	if ($userCount ne 0)	
		{my($sth) = $dbh->prepare("SELECT * FROM userDB WHERE nick like '$userseen' LIMIT $defaultLogEntries");
		$sth->execute();
		while(my $ref = $sth->fetchrow_hashref()){
			my($lastTime) = $ref->{'lastTime'};
			my($nick) = $ref->{'nick'};
			my($status)="Online";
			my($userOnline) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB WHERE nick='$userseen' AND status='$status' ");
			if (($userOnline) ne 1)
				{$seenresult .= "$nick was last online on $lastTime\n\r";}
			else
				{$seenresult .= "$nick is on online now\n\r";}
			$match++;
		}
		$sth->finish();
		my($userCount) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB WHERE nick like '$userseen'");
		$seenresult .= "Returned $match of $userCount for \'$seensearch\' \n\r";
	}
	else
		{my($userOnline) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB WHERE nick='$userseen' AND status='$status'");
		if (($userOnline) eq 0)
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
		{my ($brth) = $dbh->prepare("SELECT min_share,min_version,min_slots,slot_ratio,max_hubs,client_name,min_connection FROM client_rules WHERE client='$dcClient'");
		$brth->execute();
		my $ref = $brth->fetchrow_hashref();
		my($minShare) = $ref->{'min_share'};
		my($minVersion) = $ref->{'min_version'};
		my($minSlots) = $ref->{'min_slots'};
		my($maxSlots) = $ref->{'max_slots'};
		my($slotRatio) = $ref->{'slot_ratio'};
		my($maxHubs) = $ref->{'max_hubs'};
		my($dcClientname) = $ref->{'client_name'};
		my($minconnection) = &getConnection($ref->{'min_connection'});
		$brth->finish();
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
	$rth = $dbh->prepare("SELECT rule FROM hub_rules");
	$rth->execute();
	my($tmp_rules) = "";
	while (my $ref = $rth->fetchrow_hashref()) {
		$tmp_rules .=  "- $ref->{'rule'} \n\r";}
	$rth->finish();
	$rules .= "$tmp_rules";
}
#############################################################################################
# Help commands in PM for all user types
sub buildHelp(){
	my($user) = @_;
	my($type) = odch::get_type($user);
	
	$helpmsg = "";
	$helpmsg = "Hi, Im $botname. I maintain Law and order in here!\r
Public Commands (Main Chat):-\r
- +help = Shows these commands in a PM\r
- +rules = rules for your client,\r
- +myinfo = Show your information \r
- +time = Shows the current Data & Time of the server\r
- +showops = List of Ops Online\r
- +fakers = List share fakers detected\r
- +version = Shows which version the bot is running\r
- +stats = (Removed Temporarily)Channel statistics\r
- +myinfo = Show your information \r
- +away \'Reason\' = Mark yourself away for the reason\r
- +back = Mark yourself back. (Auto back on hub chat)\r
- +topchat = Top 10 talkers....\r
- +uptime = The Up Time of the hub\r
- +seen \'username\' = show when the specified user was last online\r
Public commands(PM):-\r
- !help = Shows these commands in a PM\r
- !seen \'username\' = show when the specified user was last online\r 
- !stats = Shows the hub statistics\r
- !rules = Shows the rules\n\r";
	if(($type eq 32) or ($type eq 16) or ($type eq 8))
	{
	$helpmsg .="- !pass \'oldpassword\' \'newpassword\' = Change your User password\r";
	}
		
	if(($type eq 32) or ($type eq 16))
	{
		my($defaultLogEntries) = &getHubVar("nr_log_entries");
		$helpmsg .="\n\r\n\rOp commands(PM):-\r
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
		$helpmsg .="\n\r\n\rOp-Admin commands(PM):-\r
- !auser \'username\' \'password\' \'level\'= Add/Edit the user with given password at level (0=reg,1=Op,2=OpAdmin) Also use to change User level\r
- !duser \'username\' = Delete user\r";
	}
}

sub info()
{
	my($user,$infoUser)=@_;
	my($userCount) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB WHERE nick='$infoUser'");
	if ($userCount ne 0)
	{
		my $ith = $dbh->prepare("SELECT nick,status,allowStatus,awayStatus,uType,loginCount,firstTime,kickCountTot,
			dcClient,dcVersion,connectionMode,IP,country,inTime,shareBytes,avShareBytes FROM userDB where nick='$infoUser'");
		$ith->execute();
		my $ref = $ith->fetchrow_hashref();
		&msgUser("$user","$ref->{'nick'}'s Info (MySQL)\r
Name : $ref->{'nick'}\r
Status : $ref->{'status'}\r
Allowed Status : $ref->{'allowStatus'}\r
Away Status : $ref->{'awayStatus'}\r
User Type : $ref->{'uType'}\r
Total Logins : $ref->{'loginCount'}\r
First Login : $ref->{'firstTime'}\r
Total Kicks : $ref->{'kickCountTot'}\r
Client : $ref->{'dcClient'}\r
Client Vers : $ref->{'dcVersion'}\r
Connection Mode : $ref->{'connectionMode'}\r
IP : $ref->{'IP'}\r
Country : $ref->{'country'}\r
Online Since : $ref->{'inTime'}\r
Share : $ref->{'shareByte'}\r
Average Share : $ref->{'avShareBytes'}");
	        $ith->finish();
	}
}

sub log()
{
	my($user)=@_;
	&setTime();
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my $sth = $dbh->prepare("SELECT logTime,action,reason,nick FROM hubLog ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$sth->execute();
	$result = "";
	while (my $ref = $sth->fetchrow_hashref()) {
			$result .= "\r\n$ref->{'logTime'} [$ref->{'action'} - $ref->{'reason'}] $ref->{'nick'}"; }
	$sth->finish();
	&msgUser("$user","$result");
}

sub kickLog()
{
	my($user)=@_;
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my $sth = $dbh->prepare("SELECT logTime,action,reason,nick FROM hubLog WHERE action like 'Kicked' ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$sth->execute();
	$result = "";
	while (my $ref = $sth->fetchrow_hashref()) {
		$result .= "\r\n$ref->{'logTime'} [$ref->{'action'} - $ref->{'reason'}] $ref->{'nick'}"; }
	$sth->finish();
	&msgUser("$user","$result");
}

sub banLog()
{
	my($user)=@_;
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my $sth = $dbh->prepare("SELECT logTime,action,reason,nick FROM hubLog WHERE action like 'Ban' ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$sth->execute();
	$result = "";
	while (my $ref = $sth->fetchrow_hashref()) {
		$result .= "\r\n$ref->{'logTime'} [$ref->{'action'} - $ref->{'reason'}] $ref->{'nick'}"; }
	$sth->finish();
	&msgUser("$user","$result");
}

sub fakersLog()
{
	my($user)=@_;
	&setTime();
	my $sth = $dbh->prepare("SELECT logTime,nick FROM hubLog WHERE reason='Fakers'");
	$sth->execute();
	$result = "";
	while (my $ref = $sth->fetchrow_hashref()) {
		$log .= "\r$ref->{'logTime'} $ref->{'nick'}"; }
	$sth->finish();
	&msgUser("$user","$result");

}

sub history()
{
	my($user,$hUser)=@_;
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my($if_exist) = $dbh->selectrow_array("SELECT COUNT(*) FROM hubLog WHERE nick='$hUser'");
	my $sth = $dbh->prepare("SELECT logTime,action,reason,nick FROM hubLog WHERE nick='$hUser' ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$sth->execute();
	$$result = "";
	while (my $ref = $sth->fetchrow_hashref()) {
		$$result .= "\r\n$ref->{'logTime'} [$ref->{'action'} - $ref->{'reason'}] $ref->{'nick'}"; }
	$sth->finish();
	&msgUser("$user","$result");
}


sub showOps(){
	$result = "";
	my $sth = $dbh->prepare("SELECT nick FROM userDB WHERE status='Online' AND (uType='Operator' OR uType='Op-Admin')");
	$sth->execute();
	while (my $ref = $sth->fetchrow_hashref()){
		$result .= " $ref->{'nick'} ";}
	$sth->finish();
}
## Required in every module ##
1;
