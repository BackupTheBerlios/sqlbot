/***************************************************************************
                          hubinterface.cpp  -  description
                             -------------------
    begin                : Sun Oct 26 2003
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
#include <stdlib.h>
#include "dcclient.h"
#include "hubinterface.h"
using std::cout;
using std::endl;

/** */
HubInterface::HubInterface(class DCClient * hidcclient,MySqlHub *hubCfg,eHubInterface interface)
{
     	dcclient     = hidcclient;
     	hubInterface = interface;
     	hubConfig    = hubCfg;
}

/** */
HubInterface::~HubInterface()
{
}


bool HubInterface::HubVersion(CString msg)
{

     CString hubSoft,hubVersion="";
     if(msg.Find("ub is running version") != -1)
     {
          int i1,i2,i3;
          i1=msg.Find("version ",0,FALSE);
          i2=msg.Find(" of ",0,FALSE);
          if(msg.Find("Open DC Hub") != -1)
          {
               hubSoft = "Open DC Hub";
               hubVersion=msg.Mid(i1+8,msg.Length()-i2-10);
               hubInterface= ehiOpendcHub;
          }
          else if (msg.Find("VerliHub") != -1)
          {
               hubSoft = "Verlihub";
               i3 = msg.Find("of VerliHub");
               hubVersion=msg.Mid(i1+8,msg.Length()-i1-i3-5);
               hubInterface = ehiVerlihub;
          }
          else
          {
               dcclient->SendConsole("WARNING",dcclient->GetBotNick(),"Unrecognised Hub, Not supported at this time");
               exit(0);
          }
          hubConfig->SetHubSoftware(hubSoft);
          hubConfig->SetHubVersion(hubVersion);
          dcclient->SendConsole("HUB SOFTWARE",hubSoft,hubVersion);
          return(TRUE);
     }

     return(FALSE);
}

/** */
CString HubInterface::KickBanMsgs( eKickBanTypes kickBanType, UserInfo * info, CClientRule * rule )
{
	CString message;
	
     	switch (kickBanType)
     	{
          	case ehikb_UnTagged:
          	{
               		message = "Untagged Client/ Unknown Client not recognised.";
			
          		break;
          	}
		
          	case ehikb_Share:
          	{
			message = "Failed Min Share";
			
			if ( rule )
			{
				message += " [" + CUtils::GetSizeString(info->GetShare(),euAUTO) + "]" +
					   " requires [" + CUtils::GetSizeString(rule->m_nMinShared,euAUTO) +
					   "] Short by [" + CUtils::GetSizeString(rule->m_nMinShared-info->GetShare(),euAUTO) +"].";
			}
			
          		break;
          	}
		
          	case ehikb_MxHubs:
          	{
               		message = "Failed Max Hubs";
			
			if ( rule )
			{	
				message += " [" + CString().setNum(info->GetHubs()) +
                    			   "] Max[" + CString().setNum(rule->m_nMaxHubs) +
                    			   "]Hubs. Over by[" + CString().setNum(info->GetHubs()-rule->m_nMaxHubs) +
                    			   "]Hubs.";
			}

          		break;
          	}

          	case ehikb_MxSlots:
          	{
               		message = "Failed Max Slots"; 
			
			if ( rule )
			{
				message += " [" + CString().setNum(info->GetSlots()) +
                    			"] requires Max of[" + CString().setNum(rule->m_nMaxSlots) +
                    			"]slots. Over by[" + CString().setNum(info->GetSlots()-rule->m_nMaxSlots) +
                    			"]slots.";
			}

          		break;
          	}
		
		
          	case ehikb_MnSlots:
          	{
               		message = "Failed Min Slots";
			
			if ( rule )
			{
				message += " [" + CString().setNum(info->GetSlots()) +
                    			"] requires[" + CString().setNum(rule->m_nMinSlots) +
                    			"]slots. Short by[" + CString().setNum(rule->m_nMinSlots-info->GetSlots()) +
                    			"]slots.";
			}

          		break;
          	}
		
          	case ehikb_SlotRatio:
          	{
               		message = "Failed Slot Ratio";
			 
			if ( rule )
			{
				message += " [" + CString().setNum(info->GetSlots()) +
                    			"Slots/" + CString().setNum(info->GetHubs()) +
                    			"hubs=" + CString().setNum((double)info->GetSlots()/(double)info->GetHubs(),3) +
                    			"] Requires a ratio of [" + CString().setNum(rule->m_nSlotHubRatio,3) +
                    			"] Slots/Hubs.";
			}

          		break;
          	}
		
		
          	case ehikb_HackedTag:
          	{
               		message = " Hacked Tag";
			
			break;
          	}
		
          	case ehikb_MinConnection:
          	{
               		message = "Failed Min Speed";
			
			if ( rule )
			{
				// TODO: convert speed
//				message += " [" + info->GetSpeed() +
//                    			"] Requires a[" + rule->m_eMinUserSpeed + "]Connection.";
			}

          		break;
          	}
		
          	case ehikb_BadWord:
          	{
			break;
          	}
		
          	case ehikb_IllegalSearch:
          	{
			break;
          	}
          
	  	case ehikb_IllegalShare:
          	{
			break;
          	}
          
	  	case ehikb_BanNick:
          	{
			break;
          	}
          
	  	case ehikb_Clone:
          	{
			break;
          	}
          
	  	case ehikb_Operator:
          	{
               		message = " Operator request";
			
          		break;
          	}
		
          	default:
		{
               		break;
		}
     	}
	
     	return(message);
}

/** */
bool HubInterface::Kick( eKickBanTypes kickBanType, UserInfo * info, CClientRule * rule )
{
     if (info == 0){return(FALSE);}

     UserInfo * botInfo = dcclient->GetNickInfo(dcclient->GetBotNick());

     CString message = KickBanMsgs(kickBanType,info,rule);

     if (botInfo->GetIsAdmin())
     {
          info->IncKickTotal();
                    
          if (info->GetKickTotal() < dcclient->GetHubConfig()->GetKickB4SBan())
          {
                message += " " + CString().setNum(dcclient->GetHubConfig()->GetKickB4SBan() - info->GetKickTotal()) + "/" +
                    CString().setNum(dcclient->GetHubConfig()->GetKickB4SBan()) +
                    " warning Kicks remaining.";

          
               dcclient->SendPrivateMessage( dcclient->GetBotNick(), info->GetNick(), hubConfig->GetHubName() + 
                    ": You are being kicked because: " + message );

               // Show this kick based on verbosity setting
               if(dcclient->GetHubConfig()->GetHubVerboseKick() & kickBanType)
               {
                    dcclient->SendChat(dcclient->GetBotNick(), " Kicking " + info->GetNick() + " because: " + message);
               }
               
               //Send the kick
               dcclient->SendKick(info->GetNick());
          }
          else    //KickBan them
          {
               dcclient->SendKick(info->GetNick());
               Ban(kickBanType,info,rule);
          }

          info->WriteUserInfo();
     }
     else
     {
          dcclient->GetLogger()->WriteSysLog(hubConfig->GetHubName()+ "  WARNING: Require Operator/Admin in this hub.");
          dcclient->SendConsole("WARNING",dcclient->GetBotNick(),"Need Operator/Admin in this Hub.");
          return(FALSE);
          
     }
     dcclient->GetLogger()->WriteSysLog(hubConfig->GetHubName() + info->GetNick()  + message); 
     dcclient->SendConsole("KICKED",info->GetNick(),message);
     return(TRUE);
}

/** */
void HubInterface::Ban( eKickBanTypes kickBanTypes, UserInfo * info, CClientRule * rule )
{
     if (info == 0){return;}

     CString message = KickBanMsgs(kickBanTypes,info,rule);
     
     if (info->GetBanTotal() < dcclient->GetHubConfig()->GetKickB4SBan())
     {
          TimeBan(kickBanTypes,euibfSBan,info,info->GetNick(),message);
     }
     else
     {
          TimeBan(kickBanTypes,euibfLBan,info,info->GetNick(),message);
     }
}
     
void HubInterface::TimeBan(eKickBanTypes kickBanTypes,euiBanFlag banFlag, UserInfo * info, CString nick, CString reason)
{
     CString nickBanCmd = "";
     CString ipBanCmd = "";
     CString banTime = "";
     CString banMsgTime = "";
     
     if (info == 0){return;}

     if (banFlag == euibfSBan)
     {
          banTime = CString().setNum(hubConfig->GetHubShortBan());
          banMsgTime = CString().setNum(hubConfig->GetHubSBan()) + hubConfig->GetHubSBanMultiplier();
     }
     else
     {
          banTime = CString().setNum(hubConfig->GetHubLongBan());
          banMsgTime = CString().setNum(hubConfig->GetHubLBan()) + hubConfig->GetHubLBanMultiplier();
     }

     switch (hubInterface)
     {
          case ehiOpendcHub:
          {
               if(!(info->GetIp().IsEmpty()))
               {
                    ipBanCmd = "!ban " + info->GetIp() + " " + banTime +"s";
                    dcclient->SendChat(dcclient->GetBotNick(),ipBanCmd); //Do a Timed Ban by IP on the hub
               }
               nickBanCmd = "!nickban " + nick + " " + banTime +"s";
               dcclient->SendChat(dcclient->GetBotNick(),nickBanCmd); //Do a Timed Ban by nick on the hub

               break;
          }
          case ehiVerlihub:
          {
               nickBanCmd = "!tempban " + nick + " " + banTime +"s " + reason;
               dcclient->SendChat(dcclient->GetBotNick(),nickBanCmd); //Do a Timed Ban by nick on the hub

               break;
          }
          default:
          {
               cout << "Ban CMD: ERROR Hub not recognised" << endl;
               return;
          }
     }
     info->SetBanFlag(banFlag);
     info->IncBanTotal();
     info->SetBanTime();
     info->SetBanExpTime(CString(banTime).asINT());

     dcclient->SendPrivateMessage(dcclient->GetBotNick(), nick.Data(),
          hubConfig->GetHubName() + ": You are being Banned for " + banMsgTime + " because: " + reason );
     if(hubConfig->GetHubVerboseBan() & kickBanTypes)
     {
          dcclient->SendChat(dcclient->GetBotNick(), " Banned " + nick + " for " + banMsgTime + " because: " + reason);
     }

     dcclient->SendConsole("BANNED",nick,"For " + banMsgTime + " Becasue: " + reason);
}
void HubInterface::UnBan( UserInfo * info, CString nick)
{
     CString nickBanCmd = "";
     CString ipBanCmd = "";

     if (info == 0){return;}

     switch (hubInterface)
     {
          case ehiOpendcHub:
          {
               if(!(info->GetIp().IsEmpty()))
               {
                    ipBanCmd = "!unban " + info->GetIp();
                    dcclient->SendChat(dcclient->GetBotNick(),ipBanCmd); //UnBan by IP on the hub
               }
               nickBanCmd = "!unnickban " + nick;
               dcclient->SendChat(dcclient->GetBotNick(),nickBanCmd); //Un by nick on the hub
               break;
          }
          case ehiVerlihub:
          {
               nickBanCmd = "!unban " + nick;
               dcclient->SendChat(dcclient->GetBotNick(),nickBanCmd); //UnBan by nick on the hub

               break;
          }
          default:
          {
               cout << "UnBan CMD: ERROR Hub not recognised" << endl;
               return;
          }
     }
     info->SetBanFlag(euibfNone);
     info->SetBanExpTime(0);
     dcclient->SendChat(dcclient->GetBotNick(), " UnBanned " + nick);
     dcclient->SendConsole("UNBANNED",nick);
}

//This function is sent to the hub, Chat handler looks for the reply coming back
void HubInterface::CallForUserIp(CString nick)
{
	UserInfo * botInfo = dcclient->GetNickInfo(dcclient->GetBotNick());
	
     if (botInfo->GetIsAdmin())
     {
          dcclient->SendChat(dcclient->GetBotNick(),"!getip " + nick );
     }
     else
     {
          dcclient->GetLogger()->WriteSysLog(hubConfig->GetHubName()+ "  WARNING: Require Operator/Admin in this hub");
          dcclient->SendConsole("WARNING",dcclient->GetBotNick(),"Need Operator/Admin in this Hub");
     }
}
bool HubInterface::SetUserIp(CString msg)
{
     CString   nick="",
               nickClone="",
               ip="";
               
     int i1,i2,i3;
     i1 = msg.Find(" has ip:",0);   //for odch
     i2 = msg.Find(" IP: ",0);   //for verlihub
     i3 = msg.Find("User:",0);   //for verlihub
     if (i1 != -1)
     {    //for odch
          nick = msg.Left(i1);
          ip = msg.Right(msg.Length()-i1-9);
     }
     else if ((i2 != -1) && (i3 != -1))
     {    //for verlihub
          nick = msg.Mid(6,i2-6);
          ip = msg.Mid(i2+5,msg.Length()-i2-6);
     }
     else
     {
          return(FALSE);     //Not ip packet
     }
     
     UserInfo *info = dcclient->GetNickInfo(nick); //Get user
     if (info == 0){ return 0; }

     if (nick == dcclient->GetBotNick())
     {    /* This is the bot reporting its IP*/
          info->SetIp(ip); //Set the bots IP and quit
          return(TRUE);
     }
     info->SetIp(ip); //Set ip in users info

     //Check this ip is not already in use by a user online.
     MYSQL_RES * result = dcclient->GetMySqlCon()->Query(dcclient->GetHubId(),"uiNick","userInfo","uiIp='"+ip+"' AND uiStatus='Online'");
     MYSQL_ROW row = dcclient->GetMySqlCon()->FetchResult(result);
     while(row)
     {
          nickClone = row[0];
          row = dcclient->GetMySqlCon()->FetchResult(result);
     }
     dcclient->GetMySqlCon()->FreeRes(result);

     if (nickClone.IsEmpty()) { return FALSE; }
     
     UserInfo *infoClone = dcclient->GetNickInfo(nickClone); //Get clone info
     if (infoClone == 0){ return 0; }
     
     /* Check that the nicks are different, it may be a duplicate
               request for IP of same user*/
     if(  (nick != dcclient->GetBotNick()) &&               // Not the bot
          (nick != nickClone) &&           // The two nicks are different
          (!info->GetIsAdmin()) &&           // The user is not an Admin
          (!infoClone->GetIsAdmin()) &&      // Not a clone of an Admin
          (hubConfig->GetHubCloneCheck())    // Clone checking is enabled
          )
     {
          /*It might be a clone*/
          if ( (info->GetShare() == infoClone->GetShare()) &&    /* The shares are the same */
               (info->GetClient() == infoClone->GetClient()) //&& /* The client is the same */
               )
          {
               cout << "Clone" << infoClone->ShowUserInfo().Data() << endl;
               cout << "Clone" << info->ShowUserInfo().Data() << endl;
               
               dcclient->GetLogger()->WriteSysLog(nick + "is a Clone of [" + nickClone + "]" );
                         //Kick clones
               Kick(ehikb_Clone,info,0);
               Kick(ehikb_Clone,infoClone,0);               
               return(TRUE);
          }
     }
     cout << "IP " << infoClone->ShowUserInfo().Data() << endl;

     return(TRUE);
}
