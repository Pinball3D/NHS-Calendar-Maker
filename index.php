<!DOCTYPE HTML>
<html lang="en">

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CL33W318FF"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());

        gtag('config', 'G-CL33W318FF');
    </script>
    <title> NHS Calendar-Maker - Newtown High School </title>
    <meta name="Author" content="Devin Matte">
    <meta name="msapplication-TileColor" content="#3A7BFF">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#3A7BFF">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="apple-touch-icon" sizes="57x57" href="images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/gif" sizes="192x192" href="images/favicon/192x192.gif">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon/favicon-16x16.png">

    <link rel="manifest" href="manifest.json">

    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css"/><![endif]-->
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css"/><![endif]-->

    <link rel="stylesheet" href="assets/css/uikit.almost-flat.css" />
    <link rel="stylesheet" href="assets/css/components/datepicker.almost-flat.min.css" />
    <link rel='stylesheet' href='assets/fullcalendar/fullcalendar.min.css' />
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/calendar-maker.css" />

    <!-- Scripts -->
    <script src="assets/fullcalendar/lib/jquery.min.js"></script>
    <script src='assets/fullcalendar/lib/moment.min.js'></script>

    <!--<script src='assets/fullcalendar/fullcalendar.min.js'></script>
    -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script src="assets/js/uikit.min.js"></script>
    <script src="assets/js/components/datepicker.min.js" async></script>

    <script src="assets/js/jquery.scrollex.min.js"></script>
    <script src="assets/js/jquery.scrolly.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

    <script type="text/javascript">
        function SetAllCheckBoxes(FormName, FieldName, CheckValue) {
            document.querySelectorAll("input[type=checkbox]").forEach(element => {
                element.checked = CheckValue;
            });
        }
    </script>
    <script>
        //helper functions
        function getDate(str) {
            const [year, month, day] = str.split("-").map(Number);
            return new Date(year, month - 1, day);
        }
        async function fetchCSV(url) {
            let response = await fetch(url);
            let text = await response.text();
            return text.split("\n").map(row => row.split(","));
        }
        //add checkboxes
        document.addEventListener("DOMContentLoaded", function () {
            let schedule = document.getElementById("scheduleinputs");
            for (let i = 1; i <= 8; i++) {
                let periodDiv = document.createElement("div");
                periodDiv.id = `period${i}container`
                periodDiv.innerHTML = `<h2><strong>Period ${i} Class:</strong></h2>` +
                    `<input type=text name='period${i}' id='period${i}' placeholder="Period ${i} Class" size=25 tabindex=3/></br>`;
                ["A", "B", "C", "D", "E", "F", "G", "H"].forEach(l => {
                    periodDiv.innerHTML += `<input type="checkbox" id="${l}-${i}" checked name="day${l}[]" value="${l}"> <label for="${l}-${i}">${l}</label>`
                });
                periodDiv.innerHTML += `</br><input type="radio" id="LW1-${i}" checked name="day${i}LW" value="LW1"> <label for="LW-${i}">Wave 1</label>` +
                    `<input type="radio" id="LW2-${i}" name="day${i}LW" value="LW2"> <label for="LW2-${i}">Wave 2</label>` +
                    `<input type="radio" id="LW3-${i}" name="day${i}LW" value="LW3"> <label for="LW3-${i}">Wave 3</label>`
                schedule.appendChild(periodDiv);
            }
        });
        //exporting
        async function exportSchedule() {
            var special_days = ["S", "SS", "SSS", "M1", "M2", "M3", "M4", "Y1", "Y2", "Y3", "Y4"];
            var exportText = "Subject,Start Date,Start Time,End Date,End Time\r\n";
            let daysData = await fetchCSV("days.csv");
            let bellsData = await fetchCSV("bells.csv");
            let startDate = getDate(document.getElementById("startdate").value)
            let endDate = getDate(document.getElementById("enddate").value);
            daysData.forEach(day => {
                if (getDate(day[0]) >= startDate && getDate(day[0]) <= endDate) {
                    console.log(day)
                    if (special_days.includes(day[1])) {
                        //handle special days
                        bellsData.filter((bell) => {
                            return (bell[0] == day[2]) && (bell[1] == day[1])
                        }).forEach((period) => {
                            if (document.getElementById("period" + period[2]).value.length > 0) {
                                exportText += `${document.getElementById("period" + period[2]).value},${day[0]},${period[3]},${day[0]},${period[4]}\r\n`
                            }
                        })
                    } else {
                        bellsData.filter((bell) => {
                            return (bell[0] == day[2]) && (bell[1] == day[1])
                        }).forEach((period) => {
                            if (document.getElementById("period" + period[2]).value.length > 0) {
                                if (document.getElementById(`${period[1]}-${period[2]}`).checked) {
                                    exportText += `${document.getElementById("period" + period[2]).value},${day[0]},${period[3]},${day[0]},${period[4]}\r\n`
                                }
                            }
                        })
                    }
                }
            });
            //exporting and filling in the calender (which doesnt work right)
            let blob = new Blob([exportText], { type: "text/csv" });
            let link = document.getElementById('downloadLink')
            link.href = URL.createObjectURL(blob);
            link.download = "schedule.csv";
            //doesnt work from here down
            const calendarAsJSON = exportText.trim().split("\r\n").map(row => row.split(","));
            var json = [];
            for (var i = 1; i < calendarAsJSON.length; i++) {
                var title = calendarAsJSON[i][0];
                var start_date = calendarAsJSON[i][1];
                var start_time = calendarAsJSON[i][2];
                var end_date = calendarAsJSON[i][3];
                var end_time = calendarAsJSON[i][4];
                json.push({
                    title: title,
                    start: start_date + "T" + start_time,
                    end: end_date + "T" + end_time
                })
            }
            console.log(json)
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridWeek',
                businessHours: {
                    start: '08:00:00',
                    end: '14:32:00',
                    dow: [1, 2, 3, 4, 5]
                },
                editable: false,
                aspectRatio: 1.0,
                height: "auto",
                weekends: false,
                allDaySlot: false,
                events: json
            });
            document.querySelector(".beginingpage").hidden = true
            document.querySelector(".endingpage").hidden = false
            calendar.render();
            $('html, body').animate({ scrollTop: 0 }, 200);
        }
    </script>
    <?php

    if (isset($_POST['submitschedule'])) {
        //let's record the access information
        //log access
        $ip = $_SERVER['REMOTE_ADDR'];
        $id = session_id();
        $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $scheduleraw = $_POST['period1'] . ":" . $_POST['period2'] . ":" . $_POST['period3'] . ":" . $_POST['period4'] . ":" . $_POST['period5'] . ":" . $_POST['period6'] . ":" . $_POST['period7'] . ":" . $_POST['period8'];
        $scheduleraw = mysqli_real_escape_string($db, strip_tags(trim($scheduleraw)));
        $logquery = "INSERT into access_log values (NULL, DATE_ADD(NOW(), INTERVAL 2 HOUR), \"" . $id . "\",\"" . $ip . "\",\"" . $hostname . "\",\"" . $scheduleraw . "\")";
        $db->query($logquery);

        //run through POST variable and get info from database
        //first get active dates
        $date_query = "select * from Days where Date between \"" . $_POST['startdate'] . "\" and \"" . $_POST['enddate'] . "\"";
        $activedates = $db->query($date_query);

        $special_days = ["S", "SS", "SSS", "M1", "M2", "M3", "M4", "Y1", "Y2", "Y3", "Y4"];

        $export_text = "";

        $export_text = "Subject,Start Date,Start Time,End Date,End Time\r\n";

        while ($single_date = $activedates->fetch_assoc()) {
            for ($i = 1; $i < 9; $i++) {
                $period = "period" . $i;
                $day = "day" . $i;
                if (isset($_POST[$period]) && (strlen($_POST[$period]) > 0)) {
                    $_POST[$period] = mysqli_real_escape_string($db, strip_tags(trim($_POST[$period])));
                    $_POST[$period] = str_replace(",", "-", $_POST[$period]);
                    if (in_array($single_date['Day'], $special_days)) {
                        //get times from database
                        $query = "SELECT * FROM Bells WHERE Day='" . $single_date['Day'] . "' AND Type='" . $single_date['Day'] . "' AND Period='" . $i . "'";
                        $stmt = $db->query($query);
                        while ($row = $stmt->fetch_assoc()) {
                            if (!is_null($row['Start'])) {
                                $export_text .= $_POST[$period] . "," . $single_date['Date'] . "," . $row['Start'] . "," . $single_date['Date'] . "," . $row['End'] . "\r\n";
                            }
                        }
                    } else {
                        for ($j = 0; $j < 10; $j++) {
                            if (isset($_POST[$day][$j]) && (strlen($_POST[$day][$j]) > 0) && ($_POST[$day][$j] == $single_date['Day'])) {
                                $query = "SELECT * FROM Bells WHERE Type = '" . $single_date['Type'] . "' AND Period = '" . $i . "' AND Day = '" . $_POST[$day][$j] . "'";
                                $stmt = $db->query($query);
                                while ($row = $stmt->fetch_assoc()) {
                                    if (!is_null($row['Start'])) {
                                        $export_text .= $_POST[$period] . "," . $single_date['Date'] . "," . $row['Start'] . "," . $single_date['Date'] . "," . $row['End'] . "\r\n";
                                        if ($row['Start'] == '11:57:00') {
                                            if ($_POST["day" . $i . "LW"] == 'LW1') {
                                                $export_text .= "Lunch," . $single_date['Date'] . ",11:57:00," . $single_date['Date'] . ",12:27:00\r\n";
                                            } else if ($_POST["day" . $i . "LW"] == 'LW2') {
                                                $export_text .= "Lunch," . $single_date['Date'] . ",12:31:00," . $single_date['Date'] . ",13:01:00\r\n";
                                            } else if ($_POST["day" . $i . "LW"] == 'LW3') {
                                                $export_text .= "Lunch," . $single_date['Date'] . ",13:05:00," . $single_date['Date'] . ",13:35:00\r\n";
                                            }
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
        //write to file as "calendar-unixTimecode.csv"
        $filename = "exported/calendar-" . time() . ".csv";
        $calfile = fopen($filename, "w");
        fwrite($calfile, $export_text);
        fclose($calfile);
        $csv = file_get_contents($filename);
        $array = array_map("str_getcsv", explode("\n", $csv));
        $json = json_encode($array);
    }
    ?>
</head>

<body>

    <div id="wrapper" class="endingpage" hidden>

        <header id="header" class="alt">
            <h1>Thanks for using the Newtown High School Calendar Generator</h1>
        </header>

        <!-- Nav -->
        <nav id="nav">
            <ul>
                <li><a href="#download" class="active">Download</a></li>
                <li><a href="#instructions">Instructions</a></li>
            </ul>
        </nav>

        <!-- Main -->
        <div id="main">

            <!-- Introduction -->
            <section id="download" class="main">
                <div class="spotlight">
                    <div class="content">
                        <header class="major">
                            <h2>Download and View your new Calendar</h2>
                        </header>

                        <ul class="actions">
                            <li><a href="" class="button special icon fa-download" id="downloadLink">Download File</a>
                            </li>
                        </ul>
                        <div class="major" id="calendar"></div>

                    </div>
                </div>
            </section>
            <section id="instructions" class="main">
                <div class="spotlight">
                    <div class="content">
                        <header class="major">
                            <h2>Use Instructions</h2>
                        </header>
                        <h3>Here are instructions for creating a new calendar in Google Calendar and importing the CSV
                            file</h3>
                        <ul>
                            <li>Open <a href="https://calendar.google.com">Google Calendar</a></li>
                            <li>Click on the "gear" in the upper right corner and access "settings" </br> <img
                                    src="images/settings.PNG" width="50%" border="0" alt=""></li>
                            <li>Under the "Add Calendar" section, select "Create new calendar" </br> <img
                                    src="images/calendartab.PNG" width="30%" border="0" alt=""></li>
                            <li>Create a new calendar by entering a calendar name and clicking on "Create Calendar"
                                (you
                                can import the file directly into your main calendar <em>but</em> if you create a
                                new
                                calendar you can always delete it or share it without affecting your other
                                events)</li>
                            <li>On the left side of the screen select "Import & export" </br> <img
                                    src="images/import.png" width="25%" border="0" alt=""></li>
                            <li>Select the CSV file that you downloaded, select your new calendar, and then click
                                "Import" </br> <img src="images/finalstep.png" width="50%" border="0" alt=""></li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Wrapper -->
    <div id="wrapper" class="beginingpage">

        <!-- Header -->
        <header id="header">
            <span class="logo"><img src="images/logo.png" width="10%" alt="" /></span>
            <h1>Welcome to the Newtown High School Calendar Generator</h1>
            <p>Maintained by NHS Tech Team</p>
        </header>

        <!-- Nav -->
        <nav id="nav">
            <ul>
                <li><a href="#intro" class="active">Instructions</a></li>
                <li><a href="#cta">Build Schedule</a></li>
            </ul>
        </nav>

        <!-- Main -->
        <div id="main">

            <!-- Introduction -->
            <section id="intro" class="main">
                <div class="spotlight">
                    <div class="content">
                        <header class="major">
                            <h2>Welcome to the Newtown High School Calendar Generator</h2>
                        </header>
                        <p>This program will generate a CSV file (suitable for import to Google Calendar) that
                            recognizes
                            Letter Day Designations, Half Days and even Finals.</p>
                        <p>
                            How to use it:
                        <ul>
                            <li>Be creative. Do it in pieces and establish a calendar for each course or section to be
                                shared with others.
                            </li>
                            <li>In the "Start Date" field, enter the date you would like the calendar information to
                                begin
                                on. Appropriate format is YYYY-MM-DD.
                            </li>
                            <li>In the "End Date" field, enter the date you would like the calendar information to end
                                on.
                                Appropriate format is YYYY-MM-DD. (Perhaps you have semester courses and need to run
                                this
                                twice.)
                            </li>
                            <li>In the "Period # Class" field, enter the name of the course, the duty, or whatever other
                                "period regular" activity you have (do not include commas - commas will be replaced with
                                dashes).
                            </li>
                            <li>Leave all of the Letter boxes checked if this event occurs on every letter day (don't
                                worry
                                about days that drop - the program knows which letter day it is). If an event occurs
                                only on
                                specific Letter days (like science lab), just leave those boxes checked.
                            </li>
                            <li>Click "Submit Schedule". Your CSV file will be created and you will see instructions on
                                how
                                to set up your Google calendar
                            </li>
                        </ul>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Get Started -->
            <section id="cta" class="main special">
                <form name="schedule" id="schedule" action=<?php echo $_SERVER['PHP_SELF'] ?> method="post">
                    <div class="row uniform">
                        <div class="6u 12u$(small)">Start Date: <input
                                data-uk-datepicker="{minDate:'2024-09-02', maxDate:'2025-06-13'}" type=text
                                name='startdate' id='startdate' size=50% value='2024-09-03' tabindex=1></div>
                        <div class="6u$ 12u$(small)">End Date: <input
                                data-uk-datepicker="{minDate:'2025-09-02', maxDate:'2025-06-13'}" type=text
                                name='enddate' id='enddate' size=50% value='2025-06-12' tabindex=2></div>
                    </div>
                    </br>
                    <div id="scheduleinputs"></div>
                    <input type="button"
                        onclick="SetAllCheckBoxes('schedule', 'day1[]', false);SetAllCheckBoxes('schedule', 'day2[]', false);SetAllCheckBoxes('schedule', 'day3[]', false);SetAllCheckBoxes('schedule', 'day4[]', false);SetAllCheckBoxes('schedule', 'day5[]', false);SetAllCheckBoxes('schedule', 'day6[]', false);SetAllCheckBoxes('schedule', 'day7[]', false);SetAllCheckBoxes('schedule', 'day8[]', false);"
                        value="Uncheck All">&nbsp;&nbsp;<input type="button"
                        onclick="SetAllCheckBoxes('schedule', 'day1[]', true);SetAllCheckBoxes('schedule', 'day2[]', true);SetAllCheckBoxes('schedule', 'day3[]', true);SetAllCheckBoxes('schedule', 'day4[]', true);SetAllCheckBoxes('schedule', 'day5[]', true);SetAllCheckBoxes('schedule', 'day6[]', true);SetAllCheckBoxes('schedule', 'day7[]', true);SetAllCheckBoxes('schedule', 'day8[]', true);"
                        value="Check All">&nbsp;&nbsp;
                    </br></br>
                    <input type=button onclick='exportSchedule()' id='submitschedule' name='submitschedule'
                        value='Submit Schedule' tabindex=10 />
                </form>
                <p>Currently Supporting Schedules for the 2024-2025 School Year</p>
                <p>Looking for a new maintainer, contact <a
                        href="mailto:calendarmaker@devinmatte.com?subject=Calendar Maker">Devin Matte</a></p>
            </section>

        </div>

        <!-- Footer -->
        <footer id="footer">
            <p class="copyright">&copy; 2014-2025 <a href="https://devinmatte.com">Devin Matte</a>
                </br>
                Design: <a href="https://github.com/devinmatte">Devin Matte</a> - Initial Design: <a
                    href="https://twitter.com/charlesdumais">Charles Dumais</a>
                </br>
                CSS Template: <a href="https://html5up.net">HTML5 UP</a>
                </br>
                <a href="https://github.com/devinmatte/Calendar-Maker" title="GitHub" class="icon fa-github"><span
                        class="label">GitHub</span>
                </a>
            </p>
        </footer>

    </div>

</body>

</html>