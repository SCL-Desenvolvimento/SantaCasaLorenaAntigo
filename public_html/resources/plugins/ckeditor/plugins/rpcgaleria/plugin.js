/*CKEDITOR.plugins.add('rpcgaleria',
{
    init: function (editor) {
        var pluginName = 'rpcgaleria';
        editor.ui.addButton('rpcGaleria',
            {
                label: 'My New Plugin',
                command: 'OpenWindow',
                icon: CKEDITOR.plugins.getPath('rpcgaleria') + 'rpcgaleriabuttonicon.png'
            });
        var cmd = editor.addCommand('OpenWindow', { exec: showMyDialog });
    }
});

function showMyDialog(e) {
    e.insertHtml(' Hello ');
}

/*
function showMyDialog(e) {
    window.open('/Default.aspx', 'MyWindow', 'width=800,height=700,scrollbars=no,scrolling=no,location=no,toolbar=no');
}
*/

CKEDITOR.plugins.add( 'rpcgaleria', {
    requires: 'widget',
    lang: 'en',
    icons: 'rpcgaleria',
    init: function( editor ) {
        editor.widgets.add( 'rpcgaleria', {
            button: editor.lang.rpcgaleria.button,
            template: '<div class="rpc-galleria" ><input value=""></input></div>',
            editables: {},
            allowedContent: 'div(!rpc-galleria); input;',
            requiredContent: 'div(rpc-galleria); input',
            upcast: function( element ) {
                return element.name === 'div' && element.hasClass( 'rpc-galleria' );
            },
            dialog: 'rpcgaleria',
            init: function() {
                //alert(this.element.getChild( 0 ));
                var src = this.element.getChild( 0 ).getAttribute( 'value' );
                var align = this.element.getStyle( 'text-align' );

                if ( src ) {
                    //alert(src);
                    this.setData( 'src', src );

                    if ( align ) {
                        this.setData( 'align', align );
                    } else {
                        this.setData( 'align', 'none' );
                    }
                }
            },
            data: function() {
                this.element.getChild( 0 ).setAttribute( 'value', this.data.src );

                this.element.removeStyle( 'float' );
                this.element.removeStyle( 'margin-left' );
                this.element.removeStyle( 'margin-right' );

                if ( this.data.align === 'none' ) {
                    this.element.removeStyle( 'text-align' );
                } else {
                    this.element.setStyle( 'text-align', this.data.align );
                }

                if ( this.data.align === 'left' ) {
                    this.element.setStyle( 'float', this.data.align );
                    this.element.setStyle( 'margin-right', '10px' );
                } else if ( this.data.align === 'right' ) {
                    this.element.setStyle( 'float', this.data.align );
                    this.element.setStyle( 'margin-left', '10px' );
                }
            }
        } );

        if ( editor.contextMenu ) {
            editor.addMenuGroup( 'rpcgaleriaGroup' );
            editor.addMenuItem( 'rpcgaleriaPropertiesItem', {
                label: editor.lang.rpcgaleria.audioProperties,
                icon: this.path + 'rpcgaleria.png',
                command: 'rpcgaleria',
                group: 'rpcgaleriaGroup'
            });

            editor.contextMenu.addListener( function( element ) {
                if ( element &&
                     element.getChild( 0 ) &&
                     element.getChild( 0 ).hasClass &&
                     element.getChild( 0 ).hasClass( 'rpc-galleria' ) ) {
                    return { rpcgaleriaPropertiesItem: CKEDITOR.TRISTATE_OFF };
                }
            });
        }

        CKEDITOR.dialog.add( 'rpcgaleria', this.path + 'dialogs/rpcgaleria.js' );
    }
} );
