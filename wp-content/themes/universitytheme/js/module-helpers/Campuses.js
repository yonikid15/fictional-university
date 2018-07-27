export default ( campuses ) => {
    return ( campuses.length > 0 )  ? 
        (`<ul class="link-list min-list"> 
            ${ campuses.map( item => {
                return (`
                    <li>
                        <a href="${item.permalink}">${item.title}</a>
                    </li>
                `);
            }).join('') }
            </ul>`
        )

        :

        ( `<p>No campuses match that search. <a href="${universityData.root_url}/campuses">View all Campuses.</a></p>` )
}