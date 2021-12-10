// function dropdown() {
//   var acc = document.getElementsByClassName("accordion");
//   //  var i = 0;
//   acc[0].classList.toggle("active");

//   let panel = document.getElementById("myPanel");
//   if (panel.style.maxHeight) {
//     panel.style.maxHeight = "0px";
//   } else {
//     panel.style.maxHeight = panel.scrollHeight + "px";
//   }

//   acc[0].addEventListener("click", function () {
//     //       /* Toggle between adding and removing the "active" class,
//     //     to highlight the button that controls the panel */

//     /* Toggle between hiding and showing the active panel */
//     var panel = this.nextElementSibling;
//     if (panel.style.maxHeight) {
//       panel.style.maxHeight = null;
//     } else {
//       panel.style.maxHeight = panel.scrollHeight + "px";
//     }
//   });
// }
function drop() {
  var element = document.getElementById("myPanel");

  if (!element.classList.contains("panelOpen")) {
    element.classList.add("panelOpen");
  } else {
    element.classList.remove("panelOpen");
  }
}
