dnl
dnl $Id: config.m4,v 1.1 2003/02/13 04:26:47 gschlossnagle Exp $
dnl

PHP_ARG_WITH(cybercash, for CyberCash support,
[  --with-cybercash[=DIR]  Include CyberCash support.  DIR is the CyberCash MCK 
                          install directory.])

  if test "$PHP_CYBERCASH" != "no"; then
  CYBERCASH_LIB=libmckcrypto.a
  CYBERCASH_HDR=mckcrypt.h

  for i in /usr/local /usr $PHP_CYBERCASH ; do
    dnl Find the include file first
    if test -r $i/$CYBERCASH_HDR ; then
      CYBERCASH_INC_DIR=$i
    elif test -r $i/include/$CYBERCASH_HDR; then
      CYBERCASH_INC_DIR=$i/include
    elif test -r $i/c-api/$CYBERCASH_HDR; then
      CYBERCASH_INC_DIR=$i/c-api
    elif test -r $i/lib/$CYBERCASH_HDR; then
      CYBERCASH_INC_DIR=$i/lib
    elif test -r $i/bin/$CYBERCASH_HDR; then
      CYBERCASH_INC_DIR=$i/bin
    fi
    dnl Now find and check the library
    if test -r $i/$CYBERCASH_LIB; then
      CYBERCASH_LIB_DIR=$i
    elif test -r $i/lib/$CYBERCASH_LIB; then
      CYBERCASH_LIB_DIR=$i/lib
    fi
    test -n "$CYBERCASH_INC_DIR" && test -n "$CYBERCASH_LIB_DIR" && break
  done

  if test -z "$CYBERCASH_INC_DIR"; then
    AC_MSG_ERROR([Could not find mckcrypt.h. Please make sure you have the
                 CyberCash MCK installed. Use
                 ./configure --with-cybercash=<cybercash-dir> if necessary])
  fi

  if test -z "$CYBERCASH_LIB_DIR"; then
    AC_MSG_ERROR([Could not find libmckcrypto.la. Please make sure you have the
                 CyberCash MCK installed. Use
                 ./configure --with-cybercash=<cybercash-dir> if necessary])
  fi


  AC_CHECK_LIB(mckcrypto,base64_encode,[AC_DEFINE(HAVE_MCK,1,[ ])],
    [AC_MSG_ERROR(Please reinstall the CyberCash MCK - cannot find mckcrypto lib)])
  PHP_NEW_EXTENSION(cybercash, cybercash.c, $ext_shared)
  PHP_ADD_INCLUDE($CYBERCASH_INC_DIR)
  PHP_ADD_LIBRARY_WITH_PATH(mckcrypto, $CYBERCASH_LIB_DIR)

fi
