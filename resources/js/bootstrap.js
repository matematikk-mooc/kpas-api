import popper from "popper.js"
import lodash from "lodash"
import "select2";

window._ = lodash;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = popper;

} catch (e) {
    console.error(e, e.stack);
}
