// JavaScript code for tab switching
function openTab(tabName) {
    var i, tabContent;
    tabContent = document.getElementsByClassName("tab");
    // Hide all tab content
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }
    // Show the selected tab content
    document.getElementById(tabName).style.display = "block";
    }


