/*
 *  Npm import
 */
import React from 'react';


/*
 *  Local import
 */


import Cart from 'js/components/Cart';

/*
 *  CSS import
 */

require('./app.css');

/*
 * code
 */

class App extends React.Component {
  constructor() {
    super();
    this.state = {
      showMe: true,
    };
  }

  // toggleMenu = () => {
  //   console.log('toggleMenu');
  // };

  render() {
    console.log(this.state);
    return (
      <div className="je-test">
        <button onClick={ this.toggleMenu } />
        <Cart />
      </div>
    );
  }
}

/*
 * Export Default
 */
export default App;
