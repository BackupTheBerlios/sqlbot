/***************************************************************************
                          botmysql.h  -  description
                             -------------------
    begin                : Tue Oct 14 2003
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

#ifndef BOTMYSQL_H
#define BOTMYSQL_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include <dclib/core/cstring.h>
#include "mysqlcon.h"

class MySqlBot {
public: 
	MySqlBot(MySqlCon * mySql);
	~MySqlBot();
     void GetBotConfig();
     void GetHubConfig(int hubId);

     CString GetBotName(){ return bcName;}
     CString GetBotMaster(){ return bcMaster;}
     CString GetBotIP(){ return bcIP;}
     int GetBotTCPport(){ return bcTCPport;}
     int GetBotUDPport(){ return bcUDPport;}
     CString GetBotConnection(){ return bcConnection;}
     CString GetBotDescription(){ return bcDescription;}
     CString GetBotSharePath(){ return bcSharePath;}
private:
     CString bcName;
     CString bcMaster;
     CString bcIP;
     int bcTCPport;
     int bcUDPport;
     CString bcConnection;
     CString bcDescription;
     CString bcSharePath;
     CString bcLogDir;
          
     MySqlCon * bcMySql;
};

#endif
