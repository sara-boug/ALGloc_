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
            <i class="fab fa-facebook"></i>
            <i class="fab fa-instagram"></i>
            <i class="fab fa-twitter"></i>
        </footer>); 
    
    }
}
export default Footer;