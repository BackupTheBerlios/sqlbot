/***************************************************************************
                          botcommands.cpp  -  description
                             -------------------
    begin                : Mon Oct 13 2003
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
#include "botcommands.h"
#include "dcclient.h"

using std::cout;
using std::endl;

CString BotCommands::GetVersion()
{
     CString version;
     version = VERSION;
     return (version);
}

void BotCommands::SendReply(eMsgSrc src, CString destNick,CString msg)
{
     if (src == emsPM)
          dcclient->SendPrivateMessage(dcclient->GetBotNick(),destNick.Data(),msg);
     else
          dcclient->SendChat(dcclient->GetBotNick(),msg);
}

bool BotCommands::ParseBotCommands(eMsgSrc bcmsgSrc,class DCClient * bcdcclient, CString nick, CString cmd){

     dcclient = bcdcclient;
     msgSrc = bcmsgSrc;
     
     UserInfo *info = dcclient->GetNickInfo(nick);
     if (info == 0){return FALSE;}
     
     if (UserBotCommands(nick,cmd) == TRUE)
          return(TRUE);
     if (info->GetIsAdmin())
     {
          if(OpBotCommands(nick,cmd) == TRUE)
               return(TRUE);
          if (nick == dcclient->GetBotMaster())
          {
               if(MasterBotCommands(nick,cmd) == TRUE)
                    return(TRUE);
          }
     }
//     SendReply(msgSrc,nick,"Command [" + cmd + "] Not recognised");
     return(1);
}

bool BotCommands::UserBotCommands(CString nick, CString cmd)
{
     UserInfo *info = dcclient->GetNickInfo(nick);
     if (info == 0){return FALSE;}
     
     if ( cmd.Left(5) =="+help" )
     {
          CString type = cmd.Mid(6,cmd.Length()-6);
          if ( type.IsEmpty())
          {
               SendReply(emsPM,nick, Help());
          }
          else if ( type == "op" )
          {
               SendReply(emsPM,nick, HelpOp());
	     }
	     else if ( type == "master" )
          {
               SendReply(emsPM,nick, HelpMaster());
          }
          return(TRUE);
     }
     else if ( cmd == "+time" )
     {
          SendReply(msgSrc,nick, "Server Time : " +
                    timeHand.getLogDate() + " " +
                    timeHand.getLogTime()  );
          return(TRUE);
     }
     else if (cmd == "+timeonline" )
     {
          SendReply( msgSrc,nick, info->ShowTimeOnline());
          return(TRUE);
     }
     else if (cmd == "+rules" )
     {
          SendReply(emsPM, nick, Rules());
          return(TRUE);
     }
     else if (cmd == "+myinfo" )
     {
          SendReply(emsPM, nick, info->ShowUserInfo());
          return(TRUE);
     }
     else if (cmd == "+stats" )
     {
          SendReply(msgSrc, nick,"There are " + CString().setNum(dcclient->GetUserCount()) + " users on line." +
               " Of which " + CString().setNum(dcclient->GetOpCount()) + " are Ops" );
          return(TRUE);
     }
     else if (cmd == "+hubinfo" )
     {
          
          SendReply(emsPM, nick, "Hub infomation is:\r\nHub Host [" + dcclient->GetHubConfig()->GetHubHost() +  "]\r\n" +
               "Hub Owner [" + dcclient->GetHubConfig()->GetHubOwner() +  "]\r\n" +
               "Max Users [" + CString().setNum(dcclient->GetHubConfig()->GetHubMaxUsers()) + "]\r\n" +
               "Hub Server Software [" + dcclient->GetHubConfig()->GetHubSoftware() + "]\r\n" +
               "Hub Server Software Version [" + dcclient->GetHubConfig()->GetHubVersion() + "]\r\n" +
               "See Also: +stats");
          return(TRUE);
     }
     else if (cmd == "+botinfo" )
     {
               SendReply(msgSrc, nick,"Im currently connected to ["
               + CString().setNum(dcclient->GetHubCount()) + "] hubs \r\n" +
               "My Master is [" + dcclient->GetBotMaster() +  "]\r\n"
               );
//          SendReply(msgSrc,nick,dcclient->GetHubConfig()->GetBotWWW() );
               
          return(TRUE);
     }
     else if (cmd == "+version" )
     {
          SendReply(msgSrc, nick,"Version " + GetVersion() );
          return(TRUE);
     }
     else if (cmd == "+showops" )
     {
        CString showops = "OPS online now (" + CString().setNum(dcclient->GetOpCount()) + ") :-";
        SendReply(msgSrc, nick, showops + dcclient->GetOpList() );
        return(TRUE);
     }
     else if (cmd == "+showvips" )
     {
        CString showvips = "VIPS online now :-";
        SendReply(msgSrc, nick, showvips + dcclient->GetVipList() );
        return(TRUE);
     }
 
     return(FALSE);
}
bool BotCommands::OpBotCommands(CString nick, CString cmd)
{

     if (cmd.Left(6) == "+info ")
     {
          CString nickArgument = cmd.Mid(6,cmd.Length()-6);
          if (!nickArgument.IsEmpty())
          {
               UserInfo * nickInfo = dcclient->GetNickInfo(nickArgument);
               if (nickInfo != 0)
                    {SendReply(emsPM,nick, nickInfo->ShowUserInfo());}
               else
                    {SendReply(msgSrc, nick, "User: " + nickArgument + " is not online");}
          }
          else
               {SendReply(msgSrc, nick, "Useage: +info nick");}
          return(TRUE);
     }
     else if ( cmd.Left(6) == "+kick " )
     {
          int i1=cmd.Find(' ',0);
          int i2=cmd.Find(' ',i1+1);
          CString kickUser = cmd.Mid(i1+1,i2-i1-1);
          CString kickReason = cmd.Right(cmd.Length()-i2-1);

          if (!kickUser.IsEmpty())
          {
               UserInfo * kickUserInfo = dcclient->GetNickInfo(kickUser);
               if (kickUserInfo)
               {
                    dcclient->interface->Kick(ehikb_Operator, kickUserInfo,0);
                    kickUserInfo->IncKickTotal();
               }
               else
               {
                    SendReply(msgSrc, nick, "User: " + kickUser + " is not online");
               }
          }
          else
          {
               SendReply(msgSrc,nick, "Useage: +kick nick reason");
          }
          return(TRUE);
     }
     else if ( cmd.Left(6) == "+sban " )
     {
          int i1=cmd.Find(' ',0);
          int i2=cmd.Find(' ',i1+1);
          CString banUser = cmd.Mid(i1+1,i2-i1-1);
          CString banReason = cmd.Right(cmd.Length()-i2-1);

          if (!banUser.IsEmpty())
          {
               UserInfo * banUserInfo = dcclient->GetNickInfo(banUser);
               if (banUserInfo)
               {
                    dcclient->SendKick(banUser);
                    dcclient->interface->TimeBan(ehikb_Operator,euibfSBan,banUserInfo,banUser,banReason);
                    return(TRUE);
               }
               else
               {
                    banUserInfo = new UserInfo(dcclient->GetHubId(),dcclient->GetMySqlCon(),banUser);
                    if (banUserInfo == 0){ return(TRUE); }

                    if(banUserInfo->ReadUserInfo())
                    {
                         dcclient->interface->TimeBan(ehikb_Operator,euibfSBan,banUserInfo,banUser,banReason);
                         banUserInfo->SetStatus(euisOffline);
                         banUserInfo->unlockSqlUser();
                         banUserInfo->WriteUserInfo();
                         delete banUserInfo;
                         return(TRUE);
                    }

               }
               SendReply(msgSrc, nick, "User: " + banUser + " is not known to me");
          }
          else
          {
               SendReply(msgSrc,nick, "Useage: +sban nick reason");
          }
          return(TRUE);
     }
     else if ( cmd.Left(6) == "+lban " )
     {
          int i1=cmd.Find(' ',0);
          int i2=cmd.Find(' ',i1+1);
          CString banUser = cmd.Mid(i1+1,i2-i1-1);
          CString banReason = cmd.Right(cmd.Length()-i2-1);

          if (!banUser.IsEmpty())
          {
               UserInfo * banUserInfo = dcclient->GetNickInfo(banUser);
               if (banUserInfo)
               {
                    dcclient->SendKick(banUser);
                    dcclient->interface->TimeBan(ehikb_Operator,euibfLBan,banUserInfo,banUser,banReason);
                    return(TRUE);
               }
               else
               {
                    banUserInfo = new UserInfo(dcclient->GetHubId(),dcclient->GetMySqlCon(),banUser);
                    if (banUserInfo == 0){ return(TRUE); }

                    if(banUserInfo->ReadUserInfo())
                    {
                         dcclient->interface->TimeBan(ehikb_Operator,euibfLBan,banUserInfo,banUser,banReason);
                         banUserInfo->SetStatus(euisOffline);
                         banUserInfo->unlockSqlUser();
                         banUserInfo->WriteUserInfo();
                         delete banUserInfo;
                         return(TRUE);
                    }

               }
               SendReply(msgSrc, nick, "User: " + banUser + " is not known to me");
          }
          else
          {
               SendReply(msgSrc,nick, "Useage: +lban nick reason");
          }
          return(TRUE);
     }
     else if ( cmd.Left(6) == "+pban " )
     {
          int i1=cmd.Find(' ',0);
          int i2=cmd.Find(' ',i1+1);
          CString banUser = cmd.Mid(i1+1,i2-i1-1);
          CString banReason = cmd.Right(cmd.Length()-i2-1);

          if (!banUser.IsEmpty())
          {
               UserInfo * banUserInfo = dcclient->GetNickInfo(banUser);
               if (banUserInfo)
               {
                    dcclient->SendKick(banUser);
                    dcclient->interface->TimeBan(ehikb_Operator,euibfPLBan,banUserInfo,banUser,banReason);
                    return(TRUE);
               }
               else
               {
                    banUserInfo = new UserInfo(dcclient->GetHubId(),dcclient->GetMySqlCon(),banUser);
                    if (banUserInfo == 0){ return(TRUE); }

                    if(banUserInfo->ReadUserInfo())
                    {
                         dcclient->interface->TimeBan(ehikb_Operator,euibfPLBan,banUserInfo,banUser,banReason);
                         banUserInfo->SetStatus(euisOffline);
                         banUserInfo->unlockSqlUser();
                         banUserInfo->WriteUserInfo();
                         delete banUserInfo;
                         
                         return(TRUE);
                    }

               }
               SendReply(msgSrc, nick, "User: " + banUser + " is not known to me");
          }
          else
          {
               SendReply(msgSrc,nick, "Useage: +pban nick reason");
          }
          return(TRUE);
     }
     else if ( cmd.Left(6) == "+uban " )
     {
          int i1=cmd.Find(' ',0);
          CString banUser = cmd.Right(cmd.Length()-i1-1);

          if (!banUser.IsEmpty())
          {
               UserInfo * banUserInfo = new UserInfo(dcclient->GetHubId(),dcclient->GetMySqlCon(),banUser);
               if (banUserInfo == 0){ return(TRUE); }

               if(banUserInfo->ReadUserInfo() == 1)
               {
                    dcclient->interface->UnBan(banUserInfo,banUser);
                    banUserInfo->SetStatus(euisOffline);
                    banUserInfo->unlockSqlUser();
                    banUserInfo->WriteUserInfo();
                    delete banUserInfo;
                    return(TRUE);
               }
               SendReply(msgSrc, nick, "User: " + banUser + " is not known to me");
          }
          else
          {
               SendReply(msgSrc,nick, "Useage: +uban nick");
          }
          return(TRUE);
     }
     else if ( cmd.Left(6) == "+move " )
     {
          int i1=cmd.Find(' ',0);
          int i2=cmd.Find(' ',i1+1);
          CString forceUser = cmd.Mid(i1+1,i2-i1-1);
          CString forceHost = cmd.Right(cmd.Length()-i2-1);

          if ( (!forceUser.IsEmpty()) ||
               (!forceHost.IsEmpty()) )
          {
               UserInfo * forceUserInfo = dcclient->GetNickInfo(forceUser);
               if (forceUserInfo)
               {
                    dcclient->ForceMove(forceUser,"",forceHost);
                    SendReply(emsCHAT, nick,"Moving " + forceUser + " to " + forceHost);
               }
               else
                    {SendReply(msgSrc, nick, "User :" + forceUser + " is not online");}
          }
          else
               {SendReply(msgSrc, nick.Data(), "Useage : +move nick host reason");}
          return(TRUE);
     }
     else if (cmd.Left(5) == "+set ")
     {
          int i1,i2;
          i1=cmd.Find(' ',0,FALSE);
          i2=cmd.Find(' ',0,FALSE);
          CString variable = cmd.Mid(i1+1,cmd.Length()-i2-i1-1);
          CString value = cmd.Right(i2-1);
          if (value.IsEmpty() || variable.IsEmpty())
               {SendReply(msgSrc, nick.Data(), "Useage : +set variable value");}
          if (variable == "maxusers")
          {
               dcclient->GetHubConfig()->SetHubMaxUsers(value);
               return(TRUE);
          }
          else if(variable == "minshare")
          {
               dcclient->GetHubConfig()->SetHubMinShare(value);
               return(TRUE);
          }
          else if(variable == "tempban")
          {
               dcclient->GetHubConfig()->SetHubTempBan(value);
               return(TRUE);
          }
          else if(variable == "mincon")
          {
               dcclient->GetHubConfig()->SetHubMinSpeed(value);
               return(TRUE);
          }
          else if(variable == "minslots")
          {
               dcclient->GetHubConfig()->SetHubMinSlots(value);
               return(TRUE);
          }
          else if(variable == "maxslots")
          {
               dcclient->GetHubConfig()->SetHubMaxSlots(value);
               return(TRUE);
          }
          else if(variable == "maxhubs")
          {
               dcclient->GetHubConfig()->SetHubMaxHubs(value);
               return(TRUE);
          }
          else if(variable == "kicknotag")
          {
               dcclient->GetHubConfig()->SetHubKickNoTag(value);
               return(TRUE);
          }
          else if(variable == "slotratio")
          {
               dcclient->GetHubConfig()->SetHubSlotRatio(value);
               return(TRUE);
          }
          else if(variable == "enableclientchecks")
          {
               dcclient->GetHubConfig()->SetHubEnableTagCheck(value);
               return(TRUE);
          }
          else
          {
               SendReply(msgSrc,nick,"Variable [" + variable + "] Not recognised");
               SendReply(msgSrc,nick,"value [" + value + "] Not recognised");
               return(TRUE);
          }
          
     }
     else if (cmd == "+reload")
     {
          dcclient->GetHubConfig()->LoadHubConfig();
          dcclient->GetLogger()->readLogCfg();
          SendReply(msgSrc,nick,"Reloaded Configuration for  [" + dcclient->GetHubConfig()->GetHubName() + "]");
          return(TRUE);
     }     
     return(FALSE);
}
bool BotCommands::MasterBotCommands(CString nick, CString cmd)
{
     if (cmd.Left(6) == "+join ")
     {
          int i1=cmd.Find(' ',0);
          int i2=cmd.Find(' ',i1+1);
          CString host = cmd.Mid(i1+1,i2-i1-1);
          CString id = cmd.Right(cmd.Length()-i2-1);
          
          if (!host.IsEmpty())
          {
               cout << "Connecting to " << host.Data() << "[" << id.Data() << "]" << endl;
               dcclient->GetBController()->JoinHub(id,host);
          }
          return(TRUE);
     }
     else if (cmd == "+die")
     {
          dcclient->SaveUserList();
          dcclient->GetBController()->LeaveHub(dcclient,dcclient->GetHubId());

          exit(0);
          return(TRUE);
     }
     else if (cmd == "+part")
     {
          dcclient->SaveUserList();
          dcclient->GetBController()->LeaveHub(dcclient,dcclient->GetHubId());
          dcclient->Disconnect();
//          return(TRUE);
     }
//        /** add transfer to the waitlist */
//          pTransferView->DLM_QueueAdd( nick, m_pClient->GetHubName(), m_pClient->GetIP()+":"+QString().setNum(m_pClient->GetPort()).ascii(),
//               DC_USER_FILELIST_HE3, DC_USER_FILELIST_HE3, "", "", eltBUFFER,
//               0, 0 );
     
     else if (cmd.Left(5) == "+set ")
     {
          int i1=cmd.Find(' ',0,FALSE);
          int i2=cmd.Find(' ',0,FALSE);
          CString variable = cmd.Mid(i1+1,cmd.Length()-i2-i1-1);
          CString value = cmd.Right(i2-1);
          if (value.IsEmpty() || variable.IsEmpty())
               {SendReply(msgSrc, nick.Data(), "Useage : +set variable value");}
          
          if (variable == "autoconnect")
          {
               variable = "hubAutoConnect='";variable += value.Data();variable += "'";
               dcclient->GetMySqlCon()->Update(dcclient->GetHubId(),"hubConfig",variable,"");
               return(TRUE);
          }
          else if(variable == "hubowner")
          {
               variable = "hubOwner='";variable += value.Data();variable += "'";
               dcclient->GetMySqlCon()->Update(dcclient->GetHubId(),"hubConfig",variable,"");
               return(TRUE);
          }
          else if(variable == "enablelogging")
          {
               variable = "hubEnableLogging='";variable += value.Data();variable += "'";
               dcclient->GetMySqlCon()->Update(dcclient->GetHubId(),"hubConfig",variable,"");
               return(TRUE);
          }
          else if(variable == "logname")
          {
               variable = "hubLogName='";variable += value.Data();variable += "'";
               dcclient->GetMySqlCon()->Update(dcclient->GetHubId(),"hubConfig",variable,"");
               return(TRUE);
          }
          else
          {
               SendReply(msgSrc,nick,"Variable [" + variable + "] Not recognised");
               return(TRUE);
          }
     }
     return(FALSE);
}
CString BotCommands::Help()
{
     CString help;
     help = "User Help:\r\n";
	help += "+help - This text\r\n";
     help += "+rules - Shows the Current Rules\r\n";     
     help += "+stats - User & Ops counts, Hub statistic informtion\r\n";
     help += "+hubinfo - Information about the hub software & version\r\n";
     help += "+botinfo - Information about the bot, #of hubs connections version, master etc\r\n";
	help += "+time - Shows the Hub Date & Time\r\n";
	help += "+myinfo - Shows your user info\r\n";
	help += "+showops - Show ops online now\r\n";
     help += "+showvips - Shows vips online now\r\n";
     help += "+version - Displays my version\r\n";
//     help += "NI +topchat - Shows the top ten biggest Chatters\r\n";     
	help += "See Also: +help op, +help master\r\n";
     return(help);
}
CString BotCommands::HelpOp()
{
     CString help;
     help = "Operator Help:\r\n";
     help += "+help op - This Text\r\n";
     help += "+info <nick> - Shows the Information held on <nick>\r\n";
	help += "+kick <nick> <reason> - Kick this user\r\n";
	help += "+sban <nick> <reason> - Short Ban this user\r\n";     
	help += "+lban <nick> <reason> - Long Ban this user\r\n";
	help += "+pban <nick> <reason> - Perm Long Ban this user\r\n";
	help += "+uban <nick> - Removes a ban, clears ban flag, and sets expire time to now\n";     
     help += "+move <nick> <host> <reason> - Move the user to another hub\r\n";
	help += "+move <nick> - Move the user to predefined hub\r\n";     
     help += "+save - Store all changes made to configs to SQL\r\n";
     help += "+reload - Reload the SQL config for this hub\r\n";
     help += "+set <variable> <value>  Available variables :- \r\n";
     help += "   maxusers <int>\r\n";
     help += "   minshare <int>\r\n";
     help += "   tempban  <int>\r\n";
     help += "   mincon\r\n";
     help += "   minslots <int>\r\n";
     help += "   maxslots <int>\r\n";
     help += "   maxhubs  <int>\r\n";
     help += "   kicknotag <0/1>\r\n";
     help += "   slotratio <x.yy>\r\n";
     help += "   enableclientchecks <0/1>\r\n";
//     help += "NI +auser <nick> <level>\r\n";
//     help += "NI +duser <nick>\r\n";
     help += "See Also: +help, +help master\r\n";
	return(help);
}
CString BotCommands::HelpMaster()
{
	CString help;
	help = "Master Help:\r\n";
	help += "+part - Disconnects the bot from this hub\r\n";
	help += "+die - Kills the bot\r\n";
     help += "+set <variable> <value>  Available variables :- \r\n";
//     help += "NI  +set autoconnect <0/1>\r\n";
//     help += "NI  +set hubowner <text>\r\n";
//     help += "NI  +set enablelogging <0/1>\r\n";
//     help += "NI  +set logname <text>\r\n";          
     help += "See Also: +help, +help op\r\n";
	return(help);
}

CString BotCommands::Rules()
{
     CString rules;
     rules = "Rules in force:\r\n";
     rules += "Minimum Share [" + CUtils::GetSizeString(dcclient->GetHubConfig()->GetHubMinShare(),euAUTO) + "]\r\n";
     rules += "Minimum Number of Slots [" + CString().setNum(dcclient->GetHubConfig()->GetHubMinSlots()) + "]\r\n";
     rules += "Maximum Number of Slots [" + CString().setNum(dcclient->GetHubConfig()->GetHubMaxSlots()) + "]\r\n";
     rules += "Minimum Connection [" + dcclient->GetHubConfig()->GetHubMinSpeed() + "]\r\n";
     rules += "Maximum Hub Connections [" + CString().setNum(dcclient->GetHubConfig()->GetHubMaxHubs()) + "]\r\n";
     rules += "Min Slot Ratio (Slots/Hub) [" + CString().setNum(dcclient->GetHubConfig()->GetHubSlotRatio(),3) + "]\r\n";
     if(dcclient->GetHubConfig()->GetHubKickNoTag())
     {
          rules += "Untagged Clients will be automatically kicked\r\n";
     }

/*     rules += "Banned Words [" + dcclient->GetHubConfig()->GetHubExBanChat()+"]\r\n";
     rules += "illegal files in share [" + dcclient->GetHubConfig()->GetHubExBanSharedFiles()+ "]\r\n";
     rules += "illegal to search ["+ dcclient->GetHubConfig()->GetHubExBanSearch()+"]\r\n";
     rules += "illegal to Nicks [" + dcclient->GetHubConfig()->GetHubExBanNick()+"]\r\n";
*/     rules += dcclient->GetHubConfig()->GetHubExRules();
     return(rules);          
}        
/*
     user commands

add reg nick password
del reg nick
     master commands
add op nick password
del op nick
join hub
part hub

*/
