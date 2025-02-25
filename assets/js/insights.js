import InfiniteScroll from 'infinite-scroll'

let elem = document.querySelector('#data_container');
let infScroll = new InfiniteScroll(elem, {
    path: function() {
        let lastPublishedAt = elem.getAttribute('data-last-published');
        let lastId = elem.getAttribute('data-last-id');    
        let typeId = elem.getAttribute('data-type-id');

        return '/insights/load-more?last_published_at=' + lastPublishedAt + '&last_id=' + lastId + '&type_id=' + typeId;
    },
    responseBody: 'json',
    scrollThreshold: 700, //default is 400. anything below 700 doesn't works. ???
    history: false
});

// load initial page
infScroll.loadNextPage();

infScroll.on('load', function( body, path, response) {
    elem.setAttribute('data-last-published', body.lastPublishedAt);
    elem.setAttribute('data-last-id', body.lastId);

    elem.insertAdjacentHTML('beforeend', body.html);
})

var postCategories = document.getElementsByClassName("post-category");
for (var i = 0; i < postCategories.length; i++) {
    postCategories[i].addEventListener('click', function(e) {
        e.preventDefault();
        
        let typeId = this.getAttribute("data-pk");
        
        elem.setAttribute('data-last-published', "");
        elem.setAttribute('data-last-id', "");
        elem.setAttribute('data-type-id', typeId);

        elem.innerHTML = "";

        infScroll.canLoad = true; //mark can load, so loadNextpage can be called properly
        infScroll.loadNextPage();
    }, false);
}