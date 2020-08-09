import React, { Component } from 'react';
import { HashRouter as Router,  Switch,Route , Link  } from "react-router-dom";
import Home from './js/Home';
import Header from './js/Header';
import Register from './js/Register'; 

class App extends Component {
    render() {
        return (

                <Router>

                    <div>
                       <Header />
                         <Switch>
                          <Route path="/register" component={Register} /> 
                        </Switch>                      
                      </div>
                </Router>
         )

    }
}


 

export default App;