/***************************************************************************
                          main.cpp  -  description
                             -------------------
    begin                : Mon Sep 29 19:25:34 BST 2003
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

#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include <dclib/core/cstring.h>
#include <iostream>
#include <signal.h>     // for signals

#include "botcontroller.h"

int main(int argc, char *argv[])
{
	int i;
     CString configpath = "";     
	// parameter stuff ...
	for(i=1;i<argc;i++)
	{
          if ( CString(argv[i]) == "-c" )
		{
			i++;
			if ( i<argc )
			{
				configpath = argv[i];
			}
			else
			{
				printf("Wrong parameter !\n");
				return -1;
			}
		}
          else
          {
             configpath = " ";
          }
	}

     BotController *controller = new BotController();
     controller->Start();

     delete controller;
     return EXIT_SUCCESS;
}


void sigsegv_handler(int signo)
{
	fprintf(stderr, "Signal (%d) received, sorry no backtrace\n", \
        	signo);
	exit(1);
}
