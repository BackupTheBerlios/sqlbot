/***************************************************************************
                          dcclient.cpp  -  description
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

#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

#include <iostream>
#include <stdlib.h>
#include "dcclient.h"
#include "globalconf.h"

using namespace std;
using std::cout;
using std::endl;

int DCClient::hubCount = 0;
CStringList DCClient::hubList;

/** */
DCClient::DCClient(BotController * bController,MySqlCon * mySql,int hubId,CString s_botMaster,CString s_botNick)
	: CClient()
{
     dcHubId = hubId;
     botController = bController;
     MySql = mySql;
     botMaster = s_botMaster;
     botNick = s_botNick;
     chatFloodCounter = 0;
     nickList = new CStringList();
     opList = new CStringList();
     vipList = new CStringList();
     hubConfig = new MySqlHub(hubId,MySql);
     logger = new Log(dcHubId,MySql);
     interface = new HubInterface(this,hubConfig,ehiUnkown);
     hubCount++;
     DCClient * client = this;
     hubList.Add(CString().setNum(dcHubId),(CObject*&)client);
}

/** */
DCClient::~DCClient()
{
     hubCount--;
     delete opList;
     delete nickList;
     delete vipList;
     delete hubConfig;
     delete logger;
     delete interface;
     hubList.Remove(CString().setNum(dcHubId));
     Disconnect();
}

/* Callback function gives Msgs recevied from the hub to the client*/
int DCClient::DC_CallBack( CObject * Object )
{
	CDCMessage *DCMsg;
     
     DCMsg = (CDCMessage*) Object;

     switch (DCMsg->m_eType)
     {
          case DC_MESSAGE_CONNECTION_STATE:
          {
               CMessageConnectionState *msg = (CMessageConnectionState*) Object;
               hubConfig->LoadHubConfig();
               if ( msg->m_eState == estCONNECTED )
               {
                    
                    chatFloodCounter = 0;
                    SendConsole("Connected",hubConfig->GetHubHost());
                    MySql->Update(dcHubId,"hubConfig","hcStatus='Online'","");
                    // init userlist
                    NickList(0);
               }
               else if ( msg->m_eState == estCONNECTIONTIMEOUT )
               {
                    logger->WriteSysLog("Connection timeout:"+hubConfig->GetHubHost());
               }
               else if ( msg->m_eState == estSOCKETERROR )
               {
                    logger->WriteSysLog("Error: '"+msg->m_sMessage + "':" + hubConfig->GetHubHost());                    
               }
               else if ( msg->m_eState == estDISCONNECTED )
               {
                    chatFloodCounter = 0;
                    MySql->Update(dcHubId,"hubConfig","hcStatus='Offline'","");
                    SendConsole("Disconnected",hubConfig->GetHubHost());
		    // reconnect ???
	       }
               break;
          }
          case DC_MESSAGE_LOCK:
          {
               Lock((CMessageLock *) Object );
               break;
          }
          case DC_MESSAGE_VALIDATEDENIDE:
          {
               SendConsole("Nick in Use",hubConfig->GetHubHost());

               Disconnect();
               break;
          }

          case DC_MESSAGE_NICKLIST:
          {
               NickList( (CMessageNickList *) Object );
               break;
          }

          case DC_MESSAGE_OPLIST:
          {
               OpList( (CMessageNickList *) Object );
               break;
          }

          case DC_MESSAGE_REVCONNECTTOME:
          {
               CMessageRevConnectToMe * msg = (CMessageRevConnectToMe*) Object;
               SendPrivateMessage(GetNick(), msg->m_sDstNick.Data(),
                    "Im a bot I dont have a filelist. Remove me from your Queue");
               break;
          }

          case DC_MESSAGE_CHAT:
          {
               Chat( (CMessageChat *) Object);
               break;
          }

          case DC_MESSAGE_MYINFO:
          {
               MyInfo( (CMessageMyInfo *) Object );
               break;
          }

          case DC_MESSAGE_QUIT:
          {
               Quit( (CMessageQuit *) Object );
               break;
          }

          case DC_MESSAGE_SEARCH:
          {
               Search( (CMessageSearch *) Object );
               break;
          }

          case DC_MESSAGE_HELLO:
          {
               Hello((CMessageHello *) Object);
               break;
          }

          case DC_MESSAGE_FORCEMOVE:
          {
               SendConsole("Force Move",hubConfig->GetHubHost());
               break;
          }

          case DC_MESSAGE_HUBNAME:
          {
               CMessageHubName * msg = (CMessageHubName*) Object;
               GetHubConfig()->SetHubName(msg->m_sHubName);
               break;
          }

          case DC_MESSAGE_HUB_TOPIC:
          {
               break;
          }

          case DC_MESSAGE_PRIVATECHAT:
          {
               PrivateChat((CMessagePrivateChat*) Object);
               break;
          }

          case DC_MESSAGE_GETPASS:
          {

               //Read hubConfig for all Connect hubs
               SendPass(hubConfig->GetHubPwd().Data());
               SendConsole("Nick Registerd","Sent Password");
               
               break;
          }

          case DC_MESSAGE_BADPASS:
          {
               // Invalid password sent
               SendConsole("Bad Password Sent",hubConfig->GetHubHost());

               break;
          }
          default:
          {
               break;
          }
     }

     if ( Object )
          delete Object;
 
     return 0;
}



/** nicklist */
void DCClient::NickList( CMessageNickList * MessageNickList )
{
     CString * nick = 0;
     UserInfo * info = 0;
     
     if ( MessageNickList )
	{
          while ( (nick=MessageNickList->m_NickList.Next(nick)) != 0 )
          {
               if ( nickList->Get(*nick, (CObject*&)info ) == -1 )
               {
                    //int hubID,class MySqlCon * mySql,CString nick
                    info = new UserInfo(dcHubId,MySql,*nick);
                    if (info == 0){ return;}

                    info->ReadUserInfo();
                    nickList->Add(*nick,info);
               }
          }
     }
     cout << endl ;
}

/** oplist */
void DCClient::OpList( CMessageNickList * MessageOpList )
{
    return;
    CString * opNick = 0;
    UserInfo * info = 0;
    if ( MessageOpList )
    {
        while ( (opNick=MessageOpList->m_NickList.Next(opNick)) != 0 )
        {
	        if ( opList->Get(*opNick, (CObject*&)info ) == -1 )
            {
                    if (info == 0){return;}

                    nickList->Get(*opNick, (CObject*&)info);
                    if (info == 0){ return;}
                    opList->Add(*opNick,info);
		    cout << "Added [" << opNick->Data() << "] To op list. Info" << info << endl;
               }
          }
     }
		    cout << "Added [" << opNick->Data() << "] To op list. Info" << info << endl;
}
CString DCClient::GetOpList()
{
        CString sOpList = "";
        CString Nick;
        
        UserInfo * info = 0;
        while( opList->Next((CObject*&)info) == 1 )
        {
            if(info == 0){return sOpList;}
            sOpList += info->GetNick();
            sOpList += ", ";
        }
        return (sOpList);
}

CString DCClient::GetVipList()
{
        CString sVipList = "";
        UserInfo * info = 0;
            while( vipList->Next((CObject*&)info) == 1 )
            {
                if(info == 0){return sVipList;}
                if (info->GetUserLevel() == euiVIP)
                {
                    sVipList += info->GetNick();
                    sVipList += ", ";
                }
            }
            return (sVipList);
}

// User just joined hub
void DCClient::Hello( CMessageHello * MessageHello )
{
     CString hubName = GetHubName(),
               nick = MessageHello->m_sNick;
     UserInfo * info = 0;
          
     if ( nickList->Get( nick, (CObject*&)info ) == -1 )
     {
          info = new UserInfo(dcHubId,MySql,nick);
          if (info == 0){ return; }

          info->ReadUserInfo();
          nickList->Add(nick,info);

          /*Hub is full*/
          if(GetUserCount() > GetHubConfig()->GetHubMaxUsers())
          {
               /*Redirect users, but let in VIPs and OPs*/
               if (info->GetUserLevel() == euiUser)
               {
                    if(!(GetHubConfig()->GetHubRedirectHost().IsEmpty()))
                    {
                         ForceMove(MessageHello->m_sNick,
                              "Hub is Full" + CString().setNum(GetHubConfig()->GetHubMaxUsers())
                              ,GetHubConfig()->GetHubRedirectHost());
                         SendConsole("REDIRECT",nick,"Hub is Full");
                    }
               }
          }
          SendConsole("JOIN",nick);
          
	  if ( GetHubConfig()->GetHubMotd() != "" )
	  	SendPrivateMessage( GetBotNick(), nick.Data(), GetHubConfig()->GetHubMotd() );

          //Announce user level joins
          CString joinAnnouncement = "";
          int userVerbose = 0;
          switch (info->GetUserLevel())
          {
               case euiVIP:
               {
                    joinAnnouncement = "VIP ";
                    userVerbose = euiVerbVIP;
                    vipList->Add(nick,info); // add to vip list
                    break;
               }
               case euiOp:
               {
                    joinAnnouncement =  "Operator ";
                    if ( opList->Get( nick, (CObject*&)info ) != -1 )
                  {
                        opList->Add(nick,info);
                  }
                    userVerbose = euiVerbOp;
                    break;
               }
               case euiOpAdmin:
               {
                    joinAnnouncement =  "Op-admin ";
                    if ( opList->Get( nick, (CObject*&)info ) != -1 )
                  {
                        opList->Add(nick,info);
                  }
                    userVerbose = euiVerbOpAdmin;
                    break;
               }
               case euiBotMaster:
               {
                    joinAnnouncement =  "My Master ";

                    if ( opList->Get( nick, (CObject*&)info ) != -1 )
                  {
                        opList->Add(nick,info);
                  }

                    userVerbose = euiVerbBotMaster;
                    break;
               }
               case euiBot:
               {
                    joinAnnouncement =  "A Bot called ";
                    userVerbose = euiVerbBot;
                    break;
               }

               default:
                    joinAnnouncement =  "";
                    userVerbose = euiVerbUser;
                    break;
          }
          if(GetHubConfig()->GetHubVerboseJoin() & userVerbose)
          {
               SendChat( GetNick(), joinAnnouncement + nick + " Just Joined");
          }
     }
}
// User just left hub
void DCClient::Quit( CMessageQuit * MessageQuit )
{
     CString   hubName = GetHubName(),
               nick = MessageQuit->m_sNick,
               ip = "";
     UserInfo * info;

     if ( nickList->Get( nick, (CObject*&)info ) == 0 )
     {
          if (info == 0){return;}

          CString joinAnnouncement = "";
          switch (info->GetUserLevel())
          {
               case euiVIP:
               {
                    joinAnnouncement = "VIP ";
                    break;
               }
               case euiOp:
               {
                    joinAnnouncement =  "Operator ";
                    break;
               }
               case euiOpAdmin:
               {
                    joinAnnouncement =  "Op-admin ";
                    break;
               }
               case euiBotMaster:
               {
                    joinAnnouncement =  "My Master ";
                    break;
               }
               default:
                    joinAnnouncement =  "";
                    break;
          }
          if(GetHubConfig()->GetHubVerboseJoin() & info->GetUserLevel())
          {
               SendChat( GetNick(), joinAnnouncement + nick + " Just Left Us");
          }
                    
          info->SetStatus(euisOffline);
          info->unlockSqlUser(); 
          info->WriteUserInfo();
          ip = info->GetIp();

          if(ip.IsEmpty())
               SendConsole("PARTED",nick);
          else
               SendConsole("PARTED",nick,ip);          

          nickList->Remove(nick);   //Delete from online
          if ( vipList->Get( nick, (CObject*&)info ) == 0 )
          {
                vipList->Remove(nick); // remove to vip list
          }

          
          if ( opList->Get( nick, (CObject*&)info ) == 0 )
          {
               opList->Remove(nick);
          }
     }
}
/** myinfo */
void DCClient::MyInfo( CMessageMyInfo * MessageMyInfo )
{
     CString s,
          nick = MessageMyInfo->m_sNick,
          hubName = GetHubName();
	  
     UserInfo * info = 0;
     UserInfo * userinfo = 0;
     if ( nickList->Get( nick, (CObject*&)info ) == -1 )
          {return;}

     if (info == 0){return;}

     if ( MessageMyInfo->m_bOperator )
     {
            if ( opList->Get(nick, (CObject*&)userinfo ) == -1 )
            {
                opList->Add(nick,info);
            }
     }

     info->SetIsAdmin(MessageMyInfo->m_bOperator);

     info->SetSpeed(MessageMyInfo->m_sUserSpeed);

     info->SetShare(MessageMyInfo->m_nShared);

          
     switch(MessageMyInfo->m_eAwayMode)
     {
          case euamAWAY:
          {
               info->SetIsAway(euiiaAway);
               break;
          }
          default:
         {
               info->SetIsAway(euiiaOnline);
               break;
          }
     }

	// set user client version
	info->UserClientVersion(MessageMyInfo->m_eClientVersion);

	switch(MessageMyInfo->m_eClientVersion)
	{
		case eucvDCPP:
		{
			info->SetClient("++"); //DC++
			break;
		}
		case eucvDCGUI:
          	{
               		info->SetClient("DCGUI"); //DCGUI-QT
               		break;
          	}
          	case eucvNMDC:
          	{
               		info->SetClient("NMDC"); //NMDC
               		break;
          	}
          	case eucvDCTC:
          	{
               		info->SetClient("DCTC"); 
               		break;
          	}
	  	case eucvNONE:
	  	{
               		info->SetClient("NOTAG");
               		break;
	  	}
          	default:
          	{
               		info->SetClient("UNKNOWN");
               		break;
          	}
     	}
	
     	if (MessageMyInfo->m_sComment != "")
	{
		info->SetDescription(MessageMyInfo->m_sComment);
	}

     	// Set Tag
     	info->SetTag(MessageMyInfo->m_sVerComment);
     	interface->CallForUserIp(nick);  //Request ip from hub

     if(VERBOSITY & VERBOSE_CONSOLE_INFO_OUTPUT)
     {
          SendConsole("INFO",
               "Nick[" + info->GetNick() +
               "] Operator[" + CString().setNum(info->GetIsAdmin()) +
               "] Away[" + CString().setNum(info->GetIsAway()) +
               "] Client[" + info->GetClient() +
               "] Share[" + CString().setNum(info->GetShare()) +
               "][" + CUtils::GetSizeString(info->GetShare(),euAUTO) +
               "] Speed[" + info->GetSpeed() +"]" );
     }
     if(info->GetBanFlag() == euibfPLBan)
     {
          interface->TimeBan(ehikb_Operator,euibfPLBan,info,nick,"Unwelcome Visitor Ban Reapplied");
          SendKick(nick);
          return;
     }

     if (info->GetUserLevel() == euiUser)
     {
          if(hubConfig->GetHubEnableTagCheck())
          {
               ClientCheck(info,nick);
          }
     }
     else
     {
         SendConsole("CLIENT CHECKS SKIPPED",nick,"User is Level " + CString().setNum(info->GetUserLevel()));
     }
     info->WriteUserInfo(); //We have all but IP about, the write the record
}
/** */
void DCClient::PrivateChat( CMessagePrivateChat * MessageChat)
{
    UserInfo * info = GetNickInfo(MessageChat->m_sSrcNick);
    if(info == 0){return;}
    
    // Check for Command
    if (CheckForCommand(emsPM,MessageChat->m_sSrcNick, MessageChat->m_sMessage ) == TRUE)
    {
         SendConsole("COMMAND",MessageChat->m_sSrcNick,MessageChat->m_sMessage);
    }
    else if (info->GetIsAdmin())  //If its an op Send it to OpChat
    {
        CString Nick = "";

        CString opMsg = "<" + MessageChat->m_sSrcNick + ">  " + MessageChat->m_sMessage;
        //CString opMsg = MessageChat->m_sMessage;
 	UserInfo * info = 0;
        while( opList->Next((CObject*&)info) == 1 )
        {
	       if(info == 0){return;}
               Nick = info->GetNick();

	       if (Nick != MessageChat->m_sSrcNick &&
               Nick != GetBotNick() &&
               info->GetUserLevel() != euiBot)
               {
                    SendPrivateMessage( GetBotNick(), Nick.Data(),opMsg);
               }
          }
     }
}
void DCClient::SendConsole(CString out1,CString out2,CString out3)
{
     if (VERBOSITY & VERBOSE_CONSOLE_OUTPUT)
     {
          cout << hubConfig->GetHubName().Data() << ": "
               << out1.Data() << ": "
               << out2.Data() << " ["
               << out3.Data() << "]" << endl;
     }
     logger->WriteSysLog(hubConfig->GetHubName() + ": " + out1 + ": " + out2 + " [" + out3 + "]");


}
void DCClient::SendConsole(CString out1,CString out2)
{
     if (VERBOSITY & VERBOSE_CONSOLE_OUTPUT)
     {
          cout << hubConfig->GetHubName().Data() << ": "
               << out1.Data() << ": "
               << out2.Data() << endl;
     }
     logger->WriteSysLog(hubConfig->GetHubName() + ": " + out1 + ": " + out2 );

    
}
/** Hub Chat */
void DCClient::Chat( CMessageChat * MessageChat )
{
     CString hubName = GetHubName();

     //Check if this is return for User IP request
     if (interface->SetUserIp(MessageChat->m_sMessage) == TRUE) { return;}
     else if(interface->HubVersion(MessageChat->m_sMessage) == TRUE) {return;}
     else if (CheckForCommand(emsCHAT,MessageChat->m_sNick, MessageChat->m_sMessage ) == TRUE)
     {
          SendConsole("COMMAND",MessageChat->m_sNick,MessageChat->m_sMessage);
     }
     else  // Geneal Chat
     {
          if ( chatFloodMessage.m_sMessage == MessageChat->m_sMessage
               && chatFloodMessage.m_sNick == MessageChat->m_sNick )
          {
               // increment flood counter
               chatFloodCounter++;
          }
          else
          {
               // update message and reset counter
               chatFloodMessage.m_sMessage = MessageChat->m_sMessage;
               chatFloodMessage.m_sNick    = MessageChat->m_sNick;
               chatFloodCounter = 0;
          }

          if ( chatFloodCounter >= 5 )
          {
               // only one flood message
               if ( chatFloodCounter == 5 )
               {
                    SendChat( GetBotNick(), GetBotNick() + " Detected a possible flood from "
                              + MessageChat->m_sNick.Data() + " " );
                    SendConsole("FLOOD",MessageChat->m_sNick);          
               }
               // don't show the flood messages
               MessageChat = 0;
          }
          if (MessageChat)
          {
               UserInfo * info =0;
               info = GetNickInfo(MessageChat->m_sNick);
               if (info == 0){return;}
               info->IncSayTotal();
               
               // Dont log bot chat
               if (MessageChat->m_sNick != GetBotNick())
               {
                    logger->WriteChatLog(MessageChat->m_sNick,MessageChat->m_sMessage);
               }
#if MULTIHUB_CHAT
               //Send chat to all connect hubs
               DCClient *remoteHub = 0;
               while( hubList.Next((CObject*&)remoteHub) == 1 )
               {
                    if(remoteHub == 0){return;}

                    if ((remoteHub->GetHubId() != GetHubId()) &&
                        (MessageChat->m_sNick != GetBotNick()))
                    {
                         remoteHub->SendChat( GetBotNick(), GetHubName() + "> " + MessageChat->m_sNick + ": " + MessageChat->m_sMessage );
                    }
               }
#endif
          }
     }
}
//** Hub searches */
void DCClient::Search( CMessageSearch * MessageSearch )
{
     CString hubName = GetHubName();
     CString nick,ip,search;
     UserInfo * info =0;

     if(MessageSearch->m_nPort != 0)
     {
           ip =  MessageSearch->m_sSource;

           nick = GetNickFromIp(ip);
           if (nick.IsEmpty()){return;}
           
           info = GetNickInfo(nick);

           if (info == 0){return;}
           info->IncSearchTotal(); //inc search count
     }
     else
     {
          nick =  MessageSearch->m_sSource;
          info = GetNickInfo(nick);
          
          if (info == 0){ return;}

          info->IncSearchTotal(); //inc search count
          ip = info->GetIp();
     }
     logger->WriteSrchLog(nick,MessageSearch->m_sString.Data(),
          MessageSearch->m_eFileType);
}
//** Lock String */
void DCClient::Lock(CMessageLock * MessageLock )
{
     return;    
     cout << "Lock :" 
          << "\tPk: " << MessageLock->m_sPK.Data() << "\r\n "
          << "\tData: " << MessageLock->m_sData.Data() << "\r\n " << endl;
     if (!MessageLock->m_sVersionString.IsEmpty())
     {
          cout << "\tVersion: " << MessageLock->m_sVersionString.Data() << "\r\n" << endl;
          cout << "\tVersion Maj: " << MessageLock->m_nVersionMajor << "\r\n"  << endl;
          cout << "\tVersion Min: " << MessageLock->m_nVersionMinor << "\r\n"  << endl;
          cout << "\tVersion Patch: " << MessageLock->m_nVersionPatch << "\r\n" << endl;
          cout << "\tExp: " << MessageLock->m_bExtProtocol << "\r\n" << endl;
     }
}
bool DCClient::ForceMove( CString nick, CString message, CString host )
{
     UserInfo * info = GetNickInfo(GetBotNick());
     
     if (info == 0){return 0;}
     if (info->GetIsAdmin())
     {
          SendOpForceMove( nick, host, message);
     }
     else
     {
          logger->WriteSysLog(hubConfig->GetHubName()+ "  WARNING: Require Operator/Admin in this hub");
          cout << hubConfig->GetHubName().Data() << "  WARNING: Not Operator/Admin in this hub" << endl;
     }
     return(TRUE);
}

UserInfo * DCClient::GetNickInfo(CString nick){
     UserInfo * nickInfo =0;
     nickList->Get(nick, (CObject*&)nickInfo);
     if (nickInfo == 0){ return 0; }

     return nickInfo;
}
CString DCClient::GetNickFromIp(CString ip){

     UserInfo * info = 0;
     while( nickList->Next((CObject*&)info) == 1 )
     {
          if(info == 0){return "";}

          if (info->GetIp() == ip)
          {
               return(info->GetNick());
          }
     }
     return "";
}
bool DCClient::CheckForCommand(eMsgSrc msgSrc,CString nick, CString cmd)
{
     if ( (cmd != "") && (cmd.Left(1) == '+') )
     {
          botc.ParseBotCommands(msgSrc,this,nick,cmd);

          return(TRUE);
     }
     return(FALSE);
}                                                

/** */
bool DCClient::ClientCheck(UserInfo *info,CString nick)
{
	bool res = FALSE;

	if (!info)
		return FALSE;

	//Tag checks disabled
	if (!hubConfig->GetHubEnableTagCheck())
	{
          	return(TRUE);
     	}

	//Do not Check Admins
	if(info->GetIsAdmin())
	{
		return(TRUE);
	}
	
	CClientRule * rule;
	CClientRule * matchrule;
	CClientRule * defaultrule = hubConfig->GetDefaultClientRule();;
	CStringList * rules = hubConfig->GetClientRules();
	
	if ( !rules )
		return(TRUE);
	if ( rules->Count() == 0 )
		return(TRUE);
	
	rule = 0;
	matchrule = 0;

	// walking the rule list
	while( rules->Next((CObject*&)rule) )
	{
		if ( info->UserClientVersion() == rule->m_eClientVersion )
		{
			// compare client min version
			if ( (rule->m_MinVersion.m_nVersionMajor != 0) ||
			     (rule->m_MinVersion.m_nVersionMinor != 0) ||
			     (rule->m_MinVersion.m_nVersionPatch != 0) )
			{
				if ( info->ClientVersion()->m_nVersionMajor < rule->m_MinVersion.m_nVersionMajor )
					continue;
				if ( (info->ClientVersion()->m_nVersionMajor == rule->m_MinVersion.m_nVersionMajor) &&
				     (info->ClientVersion()->m_nVersionMinor < rule->m_MinVersion.m_nVersionMinor) )
					continue;
				if ( (info->ClientVersion()->m_nVersionMajor == rule->m_MinVersion.m_nVersionMajor) &&
				     (info->ClientVersion()->m_nVersionMinor == rule->m_MinVersion.m_nVersionMinor) &&
				     (info->ClientVersion()->m_nVersionPatch < rule->m_MinVersion.m_nVersionPatch) )
					continue;
			}

			// compare client max version
			if ( (rule->m_MaxVersion.m_nVersionMajor != 0) ||
			     (rule->m_MaxVersion.m_nVersionMinor != 0) ||
			     (rule->m_MaxVersion.m_nVersionPatch != 0) )
			{
				if ( info->ClientVersion()->m_nVersionMajor > rule->m_MaxVersion.m_nVersionMajor )
					continue;
				if ( (info->ClientVersion()->m_nVersionMajor == rule->m_MaxVersion.m_nVersionMajor) &&
				     (info->ClientVersion()->m_nVersionMinor > rule->m_MaxVersion.m_nVersionMinor) )
					continue;
				if ( (info->ClientVersion()->m_nVersionMajor == rule->m_MaxVersion.m_nVersionMajor) &&
				     (info->ClientVersion()->m_nVersionMinor == rule->m_MaxVersion.m_nVersionMinor) &&
				     (info->ClientVersion()->m_nVersionPatch > rule->m_MaxVersion.m_nVersionPatch) )
					continue;
			}

			printf("Rule found '%s'\n",rule->m_sName.Data());

			// store rule
			matchrule = rule;

			// check client
			res = ClientCheck(info,matchrule);
			
			// client kicked, no more tests
			if ( res == FALSE )
				break;
		}
	}

	// test if we dont have found a rule
	if ( !matchrule )
	{
		// use default rule if exist
		if ( defaultrule )
		{
			res = ClientCheck(info,defaultrule);
		}
		else
		{
			printf("No Rule found !\n");
			res = TRUE;
		}
	}

     	if ( res )
	{
		//Download file list

     		//The client checks ok, make sure ban flag is none
     		info->SetBanFlag(euibfNone);
	}
	
     	return res;
}

/** Check the client tag */
bool DCClient::ClientCheck( UserInfo *info, CClientRule * rule )
{
	bool rulecmd = FALSE;
	eKickBanTypes kickBanTypes;

	// first we send client motd
	if ( rule->m_bMotd && (rule->m_sMotd != "") )
	{
		SendPrivateMessage( GetNick(), info->GetNick(), rule->m_sMotd );
	}
	
	// check for command
	switch(rule->m_nClientCommand)
	{
		case 1:
			interface->Kick(ehikb_None,info,rule);

			return FALSE;
		
		case 2:
			interface->Redirect(ehikb_None,info,rule);

			return FALSE;

		default:
			break;	
	}			

     	// Check min speed
     	if ( hubConfig->ConvertSpeed(info->GetSpeed()) < rule->m_eMinUserSpeed )
     	{
		rulecmd = TRUE;
		kickBanTypes = ehikb_MinConnection;
     	}
	// Check min share
     	else if ( info->GetShare() < rule->m_nMinShared )
     	{
		rulecmd = TRUE;
		kickBanTypes = ehikb_Share;
     	}

	// TODO: Check max share

     	// Check Min Slots
     	else if ( info->GetSlots() < rule->m_nMinSlots )
     	{
		rulecmd = TRUE;
		kickBanTypes = ehikb_MnSlots;
     	}
     	// Check Max Slots
	else if ( (rule->m_nMaxSlots > 0) &&
	          (info->GetSlots() > rule->m_nMaxSlots) )
     	{
		rulecmd = TRUE;
		kickBanTypes = ehikb_MxSlots;
     	}
     	// Check max hubs
     	else if ( (rule->m_nMaxHubs > 0) && 
	     (info->GetHubs() > rule->m_nMaxHubs) )
     	{
		rulecmd = TRUE;
		kickBanTypes = ehikb_MxHubs;
     	}
     	else
     	{
          	// do a simple check to see if x/y/z tags make sense.
          	if( (info->GetUiHubsOp() != 0) &&
              	    (info->GetUiHubsReg() == 0) &&
              	    (info->GetUiHubsNorm() == 0) &&
              	    (info->GetClient() == "++"))
          	{
               		cout << "Invalid tag check Manually inspect "<< info->GetNick().Data() << " tag" << endl;
          	}
     	}

     	/* For Hacked DC++ tags, version 0.24 and above have tags x/y/z,
           clients claiming to be newer than this without x/y/z have been hacked at some point. ASTA LA VISTA BABY*/
     	if ( !rulecmd && (info->UserClientVersion() == eucvDCPP) )
     	{
          	CString minHackedTag = "0.24";
		
          	if (CString(info->GetClientVersion()).asDOUBLE() > CString(minHackedTag).asDOUBLE())
          	{
               		if ((info->GetUiHubs() != 0) &&
                   	(info->GetUiHubsReg() == 0) &&
                   	(info->GetUiHubsNorm() == 0) &&
                   	(info->GetUiHubsOp() == 0))
               		{
				rulecmd = TRUE;
				kickBanTypes = ehikb_HackedTag;
               		}
          	}
     	}
	
     	// Check slot Ratio
     	double userSlotRatio = ((double)info->GetSlots()/(double)info->GetHubs());
	
     	if ( !rulecmd && (userSlotRatio < rule->m_nSlotHubRatio) )
     	{
		rulecmd = TRUE;
		kickBanTypes = ehikb_SlotRatio;
     	}

	if ( rulecmd )
	{
		// check for command
		switch(rule->m_nClientCommand)
		{
			case 1:
				interface->Kick(kickBanTypes,info,rule);

				return FALSE;
		
			case 2:
				interface->Redirect(kickBanTypes,info,rule);

				return FALSE;

			default:
				break;	
		}
	}

	return TRUE;
}

void DCClient::SaveUserList(void)
{
     UserInfo * info =0;
     CString nick;

     SendConsole("EXIT","Saving User List");

     DCClient *remoteHub = 0;
     while( hubList.Next((CObject*&)remoteHub) == 1 )
     {
          if(remoteHub == 0){return;}

          remoteHub->MySql->Update(remoteHub->dcHubId,"hubConfig","hcStatus='Offline'","");
          while( remoteHub->nickList->Next((CObject*&)info) == 1 )
          {
               if(info == 0){continue;}
               nick = info->GetNick();

               info->SetStatus(euisOffline);
               info->unlockSqlUser(); //Unlocks the SQL record
               info->WriteUserInfo();
          }
          remoteHub->Disconnect();
     }
}
