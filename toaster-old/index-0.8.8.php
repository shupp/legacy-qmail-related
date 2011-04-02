<?php

ini_set('session.use_trans_sid',1);
// Setup session
session_name('toaster_session');
session_start();

// Location stuff
if(!isset($_REQUEST['varsrc']) && !isset($_SESSION['varsrc'])) {
	$varsrc = '/var/src';
} else {
	if(isset($_REQUEST['varsrc'])) {
		$varsrc = $_REQUEST['varsrc'];
		$_SESSION['varsrc'] = $varsrc;
	} else if(isset($_SESSION['varsrc'])) {
		$varsrc = $_SESSION['varsrc'];
	} else {
		$varsrc = '/var/src';
	}
}

// set versions

$tversion = '0.8.8';
$daemontools = '0.76';
$ucspitcp = '0.88';
$netqmail = "1.05";
$toasterpatch = "0.8.2";
// $chkuser = "0.6";
// $vpopmail = "5.4.10";
$vpopmail = "5.4.13";
$vpopmail_patch = "vpopmail-5.4.13-cumulative-1.patch";
$autorespond = "2.0.4";
$qmailadmin = "1.2.10";
$qmailadminhelp = "1.0.8";
$ezmlm = "0.53";
// $ezmlmidx = "5.0.2";
$ezmlmidx = "0.443";
$courierauthlib = "0.58";
$courierimap = "4.1.0";
$squirrelmail = "1.4.6";
$quotausage = "1.3.1-1.2.7";
$toasterscripts = "0.8.1";
$ripmime = "1.4.0.6";
$simscan = "1.2";
$clamav = "0.88.2";
$qmailmrtg7 = "4.2";
$tmda = "1.0.3";
$tmdacgi = "0.13";
$tmdacgipatch = "0.13";

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="STYLESHEET" type="text/css" href="/core-style-beta.css">

<title>Bill's Linux Qmail Toaster</title>
<body>
<a name="top"></a>
<center>
<table border=0 width="90%">
<tr><td>

<center>
<script type="text/javascript"><!--
google_ad_client = "pub-9237944494922065";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_channel ="";
google_color_border = "056614";
google_color_bg = "FFFFFF";
google_color_link = "056614";
google_color_url = "000000";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>


<table border=0>
    <tr valign="middle"><td><img src="/images/qmail.gif" alt="qmail logo" border=0></td>
    <td><h2>Bill's Linux Qmail Toaster</h2>

	<table border=0>
	<tr>
    	<td>Version: <? echo $tversion?> <a href=#changelog>ChangeLog</a><br> 
    	Last modified: <? echo strftime("%b %d, %Y %H:%M", filemtime("index-$tversion.php"))?>
        <p><br>
        An Italian Translation can be found <a href="http://www.andreaboscolo.it/toaster/">here</a>
    	</td>
    	<td valign="top"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
      	<input type="hidden" name="cmd" value="_xclick">
      	<input type="hidden" name="business" value="hostmaster@shupp.org">
      	<input type="hidden" name="item_name" value="Shupp.Org Support Donation">
      	<input type="hidden" name="no_shipping" value="1">
      	<input type="hidden" name="cancel_return" value="http://shupp.org/toaster/">
      	<input type="image" vspace=2 hspace=5 align=left src="/images/paypal-donate.gif" border="0" name="submit" 
      	alt="Make payments with PayPal - it's fast, free and secure!">
    	</form>
    	</td>
  	<tr>
	</table>
	<td><img src="/images/penguin.gif" alt="linux logo" border=0></td>
	</td>
    </tr>
</table>
</center>


<table border=0>

<br>

<!-- Outline -->
<h2><a href=#preface>Preface</a></h2>
<ul>
    <li><a href=#whatisatoaster>What's a POP toaster?<a><br>
    <li><a href=#what>What this toaster does and does not do<a><br>
    <li><a href=#assumptions>Assumptions/Support (Please Read!)</a><br>
    <li><a href=#prerequisites>Prerequisites</a>
    <li><a href=#debian>Debian Notes</a>
    <li><a href=#trustix>Trustix Notes</a>
    <li><a href=#license>Toaster License</a>
</ul>

<h2><a href=#gettingstarted>Getting Started</a></h2>
<ul>
    <li><a href=#dns>DNS</a>
    <li><a href=#remove>Remove existing sendmail/pop/imap servers</a>
    <li><a href=#download>Download packages</a>
</ul>

<h2><a href=#install>Install Software</a></h2>
<ul>
    <li><a href=#daemontools>daemontools</a>
    <li><a href=#ucspi-tcp>ucspi-tcp</a>
    <li><a href=#qmail>qmail</a>
    <li><a href=#vpopmail>vpopmail</a>
    <li><a href=#courier-imap>courier-imap</a>
    <!-- <li><a href=#apache>apache</a> -->
    <!-- <li><a href=#sqwebmail>sqwebmail</a> -->
    <li><a href=#squirrelmail>squirrelmail</a>
    <li><a href=#autorespond>autorespond</a>
    <li><a href=#ezmlm>ezmlm-idx</a>
    <li><a href=#qmailadmin>qmailadmin</a>
</ul>

<h2><a href=#test>Test Drive</a></h2>

<h2><a href=#options>Options</a></h2>
<ul>
    <li><a href=#spamassassin>SpamAssassin</a>
    <li><a href=#clamav>ClamAV</a>
    <li><a href=#ripmime>ripmime</a>
    <li><a href=#simscan>simscan</a>
    <li><a href=#tmda>TMDA</a>
    <li><a href=#qmailmrtg7>Qmailmrtg7 - MRTG Graphs</a>
    <li><a href=#limits>Qmailadmin Limits</a>
</ul>

<h2><a href=#appendix>Appendix</a></h2>
    <li><a href=#donate>Donate</a>
    <li><a href=#troubleshooting>Troubleshooting</a>
    <li><a href=#credits>Credits</a>
    <li><a href=#resources>Resources</a>
    <li><a href=#success>Success Reports</a>
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
        This "howto" will walk you through building a Linux Qmail "Toaster".  
        While these instructions are intended to work with popular Linux 
        distributions, they will probably work on other flavors of Unix 
        without too much modification.<p>

        Here's a list of features you'll get:

        <ul>
            <li>Qmail SMTP Mail Server with SMTP-AUTH (Plain, Login, CRAM-MD5), TLS (SSL) support, and optional 
                Virus/Spam Scanner.
            <li>POP3 Server with CRAM-MD5, APOP, and SSL support
            <li>IMAP Server with TLS (SSL) support
            <li>WebMail Server
            <li>Quota Support (usage viewable by webmail)
            <li>Autoresponder
            <li>Mailing Lists
            <li>Web-Based Email Administration<p>
        </ul>

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

        <b>Commercial support <u>is</u> available</b>. See <a href=http://merchbox.com/qmail.php>http://merchbox.com/qmail.php</a> for more information.  There are
        also other sources of commercial support for the individual packages.  See the respective 
        documentation for each package for further information.<p>
        <a href=#top>top</a><p>

    <li><a name=prerequisites><b>Prerequisites</b></a><p>
        If you have installed a recent version of your Linux distribution, you shouldn't have any 
        problems, especially if you did a "server" type of install rather than "Desktop".  However, this install DOES require that you have the apache web server and PHP installed.  Most distributions come with these now.  PHP is only required for SquirrelMail.
        The main issues you might see are missing development libraries, or gdbm, stunnel, kerberos dev files, or openssl.  If you are using an rpm based distro, an easy way to make sure they are installed is to use <a href="http://linux.duke.edu/projects/yum/">yum</a>, which is likely installed already.  Make sure the packages get installed if necessary using this command:

        <blockquote><font face=courier size=-1>
        yum install  gcc g++ gcc-g++ gdbm gdbm-devel openssl openssl-devel stunnel krb5-devel bzip2 bzip2-devel<br>
        </font></blockquote>

        <b>SELinux</b>: Some setuid programs, like QmailAdmin, will not work with SELinux.  You should disable SELinux during installation of the OS.  If you have already installed the OS, you should disable it.  I have also seen SELinux prevent SquirrelMail from working properly under Apache.
	In Fedora Core 4, I was able to disable it by setting "SELINUX=disabled" in /etc/sysconfig/selinux and rebooting.<p>

	<b>Aliases</b>
	Some distributions setup aliases for common commands that might interfere with this install.  I recommend unaliasing some of the more common ones if you are unsure:
	<blockquote>
	unalias rm mv cp
	</blockquote>

        This orignial version of this document was written using RedHat Linux 9.0.  However, I now use debian exclusively now, and I fully recommend it.  Once you get comfortable with apt, you'll never go back!<p>
        <a href=#top>top</a><p>

	<li><a name="debian"<b>Debian notes:</b></a><p>
	The easy way to make sure you have all the proper debian packages installed is to just execute this command:

	<blockquote>
	apt-get install libgdbm-dev gcc g++ patch make libc-dev stunnel libssl-dev apache php4 wget bzip2
	</blockquote>

	In addition, you probably want these things installed as well:
	<blockquote>
	apt-get install man telnet host
	</blockquote>

	Lots of Debian packages require an MTA, and Debian defaults to exim4.  This obviously can be a problem for qmail.  So lets fix that with a dummy equivs package:
	<blockquote>
	#<i> install equivs</i><br>
	apt-get install equivs<p>

	#<i> build the dummy mta package</i><br>
	cd /tmp<br>
	cp /usr/share/doc/equivs/examples/mail-transport-agent.ctl .<br>
	equivs-build mail-transport-agent.ctl<p>

	#<i> remove exim4</i><br>
	dpkg --ignore-depends=exim4 -r exim4<br>
	dpkg --ignore-depends=exim4-daemon-light -r exim4-daemon-light<br>
    dpkg --ignore-depends=exim4-base -r exim4-base<br>
    dpkg --ignore-depends=exim4-base -r exim4-base<p>
	#<i> and install the dummy mta package</i><br>
	dpkg -i /tmp/mta-local_1.0_all.deb
	</blockquote>

	Courier-imap's startup script likes to use a different lock directory, so we'll just create it:
	<blockquote>
	mkdir /var/lock/subsys
	</blockquote>

	Courier's programs want gmake, which is actually make on debian.  Let's alias it:
	<blockquote>
	alias gmake='make'
	</blockquote>

	Apache's default config (for apache 1 anyway), may cause us some problems later.  Let's fix them now:

	<blockquote class="edit">
	Edit /etc/apache/httpd.conf
	<ul>
		<li>Remove comments in front of the "AddType" directives for .php and .phps file extensions if they exist
		<li>Comment out the default "Alias" directive pointing "/images/" to "/usr/share/images/"
	</ul>
	</blockquote>

	Apache's DocumentRoot is in /var/www instead of /var/www/html.  What I do is use that, and put SquirrelMail's data directory in /etc/apache.<p>
	Apache's cgi-bin directory by default is /usr/lib/cgi-bin.  You should use that path when configuring qmailadmin.<p>

	SpamAssassin needs many perl libraries.  If you plan to use SpamAssassin, then install these libraries now:
	<blockquote>
	apt-get install libdigest-sha1-perl libnet-dns-perl \<br>
	libmail-spf-query-perl libgeo-ipfree-perl razor pyzor libnet-ident-perl \<br>
	libio-socket-ssl-perl libarchive-tar-perl libio-zlib-perl \<br>
	libsys-hostname-long-perl
	</blockquote>

	ClamAV requires the zlib and zlib-dev packages:
	<blockquote>
	apt-get install zlib1g zlib1g-dev libbz2-dev libgmp3 libgmp3-dev
	</blockquote>

        <a href=#top>top</a><p>
	<li><a name="trustix"<b>Trustix notes:</b></a><p>
        Matthew Valentini provided some <a href="trustix_notes.eml.html">notes</a> on using this toaster with Trustix 3.0.  I have not tested them, I'm just making them available as-is.<p>
        <a href=#top>top</a><p>

    <li><a name=license><b>Toaster License</b></a><p>
        This document is covered by the same license as <a href=http://lifewithqmail.org>Life With Qmail</a>, and the license is detailed here:<br>

        <a href=http://www.opencontent.org/opl.shtml>http://www.opencontent.org/opl.shtml</a>
 </ul>


<a name=gettingstarted><h2>Getting Started</h2></a>

<ul>
    <li><a name=dns><b>DNS</b></a><p>

        Before we begin, make sure DNS (mx record) is properly setup. If you were using "merchbox.com" as your virtual domain, here's how your host lookups would look after setting up dns:<br><br>

    <blockquote><font face=courier size=-1>
        [shupp@ns1 toaster]$ host -t mx merchbox.com<br>
        merchbox.com. mail is handled by 0 mail.merchbox.com.<br>
        [shupp@ns1 toaster]$ host -t a mail.merchbox.com<br>
        mail.merchbox.com. has address 216.234.249.114<br>
    </blockquote></font>
        <p>

    <li><a name=remove><b>Remove existing smtp/pop/imap servers</b><a><p>
        Now we must remove any existing installations of sendmail/postfix and
        disable pop/imap servers.  To remove sendmail and postfix from
        an rpm based distribution, try this:

        <blockquote><font face=courier size=-1>
        rpm -e --nodeps sendmail postfix<br>
        </font></blockquote>

        Unless you have other services that absolutely have to run on this machine, I recommend
        shutting down inetd or xinetd altogether and removing it from your startup scripts.  The
        only thing you'll need (outside of what we're about to isntall) is ssh, which is 
        probably installed already.  This will likely shut off any pop3 or imap servers, as well as 
        other unneccessary ports.  Otherwise, disable them manually.<p>

        To be sure that these services are disabled, try telnetting to ports 25, 110, and 143 
        and make sure your connections are refused.
        <p><a href=#top>top</a><p>

    <li><a name=download><b>Download packages</b><a><p>
        I keep my software source in /var/src.  If you want to put it in another location, set the location below, and all paths will be updated. <p>
	<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
	<input type="text" name="varsrc" value="<?php echo $varsrc?>"> <input type="submit" value="set">
	</form>
        <p><u>Some of this is version dependent, so please stick to the URLs below!</u>

        <blockquote><font face=courier size=-1>
        umask 0022<br>
        mkdir -p <?php echo $varsrc?>/tar<br>
        cd <?php echo $varsrc?>/tar<p>
        wget http://cr.yp.to/daemontools/daemontools-<?php echo $daemontools?>.tar.gz<br>
        wget http://cr.yp.to/ucspi-tcp/ucspi-tcp-<?php echo $ucspitcp?>.tar.gz<br>
        wget http://shupp.org/software/netqmail-<?php echo $netqmail?>.tar.gz<br>
        wget http://shupp.org/patches/qmail-toaster-<?echo $toasterpatch?>.patch.bz2<br>
        wget http://shupp.org/software/vpopmail-<?php echo $vpopmail?>.tar.gz<br>
        wget http://shupp.org/patches/<?php echo $vpopmail_patch?><br>
        wget http://shupp.org/software/autorespond-<?php echo $autorespond?>.tar.gz<br>
        wget http://shupp.org/patches/autorespond-2.0.4-2.0.5.patch<br>
        wget http://shupp.org/software/qmailadmin-<?php echo $qmailadmin?>.tar.gz<br>
        wget http://shupp.org/software/qmailadmin-help-<?php echo $qmailadminhelp?>.tar.gz<br>
        wget http://cr.yp.to/software/ezmlm-<?php echo $ezmlm?>.tar.gz<br>
        wget http://shupp.org/software/ezmlm-idx-<?php echo $ezmlmidx?>.tar.gz<br>
        <!-- wget http://telia.dl.sourceforge.net/sourceforge/courier/courier-imap-<?php echo $courierimap?>.tar.bz2<br> -->
        wget http://shupp.org/software/courier-imap-<?php echo $courierimap?>.tar.bz2<br>
        wget http://shupp.org/software/courier-authlib-<?php echo $courierauthlib?>.tar.bz2<br>
        wget http://shupp.org/software/squirrelmail-<?php echo $squirrelmail?>.tar.bz2<br>
        wget http://shupp.org/software/quota_usage-<?php echo $quotausage?>.tar.gz<br>
        wget http://shupp.org/software/toaster-scripts-<?php echo $toasterscripts?>.tar.gz<p>
        <!-- wget http://shupp.org/patches/ezmlm-idx-0.53.400.unified_41.patch -->
        cd ../<br>
        tar -xzf tar/netqmail-<?php echo $netqmail?>.tar.gz<br>
        cd netqmail-<?php echo $netqmail?><br>
        ./collate.sh

        
        </font></blockquote>
        <a href=#top>top</a><p>

</ul>

<a name=install><h2>Install Software</h2></a>

Now that you have downloaded all the software packages to <?php echo $varsrc?>/tar, please go through each of 
these installation steps as the appear, and in this order, unless you <i>really</i> know what 
you're doing.  (Because if you did, you wouldn't be reading this, right?)<p>

<u>The below steps assume that your "rc" directories are in /etc/ and 
your "init.d" path is "/etc/init.d".  If yours are different, please 
substitue paths accordingly.</u><p>

<ul>
    <li><a name=daemontools><b>daemontools</b><a><p>
        daemontools is a collection of tools for managing UNIX services.
        It will monitor qmail-send, and qmail-smtpd, and qmail-pop3d services.

        <p>
        Info: <a href=http://cr.yp.to/daemontools.html>http://cr.yp.to/daemontools.html</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        mkdir -p /package<br>
        chmod 1755 /package<br>
        cd /package<br>
        tar -xpzf <?php echo $varsrc?>/tar/daemontools-<?php echo $daemontools?>.tar.gz<br>
        cd admin/daemontools-<?php echo $daemontools?><br>
        patch -p1 < <?php echo $varsrc?>/netqmail-<?php echo $netqmail?>/other-patches/daemontools-<?php echo $daemontools?>.errno.patch<br>
        package/install<p>

        # <i>add the "clear" service</i><p>

        cd ../<br>
        mkdir clear<br>
        touch clear/down<p>

        cat > clear/run <?= htmlspecialchars("<<")?>EOF <br>
        #!/bin/sh<br>
        yes '' | head -4000 | tr '\n' .<p>

        # When you want to clear the service errors, just run this:<br>
        # svc -o /service/clear<br>
        EOF<p>

        chmod +x clear/run<br>
        chmod a-w clear/down<p>

        ln -s /package/admin/clear /service/clear<br>
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
        cd <?php echo $varsrc?>/<br>
        tar -xzf tar/ucspi-tcp-0.88.tar.gz<br>
        cd ucspi-tcp-0.88<br>
        patch -p1 < <?php echo $varsrc?>/netqmail-<?php echo $netqmail?>/other-patches/ucspi-tcp-0.88.errno.patch<p>
        # <i>NOTE: If you are on the <span class="error">x86_64</span> platform, you need to remove the<br>
        # "-02" argument to gcc in conf-cc.  See <a href="http://marc.theaimsgroup.com/?l=qmail&m=111725518121864&w=2">this</a> for details</i><p>
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
        <ul>
            <li>smtp auth 
            <li>spf
            <li>qmail-queue (to allow for virus scanners)     
            <li>maildir++ patch
            <li>support oversize dns packets (not necessary if you use dnscache)
            <li>chkuser (check for local users, envelope syntax) with user extensions enables (for TMDA)
            <li>spam throttle
            <li>mfcheck (check for valid sender domain)
            <li>spam throttle
            <li>qregex (regular expression matching in badmailfrom and badmailto)     
            <li>big concurrency (set the spawn limit above 255)     
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
        useradd -g qmail -d /var/qmail qmails<p>

        cd <?php echo $varsrc?><br>
        tar -xzf tar/toaster-scripts-<?php echo $toasterscripts?>.tar.gz<br>
        cd netqmail-<?php echo $netqmail?>/netqmail-<?php echo $netqmail?>/<br>
        <p>#<i> NOTE: RedHat/Fedora users may need to link certain include files for the TLS patch.<br>
        # Issue the command below only if make fails:<br>
        ln -s /usr/kerberos/include/com_err.h /usr/kerberos/include/krb5.h /usr/kerberos/include/profile.h /usr/include/<br>
        # as well as remove the sendmail link if it still exists:<br>
        rm /usr/sbin/sendmail</i><p>

        make<br>
        make setup check<br>
        #<i> NOTE: qmail will be patched AFTER vpopmail is installed</i><p>

        # <i>turn on SPF checking</i><br>
        echo 3 > /var/qmail/control/spfbehavior<p>

        # <i>turn on mfcheck</i><br>
        echo 1 > /var/qmail/control/mfcheck<p>

        # <i>Setup the primary administrator's email address.<br>
        # This address will receive mail for root, postmaster, and mailer-daemon.<br>
        # Replace "admin@example.com" with your email address</i><br>
        (cd ~alias;  echo "admin@example.com" > .qmail-postmaster ;\<br>
        echo "admin@example.com" > .qmail-mailer-daemon ;\<br>
        echo "admin@example.com" > .qmail-root )<br>
        chmod 644 ~alias/.qmail*<p>

        
        <i># on the next line replace "full.hostname" with the hostname of your mail server</i><br>
        ./config-fast <i>full.hostname</i><p>

        </font></blockquote>

        Configure:

        <blockquote class="edit"><font face=courier size=-1>
        #<i> add qmail man pages to MANPATH</i><p>
        <ul><li>Edit /etc/man.config
            <li>Add "MANPATH /var/qmail/man"<br>
		<i>NOTE: Debian users should set MANDATORY_MANPATH rather than MANPATH</i><p>
        </ul>
        </font></blockquote>

        <blockquote><font face=courier size=-1>
        cd <?php echo $varsrc?><br>
        cp toaster-scripts-<?php echo $toasterscripts?>/rc /var/qmail/rc<br>
        chmod 755 /var/qmail/rc<br>
        mkdir /var/log/qmail<br>
        echo ./Maildir/ >/var/qmail/control/defaultdelivery<br>
        cp toaster-scripts-<?php echo $toasterscripts?>/qmailctl /var/qmail/bin/<br>
        chmod 755 /var/qmail/bin/qmailctl<br>
        ln -s /var/qmail/bin/qmailctl /usr/bin<br>
        ln -s /var/qmail/bin/sendmail /usr/sbin/sendmail<br>
        ln -s /var/qmail/bin/sendmail /usr/lib/sendmail<p>

        #<i>Now create the supervise directories/scripts for the qmail services:</i><br>
        mkdir -p /var/qmail/supervise/qmail-send/log<br>
        mkdir -p /var/qmail/supervise/qmail-smtpd/log<br>
        mkdir -p /var/qmail/supervise/qmail-pop3d/log<br>
        mkdir -p /var/qmail/supervise/qmail-pop3ds/log<br>
        cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/send.run /var/qmail/supervise/qmail-send/run<br>
        cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/send.log.run /var/qmail/supervise/qmail-send/log/run<br>
        cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/smtpd.run /var/qmail/supervise/qmail-smtpd/run<br>
        cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/smtpd.log.run /var/qmail/supervise/qmail-smtpd/log/run<br>
        cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/pop3d.run /var/qmail/supervise/qmail-pop3d/run<br>
        cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/pop3d.log.run /var/qmail/supervise/qmail-pop3d/log/run<p>

        #<i> NOTE:  If you are using stunnel version 4, you should use pop3ds.run.v4 <br>
        # instead of pop3ds.run below.  <br>
        # Type "stunnel -V" (v. 3) or "stunnel -version" (v. 4) to see what version is installed.<br>
        # You will also need to copy over stunnel.conf like so: <br>
        #<br>
        # cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/stunnel.conf /var/qmail/supervise/qmail-pop3ds/</i><p>

        cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/pop3ds.run /var/qmail/supervise/qmail-pop3ds/run<p>

        cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/pop3ds.log.run /var/qmail/supervise/qmail-pop3ds/log/run<br>
        echo 20 > /var/qmail/control/concurrencyincoming<br>
        chmod 644 /var/qmail/control/concurrencyincoming<br>
        chmod 755 /var/qmail/supervise/qmail-send/run<br>
        chmod 755 /var/qmail/supervise/qmail-send/log/run<br>
        chmod 755 /var/qmail/supervise/qmail-smtpd/run<br>
        chmod 755 /var/qmail/supervise/qmail-smtpd/log/run<br>
        chmod 755 /var/qmail/supervise/qmail-pop3d/run<br>
        chmod 755 /var/qmail/supervise/qmail-pop3d/log/run<br>
        chmod 755 /var/qmail/supervise/qmail-pop3ds/run<br>
        chmod 755 /var/qmail/supervise/qmail-pop3ds/log/run<br>
        mkdir -p /var/log/qmail/smtpd<br>
        mkdir -p /var/log/qmail/pop3d<br>
        mkdir -p /var/log/qmail/pop3ds<br>
        chown -R qmaill /var/log/qmail<br>
        <br>

        #<i>allow daemontools to start qmail</i><br>
        ln -s /var/qmail/supervise/qmail-send /var/qmail/supervise/qmail-smtpd /service<p>
        #<i>verify that it's running with qmailctl</i><br>
        sleep 5<br>
        qmailctl stat
        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=vpopmail><b>Vpopmail</b><a><p>

        Vpopmail is a virtual domain package add-on for qmail.  It can handle multiple domains<br>
        on a single IP address, and none of the user accounts are /etc/passwd or "system" accounts.
        <p>
        Info: <a href=http://vpopmail.sf.net/>http://vpopmail.sf.net</a><p>

        Install:

        <blockquote><font face=courier size=-1>
        groupadd -g 89 vchkpw<br>
        useradd -u 89 -g vchkpw vpopmail<p>

        cd <?php echo $varsrc?><br>

        tar -xzf tar/vpopmail-<?php echo $vpopmail?>.tar.gz<br>
        cd vpopmail-<?php echo $vpopmail?><br>
        patch -p0 < ../tar/<?php echo $vpopmail_patch?><p>

        # <i>NOTE:  If you are on the <span class="error">x86_64</span> platform, you need to set the <br>
        # CFLAGS compiler environment by prefacing the next command with <br>
        # "CFLAGS=-fPIC", like so: "CFLAGS=-fPIC ./configure ..."</i><p>
        ./configure --enable-logging=v<br>
        make<br>
        make install-strip<p>
        # <i>NOTE:  If you are on the x86_64 platform, you need to edit <br>
        # cdb/compile to add the -fPIC argument to cc.  It should look<br>
        # something like this: 'exec gcc -fPIC -02 -c ${1+"$@"}'<br>
        # After editing compile, do "make && make install-strip" again.<br>
        # see <a href="http://forum.qmailrocks.org/showthread.php?p=12293">this post</a> for more details</i><p>
        echo '127.:allow,RELAYCLIENT=""' > ~vpopmail/etc/tcp.smtp<br>
        (cd ~vpopmail/etc ; tcprules tcp.smtp.cdb tcp.smtp.tmp < tcp.smtp)<p>

        <!-- # <i> add the followowing line to your crontab via `crontab -e`<br>
        9-59,10 * * * * /home/vpopmail/bin/clearopensmtp 2>&1 > /dev/null</i><p> -->

        # <i> install the vpopmail start script</i><br>
        cp ../toaster-scripts-<?php echo $toasterscripts?>/vpopmailctl /var/qmail/bin/vpopmailctl<br>
        <p>
        chmod 755 /var/qmail/bin/vpopmailctl<br>
        ln -s /var/qmail/bin/vpopmailctl /usr/bin<br>

        <p># add qmail toaster patch now that vpopmail is installed<br>
        cd <?php echo $varsrc?>/netqmail-<?php echo $netqmail?>/netqmail-<?php echo $netqmail?><br>
        bunzip2 -c ../../tar/qmail-toaster-<?php echo $toasterpatch?>.patch.bz2 | patch -p0<br>

        make clean<br>
        make<br>
        qmailctl stop<br>
        make setup check<p>
        <i># NOTE: the following command needs to be run after any future<br>
        #  re-installs of qmail as it will chown this directory back to qmail</i><p>

        chown -R vpopmail:vchkpw /var/qmail/spam<p>
        make cert<br>
        <i># enter your company's information</i><br>
        make tmprsadh<br>
        <i># NOTE: This may take a LONG time</i><p>
        # <i> now add the followowing line to your crontab via `crontab -e` to update these temp keys each night</i><br>
        01 01 * * * /var/qmail/bin/update_tmprsadh > /dev/null 2>&1<p>

        # <i>start qmail back up</i><br>
        qmailctl start<p>

        #<i>allow daemontools to start vpopmail</i><br>
        ln -s /var/qmail/supervise/qmail-pop3d /var/qmail/supervise/qmail-pop3ds /service<p>

        #<i>verify that it's running with vpopmailctl</i><br>
        sleep 5<br>
        vpopmailctl stat
        </font></blockquote>
        
        <p><a href=#top>top</a><p>

        <hr>
    <li><a name=courier-imap><b>Courier-IMAP</b><a><p>

        Courier-IMAP will supply IMAP/SIMAP access.
        <p>
        Info: <a href=http://www.courier-mta.org/imap/>http://www.courier-mta.org/imap/</a>
        </p>

        Install Courier's Auth Library:
        <blockquote><font face=courier size=-1>
        cd <?php echo $varsrc?><br>
        tar -xjf tar/courier-authlib-<?php echo $courierauthlib?>.tar.bz2<br>
        cd courier-authlib-<?php echo $courierauthlib?><br>
	./configure<br>
	<i># NOTE: RedHat/Fedora users need to add "--with-redhat"</i><p>
	gmake<br>
	gmake install-strip<br>
	gmake install-configure<p>

        cp courier-authlib.sysvinit /etc/init.d/courier-authlib<br>
        chmod 755 /etc/init.d/courier-authlib<br>
        ln -s ../init.d/courier-authlib /etc/rc0.d/K30courier-authlib<br>
        ln -s ../init.d/courier-authlib /etc/rc1.d/K30courier-authlib<br>
        ln -s ../init.d/courier-authlib /etc/rc2.d/S80courier-authlib<br>
        ln -s ../init.d/courier-authlib /etc/rc3.d/S80courier-authlib<br>
        ln -s ../init.d/courier-authlib /etc/rc4.d/S80courier-authlib<br>
        ln -s ../init.d/courier-authlib /etc/rc5.d/S80courier-authlib<br>
        ln -s ../init.d/courier-authlib /etc/rc6.d/K30courier-authlib<br>
        </font></blockquote>

	Configure:

        <blockquote class="edit"><font face=courier size=-1>
        Edit /usr/local/etc/authlib/authdaemonrc
        <ul>
            <li>Change authmodulelist="..." to authmodulelist="authvchkpw"
        </ul>
        </font></blockquote>

        Start Authlib's Authdaemon Server

        <blockquote><font face=courier size=-1>
        /etc/init.d/courier-authlib start<p>
        </font></blockquote>


	Install Courier-IMAP:

        <blockquote><font face=courier size=-1>
        cd <?php echo $varsrc?><br>
        tar -xjf tar/courier-imap-<?php echo $courierimap?>.tar.bz2<br>
        cd courier-imap-<?php echo $courierimap?><br>
        # build as vpopmail<br>
        chown -R vpopmail:vchkpw ../courier-imap-<?php echo $courierimap?><br>
        su vpopmail<br>
        umask 0022<br>

        # <i>configure may take some time...</i><br>
        ./configure <br>
        # <i>NOTE: RedHat/Fedora users need to add "--with-redhat"</i><p>

        # <i>NOTE: If you do not have gmake installed, use "make" below</i><p>
        gmake<br>
        exit<br>
        gmake install-strip<br>
        gmake install-configure<p>

        cp courier-imap.sysvinit /etc/init.d/courier-imap<br>
        chmod 755 /etc/init.d/courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc0.d/K30courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc1.d/K30courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc2.d/S80courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc3.d/S80courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc4.d/S80courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc5.d/S80courier-imap<br>
        ln -s ../init.d/courier-imap /etc/rc6.d/K30courier-imap<br>
        </font></blockquote>

        Configure:

        <blockquote class="edit"><font face=courier size=-1>
        <!-- Edit /usr/lib/courier-imap/etc/authdaemonrc
        <ul>
            <li>Change authmodulelist="..." to authmodulelist="authvchkpw"
        </ul> -->
        Edit /usr/lib/courier-imap/etc/imapd
        <ul>
            <li>Change 'IMAPDSTART=NO' to 'IMAPDSTART=YES'
        </ul>
        Edit /usr/lib/courier-imap/etc/imapd-ssl
        <ul>
            <li>Change 'IMAPDSSLSTART=NO' to 'IMAPDSSLSTART=YES'
        </ul><p>

        # <i>optional: The first time courier-imap is started, the SSL certificate <br>
        # is first created using "localhost" as the "common name".  <br>
        # If you want to change this to match your hostname, you can customize <br>
        # the CN line in /usr/lib/courier-imap/etc/imapd.cnf (and pop3d.cnf of <br>
        # you choose to use courier's pop3d) so that the common name matches <br>
        # your server name BEFORE you start the server for the first time</i><p>
        </font></blockquote>

        Start IMAP server

        <blockquote><font face=courier size=-1>
        /etc/init.d/courier-imap start<p>
        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>

    <!-- <li><a name=apache><b>Apache</b><a><p>
        The Apache Web server for webmail and qmailadmin access.  We'll install it with shared 
        object support so that you can add other things later (like PHP) without having to recompile.<p>

        Info: <a href=http://httpd.apache.org>http://httpd.apache.org</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd <?php echo $varsrc?><br>
        tar -xzf tar/apache_2.0.44.tar.gz<br>
        cd apache_2.0.44<br>
        ./configure --enable-module=so<br>
        make<br>
        make install<p>
        #<i>Make apache start at boot time.</i><br>
        ln -s /usr/local/apache/bin/apachectl /etc/init.d/httpd<br>
        ln -s ../init.d/httpd /etc/rc0.d/K30httpd<br>
        ln -s ../init.d/httpd /etc/rc1.d/K30httpd<br>
        ln -s ../init.d/httpd /etc/rc2.d/S80httpd<br>
        ln -s ../init.d/httpd /etc/rc3.d/S80httpd<br>
        ln -s ../init.d/httpd /etc/rc4.d/S80httpd<br>
        ln -s ../init.d/httpd /etc/rc5.d/S80httpd<br>
        ln -s ../init.d/httpd /etc/rc6.d/K30httpd<p>

        #<i> start apache</i><br>
        /usr/local/apache/bin/apachectl start
        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr> -->

    <!-- <li><a name=sqwebmail><b>SqWebmail</b><a><p>
        SqWebmail is a web cgi client that provides direct access to users' mailboxes, bypassing
        the need for a pop or imap client in between.
        <p>
        Info: <a href=http://inter7.com/sqwebmail/>http://inter7.com/sqwebmail</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd <?php echo $varsrc?><br>
        tar -xzf tar/sqwebmail-3.3.4.tar.gz<br>
        cd sqwebmail-3.3.4<p>

        # <i>configure may take some time...</i><br>
        ./configure --without-authdaemon --with-authvchkpw<br>
        make<br>
        make install-strip<p>

        # <i>add the following line to your crontab via `crontab -e`<br>
        40 * * * * /usr/local/share/sqwebmail/cleancache.pl 2>&1 > /dev/null</i>

        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr> -->

    <li><a name=squirrelmail><b>SquirrelMail</b><a><p>
        SquirrelMail is a web based IMAP client
        <p>
        Info: <a href=http://www.squirrelmail.org/>http://www.squirrelmail.org</a><p>
        NOTE:  This section assumes that your apache ServerRoot is /var/www and that your DocumentRoot is /var/www/html, and also that your web server runs as apache:apache
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd <?php echo $varsrc?><br>
        tar -xjf tar/squirrelmail-<?php echo $squirrelmail?>.tar.bz2<br>
        cd squirrelmail-<?php echo $squirrelmail?><br>
        cd plugins<br>
        tar -xzf ../../tar/quota_usage-<?php echo $quotausage?>.tar.gz<br>
        cp quota_usage/config.php.sample quota_usage/config.php<br>
        cd ../<br>
        ./configure<br>
        # <i> here you will have to set a few options:<br>
            <ul>
                <li>go to Server Settings (2) and change the Server Software from "other" to "courier" (a)
                <li>From the main menu, go to General Options (4) and change Data Direcotry (2) to "/var/www/data/"
                <li>From the main menu, go to Plugins and enable the quota_usage plugin, along with any others you prefer
                <li>Save settings
                <li>quit
            </ul></i><p>

        # <i>move the data directory into place and change permissions to the user:group that the web server runs as:<br></i>
        mv data /var/www/<br>
        chown -R apache:apache /var/www/data<p>

        # <i>install squirrelmail<br></i>
        cd ../<br>
        mv squirrelmail-<?php echo $squirrelmail?> /var/www/html/<p>

        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>
    <li><a name=autorespond><b>autorespond</b><a><p>
        Autorespond is compatible autoresponder/vacation type tool that works well with 
        vdelivermail and qmailadmin.
        <p>
        Info: <a href=http://qmailadmin.sf.net>http://qmailadmin.sf.net</a>
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd <?php echo $varsrc?><br>
        tar -xzf tar/autorespond-<?php echo $autorespond?>.tar.gz<br>
        cd autorespond-<?php echo $autorespond?><br>
        patch -p1 < ../tar/autorespond-2.0.4-2.0.5.patch<br>
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
        cd <?php echo $varsrc?><br>
        tar -xzf tar/ezmlm-<?php echo $ezmlm?>.tar.gz<br>
        tar -xzf tar/ezmlm-idx-<?php echo $ezmlmidx?>.tar.gz<br>
        mv ezmlm-idx-<?php echo $ezmlmidx?>/* ezmlm-<?php echo $ezmlm?>/<br>
        cd ezmlm-<?php echo $ezmlm?><br>
        patch -p0 < idx.patch<br>
        <!-- patch < ../tar/ezmlm-idx-0.53.400.unified_41.patch<br> -->
        make<br>
        make setup<p>
        </font></blockquote>

        <p><a href=#top>top</a><p>

        <hr>

    <li><a name=qmailadmin><b>qmailadmin</b><a><p>
        Qmailadmin can handle nearly all virtual email administration tasks for you from a 
        web browser, except for adding and removing virtual domains.<p>

        Info: <a href=http://sourceforge.net/projects/qmailadmin>http://sourceforge.net/projects/qmailadmin</a> 
        <p>

        Install:

        <blockquote><font face=courier size=-1>
        cd <?php echo $varsrc?><br>
        tar -xzf tar/qmailadmin-<?php echo $qmailadmin?>.tar.gz<br>
        cd qmailadmin-<?php echo $qmailadmin?><br>
        ./configure --enable-help --enable-htmldir=/var/www/html --enable-cgibindir=/var/www/cgi-bin<br>
        make<br>
        make install-strip<p>
        cd ../<br>
        tar -xzf tar/qmailadmin-help-<?php echo $qmailadminhelp?>.tar.gz<br>
        cd qmailadmin-help-<?php echo $qmailadminhelp?><br>
        mkdir /var/www/html/images/qmailadmin/help<br>
        cp -rp * /var/www/html/images/qmailadmin/help
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
    /home/vpopmail/bin/vadduser -q 6MB user@test.com [password]<p>
    # verify the user settings, and create the "maildirsize" file<br>
    /home/vpopmail/bin/vuserinfo user@test.com
    </font></blockquote>

Now, to log into SquirrelMail as user@test.com, point your browser here:
        
    <blockquote><font face=courier size=-1>
    http://mail.test.com/squirrelmail-<?php echo $squirrelmail?>/
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

To test our SSL/TLS connections, all you need to do is duplicate the same tests above, but use openssl's s_client tool to handle encryption.
    <blockquote><font face=courier size=-1>
    # for pop:<br>
    openssl s_client -connect localhost:995<p>
    # for imap:<br>
    openssl s_client -connect localhost:993<p>
    # for smtp/tls:<br>
    openssl s_client -crlf -starttls smtp -connect localhost:25<p>
    </font></blockquote>
    NOTE: If you get an error like this:<p>
    20656:error:14077410:SSL routines:SSL23_GET_SERVER_HELLO:sslv3 alert handshake failure:s23_clnt.c:473:<p>
    your qmail install might need the cipher lists.  Debian does this, I don't know why.  The following commands will fix it:<br>
    <br>

    <blockquote><font face=courier size=-1>
    openssl ciphers > /var/qmail/control/tlsclientciphers<br>
    openssl ciphers > /var/qmail/control/tlsserverciphers<br>
    </font></blockquote>

    <p><a href=#top>top</a><p>
    <hr>
<h2><a name=options>Options</a></h2>

Here, you can add 4 tools that together will prevent viruses from reaching your system via email, and tag spam for client filtering.

<ul>
    <li><a name=spamassassin><b>SpamAssassin</b></a><p>

    SpamAssassin is the leading open source spam scanner.  See <a href="http://www.spamassassin.org/">http://www.spamassassin.org</a> for more details.  The easiest way to install SpamAssassin is from CPAN:<p>

    Install:

    <blockquote>
    cd /root<br>
    <!-- export LANG=en_US<br> -->
    perl -MCPAN -e shell<br>
    o conf prerequisites_policy ask<br>
    install Mail::SpamAssassin<br>
    quit<p>

    #<i> run spamd under daemontools</i><br>
    mkdir -p /var/qmail/supervise/spamd/log<br>
    mkdir -p /var/log/spamd<br>
    chown qmaill /var/log/spamd<br>
    cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/spamd.run /var/qmail/supervise/spamd/run<br>
    cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/spamd.log.run /var/qmail/supervise/spamd/log/run<br>
    chmod 755 /var/qmail/supervise/spamd/run<br>
    chmod 755 /var/qmail/supervise/spamd/log/run<br>
    cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/local.cf /etc/mail/spamassassin/local.cf<br>
    mkdir /etc/mail/spamassassin/.spamassassin/<br>
    chown vpopmail  /etc/mail/spamassassin/.spamassassin/<br>
    ln -s /var/qmail/supervise/spamd /service<p>
    </blockquote>

    <li><a name=clamav><b>ClamAV</b></a><p>
    This open source virus scanner will be called by simscan.  For more 
    information, visit <a href="http://clamav.sf.net">http://clamav.sf.net</a><p>

    NOTE: You need gmp-devel installed to verify the digital signatures of the virus database.<p>

    Install:
    <blockquote>
    groupadd clamav<br>
    useradd -g clamav clamav<br>
    cd <?php echo $varsrc?>/tar<br>
    wget http://shupp.org/software/clamav-<?echo $clamav?>.tar.gz<br>
    wget http://shupp.org/patches/clamav-<?echo $clamav?>-stderr.patch<br>
    wget http://shupp.org/patches/clamav-<?echo $clamav?>-conf.patch<br>
    wget http://shupp.org/patches/clamav-<?echo $clamav?>-freshclamconf.patch<br>
    cd ../<br>
    tar -xzf tar/clamav-<?echo $clamav?>.tar.gz<br>
    cd clamav-<?echo $clamav?><br>
    patch -p0 < ../tar/clamav-<?echo $clamav?>-stderr.patch<br>
    patch -p0 < ../tar/clamav-<?echo $clamav?>-conf.patch<br>
    patch -p0 < ../tar/clamav-<?echo $clamav?>-freshclamconf.patch<br>
    ./configure<br>
    make<br>
    make install<p>

    #<i> setup freshclam</i><br>
    touch /var/log/freshclam.log<br>
    chmod 600 /var/log/freshclam.log<br>
    chown clamav /var/log/freshclam.log<br>
    cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/freshclam /etc/init.d/freshclam<br>
    chmod 755 /etc/init.d/freshclam<br>
    ln -s ../init.d/freshclam /etc/rc0.d/K30freshclam<br>
    ln -s ../init.d/freshclam /etc/rc1.d/K30freshclam<br>
    ln -s ../init.d/freshclam /etc/rc2.d/S80freshclam<br>
    ln -s ../init.d/freshclam /etc/rc3.d/S80freshclam<br>
    ln -s ../init.d/freshclam /etc/rc4.d/S80freshclam<br>
    ln -s ../init.d/freshclam /etc/rc5.d/S80freshclam<br>
    ln -s ../init.d/freshclam /etc/rc6.d/K30freshclam<p>

    # <i>add freshclam.log to logrotate</i><br>
    cp <?php echo $varsrc?>/toaster-scripts-<?php echo $toasterscripts?>/freshclam.logrotate /etc/logrotate.d/freshclam<p>

    #<i> run clamd under daemontools</i><br>
    mkdir -p /var/qmail/supervise/clamd/log<br>
    mkdir -p /var/log/clamd<br>
    chown clamav /var/log/clamd<br>
    cp ../toaster-scripts-<?php echo $toasterscripts?>/clamd.run /var/qmail/supervise/clamd/run<br>
    cp ../toaster-scripts-<?php echo $toasterscripts?>/clamd.log.run /var/qmail/supervise/clamd/log/run<br>
    chmod 755 /var/qmail/supervise/clamd/run<br>
    chmod 755 /var/qmail/supervise/clamd/log/run<p>

    
    #<i> Start clamd and freshclam</i><br>
    ln -s /var/qmail/supervise/clamd /service<br>
    /etc/init.d/freshclam start<p>
    </blockquote>

    <li><a name=ripmime><b>ripmime</b></a><p>
    ripmime is a tool for extracting MIME attachments from email, and is used by qscanq.  See <a href="http://www.pldaniels.com/ripmime/">http://www.pldaniels.com/ripmime/</a> for more details<p>

    Install:
    <blockquote>
    cd <?php echo $varsrc?>/tar<br>
    wget http://shupp.org/software/ripmime-<?php echo $ripmime?>.tar.gz<br>
    cd ..<br>
    tar -xzf tar/ripmime-<?php echo $ripmime?>.tar.gz<br>
    cd ripmime-<?php echo $ripmime?><br>
    make<br>
    make install<br>
    </blockquote>
    
    <li><a name=simscan><b>simscan</b></a><p>
    Your qmail installation is already patched (qmail-queue patch) to support 
    simscan, a new tool for using virus/spam scanners with 
    qmail. The nice thing is that it prevents viruses (and optionally spam) 
    from even getting into your queue. This is 
    different from qmail-scanner, which will quarantine infected messages 
    instead of stopping them at the SMTP level.  Go to 
    <a href="http://inter7.com/?page=simscan">http://inter7.com/?page=simscan</a> 
    for more information.<p>

    Install:
    <blockquote>
    cd <?php echo $varsrc?>/tar<br>
    wget http://shupp.org/software/simscan-<?echo $simscan?>.tar.gz<br>
    wget http://shupp.org/patches/ripmime.txt<br>
    cd ../<br>
    tar -xzf tar/simscan-<?echo $simscan?>.tar.gz<br>
    cd simscan-<?echo $simscan?><br>
    patch -p0 < ../tar/ripmime.txt<p>
    ./configure --enable-user=clamav \<br>
    --enable-clamav=y \<br>
    --enable-spam=y \<br>
    --enable-spam-passthru=y \<br>
    --enable-per-domain=y \<br>
    --enable-ripmime \<br>
    --enable-attach=y \<br>
    --enable-received=y<p>

    make<br>
    make install-strip<p>

    #<i> add default rules for simscan</i><br>
    echo ":clam=yes,spam=yes,spam_passthru=yes,attach=.vbs:.lnk:.scr:.wsh:.hta:.pif" > /var/qmail/control/simcontrol<br>
    #<i> update /var/qmail/control/simcontrol.cdb</i><br>
    /var/qmail/bin/simscanmk<br>
    #<i> put versions for received header in /var/qmail/control/simversions.cdb</i><br>
    /var/qmail/bin/simscanmk -g<p>

    <!-- cp ../toaster-scripts-<?echo $toasterscripts?>/ssattach /var/qmail/control/<p> -->


    #<i> turn on scanning</i><br>
    echo ':allow,QMAILQUEUE="/var/qmail/bin/simscan"' >> ~vpopmail/etc/tcp.smtp<br>
    qmailctl cdb<p>
    </blockquote>



    <li><a name=tmda><b>TMDA</b></a><p>
    If you want the option to be very agressive about spam control, TMDA (Tagged Message Delivery Agent) is a challenge based tool that requires a sender to confirm their sending address before the incoming message is delivered.  I don't use it myself, but many people do.<br>
    See <a href="http://www.tmda.net/">www.tmda.net</a> for more details.<p>

    Install:
    <blockquote>
    cd <?php echo $varsrc?>/tar<br>
    wget http://shupp.org/software/tmda-<?echo $tmda?>.tgz<br>
    wget http://shupp.org/software/tmda-cgi-<?echo $tmdacgi?>.tar.bz2<br>
    wget http://shupp.org/patches/tmda-cgi-patch-<?echo $tmdacgipatch?>.patch<p>

    cd ../<br>
    tar -xvzf tar/tmda-<?echo $tmda?>.tgz<br>
    cd tmda-<?echo $tmda?><br>
    ./compileall<br>
    cd ../<br>
    mv tmda-<?echo $tmda?> /usr/local/<br>
    ln -s /usr/local/tmda-<?echo $tmda?> /usr/local/tmda<p>

    #<i> Now we'll install the vpopmail related scripts</i><p>

    cp <?= $varsrc?>/toaster-scripts-<?= $toasterscripts?>/vadduser-tmda /home/vpopmail/bin/<br>
    cp <?= $varsrc?>/toaster-scripts-<?= $toasterscripts?>/vpopmail-vdir.sh /home/vpopmail/bin/<br>
    cp <?= $varsrc?>/toaster-scripts-<?= $toasterscripts?>/vtmdarc /home/vpopmail/etc/<p>

    #<i> Setup tmda-ofmipd</i><p>

    mkdir -p /var/qmail/supervise/tmda-ofmipd/log<br>
    mkdir -p /var/qmail/supervise/tmda-ssl/log<br>
    mkdir /var/log/tmda-ofmipd/<br>
    mkdir /var/log/tmda-ssl/<br>
    chown vpopmail /var/log/tmda-*<br>
    cp <?= $varsrc?>/toaster-scripts-<?= $toasterscripts?>/tmda-ofmipd.run /var/qmail/supervise/tmda-ofmipd/run<br>
    cp <?= $varsrc?>/toaster-scripts-<?= $toasterscripts?>/tmda-ofmipd.log.run /var/qmail/supervise/tmda-ofmipd/log/run<p>

    #<i> NOTE:  If you are using stunnel version 4, you should use tmda.ssl.run.v4 <br>
    # instead of tmda-ofmipd.ssl.run below.  <br>
    # Type "stunnel -V" (v. 3) or "stunnel -version" (v. 4) to see what version is installed.<br>
    # You will also need to copy over stunnel.conf like so: <br>
    #<br>
    # cp <?= $varsrc?>/toaster-scripts-<?= $toasterscripts?>/tmda-stunnel.conf /var/qmail/supervise/tmda-ssl/stunnel.conf</i><p>

    cp <?= $varsrc?>/toaster-scripts-<?= $toasterscripts?>/tmda-ofmipd.ssl.run /var/qmail/supervise/tmda-ssl/run<p>

    cp <?= $varsrc?>/toaster-scripts-<?= $toasterscripts?>/tmda-ofmipd.ssl.log.run /var/qmail/supervise/tmda-ssl/log/run<br>
    chmod 755 /var/qmail/supervise/tmda-ofmipd/run<br>
    chmod 755 /var/qmail/supervise/tmda-ofmipd/log/run<br>
    chmod 755 /var/qmail/supervise/tmda-ssl/run<br>
    chmod 755 /var/qmail/supervise/tmda-ssl/log/run<br>
    ln -s /var/qmail/supervise/tmda-ofmipd /var/qmail/supervise/tmda-ssl /service/<p>

    #<i> Setup tmda-cgi</i><p>

    cd <?= $varsrc?><br>
    tar -xvjf tar/tmda-cgi-<?= $tmdacgi?>.tar.bz2<br>
    cd tmda-cgi-<?= $tmdacgi?><br>
    patch -p0 < ../tar/tmda-cgi-patch-<?= $tmdacgipatch?>.patch<br>
    cd ../<br>
    mv tmda-cgi-<?= $tmdacgi?> /usr/local/<br>
    cd /usr/local/tmda-cgi-<?= $tmdacgi?><br>
    rm -r skel/uninstall/%\(Parent\)s/<p>

    ./configure \<br>
    &nbsp -t /var/www/cgi-bin/tmda.cgi \<br>
    <!-- &nbsp -s /tmp/TMDASession. \<br> -->
    &nbsp -p /home/vpopmail/bin/vchkpw \<br>
    <!-- &nbsp -o 0.01 \<br> -->
    &nbsp -m single-user \<br>
    &nbsp -l "vpopmail1 /home/vpopmail/bin/vuserinfo ~" \<br>
    &nbsp -i /usr/local/tmda-cgi-0.13/ \<br>
    <!-- &nbsp -e 300 \<br> -->
    &nbsp -d /display <p>

    make<br>
    make install<br>
    chown vpopmail:vchkpw /var/www/cgi-bin/tmda.cgi<br>
    chmod ug+s /var/www/cgi-bin/tmda.cgi
    </blockquote>

    <blockquote class="edit">
        Now, you'll need to add an alias directive to your web server.  Add:<p>

        Alias /display /usr/local/tmda-cgi-<?= $tmdacgi?>/display/<p>

        to /etc/httpd/httpd.conf (or where ever your conf file is), and restart
        Apache.
    </blockquote>

    Now, you should be able to point your browser to /cgi-bin/tmda.cgi, log in
    with your email address and password, and add/edit/remove tmda for your account.  Note that the .qmail files created by tmda WILL show up in QmailAdmin.<p><br>


    <li><a name=qmailmrtg7><b>Qmailmrtg7 - MRTG Graphs</b></a><p>
    This is a great tool for graphing your mail server's activity.<br>
    See <a href="http://inter7.com/?page=qmailmrtg7">http://inter7.com/?page=qmailmrtg7</a> 
    for more information.<p>

    <font color="red">NOTE:</font>  qmailmrtg7 requires MRTG to already be installed.<p>

    Install:
    <blockquote>
    cd <?php echo $varsrc?>/tar<br>
    mkdir /var/www/html/qmailmrtg<p>
    wget http://shupp.org/software/qmailmrtg7-<?echo $qmailmrtg7?>.tar.gz<br>
    wget http://shupp.org/patches/qmailmrtg7-<?echo $qmailmrtg7?>-cfg.patch<br>
    cd ../<br>
    tar -xzf tar/qmailmrtg7-<?echo $qmailmrtg7?>.tar.gz<br>
    cd qmailmrtg7-<?echo $qmailmrtg7?><br>
    patch -p0 < ../tar/qmailmrtg7-<?echo $qmailmrtg7?>-cfg.patch<br>
    make<br>
    make install<p>
    </blockquote>

    <blockquote class="edit">
    <i># Edit qmail.mrtg.cfg and change all instances of FQDN to your hostname</i>
    </blockquote>

    <blockquote>
    cp qmail.mrtg.cfg /etc/<br>
    indexmaker --section=title /etc/qmail.mrtg.cfg > /var/www/html/qmailmrtg/index.html<p>
    <i># now run mrtg 3 times to get rid of initial cron errors</i><br>
    env LANG=C mrtg /etc/qmail.mrtg.cfg<br>
    env LANG=C mrtg /etc/qmail.mrtg.cfg<br>
    env LANG=C mrtg /etc/qmail.mrtg.cfg
    </blockquote>

    <blockquote class="edit">
    <i># Add the following line to your crontab</i><br>
    0-55/5 * * * * env LANG=C /usr/bin/mrtg /etc/qmail.mrtg.cfg > /dev/null
    </blockquote>



    <!-- To make SqWebmail use the virus scanner as well, you'll need to edit<br>
    /usr/local/share/sqwebmail/sendit.sh<br>
    and add these 2 lines above the "exec" statement:

    <blockquote><font face=courier size=-1>
    QMAILQUEUE="/var/qmail/bin/qmail-scanner-queue.pl"<br>
    export QMAILQUEUE<br>
    </font></blockquote> -->

    <li><a name=limits><b>Qmailadmin Limits</b></a><p>

    Qmailadmin can set per domain limits/defaults, which is really useful
    for hosting companies with different mail packages.  This is covered in 
    detail in the INSTALL file of qmailadmin (<?php echo $varsrc?>/qmailadmin-<?php echo $qmailadmin?>/INSTALL).

</ul>

    <p><a href=#top>top</a><p>
    <hr>
<h2><a name=appendix>Appendix</a></h2>

<ul>
    <li><a name=donate><b>Donate</b></a><p>
        <ul>
            <li>If you find this toaster useful to you, and want to support its development, please feeel free to donate via Paypal:<p>

              <table><tr><td class="border"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="hostmaster@shupp.org">
                <input type="hidden" name="item_name" value="Shupp.Org Support Donation">
                <input type="hidden" name="no_shipping" value="1">
                <input type="hidden" name="cancel_return" value="http://shupp.org/toaster/">
                <input type="image" vspace=2 hspace=5 align=left src="/images/paypal-donate.gif" border="0" name="submit" 
                alt="Make payments with PayPal - it's fast, free and secure!">
              </form></td><tr></table><br>
        </ul>
    <li><a name=troubleshooting><b>Troubleshooting</b></a><p>
        <ul>
            <li>Subscribe to this toaster list: <a href=mailto:toaster-subscribe@shupp.org>toaster-subscribe@shupp.org</a><br>
            (to unsubscribe: <a href=mailto:toaster-unsubscribe@shupp.org>toaster-unsubscribe@shupp.org</a>)<br>
            There is also a <a href=http://www.mail-archive.com/toaster@shupp.org>searchable archive</a><p>
            <li>It's recommended that you join the mailing list for 
            vpopmail (<a href=mailto:vchkpw-subscribe@inter7.com>vchkpw-subscribe@inter7.com</a>), since this is the core of the virtual domain package.<p>
            <li>Also, Life With Qmail (see links below) covers qmail setup/maintenance in great detail.  Make sure you read it.<br><p>

        </ul>

    <li><a name=credits><b>Credits</b></a><p>
        <ul>
            <li>This document is certainly inspired by Matt Simerson's FreeBSD 
                <a href=http://www.tnpi.biz/internet/mail/toaster/>Mail 
                Toaster</a>.  If you use FreeBSD, use it instead, it's great.<p>

            <li>Most of the commands listed in the steps above are derived 
                either directly from the documentation or
                <a href=http://www.lifewithqmail.org>Life With Qmail</a>.<p>

            <li>There have been countless ideas, corrections, testing, and even bits of code contribted from the toaster users list.<p>
            <li>This toaster was put together by Bill Shupp (hostmaster@shupp.org).<p><br>

        </ul>
    <li><a name=resources><b>Resources</b></a><p>
            <ul>
                <li><a href="http://cr.yp.to/">Dan Bernstein's site</a>
                <li><a href="http://www.lifewithqmail.org">Life With Qmail</a>
            </ul>
        <p><br>
    <li><a name=changelog><b>ChangeLog</b></a><p>
        <b>0.8.8</b> - 5/11/2006
	<ul>
	    <li>Update clamav to 0.88.2<br>
	    <li>Added link to Trustix 3.0 notes - tnx Matthew Valentini<p>
    </ul>
        <b>0.8.7</b> - 4/9/2006
	<ul>
	    <li>Update toaster patch (put mfcheck back, turn on user extensions in chkuser), ezmlm-idx, courier imap, squirrelmail, quota usage, toaster scripts, clamav (and corresponding patches), and simscan<br>
	    <li>Added TMDA in Options<br>
	    <li>Added notes for x86_64 issues - tnx Rick Root<br>
	    <li>Added chkconfig directives to the freshclam init script - tnx Ken Schweigert<br>
	    <li>Finally added notes about using pop3ds.run.v4 in case you have stunnel version 4<br>
	    <li>Added umask directives - tnx George Toft<br>
	    <li>Added clear option in qmailctl to clear readproctitle errors - tnx Sim<br>
	    <li>Added simscan patch to call ripmime correctly<br>
	    <li>Added SSL/TLS testing info.  Also noted how to deal with cipher list problems<br>
	    <li>Added freshclam.log to logrotate - tnx Tom Collins<br>
	    <li>4/10/2006 - Added SSL instance of tmda-ofmipd<br>
	    <li>4/10/2006 - Note different version syntax for stunnel 3 and 4 - tnx Ingo Claro<br>
	    <li>4/10/2006 - Downgrade ezmlm-idx to 0.443 until chkuser is updated to support the new version<br>
	    <li>4/10/2006 - upgrade QmailAdmin to 1.2.10 to include security fixes<br>
	    <li>4/19/2006 - Add removal of exim4-base and exim4-config to Debian notes<p>
	</ul>
        <b>0.8.6</b> - 1/28/2006
	<ul>
	    <li>Update clamav to 0.88<br>
	    <li>use vpopmail-5.4.13-cumulative-1.patch which has one bug fix and back ports vpopmaild <p>
	</ul>
        <b>0.8.5</b> - 1/5/2006
	<ul>
	    <li>Update toaster patch to version 0.8.1 (tls update)<br>
	    <li>Update ripmime to 1.4.0.6<p>
	</ul>
        <b>0.8.4</b> - 12/26/2005
	<ul>
	    <li>Update courier-imap/courier-authlib versions<br>
	    <li>Fix spelling error<br>
	    <li>Use yum instead of rpm -q in "Prerequisites"<br>
	    <li>Fix some apt-get typos - tnx Tom Nats<br>
	    <li>Mention MANDATORY_MANPATH for Debian - tnx Andrea Riela<p>
	</ul>
        <b>0.8.3</b> - 12/3/2005
	<ul>
	    <li>Add SpamAssassin and ClamAV dependencies installation, and gmake alias to Debian Notes section<p>
	</ul>
        <b>0.8.2</b> - 11/7/2005
	<ul>
	    <li>Update clamav to 0.87.1<p>
	</ul>
        <b>0.8.1</b> - 9/16/2005
	<ul>
	    <li>Update qmailadmin, courier-authlib, courier-imap, clamav, and ezmlm-idx versions<p>
	</ul>
        <b>0.8</b> - 7/24/2005
	<ul>
	    <li>Update qmailadmin configure line since debian requires arguments
	    <li>Added Debian notes
	    <li>Added qmailmrtg7 under "Options"
	    <li>Update all software packages to latest versions, including courier-imap which now requires authlib
	    <li>Update toaster patch to include latest patch versions
	    <li>Update notes in Test Drive
	    <li>Update archive links in Troubleshooting
		<p>
	</ul>
        <b>0.7.13</b> - 6/23/2005
	<ul>
	    <li>Update clamav to 0.86.1<p>
	</ul>
        <b>0.7.12</b> - 5/23/2005
        <ul>
            <li>Update clamav to 0.85.1<p>
        </ul>
        <b>0.7.11</b> - 5/13/2005
        <ul>
            <li>Update clamav to 0.85<p>
        </ul>
        <b>0.7.10</b> - 5/1/2005
        <ul>
            <li>Update vpopmail to 5.4.10
            <li>Update ripmime to 1.4.0.5
            <li>Update clamav to 0.84<p>
        </ul>
        <b>0.7.9</b> - 3/13/2005
        <ul>
            <li>Update vpopmail to 5.4.9<p>
        </ul>
        <b>0.7.7</b> - 2/16/2005
        <ul>
            <li>add spam_passthru=yes to simcontrol - tnx Jason S<br>
            <li>add note about needing gmp-devel - tnx Jesus San Miguel<br>
            <li>add note about chowning /var/qmail/spam to vpopmail:vchkpw after re-installs of qmail (for spam throttle)<br>
            <li>remove chmod +t commands, as they are only for daemontools < 0.75<br>
            <li>update qmailctl to use svclist for a list of services - handy for multiple instances of qmail-smtpd.  - tnx Tom Collins<p>
        </ul>
        <b>0.7.7</b> - 2/16/2005
        <ul>
            <li>Upgraded clamav to 0.83<br>
            <li>Upgraded simscan to 1.1<p>
        </ul>
        <b>0.7.6</b> - 2/7/2005
        <ul>
            <li>Upgraded clamav to 0.82<p>
        </ul>
        <b>0.7.5</b> - 2/4/2005
        <ul>
            <li>Fixed missing semi-colons in creation of aliases - tnx Tom Collins
            <li>Consolidate rpm command suggestions - tnx Tom Collins
            <li>Add qmail man pages to MANCONFIG path - tnx Tom Collins<p>
        </ul>
        <b>0.7.4</b> - 2/1/2005
        <ul>
            <li>Updated clamav to 0.81 - tnx Bob Hutchinson for updated stderr patch<br>
            <li>Minor change to tcprules command in vpopmail install - tnx Jake Applebaum<p>
        </ul>
        <b>0.7.3</b> - 1/25/2005
        <ul>
            <li>Updated qmail-toaster patch to 0.7.2 to fix a CR in overmaildirquota.c<p>
        </ul>
        <b>0.7.2</b> - 1/20/2005
        <ul>
            <li>Updated maildir++ patch to fix duplicate free() - tnx Tom Collins<p>
        </ul>
        <b>0.7.1</b> - 1/8/2005
        <ul>
            <li>Added mention of SELinux incompatibility with QmailAdmin - tnx Dave Roberts<p>
        </ul>
        <b>0.7</b> - 12/27/2004
        <ul>
            <li>1/8/2005 - Added mention of SELinux incompatibility with QmailAdmin - tnx Dave Roberts
            <li>Changed qmail-toaster patch from 0.7b5 to 0.7 (no changes)
            <li>Changed toaster version from 0.7b2 to 0.7
            <li>Added note to courier-imap install about changing from self-signed certificate
        </ul><p>
        <b>0.7b2</b> - 12/20/2004
        <ul>
            <li>Updated paths to init scripts.  This document now uses /etc/init.d and /etc/rc?.d instead of /etc/rc.d/init.d and /etc/rc.d/rc?.d
            <li>Enabled per-domain scanning in simscan - tnx Jose Luis Canciani 
            <li>Typo in version info of toaster patch - tnx Eduardo Cortes
        </ul><p>
        <b>0.7b1</b> - 12/15/2004
        <ul>
            <li>New Toaster patch with updated versions, SPF, dropped mfcheck, swapped spam throttle for tarpit
            <li>Updated vpopmail to 5.4.8, qmailadmin to 1.2.3
            <li>Updated courier-imap to 3.0.8
            <li>Added SpamAssassin, ClamAV, simscan, ripmime to Options
            <li>Lots of updates to run/config files
            <li>qmailctl now detects whether roaming users is being used
        </ul><p>
        <b>0.6</b> - 2/16/2004
        <ul>
            <li>Switched to netqmail 1.05 for base patch install
            <li>Updated large patch and chkuser patch
            <li>Updated vpopmail to 5.4.1, qmailadmin to 1.2.1
            <li>Updated courier-imap to 2.2.2.20040207 to comply with vlimits code
        </ul><p>
        <b>0.5.2</b> - 1/27/2004
        <ul>
            <li>Fixed problem with missing config.php in quota_usage plugin install
        </ul><p>
        <b>0.5.1</b> - 1/20/2004
        <ul>
            <li>Fixed some typos
            <li>Noted in "prerequisites" that krb5 dev files, Apache and PHP are required
        </ul><p>
        <b>0.5</b> - 1/6/2004
        <ul>
            <li>Updated core software versions, qmail patch
            <li>Switched from SqWebmail to SquirrelMail
            <li>Removed Apache install (just use the distro's)
            <li>Removed roaming users support (use smtp-auth instead)
            <li>Added license link
        </ul><p>
        <b>0.4.7</b> - 6/17/2002
        <ul>
            <li>typos in courier-imap link, filename.  thanks to Yalcin Cekic.
        </ul><p>
        <b>0.4.6</b> - 6/05/2002
        <ul>
            <li>the qmail-smtpd run script didn't have a hostname, which is now required for the smtp-auth patch 0.30 and above.
            <li>forgot to chown the imapd.pem for courier-imap now that we're running as vpopmail
            <li>incorrect configure option for qmailadmin
        </ul><p>
        <b>0.4.5</b> - 6/03/2002
        <ul>
            <li>Use courier-imap 1.4.6 (security advisory)
        </ul><p>
        <b>0.4.4</b> - 5/31/2002
        <ul>
            <li>Update courier-imap install to run as vpopmail.vchkpw instead of root (for romaing imap users)
            <li>Update support information
        </ul><p>
        <b>0.4.3</b> - 5/29/2002
        <ul>
            <li>Use port numbers in pop3d start scripts instead of "pop-3" and "pop3s"
        </ul><p>
        <b>0.4.2</b> - 5/26/2002
        <ul>
            <li>Upgrade to new 0.31 smtp auth patch
        </ul><p>
        <b>0.4.1</b> - 5/21/2002
        <ul>
            <li>Use vpopmail-5.3.6 instead of alternate vchkpwcmd5
            <li>Use courier-imap 1.4.5
            <li>Use sqwebmail 3.3.4
        </ul><p>
        <b>0.4</b> - 4/20/2002 (not published)
        <ul>
            <li>Supply new vchkpwcmd5 module (alternate to vchkpw)
            <li>Use vpopmail-5.3.5-cmd5 and qmailadmin 1.0.4
            <li>Use courier-imap 1.4.4 and sqwebmail 3.3.3
            <li>Switch to qmail-pop3d from courier pop3d
            <li>Upgrade to v. 0.30 of the SMTP-AUTH patch
        </ul><p>
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

    <li><a name=success><b>Success Reports</b></a><p>

        <blockquote>"Bill, thanks loads for the toaster.  It works wonderfully, and didn't take
too long to set up.  I only wish I had it two weeks ago.  :)" -- Matt G.</blockquote>

        <blockquote>"Hi I just installed the complete qmail toaster suite tonight. . [nearly] flawless installation. . and [nearly] all done from your site. .  i think you are doing great things with that toaster site. ." -- Ezra P.</blockquote>
        <blockquote>"Thanks for making it easy. Now to understand what I (you) have done. ; )" -- Charles C.</blockquote>
        <blockquote>"Hi Bill,

I just wanted to thank you for the great instructions on setting up
qmail, etc. on Linux. I'm a bit of a rookie and for the last month I've
been looking for a fairly easy to configure setup for mail. After going
from RedHat w/ Sendmail to Win 2K / Exchange to FreeBSD, Debian, Gentoo,
Slack with some combo of qmail, courier, postfix and back again I
finally stumbled upon your site - gave RedHat a fresh install and within
half an hour I was up and running with Qmail. (I'm sending this to you
using my server)
<p>
I couldn't have done it without you! I really do appreciate the work you
put into the instructions and patches." -- Thomas A.</blockquote>
        <blockquote>"Bill,
Just wanted to drop a note to you to thank you for all the obvious work you put into the toaster website.  I just installed it, and it worked perfectly. In fact, I am sending you the first email from my new setup. 
Again, thanks for all the hard work that went into the instructions." -- Jim S.</blockquote>
        <!-- <blockquote>"" -- </blockquote> -->

</ul>
</ul>
        
</td></td>
</table>
</center>

</body>
</html>
