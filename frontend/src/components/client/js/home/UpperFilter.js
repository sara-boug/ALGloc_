import React, { Component } from 'react';

// this function deals with upper bar  containning the  Filter  selected from the left bar 
function UpperFilter(props) { 

       const filters = props.filters; 
       const currentFilters = []; 
         filters.forEach((element) =>{
             var filter = element.filter; 
             var filterUI = 
               <div className="toast rounded-pill fade show" role="alert" aria-live="polite"
                aria-atomic="true" data-autohide="false"   id={filter.id} key={filter.id}  onClick={ (e)=>{ props.delete(e.currentTarget)}}>
                <div role="alert" aria-live="assertive" aria-atomic="true" 
                className="filter-name text-monospace"  >  {filter.id}</div>
                <button type="button" class="ml-2 mb-1 close" aria-label="Close">
                 <span aria-hidden="true" 
                >
     
                 &times;</span>
               </button>
             </div>
            currentFilters.push(filterUI);
          }); 
          // making sure that the filter toast are visible
          return (
           <div className="filter-buttons rounded">
             <div className="filter-title"> Filter :</div>
             <div className="upper-filters-container">
               {currentFilters}
             </div> 
           </div>
           
         );
     

     
   
}


export default UpperFilter ; 