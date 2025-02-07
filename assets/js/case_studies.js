import InfiniteScroll from 'infinite-scroll'

let elem = document.querySelector('#data_container');
let infScroll = new InfiniteScroll(elem, {
    path: function() {
        let allCount = parseInt(elem.getAttribute('data-all'));
        let currentCount = parseInt(elem.getAttribute('data-current'));
        if (parseInt(allCount) > parseInt(currentCount)) {
            let lastPublishedAt = elem.getAttribute('data-last-published');
            let lastId = elem.getAttribute('data-last-id');    

            return '/case-studies/load-more?last_published_at=' + lastPublishedAt + '&last_id=' + lastId;
        }
    },
    responseBody: 'json',
    scrollThreshold: 700, //default is 400. anything below 700 doesn't works. ???
    history: false
});

// load initial page
infScroll.loadNextPage();

infScroll.on('load', function( body, path, response) {
    let currentCount = parseInt(elem.getAttribute('data-current'));
    let totalFeched = currentCount + parseInt(body.nbData);

    elem.setAttribute('data-current', totalFeched)
    elem.setAttribute('data-last-published', body.lastPublishedAt);
    elem.setAttribute('data-last-id', body.lastId);

    elem.insertAdjacentHTML('beforeend', body.html);
})