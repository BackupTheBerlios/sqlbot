/***************************************************************************
                          userinfo.h  -  description
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

#ifndef USERINFO_H
#define USERINFO_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include <time.h>
#include <dclib/cutils.h>
#include <dclib/core/cobject.h>
#include <dclib/core/cstring.h>
#include <dclib/dcobject.h>
#include "mysqlcon.h"
#include "timehand.h"
#include "globalconf.h"

class UserInfo : public CObject {
public: 
	/** */
	UserInfo(int hubID,class MySqlCon * mySql,CString nick);
	/** */
     	virtual ~UserInfo();

     	/** */
	void SetClient(CString client);
	/** */
	CString GetClient(void) { return uiClient;}
	/** */
	void UserClientVersion( enum eUserClientVersion e ) { m_ClientVersion.m_eClientVersion = e; }
	/** */
	enum eUserClientVersion UserClientVersion() { return m_ClientVersion.m_eClientVersion; }
	/** */
	CMessageLock * ClientVersion() { return &m_ClientVersion; }

     void SetIsAdmin(int admin);
     void SetSpeed(CString speed);
     void SetShare(ulonglong share);
     void SetStatus(euiStatus status);
     void SetIsAway(euiIsAway away);
     void SetDescription(CString description);
     void SetIp(CString ip);
     void SetTag(CString tag);
     void SetBanTime(void);
     void SetBanFlag(euiBanFlag banflag){ uiBanFlag = banflag;}
     void SetBanExpTime(int seconds);
     // Call to inc kick count
     void IncKickTotal(void);
     // Call to inc Ban count
     void IncBanTotal(void);
     // Call to inc Search count
     void IncSearchTotal(void);
     void IncSayTotal(void);
     void IncLoginCount(void);
     void WriteUserInfo(void); //Write userInfo to SQL
     int ReadUserInfo(void); //Read userInfo from SQL

     int GetKickTotal(void) { return uiKickTotal;}
     int GetBanTotal(void) { return uiBanTotal;}
     CString GetNick(void) { return uiNick;}
     CString GetIp(void) { return uiIp;}
     CString GetTag(void) { return uiTag;}
     CString GetSpeed(void) { return uiSpeed;}
     CString GetCountry(void) { return uiCountry;}
     CString GetClientVersion(void) { return uiVersion;}
     CString GetDescription(void) { return uiDescription;}
     int GetUserLevel(void) { return uiUserLevel;}
     int GetIsAdmin(void){return uiIsAdmin;}
     int GetIsAway(void){return uiIsAway;}
     int GetBanFlag(void){return uiBanFlag;}
     int GetUiHubs(void) { return uiHubs;}          // Total number of hubs
     int GetUiHubsOp(void) { return uiHubsOp;}      // Number of hubs as an op
     int GetUiHubsReg(void) { return uiHubsReg;}    // Number of hubs as registered
     int GetUiHubsNorm(void) { return uiHubsNorm;}  // Number of hubs as user

     ulonglong GetShare(void) {return uiShare;}
     int GetSlots(void) {return uiSlots;}
     int GetHubs(void) {return uiHubs;}
     //Returns time total online time as H:MM:SS
     CString ShowTimeOnline(void);
     CString ShowUserInfo(void);
     int memUsage(void) { return sizeof(*this);}
     
     /** Get Country from IP address. HEAVY LOAD*/
     void CalculateCountry(CString dottedIP);
     void unlockSqlUser(void) { sqlDataUptoDate = 0; }
     void lockSqlUser(void) { sqlDataUptoDate = 1; }
                                                                  
private:

	/** */
	CMessageLock m_ClientVersion;
	/** */
	enum eUserClientVersion m_eUserClientVersion;
	/** */
	CString uiVersion;            // Client version
	
	
     CString   uiEscNick;              // an SQL safe version of the nick

     CString   uiNick;               // nick name of user
     CString   uiIp;                 // IP address of user
     CString   uiHost;               // host mask
     int       uiIsAway;             // 0-online,1-away
     euiStatus uiStatus;             // 0-offline,1-online
     int       uiHubID;              // hub this was user is on
     CString   uiCountry;            // Country of residence
     CString   uiCountryFull;            // Country of residence     
     int       uiIsAdmin;            // Is user/reg/op/admin
     euiUserLevel uiUserLevel;          // The User level
     CString   uiPassword;           // Hub password
     ulonglong uiShare;              // Total in Bytes of Share
     CString   uiTag;                // Tag
     CString   uiClient;             // Name of client from info string
     CString   uiDescription;        // Description
     
     CString   uiMode;               // Active or Passive
     int       uiHubs;               // Total number of hubs
     int       uiHubsOp;             // Number of hubs as an op
     int       uiHubsReg;            // Number of hubs as registered
     int       uiHubsNorm;           // Number of hubs as user
     int       uiSlots;              // Number of slots
     int       uiLimiter;            // Speed limiter
     CString   uiSpeed;              // Reported connection speed
     CString   uiFirstSeenTime;      // First time seen on hub     (YYYYMMDDHHMMSS)
     CString   uiLastSeenTime;       // Time last seen on hub      (YYYYMMDDHHMMSS)
     int       uiTimeOnline;         // Total time spent on hub    (seconds)
     int       uiTotalSearches;      // Number of search requests
     int       uiKickTotal;          // Number of timers kicked
     int       uiBanTotal;           // Numer of bans made
     int       uiSayTotal;           // Number of lines spoken
     bool      uiShareChckd;         // Flag yes/no               
     CString   uiShareChckdStart;    // Time the share check filelist download was attempted (YYYY-MM-DD HH:MM:SS)
     CString   uiShareChckdExpire;   // Time share check expires   (YYYYMMDDHHMMSS)
     int       uiBanFlag;            // Ban status of user         (pBan,tBan,nBan,FakeShare,FakeTag)
     CString   uiBanTime;            // time ban condition was set (YYYYMMDDHHMMSS)
     CString   uiBanExpire;          // time ban condition expires (YYYYMMDDHHMMSS)
     int       uiLoginCount;

     int       sqlUserExists;          //0=No data in SQL, 1=Data in SQL for this user
     int       sqlDataUptoDate;

     time_t    sdclibUptime;
     time_t    Uptime(void){return sdclibUptime;}

     TimeHand timeHand;  //Timer handling object
     MySqlCon * MySql; // The mysql connection

     
};

#endif
