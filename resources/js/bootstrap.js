import popper from "popper.js"
import lodash from "lodash"


window._ = lodash;

try {
    window.Popper = popper;

} catch (e) {
    console.error(e, e.stack);
}
