#############################################################################################
# 	module name 	userManagement.pm
#
#	Author		Nutter
#
#	Summary		Use DB handling
#
#	Description	A module containing all or most functions for manipulating the userDB
#			functions used elswhere
#
#	http://sqlbot/berlios.de
#	http://nutter.kicks-ass.net:35600/
#	http://axljab.homelinux.org:8080/			
#
#
##############################################################################################

# Is this userOnline now ?
sub userIsOnline(){
	my($user,$ip) = @_;

	my($sqluser) = &sqlConvertNick($user);
	my($value) = $dbh->selectrow_array("SELECT COUNT(nick) FROM userDB 
				WHERE nick='$sqluser' AND status='Online' ");
	if($value eq 1)
	{return 1;}
	return 0;
}
# Is there a match for this user ?
sub userInDB(){
	my($user,$ip) = @_;
	my($sqluser) = &sqlConvertNick($user);
	my($value) = $dbh->selectrow_array("SELECT COUNT(nick) FROM userDB WHERE nick='$sqluser' ");	
	if($value eq 1) 
		{my($value1) = $dbh->selectrow_array("SELECT COUNT(nick) FROM userDB 
				WHERE nick='$sqluser' AND allowStatus='allow'");
		if($value1 eq 1) 
			{return 2;}
		else 
			{return 1;}}
	return 0;
}
# Create a new record with some defaults
sub createNewUserRecord(){
	my($user) = @_;
	&setTime();
	my($sqluser) = &sqlConvertNick($user);	
	my($dtime)="$date $time";
	$connection = &getConnection($conn);
	$dbh->do("INSERT INTO userDB VALUES ('','$sqluser','not set','0','$utype','$type','Normal','off',' ',	
		'$fullDescription','$dcClient','$dcVersion','$NSlots','$NbHubs','$UploadLimit','$connection','$connectionMode','$country',
		'$ip','$hostname','$dtime','0','$dtime','0','1','0','0','0','0','0','0','0','$shareBytes','$shareBytes',' ',' ')");
}

sub updateUserRecordRecheck(){
	my($user,$ip) = @_;
	$connection = &getConnection($conn);
	my($sqluser) = &sqlConvertNick($user);

	$dbh->do("UPDATE userDB SET nick='$sqluser',slots='$NSlots',hubs='$NbHubs',limiter='$UploadLimit'
			,fullDescription='$fullDescription',shareByte='$shareBytes',
			status='Online'	WHERE nick='$sqluser'");

}

# User record exists so update the details
sub updateUserRecord(){
	my($user) = @_;
	&setTime();
	my($inTime)="$date $time";
	my($sqluser) = &sqlConvertNick($user);
	my($uurth) = $dbh->prepare("SELECT loginCount FROM userDB 
			WHERE nick='$sqluser' ");
	$uurth->execute();
	my($ref) = $uurth->fetchrow_hashref();
	my($loginCount) = "$ref->{'loginCount'}";
	$loginCount++;
	$uurth->finish();
	my($connection) = &getConnection($conn);
	my($userInDB) = &userInDB($user,$ip);
	if($userInDB eq 2)
	{
		$dbh->do("UPDATE userDB SET nick='$sqluser',utype='$utype',dcClient='$dcClient',
					dcVersion='$dcVersion',slots='$NSlots',hubs='$NbHubs',
					limiter='$UploadLimit',connection='$connection',
					connectionMode='$connectionMode',country='$country',
					hostname='$hostname',IP='$ip',inTime='$inTime',
					avShareBytes='$shareBytes',loginCount='$loginCount',
					fullDescription='$fullDescription',shareByte='$shareBytes',
					IP='$ip'
					WHERE nick='$sqluser' OR IP='$ip'");
	}
	else
	{
		$dbh->do("UPDATE userDB SET nick='$sqluser',utype='$utype',dcClient='$dcClient',
					dcVersion='$dcVersion',slots='$NSlots',hubs='$NbHubs',
					limiter='$UploadLimit',connection='$connection',
					connectionMode='$connectionMode',country='$country',
					hostname='$hostname',IP='$ip',inTime='$inTime',
					avShareBytes='$shareBytes',loginCount='$loginCount',
					fullDescription='$fullDescription',shareByte='$shareBytes',
					IP='$ip'
					WHERE nick='$sqluser'");
	}

}

# Check the allow status of this user
sub userStatus(){
	my($user)=@_;
	# Return the status (nBan,tBan,pBan or Allow) of this user.

}
# This user has logged off, so mark Offline
sub userOffline(){
	my($user) = @_;
	&setTime();
	my($sqluser) = &sqlConvertNick($user);
	my($uoth) = $dbh->prepare("SELECT inTime,onlineTime FROM userDB WHERE nick='$sqluser' ");
	$uoth->execute();
	my($ref) = $uoth->fetchrow_hashref();

	my($outTime)="$date $time";
	my($inTime) = "$ref->{'inTime'}";
	my($onlineTime) = "$ref->{'onlineTime'}";
#	my($totOnlineTime) = &calcOnlineTime($outTime,$inTime,$onlineTime);
	$uoth->finish();

	$dbh->do("UPDATE userDB SET 	status='Offline',
					onlineTime='$totOnlineTime',
					outTime='$outTime'
					WHERE nick='$sqluser'");
}

sub userOnline(){
	my($user) = @_;
	my($online) ="Online";
	&setTime();
	my($sqluser) = &sqlConvertNick($user);
	$dbh->do("UPDATE userDB SET status='$online',
					inTime='$date $time'
					WHERE nick='$sqluser' ");
}


# User has spoken increment line count
###
sub incLineCount(){
	my($user)=@_;
	my($sqluser) = &sqlConvertNick($user);
	my($ilcth) = $dbh->prepare("SELECT lineCount FROM userDB WHERE nick='$sqluser'");
	$ilcth->execute();
	my($ref) = $ilcth->fetchrow_hashref();

	my($lineCount) = "$ref->{'lineCount'}";
	$ilcth->finish();
	$lineCount=$lineCount+1;

	$dbh->do("UPDATE userDB SET lineCount='$lineCount' WHERE nick='$sqluser' ");
	
}
# Change the level of this user
# level - 2 =op-Admin, 1=Op, 0=RegUser 
sub setRegUser(){
	my($user,$setUser,$passwd,$level) = @_;	
	my($sqluser) = &sqlConvertNick($user);
	my($userLevel) = odch::check_if_registered($setUser);
	my($type)  = odch::get_type($user);
	my($ip) = odch::get_ip($setUser);

	if(($type ne 32) && ($level eq 2))
		{&msgUser($user,"You Cannot Change the level of an OpAdmin");
		return(1);}
	elsif(($userLevel ne 0))
		{# Remove this user from reg list
		odch::remove_reg_user($setUser);}

	odch::add_reg_user($setUser, $passwd, int($level));
	&msgUser($user,">>>> $setUser is now at level $level");
	&msgUser($setUser,">>>> $user has changed your level to $level your password is $passwd");
	# Change this user type in the userDB
	my($utype)  = odch::get_type($setUser);
	$dbh->do("UPDATE userDB SET type='$utype',passwd='$passwd' WHERE nick='$sqluser'");
	&addToLog($setUser,"Add/Edit User",$user);	
	
}
sub userWorker(){
	my($bwth) = $dbh->prepare("SELECT function,nick,information,IP FROM botWorker WHERE function LIKE '3%'");
	$bwth->execute();
	while ($ref = $bwth->fetchrow_hashref()){
		my($function) = "$ref->{'function'}";
		my($user) = "$ref->{'nick'}";
		my($ip) = "$ref->{'IP'}";
		my($information) = "$ref->{'information'}";
		&debug("($function)$user($ip)$information");
		if ($function=='30'){
			&setRegUser('Admin',$user,$information,2);}		
		if ($function=='31'){
			&setRegUser('Admin',$user,$information,1);}
		elsif($function=='32'){
			&setRegUser('Admin',$user,$information,0);}
		elsif($function=='33'){
			&delRegUser($user);}
		$dbh->do("DELETE FROM botWorker WHERE function LIKE '3%' ");}
	$bwth->finish();
}
sub aUserWorker(){
	my($auwth) = $dbh->prepare("SELECT function,nick,information,IP FROM botWorker WHERE function LIKE '5%'");
	$auwth->execute();
	while ($ref = $auwth->fetchrow_hashref()){
		my($function) = "$ref->{'function'}";
		my($user) = "$ref->{'nick'}";
		my($ip) = "$ref->{'IP'}";
		my($information) = "$ref->{'information'}";
		
		if ($function=='50'){
			my($sqluser) = &sqlConvertNick($user);
			$dbh->do("UPDATE userDB SET allowStatus='Allow'	WHERE (nick='$sqluser' OR IP='$ip') AND allowStatus!='Banned'");}	
		$dbh->do("DELETE FROM botWorker WHERE nick='$sqluser'");
	
	$auwth->finish();
}

}
sub chPassUser(){
	my($user,$oldPass,$newPass) = @_;	
	my($level) = odch::check_if_registered($setUser);
	my($sqluser) = &sqlConvertNick($user);
	if(($level eq 0)) # User is not registered
		{&msgUser("You are not registered.");
		return(1);}
	else
	{	
		my($value) = $dbh->do("SELECT passwd FROM userDB 
					WHERE nick='$sqluser' AND (passwd='$oldPass' OR passwd='not set')");
		if($value eq 1)
			{# Remove this user from reg list
			my($type)  = odch::get_type($user);
			odch::remove_reg_user($user);
			if($type == 32){$level = 2}
			elsif($type == 16){$level = 1}
			elsif($type == 8){$level = 0}

			odch::add_reg_user($user, $newPass, $level);
			# Change this user type in the userDB
			$dbh->do("UPDATE userDB SET type='$type',passwd='$newPass' 
					WHERE nick='$sqluser' AND allowStatus!='Banned'");
			&msgUser($user,"Your Password has now been set to $newPass");
			&addToLog($user,"Change Pass",$user);}
		else
			{&msgUser($user,"Incorrect Password");}
	}
}
sub delRegUser(){
	my($user) = @_;
	my($sqluser) = &sqlConvertNick($user);
	my($currentUserLevel) = odch::check_if_registered($user);
	if(($currentUserLevel eq 3))
		{&msgUser($user,"You cannot delete an OpAdmin");
		return(1);}
	else
		{# Remove this user from reg list
		odch::remove_reg_user($user);}
	&addToLog($user,"Del User",$user);
	$dbh->do("UPDATE userDB SET type='User',passwd='not set' WHERE nick='$sqluser' ");
}


sub userAway(){
	my($user,$data) = @_;
	my($tmp_ptr) = index($data,"+away");
	my($sqluser) = &sqlConvertNick($user);
	my($awayMsg) = substr($data, $tmp_ptr+5);
	chop($awayMsg);
	$dbh->do("UPDATE userDB SET awayMsg='$awayMsg',awayStatus='on' 
			WHERE nick='$sqluser'");
}
sub userBack(){
	my($user) = @_;
	my($sqluser) = &sqlConvertNick($user);
	my($value) = $dbh->do("SELECT awayStatus FROM userDB 
			WHERE awayStatus='on' AND nick='$sqluser'");
	if($value eq 1)
		{$dbh->do("UPDATE userDB SET awayStatus='off' WHERE nick='$sqluser'");
		&msgAll("$user returns after being away");}

}
## Required in every module ##
1;
