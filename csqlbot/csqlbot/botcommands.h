/***************************************************************************
                          botcommands.h  -  description
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

#ifndef BOTCOMMANDS_H
#define BOTCOMMANDS_H

#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

#include <dclib/core/cstring.h>
#include "userinfo.h"
#include "timehand.h"
#include "globalconf.h"
class DCClient;

class BotCommands {
public:

     
     bool ParseBotCommands(eMsgSrc bcmsgSrc,class DCClient * dcclient, CString nick, CString cmd);
     bool MasterBotCommands(CString nick, CString cmd);
     bool OpBotCommands(CString nick, CString cmd);
     bool UserBotCommands(CString nick, CString cmd);
     CString Rules();
     CString GetVersion();
     void SendReply(eMsgSrc src, CString destNick,CString msg);

     CString Help();
     CString HelpOp();
     CString HelpMaster();
private:
     TimeHand timeHand;
     DCClient * dcclient;
     eMsgSrc msgSrc;
};

#endif
