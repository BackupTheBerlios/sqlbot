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
sub setTime() {
        use POSIX qw(strftime);
        $long_time = strftime "%H:%M:%S", localtime;
        $date = strftime "%Y-%m-%d", localtime;
}
sub round(){
    my($number) = shift;
    return sprintf("%.2f", $number)
}

sub debug() {
	my($debuguser) = &getHubVar("debug_user");
	my($debug)= &getHubVar("use_debug");
	if($debug){ #Set in sqlbot.pl
		my($msg) = @_;	
		odch::data_to_user($debuguser, "\$To: $debuguser From: $botname \$ $msg|");
	}
}

sub msgUser() {
	my($user,$msg) = @_;
	odch::data_to_user($user, "\$To: $user From: $botname \$ $msg |");
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
	&debug("$user - Version sent");
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


## Required in every module ##
1;
