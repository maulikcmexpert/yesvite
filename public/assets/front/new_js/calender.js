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

      const currentMonthButton = document.getElementsByClassName('calender_current_month');
    if (month.isSame(today, 'month')) {
      $(currentMonthButton).show(); // Show the button
    } else {
        $(currentMonthButton).hide(); // Show the button
    }

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
      const weekdays = document.createElement('div');
      weekdays.className = 'weekdays';
      ['S', 'M', 'T', 'W', 'T', 'F', 'S'].forEach(day => {
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

