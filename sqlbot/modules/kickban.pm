#############################################################################################
# 	module name 	clientchecks.pm
#
#	Author		Nutter, Axllent
#
#	Summary		Routines that check clients
#
#	Description	Functions that check a client/user for various things.
#
#
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org:8080/			
#
# Notes, Function codes
# 10=kick
# 20=allow	21=pban 22=tban	23=uban
# 30=OpAdmin	31=Op	32=Reg	33=Normal	
##############################################################################################
# (script & php) Read the botWorker table and kick marked users
sub kickWorker()
{
	my($kwth) = $dbh->prepare("SELECT nick,information FROM botWorker WHERE function LIKE'1%'");
	
	$kwth->execute();
	while ($ref = $kwth->fetchrow_hashref())
	{
		my($nick) = "$ref->{'nick'}";
		my $user  = &sqldeConvertNick($nick);
		my($information) = "$ref->{'information'}";
		
		if (&getVerboseOption("verbose_kicks")){
			if (lc($information) eq lc($notags)){
				if (&getVerboseOption("verbose_notagkicks"))
					{&msgAll("Kicking $user becuase of $information.");}}
			else{&msgAll("Kicking $user becuase of $information.");}}
		if(&getLogOption("log_kicks")){
			if (lc($information) eq lc($notags)){
				if(&getLogOption("log_no_tags_kicks")){
					&addToLog($user,'Kicked',$information);}}
			else{&addToLog($user,'Kicked',$information);}}
			
		&msgUser("$user","You have been kicked ($information)");
		odch::kick_user($user); # GoodBye..

		my($kw1th) = $dbh->prepare("SELECT kickCountTot,kickCount FROM userDB WHERE nick='$nick' AND allowStatus!='Banned'");
		$kw1th->execute();
		$ref1 = $kw1th->fetchrow_hashref();
		my($kickCountTot) = "$ref1->{'kickCountTot'}";
		my($kickCount) = "$ref1->{'kickCount'}";
		$kickCount++;
		$kickCountTot++;
		$kw1th->finish();
		$dbh->do("UPDATE userDB SET kickCountTot='$kickCountTot',kickCount='$kickCount',lastReason='$information',lastAction='Kicked' WHERE nick='$nick' AND allowStatus!='Banned'");

		$dbh->do("DELETE FROM botWorker WHERE function LIKE '1%' AND nick='$nick'");
	}
	$kwth->finish();
	
}
##############################################################################################
# (script) Add User to worker, called from bot
sub kickUser(){
	my($kickuser,$lastReason)=@_;
	my($ip) = odch::get_ip($kickuser);
	my($sqluser) = &sqlConvertNick($kickuser);
	$dbh->do("INSERT INTO botWorker VALUES ('mysql_insertid','10','$sqluser','$ip','$lastReason')");
}
##############################################################################################
# Read the botWorker table and Ban users
sub banWorker()
{
	my($bwth) = $dbh->prepare("SELECT function,nick,information,IP FROM botWorker WHERE function LIKE '2%'");
	$bwth->execute();
	while ($ref = $bwth->fetchrow_hashref()){
		my($function) = "$ref->{'function'}";
		my($sqluser) = "$ref->{'nick'}";
		my $user  = &sqldeConvertNick($sqluser);
		my($ip) = "$ref->{'IP'}";
		my($information) = "$ref->{'information'}";
		
		if ($function=='21'){
			&banUser($user,$information,$ip,"pban");}
		elsif($function=='22'){
			&banUser($user,$information,$ip,"tban");}
		elsif($function=='23'){
			&banUser($user,$information,$ip,"uban");}
		elsif($function=='24'){
			&banUser($user,"Fake(Tag)",$ip,"pban");}
		elsif($function=='25'){
			&banUser($user,"Fake(Share)",$ip,"pban");}
#		elsif($function=='26'){
#			&banUser($user,$information,$ip,"nban");}
		elsif($function=='27'){
			
			$dbh->do("UPDATE userDB SET tBanCount='0',kickCount='0'	WHERE nick='$sqluser'");
		}
		$dbh->do("DELETE FROM botWorker WHERE IP='$ip'");
	}
	$bwth->finish();
}
##############################################################################################
#Read the botWorker table and Ban users
sub banUser (){
	my($nick,$reason,$ip,$mode)=@_;
	my($sqluser) = &sqlConvertNick($nick);
	my($buth) = $dbh->prepare("SELECT lastReason,tBanCount,tBanCountTot,pBanCountTot FROM userDB WHERE nick='$sqluser' AND lastAction!='P-Banned'");
	$buth->execute();
	my $ref = $buth->fetchrow_hashref();
	my($tBanCount) = "$ref->{'tBanCount'}";
	my($tBanCountTot) = "$ref->{'tBanCountTot'}";
	my($pBanCountTot) = "$ref->{'pBanCountTot'}";
	my($lastReason) = "$ref->{'lastReason'}";
	my($lastAction) ="";
	my($allowStatus)= "";
	&debug("banUserBANNED- user=$nick, reason=$reason, ip=$ip, mode=$mode");

	$buth->finish();
	
	if ($mode =~ /tBan/i){		# Temporary Ban
		$lastAction = "T-Banned";
		$tBanCount++;
		$tBanCountTot++;
		$allowStatus = "Normal";
		my($temp_ban_time)=&getHubVar("temp_ban_time");
		if (&getVerboseOption("verbose_banned")){
			&msgAll("T-BANNED($temp_ban_time Mins) $nick($ip) $reason");}
			
		$temp_ban_time = $temp_ban_time * 60;
		odch::add_ban_entry("$ip $temp_ban_time");
		my($userInDB) = &userInDB($nick,$ip);
		if($userInDB ne 2)
			{odch::add_nickban_entry("$nick $temp_ban_time");}}
			
	elsif ($mode =~ /pban/i){	# Permanent Ban
		$lastAction = "P-Banned";
		$pBanCountTot++;
		odch::add_ban_entry($ip);
		$allowStatus = "Banned";
		my($userInDB) = &userInDB($nick,$ip);
		if($userInDB ne 2)
			{odch::add_nickban_entry($nick);}

		if (&getVerboseOption("verbose_banned")){
			&msgAll("P-BANNED $nick($ip) $reason");}}
			
	elsif ($mode =~ /uban/i){	# Remove ban
		$lastAction = "Un-Ban";
		if (&getVerboseOption("verbose_banned")){
			&msgAll("UN-BANNED $nick($ip) $reason");}
		odch::remove_ban_entry($ip);

		my($userInDB) = &userInDB($nick,$ip);
		if($userInDB ne 2)
			{odch::remove_nickban_entry($nick);}
		$dbh->do("UPDATE userDB SET tBanCount='0',
					kickCount='0',
					allowStatus='Normal',
					lastReason='Removed',
				    	lastAction='$lastAction'
				    	WHERE nick='$sqluser'");

		if(&getLogOption("log_bans"))
			{&addToLog($nick,$lastAction,"Removed");}			
		return(1);}
	else{return(1);}
	
	
	if($reason eq ""){ #If no reason is passed do not update
		$reason = $lastReason;}
	
	$dbh->do("UPDATE userDB SET tBanCountTot='$tBanCountTot',
				tBanCount='$tBanCount',
				pBanCountTot='$pBanCountTot',
				allowStatus='$allowStatus',
				lastReason='$reason',
			    	lastAction='$lastAction'
			    	WHERE nick='$sqluser'");
	
	if(&getLogOption("log_bans"))
    		{&addToLog($nick,$lastAction,$reason);}
	&msgUser($nick,"You have been BANNED share:$reason");
	odch::kick_user($nick);
}


## Required in every module ##
1;
