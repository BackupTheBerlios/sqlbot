####### kdevelop will overwrite this part!!! (begin)##########
bin_PROGRAMS = csqlbot

## INCLUDES were found outside kdevelop specific part

csqlbot_SOURCES = userinfo.cpp timehand.cpp mysqlhub.cpp mysqlcon.cpp mysqlbot.cpp log.cpp hubinterface.cpp dcclient.cpp dcbotconfig.cpp botcontroller.cpp botcommands.cpp main.cpp 
csqlbot_LDADD   = -ldc $(XML_LIBS) $(LIBSSL) $(SSL_LDFLAGS)


EXTRA_DIST = main.cpp botcommands.cpp botcommands.h botcontroller.cpp botcontroller.h dcbotconfig.cpp dcbotconfig.h dcclient.cpp dcclient.h globalconf.h hubinterface.cpp hubinterface.h log.cpp log.h mysqlbot.cpp mysqlbot.h mysqlcon.cpp mysqlcon.h mysqlhub.cpp mysqlhub.h timehand.cpp timehand.h userinfo.cpp userinfo.h 

####### kdevelop will overwrite this part!!! (end)############
# set the include path found by configure
INCLUDES= $(all_includes) -I/usr/local/include/dclib/ $(SSL_INCLUDES) $(LIBDC_CFLAGS) $(XML_CFLAGS) -D_REENTRANT -Wall

# the library search path.
csqlbot_LDFLAGS = $(all_libraries) $(LIBDC_LIBS) $(XML_LIBS) $(SSL_LDFLAGS) -lmysqlclient -L/usr/lib/mysql -L/usr/local/lib/
