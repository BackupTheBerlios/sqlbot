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
	my($totalgigs) = $dbh->selectrow_array("SELECT sum(shared_gigs) from online");
	my($totalusers) = $dbh->selectrow_array("SELECT COUNT(*) from online");
	my($totDiffUsers) = $dbh->selectrow_array("SELECT COUNT(*) from user_stats");
	my($averagegigs) = int($totalgigs * 100 / $totalusers) / 100;
	&totalUptime();

	my($sth) = $dbh->prepare("select country, COUNT(*) from online GROUP by country");
	$sth->execute();
	my($tmp_countries) = "";
	while (my $ref = $sth->fetchrow_hashref()){
		$tmp_countries .= "\[$ref->{'country'}-$ref->{'COUNT(*)'}] "; }
	$sth->finish();
	my($countries) =  "$tmp_countries";

	my ($cth) = $dbh->prepare("select client, COUNT(*) from online GROUP by client");
	$cth->execute();
	my($tmp_client) = "";
	while (my $ref = $cth->fetchrow_hashref()) {
		$tmp_client .= "$ref->{'client'} [$ref->{'COUNT(*)'}]\n\r"; }
	$cth->finish();
	my($clients) = "$tmp_client";

	$rth = $dbh->prepare("SELECT * FROM records");
	$rth->execute();
	my($tmp_records) = "";
	while (my $ref = $rth->fetchrow_hashref()) {
		$tmp_records .=  "Record $ref->{'recordName'} is $ref->{'recordValue'} Set on $ref->{'date'} at $ref->{'time'}\n\r";}
	$rth->finish();
	my($records) = "$tmp_records";
	my($webAddress) = &getHubVar("hub_website_address");
	$statsmsg = "Online stats:\r
Uptime: $days d $hours h $mins m\r
Users online : $totalusers \r
Total Share  : $totalgigs Gigs, Average share of $averagegigs Gigs per user.\r
Online Clients:\r
$clients \r
Users Online from Countries: $countries\n\r
Total Unique visitors: $totDiffUsers\r
Current Records :\r
$records\r
More detailed stats can be found at $webAddress|";

}

sub checkRecords(){
	&setTime();
	my($newRecord) = 1;
	my($currtotalgigs) = $dbh->selectrow_array("SELECT sum(shared_gigs) from online");
	my($currtotalusers) = $dbh->selectrow_array("SELECT COUNT(*) from online");

	my $sth = $dbh->prepare("SELECT * FROM records WHERE recordName='share'");
	$sth->execute();
	my $ref = $sth->fetchrow_hashref();
	$record = $ref->{'recordValue'};
	$recordDate = $ref->{'date'};
	$recordTime = $ref->{'time'};
	$sth->finish();

	if ($record < $currtotalgigs)
	{
		$diff = $currtotalgigs-$record;
		if (&getVerboseOption("verbose_records")){
			&msgAll("A New share Record has been Set. The last record of $record GB was set on $recordDate : $recordTime. The new share of $currtotalgigs GB beats the previous record by $diff GB");}

		$dbh->do("UPDATE records SET recordValue='$currtotalgigs',date='$date',time='$long_time' WHERE recordName='share'");
		
		$newRecord = 0;
	}

	$sth = $dbh->prepare("SELECT * FROM records WHERE recordName='users'");
	$sth->execute();
	$ref = $sth->fetchrow_hashref();
	$record = $ref->{'recordValue'};
	$recordDate = $ref->{'date'};
	$recordTime = $ref->{'time'};

	$sth->finish();

	if ($record < $currtotalusers)
	{
		if (&getVerboseOption("verbose_records"))
			{&msgAll("A New User Record has been Set. The last record of $record was set on $recordDate : $recordTime");}
		$dbh->do("UPDATE records SET recordValue='$currtotalusers',date='$date',time='$long_time' WHERE recordName='users'");
		$newRecord = 0;
	}
	return $newRecord;
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
