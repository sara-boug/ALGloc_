import React , {Component } from 'react' ;
 import {NavLink , BrowserRouter} from 'react-router-dom'; 

// the header that would be added to all client pages
class Header extends Component { 
    constructor(props)  {
        super(props); 
        this.state = { 

        }
      
    } 
     setupUser() { 
 
     }

     render(){ 
         return ( 
            <nav className= "navbar navbar-dark  bg-dark"> 
             <NavLink  exact to="/home"> <strong>Alg</strong>loc </NavLink>
           </nav>
         ); 
     }
   
    
    
}
export default Header; 