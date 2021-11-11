/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/highcharts.js":
/*!*********************************!*\
  !*** ./assets/js/highcharts.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_date_to_string_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.date.to-string.js */ "./node_modules/core-js/modules/es.date.to-string.js");
/* harmony import */ var core_js_modules_es_date_to_string_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_date_to_string_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
/* harmony import */ var core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_to_string_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var core_js_modules_es_regexp_to_string_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! core-js/modules/es.regexp.to-string.js */ "./node_modules/core-js/modules/es.regexp.to-string.js");
/* harmony import */ var core_js_modules_es_regexp_to_string_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_regexp_to_string_js__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var jquery_confirm__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! jquery-confirm */ "./node_modules/jquery-confirm/dist/jquery-confirm.min.js");
/* harmony import */ var jquery_confirm__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(jquery_confirm__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var datatables_net__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! datatables.net */ "./node_modules/datatables.net/js/jquery.dataTables.js");
/* harmony import */ var datatables_net__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(datatables_net__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var datatables_net_bs5__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! datatables.net-bs5 */ "./node_modules/datatables.net-bs5/js/dataTables.bootstrap5.js");
/* harmony import */ var datatables_net_bs5__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(datatables_net_bs5__WEBPACK_IMPORTED_MODULE_5__);
/* provided dependency */ var $ = __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js");





var Highcharts = __webpack_require__(/*! highcharts/highcharts */ "./node_modules/highcharts/highcharts.js"); // or require('highcharts/highstock');




window.Highcharts = Highcharts;

function calculateProgressBar(total, target, idP, idTotal, idTarget) {
  var progressBarWidth = total;
  $('#' + idTotal).text(total);
  $('#' + idTarget).text(target);

  if (target !== 0 && total < target) {
    progressBarWidth = total * 100 / target;
  }

  if (target === total) {
    progressBarWidth = 100;
  }

  $('#' + idP).css({
    'width': progressBarWidth + '%',
    'color': 'black',
    'font-weight': 'bold'
  });
  $('#' + idP).attr('aria-valuenow', total);
  $('#' + idP).attr('aria-valuemin', '0');
  $('#' + idP).attr('aria-valuemax', target);
  $('#' + idP).text(Math.round(progressBarWidth) + "%");
}

document.addEventListener('DOMContentLoaded', function () {
  var BASE_URI = '/admin/client/show-chart-goal-clients';
  var URI_SELLER_WEEK = '/seller/visits-week';
  var URI_SELLER_MONTH = '/seller/visits-month';
  var URI_SELLER_OF_MONTH = '/seller/visits-of-the-month';
  var URI_SELLER_QUARTER = '/seller/visits-quarter';
  var URL_LAST_WEEK = '/last-num-week';
  var URI_SELLER_UNVISITED_CLIENT = '/seller/clients-list';
  var d = new Date();
  var year = d.getFullYear();
  $('#years').change(function (e) {
    e.preventDefault();
    console.log(e.target.value);
    postData(BASE_URI, e.target.value, {
      year: e.target.value
    });
  });

  var postData = function postData(url, year, data) {
    $.post(url, data).done(function (data) {
      console.log(data['Series']);
      console.log(data['categories']);
      chart('column', "Clients' goal -" + year + "", data);
    }).fail(function () {
      $.alert("error");
    }).always(function () {//setTimeout(function(){location.reload()}, 2000);
    });
  };

  postData(BASE_URI, year, {
    year: year
  });

  function chart(type, title, data) {
    Highcharts.chart('container', {
      chart: {
        type: type
      },
      title: {
        text: title
      },
      xAxis: {
        categories: data['categories']
      },
      yAxis: {
        min: 0,
        title: {
          text: 'Total fruit consumption'
        },
        stackLabels: {
          enabled: true,
          style: {
            fontWeight: 'bold',
            color: // theme
            Highcharts.defaultOptions.title.style && Highcharts.defaultOptions.title.style.color || 'gray'
          }
        }
      },
      legend: {
        align: 'right',
        x: -30,
        verticalAlign: 'top',
        y: 25,
        floating: true,
        backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
        borderColor: '#CCC',
        borderWidth: 1,
        shadow: false
      },
      tooltip: {
        headerFormat: '<b>{point.x}</b><br/>',
        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
      },
      plotOptions: {
        column: {
          stacking: 'normal',
          dataLabels: {
            enabled: true
          }
        }
      },
      series: data['Series']
    });
  }
  /**
   * THE SELLER DASHBOARD
   */
  // The initialization of both week select and the current Year


  $('#selectedYear').text(year);
  $('#selectedMYear').text(year);
  getTheWeekOfYear(year);
  $('#yearFilter').change(function (e) {
    e.preventDefault();
    $('#selectedYear').text(e.target.value);
    $('#selectedMYear').text(e.target.value);
    postDataChartWeek('container-2', URI_SELLER_WEEK, {
      'year': e.target.value
    });
    postDataChartMonth('container-3', URI_SELLER_MONTH, {
      'year': e.target.value,
      'month': ''
    });
    postDataProgressQuarter(URI_SELLER_QUARTER, {
      'year': e.target.value
    });
    getTheWeekOfYear(e.target.value);
  }); //  fill in the dropdown of the week select

  function getTheWeekOfYear(year) {
    var x = document.getElementById("weekSelect");
    x.options.length = 0;
    $.post(URL_LAST_WEEK, {
      year: year
    }).done(function (data) {
      console.log(data.lastWeek);

      for (var i = 1; i <= data.lastWeek; i++) {
        var option = document.createElement("option");
        option.text = "S " + i;
        option.value = i.toString();

        if (option.value === data.thisWeek) {
          option.selected = true;
        }

        x.add(option);
      }
    }).fail(function () {
      $.alert("error");
    }).always(function () {//setTimeout(function(){location.reload()}, 2000);
    });
  }

  postDataChartWeek('container-2', URI_SELLER_WEEK, {});
  postDataChartMonth('container-3', URI_SELLER_MONTH, {});
  $('#weekSelect').change(function (e) {
    e.preventDefault();
    postDataChartWeek('container-2', URI_SELLER_WEEK, {
      'year': $('#selectedYear').text(),
      'week': e.target.value
    });
  });
  $('#monthSelect').change(function (e) {
    e.preventDefault();
    console.log(e.target.value);
    postDataChartMonth('container-3', URI_SELLER_OF_MONTH, {
      'year': $('#selectedMYear').text(),
      'month': e.target.value
    });
  });
  postDataProgressQuarter(URI_SELLER_QUARTER, {});

  function postDataProgressQuarter(url, data) {
    $.post(url, data).done(function (data) {
      console.log(data);

      var _loop = function _loop(i) {
        console.log(data['sellerNbVisits'][i]['nbVisits']);
        calculateProgressBar(data['sellerNbVisits'][i]['nbVisits'], data['targetNbVisits'][i], 'PQ' + (i + 1), 'STQ' + (i + 1), 'TTQ' + (i + 1)); // Progress bar of clients: the % of visited clients

        calculateProgressBar(data['sellerNbVisits'][i]['nbNonVClients'], data.nbSellerClients, 'PC' + (i + 1), 'CNVQ' + (i + 1), 'TC' + (i + 1));
        $('#cardT' + (i + 1)).click(function () {
          /**/
          $.post(URI_SELLER_UNVISITED_CLIENT, {
            'data': data['sellerNbVisits'][i]['nonVCQuarter']
          }).done(function (response) {
            console.log(response);
            $.dialog({
              title: 'List of unvisited clients',
              content: response,
              animation: 'scale',
              columnClass: 'col-md-8',
              closeAnimation: 'scale',
              type: 'green',
              backgroundDismiss: true
            });
          }).fail(function () {
            $.alert("error");
          }).always(function () {//setTimeout(function(){location.reload()}, 2000);
          });
        });
      };

      for (var i = 0; i < data['targetNbVisits'].length; i++) {
        _loop(i);
      }
    }).fail(function () {
      $.alert("error");
    }).always(function () {//setTimeout(function(){location.reload()}, 2000);
    });
  }

  function postDataChartWeek(container, url, data) {
    $.post(url, data).done(function (data) {
      calculateProgressBar(data['total'], data['nbVisitsTarget'], 'nbSellerVisits', 'totalVisitWeekly', 'totalTarget');
      createTheChart(container, 'column', 'category', 'Total visits per day', 'Visits per day during the week', 'Week n°: ' + data['week'], 'Days', data['data']);
    }).fail(function () {
      $.alert("error");
    }).always(function () {//setTimeout(function(){location.reload()}, 2000);
    });
  }

  function postDataChartMonth(container, url, data) {
    $.post(url, data).done(function (data) {
      calculateProgressBar(data['totalMonth'], data['nbVisitsMonthTarget'], 'nbSellerMonthVisits', 'totalVisitMonth', 'totalMonthTarget');
      createTheChart(container, 'column', 'category', 'Total visits per month', 'Visits per month - ' + $('#selectedMYear').text(), 'Month: ' + data['month'], 'Days', data['dataMonth']);
    }).fail(function () {
      $.alert("error");
    }).always(function () {//setTimeout(function(){location.reload()}, 2000);
    });
  }

  function createTheChart($container, type, typeX, titleY, title, subTitle, seriesName, data) {
    // Create the chart
    Highcharts.chart($container, {
      chart: {
        type: type
      },
      title: {
        text: title
      },
      subtitle: {
        text: subTitle
      },
      accessibility: {
        announceNewData: {
          enabled: true
        }
      },
      xAxis: {
        type: typeX
      },
      yAxis: {
        title: {
          text: titleY
        }
      },
      legend: {
        enabled: false
      },
      plotOptions: {
        series: {
          borderWidth: 0,
          dataLabels: {
            enabled: true,
            format: '{point.y}'
          }
        }
      },
      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
      },
      series: [{
        name: seriesName,
        colorByPoint: true,
        data: data
      }]
    });
  }
});

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					result = fn();
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"highcharts": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			for(moduleId in moreModules) {
/******/ 				if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 					__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 				}
/******/ 			}
/******/ 			if(runtime) var result = runtime(__webpack_require__);
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkIds[i]] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["vendors-node_modules_core-js_modules_es_object_to-string_js-node_modules_jquery-confirm_dist_-759dbb","vendors-node_modules_core-js_internals_regexp-flags_js-node_modules_datatables_net-bs5_js_dat-8481b4","vendors-node_modules_core-js_modules_es_date_to-string_js-node_modules_core-js_modules_es_reg-74220a"], () => (__webpack_require__("./assets/js/highcharts.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvaGlnaGNoYXJ0cy5qcyIsIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vL3dlYnBhY2svcnVudGltZS9jaHVuayBsb2FkZWQiLCJ3ZWJwYWNrOi8vL3dlYnBhY2svcnVudGltZS9jb21wYXQgZ2V0IGRlZmF1bHQgZXhwb3J0Iiwid2VicGFjazovLy93ZWJwYWNrL3J1bnRpbWUvZGVmaW5lIHByb3BlcnR5IGdldHRlcnMiLCJ3ZWJwYWNrOi8vL3dlYnBhY2svcnVudGltZS9nbG9iYWwiLCJ3ZWJwYWNrOi8vL3dlYnBhY2svcnVudGltZS9oYXNPd25Qcm9wZXJ0eSBzaG9ydGhhbmQiLCJ3ZWJwYWNrOi8vL3dlYnBhY2svcnVudGltZS9tYWtlIG5hbWVzcGFjZSBvYmplY3QiLCJ3ZWJwYWNrOi8vL3dlYnBhY2svcnVudGltZS9qc29ucCBjaHVuayBsb2FkaW5nIiwid2VicGFjazovLy93ZWJwYWNrL3N0YXJ0dXAiXSwibmFtZXMiOlsiSGlnaGNoYXJ0cyIsInJlcXVpcmUiLCJ3aW5kb3ciLCJjYWxjdWxhdGVQcm9ncmVzc0JhciIsInRvdGFsIiwidGFyZ2V0IiwiaWRQIiwiaWRUb3RhbCIsImlkVGFyZ2V0IiwicHJvZ3Jlc3NCYXJXaWR0aCIsIiQiLCJ0ZXh0IiwiY3NzIiwiYXR0ciIsIk1hdGgiLCJyb3VuZCIsImRvY3VtZW50IiwiYWRkRXZlbnRMaXN0ZW5lciIsIkJBU0VfVVJJIiwiVVJJX1NFTExFUl9XRUVLIiwiVVJJX1NFTExFUl9NT05USCIsIlVSSV9TRUxMRVJfT0ZfTU9OVEgiLCJVUklfU0VMTEVSX1FVQVJURVIiLCJVUkxfTEFTVF9XRUVLIiwiVVJJX1NFTExFUl9VTlZJU0lURURfQ0xJRU5UIiwiZCIsIkRhdGUiLCJ5ZWFyIiwiZ2V0RnVsbFllYXIiLCJjaGFuZ2UiLCJlIiwicHJldmVudERlZmF1bHQiLCJjb25zb2xlIiwibG9nIiwidmFsdWUiLCJwb3N0RGF0YSIsInVybCIsImRhdGEiLCJwb3N0IiwiZG9uZSIsImNoYXJ0IiwiZmFpbCIsImFsZXJ0IiwiYWx3YXlzIiwidHlwZSIsInRpdGxlIiwieEF4aXMiLCJjYXRlZ29yaWVzIiwieUF4aXMiLCJtaW4iLCJzdGFja0xhYmVscyIsImVuYWJsZWQiLCJzdHlsZSIsImZvbnRXZWlnaHQiLCJjb2xvciIsImRlZmF1bHRPcHRpb25zIiwibGVnZW5kIiwiYWxpZ24iLCJ4IiwidmVydGljYWxBbGlnbiIsInkiLCJmbG9hdGluZyIsImJhY2tncm91bmRDb2xvciIsImJvcmRlckNvbG9yIiwiYm9yZGVyV2lkdGgiLCJzaGFkb3ciLCJ0b29sdGlwIiwiaGVhZGVyRm9ybWF0IiwicG9pbnRGb3JtYXQiLCJwbG90T3B0aW9ucyIsImNvbHVtbiIsInN0YWNraW5nIiwiZGF0YUxhYmVscyIsInNlcmllcyIsImdldFRoZVdlZWtPZlllYXIiLCJwb3N0RGF0YUNoYXJ0V2VlayIsInBvc3REYXRhQ2hhcnRNb250aCIsInBvc3REYXRhUHJvZ3Jlc3NRdWFydGVyIiwiZ2V0RWxlbWVudEJ5SWQiLCJvcHRpb25zIiwibGVuZ3RoIiwibGFzdFdlZWsiLCJpIiwib3B0aW9uIiwiY3JlYXRlRWxlbWVudCIsInRvU3RyaW5nIiwidGhpc1dlZWsiLCJzZWxlY3RlZCIsImFkZCIsIm5iU2VsbGVyQ2xpZW50cyIsImNsaWNrIiwicmVzcG9uc2UiLCJkaWFsb2ciLCJjb250ZW50IiwiYW5pbWF0aW9uIiwiY29sdW1uQ2xhc3MiLCJjbG9zZUFuaW1hdGlvbiIsImJhY2tncm91bmREaXNtaXNzIiwiY29udGFpbmVyIiwiY3JlYXRlVGhlQ2hhcnQiLCIkY29udGFpbmVyIiwidHlwZVgiLCJ0aXRsZVkiLCJzdWJUaXRsZSIsInNlcmllc05hbWUiLCJzdWJ0aXRsZSIsImFjY2Vzc2liaWxpdHkiLCJhbm5vdW5jZU5ld0RhdGEiLCJmb3JtYXQiLCJuYW1lIiwiY29sb3JCeVBvaW50Il0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFBQTs7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QUNBO1VBQ0E7VUFDQTtVQUNBO1VBQ0E7VUFDQTs7VUFFQTtVQUNBOztVQUVBO1VBQ0E7VUFDQTs7VUFFQTtVQUNBOzs7OztXQ3pCQTtXQUNBO1dBQ0E7V0FDQTtXQUNBLDhCQUE4Qix3Q0FBd0M7V0FDdEU7V0FDQTtXQUNBO1dBQ0E7V0FDQSxnQkFBZ0IscUJBQXFCO1dBQ3JDO1dBQ0E7V0FDQSxpQkFBaUIscUJBQXFCO1dBQ3RDO1dBQ0E7V0FDQSxJQUFJO1dBQ0o7V0FDQTtXQUNBO1dBQ0E7V0FDQTtXQUNBO1dBQ0E7V0FDQTtXQUNBO1dBQ0E7V0FDQSxFOzs7OztXQzFCQTtXQUNBO1dBQ0E7V0FDQTtXQUNBO1dBQ0EsZ0NBQWdDLFlBQVk7V0FDNUM7V0FDQSxFOzs7OztXQ1BBO1dBQ0E7V0FDQTtXQUNBO1dBQ0Esd0NBQXdDLHlDQUF5QztXQUNqRjtXQUNBO1dBQ0EsRTs7Ozs7V0NQQTtXQUNBO1dBQ0E7V0FDQTtXQUNBLEVBQUU7V0FDRjtXQUNBO1dBQ0EsQ0FBQyxJOzs7OztXQ1BELHdGOzs7OztXQ0FBO1dBQ0E7V0FDQTtXQUNBLHNEQUFzRCxrQkFBa0I7V0FDeEU7V0FDQSwrQ0FBK0MsY0FBYztXQUM3RCxFOzs7OztXQ05BOztXQUVBO1dBQ0E7V0FDQTtXQUNBO1dBQ0E7V0FDQTs7V0FFQTs7V0FFQTs7V0FFQTs7V0FFQTs7V0FFQTs7V0FFQTs7V0FFQTtXQUNBO1dBQ0E7V0FDQTtXQUNBO1dBQ0E7V0FDQTtXQUNBO1dBQ0E7V0FDQTtXQUNBO1dBQ0E7V0FDQTtXQUNBLE1BQU0sb0JBQW9CO1dBQzFCO1dBQ0E7V0FDQTtXQUNBO1dBQ0E7V0FDQTtXQUNBO1dBQ0E7O1dBRUE7V0FDQTtXQUNBLDRHOzs7OztVQzlDQTtVQUNBO1VBQ0E7VUFDQTtVQUNBIiwiZmlsZSI6ImhpZ2hjaGFydHMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgJ2pxdWVyeS1jb25maXJtJ1xuY29uc3QgSGlnaGNoYXJ0cyA9IHJlcXVpcmUoJ2hpZ2hjaGFydHMvaGlnaGNoYXJ0cycpOyAgLy8gb3IgcmVxdWlyZSgnaGlnaGNoYXJ0cy9oaWdoc3RvY2snKTtcbmltcG9ydCAnZGF0YXRhYmxlcy5uZXQnXG5pbXBvcnQgJ2RhdGF0YWJsZXMubmV0LWJzNSdcbndpbmRvdy5IaWdoY2hhcnRzID0gSGlnaGNoYXJ0cztcblxuZnVuY3Rpb24gY2FsY3VsYXRlUHJvZ3Jlc3NCYXIodG90YWwsIHRhcmdldCwgaWRQLCBpZFRvdGFsLCBpZFRhcmdldCkge1xuICAgIGxldCBwcm9ncmVzc0JhcldpZHRoID0gdG90YWw7XG4gICAgJCgnIycraWRUb3RhbCkudGV4dCh0b3RhbClcbiAgICAkKCcjJytpZFRhcmdldCkudGV4dCh0YXJnZXQpXG5cbiAgICBpZih0YXJnZXQgIT09IDAgJiYgdG90YWwgPCB0YXJnZXQpe1xuICAgICAgICBwcm9ncmVzc0JhcldpZHRoID0gKHRvdGFsKjEwMCkvdGFyZ2V0XG4gICAgfVxuICAgIGlmKHRhcmdldCA9PT0gdG90YWwgKXtcbiAgICAgICAgcHJvZ3Jlc3NCYXJXaWR0aCA9IDEwMFxuICAgIH1cblxuICAgICQoJyMnK2lkUCkuY3NzKHsnd2lkdGgnOiBwcm9ncmVzc0JhcldpZHRoKyclJywgJ2NvbG9yJzonYmxhY2snLCAnZm9udC13ZWlnaHQnOiAnYm9sZCd9KVxuICAgICQoJyMnK2lkUCkuYXR0cignYXJpYS12YWx1ZW5vdycsIHRvdGFsKVxuICAgICQoJyMnK2lkUCkuYXR0cignYXJpYS12YWx1ZW1pbicsICcwJylcbiAgICAkKCcjJytpZFApLmF0dHIoJ2FyaWEtdmFsdWVtYXgnLCB0YXJnZXQpXG4gICAgJCgnIycraWRQKS50ZXh0KE1hdGgucm91bmQocHJvZ3Jlc3NCYXJXaWR0aCkrXCIlXCIpXG59XG5cbmRvY3VtZW50LmFkZEV2ZW50TGlzdGVuZXIoJ0RPTUNvbnRlbnRMb2FkZWQnLCBmdW5jdGlvbiAoKSB7XG4gICAgbGV0IEJBU0VfVVJJID0gJy9hZG1pbi9jbGllbnQvc2hvdy1jaGFydC1nb2FsLWNsaWVudHMnO1xuICAgIGxldCBVUklfU0VMTEVSX1dFRUsgPSAnL3NlbGxlci92aXNpdHMtd2VlaydcbiAgICBsZXQgVVJJX1NFTExFUl9NT05USCA9ICcvc2VsbGVyL3Zpc2l0cy1tb250aCdcbiAgICBsZXQgVVJJX1NFTExFUl9PRl9NT05USCA9ICcvc2VsbGVyL3Zpc2l0cy1vZi10aGUtbW9udGgnXG4gICAgbGV0IFVSSV9TRUxMRVJfUVVBUlRFUiA9ICcvc2VsbGVyL3Zpc2l0cy1xdWFydGVyJ1xuICAgIGxldCBVUkxfTEFTVF9XRUVLID0gJy9sYXN0LW51bS13ZWVrJ1xuICAgIGxldCBVUklfU0VMTEVSX1VOVklTSVRFRF9DTElFTlQgPSAnL3NlbGxlci9jbGllbnRzLWxpc3QnO1xuICAgIGNvbnN0IGQgPSBuZXcgRGF0ZSgpO1xuICAgIGxldCB5ZWFyID0gZC5nZXRGdWxsWWVhcigpO1xuICAgICQoJyN5ZWFycycpLmNoYW5nZShlID0+IHtcbiAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpXG4gICAgICAgIGNvbnNvbGUubG9nKGUudGFyZ2V0LnZhbHVlKVxuICAgICAgICBwb3N0RGF0YShCQVNFX1VSSSxlLnRhcmdldC52YWx1ZSx7eWVhcjogZS50YXJnZXQudmFsdWV9KVxuICAgIH0pXG5cblxuICAgIGxldCBwb3N0RGF0YSA9ICh1cmwseWVhciAsZGF0YSApID0+IHtcbiAgICAgICAgJC5wb3N0KHVybCwgZGF0YSlcbiAgICAgICAgICAgIC5kb25lKGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YVsnU2VyaWVzJ10pXG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YVsnY2F0ZWdvcmllcyddKVxuICAgICAgICAgICAgICAgIGNoYXJ0KCdjb2x1bW4nLFwiQ2xpZW50cycgZ29hbCAtXCIreWVhcitcIlwiLCBkYXRhKVxuICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIC5mYWlsKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICQuYWxlcnQoIFwiZXJyb3JcIiApO1xuICAgICAgICAgICAgfSlcbiAgICAgICAgICAgIC5hbHdheXMoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgLy9zZXRUaW1lb3V0KGZ1bmN0aW9uKCl7bG9jYXRpb24ucmVsb2FkKCl9LCAyMDAwKTtcbiAgICAgICAgICAgIH0pO1xuICAgIH1cbiAgICBwb3N0RGF0YShCQVNFX1VSSSwgeWVhcix7eWVhcjp5ZWFyIH0pXG5cbiAgICBmdW5jdGlvbiBjaGFydCh0eXBlLCB0aXRsZSwgZGF0YSl7XG4gICAgICAgIEhpZ2hjaGFydHMuY2hhcnQoJ2NvbnRhaW5lcicsIHtcbiAgICAgICAgICAgIGNoYXJ0OiB7XG4gICAgICAgICAgICAgICAgdHlwZTogdHlwZVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHRpdGxlOiB7XG4gICAgICAgICAgICAgICAgdGV4dDogdGl0bGVcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICB4QXhpczoge1xuICAgICAgICAgICAgICAgIGNhdGVnb3JpZXM6IGRhdGFbJ2NhdGVnb3JpZXMnXVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHlBeGlzOiB7XG4gICAgICAgICAgICAgICAgbWluOiAwLFxuICAgICAgICAgICAgICAgIHRpdGxlOiB7XG4gICAgICAgICAgICAgICAgICAgIHRleHQ6ICdUb3RhbCBmcnVpdCBjb25zdW1wdGlvbidcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIHN0YWNrTGFiZWxzOiB7XG4gICAgICAgICAgICAgICAgICAgIGVuYWJsZWQ6IHRydWUsXG4gICAgICAgICAgICAgICAgICAgIHN0eWxlOiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBmb250V2VpZ2h0OiAnYm9sZCcsXG4gICAgICAgICAgICAgICAgICAgICAgICBjb2xvcjogKCAvLyB0aGVtZVxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIEhpZ2hjaGFydHMuZGVmYXVsdE9wdGlvbnMudGl0bGUuc3R5bGUgJiZcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBIaWdoY2hhcnRzLmRlZmF1bHRPcHRpb25zLnRpdGxlLnN0eWxlLmNvbG9yXG4gICAgICAgICAgICAgICAgICAgICAgICApIHx8ICdncmF5J1xuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIGxlZ2VuZDoge1xuICAgICAgICAgICAgICAgIGFsaWduOiAncmlnaHQnLFxuICAgICAgICAgICAgICAgIHg6IC0zMCxcbiAgICAgICAgICAgICAgICB2ZXJ0aWNhbEFsaWduOiAndG9wJyxcbiAgICAgICAgICAgICAgICB5OiAyNSxcbiAgICAgICAgICAgICAgICBmbG9hdGluZzogdHJ1ZSxcbiAgICAgICAgICAgICAgICBiYWNrZ3JvdW5kQ29sb3I6XG4gICAgICAgICAgICAgICAgICAgIEhpZ2hjaGFydHMuZGVmYXVsdE9wdGlvbnMubGVnZW5kLmJhY2tncm91bmRDb2xvciB8fCAnd2hpdGUnLFxuICAgICAgICAgICAgICAgIGJvcmRlckNvbG9yOiAnI0NDQycsXG4gICAgICAgICAgICAgICAgYm9yZGVyV2lkdGg6IDEsXG4gICAgICAgICAgICAgICAgc2hhZG93OiBmYWxzZVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHRvb2x0aXA6IHtcbiAgICAgICAgICAgICAgICBoZWFkZXJGb3JtYXQ6ICc8Yj57cG9pbnQueH08L2I+PGJyLz4nLFxuICAgICAgICAgICAgICAgIHBvaW50Rm9ybWF0OiAne3Nlcmllcy5uYW1lfToge3BvaW50Lnl9PGJyLz5Ub3RhbDoge3BvaW50LnN0YWNrVG90YWx9J1xuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHBsb3RPcHRpb25zOiB7XG4gICAgICAgICAgICAgICAgY29sdW1uOiB7XG4gICAgICAgICAgICAgICAgICAgIHN0YWNraW5nOiAnbm9ybWFsJyxcbiAgICAgICAgICAgICAgICAgICAgZGF0YUxhYmVsczoge1xuICAgICAgICAgICAgICAgICAgICAgICAgZW5hYmxlZDogdHJ1ZVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHNlcmllczogZGF0YVsnU2VyaWVzJ11cbiAgICAgICAgfSk7XG4gICAgfVxuXG4gICAgLyoqXG4gICAgICogVEhFIFNFTExFUiBEQVNIQk9BUkRcbiAgICAgKi9cbiAgICAvLyBUaGUgaW5pdGlhbGl6YXRpb24gb2YgYm90aCB3ZWVrIHNlbGVjdCBhbmQgdGhlIGN1cnJlbnQgWWVhclxuICAgICQoJyNzZWxlY3RlZFllYXInKS50ZXh0KHllYXIpXG4gICAgJCgnI3NlbGVjdGVkTVllYXInKS50ZXh0KHllYXIpXG4gICAgZ2V0VGhlV2Vla09mWWVhcih5ZWFyKVxuXG4gICAgJCgnI3llYXJGaWx0ZXInKS5jaGFuZ2UoZSA9PiB7XG4gICAgICAgIGUucHJldmVudERlZmF1bHQoKVxuICAgICAgICAkKCcjc2VsZWN0ZWRZZWFyJykudGV4dChlLnRhcmdldC52YWx1ZSlcbiAgICAgICAgJCgnI3NlbGVjdGVkTVllYXInKS50ZXh0KGUudGFyZ2V0LnZhbHVlKVxuICAgICAgICBwb3N0RGF0YUNoYXJ0V2VlaygnY29udGFpbmVyLTInLFVSSV9TRUxMRVJfV0VFSywgeyd5ZWFyJzplLnRhcmdldC52YWx1ZX0pXG4gICAgICAgIHBvc3REYXRhQ2hhcnRNb250aCgnY29udGFpbmVyLTMnLFVSSV9TRUxMRVJfTU9OVEgsIHsneWVhcic6ZS50YXJnZXQudmFsdWUsICdtb250aCc6Jyd9KVxuICAgICAgICBwb3N0RGF0YVByb2dyZXNzUXVhcnRlcihVUklfU0VMTEVSX1FVQVJURVIsIHsneWVhcic6IGUudGFyZ2V0LnZhbHVlfSlcbiAgICAgICAgZ2V0VGhlV2Vla09mWWVhcihlLnRhcmdldC52YWx1ZSlcblxuXG4gICAgfSk7XG5cbiAgICAvLyAgZmlsbCBpbiB0aGUgZHJvcGRvd24gb2YgdGhlIHdlZWsgc2VsZWN0XG4gICAgZnVuY3Rpb24gZ2V0VGhlV2Vla09mWWVhcih5ZWFyKSB7XG4gICAgICAgIGxldCB4ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJ3ZWVrU2VsZWN0XCIpO1xuICAgICAgICB4Lm9wdGlvbnMubGVuZ3RoID0gMFxuICAgICAgICAkLnBvc3QoVVJMX0xBU1RfV0VFSywge3llYXI6IHllYXJ9KVxuICAgICAgICAgICAgLmRvbmUoZnVuY3Rpb24gKGRhdGEpIHtcbiAgICAgICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhLmxhc3RXZWVrKVxuICAgICAgICAgICAgICAgIGZvciAobGV0IGkgPSAxOyBpIDw9IGRhdGEubGFzdFdlZWs7IGkrKykge1xuICAgICAgICAgICAgICAgICAgICBsZXQgb3B0aW9uID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudChcIm9wdGlvblwiKTtcbiAgICAgICAgICAgICAgICAgICAgb3B0aW9uLnRleHQgPSBcIlMgXCIraTtcbiAgICAgICAgICAgICAgICAgICAgb3B0aW9uLnZhbHVlID0gaS50b1N0cmluZygpXG4gICAgICAgICAgICAgICAgICAgIGlmKG9wdGlvbi52YWx1ZSA9PT0gZGF0YS50aGlzV2VlayApe1xuICAgICAgICAgICAgICAgICAgICAgICAgb3B0aW9uLnNlbGVjdGVkID0gdHJ1ZTtcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB4LmFkZChvcHRpb24pO1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAuZmFpbChmdW5jdGlvbigpIHtcbiAgICAgICAgICAgICAgICAkLmFsZXJ0KCBcImVycm9yXCIgKTtcbiAgICAgICAgICAgIH0pXG4gICAgICAgICAgICAuYWx3YXlzKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgIC8vc2V0VGltZW91dChmdW5jdGlvbigpe2xvY2F0aW9uLnJlbG9hZCgpfSwgMjAwMCk7XG4gICAgICAgICAgICB9KTtcbiAgICB9XG4gICAgcG9zdERhdGFDaGFydFdlZWsoJ2NvbnRhaW5lci0yJyxVUklfU0VMTEVSX1dFRUssIHt9KVxuICAgIHBvc3REYXRhQ2hhcnRNb250aCgnY29udGFpbmVyLTMnLFVSSV9TRUxMRVJfTU9OVEgsIHt9KVxuICAgICQoJyN3ZWVrU2VsZWN0JykuY2hhbmdlKGUgPT4ge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KClcbiAgICAgICBwb3N0RGF0YUNoYXJ0V2VlaygnY29udGFpbmVyLTInLFVSSV9TRUxMRVJfV0VFSywgeyd5ZWFyJzokKCcjc2VsZWN0ZWRZZWFyJykudGV4dCgpICwgJ3dlZWsnOiBlLnRhcmdldC52YWx1ZX0pXG4gICAgfSk7XG4gICAgJCgnI21vbnRoU2VsZWN0JykuY2hhbmdlKGUgPT4ge1xuICAgICAgICBlLnByZXZlbnREZWZhdWx0KClcbiAgICAgICAgY29uc29sZS5sb2coZS50YXJnZXQudmFsdWUpXG4gICAgICAgIHBvc3REYXRhQ2hhcnRNb250aCgnY29udGFpbmVyLTMnLFVSSV9TRUxMRVJfT0ZfTU9OVEgsIHsneWVhcic6JCgnI3NlbGVjdGVkTVllYXInKS50ZXh0KCkgLCAnbW9udGgnOiBlLnRhcmdldC52YWx1ZX0pXG4gICAgfSk7XG4gICAgcG9zdERhdGFQcm9ncmVzc1F1YXJ0ZXIoVVJJX1NFTExFUl9RVUFSVEVSLCB7fSlcbmZ1bmN0aW9uIHBvc3REYXRhUHJvZ3Jlc3NRdWFydGVyKHVybCwgZGF0YSkge1xuICAgICQucG9zdCh1cmwsIGRhdGEpXG4gICAgICAgIC5kb25lKGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICBjb25zb2xlLmxvZyhkYXRhKVxuICAgICAgICAgICAgZm9yIChsZXQgaSA9IDA7IGkgPCBkYXRhWyd0YXJnZXROYlZpc2l0cyddLmxlbmd0aDsgaSsrKSB7XG4gICAgICAgICAgICAgICAgY29uc29sZS5sb2coZGF0YVsnc2VsbGVyTmJWaXNpdHMnXVtpXVsnbmJWaXNpdHMnXSlcbiAgICAgICAgICAgICAgICBjYWxjdWxhdGVQcm9ncmVzc0JhcihkYXRhWydzZWxsZXJOYlZpc2l0cyddW2ldWyduYlZpc2l0cyddLCBkYXRhWyd0YXJnZXROYlZpc2l0cyddW2ldLCdQUScrKGkrMSksJ1NUUScrKGkrMSksICdUVFEnKyhpKzEpKVxuICAgICAgICAgICAgICAgIC8vIFByb2dyZXNzIGJhciBvZiBjbGllbnRzOiB0aGUgJSBvZiB2aXNpdGVkIGNsaWVudHNcbiAgICAgICAgICAgICAgICBjYWxjdWxhdGVQcm9ncmVzc0JhcihkYXRhWydzZWxsZXJOYlZpc2l0cyddW2ldWyduYk5vblZDbGllbnRzJ10sIGRhdGEubmJTZWxsZXJDbGllbnRzLCAnUEMnKyhpKzEpLCAnQ05WUScrKGkrMSksICdUQycrKGkrMSkpXG5cbiAgICAgICAgICAgICAgICAkKCcjY2FyZFQnKyhpKzEpKS5jbGljayhmdW5jdGlvbiAoKXtcblxuICAgICAgICAgICAgICAgICAgICAvKiovXG5cbiAgICAgICAgICAgICAgICAgICAgJC5wb3N0KFVSSV9TRUxMRVJfVU5WSVNJVEVEX0NMSUVOVCwgeydkYXRhJzogZGF0YVsnc2VsbGVyTmJWaXNpdHMnXVtpXVsnbm9uVkNRdWFydGVyJ10gfSlcbiAgICAgICAgICAgICAgICAgICAgICAgIC5kb25lKGZ1bmN0aW9uIChyZXNwb25zZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGNvbnNvbGUubG9nKHJlc3BvbnNlKVxuXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgJC5kaWFsb2coe1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0aXRsZTogJ0xpc3Qgb2YgdW52aXNpdGVkIGNsaWVudHMnLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb250ZW50OiByZXNwb25zZSxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgYW5pbWF0aW9uOiAnc2NhbGUnLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBjb2x1bW5DbGFzczogJ2NvbC1tZC04JyxcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgY2xvc2VBbmltYXRpb246ICdzY2FsZScsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHR5cGU6ICdncmVlbicsXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIGJhY2tncm91bmREaXNtaXNzOiB0cnVlLFxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICAgICAgICAgIC5mYWlsKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICQuYWxlcnQoIFwiZXJyb3JcIiApO1xuICAgICAgICAgICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICAgICAgICAgIC5hbHdheXMoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgLy9zZXRUaW1lb3V0KGZ1bmN0aW9uKCl7bG9jYXRpb24ucmVsb2FkKCl9LCAyMDAwKTtcbiAgICAgICAgICAgICAgICAgICAgICAgIH0pO1xuXG4gICAgICAgICAgICAgICAgfSlcblxuICAgICAgICAgICAgfVxuXG5cbiAgICAgICAgfSlcbiAgICAgICAgLmZhaWwoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAkLmFsZXJ0KCBcImVycm9yXCIgKTtcbiAgICAgICAgfSlcbiAgICAgICAgLmFsd2F5cyhmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIC8vc2V0VGltZW91dChmdW5jdGlvbigpe2xvY2F0aW9uLnJlbG9hZCgpfSwgMjAwMCk7XG4gICAgICAgIH0pO1xufVxuZnVuY3Rpb24gcG9zdERhdGFDaGFydFdlZWsoY29udGFpbmVyLHVybCwgZGF0YSkge1xuICAgICQucG9zdCh1cmwsIGRhdGEpXG4gICAgICAgIC5kb25lKGZ1bmN0aW9uIChkYXRhKSB7XG4gICAgICAgICAgICBjYWxjdWxhdGVQcm9ncmVzc0JhcihkYXRhWyd0b3RhbCddLCBkYXRhWyduYlZpc2l0c1RhcmdldCddLCAnbmJTZWxsZXJWaXNpdHMnLCAndG90YWxWaXNpdFdlZWtseScsICd0b3RhbFRhcmdldCcpXG4gICAgICAgICAgICBjcmVhdGVUaGVDaGFydChjb250YWluZXIsXG4gICAgICAgICAgICAgICAgJ2NvbHVtbicsXG4gICAgICAgICAgICAgICAgJ2NhdGVnb3J5JyxcbiAgICAgICAgICAgICAgICAnVG90YWwgdmlzaXRzIHBlciBkYXknLFxuICAgICAgICAgICAgICAgICdWaXNpdHMgcGVyIGRheSBkdXJpbmcgdGhlIHdlZWsnLFxuICAgICAgICAgICAgICAgICdXZWVrIG7CsDogJytkYXRhWyd3ZWVrJ10sXG4gICAgICAgICAgICAgICAgJ0RheXMnLFxuICAgICAgICAgICAgICAgIGRhdGFbJ2RhdGEnXSlcbiAgICAgICAgfSlcbiAgICAgICAgLmZhaWwoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAkLmFsZXJ0KCBcImVycm9yXCIgKTtcbiAgICAgICAgfSlcbiAgICAgICAgLmFsd2F5cyhmdW5jdGlvbigpIHtcbiAgICAgICAgICAgIC8vc2V0VGltZW91dChmdW5jdGlvbigpe2xvY2F0aW9uLnJlbG9hZCgpfSwgMjAwMCk7XG4gICAgICAgIH0pO1xufVxuZnVuY3Rpb24gcG9zdERhdGFDaGFydE1vbnRoKGNvbnRhaW5lcix1cmwsIGRhdGEpIHtcbiAgICAkLnBvc3QodXJsLCBkYXRhKVxuICAgICAgICAuZG9uZShmdW5jdGlvbiAoZGF0YSkge1xuICAgICAgICAgICBjYWxjdWxhdGVQcm9ncmVzc0JhcihkYXRhWyd0b3RhbE1vbnRoJ10sIGRhdGFbJ25iVmlzaXRzTW9udGhUYXJnZXQnXSwgJ25iU2VsbGVyTW9udGhWaXNpdHMnLCAndG90YWxWaXNpdE1vbnRoJywgJ3RvdGFsTW9udGhUYXJnZXQnKVxuICAgICAgICAgICAgY3JlYXRlVGhlQ2hhcnQoY29udGFpbmVyLFxuICAgICAgICAgICAgICAgICdjb2x1bW4nLFxuICAgICAgICAgICAgICAgICdjYXRlZ29yeScsXG4gICAgICAgICAgICAgICAgJ1RvdGFsIHZpc2l0cyBwZXIgbW9udGgnLFxuICAgICAgICAgICAgICAgICdWaXNpdHMgcGVyIG1vbnRoIC0gJyskKCcjc2VsZWN0ZWRNWWVhcicpLnRleHQoKSxcbiAgICAgICAgICAgICAgICAnTW9udGg6ICcrZGF0YVsnbW9udGgnXSxcbiAgICAgICAgICAgICAgICAnRGF5cycsXG4gICAgICAgICAgICAgICAgZGF0YVsnZGF0YU1vbnRoJ10pXG4gICAgICAgIH0pXG4gICAgICAgIC5mYWlsKGZ1bmN0aW9uKCkge1xuICAgICAgICAgICAgJC5hbGVydCggXCJlcnJvclwiICk7XG4gICAgICAgIH0pXG4gICAgICAgIC5hbHdheXMoZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAvL3NldFRpbWVvdXQoZnVuY3Rpb24oKXtsb2NhdGlvbi5yZWxvYWQoKX0sIDIwMDApO1xuICAgICAgICB9KTtcbn1cbmZ1bmN0aW9uIGNyZWF0ZVRoZUNoYXJ0KCRjb250YWluZXIsIHR5cGUsIHR5cGVYLCB0aXRsZVksdGl0bGUsIHN1YlRpdGxlLCBzZXJpZXNOYW1lLCBkYXRhKSB7XG4gICAgLy8gQ3JlYXRlIHRoZSBjaGFydFxuICAgIEhpZ2hjaGFydHMuY2hhcnQoJGNvbnRhaW5lciwge1xuICAgICAgICBjaGFydDoge1xuICAgICAgICAgICAgdHlwZTogdHlwZVxuICAgICAgICB9LFxuICAgICAgICB0aXRsZToge1xuICAgICAgICAgICAgdGV4dDogdGl0bGVcbiAgICAgICAgfSxcbiAgICAgICAgc3VidGl0bGU6IHtcbiAgICAgICAgICAgIHRleHQ6IHN1YlRpdGxlXG4gICAgICAgIH0sXG4gICAgICAgIGFjY2Vzc2liaWxpdHk6IHtcbiAgICAgICAgICAgIGFubm91bmNlTmV3RGF0YToge1xuICAgICAgICAgICAgICAgIGVuYWJsZWQ6IHRydWVcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcbiAgICAgICAgeEF4aXM6IHtcbiAgICAgICAgICAgIHR5cGU6IHR5cGVYXG4gICAgICAgIH0sXG4gICAgICAgIHlBeGlzOiB7XG4gICAgICAgICAgICB0aXRsZToge1xuICAgICAgICAgICAgICAgIHRleHQ6IHRpdGxlWVxuICAgICAgICAgICAgfVxuXG4gICAgICAgIH0sXG4gICAgICAgIGxlZ2VuZDoge1xuICAgICAgICAgICAgZW5hYmxlZDogZmFsc2VcbiAgICAgICAgfSxcbiAgICAgICAgcGxvdE9wdGlvbnM6IHtcbiAgICAgICAgICAgIHNlcmllczoge1xuICAgICAgICAgICAgICAgIGJvcmRlcldpZHRoOiAwLFxuICAgICAgICAgICAgICAgIGRhdGFMYWJlbHM6IHtcbiAgICAgICAgICAgICAgICAgICAgZW5hYmxlZDogdHJ1ZSxcbiAgICAgICAgICAgICAgICAgICAgZm9ybWF0OiAne3BvaW50Lnl9J1xuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH1cbiAgICAgICAgfSxcblxuICAgICAgICB0b29sdGlwOiB7XG4gICAgICAgICAgICBoZWFkZXJGb3JtYXQ6ICc8c3BhbiBzdHlsZT1cImZvbnQtc2l6ZToxMXB4XCI+e3Nlcmllcy5uYW1lfTwvc3Bhbj48YnI+JyxcbiAgICAgICAgICAgIHBvaW50Rm9ybWF0OiAnPHNwYW4gc3R5bGU9XCJjb2xvcjp7cG9pbnQuY29sb3J9XCI+e3BvaW50Lm5hbWV9PC9zcGFuPjogPGI+e3BvaW50Lnl9PC9iPiBvZiB0b3RhbDxici8+J1xuICAgICAgICB9LFxuXG4gICAgICAgIHNlcmllczogW1xuICAgICAgICAgICAge1xuICAgICAgICAgICAgICAgIG5hbWU6IHNlcmllc05hbWUsXG4gICAgICAgICAgICAgICAgY29sb3JCeVBvaW50OiB0cnVlLFxuICAgICAgICAgICAgICAgIGRhdGE6IGRhdGFcbiAgICAgICAgICAgIH1cbiAgICAgICAgXSxcbiAgICB9KTtcblxuXG59XG5cblxuXG59KTsiLCIvLyBUaGUgbW9kdWxlIGNhY2hlXG52YXIgX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fID0ge307XG5cbi8vIFRoZSByZXF1aXJlIGZ1bmN0aW9uXG5mdW5jdGlvbiBfX3dlYnBhY2tfcmVxdWlyZV9fKG1vZHVsZUlkKSB7XG5cdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuXHR2YXIgY2FjaGVkTW9kdWxlID0gX193ZWJwYWNrX21vZHVsZV9jYWNoZV9fW21vZHVsZUlkXTtcblx0aWYgKGNhY2hlZE1vZHVsZSAhPT0gdW5kZWZpbmVkKSB7XG5cdFx0cmV0dXJuIGNhY2hlZE1vZHVsZS5leHBvcnRzO1xuXHR9XG5cdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG5cdHZhciBtb2R1bGUgPSBfX3dlYnBhY2tfbW9kdWxlX2NhY2hlX19bbW9kdWxlSWRdID0ge1xuXHRcdC8vIG5vIG1vZHVsZS5pZCBuZWVkZWRcblx0XHQvLyBubyBtb2R1bGUubG9hZGVkIG5lZWRlZFxuXHRcdGV4cG9ydHM6IHt9XG5cdH07XG5cblx0Ly8gRXhlY3V0ZSB0aGUgbW9kdWxlIGZ1bmN0aW9uXG5cdF9fd2VicGFja19tb2R1bGVzX19bbW9kdWxlSWRdLmNhbGwobW9kdWxlLmV4cG9ydHMsIG1vZHVsZSwgbW9kdWxlLmV4cG9ydHMsIF9fd2VicGFja19yZXF1aXJlX18pO1xuXG5cdC8vIFJldHVybiB0aGUgZXhwb3J0cyBvZiB0aGUgbW9kdWxlXG5cdHJldHVybiBtb2R1bGUuZXhwb3J0cztcbn1cblxuLy8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbl9fd2VicGFja19yZXF1aXJlX18ubSA9IF9fd2VicGFja19tb2R1bGVzX187XG5cbiIsInZhciBkZWZlcnJlZCA9IFtdO1xuX193ZWJwYWNrX3JlcXVpcmVfXy5PID0gKHJlc3VsdCwgY2h1bmtJZHMsIGZuLCBwcmlvcml0eSkgPT4ge1xuXHRpZihjaHVua0lkcykge1xuXHRcdHByaW9yaXR5ID0gcHJpb3JpdHkgfHwgMDtcblx0XHRmb3IodmFyIGkgPSBkZWZlcnJlZC5sZW5ndGg7IGkgPiAwICYmIGRlZmVycmVkW2kgLSAxXVsyXSA+IHByaW9yaXR5OyBpLS0pIGRlZmVycmVkW2ldID0gZGVmZXJyZWRbaSAtIDFdO1xuXHRcdGRlZmVycmVkW2ldID0gW2NodW5rSWRzLCBmbiwgcHJpb3JpdHldO1xuXHRcdHJldHVybjtcblx0fVxuXHR2YXIgbm90RnVsZmlsbGVkID0gSW5maW5pdHk7XG5cdGZvciAodmFyIGkgPSAwOyBpIDwgZGVmZXJyZWQubGVuZ3RoOyBpKyspIHtcblx0XHR2YXIgW2NodW5rSWRzLCBmbiwgcHJpb3JpdHldID0gZGVmZXJyZWRbaV07XG5cdFx0dmFyIGZ1bGZpbGxlZCA9IHRydWU7XG5cdFx0Zm9yICh2YXIgaiA9IDA7IGogPCBjaHVua0lkcy5sZW5ndGg7IGorKykge1xuXHRcdFx0aWYgKChwcmlvcml0eSAmIDEgPT09IDAgfHwgbm90RnVsZmlsbGVkID49IHByaW9yaXR5KSAmJiBPYmplY3Qua2V5cyhfX3dlYnBhY2tfcmVxdWlyZV9fLk8pLmV2ZXJ5KChrZXkpID0+IChfX3dlYnBhY2tfcmVxdWlyZV9fLk9ba2V5XShjaHVua0lkc1tqXSkpKSkge1xuXHRcdFx0XHRjaHVua0lkcy5zcGxpY2Uoai0tLCAxKTtcblx0XHRcdH0gZWxzZSB7XG5cdFx0XHRcdGZ1bGZpbGxlZCA9IGZhbHNlO1xuXHRcdFx0XHRpZihwcmlvcml0eSA8IG5vdEZ1bGZpbGxlZCkgbm90RnVsZmlsbGVkID0gcHJpb3JpdHk7XG5cdFx0XHR9XG5cdFx0fVxuXHRcdGlmKGZ1bGZpbGxlZCkge1xuXHRcdFx0ZGVmZXJyZWQuc3BsaWNlKGktLSwgMSlcblx0XHRcdHJlc3VsdCA9IGZuKCk7XG5cdFx0fVxuXHR9XG5cdHJldHVybiByZXN1bHQ7XG59OyIsIi8vIGdldERlZmF1bHRFeHBvcnQgZnVuY3Rpb24gZm9yIGNvbXBhdGliaWxpdHkgd2l0aCBub24taGFybW9ueSBtb2R1bGVzXG5fX3dlYnBhY2tfcmVxdWlyZV9fLm4gPSAobW9kdWxlKSA9PiB7XG5cdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuXHRcdCgpID0+IChtb2R1bGVbJ2RlZmF1bHQnXSkgOlxuXHRcdCgpID0+IChtb2R1bGUpO1xuXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmQoZ2V0dGVyLCB7IGE6IGdldHRlciB9KTtcblx0cmV0dXJuIGdldHRlcjtcbn07IiwiLy8gZGVmaW5lIGdldHRlciBmdW5jdGlvbnMgZm9yIGhhcm1vbnkgZXhwb3J0c1xuX193ZWJwYWNrX3JlcXVpcmVfXy5kID0gKGV4cG9ydHMsIGRlZmluaXRpb24pID0+IHtcblx0Zm9yKHZhciBrZXkgaW4gZGVmaW5pdGlvbikge1xuXHRcdGlmKF9fd2VicGFja19yZXF1aXJlX18ubyhkZWZpbml0aW9uLCBrZXkpICYmICFfX3dlYnBhY2tfcmVxdWlyZV9fLm8oZXhwb3J0cywga2V5KSkge1xuXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIGtleSwgeyBlbnVtZXJhYmxlOiB0cnVlLCBnZXQ6IGRlZmluaXRpb25ba2V5XSB9KTtcblx0XHR9XG5cdH1cbn07IiwiX193ZWJwYWNrX3JlcXVpcmVfXy5nID0gKGZ1bmN0aW9uKCkge1xuXHRpZiAodHlwZW9mIGdsb2JhbFRoaXMgPT09ICdvYmplY3QnKSByZXR1cm4gZ2xvYmFsVGhpcztcblx0dHJ5IHtcblx0XHRyZXR1cm4gdGhpcyB8fCBuZXcgRnVuY3Rpb24oJ3JldHVybiB0aGlzJykoKTtcblx0fSBjYXRjaCAoZSkge1xuXHRcdGlmICh0eXBlb2Ygd2luZG93ID09PSAnb2JqZWN0JykgcmV0dXJuIHdpbmRvdztcblx0fVxufSkoKTsiLCJfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSAob2JqLCBwcm9wKSA9PiAoT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsKG9iaiwgcHJvcCkpIiwiLy8gZGVmaW5lIF9fZXNNb2R1bGUgb24gZXhwb3J0c1xuX193ZWJwYWNrX3JlcXVpcmVfXy5yID0gKGV4cG9ydHMpID0+IHtcblx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG5cdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG5cdH1cblx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbn07IiwiLy8gbm8gYmFzZVVSSVxuXG4vLyBvYmplY3QgdG8gc3RvcmUgbG9hZGVkIGFuZCBsb2FkaW5nIGNodW5rc1xuLy8gdW5kZWZpbmVkID0gY2h1bmsgbm90IGxvYWRlZCwgbnVsbCA9IGNodW5rIHByZWxvYWRlZC9wcmVmZXRjaGVkXG4vLyBbcmVzb2x2ZSwgcmVqZWN0LCBQcm9taXNlXSA9IGNodW5rIGxvYWRpbmcsIDAgPSBjaHVuayBsb2FkZWRcbnZhciBpbnN0YWxsZWRDaHVua3MgPSB7XG5cdFwiaGlnaGNoYXJ0c1wiOiAwXG59O1xuXG4vLyBubyBjaHVuayBvbiBkZW1hbmQgbG9hZGluZ1xuXG4vLyBubyBwcmVmZXRjaGluZ1xuXG4vLyBubyBwcmVsb2FkZWRcblxuLy8gbm8gSE1SXG5cbi8vIG5vIEhNUiBtYW5pZmVzdFxuXG5fX3dlYnBhY2tfcmVxdWlyZV9fLk8uaiA9IChjaHVua0lkKSA9PiAoaW5zdGFsbGVkQ2h1bmtzW2NodW5rSWRdID09PSAwKTtcblxuLy8gaW5zdGFsbCBhIEpTT05QIGNhbGxiYWNrIGZvciBjaHVuayBsb2FkaW5nXG52YXIgd2VicGFja0pzb25wQ2FsbGJhY2sgPSAocGFyZW50Q2h1bmtMb2FkaW5nRnVuY3Rpb24sIGRhdGEpID0+IHtcblx0dmFyIFtjaHVua0lkcywgbW9yZU1vZHVsZXMsIHJ1bnRpbWVdID0gZGF0YTtcblx0Ly8gYWRkIFwibW9yZU1vZHVsZXNcIiB0byB0aGUgbW9kdWxlcyBvYmplY3QsXG5cdC8vIHRoZW4gZmxhZyBhbGwgXCJjaHVua0lkc1wiIGFzIGxvYWRlZCBhbmQgZmlyZSBjYWxsYmFja1xuXHR2YXIgbW9kdWxlSWQsIGNodW5rSWQsIGkgPSAwO1xuXHRmb3IobW9kdWxlSWQgaW4gbW9yZU1vZHVsZXMpIHtcblx0XHRpZihfX3dlYnBhY2tfcmVxdWlyZV9fLm8obW9yZU1vZHVsZXMsIG1vZHVsZUlkKSkge1xuXHRcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5tW21vZHVsZUlkXSA9IG1vcmVNb2R1bGVzW21vZHVsZUlkXTtcblx0XHR9XG5cdH1cblx0aWYocnVudGltZSkgdmFyIHJlc3VsdCA9IHJ1bnRpbWUoX193ZWJwYWNrX3JlcXVpcmVfXyk7XG5cdGlmKHBhcmVudENodW5rTG9hZGluZ0Z1bmN0aW9uKSBwYXJlbnRDaHVua0xvYWRpbmdGdW5jdGlvbihkYXRhKTtcblx0Zm9yKDtpIDwgY2h1bmtJZHMubGVuZ3RoOyBpKyspIHtcblx0XHRjaHVua0lkID0gY2h1bmtJZHNbaV07XG5cdFx0aWYoX193ZWJwYWNrX3JlcXVpcmVfXy5vKGluc3RhbGxlZENodW5rcywgY2h1bmtJZCkgJiYgaW5zdGFsbGVkQ2h1bmtzW2NodW5rSWRdKSB7XG5cdFx0XHRpbnN0YWxsZWRDaHVua3NbY2h1bmtJZF1bMF0oKTtcblx0XHR9XG5cdFx0aW5zdGFsbGVkQ2h1bmtzW2NodW5rSWRzW2ldXSA9IDA7XG5cdH1cblx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18uTyhyZXN1bHQpO1xufVxuXG52YXIgY2h1bmtMb2FkaW5nR2xvYmFsID0gc2VsZltcIndlYnBhY2tDaHVua1wiXSA9IHNlbGZbXCJ3ZWJwYWNrQ2h1bmtcIl0gfHwgW107XG5jaHVua0xvYWRpbmdHbG9iYWwuZm9yRWFjaCh3ZWJwYWNrSnNvbnBDYWxsYmFjay5iaW5kKG51bGwsIDApKTtcbmNodW5rTG9hZGluZ0dsb2JhbC5wdXNoID0gd2VicGFja0pzb25wQ2FsbGJhY2suYmluZChudWxsLCBjaHVua0xvYWRpbmdHbG9iYWwucHVzaC5iaW5kKGNodW5rTG9hZGluZ0dsb2JhbCkpOyIsIi8vIHN0YXJ0dXBcbi8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuLy8gVGhpcyBlbnRyeSBtb2R1bGUgZGVwZW5kcyBvbiBvdGhlciBsb2FkZWQgY2h1bmtzIGFuZCBleGVjdXRpb24gbmVlZCB0byBiZSBkZWxheWVkXG52YXIgX193ZWJwYWNrX2V4cG9ydHNfXyA9IF9fd2VicGFja19yZXF1aXJlX18uTyh1bmRlZmluZWQsIFtcInZlbmRvcnMtbm9kZV9tb2R1bGVzX2NvcmUtanNfbW9kdWxlc19lc19vYmplY3RfdG8tc3RyaW5nX2pzLW5vZGVfbW9kdWxlc19qcXVlcnktY29uZmlybV9kaXN0Xy03NTlkYmJcIixcInZlbmRvcnMtbm9kZV9tb2R1bGVzX2NvcmUtanNfaW50ZXJuYWxzX3JlZ2V4cC1mbGFnc19qcy1ub2RlX21vZHVsZXNfZGF0YXRhYmxlc19uZXQtYnM1X2pzX2RhdC04NDgxYjRcIixcInZlbmRvcnMtbm9kZV9tb2R1bGVzX2NvcmUtanNfbW9kdWxlc19lc19kYXRlX3RvLXN0cmluZ19qcy1ub2RlX21vZHVsZXNfY29yZS1qc19tb2R1bGVzX2VzX3JlZy03NDIyMGFcIl0sICgpID0+IChfX3dlYnBhY2tfcmVxdWlyZV9fKFwiLi9hc3NldHMvanMvaGlnaGNoYXJ0cy5qc1wiKSkpXG5fX3dlYnBhY2tfZXhwb3J0c19fID0gX193ZWJwYWNrX3JlcXVpcmVfXy5PKF9fd2VicGFja19leHBvcnRzX18pO1xuIl0sInNvdXJjZVJvb3QiOiIifQ==