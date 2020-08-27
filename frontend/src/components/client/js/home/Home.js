import React, { Component } from 'react';
import "../../../css/home.css";
import UpperFilter, { upperFilter } from "./UpperFilter";
import axios from 'axios';
import Vehicle from './Vehicle';

class Home extends Component {
  constructor(props) {
    super(props);

    this.state = {
      host: "http://localhost:8000",
      filters: {  "content"  : {"agency": [],"model": [],"category": [], "brand": [] }  , 
                   "ui" : {"agency": [],"model": [],"category": [], "brand": [] } // filter check box on the left 
               }, 
      filterNum: 0, // keeping track of the active elements 
      categories: [],
      vehicles: [],
      agencies: [],
      models: [],
      brands: [], 
      // handling the spinners download separately 
      agency_spinner_hidden : false,
      category_spinner_hidden : false,
      model_spinner_hidden : false,
      brand_spinner_hidden : false



    }
    this.container = this.container.bind(this);
    this.agencyFilter = this.agencyFilter.bind(this);
    this.handleFilterClick = this.handleFilterClick.bind(this); // this function used to handle the click the filter on the left of the page 
    this.categoryFilter = this.categoryFilter.bind(this);
    this.brandFilter = this.brandFilter.bind(this);
    this.modelFilter = this.modelFilter.bind(this);
    this.deleteElement = this.deleteElement.bind(this);
    this.filters = (elements, attribute, filterTitle, type , spinner_hidden) => {
    const filterElements = [];

      for (var i in elements) {
        if (!Number.isInteger(parseInt(i))) { //  since  every response that concerns a collection contains pagination 
          // this condition allow excluding the page , link , limit attribute 
          break;
        }
        var element = elements[i];
        var elementUI = <div class="custom-control custom-checkbox fade show" key={element[attribute]}>
          <input type="checkbox" class="custom-control-input" id={element[attribute]} name="checkbox-stacked"
            onChange={(e) => this.handleFilterClick(e, type)} />
          <label class="custom-control-label text-monospace" for={element[attribute]} >{element[attribute]}</label>
        </div>
        filterElements.push(elementUI);
      };
      const spinner = <div class="d-flex justify-content-center" > 
      <div class="spinner-border" role="status"     hidden = {spinner_hidden}> </div> </div>
      return (
        <div className="filter  rounded" id={type} >
          <div className="title">{filterTitle} :  </div>

          <div className="components">
            {filterElements}
            { spinner}

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
          this.setState({ agencies: JSON.parse(JSON.stringify(res.data)) , 
            agency_spinner_hidden :  true 
        });

        });
      axios.get(this.state.host + "/public/categories")  // get the cavailable categories
        .then(res => {
          this.setState({ categories: JSON.parse(JSON.stringify(res.data)) , 
            category_spinner_hidden :  true  });
        });
      axios.get(this.state.host + "/public/brands")  // get the cavailable categories
        .then(res => {
          this.setState({ brands: JSON.parse(JSON.stringify(res.data)) , 
            brand_spinner_hidden :  true });
        });
      axios.get(this.state.host + "/public/models")  // get the cavailable categories
        .then(res => {
          this.setState({ models: JSON.parse(JSON.stringify(res.data)), 
            model_spinner_hidden :  true  });

        });


    } catch (e) {
      console.log(e);
    }
  }
  // handling filter Click 
  handleFilterClick(event, type) {
    try {
      const filters = this.state.filters;
      const filtersUI= {"agency": [],"model": [],"category": [], "brand": [] } ; 
      const filter = event.currentTarget;
      if (filter.checked) {
        filters["content"][type].push(filter.id);  // on each click a filter is added to the array of filters  in the state
         filters["ui"][type].push(filter) ;  
        this.setState({  filterNum: this.state.filterNum + 1  });
      } else {
        filters["content"][type].pop(filter.id);
        filters["ui"][type].pop(filter);

        //filtersUI[type].push(filter);
        this.setState({  filterNum: this.state.filterNum - 1     });
        //filters["ui"] = filtersUI ; 

      }
       this.setState({
        filters: filters
      });
     } catch (exception) {
      console.log(exception);
    }

  }



  // filters
  agencyFilter() {

    return this.filters(this.state.agencies, "agency_code", "Agency", "agency" , this.state.agency_spinner_hidden);
  }
  categoryFilter() {

    return this.filters(this.state.categories, "name_", "Category", "category" , this.state.category_spinner_hidden);

  }
  modelFilter() {

    return this.filters(this.state.models, "name_", "Model", "model" , this.state.model_spinner_hidden);

  }
  brandFilter() {

    return this.filters(this.state.brands, "name_", "Brand", "brand" , this.state.brand_spinner_hidden);

  }

  // handling the delete  element on the X icon of the filter element 
  deleteElement(target) {  // since the  filters array in the state containes nodes of the  filters 
    // and filters's title is extracted from the id of target
    const filters = this.state.filters;
    const filtersTitle = ["agency", "model", "category", "brand"];
    const newFilters =  {  "content"  : {"agency": [],"model": [],"category": [], "brand": [] }  , 
                          "ui" : {"agency": [],"model": [],"category": [], "brand": [] } };
     filtersTitle.forEach((title) => {
         
        filters["content"][title].forEach((filter, i ) => {
        if (filter == target.id) {
              filters["ui"][title][i].checked = false;  // setting the checkbox to checked 
        } else {
           newFilters["content"][title].push(filter);
           console.log(filters["ui"][title][i]); 
           newFilters["ui"][title].push(filters["ui"][title][i]);

        }
      });
    });
    this.setState({
      filters: newFilters  ,// since the the upper filter is related to state.filter 
      // unsetting it from the filters array will automaticaly unset the GUI
       filterNum: this.state.filterNum - 1    

    }); 
   }

  container() {
    try {
      return (
        <div className="container">
          <div className="row">
            <div className="col-sm-3" id="filters">
              <this.agencyFilter></this.agencyFilter>
              <this.categoryFilter></this.categoryFilter>
              <this.modelFilter></this.modelFilter>
              <this.brandFilter></this.brandFilter>
            </div>
            <div className="col-sm-9">
              <UpperFilter filters={this.state.filters}
                delete={this.deleteElement}></UpperFilter>
              <Vehicle filters={this.state.filters} host={this.state.host}
                filterNum={this.state.filterNum} ></Vehicle>
            </div>
          </div>
        </div>

      );
    } catch (exception) {

      console.log(exception);
    }
  }
  render() {
    $('.toast').toast('show');
    return (
      <this.container></this.container>
    )

  }
}
export default Home; 