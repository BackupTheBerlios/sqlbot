/***************************************************************************
                          globalConf.h  -  description
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

#ifndef GLOBALCONF_H
#define GLOBALCONF_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
/* Error handling and control*/ 
#define EXITON_MYSQLERROR     0    //Quit if MySql QUERY gives an error
#define MULTIHUB_CHAT         0    //Enable the Multhub Chat linking
#define COUNTRY_IP_LOOKUP     1     //Enable the IP to Country lookup, HEAVY LOAD

/* Verbosity options */
#define VERBOSE_NONE                  0x00
#define VERBOSE_CONSOLE_OUTPUT        0x01     //Show output to the shell
#define VERBOSE_MYSQL_OUTPUT          0x02    //Show all Sql Output in the console
#define VERBOSE_CONSOLE_INFO_OUTPUT   0x04    //Show info event in Console

#define VERBOSITY         (VERBOSE_NONE | VERBOSE_CONSOLE_OUTPUT | VERBOSE_CONSOLE_INFO_OUTPUT)


/** DO NOT CHANGE THE FOLLOW ENUM LISTS */
typedef enum euiUserLevel {
     euiUser,
     euiVIP,
     euiOp,
     euiOpAdmin,
     euiBotMaster,
     euiBot
} euiUserLevel;

typedef enum euiVerboseUserLevel {
     euiVerbUser        = 0x00,
     euiVerbVIP         = 0x01,
     euiVerbOp          = 0x02,
     euiVerbOpAdmin     = 0x04,
     euiVerbBotMaster   = 0x08,
     euiVerbBot         = 0x10
} euiVerboseUserLevel;

typedef enum ehcLogging {
     ehcNone,
     ehcTxt,
     ehcSql,
     ehcTxtSql
} ehcLogging;

typedef enum eMsgSrc {
	emsCHAT,
	emsPM
} eMsgSrc;

typedef enum eHubInterface {
     ehiUnkown,
     ehiOpendcHub,
     ehiVerlihub
} eHubInterface;

typedef enum eKickBanTypes {
     ehikb_None           = 0x00,
     ehikb_UnTagged       = 0x01,
     ehikb_Share          = 0x02,
     ehikb_MxHubs         = 0x04,
     ehikb_MxSlots        = 0x08,
     ehikb_MnSlots        = 0x10,
     ehikb_SlotRatio      = 0x20,
     ehikb_HackedTag      = 0x40,
     ehikb_MinConnection  = 0x80,
     ehikb_BadWord        = 0x100,
     ehikb_IllegalSearch  = 0x200,
     ehikb_IllegalShare   = 0x400,
     ehikb_BanNick        = 0x800,
     ehikb_Clone          = 0x1000,
     ehikb_Operator       = 0x2000
} eKickBanTypes;

typedef enum euiStatus {
     euisOffline,
     euisOnline
} euiStatus;

typedef enum euiBanFlag {
     euibfNone,
     euibfSBan,
     euibfLBan,
     euibfPLBan
} euiBanFlag;

typedef enum euiIsAway {
     euiiaOnline,
     euiiaAway
} euiIsAway;
#endif
