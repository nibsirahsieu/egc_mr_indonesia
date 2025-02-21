import {TLN} from './tln.js'
import '../styles/tln.css'
import BsToast from '@erwinstone/bs-toast'
import {submitForm} from './utils.js'

$(function() {
  TLN.append_line_numbers('header_script');
  TLN.append_line_numbers('footer_script');

  submitForm('#formHeaderFooterScript', function(response, $form) {
    new BsToast({
      body: 'Header and Footer script has been updated.',
      className: 'border-0 bg-success text-white',
      btnCloseWhite: true,
    }).show()
  });
});