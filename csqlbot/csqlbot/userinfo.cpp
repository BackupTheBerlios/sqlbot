/***************************************************************************
                          userinfo.cpp  -  description
                             -------------------
    begin                : Wed Oct 8 2003
    copyright            : (C) 2003 by Steve Gray
    email                :
 ***************************************************************************/

/***************************************************************************
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/


#include <iostream>
#include "userinfo.h"
#include "globalconf.h"

using std::cout;
using std::endl;

UserInfo::UserInfo(int hubID,MySqlCon * mySql,CString nick){

     MySql = mySql;
     uiNick = nick;
     uiHubID = hubID;
     
     //Escape some strings for mysql
     if ( (uiNick.Find("\'") != -1) ||
          (uiNick.Find("\\") != -1))
     {
          uiEscNick = uiNick.Replace("\\","\\\\");
          uiEscNick = uiEscNick.Replace("\'","\\'");
     }
     else
     {
          uiEscNick = uiNick;
     }

     m_eUserClientVersion = eucvNONE;
     
     uiIp = "";
     uiHost = "Not known";
     uiStatus = euisOnline;
     uiIsAway = euiiaOnline;
     uiLastSeenTime = timeHand.getDate() + timeHand.getTime();   // Raw time now
     uiShare = 0;
     uiClient = "NOTAG";
     uiTag = "Not known";
     uiDescription = "Not Set";
     uiHubs = 0;
     uiHubsOp = 0;
     uiHubsReg = 0;
     uiHubsNorm = 0;
     uiSlots = 0;
     uiLimiter = 0;
     uiSpeed = "Not known";
     uiCountry = "";
     uiIsAdmin = 0;
     uiUserLevel = euiUser;
     uiPassword = "";
     uiFirstSeenTime = timeHand.getDate() + timeHand.getTime();  // Raw time now
     uiTimeOnline = 0;         // Seconds
     uiTotalSearches = 0;
     uiKickTotal = 0;
     uiBanTotal = 0;
     uiSayTotal = 0;
     uiShareChckd = 0;
     uiShareChckdStart = ""; // The time we start the share check. i.e NOW
     uiShareChckdExpire = ""; // The time above + exirpe time
     uiBanFlag = 0;
     uiBanTime = "";     //time
     uiBanExpire = "";   //time
     uiLoginCount = 0;

     sdclibUptime = time(0);

     sqlDataUptoDate = 0;
     sqlUserExists = 0;
}
UserInfo::~UserInfo(){
}
/** WARNING: This is intensive function */
void UserInfo::CalculateCountry(CString dottedIP)
{
#if COUNTRY_IP_LOOKUP
     if(dottedIP.IsEmpty()){return;}
     if(!(uiCountry.IsEmpty())){return;}
     
     unsigned int IPNumber;
     CString code2;
     int A,B,C,D;
     int a1=dottedIP.Find('.');
     int b1=dottedIP.Find('.',a1+1);
     int c1=dottedIP.Find('.',b1+1);
               
     A=CString(dottedIP.Mid(0,a1)).asINT();
     B=CString(dottedIP.Mid(a1+1,b1-a1)).asINT();
     C=CString(dottedIP.Mid(b1+1,c1-b1)).asINT();
     D=CString(dottedIP.Mid(c1+1,dottedIP.Length()-c1-1)).asINT();

     IPNumber = A*(256*256*256) + B*(256*256) + C*256 + D;

     MYSQL_RES *result = MySql->Query(0,"country_code2,country_name","iptoc",
          "IP_FROM<='" + CString().setNum(IPNumber) +
          "' AND IP_TO>='" + CString().setNum(IPNumber) + "'");

     MYSQL_ROW row = MySql->FetchResult(result);
     while(row)
     {
          uiCountry = row[0];
          uiCountryFull = row[1];
          row = MySql->FetchResult(result);
     }
     MySql->FreeRes(result);
#endif
}
int UserInfo::ReadUserInfo(void)
{
     MYSQL_RES * result = MySql->Query(uiHubID,"*","userInfo","uiNick='"+uiEscNick+"'");
     if (MYSQL_ROW row = MySql->FetchResult(result))
     {
          int i =0;
/*          uiNick       = row[i]; */                  i++;
          uiIp           = row[i];                     i++;
          uiHost         = row[i];                     i++;
/*          uiIsAway     = CString(row[i]).asINT(); */ i++;
          uiStatus       = euisOnline;                 i++;
/*          hubID        = row[i]; */                  i++;
          uiCountry      = row[i];                     i++;
          uiIsAdmin      = CString(row[i]).asINT();    i++;
          uiUserLevel    = (euiUserLevel)CString(row[i]).asINT();    i++;          
          uiPassword     = row[i];                     i++;
/*          uiShare      = row[i]; */                  i++;
/*          uiTag        = row[i]; */                  i++;
/*          uiClient     = row[i]; */                  i++;
/*          uiDescription = row[i]; */                 i++;
/*          uiVersion    = row[i]; */                  i++;
/*          uiMode       = row[i]; */                  i++;
/*          uiHubs       = CString(row[i]).asINT(); */ i++;
/*          uiHubsOp     = CString(row[i]).asINT(); */ i++;
/*          uiHubsReg    = CString(row[i]).asINT(); */ i++;
/*          uiHubsNorm   = CString(row[i]).asINT(); */ i++;
/*          uiSlots      = CString(row[i]).asINT(); */ i++;
/*          uiLimiter    = CString(row[i]).asINT(); */ i++;
/*          uiSpeed      = row[i]; */                  i++; 
          uiFirstSeenTime = row[i];                    i++;
          uiLastSeenTime = row[i];                     i++;
          uiTimeOnline   = CString(row[i]).asINT();    i++;
          uiTotalSearches = CString(row[i]).asINT();   i++;
          uiKickTotal    = CString(row[i]).asINT();    i++;
          uiBanTotal     = CString(row[i]).asINT();    i++;
          uiSayTotal     = CString(row[i]).asINT();    i++;
          uiShareChckd   = row[i];                     i++;
          uiShareChckdStart = row[i];                  i++;
          uiShareChckdExpire = row[i];                 i++;
          uiBanFlag      = CString(row[i]).asINT();    i++;
          uiBanTime      = row[i];                     i++;
          uiBanExpire    = row[i];                     i++;
          uiLoginCount   = CString(row[i]).asINT();    i++;

          // Reset the ban flag if genuine temp ban
          if(uiBanFlag != euibfPLBan)
          {
               uiBanFlag = euibfNone;
          }

          sqlUserExists =1;
     }
     else
     {
          sqlUserExists=0;
     }
     //Free resources from Query
     MySql->FreeRes(result);
     
     IncLoginCount();

     return(sqlUserExists);
}
void UserInfo::WriteUserInfo(void){
     int i = time(0)-Uptime(); //This session
     i+=uiTimeOnline;
     uiTimeOnline = i;
     
     if(sqlDataUptoDate) {return;} //Must reset this to write into Sql
     
     	CString uiEscDescription;     
	
	//Escape some strings for mysql
	uiEscDescription = uiDescription;

	if (uiEscDescription.Find("\\") != -1)
		uiEscDescription = uiDescription.Replace("\\","\\\\");
	if (uiEscDescription.Find("\'") != -1)
		uiEscDescription = uiDescription.Replace("\'","\\'");
     
     if(sqlUserExists)
     {
          MySql->Update(uiHubID,"userInfo",
//               "uiIp='" + uiIp +
               "uiHost='" + uiHost +
               "',uiStatus='" + CString().setNum(uiStatus) +
               "',uiIsAway='" + CString().setNum(uiIsAway) +               
//             "',uiHubID='" + uiHubID +
               "',uiCountry='" + uiCountry +
               "',uiIsAdmin='" + CString().setNum(uiIsAdmin) +
//               "',uiUserLevel='" + CString().setNum(uiUserLevel) +               
//             "',uiPassword='" + uiPassword +
//               "',uiShare='" + CString().setNum(uiShare) +
               "',uiTag='" + uiTag +
               "',uiClient='" + uiClient +
               "',uiDescription='" + uiEscDescription +
               "',uiVersion='" + m_ClientVersion.m_sVersionString +
               "',uiMode='" + uiMode +
               "',uiHubs='" + CString().setNum(uiHubs) +
               "',uiHubsOp='" + CString().setNum(uiHubsOp) +
               "',uiHubsReg='" + CString().setNum(uiHubsReg) +
               "',uiHubsNorm='" + CString().setNum(uiHubsNorm) +
               "',uiSlots='" + CString().setNum(uiSlots) +
               "',uiLimiter='" + CString().setNum(uiLimiter) +
               "',uiSpeed='" + uiSpeed +
               "',uiFirstSeenTime='" + uiFirstSeenTime +
               "',uiLastSeenTime='" + timeHand.getDate() + timeHand.getTime() +
               "',uiTimeOnline='" + CString().setNum(uiTimeOnline) +
               "',uiTotalSearches='" + CString().setNum(uiTotalSearches) +
               "',uiKickTotal='" + CString().setNum(uiKickTotal) +
               "',uiBanTotal='" + CString().setNum(uiBanTotal) +
               "',uiSayTotal='" + CString().setNum(uiSayTotal) +
               "',uiShareChckd='" + CString().setNum(uiShareChckd) +
               "',uiShareChckdStart='" + uiShareChckdStart +
               "',uiShareChckdExpire='" + uiShareChckdExpire +
               "',uiBanFlag='" + CString().setNum(uiBanFlag) +
               "',uiBanTime='" + uiBanTime +
               "',uiBanExpire='" + uiBanExpire +
               "',uiLoginCount='" + CString().setNum(uiLoginCount) +
               "'"
               ,"uiNick='" + uiEscNick + "'");
     }
     else   //Create new Record
     {
          MySql->Insert("userInfo",
//               "uiIp='" + uiIp +
               "uiNick='" + uiEscNick + 
               "',uiHost='" + uiHost +
               "',uiStatus='" + CString().setNum(uiStatus) +
               "',uiIsAway='" + CString().setNum(uiIsAway) +
               "',hubID='" + CString().setNum(uiHubID) +
               "',uiCountry='" + uiCountry +
               "',uiIsAdmin='" + CString().setNum(uiIsAdmin) +
               "',uiUserLevel='" + CString().setNum(uiUserLevel) +
               "',uiPassword='" + uiPassword +
               "',uiShare='" + CString().setNum(uiShare) +
               "',uiTag='" + uiTag +
               "',uiClient='" + uiClient +
               "',uiDescription='" + uiEscDescription +
               "',uiVersion='" + m_ClientVersion.m_sVersionString +
               "',uiMode='" + uiMode +
               "',uiHubs='" + CString().setNum(uiHubs) +
               "',uiHubsOp='" + CString().setNum(uiHubsOp) +
               "',uiHubsReg='" + CString().setNum(uiHubsReg) +
               "',uiHubsNorm='" + CString().setNum(uiHubsNorm) +
               "',uiSlots='" + CString().setNum(uiSlots) +
               "',uiLimiter='" + CString().setNum(uiLimiter) +
               "',uiSpeed='" + uiSpeed +
               "',uiFirstSeenTime='" + uiFirstSeenTime +
               "',uiLastSeenTime='" + timeHand.getDate() + timeHand.getTime() +
               "',uiTimeOnline='" + CString().setNum(uiTimeOnline) +
               "',uiTotalSearches='" + CString().setNum(uiTotalSearches) +
               "',uiKickTotal='" + CString().setNum(uiKickTotal) +
               "',uiBanTotal='" + CString().setNum(uiBanTotal) +
               "',uiSayTotal='" + CString().setNum(uiSayTotal) +
               "',uiShareChckd='" + CString().setNum(uiShareChckd) +
               "',uiShareChckdStart='" + uiShareChckdStart +
               "',uiShareChckdExpire='" + uiShareChckdExpire +
               "',uiBanFlag='" + CString().setNum(uiBanFlag) +
               "',uiBanTime='" + uiBanTime +
               "',uiBanExpire='" + uiBanExpire +
               "',uiLoginCount='" + CString().setNum(uiLoginCount) +
               "'");
          sqlUserExists=1; //Data is in SQL  
     }
     lockSqlUser(); //Lock this record
}          
//Show the user details
CString UserInfo::ShowUserInfo(void){
     CString s;
     s= "Information for: ";
     s+= uiNick.Data(); s+= " is :\r\n";
     s+= "# Logins : "; s+= CString().setNum(uiLoginCount); s+= "\r\n";
     s+= "IP address : "; s+= uiIp.Data(); s+= "\r\n";
     s+= "Host : "; s+= uiHost.Data(); s+= "\r\n";
     s+= "Country : "; s+= uiCountry.Data(); s+= "\r\n";
     s+= "Admin : "; s+= uiIsAdmin==1?"Yes":"No"; s+= "\r\n";
     switch ( uiUserLevel )
     {
          case euiUser:
          {
               s+= "User Level : User\r\n";
               break;
          }
          case euiVIP:
          {
               s+= "User Level : VIP\r\n";
               break;
          }
          case euiOp:
          {
               s+= "User Level : Operator\r\n";
               break;
          }
          case euiOpAdmin:
          {
               s+= "User Level : Operator Admin (Op-admin)\r\n";
               break;
          }
          case euiBotMaster:
          {
               s+= "User Level : Bot Master\r\n";
               break;
          }
          case euiBot:
          {
               s+= "User Level : Bot\r\n";
               break;
          }
          
     }

     s+= "Share : "; s+=  CUtils::GetSizeString(uiShare,euAUTO); s+= "\r\n";
     s+= "Description : "; s+= uiDescription.Data(); s+= "\r\n";
     if (!uiClient.IsEmpty())
     {
          s+= "Client : "; s+= uiClient.Data(); s+= "\r\n";
          s+= "Version : "; s+= m_ClientVersion.m_sVersionString.Data(); s+= "\r\n";
          s+= "Mode : "; s+= uiMode.Data(); s+= "\r\n";
          if (uiHubsNorm)
          {
               s+= "# Hubs [Op] : "; s+=CString().setNum(uiHubsOp); s+= "\r\n";
               s+= "# Hubs [Registerd] : "; s+=CString().setNum(uiHubsReg); s+= "\r\n";
               s+= "# Hubs [Norm] : "; s+=CString().setNum(uiHubsNorm); s+= "\r\n";
          }
          else
          {
               s+= "# Hubs : "; s+=CString().setNum(uiHubs); s+= "\r\n";
          }
          s+= "Slots : "; s+= CString().setNum(uiSlots); s+= "\r\n";
          if (!uiLimiter)
          {
               s+= "Limter : "; s+= CString().setNum(uiLimiter); s+= "\r\n";
          }
     }
     s+= "Connection : "; s+= uiSpeed.Data(); s+= "\r\n";
     s+= "First seen : "; s+= uiFirstSeenTime.Data();s+= "\r\n";
     s+= "Time online : "; s+= ShowTimeOnline(); s+= "\r\n";
     s+= "# Searches : "; s+= CString().setNum(uiTotalSearches); s+= "\r\n";
     s+= "# Kicks : "; s+= CString().setNum(uiKickTotal); s+= "\r\n";
     s+= "# Bans : "; s+= CString().setNum(uiBanTotal); s+= "\r\n";
     s+= "# Says : ", s+= CString().setNum(uiSayTotal); s+= "\r\n";
     s+= "Share Verified : "; s+= uiShareChckd==1?"Yes":"No"; s+= "\r\n";
     if (uiShareChckd==1)
     {
          s+= "Share Verified on :\t"; s+= uiShareChckdStart.Data(); s+= "\r\n";
          s+= "Share Check Expire:\t"; s+= uiShareChckdExpire.Data(); s+= "\r\n";
     }

     return(s);
}
CString UserInfo::ShowTimeOnline(void)
{
     CString s;
     int d,h,m;
     int i = time(0)-Uptime(); //This session
     i+=uiTimeOnline;

     d = i/(60*60*24);
     i = i%(60*60*24);
     h = i/(60*60);
     i = i%(60*60);
     m = i/60;

     s  = "";

     if ( d > 0 )
     {
          s += CString().setNum(d);
          if ( d == 1 )
               s += " day,";
          else
               s += " days,";
     }
     if ( h > 0 )
     {
          s += CString().setNum(h);
          if ( h == 1 )
               s += " hour,";
          else
               s += " hours,";
     }
     s += CString().setNum(m);
     if ( m == 1 )
          s += " minute";
     else
          s += " minutes";

     return(s);
}
void UserInfo::SetTag(CString tag)
{
     if (tag.IsEmpty())
     {
          uiTag = "Untagged";
          return;
     }
     else
     {
          //http://www.dslreports.com/faq/dc?text=1 <= from here
          uiTag = tag;
          CString temp;

//          int i1=tag.Find(' ');
          int i2=tag.Find("V:",0,FALSE);
          int i3=tag.Find(",M:",0,FALSE);
          int i4=tag.Find(",H:",0,FALSE);
          int i5=tag.Find(",S:",0,FALSE);
          int i6=tag.Find(",0:",0,FALSE);
          int i7=tag.Find('>',0,FALSE);
          
	m_ClientVersion.m_sVersionString = tag.Mid(i2+2,i3-i2-2);
	  
	int i,i1;
	
	// parse version
	if ( m_ClientVersion.m_sVersionString != "" )
	{
		if ( (i=m_ClientVersion.m_sVersionString.Find('.')) != -1 )
		{
			m_ClientVersion.m_nVersionMajor = m_ClientVersion.m_sVersionString.Mid(0,i).asINT();
			i++;
			if ( (i1=m_ClientVersion.m_sVersionString.Find('.',i)) != -1 )
			{
				m_ClientVersion.m_nVersionMinor = m_ClientVersion.m_sVersionString.Mid(i,m_ClientVersion.m_sVersionString.Length()-i1).asINT();
				i=i1+1;
				m_ClientVersion.m_nVersionPatch = m_ClientVersion.m_sVersionString.Mid(i,m_ClientVersion.m_sVersionString.Length()-i).asINT();
			}
			else
			{
				m_ClientVersion.m_nVersionMinor = m_ClientVersion.m_sVersionString.Mid(i,m_ClientVersion.m_sVersionString.Length()-i).asINT();
			}
		}
	}

          uiMode=tag.Mid(i3+3,i4-i3-3);
          if(uiMode=="A") {uiMode="Active";}
          else if (uiMode=="5"){uiMode="Socks";}
          else if (uiMode=="P"){uiMode="Passive";}
          else {uiMode = "ERROR";}
          if(tag.Find("/") != -1)
          {
               int h1=tag.Find('/');
               int h2=tag.Find('/',h1+1);
               uiHubsNorm=CString(tag.Mid(i4+3,h1-i4-3)).asINT();
               uiHubsReg=CString(tag.Mid(h1+1,h2-h1-1)).asINT();
               uiHubsOp=CString(tag.Mid(h2+1,i5-h2-1)).asINT();
               uiHubs = uiHubsNorm + uiHubsReg + uiHubsOp;
          }
          else
          {
               uiHubs=CString(tag.Mid(i4+3,i5-i4-3)).asINT();
          }
          uiSlots=CString(tag.Mid(i5+3,i7-i6-i5-3)).asINT();

          uiLimiter = 0;
     }
}          
void UserInfo::SetStatus(euiStatus status)
{
     uiStatus = status;
}
void UserInfo::SetIsAdmin(int admin)
{
     uiIsAdmin = admin;
     if ((uiIsAdmin == 1) &&
         (uiUserLevel == euiUser))
     {
          MySql->Update(uiHubID,"userInfo",
               "uiUserLevel='" + CString().setNum(euiOp) + "'"
               ,"uiNick='" + uiEscNick + "'");
     }
}
void UserInfo::SetSpeed(CString speed)
{
     uiSpeed = speed;
}
void UserInfo::SetShare(ulonglong share)
{
     if (uiShare != share)
     {
          uiShare = share;
          MySql->Update(uiHubID,"userInfo",
               "uiShare='" + CString().setNum(uiShare) + "'"
               ,"uiNick='" + uiEscNick + "'");
     }
}
void UserInfo::SetIsAway(euiIsAway away)
{
     if (uiIsAway != away)
     {
          uiIsAway = away;
          MySql->Update(uiHubID,"userInfo",
               "uiIsAway='" + CString().setNum(uiIsAway) + "'"
               ,"uiNick='" + uiEscNick + "'");
     }
}
void UserInfo::SetClient(CString client)
{
     uiClient = client;
}
void UserInfo::SetDescription(CString description)
{
     uiDescription = description;
}

void UserInfo::SetIp(CString ip)
{
     if (uiIp != ip)
     {
          uiIp = ip;
          CalculateCountry(uiIp);

          MySql->Update(uiHubID,"userInfo",
               "uiIp='" + uiIp +
               "',uiCountry='" + uiCountry + "'"
               ,"uiNick='" + uiEscNick + "'");
     }
     
}
void UserInfo::IncKickTotal(void)
{
      uiKickTotal++;
}
void UserInfo::IncBanTotal(void)
{
     uiBanTotal++; //Inc total ban counter
}
void UserInfo::SetBanTime(void)
{
     uiBanTime = timeHand.getDate() + timeHand.getTime();
}
void UserInfo::SetBanExpTime(int seconds)
{
     uiBanExpire = timeHand.addTime(seconds);
}
void UserInfo::IncSearchTotal(void)
{
     uiTotalSearches++;
}
void UserInfo::IncSayTotal(void)
{
     uiSayTotal++;
}
void UserInfo::IncLoginCount(void)
{
     uiLoginCount++;
}
