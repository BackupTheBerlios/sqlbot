/***************************************************************************
                          timehand.h  -  description
                             -------------------
    begin                : Sun Oct 19 2003
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

#ifndef TIMEHAND_H
#define TIMEHAND_H
#ifdef HAVE_CONFIG_H
#include <config.h>
#endif
#include <dclib/core/cstring.h>
#include <ctime>

class TimeHand {
public: 
	TimeHand();
	~TimeHand();
     // A date CString formatted for use in Log file
     CString getLogDate();
     // A Time CString formatted for use in Log file
     CString getLogTime();
     // A date CString formatted for use in php/sql DB     
     CString getDate();
     // A Time CString formatted for use in php/sql DB     
     CString getTime();
     // This function gives a time in the future advanced by seconds
     CString addTime(int seconds);     
};

#endif
