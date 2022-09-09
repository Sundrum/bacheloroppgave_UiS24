function SnapToRoute(map, marker, polylinepath)
{
    latsnlngs.push(firstpoint);
    latsnlngs.push(endpoint);
	polyline = new google.maps.Polyline({
				path: latsnlngs,
				strokeColor: "#004079",
				strokeWeight: 2,
				strokeOpacity: 0.1
	});
    polyline.setMap(map);
    //polyline.setPath(latsnlngs);
    //this.latsnlngs_distance = google.maps.geometry.spherical.computeDistanceBetween(endpoint,firstpoint);
    this.routePixels_ = [];
    this.normalProj_ = map.getProjection();
    this.map_ = map;
    this.marker_ = marker;
    this.polyline_ = polyline;
    this.polyline_.setMap(map);

	if (polyline) console.log("map = ok");
	else console.log("map = undefined");

	console.log("Adding SMS marker...");
    this.init_();
    console.log("...SMS marker added");
}

SnapToRoute.prototype.init_ = function () {
    this.loadLineData_();
    //if (this.marker_ != activeMarker) {
        this.loadMapListener_();
    //}
};

SnapToRoute.prototype.updateTargets = function (marker, polyline) {
    this.marker_ = marker || this.marker_;
    this.polyline_ = polyline || this.polyline_;
    this.loadLineData_();
};

SnapToRoute.prototype.loadMapListener_ = function () {
    var me = this;
    if (this.marker_ == poiMarker1) {
        var point_id = 1;
    } else if (this.marker_ == poiMarker2) {
        var point_id = 2;
    }

    google.maps.event.addListener(me.marker_, "dragend", function (evt) {
        me.updateMarkerLocation_(evt.latLng);
        showContextMenuPOI(evt.latLng, point_id);
    });

    google.maps.event.addListener(me.marker_, "drag", function (evt) {
        me.updateMarkerLocation_(evt.latLng);
        showContextMenuPOI(evt.latLng, point_id);
    });
};

SnapToRoute.prototype.loadLineData_ = function () {
    //var zoom = this.map_.getZoom();
    this.routePixels_ = [];
    var path = this.polyline_.getPath();
    //var length = this.latsnlngs_distance;    
    //var routePixels_ = this.routePixels_;

    //var distancetomove = this.getClosestPointOnLines_(activeMarker, routePixels_);
    

	if (typeof path == 'undefined') console.log("undefined path");
	else console.log("path ok?");
	console.log('path length = '+length);


    for (var i = 0; i < path.getLength(); i++) {
        console.log('getAt = '+path.getAt(i)+' i = '+i);
        var Px = this.normalProj_.fromLatLngToPoint(path.getAt(i));
        console.log('getAt = '+path.getAt(i)+' i = '+i);
        this.routePixels_.push(Px);
    }
};

SnapToRoute.prototype.updateMarkerLocation_ = function (mouseLatLng) {
    var markerLatLng = this.getClosestLatLng(mouseLatLng);
    this.marker_.setPosition(markerLatLng);
};

SnapToRoute.prototype.getClosestLatLng = function (latlng) {
    var r = this.distanceToLines_(latlng);
    console.log("distance to line = " + r);
    return this.normalProj_.fromPointToLatLng(new google.maps.Point(r.x, r.y));
};

SnapToRoute.prototype.getDistAlongRoute = function (latlng) {
    if (typeof (opt_latlng) === 'undefined') {
        latlng = this.marker_.getLatLng();
    }
    var r = this.distanceToLines_(latlng);
    return this.getDistToLine_(r.i, r.to);
};

SnapToRoute.prototype.distanceToLines_ = function (mouseLatLng) {
    //var zoom = this.map_.getZoom();
    var mousePx = this.normalProj_.fromLatLngToPoint(mouseLatLng);
    var routePixels_ = this.routePixels_;
    return this.getClosestPointOnLines_(mousePx, routePixels_);
};

SnapToRoute.prototype.getDistToLine_ = function (line, to) {
    var routeOverlay = this.polyline_;
    var d = 0;
    for (var n = 1; n < line; n++) {
        d += google.maps.geometry.spherical.computeDistanceBetween(routeOverlay.getAt(n - 1), routeOverlay.getAt(n));
    }
    d += google.maps.geometry.spherical.computeDistanceBetween(routeOverlay.getAt(line - 1), routeOverlay.getAt(line)) * to;
    return d;
};

SnapToRoute.prototype.getClosestPointOnLines_ = function (pXy, aXys) {
    var minDist;
    var to;
    var from;
    var x;
    var y;
    var i;
    var dist;

    if (aXys.length > 1) {
        for (var n = 1; n < aXys.length; n++) {
            if (aXys[n].x !== aXys[n - 1].x) {
                var a = (aXys[n].y - aXys[n - 1].y) / (aXys[n].x - aXys[n - 1].x);
                var b = aXys[n].y - a * aXys[n].x;
                dist = Math.abs(a * pXy.x + b - pXy.y) / Math.sqrt(a * a + 1);
            } else {
                dist = Math.abs(pXy.x - aXys[n].x);
            }

            var rl2 = Math.pow(aXys[n].y - aXys[n - 1].y, 2) + Math.pow(aXys[n].x - aXys[n - 1].x, 2);
            var ln2 = Math.pow(aXys[n].y - pXy.y, 2) + Math.pow(aXys[n].x - pXy.x, 2);
            var lnm12 = Math.pow(aXys[n - 1].y - pXy.y, 2) + Math.pow(aXys[n - 1].x - pXy.x, 2);
            var dist2 = Math.pow(dist, 2);
            var calcrl2 = ln2 - dist2 + lnm12 - dist2;
            if (calcrl2 > rl2) {
                dist = Math.sqrt(Math.min(ln2, lnm12));
            }

            if ((minDist == null) || (minDist > dist)) {
                to = Math.sqrt(lnm12 - dist2) / Math.sqrt(rl2);
                from = Math.sqrt(ln2 - dist2) / Math.sqrt(rl2);
                minDist = dist;
                i = n;
            }
        }
        if (to > 1) {
            to = 1;
        }
        if (from > 1) {
            to = 0;
            from = 1;
        }
        var dx = aXys[i - 1].x - aXys[i].x;
        var dy = aXys[i - 1].y - aXys[i].y;

        x = aXys[i - 1].x - (dx * to);
        y = aXys[i - 1].y - (dy * to);
    }
    return {
        'x': x,
        'y': y,
        'i': i,
        'to': to,
        'from': from
    };
};