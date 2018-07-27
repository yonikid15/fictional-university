export default ( programs ) => {
    return ( programs.length > 0 )  ? 
        (`<ul class="link-list min-list"> 
            ${ programs.map( item => {
                return (`
                    <li>
                        <a href="${item.permalink}">${item.title}</a>
                    </li>
                `);
            }).join('') }
            </ul>`
        )

        :

        ( `<p>No programs match that search. <a href="${universityData.root_url}/programs">View all Programs.</a></p>` )
}