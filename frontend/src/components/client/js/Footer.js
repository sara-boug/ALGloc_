import React  , {Component} from  'react' ; 
import  axios from  'axios' ; 
import  '../../css/footer.css'; 

class Footer extends Component { 
    constructor(props) { 
        super(props)
    }
    render() { 
        return(
        <footer className="page-footer">
            <div></div>
            <i className="fab fa-facebook"></i>
            <i className="fab fa-instagram"></i>
            <i className="fab fa-twitter"></i>
        </footer>); 
    
    }
}
export default Footer;