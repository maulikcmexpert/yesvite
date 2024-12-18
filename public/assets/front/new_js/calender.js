!function () {
  var today = moment();

  function Calendar(selector, events, startMonth, totalMonths) {
      this.el = document.querySelector(selector);
      this.events = events; // Store the events
      this.currentMonth = moment(); // Current month to show
      this.startMonth = moment(startMonth, 'YYYY-MM'); // Save the starting month
      this.maxMonth = this.startMonth.clone().add(totalMonths - 1, 'months'); // Calculate max month
      this.totalMonths = totalMonths;
      this.draw();
  }

  Calendar.prototype.draw = function () {
    this.el.innerHTML = ''; // Clear previous content

    const nav = document.createElement('div');
    nav.className = 'calendar-nav';
    
    const prevButton = createElement('button', 'prev', '<i class="fas fa-chevron-left"></i>'); // Font Awesome left arrow
    const nextButton = createElement('button', 'next', '<i class="fas fa-chevron-right"></i>'); // Font Awesome right arrow
    

    
    // Disable prev button if current month is the start month
    if (this.currentMonth.isSame(this.startMonth, 'month')) {
        prevButton.disabled = true;
    }

    // Disable next button if current month is the max month
    if (this.currentMonth.isSame(this.maxMonth, 'month')) {
        nextButton.disabled = true;
    }

    prevButton.onclick = () => this.prevMonth();
    nextButton.onclick = () => this.nextMonth();

    nav.appendChild(prevButton);
    nav.appendChild(nextButton);

    this.el.appendChild(nav);

    // Draw the current month
    this.drawMonth(this.currentMonth);
  };

  Calendar.prototype.drawMonth = function (month) {
      const monthContainer = document.createElement('div');
      monthContainer.className = 'month';
      monthContainer.setAttribute('data-year_month',month.format('MM YYYY'));


      // Month Title
      const title = document.createElement('h3');
      title.innerText = month.format('MMMM YYYY');
      monthContainer.appendChild(title);

      // Weekdays
      const weekdays = document.createElement('div');
      weekdays.className = 'weekdays';
      ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
          const weekday = document.createElement('div');
          weekday.className = 'day';
          weekday.innerText = day;
          weekdays.appendChild(weekday);
      });
      monthContainer.appendChild(weekdays);

      // Days
      const startDay = month.clone().startOf('month').day(); // Starting weekday
      const totalDays = month.daysInMonth();

      let week = document.createElement('div');
      week.className = 'week';
      for (let i = 0; i < startDay; i++) {
          const emptyDay = document.createElement('div');
          emptyDay.className = 'day other';
          week.appendChild(emptyDay);
      }

      for (let day = 1; day <= totalDays; day++) {
          const currentDay = month.clone().date(day);
          const dayDiv = document.createElement('div');
          dayDiv.className = `day ${today.isSame(currentDay, 'day') ? 'today' : ''}`;
          dayDiv.setAttribute('data-date', currentDay.format('YYYY-MM-DD'));
          dayDiv.innerHTML  = `<span class="active-day">${day}</span>`;

          // Highlight events
          const eventsForDay = this.events.filter(event =>
              moment(event.date).isSame(currentDay, 'day')
          );
          if (eventsForDay.length) {
            const spansContainer = document.createElement('div');
            spansContainer.className = 'day-events';
            const maxToShow = Math.min(4, eventsForDay.length);
        
            eventsForDay.slice(0, maxToShow).forEach(event => {
                const span = document.createElement('span');
                span.className = event.color; 
                span.title = event.title; 
                spansContainer.appendChild(span);
            });
        
            dayDiv.appendChild(spansContainer);
        }

          week.appendChild(dayDiv);

          // Handle week break
          if (currentDay.day() === 6 || day === totalDays) {
              monthContainer.appendChild(week);
              week = document.createElement('div');
              week.className = 'week';
          }
      }

      this.el.appendChild(monthContainer);
  };

  Calendar.prototype.prevMonth = function () {
      this.currentMonth.subtract(1, 'months');
      this.el.innerHTML = ''; // Clear previous content
      this.draw(); // Redraw the calendar with the updated month
  };

  Calendar.prototype.nextMonth = function () {
      this.currentMonth.add(1, 'months');
      this.el.innerHTML = ''; // Clear previous content
      this.draw(); // Redraw the calendar with the updated month
  };

  // Create a calendar starting from a specific month
  const events = JSON.parse($('#calender_json').val());
  console.log(events);
  var startmonths = $('#startmonths').val();
  var diffmonth = parseInt($('#diffmonth').val());
  var totalmonths = parseInt($('#totalmonths').val()) + diffmonth;

  // Initialize the calendar
  const calendar = new Calendar('.calendar', events, startmonths, totalmonths);

  function createElement(tag, className, inner) {
    var el = document.createElement(tag);
    if (className) el.className = className;
    if (inner) el.innerHTML = inner;
    return el;
  }

}();

// // $(document).ready(function(){
 
  
//   // });
//   !function() {
  
//     var today = moment();
  
//     // function Calendar(selector, events) {
//       // console.log(this);
      
// //       this.el = document.querySelector(selector);
// //       this.events = events;
// //       this.current = moment().date(1);
// //       this.draw();
// //       var current = document.querySelector('.today');
// //       if(current) {
// //         var self = this;
// //         window.setTimeout(function() {
// //           self.openDay(current);
// //         }, 500);
// //       }
// //     }
// function Calendar(selector, events, startDate, endDate) {   
//   this.el = document.querySelector(selector);
//   this.events = events;
//   this.current = moment().date(1);
//   this.startDate = startDate || moment();  // default to today if not provided
//   this.endDate = endDate || moment().add(24, 'months');  // default to 24 months from today if not provided
//   this.draw();
// }
//     Calendar.prototype.draw = function() {
//       //Create Header
//       this.drawHeader();
  
//       //Draw Month
//       this.drawMonth();
  
//       this.drawLegend();
//     }
  
//     Calendar.prototype.drawHeader = function() {
//       var self = this;
//       if(!this.header) {
//         //Create the header elements
//         this.header = createElement('div', 'header');
//         this.header.className = 'header';
  
//         this.title = createElement('h1');
  
//         var right = createElement('span', 'right calender-right-swipe');
//         right.classList.add('fa', 'fa-chevron-right'); // Adds the Font Awesome right-chevron icon
//         right.addEventListener('click', function() { self.nextMonth(); });

//         var left = createElement('span', 'left calender-left-swipe');
//         left.classList.add('fa', 'fa-chevron-left'); // Adds the Font Awesome left-chevron icon
//         left.addEventListener('click', function() { self.prevMonth(); });
  
//         //Append the Elements
//         this.header.appendChild(this.title); 
//         this.header.appendChild(right);
//         this.header.appendChild(left);
//         this.el.appendChild(this.header);
//       }
  
//       this.title.innerHTML = this.current.format('MMMM YYYY');
//     }
  
  
   
  
//     Calendar.prototype.drawMonth = function() {
//       var self = this;
      
//       this.events.forEach(function(ev) {
//        ev.date = self.current.clone().date(Math.random() * (29 - 1) + 1);
//       });
      
      
//       if(this.month) {
   
//         this.oldMonth = this.month;
//         this.oldMonth.className = 'month out ' + (self.next ? 'next' : 'prev');
//         this.oldMonth.addEventListener('webkitAnimationEnd', function() {
//           self.oldMonth.parentNode.removeChild(self.oldMonth);
//           self.month = createElement('div', 'month');
//           self.backFill();
      
//             self.currentMonth();
         
//           self.fowardFill();
//           self.el.appendChild(self.month);
//           window.setTimeout(function() {
//             self.month.className = 'month in ' + (self.next ? 'next' : 'prev');
//           }, 16);
//         });
//       } else {
//           this.month = createElement('div', 'month');
//           this.el.appendChild(this.month);
//           this.backFill();
          
//           this.currentMonth();
          
//           this.fowardFill();
//           this.month.className = 'month new';
//       }
//     }
  
//     Calendar.prototype.backFill = function() {
//       var clone = this.current.clone();
//       var dayOfWeek = clone.day();
  
//       if(!dayOfWeek) { return; }
  
//       clone.subtract('days', dayOfWeek+1);
  
//       for(var i = dayOfWeek; i > 0 ; i--) {
//         this.drawDay(clone.add('days', 1));
//       }
//     }
  
//     Calendar.prototype.fowardFill = function() {
//       var clone = this.current.clone().add('months', 1).subtract('days', 1);
//       var dayOfWeek = clone.day();
  
//       if(dayOfWeek === 6) { return; }
  
//       for(var i = dayOfWeek; i < 6 ; i++) {
//         this.drawDay(clone.add('days', 1));
//       }
//     }
  
//     Calendar.prototype.currentMonth = function() {
//       var clone = this.current.clone();
  
//       while(clone.month() === this.current.month()) {
//         this.drawDay(clone);
//         clone.add('days', 1);
//       }
//     }
  
//     Calendar.prototype.getWeek = function(day) {
//       if(!this.week || day.day() === 0) {
//         this.week = createElement('div', 'week');
//         this.month.appendChild(this.week);
//       }
//     }
  
//     var i = 0;
//     Calendar.prototype.drawDay = function(day) {
//       var self = this;
//       this.getWeek(day);
  
//       //Outer Day
//       var outer = createElement('div', this.getDayClass(day));
//       outer.addEventListener('click', function() {
//         self.openDay(this);
//       });
  
//       //Day Name
//       var name = createElement('div', 'day-name', day.format('ddd'));
//       // var name ="";
  
  
//       //Day Number
//       var number = createElement('div', 'day-number', day.format('DD'));
  
  
//       //Events
//       var events = createElement('div', 'day-events');
//       this.drawEvents(day, events);
//       if(i<7){
//         outer.appendChild(name);
//         i++;
//       }
//       outer.appendChild(number);
//       outer.appendChild(events);
//       this.week.appendChild(outer);
//     }
  
//     Calendar.prototype.drawEvents = function(day, element) {
//       if(day.month() === this.current.month()) {
//         var todaysEvents = this.events.reduce(function(memo, ev) {
//           if(ev.date.isSame(day, 'day')) {
//             memo.push(ev);
//           }
//           return memo;
//         }, []);
  
//         todaysEvents.forEach(function(ev) {
//           var evSpan = createElement('span', ev.color);
//           element.appendChild(evSpan);
//         });
//       }
//     }
  
//     Calendar.prototype.getDayClass = function(day) {
//       classes = ['day'];
//       if(day.month() !== this.current.month()) {
//         classes.push('other');
//       } else if (today.isSame(day, 'day')) {
//         classes.push('today');
//       }
//       return classes.join(' ');
//     }
  
//     Calendar.prototype.openDay = function(el) {
//       var details, arrow;
//       var dayNumber = +el.querySelectorAll('.day-number')[0].innerText || +el.querySelectorAll('.day-number')[0].textContent;
//       var day = this.current.clone().date(dayNumber);
  
//       var currentOpened = document.querySelector('.details');
  
//       //Check to see if there is an open detais box on the current row
//       if(currentOpened && currentOpened.parentNode === el.parentNode) {
//         details = currentOpened;
//         arrow = document.querySelector('.arrow');
//       } else {
//         //Close the open events on differnt week row
//         //currentOpened && currentOpened.parentNode.removeChild(currentOpened);
//         if(currentOpened) {
//           currentOpened.addEventListener('webkitAnimationEnd', function() {
//             currentOpened.parentNode.removeChild(currentOpened);
//           });
//           currentOpened.addEventListener('oanimationend', function() {
//             currentOpened.parentNode.removeChild(currentOpened);
//           });
//           currentOpened.addEventListener('msAnimationEnd', function() {
//             currentOpened.parentNode.removeChild(currentOpened);
//           });
//           currentOpened.addEventListener('animationend', function() {
//             currentOpened.parentNode.removeChild(currentOpened);
//           });
//           currentOpened.className = 'details out';
//         }
  
//         //Create the Details Container
//         details = createElement('div', 'details in');
  
//         //Create the arrow
//         var arrow = createElement('div', 'arrow');
  
//         //Create the event wrapper
  
//         details.appendChild(arrow);
//         el.parentNode.appendChild(details);
//       }
  
//       var todaysEvents = this.events.reduce(function(memo, ev) {
//         if(ev.date.isSame(day, 'day')) {
//           memo.push(ev);
//         }
//         return memo;
//       }, []);
  
//       this.renderEvents(todaysEvents, details);
  
//       arrow.style.left = el.offsetLeft - el.parentNode.offsetLeft + 27 + 'px';
//     }
  
//     Calendar.prototype.renderEvents = function(events, ele) {
//       //Remove any events in the current details element
//       var currentWrapper = ele.querySelector('.events');
//       var wrapper = createElement('div', 'events in' + (currentWrapper ? ' new' : ''));
  
//       events.forEach(function(ev) {
//         var div = createElement('div', 'event');
//         var square = createElement('div', 'event-category ' + ev.color);
//         var span = createElement('span', '', ev.eventName);
  
//         div.appendChild(square);
//         div.appendChild(span);
//         wrapper.appendChild(div);
//       });
  
//       if(!events.length) {
//         var div = createElement('div', 'event empty');
//         var span = createElement('span', '', 'No Events');
  
//         div.appendChild(span);
//         wrapper.appendChild(div);
//       }
  
//       if(currentWrapper) {
//         currentWrapper.className = 'events out';
//         currentWrapper.addEventListener('webkitAnimationEnd', function() {
//           currentWrapper.parentNode.removeChild(currentWrapper);
//           ele.appendChild(wrapper);
//         });
//         currentWrapper.addEventListener('oanimationend', function() {
//           currentWrapper.parentNode.removeChild(currentWrapper);
//           ele.appendChild(wrapper);
//         });
//         currentWrapper.addEventListener('msAnimationEnd', function() {
//           currentWrapper.parentNode.removeChild(currentWrapper);
//           ele.appendChild(wrapper);
//         });
//         currentWrapper.addEventListener('animationend', function() {
//           currentWrapper.parentNode.removeChild(currentWrapper);
//           ele.appendChild(wrapper);
//         });
//       } else {
//         ele.appendChild(wrapper);
//       }
//     }
  
//     Calendar.prototype.drawLegend = function() {
//       var legend = createElement('div', 'legend');
//       var calendars = this.events.map(function(e) {
//         return e.calendar + '|' + e.color;
//       }).reduce(function(memo, e) {
//         if(memo.indexOf(e) === -1) {
//           memo.push(e);
//         }
//         return memo;
//       }, []).forEach(function(e) {
//         var parts = e.split('|');
//         var entry = createElement('span', 'entry ' +  parts[1], parts[0]);
//         legend.appendChild(entry);
//       });
//       this.el.appendChild(legend);
//     }
  
//     Calendar.prototype.nextMonth = function() {
//       i=0;
//       this.current.add('months', 1);
//       this.next = true;
//       this.draw();
//     }
  
//     Calendar.prototype.prevMonth = function() {
//       i=0;
//       this.current.subtract('months', 1);
//       this.next = false;
//       this.draw();
//     }
//     for (let index = 0; index < 3; index++) {
//       window.Calendar = Calendar;
//     }
  
//     function createElement(tagName, className, innerText) {
//       var ele = document.createElement(tagName);
//       if(className) {
//         ele.className = className;
//       }
//       if(innerText) {
//         ele.innderText = ele.textContent = innerText;
//       }
//       return ele;
//     }
//   }();
  
//   !function() {
//     var data = [
//       { eventName: 'Lunch Meeting w/ Mark', calendar: 'Work', color: 'orange' },
//       { eventName: 'Interview - Jr. Web Developer', calendar: 'Work', color: 'orange' },
//       { eventName: 'Demo New App to the Board', calendar: 'Work', color: 'orange' },
//       { eventName: 'Dinner w/ Marketing', calendar: 'Work', color: 'orange' },
  
//       { eventName: 'Game vs Portalnd', calendar: 'Sports', color: 'blue' },
//       { eventName: 'Game vs Houston', calendar: 'Sports', color: 'blue' },
//       { eventName: 'Game vs Denver', calendar: 'Sports', color: 'blue' },
//       { eventName: 'Game vs San Degio', calendar: 'Sports', color: 'blue' },
  
//       { eventName: 'School Play', calendar: 'Kids', color: 'yellow' },
//       { eventName: 'Parent/Teacher Conference', calendar: 'Kids', color: 'yellow' },
//       { eventName: 'Pick up from Soccer Practice', calendar: 'Kids', color: 'yellow' },
//       { eventName: 'Ice Cream Night', calendar: 'Kids', color: 'yellow' },
  
//       { eventName: 'Free Tamale Night', calendar: 'Other', color: 'green' },
//       { eventName: 'Bowling Team', calendar: 'Other', color: 'green' },
//       { eventName: 'Teach Kids to Code', calendar: 'Other', color: 'green' },
//       { eventName: 'Startup Weekend', calendar: 'Other', color: 'green' }
//     ];
  
    
  
//     function addDate(ev) {
      
//     }
    
   
//       var calendar = new Calendar('.calendar', data);
    
  
   
  
//   }();
  
  
!function () {
  var today = moment();


  const events = JSON.parse($('#calender_json').val());

  const monthEventCount = events.reduce((acc, event) => {
    const [year, month] = event.date.split('-');
    const key = `${year}-${month}`; 
    acc[key] = (acc[key] || 0) + 1;

    return acc;
}, {});

console.log(monthEventCount);

  function Calendar(selector, events, startMonth, totalMonths) {
      this.el = document.querySelector(selector);
      this.events = events; // Store the events
      this.startMonth = moment(startMonth, 'YYYY-MM'); // Start month as moment object
      this.totalMonths = totalMonths; // Number of months to render
      this.draw();
  }

  // Calendar.prototype.draw = function () {
  //     this.el.innerHTML = ''; // Clear previous content
  //     for (let i = 0; i < this.totalMonths; i++) {
  //         const currentMonth = this.startMonth.clone().add(i, 'months');
  //         this.drawMonth(currentMonth);
  //     }
  // };

  Calendar.prototype.draw = function () {
    this.el.innerHTML = ''; // Clear previous content

    // Add the heading "ALL Events"
    // const heading = document.createElement('h2');
    // heading.className = 'calendar-heading'; // Optional: Add a class for styling
    // heading.innerText = 'All Events';
    // this.el.appendChild(heading);
    // const weekdays = document.createElement('div');
    // weekdays.className = 'weekdays';
    // ['S', 'M', 'T', 'W', 'T', 'F', 'S'].forEach(day => {
    //     const weekday = document.createElement('div');
    //     weekday.className = 'day';
    //     weekday.innerText = day;
    //     weekdays.appendChild(weekday);
    // });
    // this.el.appendChild(weekdays);
    for (let i = 0; i < this.totalMonths; i++) {
        const currentMonth = this.startMonth.clone().add(i, 'months');
        this.drawMonth(currentMonth);
    }
};


  Calendar.prototype.drawMonth = function (month) {
      const monthContainer = document.createElement('div');
      monthContainer.className = 'month';
      monthContainer.setAttribute('data-month',  month.format('MMMM YYYY'));

      // Month Title
      const title = document.createElement('h3');
      title.innerText = month.format('MMMM YYYY');
      title.innerText = month.format('MMMM YYYY');
      monthContainer.appendChild(title);

      const monthKey = month.format('YYYY-MM');
      const eventCount = monthEventCount[monthKey] || 0; 
      const eventscount = document.createElement('h3');
      eventscount.innerText = `${eventCount} event${eventCount !== 1 ? 's' : ''}`;
      monthContainer.appendChild(eventscount);

      // Weekdays
    //   const weekdays = document.createElement('div');
    //   weekdays.className = 'weekdays';
    //   ['S', 'M', 'T', 'W', 'T', 'F', 'S'].forEach(day => {
    //       const weekday = document.createElement('div');
    //       weekday.className = 'day';
    //       weekday.innerText = day;
    //       weekdays.appendChild(weekday);
    //   });
    //   monthContainer.appendChild(weekdays);

      // Days
      const startDay = month.clone().startOf('month').day(); // Starting weekday
      const totalDays = month.daysInMonth();

      let week = document.createElement('div');
      week.className = 'week';
      for (let i = 0; i < startDay; i++) {
          const emptyDay = document.createElement('div');
          emptyDay.className = 'day other';
          week.appendChild(emptyDay);
      }

      for (let day = 1; day <= totalDays; day++) {
          const currentDay = month.clone().date(day);
          const dayDiv = document.createElement('div');
          dayDiv.className = `day ${today.isSame(currentDay, 'day') ? 'today' : ''}`;
          dayDiv.setAttribute('data-date', currentDay.format('YYYY-MM-DD'));
          dayDiv.innerHTML = today.isSame(currentDay, 'day') 
          ? `<h6>Today</h6> ${day}` 
          : `${day}`;
                
        //   if (dayDiv.classList.contains('today')) {
        //     const todaySpan = document.createElement('span');
        //     todaySpan.innerText = 'today'; // Text inside the span
        //     dayDiv.appendChild(todaySpan); // Add the span to the div
        // }   
          // Highlight events
          const eventsForDay = this.events.filter(event =>
              moment(event.date).isSame(currentDay, 'day')
          );
          if (eventsForDay.length) {
            const spansContainer = document.createElement('div');
            spansContainer.className = 'day-events';
            const maxToShow = Math.min(4, eventsForDay.length);
        
            eventsForDay.slice(0, maxToShow).forEach(event => {
                const span = document.createElement('span');
                span.className = event.color; 
                span.title = event.title; 
                spansContainer.appendChild(span);
            });
        
            // If there are more than 4 events, add an extra span
            // if (eventsForDay.length > 4) {
            //     const extraSpan = document.createElement('span');
            //     extraSpan.className = 'extra-events'; // Add a specific class for styling
            //     extraSpan.innerText = `+${eventsForDay.length - 4}`; // Show the count of extra events
            //     spansContainer.appendChild(extraSpan);
            // }
        
            dayDiv.appendChild(spansContainer);
        }
        

          week.appendChild(dayDiv);

          // Handle week break
          if (currentDay.day() === 6 || day === totalDays) {
              monthContainer.appendChild(week);
              week = document.createElement('div');
              week.className = 'week';
          }
      }

      this.el.appendChild(monthContainer);
  };

  // Initialize calendar
  // const events = [
  //     { date: '2024-09-15', title: 'Event 1', color: 'orange' },
  //     { date: '2024-09-15', title: 'Event 2', color: 'blue' },
  //     { date: '2024-09-15', title: 'Event 3', color: 'green' },
  //     { date: '2024-10-16', title: 'Event 4', color: 'purple' },
  //     { date: '2024-10-16', title: 'Event 5', color: 'yellow' },
  //     { date: '2024-11-16', title: 'Event 6', color: 'orange' },
  //     { date: '2024-08-01', title: 'Event 6', color: 'orange' },
  //     { date: '2024-09-10', title: 'Event 7', color: 'pink' },
  //     { date: '2024-08-25', title: 'Event 8', color: 'teal' }
  // ];
  // var events_calender_json=
  console.log(events);
  var startmonths=$('#startmonths').val();
  var diffmonth=parseInt($('#diffmonth').val());
  var totalmonths=parseInt($('#totalmonths').val())+diffmonth;

  // Create an object to store the count of events for each month

  // console.log($startmonths+' '+$totalmonths);
  // Create a calendar starting from June 2024 for 28 months (till October 2025)
  const calendar = new Calendar('.responsive-calender-months', events, startmonths, totalmonths);
}();

