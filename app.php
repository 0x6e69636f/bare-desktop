<?php

$apps_dir_content = scandir('App');
$required_app_classes = array();
$apps = array();

foreach($apps_dir_content as $app_filename){
    if($app_filename != '.' && $app_filename != '..'){
        $app_name_split = preg_split("/\./",$app_filename);
        array_pop($app_name_split);
        $app_name = join("",$app_name_split);

        $app_name_class = "'App.".$app_name."'";
        array_push($apps, array($app_name => $app_name_class));
        array_push($required_app_classes, $app_name_class);

    }
}

?>

Ext.require([ 'AppManager', <?php echo join(",",$required_app_classes) ?> ]);

Ext.onReady(function(){

    Ext.application({
        name   : 'MyApp',
        initComponent: function(){
            console.log("hello");
        },
        
        desktop_panel : null,
        app_manager : null,
        app_store : null,
        start_menu : null,

        launch : function() {
            var me = this;

           me.start_menu = me.build_start_menu();

           me.desktop_panel = Ext.create('Ext.Panel', {
                renderTo     : Ext.getBody(),
                width        : window.width,
                height       : 968,
                maximized : true,
                closable: false,
                bodyPadding  : 5,
                layout:'hbox',

                tbar: [
                    {
                        iconCls:'proline-icon',
                        handler:function(){ 
                            if(me.start_menu.isVisible()){ me.start_menu.hide(); }
                            else{ me.start_menu.showAt(0,40); } 
                        }
                    },
                    '-'
                ]
            });

            me.app_manager = new AppManager();

        },

        build_app_store: function(){
            var me = this;
            me.app_store = new Ext.data.Store({
                fields:['name','class_name'],
                autoLoad:true,
                proxy:{
                    url:'get_apps.php',
                    type:'ajax'
                }
            });

            return me.app_store;
        },

        build_start_menu: function(){
            var me = this;

            if(me.app_store == null){
                me.build_app_store();
            }

            me.start_menu = Ext.create( 'Ext.window.Window', {
                width: 400,
                height: 500,
                stateful: false,
                headerPosition: 'left',
                draggable:false,
                closable:false,
                title:"Start",
                layout:'border',
                items:[
                    {
                        xtype:'gridpanel',
                        region:'center',
                        store:me.app_store,

                        listeners:{
                            itemclick:function(v,r){
                                var w = Ext.create(r.data.class_name);
                                 me.app_manager.openApp(w, me.desktop_panel);
                                 me.start_menu.hide();
                            }
                        },

                        columns:[
                            {
                                text:'Apps',
                                dataIndex:'name',
                                flex:1
                            }
                        ]
                    }
                ]
            });

            return me.start_menu;
        },

        open_window: function(title, icon){
            var me = this;
            var win = Ext.create( 'Ext.window.Window', {
                width: 800,
                height: 600,
                stateful: false,
                isWindow: true,
                //headerPosition: 'left',
                title:title
            });

            if(icon != null){
                win.iconCls = icon;
            }

            me.app_manager.openApp(win, me.desktop_panel);
        }
    });
});