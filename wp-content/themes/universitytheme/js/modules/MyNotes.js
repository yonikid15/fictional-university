import $ from 'jquery';

class MyNotes {
    constructor() {
        this.events();
    }

    events() {
        $('#my-notes').on( 'click', '.delete-note', this.deleteNote);
        $('#my-notes').on( 'click', '.edit-note', this.editNote.bind( this ));
        $('#my-notes').on( 'click', '.update-note', this.updateNote.bind( this ));
        $('.submit-note').on( 'click', this.createNote.bind( this ));
    }

    // Methods will go here
    createNote( e ) {
        var ourNewPost = {
            'title': $(".new-note-title").val(),
            'content': $( ".new-note-body" ).val(),
            'status': 'publish'
        };

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader( 'X-WP-Nonce', universityData.nonce );
            },
            url: universityData.root_url + `/wp-json/wp/v2/note/`,
            type: 'POST',
            data: ourNewPost,
            success: (res) => {
                $( '.new-note-title, .new-note-body' ).val('');
                $(`
                    <li data-id="${res.id}" data-state="readonly">
                        <input
                            readonly
                            class="note-title-field"
                            type="text" 
                            value="${res.title.raw}"
                        >
                        <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                        <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                        <textarea readonly class="note-body-field">${res.content.raw}</textarea>
                        <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>
                    </li>
                `).prependTo( '#my-notes' ).hide().slideDown();

                console.log( 'congrats' );
                console.log( res );
            },
            error: (err) => { 
                if( err.responseText == "You have reached your note limit." ) {
                    $(".note-limit-message" ).addClass("active");
                }
                console.log( 'error' );
                console.log( err );
            }
        });
    }

    deleteNote( e ) {
        var thisNote = $( e.target ).parents( 'li' );
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader( 'X-WP-Nonce', universityData.nonce );
            },
            url: universityData.root_url + `/wp-json/wp/v2/note/${ thisNote.data( 'id' ) }`,
            type: 'DELETE',

            success: (res) => {
                thisNote.slideUp();
                if( res.userNoteCount < 5 ) {
                    $(".note-limit-message" ).removeClass("active");
                }
                console.log('congrats');
                console.log(res);
            },
            error: (err) => { 
                console.log('error');
                console.log(err);
            }
        });
    }

    updateNote( e ) {
        var thisNote = $( e.target ).parents( 'li' );

        var ourUpdatedPost = {
            'title': thisNote.find(".note-title-field").val(),
            'content': thisNote.find( ".note-body-field" ).val()
        };

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader( 'X-WP-Nonce', universityData.nonce );
            },
            url: universityData.root_url + `/wp-json/wp/v2/note/${ thisNote.data( 'id' ) }`,
            type: 'POST',
            data: ourUpdatedPost,
            success: (res) => {
                this.makeNoteReadOnly( thisNote );
                console.log( 'congrats' );
                console.log( res );
            },
            error: (err) => { 
                console.log( 'error' );
                console.log( err );
            }
        });
    }

    editNote( e ) {
        var thisNote = $( e.target ).parents( 'li' );
        if( thisNote.data('state') === "editable" ) {
            this.makeNoteReadOnly( thisNote );
        } else {
            this.makeNoteEditable( thisNote );
        }
    }

    makeNoteEditable( thisNote ) {
        thisNote.find(".edit-note").html('<i class="fa fa-times" aria-hidden="true"></i> Cancel');
        thisNote.find( '.note-title-field, .note-body-field' ).removeAttr( 'readonly' ).addClass( 'note-active-field' );
        thisNote.find(".update-note").addClass("update-note--visible");
        
        thisNote.data( 'state', 'editable' );
    }

    makeNoteReadOnly( thisNote ) {
        thisNote.find(".edit-note").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit');
        thisNote.find( '.note-title-field, .note-body-field' ).attr( 'readonly', 'readonly' ).removeClass( 'note-active-field' );
        thisNote.find(".update-note").removeClass("update-note--visible");
    
        thisNote.data( 'state', 'readonly' );
    }
}

export default MyNotes;