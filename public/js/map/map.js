/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/map/map.js":
/*!*********************************!*\
  !*** ./resources/js/map/map.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("var positions = Array();\n\nfunction initMap() {\n  getLatestRun('21-1020-AB-00139');\n  var mapDiv = document.getElementById('map');\n  map = new google.maps.Map(mapDiv, {\n    center: positions[0],\n    mapTypeId: 'satellite',\n    mapTypeControl: true,\n    zoom: 16,\n    streetViewControl: false,\n    tilt: 0\n  });\n} // end initMap\n\n\nfunction getLatestRun(serialnumber) {\n  //var token = $(\"meta[name='csrf-token']\").attr(\"content\");\n  $.ajax({\n    url: \"/irrigationrun/\" + serialnumber,\n    type: 'GET',\n    success: function success(data) {\n      console.log('Data' + data);\n\n      for (var i in data) {\n        positions.push({\n          lat: data[i].lat,\n          lng: data[i].lng\n        });\n      } //map.setCenter(positions[0]);\n\n    }\n  });\n}\n\ngoogle.maps.event.addDomListener(window, \"load\", getLatestRun);\ngoogle.maps.event.addDomListener(window, \"load\", initMap);\nvar latlngs = Array();\nvar endIcon = new google.maps.MarkerImage(\"../img/irr_icon_final.png\", null, null, new google.maps.Point(4, 28), new google.maps.Size(25, 25));\nvar activeIcon = new google.maps.MarkerImage(\"../img/irr_icon_present.png\", null, null, new google.maps.Point(4, 28), new google.maps.Size(25, 25));\nvar startIcon = new google.maps.MarkerImage(\"../img/irr_icon_start.png\", null, null, new google.maps.Point(4, 28), new google.maps.Size(25, 25));\nvar poiIcon = new google.maps.MarkerImage(\"../img/irr_icon_poi.png\", null, null, new google.maps.Point(4, 28), new google.maps.Size(25, 25));\nvar runIcon = {\n  path: google.maps.SymbolPath.CIRCLE,\n  scale: 2\n};\n\nfunction addMarkers() {\n  for (i = positions.length - 1; i >= 0; i--) {\n    if (i == positions.length - 1) {\n      iconR = startIcon;\n      latlngs.push(new google.maps.LatLng(positions[i]));\n    } else if (i == 0) {\n      iconR = activeIcon;\n    } else {\n      iconR = runIcon;\n    }\n\n    marker = new google.maps.Marker({\n      position: new google.maps.LatLng(positions[i]),\n      icon: iconR,\n      map: map\n    });\n    /*google.maps.event.addListener(marker, 'click', (function(marker, i) {\n        return function() {\n             var distance = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(positions[i]), new google.maps.LatLng(positions[positions.length - 1]));\n          if (i > 0) {\n              var distance2 = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(positions[i]), new google.maps.LatLng(positions[i-1]));\n          }\n          var then = positions[count-1][4];\n          var now = positions[i][4];\n          var diff = moment.duration(moment(now).diff(moment(then)));\n          var mt = (distance / (diff/1000))*3600;\n                var back;\n          if (count > 0) back = 0;\n          else back = count;\n             var distance2 = google.maps.geometry.spherical.computeDistanceBetween(new google.maps.LatLng(markerLatLon[i-back][1], markerLatLon[i-back][2]), new google.maps.LatLng(markerLatLon[count-1][1], markerLatLon[count-1][2]));\n          var then2 = markerLatLon[count-1][4];\n          var now2 = markerLatLon[i-back][4];\n          var diff2 = moment.duration(moment(now2).diff(moment(then2)));\n          var mt2 = (distance2 / (diff2/1000))*3600;\n             infowindow.setContent(markerLatLon[i][4]+'<br>'+markerLatLon[i][3]+'<br>Distanse = '+distance.toFixed(1)+' meters, '+mt.toFixed(1)+'('+mt.toFixed(1)+')'+' meters per hour ');\n          \n          infowindow.open(map, marker);\n        }\n      })(marker, i));*/\n  }\n}\n\nfunction infoCallback(infowindow) {\n  return function () {\n    infowindow.open(map);\n  };\n}//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvbWFwL21hcC5qcz9jYjY0Il0sIm5hbWVzIjpbInBvc2l0aW9ucyIsIkFycmF5IiwiaW5pdE1hcCIsImdldExhdGVzdFJ1biIsIm1hcERpdiIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJtYXAiLCJnb29nbGUiLCJtYXBzIiwiTWFwIiwiY2VudGVyIiwibWFwVHlwZUlkIiwibWFwVHlwZUNvbnRyb2wiLCJ6b29tIiwic3RyZWV0Vmlld0NvbnRyb2wiLCJ0aWx0Iiwic2VyaWFsbnVtYmVyIiwiJCIsImFqYXgiLCJ1cmwiLCJ0eXBlIiwic3VjY2VzcyIsImRhdGEiLCJjb25zb2xlIiwibG9nIiwiaSIsInB1c2giLCJsYXQiLCJsbmciLCJldmVudCIsImFkZERvbUxpc3RlbmVyIiwid2luZG93IiwibGF0bG5ncyIsImVuZEljb24iLCJNYXJrZXJJbWFnZSIsIlBvaW50IiwiU2l6ZSIsImFjdGl2ZUljb24iLCJzdGFydEljb24iLCJwb2lJY29uIiwicnVuSWNvbiIsInBhdGgiLCJTeW1ib2xQYXRoIiwiQ0lSQ0xFIiwic2NhbGUiLCJhZGRNYXJrZXJzIiwibGVuZ3RoIiwiaWNvblIiLCJMYXRMbmciLCJtYXJrZXIiLCJNYXJrZXIiLCJwb3NpdGlvbiIsImljb24iLCJpbmZvQ2FsbGJhY2siLCJpbmZvd2luZG93Iiwib3BlbiJdLCJtYXBwaW5ncyI6IkFBQUEsSUFBSUEsU0FBUyxHQUFHQyxLQUFLLEVBQXJCOztBQUVBLFNBQVNDLE9BQVQsR0FBbUI7QUFDZkMsY0FBWSxDQUFDLGtCQUFELENBQVo7QUFFQSxNQUFJQyxNQUFNLEdBQUdDLFFBQVEsQ0FBQ0MsY0FBVCxDQUF3QixLQUF4QixDQUFiO0FBQ0FDLEtBQUcsR0FBRyxJQUFJQyxNQUFNLENBQUNDLElBQVAsQ0FBWUMsR0FBaEIsQ0FBb0JOLE1BQXBCLEVBQTRCO0FBQzlCTyxVQUFNLEVBQUVYLFNBQVMsQ0FBQyxDQUFELENBRGE7QUFFOUJZLGFBQVMs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dUNDLE9BQUssRUFBRTtBQUE5QyxDQUFkOztBQUVBLFNBQVNDLFVBQVQsR0FBcUI7QUFDakIsT0FBS3BCLENBQUMsR0FBRzFCLFNBQVMsQ0FBQytDLE1BQVYsR0FBbUIsQ0FBNUIsRUFBK0JyQixDQUFDLElBQUksQ0FBcEMsRUFBdUNBLENBQUMsRUFBeEMsRUFBNEM7QUFDeEMsUUFBSUEsQ0FBQyxJQUFLMUIsU0FBUyxDQUFDK0MsTUFBVixHQUFtQixDQUE3QixFQUFpQztBQUM3QkMsV0FBSyxHQUFHVCxTQUFSO0FBQ0FOLGFBQU8sQ0FBQ04sSUFBUixDQUFhLElBQUluQixNQUFNLENBQUNDLElBQVAsQ0FBWXdDLE1BQWhCLENBQXVCakQsU0FBUyxDQUFDMEIsQ0FBRCxDQUFoQyxDQUFiO0FBQ0gsS0FIRCxNQUdPLElBQUlBLENBQUMsSUFBSSxDQUFULEVBQVk7QUFDZnNCLFdBQUssR0FBR1YsVUFBUjtBQUNILEtBRk0sTUFFQTtBQUNIVSxXQUFLLEdBQUdQLE9BQVI7QUFDSDs7QUFDRFMsVUFBTSxHQUFHLElBQUkxQyxNQUFNLENBQUNDLElBQVAsQ0FBWTBDLE1BQWhCLENBQXVCO0FBQzVCQyxjQUFRLEVBQUUsSUFBSTVDLE1BQU0sQ0FBQ0MsSUFBUCxDQUFZd0MsTUFBaEIsQ0FBdUJqRCxTQUFTLENBQUMwQixDQUFELENBQWhDLENBRGtCO0FBRTVCMkIsVUFBSSxFQUFFTCxLQUZzQjtBQUc1QnpDLFNBQUcsRUFBRUE7QUFIdUIsS0FBdkIsQ0FBVDtBQU1BOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQTRCSDtBQUNKOztBQUVELFNBQVMrQyxZQUFULENBQXNCQyxVQUF0QixFQUFrQztBQUM5QixTQUFPLFlBQVc7QUFDZEEsY0FBVSxDQUFDQyxJQUFYLENBQWdCakQsR0FBaEI7QUFDSCxHQUZEO0FBR0giLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvbWFwL21hcC5qcy5qcyIsInNvdXJjZXNDb250ZW50IjpbInZhciBwb3NpdGlvbnMgPSBBcnJheSgpO1xuXG5mdW5jdGlvbiBpbml0TWFwKCkge1xuICAgIGdldExhdGVzdFJ1bignMjEtMTAyMC1BQi0wMDEzOScpO1xuXG4gICAgdmFyIG1hcERpdiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdtYXAnKTtcbiAgICBtYXAgPSBuZXcgZ29vZ2xlLm1hcHMuTWFwKG1hcERpdiwge1xuICAgICAgICBjZW50ZXI6IHBvc2l0aW9uc1swXSxcbiAgICAgICAgbWFwVHlwZUlkOiAnc2F0ZWxsaXRlJyxcbiAgICAgICAgbWFwVHlwZUNvbnRyb2w6IHRydWUsXG4gICAgICAgIHpvb206IDE2LFxuICAgICAgICBzdHJlZXRWaWV3Q29udHJvbDogZmFsc2UsXG4gICAgICAgIHRpbHQ6IDBcbiAgICB9KTtcblxufVx0Ly8gZW5kIGluaXRNYXBcblxuZnVuY3Rpb24gZ2V0TGF0ZXN0UnVuKHNlcmlhbG51bWJlcikgXG57XG4gICAgLy92YXIgdG9rZW4gPSAkKFwibWV0YVtuYW1lPSdjc3JmLXRva2VuJ11cIikuYXR0cihcImNvbnRlbnRcIik7XG4gICAgJC5hamF4KHtcbiAgICAgICAgdXJsOiBcIi9pcnJpZ2F0aW9ucnVuL1wiICsgc2VyaWFsbnVtYmVyLFxuICAgICAgICB0eXBlOiAnR0VUJyxcblxuICAgICAgICBzdWNjZXNzOiBmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICAgY29uc29sZS5sb2coJ0RhdGEnICsgZGF0YSk7XG4gICAgICAgICAgICBmb3IgKHZhciBpIGluIGRhdGEpIHtcbiAgICAgICAgICAgICAgICBwb3NpdGlvbnMucHVzaCh7bGF0OiBkYXRhW2ldLmxhdCwgbG5nOiBkYXRhW2ldLmxuZ30pO1xuICAgICAgICAgICAgfVxuICAgICAgICAgICAgLy9tYXAuc2V0Q2VudGVyKHBvc2l0aW9uc1swXSk7XG4gICAgICAgIH0sXG4gICAgfSlcbn1cblxuZ29vZ2xlLm1hcHMuZXZlbnQuYWRkRG9tTGlzdGVuZXIod2luZG93LCBcImxvYWRcIiwgZ2V0TGF0ZXN0UnVuKTtcbmdvb2dsZS5tYXBzLmV2ZW50LmFkZERvbUxpc3RlbmVyKHdpbmRvdywgXCJsb2FkXCIsIGluaXRNYXApO1xuXG52YXIgbGF0bG5ncyA9IEFycmF5KCk7XG52YXIgZW5kSWNvbiA9IG5ldyBnb29nbGUubWFwcy5NYXJrZXJJbWFnZShcIi4uL2ltZy9pcnJfaWNvbl9maW5hbC5wbmdcIiwgbnVsbCwgbnVsbCwgbmV3IGdvb2dsZS5tYXBzLlBvaW50KDQsIDI4KSwgbmV3IGdvb2dsZS5tYXBzLlNpemUoMjUsMjUpKTtcbnZhciBhY3RpdmVJY29uID0gbmV3IGdvb2dsZS5tYXBzLk1hcmtlckltYWdlKFwiLi4vaW1nL2lycl9pY29uX3ByZXNlbnQucG5nXCIsIG51bGwsIG51bGwsIG5ldyBnb29nbGUubWFwcy5Qb2ludCg0LCAyOCksIG5ldyBnb29nbGUubWFwcy5TaXplKDI1LDI1KSk7XG52YXIgc3RhcnRJY29uID0gbmV3IGdvb2dsZS5tYXBzLk1hcmtlckltYWdlKFwiLi4vaW1nL2lycl9pY29uX3N0YXJ0LnBuZ1wiLCBudWxsLCBudWxsLCBuZXcgZ29vZ2xlLm1hcHMuUG9pbnQoNCwgMjgpLCBuZXcgZ29vZ2xlLm1hcHMuU2l6ZSgyNSwyNSkpO1xudmFyIHBvaUljb24gPSBuZXcgZ29vZ2xlLm1hcHMuTWFya2VySW1hZ2UoXCIuLi9pbWcvaXJyX2ljb25fcG9pLnBuZ1wiLCBudWxsLCBudWxsLCBuZXcgZ29vZ2xlLm1hcHMuUG9pbnQoNCwgMjgpLCBuZXcgZ29vZ2xlLm1hcHMuU2l6ZSgyNSwyNSkpO1xudmFyIHJ1bkljb24gPSB7IHBhdGg6IGdvb2dsZS5tYXBzLlN5bWJvbFBhdGguQ0lSQ0xFLCBzY2FsZTogMn07XG5cbmZ1bmN0aW9uIGFkZE1hcmtlcnMoKXtcbiAgICBmb3IgKGkgPSBwb3NpdGlvbnMubGVuZ3RoIC0gMTsgaSA+PSAwOyBpLS0pIHtcbiAgICAgICAgaWYgKGkgPT0gKHBvc2l0aW9ucy5sZW5ndGggLSAxKSkge1xuICAgICAgICAgICAgaWNvblIgPSBzdGFydEljb247XG4gICAgICAgICAgICBsYXRsbmdzLnB1c2gobmV3IGdvb2dsZS5tYXBzLkxhdExuZyhwb3NpdGlvbnNbaV0pKTtcbiAgICAgICAgfSBlbHNlIGlmIChpID09IDApIHtcbiAgICAgICAgICAgIGljb25SID0gYWN0aXZlSWNvbjtcbiAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgIGljb25SID0gcnVuSWNvbjtcbiAgICAgICAgfVxuICAgICAgICBtYXJrZXIgPSBuZXcgZ29vZ2xlLm1hcHMuTWFya2VyKHtcbiAgICAgICAgICAgIHBvc2l0aW9uOiBuZXcgZ29vZ2xlLm1hcHMuTGF0TG5nKHBvc2l0aW9uc1tpXSksXG4gICAgICAgICAgICBpY29uOiBpY29uUixcbiAgICAgICAgICAgIG1hcDogbWFwXG4gICAgICAgIH0pOyAgXG5cbiAgICAgICAgLypnb29nbGUubWFwcy5ldmVudC5hZGRMaXN0ZW5lcihtYXJrZXIsICdjbGljaycsIChmdW5jdGlvbihtYXJrZXIsIGkpIHtcbiAgICAgICAgICAgIHJldHVybiBmdW5jdGlvbigpIHtcbiAgXG4gICAgICAgICAgICAgIHZhciBkaXN0YW5jZSA9IGdvb2dsZS5tYXBzLmdlb21ldHJ5LnNwaGVyaWNhbC5jb21wdXRlRGlzdGFuY2VCZXR3ZWVuKG5ldyBnb29nbGUubWFwcy5MYXRMbmcocG9zaXRpb25zW2ldKSwgbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhwb3NpdGlvbnNbcG9zaXRpb25zLmxlbmd0aCAtIDFdKSk7XG4gICAgICAgICAgICAgIGlmIChpID4gMCkge1xuICAgICAgICAgICAgICAgICAgdmFyIGRpc3RhbmNlMiA9IGdvb2dsZS5tYXBzLmdlb21ldHJ5LnNwaGVyaWNhbC5jb21wdXRlRGlzdGFuY2VCZXR3ZWVuKG5ldyBnb29nbGUubWFwcy5MYXRMbmcocG9zaXRpb25zW2ldKSwgbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhwb3NpdGlvbnNbaS0xXSkpO1xuICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgIHZhciB0aGVuID0gcG9zaXRpb25zW2NvdW50LTFdWzRdO1xuICAgICAgICAgICAgICB2YXIgbm93ID0gcG9zaXRpb25zW2ldWzRdO1xuICAgICAgICAgICAgICB2YXIgZGlmZiA9IG1vbWVudC5kdXJhdGlvbihtb21lbnQobm93KS5kaWZmKG1vbWVudCh0aGVuKSkpO1xuICAgICAgICAgICAgICB2YXIgbXQgPSAoZGlzdGFuY2UgLyAoZGlmZi8xMDAwKSkqMzYwMDtcbiAgXG4gIFxuICAgICAgICAgICAgICB2YXIgYmFjaztcbiAgICAgICAgICAgICAgaWYgKGNvdW50ID4gMCkgYmFjayA9IDA7XG4gICAgICAgICAgICAgIGVsc2UgYmFjayA9IGNvdW50O1xuICBcbiAgICAgICAgICAgICAgdmFyIGRpc3RhbmNlMiA9IGdvb2dsZS5tYXBzLmdlb21ldHJ5LnNwaGVyaWNhbC5jb21wdXRlRGlzdGFuY2VCZXR3ZWVuKG5ldyBnb29nbGUubWFwcy5MYXRMbmcobWFya2VyTGF0TG9uW2ktYmFja11bMV0sIG1hcmtlckxhdExvbltpLWJhY2tdWzJdKSwgbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhtYXJrZXJMYXRMb25bY291bnQtMV1bMV0sIG1hcmtlckxhdExvbltjb3VudC0xXVsyXSkpO1xuICAgICAgICAgICAgICB2YXIgdGhlbjIgPSBtYXJrZXJMYXRMb25bY291bnQtMV1bNF07XG4gICAgICAgICAgICAgIHZhciBub3cyID0gbWFya2VyTGF0TG9uW2ktYmFja11bNF07XG4gICAgICAgICAgICAgIHZhciBkaWZmMiA9IG1vbWVudC5kdXJhdGlvbihtb21lbnQobm93MikuZGlmZihtb21lbnQodGhlbjIpKSk7XG4gICAgICAgICAgICAgIHZhciBtdDIgPSAoZGlzdGFuY2UyIC8gKGRpZmYyLzEwMDApKSozNjAwO1xuICBcbiAgICAgICAgICAgICAgaW5mb3dpbmRvdy5zZXRDb250ZW50KG1hcmtlckxhdExvbltpXVs0XSsnPGJyPicrbWFya2VyTGF0TG9uW2ldWzNdKyc8YnI+RGlzdGFuc2UgPSAnK2Rpc3RhbmNlLnRvRml4ZWQoMSkrJyBtZXRlcnMsICcrbXQudG9GaXhlZCgxKSsnKCcrbXQudG9GaXhlZCgxKSsnKScrJyBtZXRlcnMgcGVyIGhvdXIgJyk7XG4gICAgICAgICAgICAgIFxuICAgICAgICAgICAgICBpbmZvd2luZG93Lm9wZW4obWFwLCBtYXJrZXIpO1xuICAgICAgICAgICAgfVxuICAgICAgICAgIH0pKG1hcmtlciwgaSkpOyovXG4gICAgfVxufVxuXG5mdW5jdGlvbiBpbmZvQ2FsbGJhY2soaW5mb3dpbmRvdykge1xuICAgIHJldHVybiBmdW5jdGlvbigpIHtcbiAgICAgICAgaW5mb3dpbmRvdy5vcGVuKG1hcCk7XG4gICAgfTtcbn1cbiJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./resources/js/map/map.js\n");

/***/ }),

/***/ 1:
/*!***************************************!*\
  !*** multi ./resources/js/map/map.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /var/www/portal/resources/js/map/map.js */"./resources/js/map/map.js");


/***/ })

/******/ });