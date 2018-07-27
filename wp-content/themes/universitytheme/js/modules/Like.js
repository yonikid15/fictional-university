import $ from 'jquery';

class Like {
    constructor() {
        this.likeBox = $( '.like-box' );
        this.events();
    }

    events() {
        this.likeBox.on( 'click', this.ourClickDispatcher.bind(this) );
    }

    // methods
    ourClickDispatcher( e ) {
        var currentLikeBox = $( e.target ).closest( '.like-box' );

        if( currentLikeBox.attr( 'data-exists' ) == 'yes' ) {
            this.deleteLike( currentLikeBox );
        } else {
            this.createLike( currentLikeBox );
        }
    }

    createLike( currentLikeBox ) {
        var professor_id = currentLikeBox.data( 'professor' );
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader( 'X-WP-Nonce', universityData.nonce );
            },
            url: universityData.root_url + `/wp-json/university/v1/manageLike`,
            type: 'POST',
            data: { 'professor_id': professor_id },
            success: (res) => {
                currentLikeBox.attr( 'data-exists' , 'yes' );
                var likeCount = parseInt( currentLikeBox.find( '.like-count' ).html(), 10 );
                likeCount++;
                currentLikeBox.find( '.like-count' ).html( likeCount );
                currentLikeBox.attr( 'data-like', res );
                console.log( res );
            },
            error: (err) => {
                console.log( err );
            }
        });
    }

    deleteLike( currentLikeBox ) {
        var like_id = currentLikeBox.attr( 'data-like' );
      
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader( 'X-WP-Nonce', universityData.nonce );
            },
            url: universityData.root_url + `/wp-json/university/v1/manageLike`,
            type: 'DELETE',
            data: { 'like': like_id },
            success: (res) => {
                var likeCount = parseInt( currentLikeBox.find( '.like-count' ).html(), 10 );
                likeCount--;
                currentLikeBox.find( '.like-count' ).html( likeCount );
                currentLikeBox.attr( 'data-exists', 'no' );
                currentLikeBox.attr( 'data-like', '' );
                console.log( res );
            },
            error: (err) => {
                console.log( err );
            }
        });
        
    }
}

export default Like;