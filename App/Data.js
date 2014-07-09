Ext.define( 'App.Data', {
    extend:'Ext.window.Window',
    width: 1024,
    height: 768,
    stateful: false,
    isWindow: true,
    //headerPosition: 'left',
    title:"Data Explorer",
    layout:'border',
    items:[
      {
        xtype:'panel',
        region:'west',
        width:300,
        collapsible:true,
        title:'Project Tree'
      },
      {
        xtype:'panel',
        region:'center',
        title:'Main Panel'
      }
    ]
});