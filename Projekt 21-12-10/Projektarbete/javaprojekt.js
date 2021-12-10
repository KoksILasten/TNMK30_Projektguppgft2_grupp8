// function dropdown() {
  // var acc = document.getElementsByClassName("accordion");
   // var i = 0;
  // acc[0].classList.toggle("active");

  // let panel = document.getElementById("myPanel");
  // if (panel.style.maxHeight) {
    // panel.style.maxHeight = "0px";
  // } else {
    // panel.style.maxHeight = panel.scrollHeight + "px";
  // }

  // acc[0].addEventListener("click", function () {
          /* Toggle between adding and removing the "active" class,
        to highlight the button that controls the panel */

    // /* Toggle between hiding and showing the active panel */
    // var panel = this.nextElementSibling;
    // if (panel.style.maxHeight) {
      // panel.style.maxHeight = null;
    // } else {
      // panel.style.maxHeight = panel.scrollHeight + "px";
    // }
  // });
// }
function drop(){
	var element = document.getElementById("myPanel");
	let panel = document.getElementById("myPanel");
	
	if(!element.classList.contains("panelOpen")){
		element.classList.add("panelOpen");
		panel.style.maxHeight = panel.scrollHeight + "px";
	}else{
		element.classList.remove("panelOpen");
		panel.style.maxHeight = null;
	}
}

function required_fill() {
  var x = document.getElementById("userinput").required;
}