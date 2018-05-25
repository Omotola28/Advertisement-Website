
/**
 * Toggles the admin panels divs to show pages without refreshing page
 */
let adminPanels = ["adminAdList", "adminUsersList"];
let watchListPanels = ["sections-WL","my-Details", "section-List"];
let visibleDivId = null;
function displayPanels(divId) {
    if(visibleDivId === divId) {
        visibleDivId = null;
    } else {
        visibleDivId = divId;
    }
    if(watchListPanels.indexOf(divId) !== -1){
        hideNonVisibleDivs(watchListPanels);
    }else {
        hideNonVisibleDivs(adminPanels);
    }

}

/**
 * Hides shown divs
 * @param panel the panels to toggle 
 */
function hideNonVisibleDivs(panel) {
    let i, divId, div;
    for(i = 0; i < panel.length; i++) {
        divId = panel[i];
        div = document.getElementById(divId);
        if(visibleDivId === divId) {
            div.style.display = "block";
        } else {
            div.style.display = "none";
        }
    }
}

/**
 * Reload captcha
 */
function reloadCaptcha() {
    let captcha = document.getElementById('captcha');
    let date = new Date();
    captcha.src = "captcha.php?" + date.getTime();
    return false;
}