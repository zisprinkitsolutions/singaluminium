@extends('layouts.backend.app')
@push('css')
    @include('layouts.backend.partial.style')
 <!-- Load JSpreadsheet -->
<link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v4/jexcel.css" />
<script src="https://bossanova.uk/jspreadsheet/v4/jexcel.js "></script>
<link rel="stylesheet" href="https://jsuites.net/v5/jsuites.css" />
<script src="https://jsuites.net/v5/jsuites.js"></script>

<!-- Load Frappe Gantt -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/1.0.0/frappe-gantt.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/1.0.0/frappe-gantt.umd.js"></script>

    <style>
        body {
        font-family: Arial, sans-serif;
        font-size: 14px;
        }

        p {
        margin-bottom: 5px;
        margin-right: 40px;
        }

        a {
        text-decoration: none;
        color: blue;
        }

        .link-list {
            margin-top: 5px;
            padding-left: 30px;
        }

        h3 {
        font-size: 1.3rem;
        font-weight: bold;
        color: darkblue;
        margin-bottom: 0px;
        }


        .calendar-section {
        flex-shrink: 0; /* Prevents stretching */
        margin-top: 20px;
        margin-bottom: 15px;
        }


        .left-panel {
            min-width: 400px;
            max-width: 40%;
            display: flex;
            flex-direction: column;
            overflow: auto;
            position: relative;
        }

        .resize-handle {
        width: 24px;
        height: 100%;
        position: absolute;
        right: 0;
        top: 0;
        cursor: ew-resize;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(to right, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 1),  rgba(255, 255, 255, 1));
        box-shadow: -4px 0 6px rgba(0, 0, 0, 0.04);
        z-index: 10;
        }

        /* Subtle vertical line with gradient */
        .resize-handle::before {
        content: "";
        width: 2px;
        height: 100%;
        background: linear-gradient(to bottom, rgba(204, 204, 204, 0.1), rgba(51, 51, 51, 0.25), rgba(204, 204, 204, 0.1));
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        }

        /* Visible grip in the center */
        .resize-handle::after {
        content: "";
        width: 4px;
        height: 24px;
        background: repeating-linear-gradient(
            to bottom,
            rgba(0, 0, 0, 0.3),
            rgba(0, 0, 0, 0.3) 2px,
            transparent 2px,
            transparent 4px
        );
        position: absolute;
        left: 50%;
        transform: translateX(-50%);

        opacity: 0.7;
        }

        .right-panel {
            flex: 1; /* Takes half of the space */
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #spreadsheet {
            width: 100%;
            height: autopx;
            margin-bottom: 15px;
        }

        #gantt {
            width: 100%;
            height: auto !important; /* Let it expand naturally */
            overflow-y: hidden; /* Hide vertical scrolling */
            user-select: none; /* Disable text selection */
        }

        /* Prevent the vertical scrollbar that would appear otherwise */
        .gantt-container {
        padding-bottom: 20px;
        overflow-y: hidden !important;
        }

        .bar {
        fill: #fafafa !important;
        }

        .bar-started .bar {
        fill: #fed170 !important;
        }

        .bar-late .bar {
        fill: lightcoral !important;
        }

        .bar-progress {
        fill: lightblue !important;
        }

        .bar-complete .bar-progress {
        fill: lightgreen !important;
        }

        .handle {
        pointer-events: none;
        opacity: 0.5;
        }

        /* Custom styles for spreadsheet */
        .jexcel {
        font-size: 13px !important;
        }

        .jexcel td {
        white-space: normal !important; /* Allow text to wrap */
        word-wrap: break-word !important; /* Ensure words break and wrap within the cell */
        }
        .jexcel > thead > tr > td {
        font-weight: bold;
        font-size: 13px;
        background-color: #d0e7f4 !important;
        }
        .jexcel > tbody > tr > td:first-child,
        .jexcel > thead > tr > td:first-child {
        font-size: 11px;
        text-align: center;
        vertical-align: middle;
        color: #555555;
        }

        .jexcel colgroup col:first-child {
        width: 30px;
        }

        .jexcel thead {
        height: 44px;
        }

        .jexcel tbody tr {
        height: 24px;
        }

    </style>
@endpush
@section('content')
    <div class="app-content content print-hideen">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-body">
                @include('clientReport.project._header')

                <div class="tab-content bg-white">

                    <div class="d-flex justify-content-between" style="padding:10px;">
                        <div class="left-panel">
                            <h3 style="color: #313131;font-weight:400;">Calendar and Activities</h3>
                            <div class="calendar-section d-flex align-items-center">

                                <input class="form-control form-control-sm datepicker" style="width:130px;" type="text" id="start" value="{{date('d/m/Y')}}"/>
                                <button id="updateAllButton" class="ml-2">Update All Dates</button>
                            </div>

                            <div id="spreadsheet"></div>
                        </div>

                        <div class="right-panel ml-1">
                            <h3>Gantt Chart</h3>
                            <br>
                            <div id="gantt"></div>
                            <br>
                            <div style="margin-top:10px; width:720px;">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('js')

<script>
document.addEventListener("DOMContentLoaded", function () {
  const updateAllButton = document.getElementById("updateAllButton");
//   updateAllTaskDates("{{ date('Y-m-d') }}");
  const spreadsheetContainer = document.getElementById("spreadsheet");
  const startDateInput = document.getElementById("start"); // Get calendar input
  let isUpdating = false; // Prevent infinite loops during updates
  let isDurationUpdating = false; // Flag to prevent infinite loop on duration change

  let gantt; // Store Gantt instance globally

  let ganttUpdateTimeout;

  function updateAllTaskDates(selectedDate) {
    const data = spreadsheet.getData().filter((row) => row[0] !== ""); // Skip empty ID rows
    for (let rowIndex = 0; rowIndex < data.length; rowIndex++) {
      updateTaskDates(rowIndex, selectedDate);
    }
    // Redraw Gantt chart after all tasks are updated
    debounceGanttUpdate();
  }

  if (updateAllButton) {
    updateAllButton.addEventListener("click", function () {
       const [day, month, year] = startDateInput.value.split('/');
        var date =  `${year}-${month}-${day}`;
        updateAllTaskDates(date);
    });
  }
    const chartData = @json($chart->items);

    const taskData = [];
    taskData.push(["-", "-", "Start ◆", 0, 100,"- ", "-",'-']);

    chartData.forEach(task => {
        taskData.push([
            task.priority,
            task.assign_by,
            task.name,
            task.total_day,
            task.progress,

            task.start_date,  // make sure these fields exist in your $chart->items
            task.end_date,
            task.color,
        ]);
    });


  const spreadsheet = jspreadsheet(spreadsheetContainer, {
    data: taskData,
    tableOverflow: true,
    tableHeight: "510px",
    columns: [
      { type: "text", title: "Priority", width: 55 },
      { type: "text", title: "Assign To", width: 95 },
      { type: "text", title: "Tasks", width: 180, align: "left" },
      { type: "number", title: "Duration (days)", width: 65, wrap: true },
      { type: "number", title: "Progress (%)", width: 65 },
    //   { type: "text", title: "Color", width: 65 },
      {
        type: "calendar",
        title: "Start Date",
        width: 80,
        options: { format: "DD/MM/YYYY" },
        readOnly: true
      },
      {
        type: "calendar",
        title: "End Date",
        width: 80,
        options: { format: "DD/MM/YYYY" },
        readOnly: true
      },{
        type: "text", title: "Color", width: 65
      }
    ],
    allowInsertRow: true,
    allowDeleteRow: true,
    allowInsertColumn: false,
    allowDeleteColumn: false,
    editable: true,
    columnSorting: false,
    updateTable: function (instance, cell, col, row, val, label, cellName) {
      if (col === 0 && (val === "Low")) {
        cell.style.color = "green";
        cell.style.fontWeight = "bold";
      }else if(col === 0 && (val === "Medium")){
        cell.style.color = "blue";
        cell.style.fontWeight = "bold";
      }else if(col === 0 && (val === "High")){
        cell.style.backgroundColor = "#f8d7da";
        cell.style.color = "red";
      }else if (col === 1 && val === "Start") {
        cell.style.color = "blue";
        cell.style.fontWeight = "bold";
        cell.style.fontStyle = "italic";
      } else {
        cell.style.color = "";
        cell.style.fontWeight = "";
        cell.style.fontStyle = "";
      }
    },
    onchange: function (worksheet, cell, x, y, value) {
      const id = spreadsheet.getValueFromCoords(0, y);
      const predecessor = spreadsheet.getValueFromCoords(1, y);
      let duration = spreadsheet.getValueFromCoords(3, y); // Capture duration change
      let progress = spreadsheet.getValueFromCoords(4, y); // Capture progress change

      // Prevent infinite loop on duration update
      if (x == 3 && !isDurationUpdating) {
        isDurationUpdating = true;
        duration = Math.round(duration); // Round to nearest integer
        spreadsheet.setValueFromCoords(3, y, duration, true); // Update duration in spreadsheet
        isDurationUpdating = false; // Reset flag
      }

      // If progress exceeds 100%, set it to 100%
      if (x == 4 && progress > 100) {
        progress = 100;
        spreadsheet.setValueFromCoords(4, y, progress, true); // Update progress in spreadsheet
      }

      // Update all task dates when ID, Predecessor, or Duration are changed
      if (x == 0 || x == 1 || x == 3) {
        updateAllTaskDates(startDateInput.value);
      }

      // If Name or progress are changed, update the Gantt chart
      if (x == 2 || x == 4) {
        debounceGanttUpdate();
      }
    }
  });

  // Get the total number of rows
  let totalRows = spreadsheet.getData().length;
  spreadsheet.insertRow(2, totalRows); // Insert rows at the end

  // Preselect a range of cells
  setTimeout(() => {
    spreadsheet.updateSelectionFromCoords(0, 0, 4, totalRows - 1); // Select from A1 to D4
  }, 100);

  // Set default date on first load
  const setDay = new Date();
  setDay.setDate(setDay.getDate() - 25); // Subtract 25 days
  const formattedDate = setDay.toISOString().split("T")[0]; // Format the date to YYYY-MM-DD
  updateAllTaskDates(setDay);

  function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
  }

  function updateTaskDates(rowIndex, baseDate) {
    if (isUpdating) return;
    isUpdating = true;

    const data = spreadsheet.getData();
    const row = data[rowIndex];
    const id = row[0]; // ID column
    const predecessors = row[1] ? row[1].split(",").map((p) => p.trim()) : [];
    const duration = parseInt(row[3], 10) || 0;

    let latestEndDate = null;

    if (predecessors.length > 0 && predecessors[0] !== "-") {
      for (let pred of predecessors) {
        const predIndex = data.findIndex((r) => r[0] === pred);
        if (predIndex !== -1) {
          const predEndDate = new Date(data[predIndex][6]);
          if (!latestEndDate || predEndDate > latestEndDate) {
            latestEndDate = predEndDate;
          }
        }
      }
    }

    if (id != "") {
      let startDate = latestEndDate
        ? new Date(latestEndDate)
        : baseDate
        ? new Date(baseDate)
        : new Date();

      spreadsheet.setValueFromCoords(5, rowIndex, formatDate(startDate), true); // Start Date column index

      const endDate = new Date(startDate);
      endDate.setDate(startDate.getDate() + duration);

      spreadsheet.setValueFromCoords(6, rowIndex, formatDate(endDate), true); // End Date column index
    }
    isUpdating = false;
  }

  function debounceGanttUpdate() {
    clearTimeout(ganttUpdateTimeout);
    ganttUpdateTimeout = setTimeout(() => {
      redrawGanttChart();
    }, 100);
  }

  // Function to redraw Gantt chart
  function redrawGanttChart() {
        const data = spreadsheet.getData().filter((row) => row[0] !== ""); // Filter out empty ID rows
        const ganttTasks = data.map((row) => {
        const taskStart = new Date(row[5]); // Start date is now at index 5
        const taskEnd = new Date(row[6]); // End date is now at index 6
        let taskDependencies = row[1]
            .split(",")
            .map((dep) => dep.trim())
            .filter(Boolean); // Dependencies at index 1
        let taskProgress = Math.min(100, row[4]); // Progress at index 4

        const isMilestone = taskStart.getTime() === taskEnd.getTime();

        // Use ID as name if description is empty, except for Start and Finish
        let taskName = row[2].trim() || (row[0] !== "Start" && row[0] !== "Finish" ? row[0] : "");
        if (isMilestone) taskName = "◆ " + taskName;

        let customClass = "bar-progress";
        if (taskStart < new Date()) customClass = "bar-started";
        if (taskEnd < new Date()) customClass = "bar-late";
        if (taskProgress === 100) customClass = "bar-complete";

        return {
            id: row[0],
            name: taskName,
            start: row[5],
            end: row[6],
            progress: row[4],
            dependencies: taskDependencies,
            custom_class: customClass
        };
    });

    if (!gantt) {
      gantt = new Gantt("#gantt", ganttTasks, {
        view_mode: "Day",
        column_width: 20,
        bar_height: 25,
        on_progress_change: function (task, progress) {
          const taskIndex = spreadsheet
            .getData()
            .findIndex((row) => row[0] === task.id);
          if (taskIndex !== -1) {
            spreadsheet.setValueFromCoords(4, taskIndex, progress);
          }
        }
      });
    } else {
      gantt.refresh(ganttTasks); // Instead of destroying and recreating the Gantt chart
    }


    centerTodayInGantt();
    disableDragAndResize();
  }

  // Function to disable dragging and resizing on Gantt chart tasks
  function disableDragAndResize() {
    // Disable dragging of tasks
    document.querySelectorAll(".bar-wrapper").forEach(function (barWrapper) {
      barWrapper.addEventListener("mousedown", function (e) {
        e.preventDefault();
        e.stopPropagation();
      });
    });

    // Disable resizing of tasks
    document.querySelectorAll(".handle").forEach(function (handle) {
      handle.addEventListener("mousedown", function (e) {
        e.preventDefault();
        e.stopPropagation();
      });
    });

  }

  // We set the Gantt chart with current date in the center
  function centerTodayInGantt() {
    requestAnimationFrame(() => {
      const currentBall = document.querySelector(".current-ball-highlight");
      if (currentBall) {
        currentBall.scrollIntoView({ inline: "center", behavior: "smooth" });
      }
    });
  }

  // Center the view on today's date
  centerTodayInGantt();

  // Draggable edge (handle)
  const panel = document.querySelector('.left-panel');
	const handle = document.querySelector('.resize-handle');

	handle.addEventListener('mousedown', (event) => {
		event.preventDefault();
		document.addEventListener('mousemove', resize);
		document.addEventListener('mouseup', () => {
			document.removeEventListener('mousemove', resize);
		});
	});

	function resize(event) {
		const newWidth = event.clientX - panel.getBoundingClientRect().left;
		panel.style.width = `${Math.max(400, Math.min(newWidth, window.innerWidth * 0.6))}px`;
	}
});

</script>
@endpush
