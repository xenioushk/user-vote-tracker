/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/modules/uvt_custom_scripts.js":
/*!*******************************************!*\
  !*** ./src/modules/uvt_custom_scripts.js ***!
  \*******************************************/
/***/ (() => {

;
(function ($) {
  "use strict";

  $(function () {
    var $uvt_data_overlay = "uvt_data_overlay";
    $("body").on("click", ".uvt-btn-prev", function () {
      var $uvt_data_table = $(this).parents("ul.uvt_data_table");
      var start_id = $(this).data("start_id");
      var limit = $(this).data("limit");
      var filter = $(this).data("filter");
      var meta = $(this).data("meta");
      var global_mode = $(this).data("global_mode");
      var pagination = $(this).data("pagination");
      $uvt_data_table.addClass($uvt_data_overlay);
      $.when(get_user_vote_data(start_id, filter, limit, pagination, meta, global_mode)).done(function (response_data) {
        $uvt_data_table.html(response_data).removeClass($uvt_data_overlay);
      });
      return false;
    });
    $("body").on("click", ".uvt-btn-next", function () {
      var $uvt_data_table = $(this).parents("ul.uvt_data_table");
      // console.log($(this).length)

      var $uvt_btn_next = $uvt_data_table.find(".uvt-btn-next");
      // console.log($uvt_btn_next.length)

      var start_id = $(this).data("start_id");
      var limit = $(this).data("limit");
      var filter = $(this).data("filter");
      var meta = $(this).data("meta");
      var global_mode = $(this).data("global_mode");
      var pagination = $(this).data("pagination");
      $uvt_data_table.addClass($uvt_data_overlay);
      $.when(get_user_vote_data(start_id, filter, limit, pagination, meta, global_mode)).done(function (response_data) {
        $uvt_data_table.html(response_data).removeClass($uvt_data_overlay);
      });
      return false;
    });
    function get_user_vote_data(start_id, filter, limit, pagination, meta, global_mode) {
      return $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
          action: "get_user_vote_data",
          // this is the name of our WP AJAX function that we'll set up next
          start_id: start_id,
          filter: filter,
          limit: limit,
          pagination: pagination,
          meta: meta,
          global_mode: global_mode
        }
      });
    }
  });
})(jQuery);

/***/ }),

/***/ "./src/styles/frontend.scss":
/*!**********************************!*\
  !*** ./src/styles/frontend.scss ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
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
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_frontend_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/frontend.scss */ "./src/styles/frontend.scss");
/* harmony import */ var _modules_uvt_custom_scripts__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/uvt_custom_scripts */ "./src/modules/uvt_custom_scripts.js");
/* harmony import */ var _modules_uvt_custom_scripts__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_modules_uvt_custom_scripts__WEBPACK_IMPORTED_MODULE_1__);
// Stylesheets


// Javascripts

})();

/******/ })()
;
//# sourceMappingURL=frontend.js.map