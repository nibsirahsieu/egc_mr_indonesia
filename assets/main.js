import lazySizes from 'lazysizes';
lazySizes.cfg.lazyClass = 'lazy';
lazySizes.cfg.loadMode = 1;

import './js/ls.respimg.js';
import './js/frontend.min.js';
import './styles/frontend.min.css'

//https://stackoverflow.com/questions/58982072/recaptcha-v3-assets-cause-pagespeed-issues-how-to-defer
var fired = false;
window.addEventListener('scroll', function () {
  let scroll = window.scrollY;
  if (scroll > 0 && fired === false) {
    var recaptchaScript = document.createElement('script');
    recaptchaScript.src = 'https://www.google.com/recaptcha/api.js?render=6LfjoXYqAAAAAE0LkSkPq74iJBUicoanNPvobR7x';
    recaptchaScript.defer = true;
    document.body.appendChild(recaptchaScript);
    fired = true;
  }
}, true);