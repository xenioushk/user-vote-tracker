/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/admin/modules/admin.js":
/*!************************************!*\
  !*** ./src/admin/modules/admin.js ***!
  \************************************/
/***/ (() => {

;
(function ($) {
  /*------------------------------ Vote Reporting ---------------------------------*/

  if ($("#uvt_voting_report_page").length) {
    var $uvt_ctrl_section = $(".uvt-ctrl-section"),
      $mv_buvt_post_type = $uvt_ctrl_section.find("#mv_buvt_post_type"),
      $mv_post_title = $uvt_ctrl_section.find("#mv_post_title"),
      $mv_post_filters = $uvt_ctrl_section.find("#mv_post_filters"),
      $mv_vote_info_type = $uvt_ctrl_section.find("#mv_vote_info_type"),
      $uvt_custom_date_range = $uvt_ctrl_section.find("#uvt_custom_date_range"),
      $uvt_filter_start_date = $uvt_ctrl_section.find("#uvt_filter_start_date"),
      $uvt_filter_end_date = $uvt_ctrl_section.find("#uvt_filter_end_date"),
      $mv_uvt_go = $("#mv_uvt_go"),
      $uvt_amv = $("#uvt_amv");
    var $uvt_user_id = $("#uvt_user_id");
    $mv_buvt_post_type.val("");
    $mv_post_filters.val("");
    $mv_vote_info_type.val(1);
    var $deleteTriger = $("#deleteTriger");

    /*------------------------------ Date Range Filter ---------------------------------*/

    var $uvt_date_range_items = $([]).add($uvt_filter_start_date).add($uvt_filter_end_date);
    $uvt_custom_date_range.prop("checked", false);
    $uvt_date_range_items.val("");
    $uvt_custom_date_range.on("click", function () {
      if ($uvt_custom_date_range.is(":checked")) {
        $uvt_date_range_items.val("").removeAttr("disabled");
      } else {
        $uvt_date_range_items.val("").attr("disabled", "disabled");
      }
    });

    // Filter Start Date.

    $uvt_filter_start_date.datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      dateFormat: "yy-mm-dd",
      numberOfMonths: 1,
      onSelect: function (selectedDate) {
        $uvt_filter_end_date.datepicker("option", "minDate", selectedDate);
      }
    });
    $uvt_filter_end_date.datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      dateFormat: "yy-mm-dd",
      numberOfMonths: 1,
      onSelect: function (selectedDate) {
        $uvt_filter_start_date.datepicker({
          maxDate: selectedDate
        });
      }
    });

    ///////////////////////////////////////////////////////////////////////////////////////////

    var $uvt_msg_container = $("#uvt_msg_container"),
      $mv_post_title_default_option_value = $("#mv_post_title").find("option:first");
    $uvt_msg_container.html("");

    // Go Events.

    $mv_uvt_go.on("click", function () {
      $deleteTriger.unbind("click");
      buvt_data_table();
    });

    // Start Coding.

    buvt_data_table();
    function buvt_data_table() {
      var post_type = $mv_buvt_post_type.val(),
        post_id = $mv_post_title.val(),
        mv_post_filters = $mv_post_filters.val(),
        mv_vote_info_type = $mv_vote_info_type.val(),
        uvt_custom_date_range = $uvt_custom_date_range.is(":checked"),
        uvt_filter_start_date = $uvt_filter_start_date.val(),
        uvt_filter_end_date = $uvt_filter_end_date.val();
      var $buvt_data_table = $("#uvt-data-table");
      $buvt_data_table.DataTable().destroy();
      $buvt_data_table.removeClass("dn");
      var dataTable = $buvt_data_table.DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        order: [[0, "desc"]],
        //                "columnDefs": [{
        //                        "targets": 0,
        //                        "orderable": false,
        //                        "searchable": false
        //                    }],
        ajax: {
          url: ajaxurl + "?action=uvt_voting_stats",
          type: "POST",
          data: {
            user_id: $uvt_user_id.val(),
            post_id: post_id,
            post_type: post_type,
            mv_post_filters: mv_post_filters,
            mv_vote_info_type: mv_vote_info_type,
            uvt_custom_date_range: uvt_custom_date_range,
            uvt_filter_start_date: uvt_filter_start_date,
            uvt_filter_end_date: uvt_filter_end_date
          }
        }
      });
    }
  }
})(jQuery);

/***/ }),

/***/ "./src/admin/modules/installation_counter.js":
/*!***************************************************!*\
  !*** ./src/admin/modules/installation_counter.js ***!
  \***************************************************/
/***/ (() => {

;
(function ($) {
  function uvt_bpvm_installation_counter() {
    return $.ajax({
      type: "POST",
      url: ajaxurl,
      data: {
        action: "uvt_bpvm_installation_counter",
        // this is the name of our WP AJAX function that we'll set up next
        product_id: uvtBpvmAdminData.product_id // change the localization variable.
      },

      dataType: "JSON"
    });
  }
  if (typeof uvtBpvmAdminData.installation != "undefined" && uvtBpvmAdminData.installation != 1) {
    $.when(uvt_bpvm_installation_counter()).done(function (response_data) {
      // console.log(response_data)
    });
  }
})(jQuery);

/***/ }),

/***/ "./src/admin/styles/admin.scss":
/*!*************************************!*\
  !*** ./src/admin/styles/admin.scss ***!
  \*************************************/
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
/*!**********************************!*\
  !*** ./src/admin/admin_index.js ***!
  \**********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_admin_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/admin.scss */ "./src/admin/styles/admin.scss");
/* harmony import */ var _modules_admin__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/admin */ "./src/admin/modules/admin.js");
/* harmony import */ var _modules_admin__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_modules_admin__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _modules_installation_counter__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./modules/installation_counter */ "./src/admin/modules/installation_counter.js");
/* harmony import */ var _modules_installation_counter__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_modules_installation_counter__WEBPACK_IMPORTED_MODULE_2__);
// Load Stylesheets.


// Load JavaScripts


})();

/******/ })()
;
//# sourceMappingURL=admin.js.map