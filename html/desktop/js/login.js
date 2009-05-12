Ext.onReady(function(){
	var open_id_field = new Ext.form.TextField( {
		name :"open_id",
		allowBlank :false,
		fieldLabel :"Open ID",
		vtype :"alphanum",
		minLength :2,
		blankText :"Open ID Url",
		anchor :"85%"
	});
	
	var loginForm = new Ext.form.FormPanel({
		id :"login-form",
		baseCls :"x-plain",
		standardSubmit :true,
		width :300,
		height :110,
		frame :false,
		labelWidth :70,
		items : [ open_id_field ],
		keys : {
			key : [ 13 ],
			fn : function() {
				loginForm.getForm().getEl().dom.action = "login.php";
				loginForm.getForm().getEl().dom.method = "POST";
				loginForm.getForm().submit()
			},
			scope :this
		},
		buttons : [
				{
					text :"Register",
					scope :this,
					handler : function() {
						window.location.href = "register.php"
					}
				},
				{
					handler : function() {
						loginForm.getForm().getEl().dom.action = "login.php";
						loginForm.getForm().getEl().dom.method = "POST";
						loginForm.getForm().submit()
					},
					scope :this,
					text :"Login"
				} ]
	});
	
	var login_window = new Ext.Window({
		applyTo		: 'login-win',
		layout		: 'fit',
		closable	: false,
		width		: 400,
		height		: 300,
		plain		: true,
		items		: [loginForm]
	});
	
	var loading = Ext.get("loading");
	var mask = Ext.get("loading-mask");
	mask.setOpacity(1);
	mask.fadeOut( {
		xy :loading.getXY(),
		width :loading.getWidth(),
		height :loading.getHeight(),
		remove :true,
		duration :1,
		opacity :0,
		callback : function() {
			loading.fadeOut( {
				endOpacity :0,
				duration :0
			})
		}
	});
	
	login_window.show();
});
