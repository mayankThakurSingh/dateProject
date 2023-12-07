/**
* Example of the endpoint to be called
*/

Endpoint Url : {{localhost}}/api/date

Endpoint parameters : start_date & end_date (date format: d-m-y)

Endpoint method : POST

1. Insert the desired start and end date you want. If start and end date passes all the validation rules that are listed below :

    a. The dates are least two years apart but not more than five

    b. The start date cannot be a Sunday
    
    c. The start date cannot be greater than the end date

   then it will return the number of sundays between the two dates excluding any sunday that falls on or after the 28th of the month.