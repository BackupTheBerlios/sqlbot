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
		my($user) = "$ref->{'nick'}";
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

		my($kw1th) = $dbh->prepare("SELECT kickCountTot,kickCount FROM userDB WHERE nick='$user' AND lastAction!='P-Banned'");
		$kw1th->execute();
		$ref1 = $kw1th->fetchrow_hashref();

		my($kickCountTot) = "$ref1->{'kickCountTot'}";
		my($kickCount) = "$ref1->{'kickCount'}";
		$kickCount++;
		$kickCountTot++;
		$kw1th->finish();
		$dbh->do("UPDATE userDB SET kickCountTot='$kickCountTot',kickCount='$kickCount',lastReason='$information',lastAction='Kicked' 
			WHERE nick='$user' AND lastAction!='P-Banned'");
		$dbh->do("DELETE FROM botWorker WHERE function LIKE '1%' AND nick='$user'");
	}
	$kwth->finish();
	
}
##############################################################################################
# (script) Add User to worker, called from bot
sub kickUser(){
	my($user,$lastReason)=@_;
	my($ip) = odch::get_ip($user);
	if ($ip =~ /192.168/) {$ip = &getHubVar("external_ip"); }
	$dbh->do("INSERT INTO botWorker VALUES ('mysql_insertid','10','$user','$ip','$lastReason')");
}
##############################################################################################
# Read the botWorker table and Ban users
sub banWorker()
{
	my($bwth) = $dbh->prepare("SELECT function,nick,information,IP FROM botWorker WHERE function LIKE '2%'");
	$bwth->execute();
	while ($ref = $bwth->fetchrow_hashref()){
		my($function) = "$ref->{'function'}";
		my($user) = "$ref->{'nick'}";
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
			$dbh->do("UPDATE userDB SET tBanCount='0',kickCount='0'	WHERE nick='$user' AND lastAction!='P-Banned'");
		}
			
		$dbh->do("DELETE FROM botWorker WHERE nick='$user'");
	}
	$bwth->finish();
}
##############################################################################################
#Read the botWorker table and Ban users
sub banUser (){
	my($user,$reason,$ip,$mode)=@_;

	my($buth) = $dbh->prepare("SELECT tBanCount,tBanCountTot,pBanCountTot FROM userDB WHERE nick='$user' AND lastAction!='P-Banned'");
	$buth->execute();
	my $ref = $buth->fetchrow_hashref();
	my($tBanCount) = "$ref->{'tBanCount'}";
	my($tBanCountTot) = "$ref->{'tBanCountTot'}";
	my($pBanCountTot) = "$ref->{'pBanCountTot'}";
	my($lastAction) ="";
	
	&debug("banUserBANNED- user=$user, reason=$reason, ip=$ip, mode=$mode");

	$buth->finish();
	
	if ($mode =~ /tBan/i){		# Temporary Ban
		$lastAction = "T-Banned";
		$tBanCount++;
		$tBanCountTot++;
		
		my($temp_ban_time)=&getHubVar("temp_ban_time");
		if (&getVerboseOption("verbose_banned")){
			&msgAll("T-BANNED($temp_ban_time Mins) $user($ip) $reason");}
			
		$temp_ban_time = $temp_ban_time * 60;
		odch::add_ban_entry("$ip $temp_ban_time");
		my($userInDB) = &userInDB($user,$ip);
		if($userInDB ne 2)
			{odch::add_nickban_entry("$user $temp_ban_time");}}
			
	elsif ($mode =~ /pban/i){	# Permanent Ban
		$lastAction = "P-Banned";
		$pBanCountTot++;
		odch::add_ban_entry($ip);

		my($userInDB) = &userInDB($user,$ip);
		if($userInDB ne 2)
			{odch::add_nickban_entry($user);}

		if (&getVerboseOption("verbose_banned")){
			&msgAll("P-BANNED $user($ip) $reason");}}
			
	elsif ($mode =~ /uban/i){	# Remove ban
		$lastAction = "Un-Ban";
		if (&getVerboseOption("verbose_banned")){
			&msgAll("UN-BANNED $user($ip) $reason");}
		odch::remove_ban_entry($ip);

		my($userInDB) = &userInDB($user,$ip);
		if($userInDB ne 2)
			{odch::remove_nickban_entry($user);}
		$dbh->do("UPDATE userDB SET tBanCount='0',
					kickCount='0',
					allowStatus='Normal',
					lastReason='Removed',
				    	lastAction='$lastAction'
				    	WHERE nick='$user' AND allowStatus='Banned'");

		if(&getLogOption("log_bans"))
			{&addToLog($user,$lastAction,"Removed");}			
		return(1);}
	else{return(1);}
	$dbh->do("UPDATE userDB SET tBanCountTot='$tBanCountTot',
				tBanCount='$tBanCount',
				pBanCountTot='$pBanCountTot',
				allowStatus='Banned',
				lastReason='$reason',
			    	lastAction='$lastAction'
			    	WHERE nick='$user' AND IP='$ip'");
	
	if(&getLogOption("log_bans"))
    		{&addToLog($user,$lastAction,$reason);}
	&msgUser($user,"You have been BANNED share:$reason");
	odch::kick_user($user);

}
## Required in every module ##
1;
