/***************************************************************************
                          log.cpp  -  description
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

#include <iostream>
#include <fstream>
#include "timehand.h"
#include "log.h"
#include "globalconf.h"

using namespace std;
using std::cout;
using std::endl;
using std::ios;

Log::Log(int hubID,MySqlCon * mySql){
     logHubID = hubID;
     logMySql = mySql;
     hcName = "";
     hcLogChat = ehcNone;
     hcLogSearches = ehcNone;
     hcLogSystem = ehcNone;
     bcLogDir = "";

     chatLogFileName = "";
     systemLogFileName = "";
     searchLogFileName = "";

     initialised = FALSE;
}
Log::~Log(){

}
void Log::readLogCfg()
{

     MYSQL_RES *result = logMySql->Query(logHubID,"hcLogChat,hcLogSearches,hcLogSystem,hcName","hubConfig","");
     
     if (MYSQL_ROW row = logMySql->FetchResult(result))
     {
          hcLogChat = (ehcLogging)CString(row[0]).asINT();
          hcLogSearches = (ehcLogging)CString(row[1]).asINT();
          hcLogSystem = (ehcLogging)CString(row[2]).asINT();
          hcName = row[3];
     }
     logMySql->FreeRes(result);

     result = logMySql->Query(0,"bcLogDir","botConfig","");
 
     if(MYSQL_ROW row = logMySql->FetchResult(result))
     {
          bcLogDir = row[0];
     }
     logMySql->FreeRes(result);

     initialised = TRUE;
}

void Log::SetSrchFileName()
{
      searchLogFileName = bcLogDir.Data();
      searchLogFileName += hcName.Data();
      searchLogFileName += "-";      
      searchLogFileName += timeHand.getLogDate();
      searchLogFileName += ".srch.log";
}
void Log::SetSysLogFileName()
{
      systemLogFileName = bcLogDir.Data();
      systemLogFileName += hcName.Data();
      systemLogFileName += "-";
      systemLogFileName += timeHand.getLogDate();
      systemLogFileName += ".sys.log";
}
void Log::SetChatLogFileName()
{
      chatLogFileName = bcLogDir.Data();
      chatLogFileName += hcName.Data();
      chatLogFileName += "-";
      chatLogFileName += timeHand.getLogDate();
      chatLogFileName += ".chat.log";
}


/** Write an entry to the log file */
void Log::WriteSysLog(CString LogMsg)
{
     if (!initialised)
     {
          readLogCfg();   //Read Sql
     }
     
     if(!hcLogSystem)  //No logging
          return;

     //Txt Logging
     if((hcLogSearches & ehcTxt) == ehcTxt)
     {
          SetSysLogFileName();
          ofstream logfile (systemLogFileName.Data(),ios::app);
          if (logfile.is_open())
          {
               logfile << timeHand.getLogTime().Data() << " - " << LogMsg.Data() << "\n";
               logfile.close();
          }
     }
     //Sql Logging
     if((hcLogSystem & ehcSql) == ehcSql)
     {

     }     
}
void Log::WriteSrchLog(CString nick, CString LogMsg, eFileTypes type)
{
     if (!initialised)
     {
          readLogCfg();   //Read Sql
     }

     //No Logging
     if(!hcLogSearches)
          return;

     //Txt Logging
     if((hcLogSearches & ehcTxt) == ehcTxt)
     {
          SetSrchFileName();
          ofstream logfile (searchLogFileName.Data(),ios::app);
          if (logfile.is_open())
          {
               logfile << timeHand.getLogTime().Data()
                       << " Nick-" << nick.Data()
                       << " Search - " << LogMsg.Data()
                       << " File Type - " << nick.Data() << "\n";
               logfile.close();
          }
     }
     //Sql Logging
     if((hcLogSearches & ehcSql) == ehcSql)
     {
          nick = nick.Replace( '\\', "\\\\" );
          nick = nick.Replace( '\'', "\\'" );
          LogMsg = LogMsg.Replace( '\\', "\\\\" );
          LogMsg = LogMsg.Replace( '\'', "\\'" );
          logMySql->Insert("logSearch",
               "lsTime='" + timeHand.getDate() + timeHand.getTime() +
               "',hubID='" + CString().setNum(logHubID) +
               "',lsNick='" + nick.Data() +
               "',lsSearch='" + LogMsg.Data() +
               "',lsType='" + CString().setNum(type) + "'");
     }
}
void Log::WriteChatLog(CString nick, CString LogMsg)
{
     if (!initialised)
     {
          readLogCfg();   //Read Sql
     }
     
     if(!hcLogChat)  //No logging
          return;

     //Txt Logging
     if((hcLogChat & ehcTxt) == ehcTxt)
     {

          SetChatLogFileName();
          ofstream logfile (chatLogFileName.Data(),ios::app);
          if (logfile.is_open())
          {
               logfile << timeHand.getLogTime().Data()
                       << " " << nick.Data()
                       << " - " << LogMsg.Data() << "\n";
               logfile.close();
          }
     }
     //Sql Logging
     if((hcLogChat & ehcSql) == ehcSql)
     {
          nick = nick.Replace( '\\', "\\\\" );
          nick = nick.Replace( '\'', "\\'" );
          LogMsg = LogMsg.Replace( '\\', "\\\\" );
          LogMsg = LogMsg.Replace( '\'', "\\'" );

          logMySql->Insert("logChat",
               "lcTime='" + timeHand.getDate() + timeHand.getTime() +
               "',hubID='" + CString().setNum(logHubID) +
               "',lcNick='" + nick.Data() +
               "',lcMessage='" + LogMsg.Data() + "'");
     }

}

