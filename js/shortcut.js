// keyboard shortcut functionality ........
$(document).keydown(function (e) {
    // h = 72
    if (e.keyCode == 72 && e.ctrlKey) {
        e.preventDefault();
        location.replace("home.php");
        return;
    }

    // i = 73
    if (e.keyCode == 73 && e.ctrlKey) {
        e.preventDefault();
        location.replace("dataInput.php");
        return;
    }

    // 0 = 79
    if (e.keyCode == 79 && e.ctrlKey) {
        e.preventDefault();
        location.replace("overview.php");
        return;
    }

    // 88 = x logout
    if (e.keyCode == 88 && e.ctrlKey) {
        e.preventDefault();
        location.replace("logout.php");
        return;
    }

    // 68 = d logout
    if (e.keyCode == 68 && e.ctrlKey) {
        e.preventDefault();
        location.replace("detail.php");
        return;
    }
});
