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
	
	my($avShareGigs) = &roundToGB($totShare/$totUsersOnline);
	$totShare = &roundToGB($totShare);
	
	my($stth) = $dbh->prepare("select country, COUNT(*) from userDB WHERE status='Online' GROUP by country");
	$stth->execute();
	my($tmpCountries) = "";
	while ($ref = $stth->fetchrow_hashref()){
		$tmpCountries .= "\[$ref->{'country'} $ref->{'COUNT(*)'}] "; }
	$stth->finish();
	my($countries) =  "$tmpCountries";
	my($stth1) = $dbh->prepare("SELECT dcClient, COUNT(*) from userDB WHERE status='Online' GROUP by dcClient");
	$stth1->execute();
	my($tmpClient) = "";
	while ($ref1 = $stth1->fetchrow_hashref()) {
		$tmpClient .= "$ref1->{'dcClient'} [$ref1->{'COUNT(*)'}]\n\r"; }
	$stth1->finish();
	my($clients) = "$tmpClient";
	my($stth2) = $dbh->prepare("SELECT * FROM records");
	$stth2->execute();
	my($tmpRecords) = "";
	while ($ref2 = $stth2->fetchrow_hashref()) {
		$tmpRecords .=  "Record $ref2->{'recordName'} is $ref2->{'recordValue'} Set on $ref2->{'date'} at $ref2->{'time'}\n\r";}
	$stth2->finish();
	my($records) = "$tmpRecords";
	my($webAddress) = &getHubVar("hub_website_address");
	

	$statsmsg = "Online stats:\r
Uptime: $days d $hours h $mins m\r
Users online : $totUsersOnline \r
Users Online from Countries: $countries\n\rAverage share of $avShareGigs per user.\r
Online Clients:\r
$clients \r

Total Share  : $totShare, 
Total Unique visitors: $totDiffUsers\r
Current Records :\r
$records\r
More detailed stats can be found at $webAddress| ";


}

sub checkRecords(){
	&setTime();
	my($newRecord) = 1;
	my($currtotalshare) = $dbh->selectrow_array("SELECT sum(shareByte) FROM userDB WHERE status='Online'");
	my($currtotalusers) = $dbh->selectrow_array("SELECT COUNT(*) FROM userDB WHERE status='Online'");
	my($shareGB) = &roundToGB($currtotalshare);

	my($crth) = $dbh->prepare("SELECT * FROM records WHERE recordName='share'");
	$crth->execute();
	my($ref) = $crth->fetchrow_hashref();
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
	
	my($crth1) = $dbh->prepare("SELECT * FROM records WHERE recordName='users'");
	$crth1->execute();
	my($ref1) = $crth1->fetchrow_hashref();
	$record = $ref1->{'recordValue'};
	$recordDate = $ref1->{'date'};
	$recordTime = $ref1->{'time'};

	$crth1->finish();

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
	my($i)=1;
	while ($ref = $tcth->fetchrow_hashref()) {
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
