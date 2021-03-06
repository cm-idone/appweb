'use strict';

$(document).ready(function()
{
    var counter = $('#counter');

    if (counter)
    {
        var date = new Date(counter.data('date'));
        var initial_seconds = 1000;
        var initial_minutes = initial_seconds * 60;
        var initial_hours = initial_minutes * 60;
        var initial_days = initial_hours * 24;
        var interval = setInterval(function()
        {
            var today = new Date(moment().tz(counter.data('time-zone')).format('YYYY-MM-DD HH:mm:ss'));
            var difference = date - today;

            if (difference <= 0)
            {
                counter.html('00:00:00');

                clearInterval(interval);
            }
            else
            {
                var final_seconds = Math.floor((difference % initial_minutes) / initial_seconds);
                final_seconds = (final_seconds <= 9) ? '0' + final_seconds : final_seconds;

                var final_minutes = Math.floor((difference % initial_hours) / initial_minutes);
                final_minutes = (final_minutes <= 9) ? '0' + final_minutes : final_minutes;

                var final_hours = ((Math.floor(difference / initial_days) * 24) + Math.floor((difference % initial_days) / initial_hours));
                final_hours = (final_hours <= 9) ? '0' + final_hours : final_hours;

                counter.html(final_hours + ':' + final_minutes + ':' + final_seconds);
            }
        }, 1000);
    }
});
