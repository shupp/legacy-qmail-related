/* 
 * Copyright (C) 1999-2002 Inter7 Internet Technologies, Inc. 
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <sys/stat.h>
#include <unistd.h>
#include <pwd.h>
#include <dirent.h>
#include <errno.h>
#include "config.h"
#include "qmailadmin.h"
#include "qmailadminx.h"
#include "vpopmail.h"
 #include "vpopmail_config.h"
#include "vauth.h"


#define HOOKS 1

#ifdef DEBUG
#include <syslog.h>
#endif

#ifdef HOOKS
#define HOOK_ADDUSER 0
#define HOOK_DELUSER 1
#define HOOK_MODUSER 2
#define HOOK_ADDMAILLIST 3
#define HOOK_DELMAILLIST 4
#define HOOK_MODMAILLIST 5
#define HOOK_LISTADDUSER 6
#define HOOK_LISTDELUSER 7
#endif


int show_users(char *Username, char *Domain, time_t Mytime)
{
  if (MaxPopAccounts == 0) return 0;
  send_template("show_users.html");
}


int show_user_lines(char *user, char *dom, time_t mytime, char *dir)
{
 int  i,j,k,startnumber,moreusers = 1;
 FILE *fs;
 struct vqpasswd *pw;
 int totalpages;
 int bounced;

  if (MaxPopAccounts == 0) return 0;

  /* Get the default catchall box name */
  if ((fs=fopen(".qmail-default","r")) == NULL) {
    fprintf(actout,"<tr><td colspan=\"5\">%s .qmail-default</tr></td>", 
      get_html_text("144"));
    vclose();
    exit(0);
  }

  fgets(TmpBuf, MAX_BUFF, fs);
  fclose(fs);

  if (*SearchUser) {
    pw = vauth_getall(dom,1,1);
    for (k=0; pw!=NULL; k++) {
      if ((!SearchUser[1] && *pw->pw_name >= *SearchUser) ||
          ((!strcmp(SearchUser, pw->pw_name)))) {
        break;
      }

      pw = vauth_getall(dom,0,0);
    }

    if (k == 0) sprintf(Pagenumber, "1");
    else sprintf(Pagenumber, "%d", (k/MAXUSERSPERPAGE)+1);
  }

  /* Determine number of pages */
  pw = vauth_getall(dom,1,1);
  for (k=0; pw!=NULL; k++) pw = vauth_getall(dom, 0, 0);

  if (k == 0) totalpages = 1;
  else totalpages = ((k/MAXUSERSPERPAGE)+1);

  /* End determine number of pages */
  if (atoi(Pagenumber)==0) *Pagenumber='1';

  if ( strstr(TmpBuf, "bounce-no-mailbox") != NULL ) {
    bounced = 1;
  } else if ( strstr(TmpBuf, "@") != NULL ) {
    bounced = 0;
  } else {
    bounced = 0;
    i = strlen(TmpBuf); --i; TmpBuf[i] = 0; /* take off newline */
    for(;TmpBuf[i]!='/';--i);
    for(j=0,++i;TmpBuf[i]!=0;++j,++i) TmpBuf3[j] = TmpBuf[i];
    TmpBuf3[j]=0;
  }

  startnumber = MAXUSERSPERPAGE * (atoi(Pagenumber) - 1);

  /*
   * check to see if there are any users to list, 
   * otherwise repeat previous page
   *  
   */
  pw = vauth_getall(dom,1,1);
  if ( AdminType==DOMAIN_ADMIN ||
       (AdminType==USER_ADMIN && strcmp(pw->pw_name,Username)==0)){

    for (k = 0; k < startnumber; ++k) { 
      pw = vauth_getall(dom,0,0); 
    }
  }

  if (pw == NULL) {
    fprintf(actout, "<tr><td colspan=\"5\" bgcolor=%s>%s</td></tr>\n", 
      get_color_text("000"), get_html_text("131"));
      moreusers = 0;
    } else {
      while ((pw != NULL) && ((k < MAXUSERSPERPAGE + startnumber) ||  
              (AdminType!=DOMAIN_ADMIN || AdminType!=DOMAIN_ADMIN || 
              (AdminType==USER_ADMIN && strcmp(pw->pw_name,Username)==0)))) {
        if (AdminType==DOMAIN_ADMIN || 
            (AdminType==USER_ADMIN && strcmp(pw->pw_name,Username)==0)) {

          fprintf(actout, "<tr bgcolor=%s>", get_color_text("000"));
          fprintf(actout, "<td align=\"left\">%s</td>", pw->pw_name);
          fprintf(actout, "<td align=\"left\">%s</td>", pw->pw_gecos);
          fprintf(actout, "<td align=\"center\">");
          fprintf(actout, "<a href=\"%s/com/moduser?user=%s&dom=%s&time=%d&moduser=%s\">",
            CGIPATH,user,dom,mytime,pw->pw_name);
          fprintf(actout, "<img src=\"/images/qmailadmin/delete.png\" border=\"0\"></a>");
          fprintf(actout, "</td>");
            

          /* if the user is postmaster, or has admin privileges, 
           * prevent deleting 
           */

          if (strncmp(pw->pw_name, "postmaster", 11) == 0 ||
              (strncmp(pw->pw_name, Username, strlen(pw->pw_name)) == 0 
                && strlen(pw->pw_name) == strlen(Username)
                && AdminType==DOMAIN_ADMIN)) {
            fprintf(actout, "<td align=\"center\">");
            fprintf(actout, "<img src=\"/images/qmailadmin/disabled.png\" border=\"0\">");
            fprintf(actout, "</td>");

            /* if the user has admin privileges and pw->pw_name is not 
             * the user or postmaster, allow deleting 
             */
          } else if (AdminType==DOMAIN_ADMIN && 
                   strncmp(pw->pw_name, Username, strlen(pw->pw_name)) != 0 ) {
            fprintf(actout, "<td align=\"center\">");
            fprintf(actout, "<a href=\"%s/com/deluser?user=%s&dom=%s&time=%d&deluser=%s\">",
              CGIPATH,user,dom,mytime,pw->pw_name);
            fprintf(actout, "<img src=\"/images/qmailadmin/delete.png\" border=\"0\"></a>");
            fprintf(actout, "</td>");

          /* special case when pw->pw_name contains the user's name, but 
           * is not the user or postmaster, allow deleting 
           */
          } else if (AdminType==DOMAIN_ADMIN && 
                     strncmp(pw->pw_name, Username, strlen(pw->pw_name)) == 0 
                     && strlen(pw->pw_name) != strlen(Username)) {
            fprintf(actout, "<td align=\"center\">");
            fprintf(actout, "<a href=\"%s/com/deluser?user=%s&dom=%s&time=%d&deluser=%s\">",
              CGIPATH,user,dom,mytime,pw->pw_name);
            fprintf(actout, "<img src=\"/images/qmailadmin/delete.png\" border=\"0\"></a>");
              fprintf(actout, "</td>");

          /* else, don't allow deleting */
          } else {
            fprintf(actout, "<td align=\"center\">");
            fprintf(actout, "<img src=\"/images/qmailadmin/disabled.png\" border=\"0\">");
            fprintf(actout, "</td>");
          }

          if (bounced==0 && strncmp(pw->pw_name,TmpBuf3,MAX_BUFF) == 0) {
            fprintf(actout, "<td align=\"center\">%s</td>", 
              get_html_text("132"));
          } else if (AdminType==DOMAIN_ADMIN) {
            fprintf(actout, "<td align=\"center\">");
            fprintf(actout, "<a href=\"%s/com/setdefault?user=%s&dom=%s&time=%d&deluser=%s&page=%s\">",
              CGIPATH,user,dom,mytime,pw->pw_name,Pagenumber);
            fprintf(actout, "<img src=\"/images/qmailadmin/delete.png\" border=\"0\"></a>");
            fprintf(actout, "</td>");
          } else {
            fprintf(actout, "<td align=\"center\">");
            fprintf(actout, "<img src=\"/images/qmailadmin/disabled.png\" border=\"0\">");
            fprintf(actout, "</td>");
          }

          fprintf(actout, "</tr>\n");
        }        
        pw = vauth_getall(dom,0,0);
        ++k;
      }
    }

    if (AdminType == DOMAIN_ADMIN) {
#ifdef USER_INDEX
      fprintf(actout, "<tr bgcolor=%s>", get_color_text("000"));
      fprintf(actout, "<td colspan=\"5\" align=\"center\">");
      fprintf(actout, "<hr>");
      fprintf(actout, "<b>%s</b>", get_html_text("133"));
      fprintf(actout, "<br>");
      for (k = 97; k < 123; k++) {
        fprintf(actout, "<a href=\"%s/com/showusers?user=%s&dom=%s&time=%d&searchuser=%c\">%c</a>\n",
          CGIPATH,user,dom,mytime,k,k);
      }
      fprintf(actout, "<br>");
      for (k = 0; k < 10; k++) {
        fprintf(actout, "<a href=\"%s/com/showusers?user=%s&dom=%s&time=%d&searchuser=%d\">%d</a>\n",
          CGIPATH,user,dom,mytime,k,k);
      }
      fprintf(actout, "</td>");
      fprintf(actout, "</tr>\n");

      fprintf(actout, "<tr bgcolor=%s>", get_color_text("000"));
      fprintf(actout, "<td colspan=\"5\">");
      fprintf(actout, "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\"><tr><td align=\"center\"><br>");
      fprintf(actout, "<form method=\"get\" action=\"%s/com/showusers\">", 
        CGIPATH);
      fprintf(actout, "<input type=\"hidden\" name=\"user\" value=\"%s\">", 
        user);
      fprintf(actout, "<input type=\"hidden\" name=\"dom\" value=\"%s\">", 
        dom);
      fprintf(actout, "<input type=\"hidden\" name=\"time\" value=\"%d\">", 
        mytime);
      fprintf(actout, "<input type=\"text\" name=\"searchuser\" value=\"%s\">&nbsp;", SearchUser);
      fprintf(actout, "<input type=\"submit\" value=\"%s\">", 
        get_html_text("204"));
      fprintf(actout, "</form>");
      fprintf(actout, "</td></tr></table>");
      fprintf(actout, "<hr>");
      fprintf(actout, "</td></tr>\n");
#endif

      fprintf(actout, "<tr bgcolor=%s>", get_color_text("000"));
      fprintf(actout, "<td colspan=\"5\" align=\"right\">");
#ifdef USER_INDEX
      fprintf(actout, "<font size=\"2\"><b>");
      fprintf(actout, "[&nbsp;");
      /* only display "previous page" if pagenumber > 1 */
      if (atoi(Pagenumber) > 1) {
        fprintf(actout, "<a href=\"%s/com/showusers?user=%s&dom=%s&time=%d&page=%d\">%s</a>",
          CGIPATH,user,dom,mytime,
          atoi(Pagenumber)-1 ? atoi(Pagenumber)-1 : atoi(Pagenumber), 
          get_html_text("135"));
        fprintf(actout, "&nbsp;|&nbsp");
      }
/*
        fprintf(actout, "<a href=\"%s/com/showusers?user=%s&dom=%s&time=%d&page=%s\">%s</a>",
            CGIPATH,user,dom,mytime,Pagenumber,get_html_text("136"));
*/

      if (moreusers && atoi(Pagenumber) < totalpages) {
        fprintf(actout,"<a href=\"%s/com/showusers?user=%s&dom=%s&time=%d&page=%d\">%s</a>",
          CGIPATH,user,dom,mytime,atoi(Pagenumber)+1,
          get_html_text("137"));
        fprintf(actout, "&nbsp;|&nbsp");
      }
/*        fprintf(actout, "&nbsp;|&nbsp");*/
#endif
      fprintf(actout, "<a href=\"%s/com/deleteall?user=%s&dom=%s&time=%d\">%s</a>", 
        CGIPATH,user,dom,mytime,get_html_text("235"));
      fprintf(actout, "&nbsp;|&nbsp");
      fprintf(actout, "<a href=\"%s/com/bounceall?user=%s&dom=%s&time=%d\">%s</a>", 
        CGIPATH,user,dom,mytime,get_html_text("134"));
      fprintf(actout, "&nbsp;|&nbsp");
      fprintf(actout, "<a href=\"%s/com/setremotecatchall?user=%s&dom=%s&time=%d\">%s</a>", 
        CGIPATH,user,dom,mytime,get_html_text("206"));
      fprintf(actout, "&nbsp]");
      fprintf(actout, "</b></font>");
      fprintf(actout, "</td></tr>\n");
  }
  return 0;
}

adduser()
{
  count_users();
  load_limits();

  if ( AdminType!=DOMAIN_ADMIN ) {
    sprintf(StatusMessage,"%s", get_html_text("142"));
    vclose();
    exit(0);
  }
                                                
  if ( MaxPopAccounts != -1 && CurPopAccounts >= MaxPopAccounts ) {
    sprintf(StatusMessage, "%s %d\n", get_html_text("199"),
      MaxPopAccounts);
    show_menu();
    vclose();
    exit(0);
  }

  send_template( "add_user.html" );

}

moduser()
{
  if (!( AdminType==DOMAIN_ADMIN ||
        (AdminType==USER_ADMIN && strcmp(ActionUser,Username)==0))){
    sprintf(StatusMessage,"%s", get_html_text("142"));
    vclose();
    exit(0);
  }
  send_template( "mod_user.html" );
} 

addusernow()
{
 char pw[50];
 int cnt=0, num;
 char *c_num;
 char **mailingListNames;
 char *tmp;
 char *email;
 char **arguments;
 int pid;
 int i;
 int error;
 struct vqpasswd *mypw;


  c_num = malloc(MAX_BUFF);
  email = malloc(128);
  tmp = malloc(MAX_BUFF);
  arguments = (char **)malloc(MAX_BUFF);

  count_users();
  load_limits();

  if ( AdminType!=DOMAIN_ADMIN ) {
    sprintf(StatusMessage,"%s", get_html_text("142"));
    vclose();
    exit(0);
  }

  if ( MaxPopAccounts != -1 && CurPopAccounts >= MaxPopAccounts ) {
    sprintf(StatusMessage, "%s %d\n", get_html_text("199"),
      MaxPopAccounts);
    show_menu();
    vclose();
    exit(0);
  }
 
  GetValue(TmpCGI,Newu, "newu=", MAX_BUFF);

  if ( fixup_local_name(Newu) ) {
    sprintf(StatusMessage, "%s %s\n", get_html_text("148"), Newu);
    adduser();
    vclose();
    exit(0);
  } 

  if ( check_local_user(Newu) ) {
    sprintf(StatusMessage, "%s %s\n", get_html_text("175"), Newu);
    adduser();
    vclose();
    exit(0);
  } 
  GetValue(TmpCGI,Password1, "password1=", MAX_BUFF);
  GetValue(TmpCGI,Password2, "password2=", MAX_BUFF);
  if ( strncmp( Password1, Password2, MAX_BUFF ) != 0 ) {
    sprintf(StatusMessage, "%s\n", get_html_text("200"));
    adduser();
    vclose();
    exit(0);
  }

  if ( strlen(Password1) <= 0 ) {
    sprintf(StatusMessage, "%s\n", get_html_text("234"));
    adduser();
    vclose();
    exit(0);
  }

  strcpy(email, "");
  strcat(email,Newu);
  strcat(email,"@");
  strcat(email,Domain);
    
  GetValue(TmpCGI,Gecos, "gecos=", MAX_BUFF);
  if ( strlen( Gecos ) == 0 ) {
    strcpy(Gecos, Newu);
  }

  GetValue(TmpCGI, c_num, "number_of_mailinglist=", MAX_BUFF);
  num = atoi(c_num);
  if(!(mailingListNames = malloc(sizeof(char *) * num))) {
    sprintf(StatusMessage, "%s\n", get_html_text("201"));
    vclose();
    exit(0);

  } else {
    for(cnt = 0; cnt < num; cnt++) {
      if(!(mailingListNames[cnt] = malloc(MAX_BUFF))) {
        sprintf(StatusMessage, "%s\n", get_html_text("201"));
        vclose();
        exit(0);
      }
    }

    for(cnt = 0; cnt < num; cnt++) {
      sprintf(tmp, "subscribe%d=", cnt);
      error = GetValue(TmpCGI, mailingListNames[cnt], tmp, MAX_BUFF);
      if( error != -1 ) {
        pid=fork();

        if (pid==0) {
          sprintf(TmpBuf1, "%s/ezmlm-sub", EZMLMDIR);
          sprintf(TmpBuf2, "%s/%s", RealDir, mailingListNames[cnt]);
          execl(TmpBuf1, "ezmlm-sub", TmpBuf2, email, NULL);
          exit(127);
        } else {
          wait(&pid);
        }
      } 
    }
  }

  /* add the user then get the vpopmail password structure */
  if ( vadduser( Newu, Domain, Password1, Gecos, USE_POP ) == 0 && 
#ifdef MYSQL_REPLICATION
    !sleep(2) &&
#endif
    (mypw = vauth_getpw( Newu, Domain )) != NULL ) {

    /* from the load_limits() function, set user flags */
    if( DisablePOP > 0 )     mypw->pw_gid |= NO_POP; 
    if( DisableIMAP > 0 )    mypw->pw_gid |= NO_IMAP; 
    if( DisableDialup > 0 )  mypw->pw_gid |= NO_DIALUP; 
    if( DisablePasswordChanging > 0 ) mypw->pw_gid |= NO_PASSWD_CHNG; 
    if( DisableWebmail > 0 ) mypw->pw_gid |= NO_WEBMAIL; 
    if( DisableRelay > 0 )  mypw->pw_gid |= NO_RELAY; 
    if( DefaultQuota[0]!= 0 ) mypw->pw_shell = DefaultQuota;

    /* update the user information */
    if ( vauth_setpw( mypw, Domain ) != VA_SUCCESS ) {

      /* report error */
      sprintf(StatusMessage, "%s %s@%s (%s) %s",
        get_html_text("002"), Newu, Domain, Gecos,
        get_html_text("120"));

    } else {

      /* report success */
      sprintf(StatusMessage, "%s %s@%s (%s) %s",
        get_html_text("002"), Newu, Domain, Gecos,
        get_html_text("119"));
      }

    /* otherwise, report error */
  } else {
    sprintf(StatusMessage, "<font color=\"red\">%s %s@%s (%s) %s</font>", 
      get_html_text("002"), Newu, Domain, Gecos, get_html_text("120"));
  }

  call_hooks( HOOK_ADDUSER );

  /* After we add the user, show the user page
   * people like to visually verify the results
   */
  show_users(Username, Domain, Mytime);

}

int call_hooks( int hook_type )
{
 FILE *fs = NULL;
 int pid;
 char *hooks_path;
 char *cmd;
 char *tmpstr;
 int len = 0;
 int error;
 static char *hooks[15] = {
   "adduser",
   "deluser",
   "moduser",
   "addmaillist",
   "delmaillist",
   "modmaillist",
   "listadduser",
   "listdeluser"};
    
  hooks_path = malloc(MAX_BUFF);

  sprintf(hooks_path, "%s/.qmailadmin-hooks", RealDir, Domain);
  if((fs = fopen(hooks_path, "r")) == NULL) {
    sprintf(hooks_path, "%s/.qmailadmin-hooks", RealDir);
    if((fs = fopen(hooks_path, "r")) == NULL) {
      return (0);
    }
  }

  while(fgets(TmpBuf, MAX_BUFF, fs) != NULL) {
    tmpstr = strtok(TmpBuf, " :\t\n");
    if ( (tmpstr[0] == '#') || (tmpstr == NULL)) continue;

    if ( strncmp(tmpstr, hooks[hook_type], strlen(hooks[hook_type])) == 0) {
      tmpstr = strtok(NULL, " :\t\n");

      if ( tmpstr == NULL) continue;
        
       len = strlen(tmpstr);
       if (!(cmd = malloc(len + 1))) {
         return (0);
       } else {
         sprintf(cmd, "%s", tmpstr);
         strcat(cmd, "");
       }

       break;
     }
  }

  fclose(fs);
    
#ifdef DEBUG
  fprintf(actout,"Where the parameters are: %s, \"%s\", %s, %s, %s, %s, NULL);",
    cmd, hooks[hook_type], Newu, Domain, Password1, Gecos);
#endif
    
  pid = fork();

#ifdef DEBUG
  fprintf(actout,"Where the parameters are: %s, \"%s\", %s, %s, %s, %s, NULL);",
    cmd, hooks[hook_type], Newu, Domain, Password1, Gecos);
#endif 

  if (pid == 0) {
    error =    execl(cmd, Newu, Domain, Password1, Gecos, NULL);
    sprintf(StatusMessage, "%s, \"%s\", %s, %s, %s, %s\n",
      get_html_text("202"), cmd, hooks[hook_type], 
      Newu, Domain, Password1, Gecos);
    if (error == -1) return (-1);
    exit(127);
  } else {
    wait(&pid);
  }

  return (0);
}

deluser()
{
  send_template( "del_user_confirm.html" );
}

delusergo()
{
 static char forward[200];
 static char forwardto[200];
 FILE *fs;
 int i;
 struct vqpasswd *pw;
     
  if ( AdminType!=DOMAIN_ADMIN ) {
    sprintf(StatusMessage,"%s", get_html_text("142"));
    vclose();
    exit(0);
  }

  vdeluser( ActionUser, Domain );

  /* Start create forward when delete - 
   * Code added by Eugene Teo 6 June 2000 
   */

  GetValue(TmpCGI,forward, "forward=", MAX_BUFF);

#ifdef DEBUG
  fprintf(actout, "Forward: %s\n<br>", forward);
#endif

  if (strcmp(forward, "on") == 0) {

    /* replace "." in name with ":" */    

    sprintf(TmpBuf2, ".qmail-%s", ActionUser);
    for(i=6;TmpBuf2[i]!=0;++i) if ( TmpBuf2[i] == '.' ) TmpBuf2[i] = ':';
    
    if ((fs=fopen(TmpBuf2,"w")) == NULL) ack("Failed to open passwd file",21);
    
    GetValue(TmpCGI, forwardto, "forwardto=", MAX_BUFF);
        
#ifdef DEBUG        
    fprintf(actout, "Forward to: %s\n<br>", forwardto);
#endif

    fprintf(fs, "&%s", forwardto);
    fclose(fs);
        
    /* End create forward when delete - 
     * Code added by Eugene Teo 6 June 2000 
     */
  }
    
  sprintf(StatusMessage, "%s %s", ActionUser, get_html_text("141"));
    
  call_hooks(HOOK_DELUSER);
  show_users(Username, Domain, Mytime);

}

count_users()
{
 struct vqpasswd *pw;

  CurPopAccounts = 0;
  pw = vauth_getall(Domain,1,0);
  while(pw!=NULL){
    ++CurPopAccounts;
    pw = vauth_getall(Domain,0,0);
  }
}

setremotecatchall() 
{
  send_template("setremotecatchall.html");
}

setremotecatchallnow() 
{
  GetValue(TmpCGI,Newu, "newu=", MAX_BUFF);

  if (check_email_addr(Newu) ) {
    sprintf(StatusMessage, "%s %s\n", get_html_text("148"), Newu);
    setremotecatchall();
    exit(0);
  }
  set_remote_catchall_now();
}

set_remote_catchall_now()
{
 FILE *fs;

  if ( (fs = fopen(".qmail-default", "w")) == NULL ) {
    fprintf(actout,"%s %s<br>\n", get_html_text("144"), ".qmail-default");
  } else {
    fprintf(fs,"| %s/bin/vdelivermail '' %s\n",VPOPMAILDIR,Newu);
    fclose(fs);
  }
  show_users(Username, Domain, Mytime);
  exit(0);
}

void bounceall()
{
 FILE *fs;

  if ( (fs = fopen(".qmail-default", "w")) == NULL ) {
    fprintf(actout,"%s %s<br>\n", get_html_text("144"), ".qmail-default");
  } else {
    fprintf(fs,"| %s/bin/vdelivermail '' bounce-no-mailbox\n",VPOPMAILDIR);
    fclose(fs);
  }
  show_users(Username, Domain, Mytime);
  vclose();
  exit(0);
}

void deleteall()
{
 FILE *fs;

  if ( (fs = fopen(".qmail-default", "w")) == NULL ) {
    fprintf(actout,"%s %s<br>\n", get_html_text("144"), ".qmail-default");
  } else {
    fprintf(fs,"| %s/bin/vdelivermail '' delete\n",VPOPMAILDIR);
    fclose(fs);
  }
  show_users(Username, Domain, Mytime);
  vclose();
  exit(0);
}

int get_catchall(void)
{
 int i,j;
 FILE *fs;

  /* Get the default catchall box name */
  if ((fs=fopen(".qmail-default","r")) == NULL) {
    fprintf(actout,"<tr><td colspan=\"5\">%s %s</td><tr>\n", 
      get_html_text("144"), ".qmail-default");
    vclose();
    exit(0);
  }
  fgets( TmpBuf, MAX_BUFF, fs);
  fclose(fs);

  if (strstr(TmpBuf, "bounce-no-mailbox") != NULL) {
    fprintf(actout,"<b>%s</b>", get_html_text("130"));

  } else if (strstr(TmpBuf, "delete") != NULL) {
    fprintf(actout,"<b>%s</b>", get_html_text("236"));

  } else if ( strstr(TmpBuf, "@") != NULL ) {
    i=strlen(TmpBuf);
    for(;TmpBuf[i]!=' ';--i);
    fprintf(actout,"<b>%s %s</b>", get_html_text("062"), &TmpBuf[i]);

  } else {
    i = strlen(TmpBuf) - 1;
    for(;TmpBuf[i]!='/';--i);
    for(++i,j=0;TmpBuf[j]!=0;++j,++i) TmpBuf2[j] = TmpBuf[i];
    TmpBuf2[j--] = '\0';

    /* take off newline */
    i = strlen(TmpBuf2); --i; TmpBuf2[i] = 0;/* take off newline */
    fprintf(actout,"<b>%s %s</b>", get_html_text("062"), TmpBuf2);
  }
  return 0;
}

modusergo()
{
 char crypted[20]; 
 char *tmpstr;
 int i;
 int ret_code;
 int password_updated = 0;
 struct vqpasswd *vpw=NULL;
 static char box[50];
 static char NewBuf[156];
 int count;
 FILE *fs;
#ifdef SQWEBMAIL_PASS
 uid_t uid;
 gid_t gid;
#endif

  vpw = vauth_getpw(ActionUser, Domain); 

  if (!( AdminType==DOMAIN_ADMIN ||
         (AdminType==USER_ADMIN && strcmp(ActionUser,Username)==0))){
    sprintf(StatusMessage,"%s", get_html_text("142"));
    vclose();
    exit(0);
  }

  if (strlen(Password1)>0 && strlen(Password2)>0 ) {
    if ( strncmp( Password1, Password2, MAX_BUFF ) != 0 ) {
      sprintf(StatusMessage, "%s\n", get_html_text("200"));
      moduser();
      vclose();
      exit(0);
    }
    if (strlen(Password1) > MAX_PW_CLEAR_PASSWD) {
      sprintf(StatusMessage,"%s %s@%s %s", 
        get_html_text("139"), ActionUser, Domain, VA_PASSWD_TOO_LONG );
      moduser();
      vclose();
      exit(0);
    } else if (vpw->pw_gid & NO_PASSWD_CHNG) {
      sprintf(StatusMessage, "%s", get_html_text("140"));
      moduser();
      vclose();
      exit(0);
    } else {
        sprintf(StatusMessage,"%s %s@%s", 
            get_html_text("139"), ActionUser, Domain);
    }

    mkpasswd3(Password1,Crypted, MAX_BUFF);
    vpw->pw_passwd = Crypted;

#ifdef CLEAR_PASS
      vpw->pw_clear_passwd = Password1;
#endif
#ifdef SQWEBMAIL_PASS
      vget_assign(Domain, NULL, 0, &uid, &gid );
      vsqwebmail_pass( vpw->pw_dir, Crypted, uid, gid);
#endif

  }

  GetValue(TmpCGI,Gecos, "gecos=", MAX_BUFF);
  if ( strlen( Gecos ) != 0 ) {
    vpw->pw_gecos = Gecos;
  }
  vauth_setpw(vpw, Domain);

  /* get the value of the cforward radio button */
  GetValue(TmpCGI,box, "cforward=", MAX_BUFF);

  /* if they want to disable everything */
  if ( strcmp(box,"disable") == 0 ) {

    /* unlink the .qmail file */
    if ( vpw == NULL ) vpw = vauth_getpw(ActionUser, Domain); 
    snprintf(NewBuf,156,"%s/.qmail", vpw->pw_dir);
    unlink(NewBuf);

    /* delete any vacation directory */
    snprintf(NewBuf,156,"%s/vacation", vpw->pw_dir);
    vdelfiles(NewBuf);

  /* if they want to forward */
  } else if (strcmp(box,"forward") == 0 ) {

    /* get the value of the foward */
    GetValue(TmpCGI,box, "nforward=", MAX_BUFF);

    /* If nothing was entered, error */
    if ( box[0] == 0 ) {
      sprintf(StatusMessage, "%s\n", get_html_text("215"));
      moduser();
      vclose();
      exit(0);

    /* check it for a valid email address
    } else if ( check_email_addr( box ) == 1 )  {
      sprintf(StatusMessage, "%s\n", get_html_text("148"));
      moduser();
    */
    }

    /* everything looks good, open the file */
    if ( vpw == NULL ) {
      vpw = vauth_getpw(ActionUser, Domain); 
    }
    snprintf(NewBuf,156,"%s/.qmail", vpw->pw_dir);

    fs = fopen(NewBuf,"w+");
    tmpstr = strtok(box," ,;\n");

    count=0;
    while( tmpstr != NULL && count < 2) {
      fprintf(fs,"&%s\n", tmpstr);
      tmpstr = strtok(NULL," ,\n");
      ++count;
    }

    /* if they want to save a copy */
    GetValue(TmpCGI,box, "fsaved=", MAX_BUFF);
    if ( strcmp(box,"on") == 0 ) {
      fprintf(fs,"%s/Maildir/\n", vpw->pw_dir);
    } 
    fclose(fs);

  /* they want vacation */
  } else if (strcmp(box,"vacation") == 0 ) {

    /* get the subject */
    GetValue(TmpCGI,box, "vsubject=", MAX_BUFF);

    /* if no subject, error */
    if ( box[0] == 0 ) {
      sprintf(StatusMessage, "%s\n", get_html_text("216"));
      moduser();
      vclose();
      exit(0);
    }
 
    /* make the vacation directory */
    if ( vpw == NULL ) vpw = vauth_getpw(ActionUser, Domain); 
    snprintf(NewBuf,156,"%s/vacation", vpw->pw_dir);
    mkdir(NewBuf, 448);

    /* open the .qmail file */
    snprintf(NewBuf,156,"%s/.qmail", vpw->pw_dir);
    fs = fopen(NewBuf,"w+");
    fprintf(fs, "| %s/autorespond 86400 3 %s/vacation/message %s/vacation\n",
      AUTORESPOND_BIN, vpw->pw_dir, vpw->pw_dir );

    /* save a copy for the user */
    fprintf(fs,"%s/Maildir/\n", vpw->pw_dir);
    fclose(fs);

    /* set up the message file */
    snprintf(NewBuf,156,"%s/vacation/message", vpw->pw_dir);
    GetValue(TmpCGI,Message, "vmessage=",MAX_BIG_BUFF);

    if ( (fs = fopen(NewBuf, "w")) == NULL ) ack("123", 123);
    fprintf(fs, "From: %s@%s\n", ActionUser,Domain);
    fprintf(fs, "Subject: %s\n\n", box);
    fprintf(fs, "%s", Message);
    fclose(fs);

    /* save the forward for vacation too */
    GetValue(TmpCGI,box,"nforward=", MAX_BUFF);
    snprintf(NewBuf, 156, "%s/.qmail", vpw->pw_dir);
    fs = fopen(NewBuf, "a+");
    tmpstr = strtok(box, " ,;\n");
    count = 0;
    while( tmpstr != NULL && count < 2 ) {
      fprintf(fs, "&%s\n", tmpstr);
      tmpstr = strtok(NULL, " ,;\n");
      ++count;
    }
    fclose(fs);

  } else {
    printf("nothing\n");
  }

  call_hooks(HOOK_MODUSER);
  show_users(Username, Domain, Mytime);
}
