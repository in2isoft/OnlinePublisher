var textPartToolbar = {
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
		imageId.setValue(this.form.imageId.value);
		imageFloat.setValue(this.form.imageFloat.value);
		imageWidth.setValue(this._toLength(this.form.imageWidth.value));
		imageHeight.setValue(this._toLength(this.form.imageHeight.value));
		this._updateInitialValues();
	},
	$clickPicker$color : function(value) {
		partToolbar.getMainController().showColorWindow(function(value) {
			color.setValue(value);
			this.form.color.value=value || '';
			this.form.text.style.color=value || '';
		}.bind(this));
	},
	_toLength : function(value) {
		if (value=='0') {
			return '';
		}
		return value;
	},
	_updateInitialValues : function() {
		fontSize.setInitialValue(hui.style.get(this.form.text,'font-size'));
		lineHeight.setInitialValue(hui.style.get(this.form.text,'line-height'));
	},
	$valueChanged$fontWeight : function(value) {
		this.form.fontWeight.value=value || '';
		this.form.text.style.fontWeight=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$fontSize : function(value) {
		this.form.fontSize.value=value || '';
		this.form.text.style.fontSize=value || '';
		partToolbar.getMainController().syncSize();
		this._updateInitialValues();
	},
	$valueChanged$textAlign : function(value) {
		this.form.textAlign.value=value || '';
		this.form.text.style.textAlign=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$fontFamily : function(value) {
		this.form.fontFamily.value=value || '';
		this.form.text.style.fontFamily=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$lineHeight : function(value) {
		this.form.lineHeight.value=value || '';
		this.form.text.style.lineHeight=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$color : function(value) {
		this.form.color.value=value || '';
		this.form.text.style.color=value || '';
	},
	$valueChanged$fontStyle : function(value) {
		this.form.fontStyle.value=value || '';
		this.form.text.style.fontStyle=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$textTransform : function(value) {
		this.form.textTransform.value=value || '';
		this.form.text.style.textTransform=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$fontVariant : function(value) {
		this.form.fontVariant.value=value || '';
		this.form.text.style.fontVariant=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$textDecoration : function(value) {
		this.form.textDecoration.value=value || '';
		this.form.text.style.textDecoration=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$wordSpacing : function(value) {
		this.form.wordSpacing.value=value || '';
		this.form.text.style.wordSpacing=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$letterSpacing : function(value) {
		this.form.letterSpacing.value=value || '';
		this.form.text.style.letterSpacing=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$textIndent : function(value) {
		this.form.textIndent.value=value || '';
		this.form.text.style.textIndent=value || '';
		partToolbar.getMainController().syncSize();
	},
	$valueChanged$imageId : function(value) {
		this.form.imageId.value=value || '';
		this.form.text.style.imageId=value || '';
	},
	$valueChanged$imageFloat : function(value) {
		this.form.imageFloat.value=value || '';
		this.form.text.style.imageFloat=value || '';
	},
	$valueChanged$imageHeight : function(value) {
		this.form.imageHeight.value=value || '';
	},
	$valueChanged$imageWidth : function(value) {
		this.form.imageWidth.value=value || '';
	},
	$click$chooseImage : function() {
		partToolbar.getMainController().chooseImage();
	}
}
hui.ui.listen(textPartToolbar);