/***************************************************************************
                          dcclient.h  -  description
                             -------------------
    begin                : Tue Sep 30 2003
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

#ifndef DCCLIENT_H
#define DCCLIENT_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

#include <dclib/cclient.h>
#include <dclib/cconfig.h>
#include <dclib/core/cstring.h>

#include "mysqlcon.h"
#include "userinfo.h"
#include "mysqlhub.h"
#include "botcommands.h"
#include "log.h"
#include "hubinterface.h"
#include "botcontroller.h"

class DCClient : public CClient  {
public: 
     /** */
     DCClient(class BotController * bController,MySqlCon * mySql,int hubId,CString s_botMaster,CString s_botNick);
     /** */
     virtual ~DCClient();

     /** callback functiomySqln */
     virtual int DC_CallBack( CObject * Object );
        
     /** chat */
     void Chat( CMessageChat * MessageChat );
     /** myinfo */
     void MyInfo( CMessageMyInfo * MessageMyInfo );
     /** nicklist */
     void NickList( CMessageNickList * MessageNickList );
     /** oplist */
     void OpList( CMessageNickList * MessageOpList );
     /** hello */
     void Hello( CMessageHello * MessageHello );
     /** Quit */
     void Quit( CMessageQuit * MessageQuit );
     /** Search Message handler*/
     void Search( CMessageSearch * MessageSearch );
     /** Private Chat Handler*/
     void PrivateChat( CMessagePrivateChat * MessageChat);
     /** */
     void Lock(CMessageLock * MessageLock );
     /** Move user elsewhere */
     bool ForceMove( CString nick, CString message, CString host );                         
     /** Send Output to the console */
     void SendConsole(CString out1,CString out2,CString out3);
     void SendConsole(CString out1,CString out2);
     /** Command Handler */
     bool CheckForCommand(eMsgSrc msgSrc, CString nick, CString cmd);
     /** Get userInfo object */
     UserInfo * GetNickInfo(CString nick);
     /** Get a Nick from an IP */
     CString GetNickFromIp(CString ip);
     /** Get Current user count */   
     int GetUserCount(void) {return nickList->Count();} 
     /** Get Operator Count */
     int GetOpCount(void) {return opList->Count();} 
     /** Get nick of the Master */
     CString GetBotMaster() {return botMaster;}
     /** Get nick of bot */
     CString GetBotNick() {return botNick;}
     /** Check the client tag */
     bool ClientCheck(UserInfo *info,CString nick);
     /** Check for Clones */
     bool CloneCheck(CString nick,CString nick1);
     /** Get the Hub ID */          
     int  GetHubId(void){return dcHubId;}
     /** Get Logging object */
     Log * GetLogger(void){return logger;}
     /** Get Hubconfig Object */
     MySqlHub * GetHubConfig(){return hubConfig;}
     /** Get MySql connection */
     MySqlCon * GetMySqlCon(void){return MySql;}
     /** command handler */
     BotCommands botc;
     /**Interface for hub commands */
     HubInterface *interface;
     /** Write all user records*/
     void SaveUserList(void);
     /** Count of hubs connected */
     static int GetHubCount(void) {return hubCount;}
     /** Top level bot controller */
     BotController * GetBController(){return botController;}

     CString GetVipList();

     CString GetOpList();
private:
     //Sql hub Id
     int dcHubId;
     // The Sql object
     MySqlCon * MySql;
     BotController * botController; //The top level controller
     int Status;
     Log * logger;
     //Full reported hubname
     CString Hubname;  
     //List for ops to this Op UserInfo
     CStringList *opList;
     //List for nick to this nicks UserInfo
     CStringList *nickList;
     //List for ops to this Op UserInfo
     CStringList *vipList;
        
     //Total onine share container
     ulonglong hubShare; 
     //Suspect flood msg
     CMessageChat chatFloodMessage;     
     //User flood counter
     long chatFloodCounter;
     //Hub config object
     MySqlHub *hubConfig;

     //Hub connection counter.
     static int hubCount;
     //Hub Object list, for hub interactions.
     static CStringList hubList;
protected:
     //Nick of Bot Master
     CString botMaster;
     //Nick of Bot
     CString botNick;

};

#endif
