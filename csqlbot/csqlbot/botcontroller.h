/***************************************************************************
                          botcontroller.h  -  description
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

#ifndef BOTCONTROLLER_H
#define BOTCONTROLLER_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include <dclib/dcos.h>
#include <dclib/cconfig.h>
#include <dclib/core/casyncdns.h>
#include <dclib/core/cmanager.h>
#include <dclib/core/cstring.h>
#include <dclib/cfilemanager.h>
#include <dclib/cquerymanager.h>
//#include <dclib/cpluginmanager.h>
#include <dclib/cservermanager.h>
#include <dclib/cdownloadmanager.h>
#include <dcclient.h>
#include <unistd.h>	//for sleep
#include "mysqlcon.h" //for sqlConnection
#include "mysqlbot.h" //for botConfig
#include "globalconf.h"
/**
  *@author Nutter
  */

class BotController : public CClient{
public: 
	BotController();
	~BotController();
     void Start(void);
     void AddHub(CString hubid,CString host);
     void JoinHub(CString hubid,CString host);
     void LeaveHub(DCClient *dcclient,int hubid);

     CStringList hubList;
private:
     BotController * botController;
     CConfig * cconfig;
     MySqlCon * mySqlCon;
     MySqlBot * mySqlBot;
};

#endif
