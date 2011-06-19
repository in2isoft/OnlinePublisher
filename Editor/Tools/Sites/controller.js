var mainController = {
	
	activePage : 0,
	dragDrop : [
		{drag:'page',drop:'language'},
		{drag:'page',drop:'subset'}
	],
	
	///////////////// List ///////////////
	
	$selectionChanged$list : function(item) {
		if (item.kind=='page') {
			hui.ui.get('edit').setEnabled(true);
			hui.ui.get('info').enable();
			hui.ui.get('delete').enable();
			hui.ui.get('view').enable();			
		} else if (item.kind=='hierarchyItem') {
			var page = item.data && item.data.page;
			hui.ui.get('edit').setEnabled(page);
			hui.ui.get('info').enable();
			hui.ui.get('delete').enable();
			hui.ui.get('view').setEnabled(page);
		} else {
			hui.ui.get('edit').disable()
			hui.ui.get('info').disable()
			hui.ui.get('delete').disable()
			hui.ui.get('view').disable()
		}
	},
	$selectionReset$list : function() {
		hui.ui.get('edit').setEnabled(false);
		hui.ui.get('info').setEnabled(false);
		hui.ui.get('delete').setEnabled(false);
		hui.ui.get('view').setEnabled(false);
	},
	$click$edit : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			document.location='../../Template/Edit.php?id='+obj.id;
		}
	},
	$click$view : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			document.location='../../Services/Preview/?id='+obj.id;
		}
	},
	$click$delete : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			this.deletePage(obj.id);
		}
	},
	deletePage : function(id) {
		if (this.activePage==id) {
			pageFormula.reset();
			pageEditor.hide();
			this.activePage = 0;
		}
		hui.ui.request({
			message : {start:'Sletter side...',delay:300,success:'Siden er nu slettet'},
			url : 'DeletePage.php',
			parameters : { id : id },
			onSuccess : function() {
				list.refresh();
				languageSource.refresh();
				hierarchySource.refresh();				
			}
		});
	},
	$click$info : function() {
		var obj = list.getFirstSelection();
		if (obj.kind=='page') {
			this.loadPage(obj.id);
		}
	},
	$listRowWasOpened$list : function(obj) {
		if (obj.kind=='page') {
			this.loadPage(obj.id);
		}
	},
	$drop$page$language : function(dragged,dropped) {
		hui.ui.request({
			message : {start:'Ændrer sprog...',delay:300},
			url : 'ChangeLanguage.php',
			json : {data : {id:dragged.id,language:dropped.value}},
			onSuccess : function() {
				hui.ui.showMessage({text:'Sproget er ændret',icon:'common/success',duration:2000});
				list.refresh();
				languageSource.refresh();
			}
		});
	},
	
	/////////// Page properties //////////
	
	loadPage : function(id) {
		hui.ui.request({
			message : {start : 'Åbner side...',delay:300},
			url : 'LoadPage.php',
			onSuccess : 'pageLoaded',
			parameters : {id:id}
		});
	},
	$success$pageLoaded : function(data) {
		this.activePage = data.id;
		pageFormula.setValues(data);
		pageEditor.show();
	},
	$click$editPage : function() {
		document.location='../../Template/Edit.php?id='+this.activePage;
	},
	$click$viewPage : function() {
		document.location='../../Services/Preview/?id='+this.activePage;
	},
	$click$cancelPage : function() {
		this.activePage = 0;
		pageFormula.reset();
		pageEditor.hide();
	},
	$click$savePage : function() {
		var values = pageFormula.getValues();
		values.id = this.activePage;
		hui.ui.request({
			message : {text : 'Gemmer side...',delay:300},
			url : 'SavePage.php',
			json : {data:values},
			onSuccess : function() {
				list.refresh();
				languageSource.refresh();
				hierarchySource.refresh();				
			}
		});
		this.activePage = 0;
		pageFormula.reset();
		pageEditor.hide();
	},
	$click$deletePage : function() {
		this.deletePage(this.activePage);
	},
	
	$click$pageInfo : function() {
		pageInfoFragment.show();
		pageInfo.setSelected(true);
		pageInfo.element.blur();
		pageTranslationFragment.hide();
		pageTranslation.setSelected(false);
	},
	
	$click$pageTranslation : function() {
		pageInfoFragment.hide();
		pageInfo.setSelected(false);
		pageTranslationFragment.show();
		pageTranslation.setSelected(true);
		pageTranslation.element.blur();
	},
	
	////////////// New page /////////////
	
	$click$newPage : function() {
		templatePicker.reset();
		designPicker.reset();
		menuItemSelection.reset();
		frameSelection.reset();
		newPageFormula.reset();
		newPageWizard.goToStep(0);
		createPage.setEnabled(false);
		newPageBox.show();
	},
	
	$click$createPage : function() {
		var design = designPicker.getValue();
		var template = templatePicker.getValue();
		var frame = frameSelection.getValue();
		var menuItem = menuItemSelection.getValue() || {};
		var form = newPageFormula.getValues();
		if (template===null) {
			newPageWizard.goToStep(0);
			hui.ui.showMessage({text:'Der er ikke valgt en skabelon',duration:2000});
		} else if (design===null) {
			newPageWizard.goToStep(1);
			hui.ui.showMessage({text:'Der er ikke valgt et design',duration:2000});
		} else if (frame===null) {
			newPageWizard.goToStep(2);
			hui.ui.showMessage({text:'Der er ikke valgt en grundopsætning',duration:2000});
		} else if (form.title=='') {
			newPageWizard.goToStep(4);
			hui.ui.showMessage({text:'Der er ikke udfyldt en titel',duration:2000});
			newPageTitle.focus();
		} else {
			var data = {design:design,template:template,frame:frame.value,menuItemId:menuItem.value,menuItemKind:menuItem.kind};
			hui.override(data,form);
			if (menuItem!=null) {
				if (menuItem.kind=='hierarchyItem') {
				  	data.hierarchyItem = menuItem.value;
				} else if (menuItem.kind=='hierarchy') {
					data.hierarchy = menuItem.value;
				}
			}
			hui.ui.request({url:'CreatePage.php',onSuccess:'pageCreated',parameters:data});
		}
	},
	$success$pageCreated : function() {
		newPageBox.hide();
		list.refresh();
		languageSource.refresh();
		hierarchySource.refresh();
	},
	
	$selectionChanged$templatePicker : function() {
		newPageWizard.goToStep(1);
	},
	$selectionChanged$designPicker : function() {
		newPageWizard.goToStep(2);
	},
	$selectionChanged$frameSelection : function() {
		newPageWizard.goToStep(3);
	},
	$selectionChanged$menuItemSelection : function() {
		newPageWizard.goToStep(4);
	},
	$valueChanged$newPageTitle : function(value) {
		this.checkNewPage(value);
	},
	$click$noMenuItem : function() {
		menuItemSelection.reset();
		newPageWizard.goToStep(4);
	},
	checkNewPage : function(value) {
		createPage.setEnabled(value.length>0);
	}
}

hui.ui.listen(mainController);