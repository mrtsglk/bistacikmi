const holidays = [
	'2024-01-01', '2024-04-10', '2024-04-11', '2024-04-12', '2024-04-23', '2024-05-01', '2024-05-19', '2024-06-15', '2024-06-16'
    , '2024-06-17', '2024-06-18', '2024-06-19', '2024-07-15', '2024-08-30', '2024-10-29',
];

const earlyCloseDates = [
	'2024-04-09', '2024-10-28',
];

function bistStatus(currentDateISOString) {

    let now = moment(currentDateISOString).tz('Europe/Istanbul');
    let timeToWait;
    if (!isWorkingDay(now)) {
        let nextOpenDate = nextWorkingDay(now).hour(10).minute(0).second(0);
        timeToWait = moment.duration(nextOpenDate.diff(now, 'seconds'), 'seconds');
        return {currentStatus: 'KAPALI', nextStatus: 'AÇIK', timeUntilNextStatus: durationToString(timeToWait)};
    }

    var openDate = moment(now).hour(10).minute(0).second(0);
    if (now.isBefore(openDate)) {
        timeToWait = moment.duration(openDate.diff(now, 'seconds'), 'seconds');
        return {currentStatus: 'KAPALI', nextStatus: 'AÇIK', timeUntilNextStatus: durationToString(timeToWait)};
    }

    let closingHour = isEarlyClose(now)? 13 : 18;
    let closeDate = moment(now).hour(closingHour).minute(0).second(0);
    if (now.isAfter(closeDate)) {
        var nextOpenDate = nextWorkingDay(now).hour(10).minute(0).second(0);
        timeToWait = moment.duration(nextOpenDate.diff(now, 'seconds'), 'seconds');
        return {currentStatus: 'KAPALI', nextStatus: 'AÇIK', timeUntilNextStatus: durationToString(timeToWait)};
    }

    timeToWait = moment.duration(closeDate.diff(now, 'seconds'), 'seconds');
    return {currentStatus: 'AÇIK', nextStatus: 'KAPALI', timeUntilNextStatus: durationToString(timeToWait)};
}

function isWorkingDay(now) {
	if (isHoliday(now)) return false;
    var SUNDAY = 0;
    var SATURDAY = 6;
    return !(now.day() === SUNDAY || now.day() === SATURDAY);
}

function isHoliday(now) {
	for (var i = 0; i < holidays.length; i++) {
		let holiday = moment.tz(holidays[i], "Europe/Istanbul");
		if (now.isSame(holiday, 'day')) {
			return true;
		}
	}
	return false;
}

function isEarlyClose(now) {
	for (var i = 0; i < earlyCloseDates.length; i++) {
		let earlyCloseDate = moment.tz(earlyCloseDates[i], "Europe/Istanbul");
		if (now.isSame(earlyCloseDate, 'day')) {
			return true;
		}
	}
	return false;
}

function nextWorkingDay(now) {
    let candidate = moment(now).add(1, 'days');
    while(!isWorkingDay(candidate))
        candidate = candidate.add(1, 'days');
    return candidate;
}

function durationToString(duration) {
    return duration.days() + ' gün ' + duration.hours() + ' saat ' + duration.minutes() + ' dakika ' + duration.seconds() + ' saniye';
}

function myTimer() {
    let status = bistStatus();
    $('#status').html(status.currentStatus).addClass(status.currentStatus.toLowerCase());;
    $('#nextStatus').html(status.nextStatus);
    $('#timeUntilNextStatus').html(status.timeUntilNextStatus);
}
