import React, { Component } from 'react';

// this function deals with upper bar  containning the  Filter  selected from the left bar 
function UpperFilter(props) { 

       const filters = props.filters; 
           // making sure that the filter toast are visible
          var agencyFilter =  filter(filters["content"]["agency"] , props);
          var modelFilter =  filter(filters["content"]["model"] , props );
          var brandFilter = filter(filters["content"]["brand"] , props );
          var categoryFilter =  filter(filters["content"]["category"] , props ) ;
          var allFilters = agencyFilter.concat(modelFilter , brandFilter , categoryFilter); 
                  
          return (
           <div className="filter-buttons rounded">
             <div className="filter-title"> Filter :</div>
             <div className="upper-filters-container">
               { allFilters}
             </div> 
           </div>
           
         );
     

   
}

function  filter( filters , props ) { 
  const currentFilters = []; 
  
  filters.forEach((filter) =>{
       var filterUI = 
        <div className="toast rounded-pill fade show" role="alert" aria-live="polite"
         aria-atomic="true" data-autohide="false"   id={filter} key={filter}  onClick={ (e)=>{ props.delete(e.currentTarget)}}>
         <div role="alert" aria-live="assertive" aria-atomic="true" 
         className="filter-name text-monospace"  >  {filter}</div>
         <button type="button" class="ml-2 mb-1 close" aria-label="Close">
          <span aria-hidden="true" 
         >

          &times;</span>
        </button>
      </div>
     currentFilters.push(filterUI);
   }); 
  return currentFilters ; 
 }



export default UpperFilter ; 