/***************************************************************************
                          botmysql.cpp  -  description
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

#include "mysqlbot.h"

MySqlBot::MySqlBot(MySqlCon * mySql){
     bcMySql = mySql;
     GetBotConfig();         
}
MySqlBot::~MySqlBot(){
}
void MySqlBot::GetBotConfig(){

     MYSQL_RES *result =bcMySql->Query(0,"*","botConfig","");
     MYSQL_ROW row = bcMySql->FetchResult(result);
     if (row)
     {
          bcName         = row[1];
          bcMaster       = row[2];
          bcIP           = row[3];
          bcTCPport      = CString(row[4]).asINT();
          bcUDPport      = CString(row[5]).asINT();
//          bcWWW          = row[6];
          bcConnection   = row[7];;
          bcDescription  = row[8];
          bcSharePath    = row[9];
          bcLogDir       = row[10];
     }
     bcMySql->FreeRes(result);
}

         
