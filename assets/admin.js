/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import lazySizes from 'lazysizes';
lazySizes.cfg.lazyClass = 'lazy';
import './js/ls.respimg.js';

import './js/jquery_global.js';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import feather from 'feather-icons'
feather.replace();

import PerfectScrollbar from 'perfect-scrollbar/dist/perfect-scrollbar.js'
window.PerfectScrollbar = PerfectScrollbar;

import './js/dashforge.js'
import './js/dashforge.aside.js'
import './styles/admin.css'
import './styles/dashforge.css'

var toastElList = [].slice.call(document.querySelectorAll('.toast'))
var toastList = toastElList.map(function (toastEl) {
  return new bootstrap.Toast(toastEl, {'autohide': true});
})

toastList.forEach((toast) => {
    toast.show();
});
