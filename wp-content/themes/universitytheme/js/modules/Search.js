import $ from 'jquery';
import GeneralInfo from '../module-helpers/GeneralInfo';
import Programs from '../module-helpers/Programs';
import Campuses from '../module-helpers/Campuses';
import Professors from '../module-helpers/Professors';
import Events from '../module-helpers/Events';

class Search {
    // 1. The constructor is where you describe and create/initiate an object.
    constructor() {
        this.addSearchHtml();
        this.openButton = $(".js-search-trigger");
        this.closeButton = $(".search-overlay__close");
        this.searchOverlay = $(".search-overlay");
        this.searchField = $("#search-term");
        this.resultsDiv = $("#search-overlay__results");
        this.events();
        this.isOverlayOpen = false;
        this.isSpinnerVisible = false;
        this.previousValue;
        this.typingTimer;
    };

    // 2. Events
    events() {
        this.openButton.on( "click", this.openOverlay.bind(this) );
        this.closeButton.on( "click", this.closeOverlay.bind(this) );

        $(document).on( "keydown", this.keyPressDispatcher.bind(this) );
        this.searchField.on( "keyup", this.typingLogic.bind(this) );
    }

    // 3. Methods
    typingLogic() {
        if( this.searchField.val() != this.previousValue ) {
            clearTimeout( this.typingTimer );

            if( this.searchField.val() ) {
                if( !this.isSpinnerVisible ) {
                    this.resultsDiv.html('<div class="spinner-loader"></div>');
                    this.isSpinnerVisible = true;
                }
    
                this.typingTimer = setTimeout( this.getResults.bind(this), 500 );

            } else {
                this.resultsDiv.html("");
                this.isSpinnerVisible = false;
            }

            this.previousValue = this.searchField.val();

        }
    }

    getResults() {
        $.getJSON(
            `${universityData.root_url}/wp-json/university/v1/search?term=${this.searchField.val()}`, 
            ( results ) => {
                this.resultsDiv.html(`
                    <div class="row">
                        <div class="one-third">
                            <h2 class="search-overlay__section-title">
                                General Information
                            </h2>
                            ${GeneralInfo( results.generalInfo )}
                        </div>
                        <div class="one-third">
                            <h2 class="search-overlay__section-title">
                                Programs
                            </h2>
                            ${Programs( results.programs )}
                            <h2 class="search-overlay__section-title">
                                Professors
                            </h2>
                            ${Professors( results.professors )}
                        </div>
                        <div class="one-third">
                            <h2 class="search-overlay__section-title">
                                Campuses
                            </h2>    
                            ${Campuses( results.campuses )}
                            <h2 class="search-overlay__section-title">
                                Events
                            </h2>
                            ${Events( results.events )}
                        </div>
                    </div>
                `);

                this.isSpinnerVisible = false;
        });
    }

    keyPressDispatcher(e) {
        if( e.keyCode == 83 && !this.isOverlayOpen && !$( "input, textarea" ).is(':focus') ) {
            this.openOverlay();
        }
        if( e.keyCode == 27 && this.isOverlayOpen ) {
            this.closeOverlay();
        }
    }

    openOverlay() {
        this.searchOverlay.addClass( "search-overlay--active" );
        $("body").addClass("body-no-scroll");
        this.resultsDiv.html("");
        this.searchField.val("");
        setTimeout( () => this.searchField.focus() , 301 );
        this.isOverlayOpen = true;
        return false;
    }

    closeOverlay() {
        this.searchOverlay.removeClass( "search-overlay--active" );
        $("body").removeClass("body-no-scroll");
        this.isOverlayOpen = false;
    }

    addSearchHtml() {
        $("body").append(`
            <div class="search-overlay">
                <div class="search-overlay__top">
                    <div class="container">
                        <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <input id="search-term" type="text" class="search-term" placeholder="What are you looking for?">
                        <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="container">
                    <div id="search-overlay__results">
                        
                    </div>
                </div>
            </div>
        `);
    }
};

export default Search;