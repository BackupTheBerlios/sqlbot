/***************************************************************************
                          mysqlcon.h  -  description
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

#ifndef MYSQLCON_H
#define MYSQLCON_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include </usr/include/mysql/mysql.h>

class MySqlCon {
public: 
	MySqlCon();
	~MySqlCon();

     MYSQL mysql;
     MYSQL_ROW row;


     MYSQL_RES *Query(int HubID,CString tableItem,CString table,CString where);
     
     int Insert(CString table,CString data);
     int Update(int HubID,CString table,CString data,CString where);
     MYSQL_ROW FetchResult(MYSQL_RES *result);
     int RowCount(MYSQL_RES *result);     //Returns row count of query
     void FreeRes(MYSQL_RES *result);     //Free the sql resources
};

#endif
