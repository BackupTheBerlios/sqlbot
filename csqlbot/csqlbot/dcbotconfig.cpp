/***************************************************************************
                          dcconfig.cpp  -  description
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
 
#include <dclib/core/cbytearray.h>
#include <dclib/core/cstring.h>
#include <dclib/core/cthread.h>
#include <dclib/dcobject.h>
#include <dclib/core/che3.h>
#include <dclib/core/cxml.h>
#include <dclib/core/csocket.h>
#include <dclib/core/cstringlist.h>


#include "dcbotconfig.h"

#include <dclib/dcos.h>


// config files
#define DCBOT_CONFIG		"dcbot.cfg"
#define XML_DCBOT_CONFIG	     "dcbot"
#define XML_BOT			"bot"
#define XML_SQL               "mysql"

#define XML_SQLUSER           "sqluser"
#define XML_SQLPASSWORD       "sqlpassword"
#define XML_SQLHOST           "sqlhost"
#define XML_SQLDATABASE       "sqldatabase"



DCBotConfig * Config = 0;

DCBotConfig::DCBotConfig( CString configpath )/* : CConfig(configpath)*/
{
     sConfigPath = configpath;

     Config = this;
}

DCBotConfig::~DCBotConfig()
{

}

/** */
void DCBotConfig::ParseMySqlConfig( xmlNodePtr node )
{
     xmlNodePtr n1,n2;
     CXml xml;

     for(n1=node;n1!=0;n1=n1->next)
     {
          /* bot entrys */
          if ( xml.name(n1) == XML_SQL )
          {
               for(n2=n1->xmlChildrenNode;n2!=0;n2=n2->next)
               {
                    if ( xml.name(n2) == XML_SQLUSER )
                         sqlUser = xml.content(n2);
                    else if ( xml.name(n2) == XML_SQLPASSWORD )
                         sqlPassword = xml.content(n2);
                    else if ( xml.name(n2) == XML_SQLHOST )
                         sqlHost = xml.content(n2);
                    else if ( xml.name(n2) == XML_SQLDATABASE )
                         sqlDatabase = xml.content(n2);
               }
          }
     }
}

/** */
int DCBotConfig::Load()
{
	int err = 0;
	CString s;
	xmlNodePtr node;
	CXml xml;

	s = sConfigPath + DCBOT_CONFIG;

	if ( xml.ParseFile(s) == TRUE )
	{
		for(node=xml.doc()->children;node!=0;node=node->next)
		{
			if ( xml.name(node) == XML_DCBOT_CONFIG )
			{
				ParseMySqlConfig(node->xmlChildrenNode);
			}
		}
	}
	else
	{
		err = -1;
	}
	return err;
}

/** */
int DCBotConfig::Save()
{
	int err=0;
	CString s;
	xmlDocPtr doc;
	CXml xml;

	doc = xmlNewDoc((const xmlChar*)"1.0");

	doc->children = xmlNewDocNode(doc,0,(const xmlChar*)XML_DCBOT_CONFIG,0);

	// gui
//	node = xmlNewChild( doc->children, 0, (const xmlChar*)XML_BOT, 0 );
//	xml.xmlNewStringChild( node, 0, (const xmlChar*)XML_BOTNICK, botNick );
//	xml.xmlNewStringChild( node, 0, (const xmlChar*)XML_BOTMASTER, botMaster );
//	xml.xmlNewStringChild( node, 0, (const xmlChar*)XML_BOTCONNECTION, botConnection );
//	xmlNewChild( node, 0, (const xmlChar*)XML_BOTTCPPORT, CString().setNum(botTcpPort).Data() );
//	xmlNewChild( node, 0, (const xmlChar*)XML_BOTUDPPORT, CString().setNum(botUdpPort).Data() );
//	xml.xmlNewStringChild( node, 0, (const xmlChar*)XML_HUBPASS, hubPass );
//	xml.xmlNewStringChild( node, 0, (const xmlChar*)XML_DEFAULTHUB, defaultHub );


	// save file
	s = sConfigPath + DCBOT_CONFIG;

	if ( xmlSaveFormatFile(s.Data(),doc,1) == -1 )
	{
		err = -1;
	}

	xmlFreeDoc(doc);

	return err;
}

