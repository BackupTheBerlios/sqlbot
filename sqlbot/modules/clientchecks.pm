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
##############################################################################################
#Break down the components of a client description

sub splitDescription() {
	my($user) = @_;

	#Initialise globals
	$type=0; $ip=""; ;$GigsShared="";
	$tmpdata=""; $fullDescription=""; $dcClient="";$UploadLimit=0;
	$dcVersion=""; $NbHubs=0; $NSlots=0; $slt_ratio=""; $country="";
	$UploadLimit=0; $conn=""; $connection=""; $email="";$tmpModeAP="";
	$connectionMode="unknown";$tmp0="";$shareBytes=0;
	@tmp0 = ("","");@tmp1 = ("","");@tmp2 = ("","");@tmp3 = ("","");@tmp4 = ("","");
	@tmp4 = ("","","");my($tmpdata)="";my($verdata)="";my($tmpModeAP)="";
	my(@verdata2)=("","","","","","");my(@tmpdata2)=("","","","","","");
	
	$type = odch::get_type($user);
	$ip = odch::get_ip($user);
	$shareBytes = odch::get_share($user);
	$GigsShared = &roundToGB($shareBytes);
	$conn = odch::get_connection($user);
	$email = odch::get_email($user);

	## Check for internal networks ##
	if ($ip =~ /192.168/)  
		{$country = &getHubVar("hub_country");}
	else
		{## Get Country code for user #
		my($reg) = IP::Country::Fast->new();
		$country = $reg->inet_atocc($ip);}
	$hostname = odch::get_hostname($user);
	## Get user Type ##
	if($type =~ /8/){$utype = "Registered";}
	elsif($type =~ /16/){$utype = "Operator";}
	elsif($type =~ /32/){$utype = "Op-Admin";}
	else {$utype = "User";}

	$tmpdata = odch::get_description($user);
	if($tmpdata eq "") {return 1;}
	$tmpdata =~ s/'//g;
	$fullDescription = "$tmpdata";
	my($pos1) = rindex($tmpdata, "<") +1;
	my($pos2) = rindex($tmpdata, ">");
	if($pos2 < 10){return 1;}
	
	$verdata = substr($tmpdata, $pos1, $pos2 - $pos1);
	@verdata2 = split(/\ /, $verdata);

	$pos1 = rindex($tmpdata, "<$verdata2[0]") + 1 + length($verdata2[0]);
	$tmpdata = substr($tmpdata, $pos1, $pos2 - $pos1);
	@tmpdata2 = split(/,/, $tmpdata);

        $dcClient = $verdata2[0];
	
	
	@tmp0 = split(/:/, $tmpdata2[0]);
	@tmp1 = split(/:/, $tmpdata2[1]);
	@tmp2 = split(/:/, $tmpdata2[2]);
	@tmp3 = split(/:/, $tmpdata2[3]);
	@tmp4 = split(/:/, $tmpdata2[4]);
	$dcVersion = $tmp0[1];
	
	
	$tmpModeAP = $tmp1[1];
	if($tmpModeAP =~ /A/){$connectionMode = "Active";}
	elsif($tmpModeAP =~ /P/){$connectionMode = "Passive";}

	$NbHubs = $tmp2[1];
	## New DC ++ format support ##
	
	if($NbHubs =~ /\//) { ($a,$b,$c)=split(/\//,$NbHubs); $NbHubs=$a+$b+$c; }
	if($NbHubs == 0){$NbHubs++;}

	$NSlots = $tmp3[1];
	$UploadLimit = $tmp4[1];

	## Slot Ratio ##
	if ($NSlots > 0)
		{$slt_ratio = ($NSlots / $NbHubs) ;}
	else {$slt_ratio = 0;}

}
# Rescan all online clients
sub clientRecheck()
{
	my ($usersonline) = odch::get_user_list(); #Get space separated list of who is online
	my ($numonlineusers) = odch::count_users(); #And how many

	@userlist=split(/\ /,$usersonline);
	my ($checkUserCount) = 0;
	while ($checkUserCount != $numonlineusers)
	{
		$user=$userlist[$checkUserCount];
		$checkUserCount ++;
		&parseClient($user);

		if (($ip eq '') || ($user eq $botname)) {}
		else {
		$userInDB = &userInDB($user,$ip);
		if ($userInDB eq 2) {
			if (&userIsOnline($user,$ip) ne 1)
				{&userOnline($user);}	
		}#If they are set to allow then do nothing
		elsif ($userInDB eq 0) #If they dont exist create a record
			{&createNewUserRecord($user);
			&userConnect($user);	
			&userOnline($user);}
		else{
			if (&userIsOnline($user,$ip) ne 1)
				{&userOnline($user);}	
			else{
				&updateUserRecordRecheck($user);
				if($type eq 0 )	
					{&userOffline($user);}
				if($type eq 32 )
				{ #If Opadmin
					# Check OP admins if set
					if (&getConfigOption("check_opadmin")) {
						&checkKicks($user);  # Check kick counter
						&processEvent($user);}}    # take action if any
				elsif($type eq 16) { # if Op
					# Check OPs if set
					if (&getConfigOption("check_op")) {
						&checkKicks($user);  # Check kick counter
						&processEvent($user); }}   # take action if any
				elsif($type eq 8) { # if Reg User
					if (&getConfigOption("check_reg")) {
						&checkKicks($user);  # Check kick counter
						&processEvent($user); }}   # take action if any
				else {	# Normal user
					&checkKicks($user);  # Check kick counter
					&processEvent($user); }}}}}  # take action if any
	# Make sure the online sql table does not contain ghosts
	my ($cth) = $dbh->prepare("SELECT nick FROM userDB WHERE status='Online'");
	$cth->execute();
	while (my $ref = $cth->fetchrow_hashref()) {
		my($nick) = "";
		$nick = "$ref->{'nick'}"; 
		$type = odch::get_type($nick);
		if($type eq 0 )	{&userOffline($nick);}}
	$cth->finish();
	
	# Read all users from DB who are T-Bbanned and see if any have expired
#	my ($cibth) = $dbh->prepare("SELECT nick,IP FROM userDB WHERE lastAction='T-Banned'");
#	$cibth->execute();
#	while (my $ref = $cibth->fetchrow_hashref()) {
#		my($nick) = "";
#		$nick = "$ref->{'nick'}";
#		$ip = "$ref->{'IP'}"; 
#		$banned = odch::check_if_banned($nick,NICKBAN);
#		if($banned eq 1 ){&debug("$nick Is Banned");}
#		else{&debug("$nick Is Not Banned");}
#	}
#	$cibth->finish();
	
}


# Get all the info need from the client
sub parseClient(){
	my($user) = @_;
	
	&splitDescription($user);

	$REASON = "";
	$ACTION = "";
	
	## CHECK FAKERS ##
	if(($shareBytes =~ /(\d)\1{5,}/))
		{$REASON = "Fake(Share)";
		$ACTION = "P-Banned";
		if ((&getClientExists($dcClient)) && ($dcVersion ne "")){}
		else{	$dcClient = "";
			$dcClientname = "";
			$dcVersion = "";}
	}
	elsif(($shareBytes eq 11534336) || ($fullDescription =~ "mldc"  )) 
	{	$REASON = "MLDC";
		$ACTION = "P-Banned";
		$dcClientname = "mldc";
	}
	else {
	## CHECK CLIENT ##
		if ((&getClientExists($dcClient)) && ($dcVersion ne "")){
			my($pcth) = $dbh->prepare("SELECT * FROM client_rules WHERE client='$dcClient'");
			$pcth->execute();
			my ($ref) = $pcth->fetchrow_hashref();
			
			## MIN VERSION ##
			if ($dcVersion =~/(\d)\1{2,}/){
				if ($ref->{'min_version'} > $dcVersion)
					{$REASON = "Version";
					$ACTION = "Kicked";}}
			## MIN SLOTS ##
			if ($ref->{'min_slots'} > 0 )
				{if ($NSlots < $ref->{'min_slots'})
					{$REASON = "Slots(min)";
					$ACTION = "Kicked";}}
			else
				{my($minslots) = &getConnectionSlots($conn,1);
				if ($NSlots < $minslots)
					{$REASON = "Slots(min)";
					$ACTION = "Kicked";}}
			## MAX SLOTS ##
			if ($ref->{'max_slots'} > 0 )
				{if ($NSlots > $ref->{'max_slots'})
					{$REASON = "Slots(max)";
					$ACTION = "Kicked";}}
			else
				{my($maxslots) = &getConnectionSlots($conn,2);
				if ($NSlots > $maxslots)
					{$REASON = "Slots(max)";
					$ACTION = "Kicked";}}
			## SLOT RATIO ##
			if ($ref->{'slot_ratio'} ne 0 )
				{if ($ref->{'slot_ratio'} > $slt_ratio)
					{$REASON = "SlotRatio";
					$ACTION = "Kicked";}}
			## MAX HUBS ##
			if ($ref->{'max_hubs'} ne 0 )
				{if ($ref->{'max_hubs'} < $NbHubs)
					{$REASON = "Hubs";
					$ACTION = "Kicked";}}
			## MIN CONNECTION ##
			if ($ref->{'min_connection'} > $conn)
				{$REASON = "Connection";
				$ACTION = "Kicked";}
			## MIN SHARE ##
			if ($ref->{'min_share'} > $GigsShared)
				{$REASON = "Share";
				$ACTION = "Kicked";}

			## Add new client checks here ##
			#Use the real clientname not its TAG
			$dcClientname = $ref->{'client_name'};
			$pcth->finish();}
		else {
		## NO TAGS ##
			if (&getConfigOption("kick_notags"))
				{$REASON = "NoTags";
				$ACTION = "Kicked";}
			# Make sure the user meets at least the following if untagged
			my($pcth) = $dbh->prepare("SELECT * FROM client_rules WHERE client='++'");
			$pcth->execute();
			my($ref1) = $pcth->fetchrow_hashref();

			## MIN SHARE ##
			if ($ref1->{'min_share'} > $GigsShared)
				{$REASON = "Share";
				$ACTION = "Kicked";}
			## MIN CONNECTION ##
			elsif ($ref1->{'min_connection'} > $conn){
				$REASON = "Connection";
				$ACTION = "Kicked";}
			$dcClient = "No Tag";
			$dcClientname = "Not Known";
			$dcVersion = "No Tag";
			$pcth->finish();}
	## END ##
	}
	return 1;	
}

# Check number of times user has been kicked in past 24hrs
sub checkKicks(){
	my($user) = @_;
	&setTime();
	if (&getConfigOption("check_kicks"))
		{my($ckth) = $dbh->prepare("SELECT kickCount,tBanCount FROM userDB 
				WHERE nick = '$user' AND lastAction!='P-Banned'");
		$ckth->execute();
		my($ref) = $ckth->fetchrow_hashref();
		my($kickCount) = "$ref->{'kickCount'}";
		my($tBanCount) = "$ref->{'tBanCount'}";
		$ckth->finish();
		my($kick_before_tban) = &getHubVar("kick_before_tban");
		my($tban_before_pban) = &getHubVar("tban_before_pban");
		if ($tBanCount > $tban_before_pban)
			{&msgUser("$user","You have now been T-Banned $tban_before_pban times. You have been permantly banned!");
			$ACTION = "P-Banned";}
		elsif ($kickCount > $kick_before_tban) 
			{&msgUser("$user","You have now been kicked $kick_before_tban times . You have been T-Banned !");
			$ACTION = "T-Banned";}}
		
}

sub checkClones(){
	my($newuser) = @_;
	if (&getConfigOption("clone_check")){
# userIsOnline
		$newip = odch::get_ip($newuser);
		my ($usersonline) = odch::get_user_list(); #Get space separated list of who is online
		my ($numonlineusers) = odch::count_users(); #And how many
		@userlist=split(/\ /,$usersonline);
		my ($checkUserCount) = 0;
		while ($checkUserCount != $numonlineusers)
			{$onlineuser=$userlist[$checkUserCount];
			my($onlinetype) = odch::get_type($onlineuser);
			$onlineip = odch::get_ip($onlineuser);
			#If the ip of user joining is the same ip as an op then ignore
			if (($onlinetype ne 0) && ($onlinetype < 8))
				{if(($newip eq $onlineip) && ($newuser ne $onlineuser))
					{&msgAll("$newuser($newip) is a clone of $onlineuser($onlineip)");
					$REASON = "Clone";
					$ACTION = "Kicked"}}
			$checkUserCount ++;}}	
}

sub nickFilter(){
	my($user)=@_;
	#get user ip
	$userip = odch::get_ip($user);
	#Parital match of nick in
	&msgUser("");
}
## Required in every module ##
1;
