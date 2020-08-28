import React, { Component } from "react";
import axios from 'axios' ; 
import "../../css/register.css";

class Register extends Component {
    constructor(props) {
        super(props);
        this.state = {
            host: "http://localhost:8000", 
             signup : { 
                 "fullname" : ""  ,
                 "email" : "" , 
                 "password" : "" , 
                 "confirmPassword" : "" , 
                 "address" : "" , 
                 "city" : "" , 
                 "license_number" : "" 
             },
            login : { 
               "email" : "" , 
               "password" : "" 
             } , 
             cities: [] , 
             alert_hidden : true,
             singupAlert: "Danger"

        }
        this.signUp = this.signUp.bind(this);
        this.login = this.login.bind(this);
        this.citiesSelect = this.citiesSelect.bind(this);
        this.handleChangeSignup = this.handleChangeSignup.bind(this); 
        this.handleSignup = this.handleSignup.bind(this);  
        this.signupAlert  = this.signupAlert.bind(this) ; 
        }
     componentWillMount() { 
         axios.get(this.state.host + "/public/cities")
         .then((res) => {
             this.setState({ 
               cities:res.data
             }); 
         }); 

     }
     handleChangeSignup(event ,   attribute  ) { 
         const signup = this.state.signup ; // state attribute    
          signup[attribute] = event.target.value; 
          this.setState({
            singnup:  signup
          })

     }
     handleSignup( event) { 
          event.preventDefault ; 
          console.log(this.state.signup); 
          /*axios.post(this.state.host + "/signup" , this.state.signup) 
          .then((res)=> { 
              const data =res.data;
              console.log(res); 
          });*/
     }
      
  
     citiesSelect() { 
           const cities = this.state.cities ; 
           const citiesUI = [ ]; 
           for( var i in cities) {
               if(!Number.isInteger(parseInt(i))) { break ;}
               var city = <option key={cities[i]["id"]} value={cities[i]["name_"]} 
                >{cities[i]["name_"]}</option> ; 
                citiesUI.push(city);
           }
           
          return ( <select  className="form-control" id="city" onChange =  { (e) => { this.handleChangeSignup(e ,"city" )}} > 
                 {citiesUI}
               </select>
          ) ; 
     }
        signupAlert() { 
          var message  =  " "  ;
          var  alert_hidden = true ;
          if ( this.state.signup["password"] !==this.state.signup["confirmPassword"] ){ 
                       message  =  " passwords  are not identical"  ;
                       alert_hidden = false;
         }  
         const alert = <div className ="alert alert-danger" role="alert" 
         hidden = {alert_hidden}> <i className="fas fa-exclamation-circle"></i> { message } </div> ; 
        return alert ;
     }
     signUp() {
        return (
            <div className="col-sm-5 rounded" id="signup">
                <div className="col-sm  header">  Don't have an account yet?  <strong className="form-header"> Sign Up</strong>  </div>                
                 
                 <form>
                     <this.signupAlert></this.signupAlert>
                    <div className="form-row">
 
                        <div className="form-group col-md-6">
                            <label htmlFor="fullname">fullname </label>
                            <input type="text" className="form-control "  id="fullname" 
                             value={this.state.signup["fullname"]} 
                              onChange =  { (e) => { this.handleChangeSignup(e ,"fullname" )}}  required/>
                        </div>
                        <div className="form-group col-md-6">
                            <label htmlFor="email">Email </label>
                            <input type="email" className="form-control" id="email" 
                            value={this.state.signup["email"]} 
                            onChange =  { (e) => { this.handleChangeSignup(e ,"email" )}} 
                             required/>
                        </div>
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
                            <label htmlFor="passwordConfirm">Confirm Password </label>
                            <input type="password" className="form-control" id="passwordConfirm" 
                            value={this.state.signup["confirmPassword"]} 
                            onChange =  { (e) => { this.handleChangeSignup(e ,"confirmPassword" )}} 
                            required/>                        </div>
                    </div>
                    <div className="form-group">
                        <label htmlFor="address">Adress</label>
                        <input type="address" className="form-control" id="address" 
                        value={this.state.signup["address"]} 
                        onChange =  { (e) => { this.handleChangeSignup(e ,"address" )}}
                        required/>
     
                    </div>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <label htmlFor="city">City</label>
                             <this.citiesSelect></this.citiesSelect>
                        </div>
                        <div className="form-group col-md-6">
                            <label htmlFor="licenseNumber">license Number </label>
                            <input type="text" className="form-control" id="licenseNumber" 
                            value={this.state.signup["license_number"]} 
                            onChange =  { (e) => { this.handleChangeSignup(e ,"license_number" )}}
                            required />
                        </div>
                    </div>
                    <div className="text-center form-group col-md-12" >
                        <button type="submit" className="btn  rounded-pill" id="submit" 
                         onClick = {(e) => {this.handleSignup(e)}}>Submit</button>
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