(function(doc) {
    var prefetcher = doc.createElement('link');
    prefetcher.setAttribute('rel', 'prerender');
    prefetcher.setAttribute('id', 'prerender');
    doc.getElementsByTagName('head')[0].appendChild(prefetcher);

    var prefetch = function(event) {
        var target = event.target;
        var link = target.closest ? target.closest('a') : null;
        if (!link) return;

        var href = link.getAttribute('href');
        if (href.indexOf(origin) === 0 && href.match(/\.html$/)) {
            prefetcher.setAttribute('href', href);
            console.log('Prerendering: ' + href);
        }
    };

    doc.addEventListener('mouseover', prefetch);
    doc.addEventListener('touchstart', prefetch);
})(document);
