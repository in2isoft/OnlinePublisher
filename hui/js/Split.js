/**
 * A bar
 * <pre><strong>options:</strong> {
 *  element : «Element»,
 *  name : «String»
 * }
 * </pre>
 * @constructor
 * @param {Object} options The options
 */
hui.ui.Split = function(options) {
	this.options = hui.override({},options);
	this.name = options.name;
	this.element = hui.get(options.element);
	this.rows = hui.get.children(this.element);
	this.handles = [];
	this.sizes = [];
	for (var i=0; i < this.rows.length; i++) {
		if (i>0) {
			this.handles.push(hui.build('div',{'class':'hui_split_handle',parent:this.element}));
		}
	};
	this._buildSizes();
	hui.ui.extend(this);
	this._addBehavior()
}

hui.ui.Split.prototype = {
	_buildSizes : function() {
		this.sizes = [];
		for (var i=0; i < this.rows.length; i++) {
			var row = this.rows[i],
				str = row.getAttribute('data-height');
			if (str) {
				this.sizes.push(this._getSize(str));
			} else {
				this.sizes.push(0);
			}
		};
		var total = 0,
			unspecified = 0;
		for (var i=0; i < this.sizes.length; i++) {
			total+=this.sizes[i];
			unspecified+=this.sizes[i]==0 ? 1 : 0;
		};
		var rest = (1-total)/unspecified;
		for (var i=0; i < this.sizes.length; i++) {
			if (this.sizes[i]==0) {
				this.sizes[i] = rest;
			}
		}
	},
	_getSize : function(str) {
		if (str.indexOf('%')!=-1) {
			return parseInt(str)/100;
		}
		return parseInt(str)/this.element.clientHeight;
	},
	_addBehavior : function() {
		
	},
	$$resize : function() {
		this._layout();
	},
	_layout : function() {
		var pos = 0,
			full = this.element.clientHeight;
		for (var i=0; i < this.rows.length; i++) {
			this.rows[i].style.top = (pos*full)+'px';
			var height = (this.sizes[i]*full);
			if (i<this.rows.length-1) {
				height-=6;
			}
			this.rows[i].style.height = height+'px';
			pos+=this.sizes[i];
			if (i<this.rows.length-1) {
				this.handles[i].style.top = (pos*full)+'px';
			}
		};
		//hui.ui.reLayout()
	}
}