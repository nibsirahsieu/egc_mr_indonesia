import lazySizes from 'lazysizes';
lazySizes.cfg.lazyClass = 'lazy';

import './js/ls.respimg.js';
import './js/frontend/main.js';
import './styles/app.css'

//https://stackoverflow.com/questions/58982072/recaptcha-v3-assets-cause-pagespeed-issues-how-to-defer
/*var fired = false;
window.addEventListener('scroll', function () {
  let scroll = window.scrollY;
  if (scroll > 0 && fired === false) {
    var recaptchaScript = document.createElement('script');
    recaptchaScript.src = 'https://www.google.com/recaptcha/api.js?render=6LfjoXYqAAAAAE0LkSkPq74iJBUicoanNPvobR7x';
    recaptchaScript.defer = true;
    document.body.appendChild(recaptchaScript);
    fired = true;
  }
}, true);*/

function loadRecaptcha() {
    if (document.getElementById("recaptcha-script")) return;
    var recaptchaScript = document.createElement('script');
    recaptchaScript.id = "recaptcha-script";
    recaptchaScript.src = 'https://www.google.com/recaptcha/api.js?render=' + document.documentElement.dataset.sitekey;
    recaptchaScript.defer = true;
    document.body.appendChild(recaptchaScript); 

    // remove listeners after load
    window.removeEventListener("scroll", loadRecaptchaOnEvent);
    window.removeEventListener("touchstart", loadRecaptchaOnEvent);
}

function loadRecaptchaOnEvent() {
  loadRecaptcha();
}

window.addEventListener("scroll", loadRecaptchaOnEvent, { once: true });
window.addEventListener("touchstart", loadRecaptchaOnEvent, { once: true });