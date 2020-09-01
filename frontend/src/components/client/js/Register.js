import React, { Component } from "react";
import axios from 'axios' ; 
import {Redirect , Route , HashRouter as Router , NavLink} from "react-router-dom" ; 
import "../../css/register.css";

class Register extends Component {
    constructor(props) {
        super(props);
        this.signup_disabled = React.createRef; 
        this.redirect = React.createRef ; 
        this.state = {
            host: "http://localhost:8000", 
             signup : { 
                 "fullname_" : ""  ,
                 "email" : "" , 
                 "password" : "" , 
                 "confirmPassword" : "" , 
                 "address" : "" , 
                 "phone_number" : "" , 
                 "license_number" : "" ,
                 "city" : { 
                     "id": "" , 
                     "name_": ""
                 }
             },
            login : { 
               "email" : "" , 
               "password" : "" 
             } , 
             cities: [] , 
             modal_message: {  // parameters for the modal popup 
                  "title": " " , 
                  "body" : " " , 
                  "button_hidden" :true
             }, 
             redirect : false 
        }
        this.signUp = this.signUp.bind(this);
        this.login = this.login.bind(this);
        this.citiesSelect = this.citiesSelect.bind(this);
        this.handleChangeSignup = this.handleChangeSignup.bind(this); 
        this.handleSignup = this.handleSignup.bind(this);  
        this.signupAlert  = this.signupAlert.bind(this) ; 
        this.loginModal = this.loginModal.bind(this); 
        this.handleChangeLogin = this.handleChangeLogin.bind(this); 
        this.handleLogin = this.handleLogin.bind(this); 
        }
     componentWillMount() { 
        
        this.signup_disabled = false; // setting up the sign up disable button 
         this.redirect = "/register" ; 
        axios.get(this.state.host + "/public/cities")
         .then((res) => {
             this.setState({ 
               cities:res.data
             });
             // setting up a default value for the city attribute in the signup state 
             const cities = this.state.cities
             const signup= this.state.signup; 
             signup["city"]["id"] = cities[0]["id"];
             signup["city"]["name_"] = cities[0]["name_"];
             this.setState({ 
                signup : signup  
              }); 
            }); 
     } 

     componentWillUpdate(prevProps , prevState ) { // repition to be fixed 
         if ( this.state.signup["password"] !==this.state.signup["confirmPassword"] ){ 
              this.signup_disabled =  true ; 
              // email model validation 
            } else  if(this.state.signup["email"].length> 0 &&
            !this.state.signup["email"].trim()
            .match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.com)$/)) { 
                this.signup_disabled =  true ; 
            } else { 
                this.signup_disabled = false; 
            }
 
        if(prevState.redirect == true) {
             this.redirect="/"; 
        }

     }
 
     
     handleChangeSignup(event ,   attribute  ) { 
         event.preventDefault; 
          const signup = this.state.signup ; // state attribute    
          if(attribute == "city") {  // the city attribute is special since it recieves an object composed of id and name
            const value =  (event.target.value ).split( "," ) ;
              signup[attribute]["id"] =   value[0]; 
              signup[attribute]["name_"] = value[1];
              this.setState({
                singnup:  signup
              }); 
              return;
    
        } 
          signup[attribute] = event.target.value; 
          this.setState({
            singnup:  signup
          })

     }
     handleSignup( event) { 
          event.preventDefault ; 
          const signupObject = this.state.signup ; 
          delete signupObject["confirmPassword"] ; 
          $('#modal-login').modal('show');
          var spinner_hidden = false ; 
          this.setState ({
              modal_message :{
                  "title" :  <div> <div class="spinner-border" role="status" hidden={spinner_hidden} >  </div>  Loading...</div> , 
                  "body" :   <p> </p> , 
                  "button_hidden": true 
               }
          });
          axios.post(this.state.host + "/signup" , signupObject) 
          .then((res)=> {
              const data =res.data;
            
              this.setState ({
                modal_message :{
                    "title" : <div> <i class="far fa-check-circle"></i>  singnup sucess</div> , 
                    "body" :  <div> login now...  </div> ,
                    "button_hidden" : false 
                 }
            });
        }).catch((e)=> {  // handling the excetion that may accur during data display
            this.setState ({
                modal_message :{
                    "title" : <div> <i class="fas fa-exclamation-triangle"></i> error accured</div> , 
                    "body" :  <div> try to signup later or login</div> ,
                    "button_hidden" : false 
                 }
                }); 
        }); 

     }
      
  
     citiesSelect() { 
           var cities = this.state.cities ; 
           const citiesUI = [ ]; 
           for(var   i in cities) {
               if(!Number.isInteger(parseInt(i))) { break ;}
               var city = [cities[i]["id"] ,   cities[i]["name_"] ];

                var city = <option  key={cities[i]["id"]} 
                                 value={  city  } 
                                 id={cities[i]["id"]} >{cities[i]["name_"]}</option> ; 
                  citiesUI.push(city);
                
           }
          return ( <select  className="form-control" id="city" onChange =  { (e) => { this.handleChangeSignup(e ,"city" )}} > 
                 {citiesUI}
               </select>
          ) ; 
     }  
     // hnadling the  alert on the top of the form  inputs 
        signupAlert() { 
          var message  =  " "  ;
          var  alert_hidden = true ;
          this.signup_disabled = false; 

           if ( this.state.signup["password"] !==this.state.signup["confirmPassword"] ){ 
                       message  =  " passwords  are not identical"  ;
                       alert_hidden = false ; 
                       this.signup_disabled =  true ; 

         // email model validation 
        } else  if(this.state.signup["email"].length> 0 &&
            !this.state.signup["email"].trim()
            .match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.com)$/)) { 
                message  =  "invalid email address"  ;
                alert_hidden = false ; 
                 this.signup_disabled = true; 

         }  
         
         const alert = <div className ="alert alert-danger" role="alert" 
         hidden = {alert_hidden}> <i className="fas fa-exclamation-circle"></i> { message } </div> ; 
         return alert ;
     }
     loginModal() { 
          return (
              <div className="modal fade" tabIndex="-1" role="dialog" id="modal-login" data-backdrop="false"> 
               <div className="modal-dialog modal-dialog-centered" role ="document" > 
                <div className="modal-content text-monospace"> 
                <div className="modal-header text-monospace">
                 <p className="modal-title text-monospace">
                  {this.state.modal_message.title}  </p>
                 <button type="button" className="close" data-dismiss="modal" aria-label="close" 
                 hidden={this.state.modal_message["button_hidden"]}>
                     <span aria-hidden="true">&times;</span>
                 </button>
                 </div> 
                 <div className="modal-body text-monospace"> 
                 {this.state.modal_message.body} 
                  </div>
                 <div className="modal-footer text-monospace">
             <button type="button" className="btn" data-dismiss="modal" 
             hidden={this.state.modal_message["button_hidden"]} > OK</button>
                 </div>
                </div>
               </div>
              </div> 

          ); 
     } 
     signUp() {
        return (
        
            <div className="col-sm-5 rounded" id="signup">  
              <this.loginModal></this.loginModal>
                <div className="col-sm  header">  Don't have an account yet?  <strong className="form-header"> Sign Up</strong>  </div>                
                 
                 <form onSubmit= {(e) => {this.handleSignup(e)}}>
                     <this.signupAlert></this.signupAlert>
                    <div className="form-row">
 
                        <div className="form-group col-md-6">
                            <label htmlFor="fullname_">fullname</label>
                            <input type="text" className="form-control "  id="fullname_" 
                             value={this.state.signup["fullname_"]} 
                              onChange =  { (e) => { this.handleChangeSignup(e ,"fullname_" )}}  required/>
                        </div>
                        <div className="form-group col-md-6">
                            <label htmlFor="email">email </label>
                            <input type="email" className="form-control" id="email" 
                            value={this.state.signup["email"]} 
                            onChange =  { (e) => { this.handleChangeSignup(e ,"email" )}} 
                             required/>
                        </div>
                    </div>
                    <div className="form-group"> 
                     <label htmlFor = "phone_number"> phone number </label>
                     <input type="number" className="form-control" id="phone_number"
                        value={this.state.signup["phone_number"]} 
                        onChange=  { (e) => { this.handleChangeSignup(e ,"phone_number" )}}
                        required/>
     
             
                    </div>

                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <label htmlFor="password">Password</label>
                            <input type="password" className="form-control" id="password"
                            value={this.state.signup["password"]} 
                            onChange =  { (e) => { this.handleChangeSignup(e ,"password" )}} 
                            required/>
                        </div>
                        <div className="form-group col-md-6">
                            <label htmlFor="passwordConfirm">confirm password </label>
                            <input type="password" className="form-control" id="passwordConfirm" 
                            value={this.state.signup["confirmPassword"]} 
                            onChange =  { (e) => { this.handleChangeSignup(e ,"confirmPassword" )}} 
                            required/>                        </div>
                    </div>
                    <div className="form-group">
                        <label htmlFor="address"> domicil adress</label>
                        <input type="address" className="form-control" id="address" 
                        value={this.state.signup["address"]} 
                        onChange =  { (e) => { this.handleChangeSignup(e ,"address" )}}
                        required/>
     
                    </div>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <label htmlFor="city">city</label>
                             <this.citiesSelect></this.citiesSelect>
                        </div>
                        <div className="form-group col-md-6">
                            <label htmlFor="licenseNumber"> driving license Number </label>
                            <input type="text" className="form-control" id="licenseNumber" 
                            value={this.state.signup["license_number"]} 
                            onChange =  { (e) => { this.handleChangeSignup(e ,"license_number" )}}
                            required />
                        </div>
                    </div>
                    <div className="text-center form-group col-md-12" >
                        <button type="submit" className="btn  rounded-pill" id="submit" 
                           disabled = {this.signup_disabled}>submit</button>
                    </div>
                </form>
            </div>

        )
    }
    handleChangeLogin(event , attribute ) 
     { 
         var login = this.state.login; 
         login[attribute] = event.target.value ; 
          this.setState( { 
            login : login 
          }); 
      }
    handleLogin(event) { 
        event.preventDefault ; 
        const login = this.state.login ; 
        const spinner_hidden= false; 
        $('#modal-login').modal('show');
        this.setState ({
            modal_message :{
                "title" :  <div> <div class="spinner-border" role="status" hidden={spinner_hidden} >  </div>  Loading...</div> , 
                "body" :   <p> </p> , 
                "button_hidden": true 
             }

        });

        axios.post(this.state.host +"/login" ,login)
              .then((res) => { 
                $('#modal-login').modal('hide');
                axios.get(this.state.host+"/client/current" , 
                { headers : {'Authorization' : 'Bearer '+ res.data["api_token"] }}
                 ).then(res2 => {
                    console.log(res2);            
                 })
        
             }).catch((e) => { 
                this.setState ({
                    modal_message :{
                        "title" : <div> <i class="fas fa-exclamation-triangle"></i> credentials error</div> , 
                        "body" :  <div> consider inserting correct creadentials or try to signup </div> ,
                        "button_hidden" : false 
                      }
                    }); 
        
            })

    }
    login() {
        return (
            <div className="col-sm-3 rounded" id="login">
               <this.loginModal></this.loginModal>
                <div className="col-sm  header">  Already registered? <strong className="form-header"> login </strong> </div>
                <form onSubmit = {(e)=> { this.handleLogin(e)}}>
                    <div className="form-group">
                        <label htmlFor="email">Email</label>
                        <input type="email" className="form-control" id="email" 
                        value = {this.state.login["email"]}
                        onChange= { (e) => {this.handleChangeLogin(e , "email") }}
                        required/>

                    </div>
                    <div className="form-group">
                        <label htmlFor="password">Password</label>
                        <input type="password" className="form-control" id="password"
                         value = {this.state.login["password"] } 
                         onChange= { (e) => {this.handleChangeLogin(e , "password") }}
                         required/>

                    </div>
                    <div className="text-center form-group col-md-12" >

                    <button type="submit" className="btn rounded-pill" id="submit">Submit</button>
                    <Redirect   to ={this.redirect}>   </Redirect>
            
                    </div>

                </form>
            </div>

        );
    }


    render() {
        return (
            <div className="container-fluid">
                <div className="row">
                    <this.signUp></this.signUp>
                    <div className="middle">
                    <div   id="vl"> </div> 

                    <div className="or align-middle"><p>Or</p></div>
                    <div   id="vl"> </div> 

                    </div>
                    <this.login></this.login>
                </div>
            </div>
        );

    }
}
export default Register; 