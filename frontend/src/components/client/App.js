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
                          <Route path="/" component={Home} />
                          <Route path="/register" component={Register} /> 
                        </Switch>  
                        <Footer></Footer>

                      </div>
                </Router>
         )

    }
}


 

export default App;