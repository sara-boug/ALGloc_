import React , {Component } from 'react' ;
import   "../../css/header.css"
import {NavLink , BrowserRouter} from 'react-router-dom'; 
import axios from 'axios'; 

// the header that would be added to all client pages
class Header extends Component { 
    constructor(props)  {
        super(props); 
        this.state = { 
            host: `http://localhost`, 
            user:null
        }
        this.setupUser = this.setupUser.bind(this); 
        
    }
     componentDidMount(){ 
         var  headers = new Headers(); 
  
         if(headers.get('Authorization')==null) {  // checking the authorization header
               return
         }
          axios.get(`http://localhost:8000/client/current`) 
          .then(res => {
                this.setState({
                  user: res.data
              })
         });  
         
    }
  
     setupUser() {  // this function deals with the nabbar whether to add  sigup/login button or display the user 
         console.log("hello world"); 
        if(this.state.user ==null){
         return (
             <div className="header-button">
              <button type="button" className="btn btn-outline-primary btn-md rounded-pill">Sign In </button>
               </div>
         )
        } 
        return (
            <div>user</div>
        ) 
   
     }
     render(){ 
         return( 
            <nav className= "shadow-lg navbar  navbar-dark  bg-dark"> 
             <NavLink  exact to="/"> <a className="navbar-brand big"><strong>ALG</strong>loc</a>
             <i class="fas fa-car-side fa-lg"></i>   </NavLink>
             <this.setupUser></this.setupUser>
                 </nav>
         ); 
     }
   
    
    
}
export default Header; 