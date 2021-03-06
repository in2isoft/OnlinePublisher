hui.ui.listen({
	$select$selector : function(obj) {
		if (obj.value=='settings') {
			if (!this.loaded) {
				this.loaded = true;
				this._reloadSettings();
			}
		}
	},
	_reloadSettings : function() {
		hui.ui.request({url:'data/LoadSettings.php',$object : function(data) {
			emailFormula.setValues(data.email);
			onlineobjectsFormula.setValues(data.onlineobjects);
			analyticsFormula.setValues(data.analytics);
			reportsFormula.setValues(data.reports);
			uiFormula.setValues(data.ui);			
		}});		
	},
	
	//UI
	
	$click$saveUI : function(value) {
		var data = {'ui':uiFormula.getValues()};
		hui.ui.request({json:{data:data},url:'actions/SaveSettings.php',$success:function() {
			hui.ui.showMessage({text:'Gemt!',icon:'common/success',duration:2000})
		}});
	},
	
	// OnlineObjects
	$click$saveOnlineObjects : function() {
		saveOnlineObjects.setEnabled(false);
		var data = {'onlineobjects':onlineobjectsFormula.getValues()};
		hui.ui.request({json:{data:data},url:'actions/SaveSettings.php',$success:'saveOnlineObjects'});
	},
	$success$saveOnlineObjects : function() {
		saveOnlineObjects.setEnabled(true);
	},
	$click$testOnlineObjects : function() {
		var url = onlineobjectsFormula.getValues().url;
		hui.ui.showMessage({text:'Testing connection to OnlineObjects!',busy:true});
		hui.ui.request({
			parameters : {url:url},
			url : 'actions/TestOnlineObjects.php',
			$object : function(obj) {
				if (obj.success) {
					hui.ui.showMessage({text:'I can talk to OnlineObjects',icon:'common/success',duration:3000});
				} else {
					hui.ui.showMessage({text:'I cannot talk to OnlineObjects',icon:'common/warning',duration:3000});
				}
			},
			$failure : function() {
				hui.ui.showMessage({text:'An unexpected failure occurred!',duration:3000});
			}
		});
	},
	
	// Analytics
	$click$saveAnalytics : function() {
		saveAnalytics.setEnabled(false);
		var data = {'analytics':analyticsFormula.getValues()};
		hui.ui.request({json:{data:data},url:'actions/SaveSettings.php',$success:function() {
			saveAnalytics.setEnabled(true);
		}});
	},
	$click$testAnalytics : function() {
		hui.ui.showMessage({text:'Tester forbindelse til Google Analytics...'});
		hui.ui.request({json:{},url:'actions/TestAnalytics.php',$success:function() {
			hui.ui.showMessage({text:success ? 'It works!' : 'It does not work!',duration:2000});
		}});
	},
	
	// Email
	$click$saveEmail : function() {
		saveEmail.setEnabled(false);
		var data = {'email':emailFormula.getValues()};
		hui.ui.request({json:{data:data},url:'actions/SaveSettings.php',$success:'saveEmail'});
	},
	$success$saveEmail : function() {
		saveEmail.setEnabled(true);
	},
	$click$showEmailTest : function() {
		emailTestWindow.show();
	},
	$click$testEmail : function() {
		hui.ui.showMessage({text:'Sender e-mail...'});
		var data = emailTestFormula.getValues();
		hui.ui.request({json:{data:data},url:'actions/TestEmailSetup.php',$success:'testEmail'});
	},
	$success$testEmail : function(data) {
		if (data.success) {
			hui.ui.showMessage({text:'E-mail er sendt!',duration:2000});
		} else {
			hui.ui.showMessage({text:'Det lykkedes ikke at sende email!',duration:2000});
		}
	},
	
	// Reports
	$submit$reportsFormula : function(form) {
		saveReports.setEnabled(false);
		var data = {'reports':form.getValues()};
		hui.ui.request({json:{data:data},url:'actions/SaveSettings.php',$success:function() {
			saveReports.setEnabled(true);
			this._reloadSettings();
		}.bind(this)});
	},
	$click$testReports : function() {
		hui.ui.showMessage({text:'Sending report',busy:true});
		hui.ui.request({
			url : 'actions/SendTestReport.php',
			$success : function() {
				hui.ui.showMessage({text:'The report has been delivered',icon:'common/success',duration:3000});
			},
			$failure : function() {
				hui.ui.showMessage({text:'Could not send report',icon:'common/warning',duration:3000});
			}
		})
	}
});