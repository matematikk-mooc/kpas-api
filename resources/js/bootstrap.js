import popper from "popper.js"
import jquery from "jquery"
import lodash from "lodash"
import Vue from "vue"


window._ = lodash;
window.Vue = Vue;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = popper;
    window.$ = window.jQuery = jquery;
} catch (e) {
    console.error(e, e.stack);
}
