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

# User requested his own info
sub myInfo()
{
	my($user)=@_;

	my($loginCount) = $dbh->selectrow_array("SELECT loginCount FROM userDB 
				WHERE nick='$user' AND allowStatus!='Banned' AND status='Online' ");
	my($avShareBytes) = $dbh->selectrow_array("SELECT avShareBytes FROM userDB 
				WHERE nick='$user' AND allowStatus!='Banned' AND status='Online'");
	my($shareByte) = $dbh->selectrow_array("SELECT shareByte FROM userDB 
				WHERE nick='$user' AND allowStatus!='Banned' AND status='Online'");
	my($firstTime) = $dbh->selectrow_array("SELECT firstTime FROM userDB 
				WHERE nick='$user' AND allowStatus!='Banned' AND status='Online'");

	my($mith) = $dbh->prepare("SELECT * FROM userDB WHERE nick='$user' AND allowStatus!='Banned' AND status='Online'");
	$mith->execute();
	my($ref) = $mith->fetchrow_hashref();
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
		{my($sth) = $dbh->prepare("SELECT * FROM userDB 
					WHERE nick like '$userseen' LIMIT $defaultLogEntries");
		$sth->execute();
		while($ref = $sth->fetchrow_hashref()){
			my($outTime) = $ref->{'outTime'};
			my($nick) = $ref->{'nick'};
			my($userOnline) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB 
						WHERE nick='$userseen' AND status='Online' ");
			if (($userOnline) ne 1)
				{$seenresult .= "$nick was last online on $outTime \r\n";}
			else
				{$seenresult .= "$nick is on online now \r\n";}
			$match++;
		}
		$sth->finish();
		my($userCount) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB 
					WHERE nick like '$userseen'");
		$seenresult .= "Returned $match of $userCount for \'$seensearch\'  \r\n";
	}
	else
		{my($userOnline) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB 
					WHERE nick='$userseen' AND status='Online'");
		if (($userOnline) eq 0)
				{$seenresult .= "No Matches found for \'$seensearch\'\r";}
			else
				{$seenresult .= "\'$seensearch\' is Online now\r";}
	}
	&debug("$user - seen built");
}

#############################################################################################
sub buildRules {
	my($user)=@_;
	&parseClient($user);
	$rules = "";
	if (&getClientExists($dcClient)) 
		{my($brth) = $dbh->prepare("SELECT * FROM client_rules WHERE client='$dcClient'");
		$brth->execute();
		my($ref) = $brth->fetchrow_hashref();
		my($minShare) = $ref->{'min_share'};
		my($minVersion) = $ref->{'min_version'};
		my($minSlots) = $ref->{'min_slots'};
		my($maxSlots) = $ref->{'max_slots'};
		my($slotRatio) = $ref->{'slot_ratio'};
		my($maxHubs) = $ref->{'max_hubs'};
		my($dcClientname) = $ref->{'client_name'};
		my($minconnection) = &getConnection($ref->{'min_connection'});
		$brth->finish();
		my($conn) = odch::get_connection($user);
		$uconnection = &getConnection($conn);


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
				{my($sth) = $dbh->prepare("SELECT * FROM client_rules WHERE client='++'");
				$sth->execute();
				my($ref1) = $sth->fetchrow_hashref();
				my($minshare) = $ref1->{'min_share'};
				$sth->finish();
				$rules = "$user.. Your client is not displaying a Tag, and Tag checking is currently disabled.\r The only rules u must meet are the minimum share of $minshare GB\n\r";
				&debug("$user - Rules noTag ok");
			}
		}
	}
# Add static hub rules
	my($rth) = $dbh->prepare("SELECT rule FROM hub_rules");
	$rth->execute();
	my($tmp_rules) = "";

	while($ref2 = $rth->fetchrow_hashref()) {
		$tmp_rules .=  "- $ref2->{'rule'} \n\r";}
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
. +help = Shows these commands in a PM\r
. +rules = rules for your client,\r
. +myinfo = Show your information \r
. +time = Shows the current Data & Time of the server\r
. +showops = List of Ops Online\r
. +fakers = List share fakers detected\r
. +version = Shows which version the bot is running\r
. +stats = Channel statistics\r
. +myinfo = Show your information \r
. +away \'Reason\' = Mark yourself away for the reason\r
. +back = Mark yourself back. (Auto back on hub chat)\r
. +topchat = Top 10 talkers....\r
. +uptime = The Up Time of the hub\r
. +seen \'username\' = show when the specified user was last online\r
Public commands(PM):-\r
. +help = Shows these commands in a PM\r
. +seen \'username\' = show when the specified user was last online\r 
. +stats = Shows the hub statistics\r
. +rules = Shows the rules\n\r";
	if(($type eq 32) or ($type eq 16) or ($type eq 8))
	{
	$helpmsg .="- !pass \'oldpassword\' \'newpassword\' = Change your User password\r";
	}
		
	if(($type eq 32) or ($type eq 16))
	{
		my($defaultLogEntries) = &getHubVar("nr_log_entries");
		$helpmsg .=" \r\n \r\nOp commands(PM):-\r
. +recheck = force bot to re check all clients,and update online table\r
. +info \'username\' = get the info of another user\r
. +log = show the last $defaultLogEntries Entries from the hublog\r
. +kicklog = show the last $defaultLogEntries kicks log\r
. +banlog = show the last $defaultLogEntries bans log\r
. +history \'username\' = show the last $defaultLogEntries log entries of the user\r
. +addfaker \'username\' = Add the user to the fakers log, then Nuke\r
Any command NOT recognised is sent to ALL OPs as OP CHAT \r";
	}
	if($type eq 32)
	{
		$helpmsg .="\n\r\n\rOp-Admin commands(PM):-\r
. +auser \'username\' \'password\' \'level\'= Add/Edit the user with given password at level (0=reg,1=Op,2=OpAdmin) Also use to change User level\r
. +duser \'username\' = Delete user\r";
	}
}

sub info()
{
	my($user,$infoUser)=@_;
	my($userCount) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB WHERE nick='$infoUser'");
	if ($userCount ne 0)
	{
		my($ith) = $dbh->prepare("SELECT nick,status,allowStatus,awayStatus,uType,loginCount,firstTime,kickCountTot,
			dcClient,dcVersion,connectionMode,IP,country,inTime,shareByte,avShareBytes FROM userDB where nick='$infoUser'");
		$ith->execute();
		my($ref) = $ith->fetchrow_hashref();
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
	my($lth) = $dbh->prepare("SELECT logTime,action,reason,nick FROM hubLog ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$lth->execute();
	$result = "";
	while ($ref = $lth->fetchrow_hashref()) {
			$result .= "\r\n$ref->{'logTime'} [$ref->{'action'} - $ref->{'reason'}] $ref->{'nick'}"; }
	$lth->finish();
	&msgUser("$user","$result");
}

sub kickLog()
{
	my($user)=@_;
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my($klth) = $dbh->prepare("SELECT logTime,action,reason,nick FROM hubLog WHERE action like 'Kicked' ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$klth->execute();
	$result = "";
	while ($ref = $klth->fetchrow_hashref()) {
		$result .= "\r\n$ref->{'logTime'} [$ref->{'action'} - $ref->{'reason'}] $ref->{'nick'}"; }
	$klth->finish();
	&msgUser("$user","$result");
}

sub banLog()
{
	my($user)=@_;
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my($blth) = $dbh->prepare("SELECT logTime,action,reason,nick FROM hubLog WHERE action like 'Ban' ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$blth->execute();
	$result = "";
	while (my($ref) = $blth->fetchrow_hashref()) {
		$result .= "\r\n$ref->{'logTime'} [$ref->{'action'} - $ref->{'reason'}] $ref->{'nick'}"; }
	$blth->finish();
	&msgUser("$user","$result");
}

sub fakersLog()
{
	my($user)=@_;
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my($flth) = $dbh->prepare("SELECT outTime,nick,IP FROM userDB WHERE lastReason='Faker' ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$flth->execute();
	$result = "Fakers are :\r";
	while ($ref = $flth->fetchrow_hashref()) {
		$result .= "\r$ref->{'nick'}($ref->{'IP'}) $ref->{'outTime'} "; }
	$flth->finish();
	&msgUser("$user","$result");

}

sub history()
{
	my($user,$hUser)=@_;
	my($defaultLogEntries) = &getHubVar("nr_log_entries");
	my($if_exist) = $dbh->selectrow_array("SELECT COUNT(*) FROM hubLog WHERE nick='$hUser'");
	my($hth) = $dbh->prepare("SELECT logTime,action,reason FROM hubLog WHERE nick='$hUser' ORDER by rowID DESC LIMIT 0,$defaultLogEntries");
	$hth->execute();
	$result = "";
	while ($ref = $hth->fetchrow_hashref()) {
		$result .= "\r\n$ref->{'logTime'} [$ref->{'action'} - $ref->{'reason'}] $ref->{'nick'}"; }
	$hth->finish();
	&msgUser("$user","$result");
}


sub showOps(){
	$result = "";
	my($soth) = $dbh->prepare("SELECT nick FROM userDB WHERE status='Online' AND (uType='Operator' OR uType='Op-Admin')");
	$soth->execute();
	while ($ref = $soth->fetchrow_hashref()){
		$result .= " $ref->{'nick'} ";}
	$soth->finish();
}
## Required in every module ##
1;
