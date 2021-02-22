(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["/js/main"],{

/***/ "./resources/js/main.js":
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

var fullHeight = function fullHeight() {
  $('.js-fullheight').css('height', $(document).height());
  $(document).resize(function () {
    $('.js-fullheight').css('height', $(document).height());
  });
};

fullHeight();
$('#sidebarCollapse').on('click', function () {
  $('#sidebar').toggleClass('active');
});

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/*! exports provided: default */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ 0:
/*!**************************************************************!*\
  !*** multi ./resources/js/main.js ./resources/sass/app.scss ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(/*! F:\Program Files\xampp\htdocs\suntel\cms-music\resources\js\main.js */"./resources/js/main.js");
module.exports = __webpack_require__(/*! F:\Program Files\xampp\htdocs\suntel\cms-music\resources\sass\app.scss */"./resources/sass/app.scss");


/***/ })

},[[0,"/js/manifest"]]]);