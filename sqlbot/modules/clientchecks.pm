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
	$type=""; $ip=""; $shared="";$GigsShared="";
	$tmpdata=""; $fulldescription=""; $dcClient="";
	$dcVersion=""; $NbHubs=""; $NSlots=""; $slt_ratio=""; $country="";
	$UploadLimit=""; $conn=""; $connection=""; $email="";

	$type = odch::get_type($user);
	$ip = odch::get_ip($user);
	$shared = odch::get_share($user);
	$GigsShared = int($shared / 1024 / 1024 / 10.24) / 100;
	my($tmpdata) = odch::get_description($user);
	$tmpdata =~ s/'//g;
	$fulldescription = "$tmpdata";
	my($pos1) = rindex($tmpdata, "<") +1;
	my($pos2) = rindex($tmpdata, ">");

	$verdata = substr($tmpdata, $pos1, $pos2 - $pos1);
	my(@verdata2) = split(/\ /, $verdata);

	$pos1 = rindex($tmpdata, "<$verdata2[0]") + 1 + length($verdata2[0]);
	$tmpdata = substr($tmpdata, $pos1, $pos2 - $pos1);
	my(@tmpdata2) = split(/,/, $tmpdata);

        $dcClient = $verdata2[0];
	@tmp0 = split(/:/, $tmpdata2[0]);
	@tmp1 = split(/:/, $tmpdata2[1]);
	@tmp2 = split(/:/, $tmpdata2[2]);
	@tmp3 = split(/:/, $tmpdata2[3]);
	@tmp4 = split(/:/, $tmpdata2[4]);
	$dcVersion = $tmp0[1];

	$tmpModeAP = $tmp1[1];
	if($tmpModeAP =~ /A/){$connection_type = "Active";}
	elsif($tmpModeAP =~ /P/){$connection_type = "Passive";}

	$NbHubs = $tmp2[1];
	## New DC ++ format support ##
	
	if($NbHubs =~ /\//) { ($a,$b,$c)=split(/\//,$NbHubs); $NbHubs=$a+$b+$c; }
	if($NbHubs == 0){$NbHubs++;}

	$NSlots = $tmp3[1];
	$UploadLimit = $tmp4[1];

	$conn = odch::get_connection($user);
	$connection = &getConnection($conn);
	$email = odch::get_email($user);

	## Check for internal networks ##
	if ($ip =~ /192.168.0/) { $ip = &getHubVar("external_ip"); }

	## Get Country code for user #
	my($reg) = IP::Country::Fast->new();
	$country = $reg->inet_atocc($ip);

	## Get user Type ##
	if($type =~ /8/){$user_type = "Registered";}
	elsif($type =~ /16/){$user_type = "Operator";}
	elsif($type =~ /32/){$user_type = "Op-Admin";}
	else {$user_type = "User";}

	## Slot Ratio ##
	if ($NSlots > 0){
		$slt_ratio = ($NSlots / $NbHubs) ;}
	else { $slt_ratio = 0;}

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
		# A check in case things go wrong on getting user information.
		# Ive seen this a few times dont know why yet.
#		if (lc($connection) eq lc("Error"))
#		{	&msgUser("nutter","FATAL ERROR - Unable to obtain user information from odch for $user");
#			return 1; #Abort any more checks
#		}

		&debug("ReChecking - [$user]");
		if (&usrOnline($user) ne 1)
			{if(lc($botname) ne lc($user)) #if not a bot, should be in the online table
				{&addToOnline($user);
				&debug("ReChecking - $user was not in online table");}}	
		else
			{&debug("ReChecking - updating $user,type = $type");
			&updateInOnline($user);
			if($type eq 0 )	
				{&delFromOnline($user);}
			if($type eq 32 )
			{ #If Opadmin
			# Check OP admins if set
			if (&getConfigOption("check_opadmin")) {
					&checkKicks($user);  # Check kick counter
					&processEvent($user);    # take action if any
				}
			}
			elsif($type eq 16) { # if Op
				# Check OPs if set
				if (&getConfigOption("check_op")) {
					&checkKicks($user);  # Check kick counter
					&processEvent($user);    # take action if any
				}
			}
			elsif($type eq 8) { # if Reg User
				if (&getConfigOption("check_reg")) 
				{
					&checkKicks($user);  # Check kick counter
					&processEvent($user);    # take action if any
				}
			}
			else {	# Normal user
				&checkKicks($user);  # Check kick counter
				&processEvent($user);    # take action if any
			}
		}
	}
	# Make sure the online sql table does not contain ghosts
	my ($cth) = $dbh->prepare("SELECT * FROM online");
	$cth->execute();
	while (my $ref = $cth->fetchrow_hashref()) {
		$user = "";
		$user = "$ref->{'name'}"; 
		$type = odch::get_type($user);
		if($type eq 0 )	{&delFromOnline($user);}
	}
	$cth->finish();
}


# Get all the info need from the client
sub parseClient(){
	my($user) = @_;
	
	&splitDescription($user);

	$REASON = "";
	$ACTION = "";
	
	## CHECK FAKERS ##
	if($shared =~ /(\d)\1{5,}/) {
		$REASON = "FAKER";
		$ACTION = "Nuked";
	}
	## CHECK MLDONKEY CLIENTS
	elsif($fulldescription =~ /mldonkey client/)
		{if (&getConfigOption("check_mldonkey"))
			{$REASON = "MLDonkey";
			$ACTION = "Nuked";}
	}
	else {
	## CHECK CLIENT ##
		if ((&getClientExists($dcClient)) && ($dcVersion ne "")){
			&debug("$user - $dcClient Client has Rules");
			my $sth = $dbh->prepare("SELECT * FROM client_rules WHERE client='$dcClient'");
			$sth->execute();
			my $ref = $sth->fetchrow_hashref();
			
			## MIN VERSION ##
			if ($ref->{'min_version'} > $dcVersion){
				$REASON = "Version";
				$ACTION = "Kicked";}
			## MIN SLOTS ##
			if ($ref->{'min_slots'} > 0 ){
				if ($NSlots < $ref->{'min_slots'}){
					$REASON = "Slots(min)";
					$ACTION = "Kicked";}}
			else{
				my($minslots) = &getConnectionSlots($conn,1);
				if ($NSlots < min_slots){
					$REASON = "Slots(min)";
					$ACTION = "Kicked";}
			}
			## MAX SLOTS ##
			if ($ref->{'max_slots'} > 0 ){
				if ($NSlots > $ref->{'max_slots'}){
					$REASON = "Slots(max)";
					$ACTION = "Kicked";}}
			else{
				my($maxslots) = &getConnectionSlots($conn,2);
				if ($NSlots > $maxslots){
					$REASON = "Slots(max)";
					$ACTION = "Kicked";}
			}
			## SLOT RATIO ##
			if ($ref->{'slot_ratio'} ne 0 ){
				if ($ref->{'slot_ratio'} > $slt_ratio){
					$REASON = "SlotRatio";
					$ACTION = "Kicked";}}
			## MAX HUBS ##
			if ($ref->{'max_hubs'} ne 0 ){
				if ($ref->{'max_hubs'} < $NbHubs){
					$REASON = "Hubs";
					$ACTION = "Kicked";}}
			## MIN CONNECTION ##
			if ($ref->{'min_connection'} > $conn){
				$REASON = "Connection";
				$ACTION = "Kicked";}
			## MIN SHARE ##
			if ($ref->{'min_share'} > $GigsShared){
				$REASON = "Share";
				$ACTION = "Kicked";}

			## Add new client checks here ##
			#Use the real clientname not its TAG
			$dcClientname = $ref->{'client_name'};
			$sth->finish();
		}
		else {
		## NO TAGS ##
			&debug("$user - Client is Untagged");
			if (&getConfigOption("kick_notags")){
				$REASON = "NoTags";
				$ACTION = "Kicked";}
			# Make sure the user meets at least the following if untagged
			my $sth = $dbh->prepare("SELECT * FROM client_rules WHERE client='++'");
			$sth->execute();
			my $ref = $sth->fetchrow_hashref();

			## MIN SHARE ##
			if ($ref->{'min_share'} > $GigsShared){
				$REASON = "Share";
				$ACTION = "Kicked";}
			## MIN CONNECTION ##
			elsif ($ref->{'min_connection'} > $conn){
				$REASON = "Connection";
				$ACTION = "Kicked";}
			$dcClient = "";
			$dcClientname = "";
			$dcVersion = "";
			$sth->finish();}
		
	## END ##
	}
	&debug("$user - parseClient.client=$dcClient,action=$ACTION,reason=$REASON");
	&debug("$user - parseClient.description=$fulldescription");
	&debug("$user - parseClient.done");
	return 1;	
}

# Check number of times user has been kicked in past 24hrs
sub checkKicks(){
	my($user) = @_;
	&setTime();
	&debug("$user - checks kicks");
	if (&getConfigOption("check_kicks"))
		{my($ip) = odch::get_ip($user);
		$countkicks = $dbh->selectrow_array("SELECT COUNT(*) FROM log WHERE ip = '$ip' && action = 'Kicked' && date = '$date'");
		my($maxkicks) = &getHubVar("kick_before_tban");
		if ($countkicks > $maxkicks) {
			&msgUser("$user","You have now been kicked more than $maxkicks times in the last day.You have been banned!");
			$REASON = "$maxkicks kicks";
			$ACTION = "Banned";}}
	&debug("$user - checks kicks.done");
}

sub checkClones(){
	my($newuser) = @_;
	if (&getConfigOption("clone_check")){
		$newip = odch::get_ip($newuser);
		my ($usersonline) = odch::get_user_list(); #Get space separated list of who is online
		my ($numonlineusers) = odch::count_users(); #And how many
		@userlist=split(/\ /,$usersonline);
		my ($checkUserCount) = 0;
		while ($checkUserCount != $numonlineusers)
			{$onlineuser=$userlist[$checkUserCount];
			my($onlinetype) = odch::get_type($onlineuser);
			$onlineip = odch::get_ip($onlineuser);
			# && ($onlinetype ne 0))
			if(($newip eq $onlineip) && ($newuser ne $onlineuser))
				{&msgUser("nutter","$newuser($newip) could be a clone of $onlineuser($onlineip)");
				$REASON = "Clone";
				$ACTION = "Kicked"}
			$checkUserCount ++;}
	}	
}
## Required in every module ##
1;
