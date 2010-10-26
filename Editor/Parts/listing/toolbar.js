ui.listen({
	$ready : function() {
		this.form = partToolbar.partForm;
		fontWeight.setValue(this.form.fontWeight.value);
		fontSize.setValue(this.form.fontSize.value);
		textAlign.setValue(this.form.textAlign.value);
		fontFamily.setValue(this.form.fontFamily.value);
		lineHeight.setValue(this.form.lineHeight.value);
		color.setValue(this.form.color.value);
		fontStyle.setValue(this.form.fontStyle.value);
		textTransform.setValue(this.form.textTransform.value);
		fontVariant.setValue(this.form.fontVariant.value);
		textDecoration.setValue(this.form.textDecoration.value);
		wordSpacing.setValue(this.form.wordSpacing.value);
		letterSpacing.setValue(this.form.letterSpacing.value);
		textIndent.setValue(this.form.textIndent.value);
		listStyle.setValue(this.form['type'].value);
	},
	$valueChanged$fontWeight : function(value) {
		this.form.fontWeight.value=value || '';
		this.form.text.style.fontWeight=value || '';
	},
	$valueChanged$fontSize : function(value) {
		this.form.fontSize.value=value || '';
		this.form.text.style.fontSize=value || '';
	},
	$valueChanged$textAlign : function(value) {
		this.form.textAlign.value=value || '';
		this.form.text.style.textAlign=value || '';
	},
	$valueChanged$fontFamily : function(value) {
		this.form.fontFamily.value=value || '';
		this.form.text.style.fontFamily=value || '';
	},
	$valueChanged$lineHeight : function(value) {
		this.form.lineHeight.value=value || '';
		this.form.text.style.lineHeight=value || '';
	},
	$valueChanged$color : function(value) {
		this.form.color.value=value || '';
		this.form.text.style.color=value || '';
	},
	$valueChanged$fontStyle : function(value) {
		this.form.fontStyle.value=value || '';
		this.form.text.style.fontStyle=value || '';
	},
	$valueChanged$textTransform : function(value) {
		this.form.textTransform.value=value || '';
		this.form.text.style.textTransform=value || '';
	},
	$valueChanged$fontVariant : function(value) {
		this.form.fontVariant.value=value || '';
		this.form.text.style.fontVariant=value || '';
	},
	$valueChanged$textDecoration : function(value) {
		this.form.textDecoration.value=value || '';
		this.form.text.style.textDecoration=value || '';
	},
	$valueChanged$wordSpacing : function(value) {
		this.form.wordSpacing.value=value || '';
		this.form.text.style.wordSpacing=value || '';
	},
	$valueChanged$letterSpacing : function(value) {
		this.form.letterSpacing.value=value || '';
		this.form.text.style.letterSpacing=value || '';
	},
	$valueChanged$textIndent : function(value) {
		this.form.textIndent.value=value || '';
		this.form.text.style.textIndent=value || '';
	},
	$valueChanged$listStyle : function(value) {
		this.form.type.value=value || '';
	}
});