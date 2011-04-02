/*
** Copyright 1998 - 2001 Double Precision, Inc.  See COPYING for
** distribution information.
*/

#if	HAVE_CONFIG_H
#include	"config.h"
#endif
#include	<stdio.h>
#include	<stdlib.h>
#include	<string.h>
#include	<errno.h>
#include	<pwd.h>
#if	HAVE_UNISTD_H
#include	<unistd.h>
#endif
#include	"auth.h"
#include	"authmod.h"
#include	<vpopmail.h>
#include	<vauth.h>
#include	"vpopmail_config.h"

extern char *authvchkpw_isvirtual(char *);

extern FILE *authvchkpw_file(const char *, const char *);

static const char rcsid[]="$Id: preauthvchkpw.c,v 1.16 2001/07/25 21:28:48 mrsam Exp $";

int auth_vchkpw_pre(const char *userid, const char *service,
        int (*callback)(struct authinfo *, void *),
                        void *arg)
{
struct vqpasswd *vpw;
static uid_t uid;
gid_t	gid;
char	*usercopy;
int	notfound;
struct authinfo auth;
 char User[100];
 char Domain[100];


	memset(&auth, 0, sizeof(auth));

	usercopy=strdup(userid);
	if (!usercopy)
	{
		perror("strdup");
		return (1);
	}

	notfound=EACCES;
        parse_email(usercopy, User, Domain, 100);
        vget_assign(Domain,NULL,0,&uid, &gid);
        vpw=vauth_getpw(User, Domain);
        if ( vpw!=NULL ) {
                if (vpw->pw_dir == NULL || strlen(vpw->pw_dir) == 0 ) {
                        make_user_dir(User, Domain, uid, gid);
                        vpw=vauth_getpw(User, Domain);
                }
#ifdef ENABLE_AUTH_LOGGING
                vset_lastauth(User, Domain, service);
#endif

        }
        free(usercopy);
        vclose();

	if (!vpw)
	{
		errno=notfound;
		return (-1);
	}

	if ( strcmp("webmail", service) == 0 && vpw->pw_gid & NO_WEBMAIL ) {
		return(-1);

	} else if ( strcmp("pop3", service) == 0 ) {
		if ( vpw->pw_gid & NO_POP ) {
			return(-1);
		} else {
#ifdef HAVE_OPEN_SMTP_RELAY
			/* open the relay to pop users */
                        if ( ! ( vpw->pw_gid & NO_RELAY ) ) {
                            open_smtp_relay();
                        }
#endif
		}
	} else if ( strcmp("imap", service) == 0 ) {
		if ( vpw->pw_gid & NO_IMAP ) {
			return(-1);
		} else {
#ifdef HAVE_OPEN_SMTP_RELAY
			/* open the relay to imap users */
			if ( ! ( vpw->pw_gid & NO_RELAY ) ) {
                            open_smtp_relay();
                        }
#endif
		}
	}

	auth.sysuserid=&uid;
	auth.sysgroupid=gid;
	auth.homedir=vpw->pw_dir;
	auth.address=userid;
	auth.fullname=vpw->pw_gecos;
	auth.passwd=vpw->pw_passwd;

	return ((*callback)(&auth, arg));
}
