import React, { Component } from "react";
import "../../css/register.css";

class Register extends Component {
    constructor(props) {
        super(props);
        this.state = {

        }

        this.signUp = this.signUp.bind(this);
        this.login = this.login.bind(this);


    }
    signUp() {
        return (
            <div className="col-sm-5 rounded" id="signup">
                <div className="col-sm  header">  Don't have an account yet?  <strong className="form-header"> Sign Up</strong>  </div>
                <form>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <label for="fullname">fullname </label>
                            <input type="text" className="form-control " id="fullname"></input>
                        </div>
                        <div className="form-group col-md-6">
                            <label for="email">Email </label>
                            <input type="email" className="form-control" id="email"></input>
                        </div>
                    </div>

                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <label for="password">Password</label>
                            <input type="password" className="form-control" id="password"></input>
                        </div>
                        <div className="form-group col-md-6">
                            <label for="passwordConfirm">Confirm Password </label>
                            <input type="password" className="form-control" id="passwordConfirm"></input>
                        </div>
                    </div>
                    <div className="form-group">
                        <label for="address">Adress</label>
                        <input type="address" className="form-control" id="address"></input>

                    </div>
                    <div className="form-row">
                        <div className="form-group col-md-6">
                            <label for="city">City</label>
                            <select type="text" className="form-control" id="city">
                                <option selected>choose</option>
                            </select>
                        </div>
                        <div className="form-group col-md-6">
                            <label for="licenseNumber">license Number </label>
                            <input type="text" className="form-control" id="licenseNumber"></input>
                        </div>
                    </div>
                    <div className="text-center form-group col-md-12" >

                        <button type="submit" class="btn  rounded-pill" id="submit">Submit</button>
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
                        <label for="email">Email</label>
                        <input type="email" className="form-control" id="email"></input>

                    </div>
                    <div className="form-group">
                        <label for="password">Password</label>
                        <input type="password" className="form-control" id="password"></input>

                    </div>
                    <div className="text-center form-group col-md-12" >

                        <button type="submit" class="btn rounded-pill" id="submit">Submit</button>
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
                    <div class="middle">
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