#############################################################################################
# 	module name 	mysql.pm
#
#	Author		Nutter, Axllent
#
#	Summary		Sql handling
#
#	Description	A module containing all or most commonly used SQL functions, Specilised
#			functions used elswhere
#
#
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org:8080/			
#
##############################################################################################
sub sqlConnect(){
	$dbh = DBI->connect("DBI:mysql:odch:$sql_server","$sql_username","$sql_password",{ RaiseError => 1, AutoCommit => 0 });
	$dbh->do("SET OPTION SQL_BIG_TABLES = 1");
}

sub sqlDisconnect(){
	 $dbh->disconnect;        # Disconnect from the data source.	
}

sub addToLog(){
	my($user,$ACTION,$REASON) = @_;
	&debug("$user - add to log");
	&debug("$user - action=$ACTION,reason=$REASON");
	&setTime();
	$dbh->do("INSERT INTO log VALUES ('mysql_insertid','$date','$long_time','$ACTION','$REASON',
		'$user','$ip','$country','$dcClientname','$dcVersion','$fulldescription',
		'$connection','$NbHubs','$NSlots','$GigsShared')");
	&debug("$user - add to log.done");
}

sub addToOnline(){
	my($user) = @_;
	&debug("$user - add to online.");
	&setTime();
	
	$dbh->do("INSERT INTO online VALUES ('mysql_insertid','$date','$long_time','$user','$user_type','$ip',
		'$country','$dcClientname','$dcVersion','$fulldescription','$connection','$connection_type','$NbHubs','$NSlots','$shared',
		'$GigsShared','$email')");
	&debug("$user - add to online.done");
}

sub delFromOnline(){
	my($user) = @_;
	&debug("$user - del from online.");
	&setTime();
	## remove from MySQL online log ##
	$dbh->do("DELETE FROM online WHERE name='$user'");
	&debug("$user - del from online.done");
}

sub updateInOnline(){
	my($user) = @_;
	&debug("$user - update in online.");
	&setTime();
	$dbh->do("UPDATE online SET connected_hubs='$NbHubs',upload_slots='$NSlots',shared_bytes='$shared',client='$dcClientname',client_version='$dcVersion',fulldescription='$fulldescription',shared_gigs='$GigsShared' WHERE name='$user'");
	&debug("$user - update in online.done");
}

sub addToFakers(){
	my($user) = @_;
	&setTime();
	$dbh->do("INSERT INTO fakers VALUES ('mysql_insertid','$date','$long_time','$user',
		'$ip','$country','$dcClientname','$dcVersion','$fulldescription','$NbHubs','$NSlots','$shared','$GigsShared')");

}
sub addToKick(){
	my($user,$REASON) = @_;
	$ip = odch::get_ip($user);
	&debug("$user - KICK - $user $ip $REASON");
	$dbh->do("INSERT INTO kick VALUES ('mysql_insertid','$user','$ip','$REASON')");
	#Start the one shot timer for kicks.
	if ($alarmSet ne 1) #Set only if already set
	{
		$delayedKickTime = &getHubVar("delayed_kick_time");
		alarm($delayedKickTime);
		$alarmSet = 1;
	}
	&debug("$user - add to kick.done");
}

sub delFromKick(){
	$dbh->do("DELETE FROM kick ");
	&debug("$user - del from kick.done");
}

sub updateInStats(){
	my($user,$mode) = @_;
	&debug("$user - update stats");
	&setTime();
	my($exists) = $dbh->selectrow_array("SELECT COUNT(*) FROM user_stats WHERE name='$user'");
	if ($exists eq 1){
		my $sth = $dbh->prepare("SELECT * FROM user_stats WHERE name = '$user'");
		$sth->execute();
		my $ref = $sth->fetchrow_hashref();

		my($rowID) = "$ref->{'rowID'}";
		my($first_date) = "$ref->{'first_date'}";
		my($first_time) = "$ref->{'first_time'}";
		my($total_logins) = "$ref->{'total_logins'}";
		if($mode){$total_logins = int($total_logins + 1);} #Only increment on login
		my($old_average_shared_gigs) = "$ref->{'average_shared_gigs'}";
		my($average_shared_gigs) = int(((($old_average_shared_gigs * $old_total_logins)
					+ $GigsShared) / $total_logins) * 100) / 100;
		$sth->finish();

		$dbh->do("UPDATE user_stats SET total_logins='$total_logins',
		average_shared_gigs='$average_shared_gigs',last_ip='$ip', 
		last_date='$date',last_time='$long_time' WHERE rowID='$rowID'");
	}
	else{
		$dbh->do("INSERT INTO user_stats VALUES ('mysql_insertid','$date','$long_time','$user','$ip',
		'1','$GigsShared','$date','$long_time')");
	}
	&debug("$user - updates stats.done");
}
sub getClientExists(){
	my($Client)=@_;
	$value = $dbh->selectrow_array("SELECT COUNT(*) FROM client_rules WHERE client='$Client'");
	if($value eq 1)
	{return 1;}
	return 0;
}
sub getConfigOption(){
	my($data) = @_;
	my($value) = $dbh->do("SELECT value FROM hub_config WHERE rule='$data' && value='on'");
	if($value eq 1)
	{return 1;}
	return 0;
}

sub getConnectionSlots(){
	my($data,$mslot) = @_;
	my $sth = $dbh->prepare("SELECT * FROM connection_slots WHERE connection='$data'");
	$sth->execute();
	my $ref = $sth->fetchrow_hashref();
	if ($mslot eq 1){
		$value = "$ref->{'min_slots'}";}
	else{
		$value = "$ref->{'max_slots'}";}
	$sth->finish();
	return $value;
}

sub getVerboseOption(){
	my($data) = @_;
	my($value) = $dbh->do("SELECT value FROM verbosity WHERE rule='$data' && value='on'");
	if($value eq 1)
	{return 1;}
	return 0;
}

sub getLogOption() {
	my($data) = @_;
	my($value) = $dbh->do("SELECT value FROM log_config WHERE rule='$data' && value='on'");
	if($value eq 1)
	{return 1;}
	return 0;
}
sub getHubVar() {
	my($data) = @_;
	my $sth = $dbh->prepare("SELECT * FROM hub_variables WHERE rule='$data'");
	$sth->execute();
	my $ref = $sth->fetchrow_hashref();
	$value = "$ref->{'value'}";
	$sth->finish();
	return $value;
}
sub usrOnline(){
	my($user) = @_;
	my($value) = $dbh->selectrow_array("SELECT COUNT(*) FROM online WHERE name='$user'");
	if($value eq 1)
	{return 1;}
	return 0;
}

sub setVerbosity(){

}
## Required in every module ##
1;
