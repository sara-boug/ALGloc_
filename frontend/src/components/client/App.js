import React, { Component } from 'react';
import { HashRouter as Router,  Switch,Route , Link  } from "react-router-dom";
import Home from './js/Home';
import Header from './js/Header';

class App extends Component {
    render() {
        return (

                <Router>

                    <div>
 

                       <Header />
                        <Switch>
                            <Route  exact path="/"     component={Home} />  

                            <Route  exact path="/home"     component={Home} />  
                            </Switch>                      
                      </div>
                </Router>
         )

    }
}


 

export default App;