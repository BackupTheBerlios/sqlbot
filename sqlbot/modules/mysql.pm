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
sub getClientExists(){
	my($Client)=@_;
	my($value) = $dbh->selectrow_array("SELECT COUNT(*) FROM client_rules WHERE client='$Client'");
	if($value eq 1)
	{return 1;}
	return 0;
}

sub getConnectionSlots(){
	my($data,$mslot) = @_;
	my($value) = "";
	my($gcsth) = $dbh->prepare("SELECT * FROM connection_slots WHERE connection='$data'");
	$gcsth->execute();
	my($ref) = $gcsth->fetchrow_hashref();
	if ($mslot eq 1){
		$value = "$ref->{'min_slots'}";}
	else{
		$value = "$ref->{'max_slots'}";}
	$gcsth->finish();
	return $value;
}



## Required in every module ##
1;
