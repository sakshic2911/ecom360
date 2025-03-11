var calendar = document.getElementById("calendar");
var monthAndYear = document.getElementById("monthAndYear");
var button = document.getElementById("appointment-button");
var lang = calendar.getAttribute('data-lang');

var months = "";
var days = "";

var monthDefault = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

var dayDefault = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

if (lang == "en") {
    months = monthDefault;
    days = dayDefault;
} else {
    months = monthDefault;
    days = dayDefault;
}

today = new Date();
currentMonth = today.getMonth();
currentYear = today.getFullYear();
// selectYear = document.getElementById("year");
// selectMonth = document.getElementById("month");

// createYear = generate_year_range(1970, 2050);
// document.getElementById("year").innerHTML = createYear;

var $dataHead = "<tr>";
for (dhead in days) {
    $dataHead += "<th data-days='" + days[dhead] + "'>" + days[dhead] + "</th>";
}
$dataHead += "</tr>";
document.getElementById("thead-month").innerHTML = $dataHead;
showCalendar(currentMonth, currentYear);
curMonYear = (currentMonth +1)+"-"+currentYear;

function getAppointmentSlot(){
    myFunctionLoaderCircular();
    var timezone = $("#timezone").val();
    $.ajax({
        url: `${baseUrl}/Client/getAppointmentSlot`,
        method: "GET",
        data: {
        timezone: timezone,
        },
        success: function(res) {
            slotData = Object.keys(JSON.parse(res));
            AppointmentData  = JSON.parse(res);
            showCalendar(currentMonth, currentYear);
        }
    })
}

function generate_year_range(start, end) {
    var years = "";
    for (var year = start; year <= end; year++) {
        years += "<option value='" + year + "'>" + year + "</option>";
    }
    return years;
}


function next() {
    currentYear = (currentMonth === 11) ? currentYear + 1 : currentYear;
    currentMonth = (currentMonth + 1) % 12;
    showCalendar(currentMonth, currentYear);
}

function previous() {
    currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
    currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
    showCalendar(currentMonth, currentYear);
}

function jump() {
    // currentYear = parseInt(selectYear.value);
    // currentMonth = parseInt(selectMonth.value);
    showCalendar(currentMonth, currentYear);
}

function showCalendar(month, year) {
    todayCur = new Date();
    currentMonthCur = todayCur.getMonth();
    currentYearCur = todayCur.getFullYear();

    if(currentMonthCur  >= month && currentYearCur >= year){
        document.getElementById("previous").style.display = "none";
    }else{
        document.getElementById("previous").style.display = "block";
    }

    var lastKey = slotData[slotData.length - 1];
    var date = new Date(lastKey);
    var lastMonth = date.getMonth() + 1; 
    var lastYear = date.getFullYear();

    if(lastMonth  <= (month + 1) && lastYear <= year){
        document.getElementById("next").style.display = "none";
    }else{
        document.getElementById("next").style.display = "block";
    }
    
    var firstKey = getFirstDateOfMonth(slotData, month);
    var fisrtdate = new Date(firstKey);
    
    var firstDay = ( new Date( year, month ) ).getDay();
    tbl = document.getElementById("calendar-body");
    tbl.innerHTML = "";
    monthAndYear.innerHTML = months[month] + " " + year;
    // selectYear.value = year;
    // selectMonth.value = month;

    var date = 1;
    for ( var i = 0; i < 6; i++ ) {
        var row = document.createElement("tr");
        for ( var j = 0; j < 7; j++ ) {

            if ( i === 0 && j < firstDay ) {
                cell = document.createElement( "td" );
                cellText = document.createTextNode("");
                cell.appendChild(cellText);
                row.appendChild(cell);
            } else if (date > daysInMonth(month, year)) {
                break;
            } else {
                cell = document.createElement("td");
                cell.setAttribute("data-date", date);
                cell.setAttribute("data-month", month + 1);
                cell.setAttribute("data-year", year);
                cell.setAttribute("data-month_name", months[month]);
                cell.className = "date-picker";
                cell.innerHTML = "<span>" + date + "</span>";
                dates = year + "-" + ((month + 1) < 10 ? '0' + (month + 1) : (month + 1)) + "-" + (date < 10 ? '0' + date : date);
                if(slotData.includes(dates)) {
                    cell.classList.add("active-slot");
                    cell.onclick = (function(date, month, year) {
                        return function() {
                            var allCells = document.querySelectorAll(".date-picker");
                            allCells.forEach(function (cell) {
                                cell.classList.remove("active");
                            });
                            this.classList.add("active");
                            getslot(date, month + 1, year);
                        };
                    })(date, month, year);
                } else{
                    cell.classList.add("disabled");
                }
                if ( date === today.getDate() && year === today.getFullYear() && month === today.getMonth() ) {
                    cell.className = "date-picker selected";
                }
                
                if ( date === fisrtdate.getDate() && year === fisrtdate.getFullYear() && month === fisrtdate.getMonth() ) {
                    cell.classList.add("active");
                }
                if (date < today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
                    cell.classList.add("disabled");
                }                  
                row.appendChild(cell);
                date++;
            }
        }
        tbl.appendChild(row);
    }
    getslot(fisrtdate.getDate(),(fisrtdate.getMonth() + 1),fisrtdate.getFullYear());
}

function daysInMonth(iMonth, iYear) {
    return 32 - new Date(iYear, iMonth, 32).getDate();
}

function getslot(date,month,year){
    var button = document.getElementById("appointment-button");
    button.classList.add("d-none");
    var dates = year + "-" + (month < 10 ? '0' + month : month) + "-" + (date < 10 ? '0' + date : date);
    var slotsForDate = AppointmentData[dates].slots;
    var timeSlotsContainer = document.getElementById("slot");
    timeSlotsContainer.innerHTML = "";

    slotsForDate.forEach(function(slot,index) {
        var time = slot.substring(11, 16);
        var li = document.createElement("li");
        li.className = "widgets-time-slot mb-4";
        var span = document.createElement("span");
        var date = new Date(slot);
        
        var timezoneId = document.getElementById("timezone");
        var timezone = timezoneId.value;
        var selectedOption = timezoneId.options[timezoneId.selectedIndex];
        var gmt = slot.substring(19);//selectedOption.getAttribute('data-gmt');
        var formattedTime = date.toLocaleTimeString('en-US', {timeZone: timezone, hour: '2-digit', minute: '2-digit'});
        
        span.textContent = formattedTime;
        li.appendChild(span);
        li.onclick = function() {
            button.classList.remove("d-none");
            button.classList.add("d-block");
            span.classList.add("clicked");
            
            var allSpans = document.querySelectorAll(".widgets-time-slot span");
            allSpans.forEach(function(spanElement) {
                if (spanElement !== span) {
                    spanElement.classList.remove("clicked");
                }
            });
            if (span.classList.contains("clicked")) {
                var clickedValue = span.textContent;
                button.setAttribute("data-time", clickedValue);

                button.onclick = function() {
                    bookAppointment(dates,clickedValue,timezone,gmt)
                }
            }
        };
        timeSlotsContainer.appendChild(li);
    });
}

function slotprevious(currentMonth,currentYear) {
    showCalendar(currentMonth, currentYear);
    $('.calendar').css('display','block');
    $('.slot').css('display','none');
    var button = document.getElementById("appointment-button");
    button.classList.remove("d-block");
    button.classList.add("d-none");
}

function bookAppointment(dates,slot,timezone,gmt){
    myFunctionLoaderCircular();
    $('#appointment-button').attr('disabled', true);
    $.ajax({
        url: `${baseUrl}/Client/bookAppointment`,
        method: "GET",
        data: {
        dates: dates,
        slot: slot,
        timezone:timezone,
        gmt:gmt
        },
        success: function(res) {
            if(res == 1){
                location.reload()
            }
        }
    })
}
var myVarLoader;

function myFunctionLoaderCircular() {
    var wrapper = document.querySelector('.wrapper');
    wrapper.style.opacity = '0.4';
    wrapper.style.filter = 'blur(3px)';        
    myVarLoader = setTimeout(function() {
        document.getElementById("loaderCircular").style.display = "none";
        wrapper.style.opacity = '';
        wrapper.style.filter = '';
    }, 4000);
    document.getElementById("loaderCircular").style.display = "block";
}


function getFirstDateOfMonth(datesArray, targetMonth) {
    targetMonth = targetMonth + 1;

    for (var i = 0; i < datesArray.length; i++) {
        var date = new Date(datesArray[i]);
        if (date.getMonth() + 1 === targetMonth) { 
            return datesArray[i];
        }
    }
    return null;
}

