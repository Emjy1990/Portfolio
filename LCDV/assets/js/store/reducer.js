/*
 * Initial State
 */

const initialState = {
  message: '',
};

/*
 * Reducer
 */

const reducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case 'DO_SOMETHING':
      return {
        ...state,
      };
  default:
    return state;

  }
}

/*
 *  Action Creators
 */

const doSomething


/*
 *  Export
 */
