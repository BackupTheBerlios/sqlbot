/***************************************************************************
                          log.h  -  description
                             -------------------
    begin                : Tue Oct 7 2003
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

#ifndef LOG_H
#define LOG_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include "timehand.h"  
#include <fstream>
#include <dclib/core/cstring.h>
#include "mysqlcon.h"
#include "globalconf.h"
#include <dclib/core/types.h> //for file types



class Log {
public: 
     Log(int hubID,MySqlCon * mySql);
     ~Log();
     void readLogCfg();
     void WriteChatLog(CString nick, CString LogMsg);
     void WriteSrchLog(CString nick, CString LogMsg, eFileTypes type);
     void WriteSysLog(CString LogMsg);
     void SetChatLogFileName(void);
     void SetSysLogFileName(void);
     void SetSrchFileName(void);

     ehcLogging GetHubLogChat(void)   { return hcLogChat;}
     ehcLogging GetHubLogSrch(void)   { return hcLogSearches;}
     ehcLogging GetHubLogSystem(void)   { return hcLogSystem;}
     
private:
     // The Sql object
     MySqlCon * logMySql;
     int logHubID;
     CString hcName;
     ehcLogging hcLogChat;       //0,1
     ehcLogging hcLogSearches;   //0,1
     ehcLogging hcLogSystem;     //0,1
     CString bcLogDir;        // log file path

     CString chatLogFileName;
     CString systemLogFileName;
     CString searchLogFileName;
     
     TimeHand timeHand;

     bool initialised;          
};

#endif
