import React , {Component} from 'react' ; 
import axios from 'axios'; 

class  Vehicle  extends Component {
     constructor(props){ 
           super(props);
           this.state ={ 
                filters:this.props.filters, 
               host : this.props.host, 
               defaultvehicles : [] , // default vehicles to be displayed when no filter available
               vehicles :[], 
               vehiclesUI: [],  
               pages: null ,     // number of pagination item
               limit:null ,      // content limit of each pages for ex: 4 elements on each page
               total : null,     // the length of  all the collection to be displayed
               currentVehicles: []// array containes the vehicles to be displayed according to the pagination 
           }
           this.vehiclesArray =      this.vehiclesArray.bind(this); 
           this.setVehicleAgency =   this.setVehicleAgency.bind(this); 
           this.pagination =         this.pagination.bind(this);  // pagination is added to the vehicle class
           this.handlePagination =   this.handlePagination.bind(this);     
           this.displayVehicles =    this.displayVehicles.bind(this);  
           this.displayData = (url) => {   
            axios.get(url) 
            .then((res) => { 
              console.log(res);
             var data = res.data ;         
             this.setState({
                    vehicles: data , 
                    pages:  data["pages"],
                    limit:  data["limit"], 
                    total:  data["total"] 
 
               })
             }) .then(() => { 
               this.vehiclesArray(this.state.vehicles); 
               this.handlePagination(0); 
             }); 
           }; 

           this.transfer = ( filters ) => { 
                 const newArray = [ ]; 
                     filters.forEach(element => {
                        newArray.push('"' + element + '"'); 
                   });

                   return newArray; 
        
          }
    
               }    
      vehiclesArray(vehicles){ 
        const vehiclesUI = []; 
      for(var i in vehicles) { 
        if (!Number.isInteger(parseInt(i))) {  break ; } 
              var vehicle = vehicles[i]; 
              var vehicleUI = <div className="car-cards" key={vehicle["id"]} >    
                <img src={this.state.host + "/public/vehicle/"+ vehicle["id"]+ "/image"  }  
                  className="rounded-pill" />
                <div className="information">
                  <div className="left">
                    <div className="carTitle"> {vehicle["model"]["name_"]}
                    <div className="category-model text-monospace">
                      {vehicle["model"]["brand"]["name_"] +" " + vehicle["model"]["category"]["name_"]} </div> </div>
                    <div className="secondaryInfo">
                      <div> state : {vehicle["state_"]}</div>
                      <div> gearbox:  {vehicle["gearbox"]}</div>
                      <div> max passengers: { vehicle["passenger_number"]} </div>
                      <div> max suitcase : { vehicle["suitcase_number"] }</div>
                    </div>
                  </div>
                  <div className="right">
                    <i class="fas fa-plus-circle"></i>
                    <div className="price"> <p> Rental : {vehicle["rental_price"]} DA   </p>
                      <p>Inssurance :  {vehicle["inssurance_price"]} DA</p>
                    </div>
                     { this.setVehicleAgency(vehicle['agency']) }
                   </div>
                </div>
              </div>
      
           vehiclesUI.push(vehicleUI);
       
      } 
      this.setState({
          vehiclesUI:vehiclesUI 
      })
    }

       componentWillReceiveProps(prevProps ) { 
        if(this.props.filterNum>0) {
          console.log("yes"); 
            const filters  = this.state.filters ; // since the route parameter is expected to be json object
             const params = '{ "agency": ['+ this.transfer( filters["agency"]) + '],'+
                              ' "model"  : ['+ this.transfer( filters["model"]) + '],'+
                              ' "category"  : ['+ this.transfer( filters["category"]) + '],'+
                              ' "brand"  : ['+ this.transfer( filters["brand"]) + ']'+
                             '}'; 
           this.displayData(this.state.host + "/public/vehicles/"+params); 
       } else  { 
        console.log("No"); 

           this.setState({ 
             vehicles: this.state.defaultvehicles
             
           })
       }
       }
  
     componentDidMount(){ 
         axios.get(this.state.host + "/public/vehicles") 
         .then((res) => { 
             const  data = res.data ; 
             this.setState({
                    vehicles: data , 
                    defaultvehicles: data , // defaultvehicles  to be displayed when no filter available
                    // pagination parameters
                    pages:  data["pages"],
                    limit:  data["limit"], 
                    total:  data["total"] 
             })
         }).then(()=> {    
           this.vehiclesArray(this.state.vehicles);

              this.handlePagination(0);
 
     } ); 

    } 



 

   setVehicleAgency(agency) {   // the agency dropdown found in the right bottom of the agency card 
       return (
        <div className="btn-group dropright">
        <button type="button" className="btn  dropdown-toggle" id="vehicle-agency" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false"> Agency  </button>
        <div className="dropdown-menu dropdown-menu-left">
          <div className="dropdown-item">   <i class="fas fa-building"></i>
            <div className="text-monospace">  {agency["agency_code"]}</div> </div>
          <div className="dropdown-item">   <i class="fas fa-phone-alt"></i> 
          <div className="text-monospace"> {agency["phone_number"]}</div> </div>
          <div className="dropdown-item">  <i class="fas fa-at"></i>
           <div className="text-monospace"> {agency["email"]}</div> </div>
           <div className="dropdown-item">  <i class="fas fa-map-marker-alt"></i>
           <div className="text-monospace"> {agency["address"] + ", " +agency["city"]["name_"] }</div> </div>


        </div>
      </div>
       )

   }
   pagination() { // the pagination used to display the vehicles 
    // total number of pages items equals to the pages parameters
    var pagesItem  =[] ; 
    var pagesNum = this.state.pages; 
    for( var i= 0 ; i<pagesNum ; i++ ) { 
        var item=  <li className="page-item" key = {i} id={i}
          onClick={(e) => {this.handlePagination(e.currentTarget.id)}}> <a className="page-link"> {i+1}</a></li>
         pagesItem.push(item);
    }
    return (
      <ul className="pagination">
        <li className="page-item disabled" >  <a className="page-link" href="#"> previous</a></li>
         {pagesItem}
         <li className="page-item"> <a className="page-link" href="#">Next </a></li>
      </ul>
    );
  }
   handlePagination(elementId){ 
       const limit = this.state.limit;
       const n = (elementId *limit) + limit;
       const currentPages = [] ; 
       if (n < this.state.total) { 
            for(var i=elementId*limit  ; i < n  ; i++) { 
                   currentPages.push(this.state.vehiclesUI[i]) ; 
            }
        } else {  
          for(var i=elementId *limit ; i < this.state.total  ; i++) { 
               currentPages.push(this.state.vehiclesUI[i]) ; 
            }
          }
             this.setState({
                currentVehicles : currentPages
            })

            return currentPages ;  
            
   }
   displayVehicles(){  
          return  this.state.currentVehicles; 
   }

    render(){
     return (
         <div>
         <this.displayVehicles></this.displayVehicles>
         <this.pagination></this.pagination>
 
         </div>
       );
    }
  
  
}
export  default Vehicle ; 