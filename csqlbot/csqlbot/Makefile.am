####### kdevelop will overwrite this part!!! (begin)##########
bin_PROGRAMS = csqlbot

## INCLUDES were found outside kdevelop specific part

csqlbot_SOURCES = userinfo.cpp timehand.cpp mysqlhub.cpp mysqlcon.cpp mysqlbot.cpp log.cpp hubinterface.cpp dcclient.cpp dcbotconfig.cpp botcontroller.cpp botcommands.cpp main.cpp 
csqlbot_LDADD   = 


EXTRA_DIST = main.cpp botcommands.cpp botcommands.h botcontroller.cpp botcontroller.h dcbotconfig.cpp dcbotconfig.h dcclient.cpp dcclient.h globalconf.h hubinterface.cpp hubinterface.h log.cpp log.h mysqlbot.cpp mysqlbot.h mysqlcon.cpp mysqlcon.h mysqlhub.cpp mysqlhub.h timehand.cpp timehand.h userinfo.cpp userinfo.h 

####### kdevelop will overwrite this part!!! (end)############
# set the include path found by configure
INCLUDES= $(all_includes) -I/usr/include -I/usr/include/libxml2 -I/usr/local/include/dclib/
 
# the library search path.
csqlbot_LDFLAGS = $(all_libraries) -ldl -ldc -lmysqlclient -L/usr/lib/mysql -L/usr/local/lib/ 
                                                                                
