/***************************************************************************
                          timehand.cpp  -  description
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

#include "timehand.h"
#include <iostream>
#include <iomanip>
#include <sstream>
using namespace std;
TimeHand::TimeHand(){
}
TimeHand::~TimeHand(){
}
CString TimeHand::getDate()
{
     ostringstream datestream;
     // Get the current time
     time_t rawtime;
     struct tm * timeinfo;
     time ( &rawtime );
     timeinfo = localtime ( &rawtime );

     datestream << setw(4) << setfill('0') << timeinfo->tm_year+1900
           << setw(2) << setfill('0') <<  timeinfo->tm_mday
          << setw(2) << setfill('0') << timeinfo->tm_mon+1;
     datestream.flush();
//     cout << "datenow = " << datestream.str() << endl;
    return CString((const char *&)datestream.str());
}
CString TimeHand::getTime()
{
     ostringstream timestream;
     // Get the current time
     time_t rawtime;
     struct tm * timeinfo;
     time ( &rawtime );
     timeinfo = localtime ( &rawtime );

     timestream << setw(2) << setfill('0') << timeinfo->tm_hour
	        << setw(2) << setfill('0') << timeinfo->tm_min
		<< setw(2) << setfill('0') << timeinfo->tm_sec ;
     timestream.flush();
//     cout << "timenow = " << timestream.str() << endl;
     return CString((const char *&)timestream.str());
}
CString TimeHand::getLogDate()
{
     ostringstream datestream;
     // Get the current time
     time_t rawtime;
     struct tm * timeinfo;
     time ( &rawtime );
     timeinfo = localtime ( &rawtime );

     datestream << setw(2) << setfill('0') << timeinfo->tm_mday
     << "-" << setw(2) << setfill('0') << timeinfo->tm_mon+1
	<< "-" << setw(2) << setfill('0') << timeinfo->tm_year+1900 ;
     datestream.flush();
//     datestream.freeze(0);
//     cout << "datenow = " << datestream.str() << endl;
    return CString((const char *&)datestream.str());     
}
CString TimeHand::getLogTime()
{
     ostringstream timestream;
     
     // Get the current time
     time_t rawtime;
     struct tm * timeinfo;
     time ( &rawtime );
     timeinfo = localtime ( &rawtime );

     timestream << setw(2) << setfill('0') << timeinfo->tm_hour
     		<< ":" << setw(2) <<  setfill('0') << timeinfo->tm_min
		<< ":" << setw(2) << setfill('0') << timeinfo->tm_sec ;
     timestream.flush();
//     timestream.freeze(0);
//     cout << "log time now = " << timestream.str() << endl;
    return CString((const char *&)timestream.str());

}


CString TimeHand::addTime(int seconds)
{
     ostringstream timestream;

     // Get the current time
     time_t rawtime;
     struct tm * timeinfo;
     time ( &rawtime );
     rawtime = rawtime+seconds;
     timeinfo = localtime ( &rawtime );
     timestream << setw(2) << setfill('0') << timeinfo->tm_mday
          << setw(2) << setfill('0') << timeinfo->tm_mon+1
          << setw(2) << setfill('0') << timeinfo->tm_year+1900 
          << setw(2) << setfill('0') << timeinfo->tm_hour
          << setw(2) << setfill('0') << timeinfo->tm_min
          << setw(2) << setfill('0') << timeinfo->tm_sec ;
     timestream.flush();
     return CString((const char *&)timestream.str());
}
