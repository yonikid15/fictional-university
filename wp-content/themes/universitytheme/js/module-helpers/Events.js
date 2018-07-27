export default ( events ) => {
    return ( events.length > 0 )  ? 
        (`${ events.map( item => {
            return (`
            <div class="event-summary">
                <a class="event-summary__date t-center" href="#">
                <span class="event-summary__month">${item.month}</span>
                <span class="event-summary__day">${item.day}</span>  
                </a>
                <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                <p>${item.excerpt}<a href="${item.permalink}" class="nu gray"> Learn more</a></p>
                </div>
            </div>
            `);
        }).join('') }`)

        :

        ( `<p>No events match that search. <a href="${universityData.root_url}/campuses">View all Events.</a></p>` )
}