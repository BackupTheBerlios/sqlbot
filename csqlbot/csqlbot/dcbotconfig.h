/***************************************************************************
                          dcconfig.h  -  description
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

#ifndef DCCONFIG_H
#define DCCONFIG_H

#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

//#include <dclib/cconfig.h>

#include <libxml/xmlversion.h>
#if LIBXML_VERSION > 20406
#include <libxml/globals.h>
#endif
#include <libxml/parser.h>
#include <libxml/tree.h>



class CString;

class DCBotConfig /*: public CConfig*/ {
public:
	/** */
	DCBotConfig( CString configpath = ".sqlbot/" );
	/** */
	virtual ~DCBotConfig();

	/** */
	int Load();
	/** */
	int Save();

     void ParseMySqlConfig( xmlNodePtr node );


     CString GetSqlUser(){ return sqlUser; }
     CString GetSqlPassword(){ return sqlPassword; }
     CString GetSqlHost(){ return sqlHost; }
     CString GetSqlDatabase(){ return sqlDatabase; }
private:
      CString sConfigPath;

      //For sql access
     CString sqlUser;
     CString sqlPassword;
     CString sqlHost;
     CString sqlDatabase;
};

/** global config */
extern DCBotConfig * Config;

#endif
