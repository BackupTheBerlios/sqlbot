/***************************************************************************
                          mysqlhub.cpp  -  description
                             -------------------
    begin                : Thu Oct 16 2003
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

#include "mysqlhub.h"
#include <iostream>

using std::cout;
using std::endl;

MySqlHub::MySqlHub(int hubID,MySqlCon * mySql){
     hMySql = mySql;
     hcHubID = hubID;
     hcPwd =                  "";
     hcStatus =               "";
     hcAutoConnect =          0;
     hcName =                 "";
     hcDescription =          "";
     hcHost =                 "";
     hcMaxUsers =             500;
     hcMinShare =             0l;
     hcMinShareMultiplier =    "";
     hcRedirectHost =         "";
     hcSBan =                 60;
     hcSBanMultiplier =       "";
     hcLBan =                 60;
     hcLBanMultiplier =       "";
     hcSBansBeforeLBans =     0;
     hcShareCheckTimeout =    120;
     hcShareCheckTimeoutMultiplier = "";
     hcFileListDl =           1;
     hcOwner =                "";
     hcSoftware =             "";
     hcVersion =              "";
     hcMotd =                 "";
     hcMinSpeed =             0;
     hcMinSlots =             3;
     hcMaxSlots =             10;
     hcMaxHubs =              5;
     hcSlotRatio =            1.0;
     hcEnableTagCheck =       0;
     hcKickNoTag =            0;
     hcEnableCloneCheck =     0;
     hcVerboseJoin     =     0;
     hcVerboseKick =          0;
     hcVerboseBan =           0;

     hxRecUsers =             0;
     hxRecShare =             0;
     hxHubRules =             "";
     hxBanNicks =             "";
     hxBanSearch =            "";
     hxBanChat =              "";
     hxBanSharedFiles =       "";
     
     m_pDefaultRule = 0;

}     
MySqlHub::~MySqlHub(){
}

//Read entire table from Sql
void MySqlHub::LoadHubConfig(){
    
     MYSQL_RES * result = hMySql->Query(hcHubID,"*","hubConfig","");
     MYSQL_ROW row = hMySql->FetchResult(result);
     if (row)
     {
          
          int i = 0;
          hcHubID             = CString(row[i]).asINT();    i++;
          hcPwd               = row[i];                     i++;
          hcStatus            = row[i];                     i++;
          hcAutoConnect       = CString(row[i]).asINT();    i++;
          hcName              = row[i];                     i++;
          hcDescription       = row[i];                     i++;
          hcHost              = row[i];                     i++;
          hcMaxUsers          = CString(row[i]).asINT();    i++;
          hcMinShare          = CString(row[i]).asINT();    i++;
          hcMinShareMultiplier= row[i];                     i++;
          hcRedirectHost      = row[i];                     i++;
          hcSBan              = CString(row[i]).asINT();    i++;
          hcSBanMultiplier    = row[i];                     i++;
          hcLBan              = CString(row[i]).asINT();    i++;
          hcLBanMultiplier    = row[i];                     i++;
          hcSBansBeforeLBans  = CString(row[i]).asINT();    i++;
          hcShareCheckTimeout = CString(row[i]).asINT();    i++;
          hcShareCheckTimeoutMultiplier = row[i];           i++;
          hcFileListDl        = CString(row[i]).asINT();    i++;
          hcOwner             = row[i];                     i++;
          hcSoftware          = row[i];                     i++;
          hcVersion           = row[i];                     i++;
          hcMotd              = row[i];                     i++;
          hcMinSpeed          = row[i];                     i++;
          hcMinSlots          = CString(row[i]).asINT();    i++;
          hcMaxSlots          = CString(row[i]).asINT();    i++;
          hcMaxHubs           = CString(row[i]).asINT();    i++;
          hcMinLimiter        = CString(row[i]).asINT();    i++;
          hcSlotRatio         = CString(row[i]).asDOUBLE(); i++;
          hcEnableTagCheck    = CString(row[i]).asINT();    i++;
          hcKickNoTag         = CString(row[i]).asINT();    i++;
          hcEnableCloneCheck  = CString(row[i]).asINT();    i++;
          hcVerboseJoin       = CString(row[i]).asINT();    i++;
          hcVerboseKick       = CString(row[i]).asINT();    i++;
          hcVerboseBan        = CString(row[i]).asINT();    i++;
     }
     hMySql->FreeRes(result);

	// get client rules
	m_ClientRules.Clear();
	
	if ( m_pDefaultRule )
	{
		delete m_pDefaultRule;
		m_pDefaultRule = 0;
	}

	result = hMySql->Query(hcHubID,"*","clientRules","");
	
	if ( result )
	{
		while ( (row = hMySql->FetchResult(result)) )
		{
			int i = 0;
			CClientRule * cr = new CClientRule();
			
			cr->m_nID                        = CString(row[i++]).asULL();
			i++; // hubID
			cr->m_sName                      = row[i++];
			cr->m_eClientVersion             = eUserClientVersion(CString(row[i++]).asINT());
			cr->m_nClientCommand             = CString(row[i++]).asINT();
			cr->m_nRuleCommand               = CString(row[i++]).asINT();
			cr->m_eMinUserSpeed              = eUserSpeed(CString(row[i++]).asINT());
			cr->m_eMaxUserSpeed              = eUserSpeed(CString(row[i++]).asINT());
			cr->m_nMinShared                 = CString(row[i++]).asULL();
			cr->m_nMaxShared                 = CString(row[i++]).asULL();
			cr->m_nMinLimit                  = CString(row[i++]).asDOUBLE();
			cr->m_nMaxLimit                  = CString(row[i++]).asDOUBLE();
			cr->m_nMinSlots                  = CString(row[i++]).asULL();
			cr->m_nMaxSlots                  = CString(row[i++]).asULL();
			cr->m_nMinHubs                   = CString(row[i++]).asULL();
			cr->m_nMaxHubs                   = CString(row[i++]).asULL();
			cr->m_MinVersion.m_nVersionMajor = CString(row[i++]).asINT();
			cr->m_MinVersion.m_nVersionMinor = CString(row[i++]).asINT();
			cr->m_MinVersion.m_nVersionPatch = CString(row[i++]).asINT();
			cr->m_MaxVersion.m_nVersionMajor = CString(row[i++]).asINT();
			cr->m_MaxVersion.m_nVersionMinor = CString(row[i++]).asINT();
			cr->m_MaxVersion.m_nVersionPatch = CString(row[i++]).asINT();
			cr->m_nSlotHubRatio              = CString(row[i++]).asDOUBLE();
			cr->m_bMotd                      = CString(row[i++]).asINT();
			cr->m_sMotd                      = row[i++];
			cr->m_sRedirectHost              = row[i++];
			
			if ( cr->m_sName == "DEFAULT" )
			{
				if ( m_pDefaultRule )
					printf("WARNING: found double DEFAULT client rules\n");
				else
					m_pDefaultRule = cr;
			}
			else
				m_ClientRules.Add( CString().setNum(cr->m_nID), cr );
			
			printf("Load client rule: '%s'\n",cr->m_sName.Data());
		}
		
		hMySql->FreeRes(result);
	}

	
     result = hMySql->Query(hcHubID,"*","hubExtras","");
     row = hMySql->FetchResult(result);
     if (row)
     {
          int i = 0;
          /* hubID*/                                        i++;
          hxRecUsers          = CString(row[i]).asINT();    i++;
          hxRecShare          = CString(row[i]).asINT();    i++;
          hxHubRules          = row[i];                     i++;
          hxBanNicks          = row[i];                     i++;
          hxBanSearch         = row[i];                     i++;
          hxBanChat           = row[i];                     i++;
          hxBanSharedFiles    = row[i];                     i++;
     }
     hMySql->FreeRes(result);

     //Multiply up the share
     if (hcMinShareMultiplier == "KB")
          {hcMinShare = hcMinShare * 1024;}
     else if (hcMinShareMultiplier == "MB")
          {hcMinShare = hcMinShare * 1024*1024;}
     else if (hcMinShareMultiplier == "GB")
          {hcMinShare = hcMinShare * 1024*1024*1024;}

     //Multiply up the Short Ban timers          
     if (hcSBanMultiplier == "minutes")
          {hcShortBan = hcSBan * 60;}
     else if (hcSBanMultiplier == "hours")
          {hcShortBan = hcSBan * 60 * 60;}
     else if (hcSBanMultiplier == "days")
          {hcShortBan = hcSBan * 60 * 60 * 24;}
     //Multiply up the Long Ban timers                    
     if (hcLBanMultiplier == "minutes")
          {hcLongBan = hcLBan * 60;}
     else if (hcLBanMultiplier == "hours")
          {hcLongBan = hcLBan * 60 * 60;}
     else if (hcLBanMultiplier == "days")
          {hcLongBan = hcLBan * 60 * 60 * 24;}
     else if (hcLBanMultiplier == "weeks")
          {hcLongBan = hcLBan * 60 * 60 * 24 * 7;}
     //Multiply up the Share Check timer 
     if (hcShareCheckTimeoutMultiplier == "minutes")
          {hcShareCheckTimeout = hcShareCheckTimeout * 60;}
     else if (hcShareCheckTimeoutMultiplier == "hours")
          {hcShareCheckTimeout = hcShareCheckTimeout * 60 * 60;}
     else if (hcShareCheckTimeoutMultiplier == "days")
          {hcShareCheckTimeout = hcShareCheckTimeout * 60 * 60 * 24;}
          
}

void MySqlHub::SetHubAutoConnect(CString autoc)
{
     hcAutoConnect=CString(autoc).asINT(); 
     hMySql->Update(hcHubID,"hubConfig","hcAutoConnect='" + autoc + "'","");     
}
void MySqlHub::SetHubName( CString HubName )
{
     hcName = HubName;
     hMySql->Update(hcHubID,"hubConfig","hcName='"+ HubName +"'","");
}
void MySqlHub::SetHubDescription(CString hubDes)
{
     hcDescription=hubDes;
     hMySql->Update(hcHubID,"hubConfig","hcDescription='" + hubDes + "'","");     
}
void MySqlHub::SetHubMaxUsers(CString maxUser)
{
     hcMaxUsers=CString(maxUser).asINT();
     hMySql->Update(hcHubID,"hubConfig","hcMaxUsers='" + maxUser + "'","");     
}
void MySqlHub::SetHubMinShare(CString minShare)
{
     hcMinShare=CString(minShare).asULL();
     hMySql->Update(hcHubID,"hubConfig","hcMinShare='" + minShare + "'","");     
}
void MySqlHub::SetHubRedirectHost(CString redirHost)
{
     hcRedirectHost=redirHost;
     hMySql->Update(hcHubID,"hubConfig","hcRedirectHost='" + redirHost + "'","");     
}
void MySqlHub::SetHubTempBan(CString tBanTime)
{
     hcSBan=CString(tBanTime).asINT();
     hMySql->Update(hcHubID,"hubConfig","hcSBan='" + tBanTime + "'","");     
}
void MySqlHub::SetHubShareCheckTimeout(CString ShrChckTime)
{
     hcShareCheckTimeout=CString(ShrChckTime).asINT();
     hMySql->Update(hcHubID,"hubConfig","hcShareCheckTimeout='" + ShrChckTime + "'","");     
}
void MySqlHub::SetHubOwner(CString hOwder)
{
     hcOwner=hOwder;
     hMySql->Update(hcHubID,"hubConfig","hcOwner='" + hOwder + "'","");     
}
void MySqlHub::SetHubSoftware(CString hubSoft)
{
     hcSoftware=hubSoft;
     hMySql->Update(hcHubID,"hubConfig","hcSoftware='" + hubSoft + "'","");     
}
void MySqlHub::SetHubVersion(CString hubVer)
{
     hcVersion=hubVer;
     hMySql->Update(hcHubID,"hubConfig","hcVersion='" + hubVer + "'","");     
}
void MySqlHub::SetHubMotd(CString Motd)
{
     hcMotd=Motd;
     hMySql->Update(hcHubID,"hubConfig","hcMotd='" + Motd + "'","");     
}
void MySqlHub::SetHubMinSpeed(CString minSpeed)
{
     hcMinSpeed=minSpeed;
     hMySql->Update(hcHubID,"hubConfig","hcMinConnection='" + minSpeed + "'","");     
}
void MySqlHub::SetHubMinSlots(CString minSlots)
{
     hcMinSlots=CString(minSlots).asINT();
     hMySql->Update(hcHubID,"hubConfig","hcMinSlots='" + minSlots + "'","");     
}
void MySqlHub::SetHubMaxSlots(CString maxSlots)
{
     hcMaxSlots=CString(maxSlots).asINT();
     hMySql->Update(hcHubID,"hubConfig","hcMaxSlots='" + maxSlots + "'","");     
}
void MySqlHub::SetHubMaxHubs(CString maxHubs)
{
     hcMaxHubs=CString(maxHubs).asINT();
     hMySql->Update(hcHubID,"hubConfig","hcMaxHubs='" + maxHubs + "'","");     
}
void MySqlHub::SetHubSlotRatio(CString slotRatio)
{
     hcSlotRatio=CString(slotRatio).asDOUBLE();
     hMySql->Update(hcHubID,"hubConfig","hubSlotRatio='" + slotRatio + "'","");     
}
void MySqlHub::SetHubEnableTagCheck(CString enableTagCheck)
{
     hcEnableTagCheck=CString(enableTagCheck).asINT();
     hMySql->Update(hcHubID,"hubConfig","hcEnableTagCheck='" + enableTagCheck + "'","");     
}
void MySqlHub::SetHubKickNoTag(CString kickNoTag)
{
     hcKickNoTag=CString(kickNoTag).asINT();
     hMySql->Update(hcHubID,"hubConfig","hcKickNoTag='" + kickNoTag + "'","");     
}

/** */
int MySqlHub::ConvertSpeed(CString speed)
{
          if ( speed == "28.8Kbps" )
               return(eus288KBPS);
          else if ( speed == "33.6Kbps" )
               return(eus288KBPS);
          else if ( speed == "56Kbps" )
               return(eus56KBPS);
          else if ( speed == "ISDN" )
               return(eusISDN);
          else if ( speed == "DSL" )
               return(eusDSL);
          else if ( speed == "Satellite" )
               return(eusSATELLITE);
          else if ( speed == "Cable" )
               return(eusCABLE);
          else if ( speed == "LAN(T1)" )
               return(eusLANT1);
          else if ( speed == "LAN(T3)" )
               return(eusLANT3);
          else if ( speed == "Wireless" )
               return(eusWIRELESS);
          else if ( speed == "Microwave" )
               return(eusMICROWAVE);
          else
               return(eusUNKNOWN);
}
