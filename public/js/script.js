$( document ).ready( function() {


    $( ".container-fluid" ).css( "min-height", $( document ).height() );

    ajaxRequestHandler( "#backlinkAdd_submit", "#backlinkAdd", "/ajax/backlinks/add/", "add", "#backlinkAlert", 0 );

    ajaxRequestHandler( "#settingsUpdate_submit", "#settingsUpdate", "/ajax/settings/update/", "update", "#settingsAlert" );

    ajaxRequestHandler( "#projectUpdate_submit", "#projectUpdate", "/ajax/projects/update/", "update", ".alert" );

    ajaxRequestHandler( "#keywordsAdd_submit", "#keywordsAdd", "/ajax/keywords/add/", "add", ".alert" );

    ajaxRequestHandler( "#projectAdd_submit", "#projectAdd", "/ajax/projects/add/", "add", ".alert" );


    $( '#commentModal' ).on( 'show.bs.modal', function( event ) {
        var button = $( event.relatedTarget );
        var recipient = button.data( 'comment' );
        var modal = $( this );
        modal.find( '.modal-body' ).text( recipient );
    } )

    $( document ).on( 'click', '.backlinkRemove', function( event ) {
        event.preventDefault();
        var bid = $( this ).attr( "data-bid" );

        if ( askBeforeCriticalAction( "Sicher, dass du den Backlink " + bid + " endgültig löschen willst?" ) ) {

            $.ajax( {
                url : '/ajax/backlinks/remove/',
                type : 'POST',
                data : { backlinkID : bid },
                success : function( data ) {

                    data = $.parseJSON( data );
                    if ( data.error == 0 ) {
                        $( ".b" + bid ).fadeOut();
                    }

                },
                error : function( data ) {
                    console.log( data );
                }
            } );
        }


    } );

    $( document ).on( 'click', '.projectRemove', function( event ) {
        event.preventDefault();
        var pid = $( this ).attr( "data-pid" );

        if ( askBeforeCriticalAction( "Sicher, dass du Projekt " + pid + " (inkl. Konkurrenz und Keywords) endgültig löschen willst?" ) ) {

            $.ajax( {
                url : '/ajax/projects/remove/',
                type : 'POST',
                data : { projectID : pid },
                success : function( data ) {

                    data = $.parseJSON( data );
                    if ( data.error == 0 ) {
                        $( ".p" + pid ).fadeOut();
                    }

                },
                error : function( data ) {
                    console.log( data );
                }
            } );
        }


    } );

    $( document ).on( 'click', '.keywordRemove', function( event ) {
        event.preventDefault();
        var kid = $( this ).attr( "data-kid" );

        if ( askBeforeCriticalAction( "Sicher, dass du das Keyword " + kid + " (inkl. Rankings für alle Projekte) endgültig löschen willst?" ) ) {

            $.ajax( {
                url : '/ajax/keywords/remove/',
                type : 'POST',
                data : { keywordID : kid },
                success : function( data ) {

                    data = $.parseJSON( data );
                    if ( data.error == 0 ) {
                        $( ".k" + kid ).fadeOut();
                    }

                },
                error : function( data ) {
                    console.log( data );
                }
            } );
        }


    } );

    $( "#dateSelecter" ).change( function() {
        location.href = $( this ).val();
    } );



    $( "#search" ).keyup( function() {
        var value = this.value.toLowerCase().trim();

        $( "#searchableTable tr" ).each( function( index ) {
            if ( !index )
                return;
            $( this ).find( "td" ).each( function() {
                var id = $( this ).text().toLowerCase().trim();
                var not_found = (id.indexOf( value ) == -1);
                $( this ).closest( 'tr' ).toggle( !not_found );
                return not_found;
            } );
        } );
    } );


} );



function askBeforeCriticalAction( message )
{
    var result = confirm( message );
    if ( result == true ) {
        return true;
    } else {
        return false;

    }
}

function ajaxRequestHandler( clickedElement, formWithData, ajaxurl, actionIdentifier, alertElement, resetinputs ) {
    resetinputs = typeof resetinputs !== 'undefined' ? resetinputs : 0;

    $( document ).on( 'click', clickedElement, function( event ) {
        event.preventDefault();
        $( ".fa-save" ).hide();
        $( ".fa-spinner" ).fadeIn();

        var values = { };
        values[actionIdentifier] = $( formWithData ).serialize();

        $.ajax( {
            url : ajaxurl,
            type : 'POST',
            data : values,
            success : function( data ) {
                data = $.parseJSON( data );
                if ( data.error == 0 ) {
                    $( alertElement ).addClass( "alert-success" ).removeClass( "alert-danger" );
                    $( "#message" ).html( data.message );
                    $( ".messageHolder" ).fadeIn();
                } else {
                    $( alertElement ).addClass( "alert-danger" ).removeClass( "alert-success" );
                    $( "#message" ).html( data.message );
                    $( ".messageHolder" ).fadeIn();
                }

                if ( resetinputs == 1 ) {

                    $( formWithData + " :input" ).each( function() {
                        $( this ).val( '' );
                    } );

                }
            }

        } ).done( function( data ) {
            $( ".fa-spinner" ).hide();
            $( ".fa-save" ).fadeIn();
            console.log( data );
        } );
    } );
}