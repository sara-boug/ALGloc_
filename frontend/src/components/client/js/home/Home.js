import React, { Component } from 'react';
import "../../../css/home.css";
import UpperFilter, { upperFilter} from "./UpperFilter";
import axios from 'axios';
import Vehicle from './Vehicle'; 

class Home extends Component {
  constructor(props) {
    super(props);
    
    this.state = {
            host: "http://localhost:8000",
            filters:    [],
            categories: [],
            vehicles:   [],
            agencies:   [],
            models:     [],
            brands:     []

    }
     this.container = this.container.bind(this); 
    this.agencyFilter = this.agencyFilter.bind(this);
    this.handleFilterClick = this.handleFilterClick.bind(this); // this function used to handle the click the filter on the left of the page 
    this.categoryFilter = this.categoryFilter.bind(this);
    this.modelFilter = this.modelFilter.bind(this);
    this.pagination = this.pagination.bind(this);
    this.deleteElement = this.deleteElement.bind(this); 
     this.filters = ( elements , attribute , filterTitle) =>{ 
        const filterElements = [];
         
     for (var i in elements) {
       if (!Number.isInteger(parseInt(i))) { //  since  every response that concerns a collection contains pagination 
        // this condition allow excluding the page , link , limit attribute 
        break;
      }
      var element = elements[i];
       var elementUI = <div class="custom-control custom-checkbox fade show"   key={element[attribute]}>
        <input type="checkbox" class="custom-control-input" id={element[attribute]} name="checkbox-stacked"
          onChange={(e) => this.handleFilterClick(e)} />
        <label class="custom-control-label text-monospace" for={element[attribute]} >{element[attribute]}</label>
      </div>
     filterElements.push(elementUI);
    };
    return (
      <div className="filter agencyFilter rounded">
        <div className="title">{filterTitle} :  </div>
        <div className="components">
          {filterElements}
        </div>
      </div>
    );
  }


  }
    componentDidMount() {
    $('.toast').toast('show');

    try {
      axios.get(this.state.host + "/public/agencies")  // get  the available agencies
        .then(res => {
          this.setState({ agencies: JSON.parse(JSON.stringify(res.data)) });
        });
      axios.get(this.state.host + "/public/categories")  // get the cavailable categories
        .then(res => {
          this.setState({ categories: JSON.parse(JSON.stringify(res.data)) });
        });
        axios.get(this.state.host + "/public/brands")  // get the cavailable categories
        .then(res => {
          this.setState({ brands: JSON.parse(JSON.stringify(res.data)) });
        });
        axios.get(this.state.host + "/public/models")  // get the cavailable categories
        .then(res => {
          this.setState({ models: JSON.parse(JSON.stringify(res.data)) });
        });


    } catch (e) {
      console.log(e);
    }
  }
  // handling filter Click 
  handleFilterClick(event){ 
    try {
      const filters = this.state.filters;
     const filter= event.currentTarget ;
     if( filter.checked) {

        filters.push(filter);  // on each click a filter is added to the array of filters  in the state
      } else {
        filters.pop(filter);
      }
    this.setState({
      filters : filters
    }) ; 
  }catch(exception) { 
    console.log(exception); 
  }
  
  }
  
 
 
  // filters
  agencyFilter() {
 
       return  this.filters(this.state.agencies ,"agency_code","Agency"); 
  }  
  categoryFilter() {
 
    return  this.filters(this.state.categories ,"name_","Category"); 

  }
  modelFilter() {
 
     return  this.filters(this.state.models ,"name_","Model"); 

  }
  brandFilter() {
 
    return  this.filters(this.state.brands ,"name_","Brand"); 

   }
 
  pagination() { // the pagination used to display the car cards 
    return (
      <ul className="pagination">
        <li className="page-item disabled" >  <a className="page-link" href="#"> previous</a></li>
        <li className="page-item"> <a className="page-link" href="#"> 1</a></li>
        <li className="page-item"> <a className="page-link" href="#"> 2</a></li>
        <li className="page-item"> <a className="page-link" href="#">Next </a></li>
      </ul>
    );
  }
    // handling the delete  element on the X icon of the filter element 
    deleteElement ( target){  // since the  filters array in the state containes nodes of the  filters 
       // and filters's title is extracted from the id of target
       const filters = this.state.filters; 
       const newFilters = []; 
       filters.forEach((filter) => { 
            if(filter.id == target.id) { 
                 filter.checked = false ;  // setting the checkbox to checked 
                 console.log(filter);
               // delete filters.pop(filter);
             } else {
            newFilters.push(filter); 
             }
       }); 
         this.setState({
         filters:newFilters  // since the the upper filter is related to state.filter 
                             // unsetting it from the filters array will automaticaly unset the GUI
       })
    }

   container(){ 
     try{ 
      return (
        <div className="container">
          <div className="row">
            <div className="col-sm-3" id="filters">
              <this.agencyFilter></this.agencyFilter>
                <this.categoryFilter></this.categoryFilter>
              <this.modelFilter></this.modelFilter>
             </div>
            <div className="col-sm-9">
               <UpperFilter filters = {this.state.filters} 
                  delete = {this.deleteElement}></UpperFilter>
                  <Vehicle host={this.state.host}></Vehicle>
                    <this.pagination></this.pagination>
            </div>
          </div>
        </div>
  
      );
     }catch(exception) {
       
         console.log(exception); 
     }
   }
  render() {
    $('.toast').toast('show');
    return(
    <this.container></this.container>
    )
    
  }
}
export default Home; 