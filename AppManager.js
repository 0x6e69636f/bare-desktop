Ext.define("AppManager", {
  
  desktop:null,
  taskbar:null,

  buttons_by_app_id : {},


  openApp:function(win, parent){
    var me = this;
    if(win.title == null || win.title == "") win.setTitle("hello");
    win.showAt( Math.random()*1024, Math.random()*300);

    var button = me.createAppTaskbarButton(win);

    me.buttons_by_app_id[win.id] = button;

    parent.dockedItems.getAt(0).add(button);

    win.on('close',function(){
      me.closeApp(win, button, parent);
    });

  },

  closeApp:function(win, button, parent){
    var me = this;
    parent.dockedItems.getAt(0).remove(button);
    me.buttons_by_app_id[win.id] = null;
  },

  createAppTaskbarButton:function(win){
    var btn = Ext.create( "Ext.button.Button", {
      text:win.title,
      win:win,
      handler:function(){
        if(win.isVisible()){
          win.hide();
          btn.toggle(false);
        }else{
          win.show();
          btn.toggle(true);
        }
      }
    });

    if(win.iconCls != null){
      btn.iconCls = win.iconCls;
    }

    return btn;
  }
});