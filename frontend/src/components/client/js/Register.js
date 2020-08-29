import React, { Component } from "react";
import axios from 'axios' ; 
import "../../css/register.css";

class Register extends Component {
    constructor(props) {
        super(props);
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
 
             }
        }
        this.signUp = this.signUp.bind(this);
        this.login = this.login.bind(this);
        this.citiesSelect = this.citiesSelect.bind(this);
        this.handleChangeSignup = this.handleChangeSignup.bind(this); 
        this.handleSignup = this.handleSignup.bind(this);  
        this.signupAlert  = this.signupAlert.bind(this) ; 
        this.loginModal = this.loginModal.bind(this); 
        }
     componentWillMount() { 
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
           console.log(signup);

             
         }); 
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
        try {
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
              
            }) 


       
     }
      
  
     citiesSelect() { 
           var cities = this.state.cities ; 
           const citiesUI = [ ]; 
            let j =1 ; 
           for(var   i in cities) {
               if(!Number.isInteger(parseInt(i))) { break ;}
               var city = [cities[i]["id"] ,   cities[i]["name_"] ];

                var city = <option  key={cities[i]["id"]} 
                                 value={  city  } 
                                 id={cities[i]["id"]} >{cities[i]["name_"]}</option> ; 
                  j++; 
                citiesUI.push(city);
                
           }
          return ( <select  className="form-control" id="city" onChange =  { (e) => { this.handleChangeSignup(e ,"city" )}} > 
                 {citiesUI}
               </select>
          ) ; 
     }  
     // hnadling the  alert on the top of the form 
        signupAlert() { 
          var message  =  " "  ;
          var  alert_hidden = true ;
          if ( this.state.signup["password"] !==this.state.signup["confirmPassword"] ){ 
                       message  =  " passwords  are not identical"  ;
                       alert_hidden = false;
         } 
         // email model validation 
         if(this.state.signup["email"].length> 0 &&
            !this.state.signup["email"].trim()
            .match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.com)$/)) { 
                message  =  "invalid email address"  ;
                alert_hidden = false;
         }
         const alert = <div className ="alert alert-danger" role="alert" 
         hidden = {alert_hidden}> <i className="fas fa-exclamation-circle"></i> { message } </div> ; 
        return alert ;
     }
     loginModal() { 
          return (
              <div className="modal fade" tabIndex="-1" role="dialog" id="modal-login" data-backdrop="false"> 
               <div className="modal-dialog modal-dialog-centered" role ="document" > 
                <div className="modal-content"> 
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
                     <button type="button" className="btn" data-dismiss="modal" hidden={this.state.modal_message["button_hidden"]}> login</button>
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
                        <button type="submit" className="btn  rounded-pill" id="submit">submit</button>
                    </div>
                </form>
            </div>

        )
    }

    login() {
        return (
            <div className="col-sm-3 rounded" id="signup">
                <div className="col-sm  header">  Already registered? <strong className="form-header"> login </strong> </div>
                <form>
                    <div className="form-group">
                        <label htmlFor="email">Email</label>
                        <input type="email" className="form-control" id="email"/>

                    </div>
                    <div className="form-group">
                        <label htmlFor="password">Password</label>
                        <input type="password" className="form-control" id="password"/>

                    </div>
                    <div className="text-center form-group col-md-12" >

                        <button type="submit" className="btn rounded-pill" id="submit">Submit</button>
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