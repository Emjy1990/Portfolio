/* eslint-disable jsx-a11y */
/*
 *  Npm Import
 */
import 'babel-polyfill';
import React from 'react';
import { render } from 'react-dom';

/*
 *  Local import
 */
import App from 'js/components/App';
/*
 * CSS import
 */

require('./style.css');

/*
 * Code
 */

document.addEventListener('DOMContentLoaded', () => {
  render(<App />, document.getElementById('root'));
});
