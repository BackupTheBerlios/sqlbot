/***************************************************************************
                          hubinterface.h  -  description
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

#ifndef HUBINTERFACE_H
#define HUBINTERFACE_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include <dclib/core/cstring.h>
#include "timehand.h"

class HubInterface {
public: 
	HubInterface(class DCClient * hidcclient, MySqlHub *hubCfg, eHubInterface interface);
	~HubInterface();
     /** Configure the interface this hub*/
     bool InitInterface(void);
     /** Fill Kick Ban Message */
     CString KickBanMsgs(eKickBanTypes kickBanType,UserInfo * info);
     /** Kick the user from hub*/
     bool Kick(eKickBanTypes kickBanType, UserInfo * info, CString nick);
     /** Ban the user on the hub, will work out if LBan or SBan*/
     void Ban(eKickBanTypes kickBanTypes,UserInfo * info,CString nick);
     /** Force a Ban of type flag*/
     void TimeBan(eKickBanTypes kickBanTypes,euiBanFlag banFlag, UserInfo * info, CString nick, CString reason);
     /** Unban the user*/
     void UnBan( UserInfo * info, CString nick);
     /** Configure the version of the Hub*/
     bool HubVersion(CString msg);
     /** Send request for User IP*/
     void CallForUserIp(CString nick);
     /** Parse the IP request return*/
     bool SetUserIp(CString msg);

private:
     DCClient * dcclient;

     UserInfo * botInfo;
     CString botnick;

     MySqlHub *hubConfig;
     eHubInterface hubInterface;
     TimeHand timeHand;
};

#endif
