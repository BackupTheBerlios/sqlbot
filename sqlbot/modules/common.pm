#############################################################################################
# 	module name 	common.pm
#
#	Author		Nutter, Axllent
#
#	Summary		Routines that are commom to most other modules
#
#	Description	Any other function(s) that dont fit into another module
#
#
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org:8080/
#
##############################################################################################
# Set Date a time globals

use Time::Local;

sub sqlConvertNick() {
	my($user) = @_;
	my $cleanUser = $dbh->quote($user);
	$cleanUser =~ s/^(')//;
	$cleanUser =~ s/(')$//;
	return($cleanUser);
}

sub setTime() {
        use POSIX qw(strftime);
        $time = strftime "%H:%M:%S", localtime;
        $date = strftime "%Y-%m-%d", localtime;

}

sub roundToGB(){
	my($number)=@_;
	$number = $number / 1024 / 1024 / 1024;
	$number =~s/([0-9]+)\.([0-9][0-9])[0-9]+/$1\.$2/g;	
	return $number;
}

sub debug() {
	my($debuguser) = &getHubVar("debug_user");
	my($debug)= &getHubVar("use_debug");
	if($debug){ #Set in sqlbot.pl
		my($msg) = @_;	
		odch::data_to_user($debuguser, "\$To: $debuguser From: $botname \$$msg|");
	}
}

sub msgUser() {
	my($user,$msg) = @_;
	odch::data_to_user($user, "\$To: $user From: $botname \$$msg |");
}

sub msgOPs() {
	my($sender,$msg) = @_;
	my $sth = $dbh->prepare("SELECT nick FROM userDB WHERE (uType='Operator' OR uType='Op-Admin')");
	$sth->execute();
	while ($ref = $sth->fetchrow_hashref()){
		$op= "$ref->{'nick'}";
		if (lc($op) ne lc($sender)){ # Dont echo
			odch::data_to_user($op, "\$To: $op From: $botname \$$msg |");}
			}
	$sth->finish();
}

sub msgAll() {
	my($msg) = @_;
	odch::data_to_all("<$botname> $msg |");
}

# Bot version Request
sub version()
{
	&msgAll("Im running version $botVersion of sqlBot");
	&msgAll("     Available from http://sqlbot.berlios.de");
}
sub getConnection(){
	my($conn) =@_;
	if($conn =~ m/1/){return "28.8Kbps";}
	elsif($conn =~ m/2/){return "33.6Kbps";}
	elsif($conn =~ m/3/){return "56Kbps";}
	elsif($conn =~ m/4/){return "Satellite";}
	elsif($conn =~ m/5/){return "ISDN";}
	elsif($conn =~ m/6/){return "DSL";}
	elsif($conn =~ m/7/){return "Cable";}
	elsif($conn =~ m/8/){return "LAN(T1)";}
	elsif($conn =~ m/9/){return "LAN(T3)";}
	return "Error";
}

sub getConfigOption(){
	my($data) = @_;
	my($value) = $dbh->do("SELECT value FROM hub_config WHERE rule='$data' AND value='on'");
	if($value eq 1)
	{return 1;}
	return 0;
}

sub getVerboseOption(){
	my($data) = @_;
	my($value) = $dbh->do("SELECT value FROM verbosity WHERE rule='$data' AND value='on'");
	if($value eq 1)
	{return 1;}
	return 0;
}

sub getLogOption() {
	my($data) = @_;
	my($value) = $dbh->do("SELECT value FROM log_config WHERE rule='$data' AND value='on'");
	if($value eq 1)
	{return 1;}
	return 0;
}
sub getHubVar() {
	my($data) = @_;
	my($sth) = $dbh->prepare("SELECT value FROM hub_variables WHERE rule='$data'");
	$sth->execute();
	my($ref) = $sth->fetchrow_hashref();
	$value = "$ref->{'value'}";
	$sth->finish();
	return $value;
}
sub addToLog(){
	my($user,$ACTION,$REASON) = @_;
	&setTime();
	my($dtime)="$date $time";
	my($sqluser) = &sqlConvertNick($user);
	$dbh->do("INSERT INTO hubLog VALUES ('mysql_insertid','$sqluser','$dtime','$ACTION','$REASON')");
}

sub calcOnlineTime(){
	my($outTime,$inTime,$onlineSeconds) = @_;
	
	my($inTimeDate, $inTimeTime) = split /\ /, $inTime;
	($inTimeYear, $inTimeMon, $inTimeDays) = $inTimeDate =~ /(\d+)-(\d+)-(\d+)/;
	($inTimeHour, $inTimeMin, $inTimeSecs) = $inTimeTime =~ /(\d+):(\d+):(\d+)/;
	$inTimeEpochSeconds = timelocal($inTimeSecs,$inTimeMin,$inTimeHour,$inTimeDays,$inTimeMon,$inTimeYear);

	my($outTimeDate, $outTimeTime) = split /\ /, $outTime;
	($outTimeYear, $outTimeMon, $outTimeDays) = $outTimeDate =~ /(\d+)-(\d+)-(\d+)/;
	($outTimeHour, $outTimeMin, $outTimeSecs) = $outTimeTime =~ /(\d+):(\d+):(\d+)/;
	$outTimeEpochSeconds = timelocal($outTimeSecs,$outTimeMin,$outTimeHour,$outTimeDays,$outTimeMon,$outTimeYear);

	$differenceEpochSeconds = $outTimeEpochSeconds - $inTimeEpochSeconds;

	if($differenceEpochSeconds eq 0){return("0");}
	$onlineSeconds = $onlineSeconds + $differenceEpochSeconds;

return("$onlineSeconds");
}
## Required in every module ##
1;
