/***************************************************************************
                          botcontroller.cpp  -  description
                             -------------------
    begin                : Sun Nov 2 2003
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

#include "botcontroller.h"
#include <iostream>

using std::cout;
using std::endl;

BotController::BotController(){
     botController = this;

     CManager::SetInstance(new CManager());
     CAsyncDns::SetInstance(new CAsyncDns());

     cconfig = new CConfig(".sqlbot");

     CFileManager::SetInstance(new CFileManager());
     CListenManager::SetInstance(new CListenManager());
     
     CDownloadManager::SetInstance(new CDownloadManager());
     CQueryManager::SetInstance(new CQueryManager());
     CConnectionManager::SetInstance(new CConnectionManager());
     CHubListManager::SetInstance(new CHubListManager());
     CDownloadManager::Instance()->DLM_LoadQueue();

     //Open the sql connection
     mySqlCon = new MySqlCon();
}
BotController::~BotController(){

     CDownloadManager::Instance()->DLM_Shutdown();

     while( CDownloadManager::Instance()->DLM_ShutdownState() != essSHUTDOWNREADY )
     {
          usleep(100);
     }

     delete CDownloadManager::Instance();

     delete CConnectionManager::Instance();
     delete CHubListManager::Instance();
     
     if ( CFileManager::Instance() )
     {
          delete CFileManager::Instance();
     }

     if ( CQueryManager::Instance() )
     {
          delete CQueryManager::Instance();
     }

     if ( CAsyncDns::Instance() )
     {
          delete CAsyncDns::Instance();
     }

     if ( CConfig::Instance() )
     {
          delete CConfig::Instance();
     }

     if ( CManager::Instance() )
     {
          delete CManager::Instance();
     }

}

void BotController::Start(){

     //All Hubs offline
     mySqlCon->Update(0,"hubConfig","hcStatus='Offline'","hcStatus='Online'");
     //All Users Offline
     mySqlCon->Update(0,"userInfo","uiStatus='0'","uiStatus='1'");

     //Read botConfig from sql connection
     mySqlBot = new MySqlBot(mySqlCon);

     //Initialise some configuration data
     cconfig->SetNick(mySqlBot->GetBotName());    // The bot nick
     cconfig->SetDescriptionTag(FALSE);           //Turns the DCGUI tag OFFF
     cconfig->SetSpeed(mySqlBot->GetBotConnection());  // Bot connection
     cconfig->SetMaxUpload(0);                    // 0 upload slots
     cconfig->SetUserUploadSlots(0);              // 0 upload slots
     cconfig->SetMode(ecmACTIVE);                 // Set Mode Active
     cconfig->SetTCPListenPort(mySqlBot->GetBotTCPport()); // TCP port
     cconfig->SetUDPListenPort(mySqlBot->GetBotUDPport()); // UDP port
     cconfig->SetDescription(mySqlBot->GetBotDescription());

     //Read hubConfig for all Connect hubs
     MYSQL_RES *result = mySqlCon->Query(0,"hubID,hcHost","hubConfig","hcAutoConnect='1'");

     MYSQL_ROW row = mySqlCon->FetchResult(result);
     while(row)
     {
          JoinHub(row[0],row[1]);
          row = mySqlCon->FetchResult(result);
     }
     mySqlCon->FreeRes(result);

     while (hubList.Count() != 0)
     {
        sleep(10);
     }
}
void BotController::AddHub(CString hubid,CString host)
{
     hubList.Add(hubid, (CObject*&)host);
}


void BotController::JoinHub(CString hubid,CString host)
{
     DCClient *dcclient = 0;
     
     if(VERBOSITY & VERBOSE_CONSOLE_OUTPUT)
     {
          cout << "Connecting to hubId=[" << hubid.Data() << "]" << " Host [" << host.Data() << "]"<< endl;
     }

     dcclient = new DCClient(botController,mySqlCon,CString(hubid).asINT(),mySqlBot->GetBotMaster(),mySqlBot->GetBotName());
     // disable send myinfo
     dcclient->SetSendMyinfo(FALSE);
     // disable transfer
     dcclient->HandleTransfer(FALSE);
     hubList.Add(hubid, (CObject*&)dcclient);
     CConnectionManager::Instance()->Connect(host,host,dcclient);
}


void BotController::LeaveHub(DCClient *dcclient,int hubid)
{
     CString *host = 0;

     if(VERBOSITY & VERBOSE_CONSOLE_OUTPUT)
     {
          cout << "DisConnecting hubId=[" << hubid << "]"<< endl;
     }

     if ( hubList.Get(CString().setNum(hubid), (CObject*&)host ) == 0 )
     {
          hubList.Remove(CString().setNum(hubid));
     }
}
