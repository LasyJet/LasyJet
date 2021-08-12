class timeTable {
    // This class alow you to sum times hh:mm(1)+hh:mm(2)
    constructor(time1, time2) {
        this.time1 = time1;
        this.time2 = time2;
    }

    toMin(time) { //covert time from "hh:mm" to mimutes
        var tmpTime = time.split(":");
        var min = parseInt(tmpTime[0]) * 60 + parseInt(tmpTime[1]);
        return min;
    }

    sumTime() {
        var t1 = this.toMin(this.time1);
        var t2 = this.toMin(this.time2);
        var sumTime = t1 + t2;
        var hours = (sumTime - sumTime % 60) / 60;
        var mins = sumTime - hours * 60;
        return hours + ":" + mins;
    }
};


tm = new timeTable();

$(document).ready(function() {
    tm.time1 = "09:00";
    tm.time2 = "00:15";

    $("#content").html(tm.sumTime());
    console.log(tm.sumTime());

    tm.time1 = $("#content").html();
    tm.time2 = "00:10";

    var curTime = $("#content").html();
    $("#content").html(curTime + "<br>" + tm.sumTime());
    console.log(tm.sumTime());
});