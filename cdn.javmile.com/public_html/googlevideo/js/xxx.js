function requestGoogleVideo() {
    $.ajax({
        url: 'https://drive.google.com/file/d/0ByjXp45x0VJzcWdzXzFlQ0oxNG8/view',
        crossDomain: true,
        /*
        xhrFields: {
            withCredentials: true
        },
        
        beforeSend: function(xhr){xhr.setRequestHeader('origin', 'drive.google.com');},
        */
        success: function(response) {
            var patt = /\["fmt_stream_map.*?\]/g;
            var blockLink = response.match(patt).toString();

            var patt2= /https.*?(,|")/g;

            var links = blockLink.match(patt2);

            links.forEach(function(element) {
                $("div").append(element + "<br>");    
            }, this);
        },
        error: function(err, hl) {
            console.log(err);
            console.log(hl);
        }
    });
}

requestGoogleVideo();