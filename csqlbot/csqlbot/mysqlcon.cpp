/***************************************************************************
                          mysqlcon.cpp  -  description
                             -------------------
    begin                : Thu Oct 16 2003
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
#include <dclib/core/cstring.h>
#include "dcbotconfig.h"	//for config
#include "mysqlcon.h"
#include "globalconf.h"
using std::cout;
using std::endl;

MySqlCon::MySqlCon(){
     DCBotConfig botConfig(".sqlbot/");
     botConfig.Load();
     
     mysql_init(&mysql);
     if (!(mysql_real_connect(&mysql,botConfig.GetSqlHost().Data(),
                              botConfig.GetSqlUser().Data(),
                              botConfig.GetSqlPassword().Data(),
                              botConfig.GetSqlDatabase().Data(),0,0,0)))
     {
          cout << "ERROR : MySql Connect, Check your dcbot.cfg file, and Sql Username & Password" << endl;
          exit(0);
     }
     
}
MySqlCon::~MySqlCon(){
     mysql_close(&mysql);
}

MYSQL_ROW MySqlCon::FetchResult(MYSQL_RES *result)
{
     return(mysql_fetch_row(result));
}
void MySqlCon::FreeRes(MYSQL_RES *result)
{
     mysql_free_result(result);   
}
int MySqlCon::RowCount(MYSQL_RES *result)
{
     return(mysql_num_fields(result));
}
MYSQL_RES *MySqlCon::Query(int HubID,CString tableItem,CString table,CString where)
{
     CString   query = "SELECT ";
               query+= tableItem.Data();
               query += " FROM ";
               query += table.Data();
     if(HubID != 0)               
     {
          query += " WHERE hubID=";
          query += CString().setNum(HubID);
          if(!where.IsEmpty())
          {
               query += " AND ";query += where.Data();
          }
     }
     else if(!where.IsEmpty())
     {
          query += " WHERE ";query += where.Data();
     }
     if (mysql_query(&mysql,query.Data()))
     {
          cout << "ERROR : MySql Query......" << endl;
          cout << "MySQL Query:" << query.Data() << endl;
          if(EXITON_MYSQLERROR)
               exit(0);
     }
     if(VERBOSITY & VERBOSE_MYSQL_OUTPUT)
     {
          cout << "MySQL Query:" << query.Data() << endl;
     }
               
     MYSQL_RES *result = mysql_use_result(&mysql);
     return(result);
}

int MySqlCon::Update(int HubID,CString table,CString data,CString where)
{
     CString   query = "UPDATE ";
               query+= table.Data();
               query += " SET ";
               query += data.Data();
     if(HubID != 0)
     {
          query += " WHERE hubID=";
          query += CString().setNum(HubID);
          if(!where.IsEmpty())
          {
               query += " AND ";query += where.Data();
          }
     }
     else if(!where.IsEmpty())
     {
          query += " WHERE ";query += where.Data();
     }
     if (mysql_query(&mysql,query.Data()))
     {
          cout << "ERROR : MySql Query......" << endl;
          cout << "MySQL Query:" << query.Data() << endl;          
          if(EXITON_MYSQLERROR)
               exit(0);
     }
     if(VERBOSITY & VERBOSE_MYSQL_OUTPUT)
     {
          cout << "MySQL Query:" << query.Data() << endl;
     }

     return(TRUE);
}

int MySqlCon::Insert(CString table,CString data)
{
     CString   query = "INSERT INTO ";
               query+= table.Data();
               query += " SET ";
               query += data.Data();
               query += "";               

     if (mysql_query(&mysql,query.Data()))
     {
          cout << "ERROR : MySql Query......" << endl;
          cout << "MySQL Query:" << query.Data() << endl;
          if(EXITON_MYSQLERROR)
               exit(0);
     }
     if(VERBOSITY & VERBOSE_MYSQL_OUTPUT)
     {
          cout << "MySQL Query:" << query.Data() << endl;
     }

     return(TRUE);
}

