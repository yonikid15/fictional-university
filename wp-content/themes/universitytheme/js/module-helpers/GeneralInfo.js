export default ( generalInfo ) => {
    return ( generalInfo.length > 0 )  ? 
        (`<ul class="link-list min-list"> 
            ${ generalInfo.map( item => {
                return (`
                    <li>
                        <a href="${item.permalink}">${item.title}</a> ${
                            item.postType == 'post' ?
                                `by ${item.authorName}`
                            :
                                ''
                        }
                    </li>
                `);
            }).join('') }
            </ul>`
        )

        :

        ( `<p>No general information matches that search. <a href="${universityData.root_url}/blog">View all Posts.</a></p>` )
}