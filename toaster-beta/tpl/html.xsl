<xsl:stylesheet version="1.0"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="xml" indent="yes" 
    doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" />

 <xsl:template match="article">
   <html>
   <head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
     <title><xsl:value-of select="title"/></title>
     <link rel="stylesheet" type="text/css" href="style.css" media="screen" />
   </head>
   <body>

<div id="wrap">

<div id="bodytop"></div>

<div id="content">

<div class="header">
     <a name="top"></a>
     <h1><xsl:value-of select="title"/></h1>
</div>

<div class="breadcrumbs">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td><div align="left"><a href="./?page={previousPage}"><b><?php echo _("Back")?></b></a></div></td>
        <td><div align="center"><a href="./?page={defaultPage}"><b><?php echo _("Home") ?></b></a></div></td>
        <td><div align="right"><b><a href="./?page={nextPage}"><?php echo _("Next")?></a></b></div></td>
    </tr>
    </table>
</div>

    <table border="0" width="100%">
    <tr valign="top"><td width="80%">
     <xsl:apply-templates select="section"/>
    </td>

    <td>
    <script type="text/javascript" src="ad.js">
    </script>
    <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
    </td></tr>
    </table>


    <div class="breadcrumbs">
        <table border="0" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td><div align="left"><a href="./?page={previousPage}"><b><?php echo _("Back")?></b></a></div></td>
            <td><div align="center"><a href="./?page={defaultPage}"><b><?php echo _("Home") ?></b></a></div></td>
            <td><div align="right"><b><a href="./?page={nextPage}"><?php echo _("Next")?></a></b></div></td>
        </tr>
        </table>
    </div>

    <div class="clear"></div>
    </div>

    <div id="bottom"></div>
    </div>
   </body>
   </html>
 </xsl:template>

 <xsl:template match="section">
   <xsl:apply-templates/>
 </xsl:template>

 <xsl:template match="section/title">
   <h2><xsl:apply-templates/></h2>
 </xsl:template>

 <xsl:template match="emphasis">
   <b><xsl:apply-templates/></b>
 </xsl:template>
 <xsl:template match="para">
   <p><xsl:apply-templates/></p>
 </xsl:template>
 <xsl:template match="programlisting">
    <blockquote class="command">
        <xsl:apply-templates/>
    </blockquote>
 </xsl:template>
 <xsl:template match="blockquote">
    <blockquote class="edit">
        <xsl:apply-templates/>
    </blockquote>
 </xsl:template>
 <xsl:template match="lineannotation">
    # <i><xsl:apply-templates/></i><br />
 </xsl:template>
 <xsl:template match="literal">
    <xsl:apply-templates/><br />
 </xsl:template>
 <xsl:template match="literallayout">
    <xsl:choose>
        <xsl:when test="@role='listitem'">
            <xsl:apply-templates/>
        </xsl:when>
        <xsl:otherwise>
            <xsl:apply-templates/><br />
        </xsl:otherwise>
    </xsl:choose>
 </xsl:template>

 <xsl:template match="itemizedlist">
   <ul><xsl:apply-templates/></ul>
 </xsl:template>
 <xsl:template match="ulink">
   <a>
        <xsl:attribute name="href">
        <xsl:value-of select="@url"/>
        </xsl:attribute>
        <xsl:apply-templates/>
   </a>
 </xsl:template>
 <xsl:template match="imageobject">
   <img>
        <xsl:attribute name="src">
            <xsl:value-of select="@id"/>
        </xsl:attribute>
        <xsl:attribute name="alt">
            <xsl:value-of select="@role"/>
        </xsl:attribute>
        <xsl:attribute name="border">0</xsl:attribute>
    </img>
 </xsl:template>
 <xsl:template match="anchor">
   <a>
        <xsl:attribute name="name"><xsl:value-of select="@id"/></xsl:attribute>
    </a>
 </xsl:template>

 <xsl:template match="quote">
   "<xsl:apply-templates/>"
 </xsl:template>

 <xsl:template match="listitem">
   <li><xsl:apply-templates/></li>
 </xsl:template>
 <xsl:template match="userinput">
    <xsl:choose>
        <xsl:when test="@role='varsrc'">
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
            <input type="text" name="varsrc" value="<?php echo $varsrc?>" /> <input type="submit" value="set" />
            </form>
        </xsl:when>

        <xsl:when test="@role='donate'">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_xclick" />
                <input type="hidden" name="business" value="hostmaster@shupp.org" />
                <input type="hidden" name="item_name" value="Shupp.Org Support Donation" />
                <input type="hidden" name="no_shipping" value="1" />
                <input type="hidden" name="cancel_return" value="http://shupp.org/toaster/" />
                <input type="image" align="left" src="/images/paypal-donate.gif" name="submit" alt="<?php echo _("Make payments with PayPal - it's fast, free and secure!")?>" />
            </form>
            <br /><br />
        </xsl:when>

    </xsl:choose>
 </xsl:template>
</xsl:stylesheet>
