<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
 xmlns="http://www.w3.org/1999/xhtml"
 xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
 xmlns:img="http://uri.in2isoft.com/onlinepublisher/part/image/1.0/"
 xmlns:o="http://uri.in2isoft.com/onlinepublisher/class/object/1.0/"
 xmlns:i="http://uri.in2isoft.com/onlinepublisher/class/image/1.0/"
 exclude-result-prefixes="img o i"
 >

<xsl:template match="img:image[img:link]">
	<div class="part_image">
		<xsl:if test="img:style/@align">
			<xsl:attribute name="style">text-align: <xsl:value-of select="img:style/@align"/></xsl:attribute>
		</xsl:if>
		<a>
			<xsl:if test="$editor!='true'">
				<xsl:call-template name="img:buildlink"/>
			</xsl:if>
			<xsl:call-template name="img:buildimage"/>
		</a>

		<xsl:if test="img:link/@image and $editor!='true'">
			<script type="text/javascript">
			try {
				op.registerImageViewer('part_image_<xsl:value-of select="generate-id()"/>',{id:<xsl:value-of select="img:link/@image"/><xsl:if test="img:link/@width">,width:<xsl:value-of select="img:link/@width"/></xsl:if><xsl:if test="img:link/@height">,height:<xsl:value-of select="img:link/@height"/></xsl:if>,text:'<xsl:value-of select="img:link/@note"/>'});
			} catch (ignore) {}
			</script>
		</xsl:if>
		<xsl:apply-templates select="img:text"/>
	</div>
</xsl:template>

<xsl:template match="img:image">
	<div class="part_image">
		<xsl:if test="img:style/@align">
			<xsl:attribute name="style">text-align: <xsl:value-of select="img:style/@align"/></xsl:attribute>
		</xsl:if>
		<xsl:choose>
			<xsl:when test="$editor='true' and not(o:object)">
				<div>
					<xsl:attribute name="style">
						<xsl:text>border: 2px dashed #eee; background: #fafafa; color: #aaa; border-radius: 5px; text-align: center; font-size: 20px; overflow: hidden;</xsl:text>
					<xsl:choose>
						<xsl:when test="img:transform/@scale-height>0">height:<xsl:value-of select="img:transform/@scale-height"/>px; line-height: <xsl:value-of select="img:transform/@scale-height"/>px;</xsl:when>
						<xsl:otherwise>height: 100px; line-height: 100px;</xsl:otherwise>
					</xsl:choose>
					</xsl:attribute>
					?</div>
			</xsl:when>
			<xsl:when test="not(o:object)">
				<xsl:comment>No image</xsl:comment>
			</xsl:when>
			<xsl:otherwise>
				<xsl:call-template name="img:buildimage"/>
			</xsl:otherwise>
		</xsl:choose>
		<xsl:apply-templates select="img:text"/>
	</div>
</xsl:template>

<xsl:template name="img:buildimage">
	<xsl:variable name="src"><xsl:call-template name="img:buildsrc"/></xsl:variable>
	<xsl:variable name="width"><xsl:call-template name="img:buildwidth"/></xsl:variable>
	<xsl:variable name="height"><xsl:call-template name="img:buildheight"/></xsl:variable>
	<xsl:choose>
		<xsl:when test="img:style/@frame">
			<span class="shared_frame_{img:style/@frame}">
				<span class="shared_frame_{img:style/@frame}_top"><span><span><xsl:comment/></span></span></span>
				<span class="shared_frame_{img:style/@frame}_middle">
					<span class="shared_frame_{img:style/@frame}_middle">
						<span class="shared_frame_{img:style/@frame}_content">
							<img src="{$src}" width="{$width}"  height="{$height}" alt="" id="part_image_{generate-id()}"/>
						</span>
					</span>
				</span>
				<span class="shared_frame_{img:style/@frame}_bottom"><span><span><xsl:comment/></span></span></span>
			</span>
		</xsl:when>
		<xsl:otherwise>
			<img src="{$src}" width="{$width}"  height="{$height}" alt="" id="part_image_{generate-id()}"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="img:text">
	<p class="common_font"><xsl:apply-templates/></p>
</xsl:template>

<xsl:template name="img:buildlink">
	<xsl:attribute name="title"><xsl:value-of select="img:link/@alternative"/></xsl:attribute>
	<xsl:choose>
		<xsl:when test="img:link/@path and $preview='false'">
			<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/><xsl:value-of select="img:link/@path"/></xsl:attribute>
		</xsl:when>
		<xsl:when test="img:link/@page">
			<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?id=<xsl:value-of select="img:link/@page"/></xsl:attribute>
		</xsl:when>
		<xsl:when test="img:link/@url">
			<xsl:attribute name="href"><xsl:value-of select="img:link/@url"/></xsl:attribute>
		</xsl:when>
		<xsl:when test="img:link/@file">
			<xsl:attribute name="href"><xsl:value-of select="$navigation-path"/>?file=<xsl:value-of select="img:link/@file"/></xsl:attribute>
		</xsl:when>
		<xsl:when test="img:link/@email">
			<xsl:attribute name="href">mailto:<xsl:value-of select="img:link/@email"/></xsl:attribute>
		</xsl:when>
		<xsl:when test="img:link/@image">
			<xsl:attribute name="href"><xsl:value-of select="$path"/>services/images/?id=<xsl:value-of select="img:link/@image"/></xsl:attribute>
		</xsl:when>
	</xsl:choose>
</xsl:template>

<xsl:template name="img:buildwidth">
	<xsl:choose>
		<xsl:when test="img:transform"><xsl:value-of select="img:transform/@display-width"/></xsl:when>
		<xsl:otherwise><xsl:value-of select="o:object/o:sub/i:image/i:width"/></xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="img:buildheight">
	<xsl:choose>
		<xsl:when test="img:transform"><xsl:value-of select="img:transform/@display-height"/></xsl:when>
		<xsl:otherwise><xsl:value-of select="o:object/o:sub/i:image/i:height"/></xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="img:buildsrc">
	<xsl:choose>
	<xsl:when test="img:transform">
		<xsl:value-of select="$path"/><xsl:text>services/images/?id=</xsl:text>
		<xsl:value-of select="o:object/@id"/>
		<xsl:if test="img:transform/@scale-percent and $highquality='false'">
			<xsl:text>&amp;scale=</xsl:text><xsl:value-of select="img:transform/@scale-percent"/>
		</xsl:if>
		<xsl:if test="img:transform/@width and $highquality='false'">
			<xsl:text>&amp;width=</xsl:text><xsl:value-of select="img:transform/@width"/>
		</xsl:if>
		<xsl:if test="img:transform/@height and $highquality='false'">
			<xsl:text>&amp;height=</xsl:text><xsl:value-of select="img:transform/@height"/>
		</xsl:if>
		<xsl:if test="img:transform/@max-width and $highquality='false'">
			<xsl:text>&amp;width=</xsl:text><xsl:value-of select="img:transform/@max-width"/>
		</xsl:if>
		<xsl:if test="img:transform/@max-height and $highquality='false'">
			<xsl:text>&amp;height=</xsl:text><xsl:value-of select="img:transform/@max-height"/>
		</xsl:if>
		<xsl:if test="img:transform/@greyscale='true'">
			<xsl:text>&amp;greyscale=true</xsl:text>
		</xsl:if>
	</xsl:when>
	<xsl:otherwise>
		<xsl:value-of select="$path"/>images/<xsl:value-of select="o:object/o:sub/i:image/i:filename"/>
	</xsl:otherwise>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>