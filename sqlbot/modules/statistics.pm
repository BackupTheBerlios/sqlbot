#############################################################################################
# 	module name 	statistics.pm
#
#	Author		Nutter, Axllent
#
#	Summary		Statistics processing
#
#	Description	This module contains all the subs that are called to handle gathering of
#			information about the hub. And subs they be called if new stats records are
#			to be checked or reached.
#
#
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org:8080/			
#
##############################################################################################
# Display channel stats in main channel
sub buildStats(){
	my($totShare) = $dbh->selectrow_array("SELECT sum(shareByte) from userDB WHERE status='Online'");
	my($totUsersOnline) = $dbh->selectrow_array("SELECT COUNT(*) from userDB WHERE status='Online'");
	my($totDiffUsers) = $dbh->selectrow_array("SELECT COUNT(*) from userDB");
	&totalUptime();

#	my($sth) = $dbh->prepare("SELECT * from userDB GROUP by country WHERE status='Online'");
#	$sth->execute();
#	my($tmpCountries) = "";
#	while (my $ref = $sth->fetchrow_hashref()){
#		$tmpCountries .= "\[$ref->{'country'} $ref->{'COUNT(*)'}] "; }
#	$sth->finish();
#	my($countries) =  "$tmpCountries";

	my ($bsth) = $dbh->prepare("SELECT client, COUNT(*) from userDB GROUP by dcClient WHERE status='Online'");
	$bsth->execute();
	my($tmpClient) = "";
	while (my $ref = $bsth->fetchrow_hashref()) {
		$tmpClient .= "$ref->{'client'} [$ref->{'COUNT(*)'}]\n\r"; }
	$bsth->finish();
	my($clients) = "$tmpClient";

	$bsth1 = $dbh->prepare("SELECT * FROM records");
	$bsth->execute();
	my($tmpRecords) = "";
	while (my $ref = $bsth->fetchrow_hashref()) {
		$tmpRecords .=  "Record $ref->{'recordName'} is $ref->{'recordValue'} Set on $ref->{'date'} at $ref->{'time'}\r";}
	$bsth->finish();

	my($records) = "$tmpRecords";
	my($webAddress) = &getHubVar("hub_website_address");
	$statsmsg = "Online stats:\r
Uptime: $days d $hours h $mins m\r
Users online : $totUsersOnline \r
Total Share  : $totShare, Average share of $avShareGigs per user.\r
Online Clients:\r
$clients \r
Users Online from Countries: $countries\r\r
Total Unique visitors: $totDiffUsers\r
Current Records :\r
$records\r
More detailed stats can be found at $webAddress|";

}

sub checkRecords(){
	&setTime();
	my($newRecord) = 1;
	my($currtotalshare) = $dbh->selectrow_array("SELECT sum(shareByte) FROM userDB WHERE status='Online'");
	my($currtotalusers) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB WHERE status='Online'");
	my($shareGB) = &roundToGB($currtotalshare);

	my $crth = $dbh->prepare("SELECT * FROM records WHERE recordName='share'");
	$crth->execute();
	my $ref = $crth->fetchrow_hashref();
	$record = $ref->{'recordValue'};
	$recordDate = $ref->{'date'};
	$recordTime = $ref->{'time'};
	$crth->finish();

	if ($record < $shareGB){
		if (&getVerboseOption("verbose_records")){
			&msgAll("New Share Record $shareGB GB.Last record $record GB on $recordDate:$recordTime\r");}

		$dbh->do("UPDATE records SET recordValue='$shareGB',date='$date',time='$time' WHERE recordName='share'");
		$newRecord = 0;
	}
	
	$crth1 = $dbh->prepare("SELECT * FROM records WHERE recordName='users'");
	$crth->execute();
	$ref = $crth->fetchrow_hashref();
	$record = $ref->{'recordValue'};
	$recordDate = $ref->{'date'};
	$recordTime = $ref->{'time'};

	$crth->finish();

	if ($record < $currtotalusers){
		if (&getVerboseOption("verbose_records"))
			{&msgAll("New User Record $currtotalusers.Last record $record on $recordDate:$recordTime.");}
		$dbh->do("UPDATE records SET recordValue='$currtotalusers',date='$date',time='$time' WHERE recordName='users'");
		$newRecord = 0;}

	return $newRecord;
}
sub topChat() {
	my $tcth = $dbh->prepare("SELECT nick,lineCount FROM userDB ORDER BY lineCount DESC LIMIT 0,10");
	$tcth->execute();
	$msg = "The Top 10 Chatters are :\r";
	$i=1;
	while (my $ref = $tcth->fetchrow_hashref()) {
		$msg .=  "$i-$ref->{'nick'}: $ref->{'lineCount'}  \r";
		$i++;}
	$tcth->finish();

}
#############################################################################################
# Work out the hub uptime #
sub totalUptime() {
	$uptime = odch::get_variable("hub_uptime");
	$days = int( $uptime / 60 / 60 / 24 );
	$hours = int(( $uptime / 60 / 60 ) % 24 );
	$mins = int(( $uptime / 60 ) % 60 );
}

## Required in every module ##
1;
