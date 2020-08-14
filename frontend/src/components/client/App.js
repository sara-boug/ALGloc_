import React, { Component } from 'react';
import { HashRouter as Router,  Switch,Route , Link  } from "react-router-dom";
import Home from './js/Home';
import Header from './js/Header';
import Register from './js/Register'; 
import Footer from './js/Footer';


class App extends Component {
    render() {
        return (

                <Router>

                    <div>
                       <Header />
                         <Switch>
                         <Route path="/register" component={Register} /> 

                          <Route path="/" component={Home} />
                        </Switch>  
                        <Footer></Footer>

                      </div>
                </Router>
         )

    }
}


 

export default App;