/***************************************************************************
                          mysqlhub.h  -  description
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

#ifndef MYSQLHUB_H
#define MYSQLHUB_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include <dclib/core/cstring.h>
#include <dclib/core/cstringlist.h>
#include <dclib/core/types.h>  //For speed enums
#include <dclib/dcobject.h>

#include "mysqlcon.h"

class CClientRule : public CObject {
public:
	/** */
	CClientRule() {}
	/** */
	virtual ~CClientRule() {}
	
	/** */
	ulonglong m_nID;
	/** */
	CString m_sName;
	/** */
	eUserClientVersion m_eClientVersion;
	/** */
	int m_nClientCommand;
	/** */
	int m_nRuleCommand;

	/** */
	eUserSpeed m_eMinUserSpeed;
	/** */
	eUserSpeed m_eMaxUserSpeed;
	
	/** */
	ulonglong m_nMinShared;
	/** */
	ulonglong m_nMaxShared;
	
	/** */
	double m_nMinLimit;
	/** */
	double m_nMaxLimit;
	
	/** */
	ulonglong m_nMinSlots;
	/** */
	ulonglong m_nMaxSlots;
	
	/** */
	ulonglong m_nMinHubs;
	/** */
	ulonglong m_nMaxHubs;

	/** */
	CMessageLock m_MinVersion;
	/** */
	CMessageLock m_MaxVersion;

	/** */
	double m_nSlotHubRatio;
	
	/** */
	bool m_bMotd;
	/** */
	CString m_sMotd;
	
	/** */
	CString m_sRedirectHost;
};

class MySqlHub {
public: 
	MySqlHub(int hubID,MySqlCon * mySql);
	~MySqlHub(void);
     void LoadHubConfig(void);

     int       GetHubID(void)           { return hcHubID;}
     CString   GetHubPwd(void)          { return hcPwd;}
     CString   GetHubStatus(void)       { return hcStatus;}
     bool      GetHubAutoConnect(void)  { return hcAutoConnect;}
     CString   GetHubName(void)         { return hcName;}
     CString   GetHubDescription()      { return hcDescription;}
     CString   GetHubHost(void)         { return hcHost;}
     int       GetHubMaxUsers(void)     { return hcMaxUsers;}
     CString   GetHubRedirectHost(void) { return hcRedirectHost;}
     int       GetHubSBan(void)         { return hcSBan;}
     int       GetHubLBan(void)         { return hcLBan;}
     int       GetHubShortBan(void)     { return hcShortBan;}
     int       GetHubLongBan(void)     { return hcLongBan;}     
     CString   GetHubSBanMultiplier(void) { return hcSBanMultiplier;}
     CString   GetHubLBanMultiplier(void) { return hcLBanMultiplier;}          
     int       GetHubShareCheckTimeout(void) { return hcShareCheckTimeout;}
     CString   GetHubOwner(void)        { return hcOwner;}
     CString   GetHubSoftware(void)     { return hcSoftware;}
     CString   GetHubVersion(void)      { return hcVersion;}
     CString   GetHubMotd(void)         { return hcMotd;}
     ulonglong GetHubMinShare(void)     { return hcMinShare;}
     int       GetHubMinSlots(void)     {  return hcMinSlots;}
     int       GetHubMaxSlots(void)     {  return hcMaxSlots;}
     int       GetHubMaxHubs(void)      {  return hcMaxHubs;}
     double    GetHubSlotRatio(void)    {  return hcSlotRatio;}
     bool      GetHubEnableTagCheck(void) {  return hcEnableTagCheck;}
     bool      GetHubKickNoTag(void)    {  return hcKickNoTag;}
     CString   GetHubMinSpeed(void)     {  return hcMinSpeed;}
     int       GetSBanTime(void)        { return hcSBan;}
     int       GetLBanTime(void)        { return hcLBan;}
     int       GetKickB4SBan(void)      { return hcSBansBeforeLBans;}            
     bool      GetHubCloneCheck(void)   { return hcEnableCloneCheck;}
     //Verbosity Configs
     int       GetHubVerboseJoin(void) {  return hcVerboseJoin;}
     int       GetHubVerboseKick(void)  {  return hcVerboseKick;}
     int       GetHubVerboseBan(void)   {  return hcVerboseBan;}


     
     int       ConvertSpeed(CString speed);
     
     void      SetHubAutoConnect(CString autoc);
     void      SetHubName(CString HubNTTame);
     void      SetHubDescription(CString hubDes);
     void      SetHubMaxUsers(CString maxUsers);
     void      SetHubMinShare(CString minShare);
     void      SetHubRedirectHost(CString redirHost);
     void      SetHubTempBan(CString tBanTime);
     void      SetHubShareCheckTimeout(CString ShrChckTime);
     void      SetHubOwner(CString hOwder);
     void      SetHubSoftware(CString hubSoft);
     void      SetHubVersion(CString hubVer);
     void      SetHubMotd(CString Motd);
     void      SetHubMinSpeed(CString minSpeed);
     void      SetHubMinSlots(CString minSlots);
     void      SetHubMaxSlots(CString maxSlots);
     void      SetHubMaxHubs(CString maxHubs);
     void      SetHubSlotRatio(CString slotRatio);
     void      SetHubEnableTagCheck(CString enableTagCheck);
     void      SetHubKickNoTag(CString kickNoTag);


     CString   GetHubExRules(void) {return hxHubRules;}
     CString   GetHubExBanChat(void) {return hxBanChat;}
     CString   GetHubExBanNick(void) {return hxBanNicks;}
     CString   GetHubExBanSearch(void) {return hxBanSearch;}
     CString   GetHubExBanSharedFiles(void) {return hxBanSharedFiles;}
	
	/** */
	CStringList * GetClientRules() { return &m_ClientRules; };
	/** */
	CClientRule * GetDefaultClientRule() { return m_pDefaultRule; };
	
private:
     int       hcHubID;
     CString   hcPwd;
     CString   hcStatus;
     bool      hcAutoConnect;
     CString   hcName;
     CString   hcDescription;
     CString   hcHost;
     int       hcMaxUsers;
     ulonglong hcMinShare;
     CString   hcMinShareMultiplier;
     CString   hcRedirectHost;
     int       hcSBan;
     int       hcShortBan;
     CString   hcSBanMultiplier;
     int       hcLBan;
     int       hcLongBan;
     CString   hcLBanMultiplier;
     int       hcSBansBeforeLBans;
     int       hcShareCheckTimeout;
     CString   hcShareCheckTimeoutMultiplier;
     int       hcFileListDl;
     CString   hcOwner;
     CString   hcSoftware;
     CString   hcVersion;
     CString   hcMotd;
     CString   hcMinSpeed;
     int       hcMinSlots;
     int       hcMaxSlots;
     int       hcMaxHubs;
     int       hcMinLimiter;
     double    hcSlotRatio;
     bool      hcEnableTagCheck;
     bool      hcKickNoTag;
     bool      hcEnableCloneCheck;
     // Verbosity configuration
     int       hcVerboseJoin;
     int       hcVerboseKick;
     int       hcVerboseBan;

     int       hxRecUsers;
     ulonglong hxRecShare;
     CString   hxHubRules;
     CString   hxBanNicks;
     CString   hxBanSearch;
     CString   hxBanChat;
     CString   hxBanSharedFiles;

     	/** */
	CStringList m_ClientRules;
	/** */
     	CClientRule * m_pDefaultRule;
     
     MySqlCon * hMySql;
};

#endif
