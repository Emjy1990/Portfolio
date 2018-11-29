/*
 *  NPM Import
 */

 import { createStore } from 'redux';

/*
 *  Local Import
 */

 import reducer from './reducer';

/*
 *  Code
 */

const preloadedState = {
  testRedux: window.INITIAL_STATE,
  input: '',
};
const store = createStore(reducer, preloadedState);

/*
 *  Export
 */

export default store;
