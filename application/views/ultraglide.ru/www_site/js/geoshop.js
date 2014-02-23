function map_init(mapSettings, locations)
{
    console.log(mapSettings);
    console.log(locations);

    var mapCenter = new google.maps.LatLng(mapSettings[2], mapSettings[3]);

    var map = new google.maps.Map(document.getElementById(mapSettings[0]), {
        zoom: mapSettings[1],
        center: mapCenter,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infoWindow = new google.maps.InfoWindow();
    var marker, i, content;

    for ( i = 0; i < locations.length; i++ )
    {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][4], locations[i][5]),
            map: map,
            icon: 'http://google-maps-icons.googlecode.com/files/factory.png'
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                content =
                    '<div id="bubble">' +
                        '<div>' +
                            '<span class="inf-town">' + locations[i][0] + '</span>, ' + locations[i][1] +
                        '</div>' +
                        '<div>' +
                            locations[i][2] +
                        '</div>' +
                        '<div>' +
                            locations[i][3] +
                        '</div>' +
                    '</div>'
                ;
                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            }
        })(marker, i));
    }
}