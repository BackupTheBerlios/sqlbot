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
	my $kwth = $dbh->prepare("SELECT nick,information FROM botWorker WHERE function LIKE'1%'");
	$kwth->execute();
	while (my $ref = $kwth->fetchrow_hashref())
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
		&debug("(10)$user($ip)$information");
		odch::kick_user($user); # GoodBye..

		my $kw1th = $dbh->prepare("SELECT kickCountTot,kickCount FROM userDB WHERE nick='$user'");
		$kw1th->execute();
		my $ref = $kw1th->fetchrow_hashref();

		my($kickCountTot) = "$ref->{'kickCountTot'}";
		my($kickCount) = "$ref->{'kickCount'}";
		$kickCount++;
		$kickCountTot++;
		$kw1th->finish();
		$dbh->do("UPDATE userDB SET kickCountTot='$kickCountTot',kickCount='$kickCount',lastReason='$information',lastAction='Kicked' WHERE nick='$user'");
	}
	$kwth->finish();
	$dbh->do("DELETE FROM botWorker WHERE function LIKE '1%' ");
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
	my $bwth = $dbh->prepare("SELECT function,nick,information,IP FROM botWorker WHERE function LIKE '2%'");
	$bwth->execute();
	while (my $ref = $bwth->fetchrow_hashref()){
		my($function) = "$ref->{'function'}";
		my($user) = "$ref->{'nick'}";
		my($ip) = "$ref->{'IP'}";
		my($information) = "$ref->{'information'}";
		&debug("($function)$user($ip)$information");
		if ($function=='21'){
			&banUser($user,$information,$ip,"pban");}
		elsif($function=='22'){
			&banUser($user,$information,$ip,"tban");}
		elsif($function=='23'){
			&banUser($user,$information,$ip,"uban");}
		elsif($function=='24'){
			&banUser($user,"Faker",$ip,"pban");}
		$dbh->do("DELETE FROM botWorker WHERE function LIKE '2%' ");}
	
	$bwth->finish();
}
##############################################################################################
#Read the botWorker table and Ban users
sub banUser (){
	my($user,$reason,$ip,$mode)=@_;

	my $buth = $dbh->prepare("SELECT tBanCount,tBanCountTot,pBanCountTot FROM userDB WHERE nick='$user'");
	$buth->execute();
	my $ref = $buth->fetchrow_hashref();
	my($tBanCount) = "$ref->{'tBanCount'}";
	my($tBanCountTot) = "$ref->{'tBanCountTot'}";
	my($pBanCountTot) = "$ref->{'pBanCountTot'}";
	&debug("BanUser($mode)-$user($ip)$reason");
	$buth->finish();

	if ($mode =~ /tBan/i){		# Temporary Ban
		$mode = "T-Banned";
		$tBanCount++;
		$tBanCountTot++;
		odch::add_ban_entry($ip);
		if (&getVerboseOption("verbose_banned")){
			&msgAll("T-BANNED $user($ip) for:$reason");}}
	elsif ($mode =~ /pban/i){	# Permanent Ban
		$mode = "P-Banned";
		$pBanCountTot++;
		odch::add_ban_entry($ip);
		if (&getVerboseOption("verbose_banned")){
			&msgAll("P-BANNED $user($ip) for:$reason");}}
	elsif ($mode =~ /faker/i){	# Faker
		$mode = "P-Banned";
		$pBanCountTot++;
		&msgUser("$user","Dont FAKE your Client OR share ... Your IP has been [Banned]");
		&addToFakers($user);
		odch::add_ban_entry($ip);
		&msgAll("P-BANNED $user($ip) for:$reason");
		return(1);}
	elsif ($mode =~ /uban/i){	# Remove ban
		my($allowStatus) = "allow";
		my($lastReason) = "Removed"; 
		$mode = "Un-Ban";
		&msgAll("UnBan/Reset Kick Count $user($ip)");
		odch::remove_ban_entry($ip);
		$dbh->do("UPDATE userDB SET tBanCount='0',
					kickCount='0',
					allowStatus='Normal',
					lastReason='$reason',
				    	lastAction='$mode'
				    	WHERE nick='$user'");
		return(1);}
	else{return(1);}
	$dbh->do("UPDATE userDB SET tBanCountTot='$tBanCountTot',
				tBanCount='$tBanCount',
				pBanCountTot='$pBanCountTot',
				allowStatus='$mode',
				lastReason='$reason',
			    	lastAction='$mode'
			    	WHERE nick='$user'");
	if(&getLogOption("log_bans"))
    		{&addToLog($user,$mode,$reason);}
	odch::kick_user($user);
	&msgUser($user,"You have been BANNED($mode)");

}
## Required in every module ##
1;
