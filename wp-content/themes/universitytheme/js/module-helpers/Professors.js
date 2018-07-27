export default ( professors ) => {
    return ( professors.length > 0 )  ? 
        (`<ul class="professor-cards"> 
            ${ professors.map( item => {
                return (`
                <li class="professor-card__list-item">
                    <a class="professor-card" href="${item.permalink}">
                        <img class="professor-card__image" src="${item.image}">
                        <span class="professor-card__name">${item.title}</span>
                    </a>
                </li>
                `);
            }).join('') }
            </ul>`
        )

        :

        ( `<p>No professors match that search.</p>` )
}