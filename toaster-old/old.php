<html>
<title>Bill's Linux Qmail Toaster</title>
<body>
<a name=top><h2>Linux Qmail Toaster</h2></a>
Version: 0.3 (cdb/maildirquota) - <a href=#changelog>ChangeLog</a><br> 
Last modified: <? echo strftime("%b %d, %Y %H:%M", filemtime("index.php"))?><br>

<!-- Outline -->
<h2><a href=#preface>Preface</a></h2>
<ul>
    <li><a href=#whatisatoaster>What's a toaster?<a><br>
    <li><a href=#what>What this toaster does and does not do<a><br>
    <li><a href=#assumptions>Assumptions/Support (Please Read!)</a><br>
    <li><a href=#prerequisites>Prerequisites</a>
</ul>

<h2><a href=#gettingstarted>Getting Started</a></h2>
<ul>
    <li><a href=#dns>DNS</a>
    <li><a href=#remove>Remove existing sendmail/pop/imap/httpd servers</a>
    <li><a href=#download>Download packages</a>
</ul>

<h2><a href=#install>Install Software</a></h2>
<ul>
    <li><a href=#daemontools>daemontools</a>
    <li><a href=#ucspi-tcp>ucspi-tcp</a>
    <li><a href=#qmail>qmail</a>
    <li><a href=#vpopmail>vpopmail</a>
    <li><a href=#courier-imap>courier-imap</a>
    <li><a href=#apache>apache</a>
    <li><a href=#sqwebmail>sqwebmail</a>
    <li><a href=#autorespond>autorespond</a>
    <li><a href=#ezmlm>ezmlm-idx</a>
    <li><a href=#qmailadmin>qmailadmin</a>
</ul>

<h2><a href=#test>Test Drive</a></h2>

<h2><a href=#options>Options</a></h2>
<ul>
    <li><a href=#qmail-scanner>Qmail Scanner</a>
    <li><a href=#limits>Qmailadmin Limits</a>
</ul>

<h2><a href=#appendix>Appendix</a></h2>
    <li><a href=#troubleshooting>Troubleshooting</a>
    <li><a href=#credits>Credits</a>
    <li><a href=#links>Links</a>
</ul>
<!-- End Outline -->




<!-- Text -->
<p><br><br><br>
<hr>

<a name=preface><h2>Preface</h2></a>

<ul>
    <li><a name=whatisatoaster><b>What's a POP toaster?:</b><a><p>
        I use Dan Bernstein's definition described at <a href="http://cr.yp.to/qmail/toaster.html">http://cr.yp.to/qmail/toaster.html</a>
        <p><a href=#top>top</a><p>

    <li><a name=what><b>What this toaster does and does not do:</b><a><p>
        This "howto" will walk you through building a Qmail Linux "Toaster".  
        While these instructions are intended to work with popular Linux 
        distributions, they will probably work on other flavors of Unix 
        without too much modification.<p>

        Here's a list of features you'll get:

        <blockquote>
            Qmail SMTP Mail Server with SMTP-AUTH, TLS support, and optional 
                Virus Scanner.<br>
            POP3 Server with SSL support<br>
            IMAP Server with SSL support<br>
            WebMail Server<br>
            Quota Support (usage viewable by webmail)<br>
            Autoresponder<br>
            Mailing Lists<br>
            Web-Based Email Administration<p>
        </blockquote>

        What this toaster does NOT do is act as a thorough guide to qmail or any of the other
        packages it installs.  Such information is already available in the documentation,
        <a href=http://www.lifewithqmail.org>Life With Qmail</a>, or other howtos/toasters.  I only
        put this together to document all the commands/urls/procedures that I find myself repeating
        often.  It's intended to have a bit of a "copy and paste" feel to it aimed at the impatient
        (me).  If it's not sufficient for you, take the time to read the documentation of each package
        that's to be installed.  There's no substitute for that.

        <p><a href=#top>top</a><p>


    <li><a name=assumptions><b>Assumptions/Support</b></a><p>
        This document assumes that you are familiar with Unix system administration, mail/web
        protocols, etc.  You don't have to be a guru to make this work, but you will be patching
        and compiling source code, as well as editing configuration files.  If you want a "point and
        click" install experience, this is not for you.<p>

        These instructions come with no warranty or guarantee.  If you blow up your server, and lose 
        business in the process, that's your problem.<p>

        Support is not provided.  There are mailing lists for all these packages, as well as one 
        specific to this toaster.  See links in the appendix for more information.<p>

        Commercial support <b>is</b> available.  Please contact 
        <a href=mailto:hostmaster@shupp.org>hostmaster@shupp.org</a> for more information.  There are
        also other sources of commercial support for the individual packages.  See the respective 
        documentation for each package for further information.<p>
        <a href=#top>top</a><p>

    <li><a name=prerequisites><b>Prerequisites</b></a><p>
        If you have installed a recent version of your Linux distribution, you shouldn't have any 
        problems, especially if you did a "server" type of install rather than "Desktop".  
        The main issues you might see are missing development libraries, like gdbm or openssl, for example.
        If you are using an rpm based distro, an easy way to check for these is to issue these commands:

        <blockquote><font face=courier size=-1>
        rpm -q gdbm<br>
        rpm -q gdbm-devel<br>
        rpm -q openssl<br>
        rpm -q openssl-devel<br>
        </font></blockquote>

        If any of the above are not installed, either get the rpm for your architecture (probably on your
        cd if you have one) or install them manually.<p>

        This document was written using RedHat Linux 7.1.<p>
        <a href=#top>top</a><p>

</ul>

<a name=gettingstarted><h2>Getting Started</h2></a>

<ul>
    <li><a name=dns><b>DNS</b></a><p>

        Before we begin, make sure DNS (mx record) is properly setup.<p>

    <li><a name=remove><b>Remove existing sendmail/pop/imap/httpd servers</b><a><p>
        Now we must remove any existing installations of sendmail and
        apache, and disable pop/imap servers.  To remove sendmail and apache from
        rpm based distributions, try this:

        <blockquote><font face=courier size=-1>
        rpm -e --nodeps sendmail<br>
        rpm -e --nodeps apache<br>
        </font></blockquote>

        Unless you have other services that absolutely have to run on this machine, I recommend
        shutting down inetd or xinetd altogether and removing it from your startup scripts.  The
        only thing you'll need (outside of what we're about to isntall) is ssh, which is 
        probably installed already.  This will likely shut off any pop3 or imap servers, as well as 
        other unneccessary ports.  Otherwise, disable them manually.<p>

        To be sure that these services are disabled, try telnetting to ports 25, 80, 110, and 143 
        and make sure your connections are refused.
        <p><a href=#top>top</a><p>

    <li><a name=download><b>Download packages</b><a><p>
        I keep my software source in /var/src.  This is what I'll refer to for the rest of
        this document.
        <p><u>Some of this is version dependent, so please stick to the URLs below!</u>

        <blockquote><font face=courier size=-1>
        mkdir -p /var/src/tar<br>
        cd /var/src/tar<p>
        wget http://cr.yp.to/daemontools/daemontools-0.76.tar.gz<br>
        wget http://cr.yp.to/ucspi-tcp/ucspi-tcp-0.88.tar.gz<br>
        wget http://cr.yp.to/software/qmail-1.03.tar.gz<br>
        wget http://shupp.org/toaster/qmail-1.03.shupp.toaster.patch.gz<br>
        wget http://shupp.org/toaster/vpopmail-5.1.4.tar.gz<br>
        wget http://shupp.org/toaster/vchkpw-norelay.patch.gz<br>
        wget http://www.inter7.com/devel/autorespond-2.0.2.tar.gz<br>
        wget http://www.inter7.com/devel/qmailadmin-1.0.1.tar.gz<br>
        wget http://cr.yp.to/software/ezmlm-0.53.tar.gz<br>
        wget http://shupp.org/toaster/idx.shupp.patch.gz<br>
        wget http://prdownloads.sourceforge.net/courier/courier-imap-1.4.0.tar.gz<br>
        wget http://shupp.org/toaster/preauthvchkpw.c<br>
        wget http://prdownloads.sourceforge.net/courier/sqwebmail-3.2.1.tar.gz<br>
        wget http://www.apache.org/dist/httpd/apache_1.3.22.tar.gz<br>
        wget http://shupp.org/toaster/toaster-scripts.tar.gz
        
        </font></blockquote>
        <a href=#top>top</a><p>

</ul>

<a name=install><h2>Install Software</h2></a>

Now that you have downloaded all the software packages to /var/src/tar, please go through each of 
these installation steps as the appear, and in this order, unless you <i>really</i> know what 
you're doing.  (Because if you did, you wouldn't be reading this, right?)<p>

<u>The below steps assume that your "rc" directories are in /etc/rc.d, and 
your "init.d" path is "/etc/rc.d/init.d".  If yours are different, please 
substitue paths accordingly.</u><p>

<ul>
    <li><a name=daemontools><b>daemontools</b><a><p>
        daemontools is the package that will monitor qmail, and smtp services.

        <p>
        Info: <a href=http://cr.yp.to/daemontools.html>http://cr.yp.to/daemontools.html</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        mkdir -p /package<br>
        chmod 1755 /package<br>
        cd /package<br>
        tar -xpzf /var/src/tar/daemontools-0.76.tar.gz<br>
        cd admin/daemontools-0.76<br>
        package/install 
        </font></blockquote>

        To verify that daemontools is running, make sure that `ps ax` reports '/bin/sh /command/svscanboot'
        and 'svscan /service' as running.

        <p><a href=#top>top</a><p>
        <hr>

    <li><a name=ucspi-tcp><b>ucspi-tcp</b><a><p>
        ucspi-tcp contains tcpserver and tcpclient, command line tools for building client-server 
        applications.
        <p>
        Info: <a href=http://cr.yp.to/ucspi-tcp.html>http://cr.yp.to/ucspi-tcp.html</a> 
        <p>

        <p>Install:

        <blockquote><font face=courier size=-1>
        cd /var/src/<br>
        tar -xzf tar/ucspi-tcp-0.88.tar.gz<br>
        cd ucspi-tcp-0.88<br>
        make<br>
        make setup check<br>
        </font></blockquote>

        <a href=#top>top</a><p>
        <hr>

    <li><a name=qmail><b>qmail</b><a><p>
        qmail rocks.  It's a modern smtp server that makes sendmail obsolete.
        <p>
        Info: <a href=http://www.qmail.org>http://www.qmail.org</a>
        <p>

        The patch you will apply below is a composite of existing patches.<br>
        These links are simply for reference, you don't need to download them:
        <ul>
            <li><a href=http://drpepper.org/~zwhite/qmail-1.03-starttls-requireauth.patch>smtp-auth/tls/requireauth</a>
            <li><a href=http://www.ckdhr.com/ckd/qmail-103.patch>over-size dns patch</a>
            <li><a href=http://www.qmail.org/qmailqueue-patch>qmailqueue</a> (for qmail-scanner)
        </ul>

        <p>Install:

        <blockquote><font face=courier size=-1>
        mkdir /var/qmail<br>
        groupadd nofiles<br>
        useradd -g nofiles -d /var/qmail/alias alias<br>
        useradd -g nofiles -d /var/qmail qmaild<br>
        useradd -g nofiles -d /var/qmail qmaill<br>
        useradd -g nofiles -d /var/qmail qmailp<br>
        groupadd qmail<br>
        useradd -g qmail -d /var/qmail qmailq<br>
        useradd -g qmail -d /var/qmail qmailr<br>
        useradd -g qmail -d /var/qmail qmails<br>
        cd /var/src<br>
        tar -xzf tar/qmail-1.03.tar.gz<br>
        tar -xzf tar/toaster-scripts.tar.gz<br>
        cd qmail-1.03<br>
        gunzip -cd ../tar/qmail-1.03.shupp.toaster.patch.gz | patch -p0<br>
        make<br>
        make setup check<p>
        (cd ~alias; touch .qmail-postmaster .qmail-mailer-daemon .qmail-root)<br>
        chmod 644 ~alias/.qmail*<br>
        ./config-fast<p>
        make cert<br>
        # <i>If the above step can't find openssl, edit the path to it in the Makefile</i><br>

        </font></blockquote>

        Configure:

        <blockquote><font face=courier size=-1>
        cd /var/src<br>
        cp toaster-scripts/rc /var/qmail/rc<br>
        chmod 755 /var/qmail/rc<br>
        mkdir /var/log/qmail<br>
        echo ./Maildir/ >/var/qmail/control/defaultdelivery<br>
        cp toaster-scripts/qmailctl /var/qmail/bin/<br>

        <p>
        #<i>Make qmail start at boot time.</i><br>
        <p>
        ln -s ../init.d/qmail /etc/rc.d/rc0.d/K30qmail<br>
        ln -s ../init.d/qmail /etc/rc.d/rc1.d/K30qmail<br>
        ln -s ../init.d/qmail /etc/rc.d/rc2.d/S80qmail<br>
        ln -s ../init.d/qmail /etc/rc.d/rc3.d/S80qmail<br>
        ln -s ../init.d/qmail /etc/rc.d/rc4.d/S80qmail<br>
        ln -s ../init.d/qmail /etc/rc.d/rc5.d/S80qmail<br>
        ln -s ../init.d/qmail /etc/rc.d/rc6.d/K30qmail<br>
        <p>
        ln -s /var/qmail/bin/qmailctl /etc/rc.d/init.d/qmail
        <p>
        chmod 755 /var/qmail/bin/qmailctl<br>
        ln -s /var/qmail/bin/qmailctl /usr/bin<br>
        ln -s /var/qmail/bin/sendmail /usr/sbin/sendmail<br>
        ln -s /var/qmail/bin/sendmail /usr/lib/sendmail<p>

        #<i>Now create the supervise directories/scripts for the qmail services:</i><br>
        mkdir -p /var/qmail/supervise/qmail-send/log<br>
        mkdir -p /var/qmail/supervise/qmail-smtpd/log<br>
        chmod +t /var/qmail/supervise/qmail-send<br>
        chmod +t /var/qmail/supervise/qmail-smtpd<br>
        cp /var/src/toaster-scripts/send.run /var/qmail/supervise/qmail-send/run<br>
        cp /var/src/toaster-scripts/send.log.run /var/qmail/supervise/qmail-send/log/run<br>
        cp /var/src/toaster-scripts/smtpd.run /var/qmail/supervise/qmail-smtpd/run<br>
        cp /var/src/toaster-scripts/smtpd.log.run /var/qmail/supervise/qmail-smtpd/log/run<br>
        echo 20 > /var/qmail/control/concurrencyincoming<br>
        chmod 644 /var/qmail/control/concurrencyincoming<br>
        chmod 755 /var/qmail/supervise/qmail-send/run<br>
        chmod 755 /var/qmail/supervise/qmail-send/log/run<br>
        chmod 755 /var/qmail/supervise/qmail-smtpd/run<br>
        chmod 755 /var/qmail/supervise/qmail-smtpd/log/run<br>
        mkdir -p /var/log/qmail/smtpd<br>
        chown qmaill /var/log/qmail /var/log/qmail/smtpd
        <br>
        </font></blockquote>

        We'll start qmail after installing vpopmail.
        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=vpopmail><b>Vpopmail</b><a><p>

        Vpopmail is a virtual domain package add-on for qmail.  It can handle multiple domains<br>
        on a single IP address, and none of the user accounts are /etc/passwd or "system" accounts.
        <p>
        Info: <a href=http://www.inter7.com/vpopmail>http://www.inter7.com/vpopmail</a><p>

        Because we will only be using vchkpw (the pop authentication tool) with
        qmail-smtpd for SMTP-AUTH, we don't want it to open relays.  The patch 
        applied below fixes this.
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd /var/src<br>
        groupadd vchkpw<br>
        useradd -g vchkpw vpopmail<br>
        chown vpopmail.vchkpw /var/qmail/control/servercert.pem<br>
        tar -xzf tar/vpopmail-5.1.4.tar.gz<br>
        cd vpopmail-5.1.4<br>
        gunzip -cd ../tar/vchkpw-norelay.patch.gz | patch -p0<br>
        ./configure --enable-roaming-users=y --enable-logging=v --enable-clear-passwd=y<br>
        make<br>
        make install-strip<br>
        echo '127.:allow,RELAYCLIENT=""' >/home/vpopmail/etc/tcp.smtp<br>
        qmailctl cdb<p>

        # <i> add the followowing line to your crontab via `crontab -e`<br>
        9-59,10 * * * * /home/vpopmail/bin/clearopensmtp 2>&1 > /dev/null</i><p>

        #<i>allow daemontools to start qmail</i><br>
        ln -s /var/qmail/supervise/qmail-send /var/qmail/supervise/qmail-smtpd /service<p>
        #<i>verify that it's running with qmailctl</i><br>
        qmailctl stat
        </font></blockquote>
        
        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=courier-imap><b>Courier-IMAP</b><a><p>

        Courier-IMAP will supply both IMAP and POP access.  I use its POP3 server now because it
        supports maildirquotas, qmail-pop3d does not.
        <p>
        Info: <a href=http://www.inter7.com/courierimap>http://www.inter7.com/courierimap</a>
        </p>

        Install:

        <blockquote><font face=courier size=-1>
        cd /var/src<br>
        tar -xzf tar/courier-imap-1.4.0.tar.gz<br>
        cp tar/preauthvchkpw.c courier-imap-1.4.0/authlib/<br>
        cd courier-imap-1.4.0<br>
        export CFLAGS="-DHAVE_OPEN_SMTP_RELAY"<p>

        # <i>configure may take some time...</i><br>
        ./configure --disable-root-check --without-authdaemon --with-authvchkpw \<br>
        &nbsp &nbsp --enable-workarounds-for-imap-client-bugs <br>
        make<br>
        make install-strip<br>
        make install-configure<br>
        unset CFLAGS<p>

        cp courier-imap.sysvinit /etc/rc.d/init.d/courier-imap<br>
        chmod 755 /etc/rc.d/init.d/courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc.d/rc0.d/K30courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc.d/rc1.d/K30courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc.d/rc2.d/S80courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc.d/rc3.d/S80courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc.d/rc4.d/S80courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc.d/rc5.d/S80courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc.d/rc6.d/K30courier-imap<br>

        </font></blockquote>

        Configure:

        <blockquote><font face=courier size=-1>
        Edit /usr/lib/courier-imap/etc/imapd
        <ul>
            <li>Change 'AUTHMODULES="..."' to 'AUTHMODULES="authvchkpw"'
            <li>Change 'IMAPDSTART=NO' to 'IMAPDSTART=YES'
        </ul>
        Edit /usr/lib/courier-imap/etc/imapd-ssl
        <ul>
            <li>Change 'IMAPDSSLSTART=NO' to 'IMAPDSSLSTART=YES'
        </ul>
        Edit /usr/lib/courier-imap/etc/pop3d
        <ul>
            <li>Change "AUTHMODULES="..."' to 'AUTHMODULES="authvchkpw"'
            <li>Change 'POP3DSTART=NO' to 'POP3DSTART=YES'
        </ul>
        Edit /usr/lib/courier-imap/etc/pop3d-ssl
        <ul>
            <li>Change 'POP3DSSLSTART=NO' to 'POP3DSSLSTART=YES'
        </ul>

        </font></blockquote>

        Start IMAP and POP3 servers

        <blockquote><font face=courier size=-1>
        /etc/rc.d/init.d/courier-imap start<p>
        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=apache><b>Apache</b><a><p>
        The Apache Web server for webmail and qmailadmin access.  We'll install it with shared 
        object support so that you can add other things later (like PHP) without having to recompile.<p>

        Info: <a href=http://httpd.apache.org>http://httpd.apache.org</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd /var/src<br>
        tar -xzf tar/apache_1.3.22.tar.gz<br>
        cd apache_1.3.22<br>
        ./configure --enable-module=so<br>
        make<br>
        make install<p>
        #<i>Make apache start at boot time.</i><br>
        ln -s /usr/local/apache/bin/apachectl /etc/rc.d/init.d/httpd<br>
        ln -s ../init.d/httpd /etc/rc.d/rc0.d/K30httpd<br>
        ln -s ../init.d/httpd /etc/rc.d/rc1.d/K30httpd<br>
        ln -s ../init.d/httpd /etc/rc.d/rc2.d/S80httpd<br>
        ln -s ../init.d/httpd /etc/rc.d/rc3.d/S80httpd<br>
        ln -s ../init.d/httpd /etc/rc.d/rc4.d/S80httpd<br>
        ln -s ../init.d/httpd /etc/rc.d/rc5.d/S80httpd<br>
        ln -s ../init.d/httpd /etc/rc.d/rc6.d/K30httpd<p>

        #<i> start apache</i><br>
        /usr/local/apache/bin/apachectl start
        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=sqwebmail><b>SqWebmail</b><a><p>
        SqWebmail is a web cgi client that provides direct access to users' mailboxes, bypassing
        the need for a pop or imap client in between.
        <p>
        Info: <a href=http://inter7.com/sqwebmail/>http://inter7.com/sqwebmail</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd /var/src<br>
        tar -xzf tar/sqwebmail-3.2.1.tar.gz<br>
        cd sqwebmail-3.2.1<p>

        # <i>configure may take some time...</i><br>
        ./configure --without-authdaemon --with-authvchkpw<br>
        make<br>
        make install-strip<p>

        # <i>add the following line to your crontab via `crontab -e`<br>
        40 * * * * /usr/local/share/sqwebmail/cleancache.pl 2>&1 > /dev/null</i>

        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=autorespond><b>autorespond</b><a><p>
        Autorespond is compatible autoresponder/vacation type tool that works well with 
        vdelivermail and qmailadmin.
        <p>
        Info: <a href=http://www.inter7.com/devel>http://www.inter7.com/devel</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd /var/src<br>
        tar -xzf tar/autorespond-2.0.2.tar.gz<br>
        cd autorespond-2.0.2<br>
        make<br>
        make install<p>
        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=ezmlm><b>ezmlm-idx</b><a><p>
        Fast, full featured Mailing List Manager configureable from qmailadmin.
        <p>
        Info: <a href=http://www.ezmlm.org>http://www.ezmlm.org</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd /var/src<br>
        tar -xzf tar/ezmlm-0.53.tar.gz<br>
        cd ezmlm-0.53<br>
        gunzip -cd ../tar/idx.shupp.patch.gz | patch -p0<br>
        make<br>
        make setup<p>
        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=qmailadmin><b>qmailadmin</b><a><p>
        Qmailadmin can handle nearly all virtual email administration tasks for you from a 
        web browser, except for adding and removing virtual domains.<p>

        Info: <a href=http://www.inter7.com/qmailadmin>http://www.inter7.com/qmailadmin</a> 
        <p>

        The patch applied below fixes a problem with the default_quota 
        directive used in the .qmailadmin-limits file.
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd /var/src<br>
        tar -xzf tar/qmailadmin-1.0.1.tar.gz<br>
        cd qmailadmin-1.0.1<br>
        ./configure --enable-cgibindir=/usr/local/apache/cgi-bin/<br>
        make<br>
        make install-strip<p>
        </font></blockquote>

        Your toaster installation is done!  Now we're ready to take it for a test drive.

        <p><a href=#top>top</a><p>

        <hr>

</ul>

<h2><a name=test>Test Drive</a></h2>

Here we'll add a virtual domain, 'test.com', and test sending/receiving mail.  Substitue 'test.com' for whatever domain you setup DNS for.
<p>

    <blockquote><font face=courier size=-1>
    # <i>Add the domain to vpopmail</i><br>
    /home/vpopmail/bin/vadddomain test.com [password]<br>
    </font></blockquote>

This creates the default "postmaster" account for test.com.  You will use this
account with qmailadmin.  Try adding/removing users with qmailadmin here:<p>

    <blockquote><font face=courier size=-1>
    http://mail.test.com/cgi-bin/qmailadmin
    </font></blockquote>


To test out quota usage support, create a user with a 6MB quota like so:

    <blockquote><font face=courier size=-1>
    /home/vpopmail/bin/vadduser -q 6000000000S user@test.com [password]<p>
    # verify the user settings, and create the "maildirsize" file<br>
    /home/vpopmail/bin/vuserinfo user@test.com
    </font></blockquote>

Now, to log into sqwebmail as user@test.com, point your browser here:
        
    <blockquote><font face=courier size=-1>
    http://mail.test.com/cgi-bin/sqwebmail
    </font></blockquote>

Send yourself a message.  If you get it, it's likely you're up and running.<p>

To test your POP server, try telnetting to port 110 and logging in.

    <blockquote><font face=courier size=-1>
    # telnet localhost 110<br>
    Trying 127.0.0.1...<br>
    Connected to localhost.localdomain.<br>
    Escape character is '^]'.<br>
    +OK Hello there.<br>
    user user@test.com<br>
    +OK Password required.<br>
    pass [password]<br>
    +OK logged in.<br>
    quit<br>
    +OK Bye-bye.<br>
    Connection closed by foreign host.<br>
    </font></blockquote>

Test your IMAP server in the same way:<p>

    <blockquote><font face=courier size=-1>
    # telnet localhost 143<br>
    Trying 127.0.0.1...<br>
    Connected to localhost.localdomain.<br>
    Escape character is '^]'.<br>
    * OK Courier-IMAP ready. Copyright 1998-2001 Double Precision, Inc.  See COPYING for distribution information.<br>
    a001 login user@test.com [password]<br>
    a001 OK LOGIN Ok.<br>
    a001 logout<br>
    * BYE Courier-IMAP server shutting down<br>
    a001 OK LOGOUT completed<br>
    Connection closed by foreign host.<br>
    </font></blockquote>

    <p><a href=#top>top</a><p>
    <hr>
<h2><a name=Options>Options</a></h2>

<ul>
    <li><a name=qmail-scanner><b>Qmail Scanner</b></a><p>
    Your qmail installation is already patched (qmail-queue patch) to support 
    qmail-scanner, a popular tool for using commercial virus scanners with 
    qmail. Go to 
    <a href=http://qmail-scanner.sourceforge.net>http://qmail-scanner.sourceforge.net</a> for installation instructions.<p>

    To make SqWebmail use the virus scanner as well, you'll need to edit<br>
    /usr/local/share/sqwebmail/sendit.sh<br>
    and add these 2 lines above the "exec" statement:

    <blockquote><font face=courier size=-1>
    QMAILQUEUE="/var/qmail/bin/qmail-scanner-queue.pl"<br>
    export QMAILQUEUE<br>
    </font></blockquote>

    <li><a name=limits><b>Qmailadmin Limits</b></a><p>

    Qmailadmin can set per domain limits/defaults, which is really useful
    for hosting companies with different mail packages.  This is covered in 
    detail in the INSTALL file of qmailadmin (/var/src/qmailadmin-1.0/INSTALL).

</ul>

    <p><a href=#top>top</a><p>
    <hr>
<h2><a name=appendix>Appendix</a></h2>

<ul>
    <li><a name=troubleshooting><b>Troubleshooting</b></a><p>
        <ul>
            <li>Subscribe to this toaster list: <a href=mailto:toaster-subscribe@shupp.org>toaster-subscribe@shupp.org</a><p>
            <li>It's recommended that you join the mailing list for 
            vpopmail (<a href=mailto:vchkpw-subscribe@inter7.com>vchkpw-subscribe@inter7.com</a>), since this is the core of the virtual domain package.<p>
            <li>Also, Life With Qmail (see links below) covers qmail setup/maintenance in great detail.  Make sure you read it.<br><p>

        </ul>

    <li><a name=credits><b>Credits</b></a><p>
        <ul>
            <li>This document is certainly inspired by Matt Simerson's FreeBSD 
                <a href=http://matt.simerson.net/computing/mail/toaster>Mail 
                Toaster</a>.  If you use FreeBSD, use it instead, it's great.<p>

            <li>Most of the commands listed in the steps above are derived 
                either directly from the documentation or
                <a href=http://www.lifewithqmail.org>Life With Qmail</a>.<p>

            <li>This toaster was put together by Bill Shupp (hostmaster@shupp.org).<p><br>

        </ul>
    <li><a name=links><b>Links</b></a><p>
        <ul>
            <li><b>Software</b>
            <ul>
                <li>
                <li>daemontools: <a href=http://cr.yp.to/daemontools.html>http://cr.yp.to/daemontools.html</a>
                <li>ucspi-tcp: <a href=http://cr.yp.to/ucspi-tcp.html>http://cr.yp.to/ucspi-tcp.html</a> 
                <li>Vpopmail: <a href=http://www.inter7.com/vpopmail>http://www.inter7.com/vpopmail</a>
                <li>Courier-IMAP: <a href=http://www.inter7.com/courierimap>http://www.inter7.com/courierimap</a>
                <li>Apache: <a href=http://httpd.apache.org>http://httpd.apache.org</a>
                <li>SqWebmail: <a href=http://inter7.com/sqwebmail/>http://inter7.com/sqwebmail</a>
                <li>Autorespond: <a href=http://www.inter7.com/devel>http://www.inter7.com/devel</a>
                <li>Ezmlm: <a href=http://www.ezmlm.org>http://www.ezmlm.org</a>
                <li>Qmailadmin: <a href=http://www.inter7.com/qmailadmin>http://www.inter7.com/qmailadmin</a><p>
            </ul>

        <li><b>Resources</b>
            <ul>
                <li><a href=http://www.lifewithqmail.org>Life With Qmail</a>
                <li><a href=http://matt.simerson.net/computing/mail/toaster>Qmail FreeBSD Toaster</a> (MySQL)
            </ul>
        </ul><p><br>
    <li><a name=changelog><b>ChangeLog</b></a><p>
        <b>0.3</b> - 1/3/2002
        <ul>
            <li>Use vpopmail-5.1.4 and qmailadmin 1.0.1
            <li>remove unnecessary patches
        </ul><p>
        <b>0.2</b> - 12/26/2001
        <ul>
            <li>fixed broken link in download section
            <li>added patch for quota problems in vpopmail-5.1.3
        </ul><p>

        <b>0.1</b> - 12/24/2001
        <ul>
            <li>initial release
        </ul><p>
</ul>
        
</body>
</html>
